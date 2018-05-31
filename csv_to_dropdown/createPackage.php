<?php
/*
Author: Reagan Wilkins
reagan.wilkins@gmail.com
github.com/Rwilkins1
*/

class packageCreator
{

  function getInput($message)
  {
    echo $message . PHP_EOL;
    $handle = fopen("php://stdin", "r");
    $input = trim(fgets($handle));
    fclose($handle);
    return $input;
  }

  public function checkDirectory()
  {
    // Check for package directory, creates one if doesn't exist.
    if(file_exists("package")) {
      return true;
    } else {
      mkdir("package");
      return true;
    }
  }

  public function checkFiles()
  {
    // Check for files, remove if exists
    $packageItems = glob('package/{,.}*', GLOB_BRACE);
    foreach($packageItems as $item) {

      if($item !== "package/." && $item !== "package/..") {
        if(is_file($item)) {
          echo $item . " is a file" . PHP_EOL;
          unlink($item);
        }
        else if(is_dir($item)) {
          echo $item . " is a directory" . PHP_EOL;
          $handle = opendir($item);

          while($file = readdir($handle)) {
            if($file != "." && $file != "..") {
              if(!is_dir($item."/".$file)) {
                echo $file . " is a file" . PHP_EOL;
                unlink($item."/".$file);
              }
              else {
                delete_directory($item."/".$file);
              }
            }
          }

          closedir($handle);
          rmdir($item);
        }
      }
    }
  }

  public function setUp()
  {
    // Check if preparations need to be made before building the package
    $this->checkDirectory();
    $this->checkFiles();
    return true;
  }

  public function addFiles($files)
  {
    // Add files created by script to package directory, add to an array for manifest
    $manifestArray = array();
    foreach($files as $file => $path) {
      copy($file, "package/".$file);
    }
    return true;
  }

  public function buildManifest($files)
  {
    // Accept user input and the list of files to build the manifest.php file.
    $manifestHandle = fopen("package/manifest.php", "w");
    $code = "<?php
    ".'$manifest'." = array(";

    $versions = $this->getInput("What exact sugar versions are acceptable for this package (separate with commas)?");
    $versions = explode(", ", $versions);
    $code .= "
    'acceptable_sugar_versions' => array(
      'exact_matches' => array(
        ";
    foreach($versions as $version) {
      $code .= "'{$version}', ";
    }
    $code = substr($code, 0, -2);
    $code .= "
      ),
    ),";

    $flavors = $this->getInput("What exact sugar flavors (ENT, PRO, etc) are acceptable for this package (separate with commas)?");
    $flavors = explode(", ", $flavors);
    $code .= "
    'acceptable_sugar_flavors' => array (";
    foreach($flavors as $flavor) {
      $code .= "'{$flavor}', ";
    }
    $code = substr($code, 0, -2);
    $code .= "),";

    $author = $this->getInput("Who is the author of this package?");
    $code .= "
    'author' => '{$author}',";

    $description = $this->getInput("Enter a brief description of what this package does");
    $code .= "
    'description' => '{$description}',
    'is_uninstallable' => true,";

    $name = $this->getInput("Enter the name of the package");
    $now = gmdate('Y-m-d h:m:s');
    $code .= "
    'name' => '{$name}',
    'published_date' => '{$now}',
    'type' => 'module',";

    $version = $this->getInput("What is this package's version?");
    $code .= "
    'version' => '{$version}',
  );";

    $id = $this->getInput("Finally, enter the ID of this package");
    $code .="\n".'$installdefs'." = array(
    'id' => '{$id}',
    'copy' => array(";

    foreach($files as $file => $path) {
      $code .= "
      array(
          'from' => '<basepath>/package/{$file}',
          'to' => '{$path}{$file}',
      ),";
    }
    $code .= "
    ),
  );";
    fwrite($manifestHandle, $code);
    $this->zipDirectory($id, $version);
  }

  public function zipDirectory($id, $version)
  {
    // Creates the package zip file
    $path = realpath('package');

    $zip = new ZipArchive();
    $zip->open("{$path}/{$id}.{$version}.zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);

    $files = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($path),
      RecursiveIteratorIterator::LEAVES_ONLY
    );
    // $files = glob('package/{,.}*', GLOB_BRACE);
    foreach($files as $name => $file) {
      if(!$file->isDir()) {
        $file = basename($file);
        echo $file . PHP_EOL;
        // $filePath = $file->getRealPath();
        // $relativepath = substr($filePath, strlen($path) + 1);

        $zip->addFile("package/{$file}");
      }
    }
    $zip->close();
  }
}
?>