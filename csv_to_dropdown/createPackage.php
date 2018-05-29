<?php
/*
Author: Reagan Wilkins
reagan.wilkins@gmail.com
github.com/Rwilkins1
*/

class packageCreator
{

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
        } else if(is_dir($item)) {
          echo $item . " is a directory" . PHP_EOL;
          $handle = opendir($item);

          while($file = readdir($handle)) {
            if($file != "." && $file != "..") {
              if(!is_dir($item."/".$file)) {
                echo $file . " is a file" . PHP_EOL;
                unlink($item."/".$file);
              } else {
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
  }

  public function buildManifest($files)
  {
    // Accept user input and the list of files to build the manifest.php file.
  }

  public function zipDirectory()
  {
    // Creates the package zip file
  }
}
?>