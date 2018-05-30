<?php
/*
Author: Reagan Wilkins
reagan.wilkins@gmail.com
github.com/Rwilkins1
*/

class packageCreator
{

  function getInput()
  {
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

    echo "What exact sugar versions are acceptable for this package (separate with commas)?" . PHP_EOL;
    $versions = $this->getInput();
    $code .= "
    'acceptable_sugar_versions' => array(
      'exact_matches' => array(";

    $versions = explode(",", $versions);
    foreach($versions as $version) {
      $version = trim($version);
      $code .= "
      '{$version}',";
    }
    substr($code, 0, -1);
    $code .= "
      ),
    ),";

    echo "What exact sugar flavors (ENT, PRO, etc) are acceptable for this package (separate with commas)?" . PHP_EOL;
    $flavors = $this->getInput();

    $code .= "
    'acceptable_sugar_flavors' => array (";

    $flavors = explode(",", $flavors);
    foreach($flavors as $flavor) {
      $flavor = trim($flavor);
      $code .= "'{$flavor}', ";
    }
    substr($code, 0, -2);
    $code .= "),";
    //
    // echo "Who is the author of this package?" . PHP_EOL;
    // $author = $this->getInput();
    //
    // echo "Enter a brief description of what this package does" . PHP_EOL;
    // $description = $this->getInput();
    //
    // echo "Enter the name of the package" . PHP_EOL;
    // $name = $this->getInput();
    //
    // echo "What is this package's version?" . PHP_EOL;
    // $version = $this->getInput();

    // echo "Finally, enter the ID of this package" . PHP_EOL;

    fwrite($manifestHandle, $code);
  }

  public function zipDirectory()
  {
    // Creates the package zip file
  }
}
?>