<?php
/*
Author: Reagan Wilkins
reagan.wilkins@gmail.com
github.com/Rwilkins1
02/10/2020

Creates a bare bones CRUD application, 
eliminating the need to manually create a file for each step.
*/

// prompts the script's user for an answer
function getInput($message)
{
	echo $message . PHP_EOL;
	$handle = fopen("php:stdin", "r");
	$input = trim(fgets($handle));
	fclose($handle);
	return $input;
}

// checks for and creates (if needed) the db, models, public, utils, views directories
function checkForSubDirectories($directory)
{

}

// controls the cheking process for the db, models, public, utils, views directories
function subDirectoryController()
{
	$directoriesArray = ['db', 'models', 'public', 'utils', 'views'];
	foreach($directoriesArray as $directory) {
		checkForSubDirectories($directory);
	}
}

// If a directory is needed, builds it, if not, adds files to existing one
function buildAddDirectory($exists, $name)
{
	if($exists) {
		if(!file_exists($name)) {
			return false;
		} else {
			define('DIRECTORY', $name);
			if(subDirectoryController()) {
				return true;				
			} else {
				return false;
			}
		}
	} else {
		if(mkdir($name)) {
			define('DIRECTORY', $name);
			if(subDirectoryController()) {
				return true;				
			} else {
				return false;
			}

		} else {
			return false;
		}
	}
}

// builds the model file for the specified module
// function buildModuleModel($module)
// {

// }

// // builds the actual code for base files such as db, login, etc.
// function buildBaseFileCode($directory, $file)
// {
// 	if($directory == "db") {

// 	} else if($directory == "models") {

// 	} else if($directory == "public") {

// 	} else if($directory =="utils") {

// 	}
// }

// // builds files such as the db, login, base model
// function buildBaseFiles($module)
// {
// 	$baseFileArray = ['db' => 'login.php', 'db' => 'connect.php', 'db' => 'hashedpw.php',
// 					  'models' => 'Basemodel.php',
// 					  'public' => 'auth.login.php', 'public' => 'auth.logout.php',
// 					  'utils' => 'Auth.php', 'utils' => 'Input.php'];
// 	foreach($baseFileArray as $directory => $file) {
// 		buildBaseFileCode($directory, $file);		
// 	}
// 	buildModuleModel($module);
// }

// builds the specific code for each file
function buildCode($module, $file)
{
	$code = "<?php 
	session_start();
	require_once '../utils/Input.php';
	require_once '../utils/Auth.php';
	require_once '../models/".$module.".php';
	require_once '../models/Basemodel.php';
	";
}

// creates the file in question
function buildFile($module, $file)
{
	$fh = fopen(DIRECTORY . "/{$module}.{$file}.php", 'w');
	if($fh === false) {
		die("File Handle is false. Please check filepath!");
	}
	$code = buildCode($module, $file);
	fwrite($fh, $code);
	fclose($fh);
}

// checks if the user wants to create CRUD for another module
function checkForAnotherModule()
{
	$anotherModule = getInput("Would you like to create another module (1), or not (0)?");
	if($anotherModule) {
		$module = getInput("Enter the name of the module you wish to build");
		buildBaseFiles($module);
	} else {
		die("Thanks for using CRUDbubble!" . PHP_EOL);
	}
}

// function that calls the function to build specific files
function crudController($module)
{
	$fileArray = ['create', 'visit', 'show', 'edit'];
	foreach($fileArray as $file) {
		buildFile($module, $file);
	}
	checkForAnotherModule();
}


// main function that drives the script
function kickstartProcess()
{
	$exists = getInput("Create new directory (0), or use existing directory (1)?");
	$name = getInput("Enter name of directory");
	$directorySet = buildAddDirectory($exists, $name);
	$module = getInput("Enter the name of the module you wish to build");
	if($directorySet) {
		crudController($module);
	} else {
		die("Directory could not be validated or created" . PHP_EOL);
	}
}

kickstartProcess();



?>