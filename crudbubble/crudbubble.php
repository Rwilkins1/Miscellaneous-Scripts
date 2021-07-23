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
	fwrite(STDOUT, $message);
	$input = trim(fgets(STDIN));
	return $input;
}

// // checks for and creates (if needed) the db, models, public, utils, views directories
// function checkForSubDirectories($directory)
// {

// }

// // controls the cheking process for the db, models, public, utils, views directories
// function subDirectoryController()
// {
// 	$directoriesArray = ['db', 'models', 'public', 'utils', 'views'];
// 	foreach($directoriesArray as $directory) {
// 		checkForSubDirectories($directory);
// 	}
// }

// If a directory is needed, builds it, if not, adds files to existing one
function buildAddDirectory($exists, $name)
{
	echo $exists . PHP_EOL;
	if($exists == 1) {
		echo "Looking for directory $name..." . PHP_EOL;
		if(is_dir($name)) {
			define('DIRECTORY', $name);
			return true;
		} else {
			echo "Can't find the directory" . PHP_EOL;
			return false;
		}
	} else {
		if(mkdir($name)) {
			define('DIRECTORY', $name);
			return true;
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
function buildCode($module, $file, $fields)
{
	$fieldIndex = 1;
	$firstChar = substr($module, 0);
	if($firstChar == "A" || $firstChar == "E" || $firstChar == "I" || $firstChar == "O" || $firstChar == "U") {
		$aan = "an";
	} else {
		$aan = "a";
	}
	$code = "
	<?php 
		session_start();
	?>

	<!DOCTYPE html>
	<html lang='en'>
		<body>";

	if($file == "create") {
		$code .= "
			<div id = 'create".$module."'>
				<h3>Create $aan $module</h3>
				<form method='POST' action='' enctype='multipart/form-data'>";
				while($fieldIndex <= $fields) {
					$code .=  	"
					<label>Field".$fieldIndex."</label>
					<input type='text' name='field".$fieldIndex."'>";
					$fieldIndex++;
					
				}
				$code .=	  "
					<button type='submit'>Submit</button>
				</form>
			</div>";

	} else if ($file == "show") {
		$code .= "
			<div id = 'show".$module."'>
				<h3><?= $".$module."->name ?></h3>
			</div>";

	} else if ($file == "edit") {
		$code .= "
			<div id = 'edit".$module."'>
				<h3>Edit $aan $module</h3>
				<form method='POST' action='' enctype='multipart/form-data'>";
				while($fieldIndex <= $fields) {
					$code .= "
					<label>Field".$fieldIndex."</label>
					<input type='text' name='field' value='<?= $".$module."->field".$fieldIndex."; ?>'>";
					$fieldIndex++;					
				}
				$code .= "
					<button type='submit'>Submit</button>
				</form>
			</div>";
	} else if ($file == "landing") {
		$code .= "
			<div id = 'greeting'>
				<h1>Welcome to the {$module} App!</h1>
				<h3>Your one-stop shop for all things {$module}!</h3>
			</div>";
	}

	return $code;
}

// creates the file in question
function buildFile($module, $file, $fields)
{	
	if($file == "landing") {
		$fh = fopen(DIRECTORY . "/{$module}.{$file}.html", 'w');
	} else {
		$fh = fopen(DIRECTORY . "/{$module}.{$file}.php", 'w');
	}
	if($fh === false) {
		die("File Handle is false. Please check filepath!" . PHP_EOL);
	}
	$code = buildCode($module, $file, $fields);
	$code .= "
		</body>";
	fwrite($fh, $code);
	fclose($fh);
}

// checks if the user wants to create CRUD for another module
function checkForAnotherModule()
{
	$anotherModule = getInput("Would you like to create another module (1), or not (0)?" . PHP_EOL);
	if($anotherModule) {
		$module = getInput("Enter the name of the module you wish to build" . PHP_EOL);
		crudController($module);
	} else {
		die("Thanks for using CRUDbubble!" . PHP_EOL);
	}
}

// function that calls the function to build specific files
function crudController($module, $fields)
{
	$fileArray = ['landing', 'create', 'show', 'edit'];
	foreach($fileArray as $file) {
		buildFile($module, $file, $fields);
	}
	checkForAnotherModule($module);
}


// main function that drives the script
function kickstartProcess()
{
	$exists = getInput("Create new directory (0), or use existing directory (1)?" . PHP_EOL);
	$name = getInput("Enter name of directory" . PHP_EOL);
	$directorySet = buildAddDirectory($exists, $name);
	if(!$directorySet) {
		die("Directory could not be created or validated" . PHP_EOL);
	}
	$module = getInput("Enter the name of the module you wish to build" . PHP_EOL);
	$numFields = getInput("How many fields will users have to fill out/edit?" . PHP_EOL);
	crudController($module, $numFields);
}

kickstartProcess();



?>