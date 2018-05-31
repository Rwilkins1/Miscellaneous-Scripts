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
    $code .= "
    'acceptable_sugar_versions' => array(
      'exact_matches' => array(
        {$versions}
      ),
    ),";

    $flavors = $this->getInput("What exact sugar flavors (ENT, PRO, etc) are acceptable for this package (separate with commas)?");
    $code .= "
    'acceptable_sugar_flavors' => array ({$flavors}),";
    //
    // $author = $this->getInput("Who is the author of this package?");
    //
    // $description = $this->getInput("Enter a brief description of what this package does");
    //
    // $name = $this->getInput("Enter the name of the package");
    //
    // $version = $this->getInput("What is this package's version?");
    // 
    // $id = $this->getInput("Finally, enter the ID of this package");

    fwrite($manifestHandle, $code);
  }

  public function zipDirectory()
  {
    // Creates the package zip file
  }
}
?>