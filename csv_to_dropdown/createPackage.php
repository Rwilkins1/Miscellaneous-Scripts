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
  }

  public function checkZipFile()
  {
    // Check for a zip file, remove if exists
  }

  public function setUp()
  {
    checkDirectory();
    checkZipFile();
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