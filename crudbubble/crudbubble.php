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
	<html lang='en'>";

	if ($file == "landing") {
		$code .= "
		<style type='text/css'>
		body {
			background-color: skyblue;
		}
		#greeting {
			text-align: center;
		}
		#navigation {
			overflow: hidden;
			background-color: #333;
		}
		a {
			float: left;
			display: block;
			color: #f2f2f2;
			text-align: center;
			padding: 14px;
			text-decoration: none;
			width: 30%;
		}
		#create {
			
		}
		#edit {

		}
		#view {

		}
		#blurb {
			text-align: center;
			padding: 16px;
		}
		.stuck {
			position: fixed;
			top: 0;
			width: 100%;
		}
		.stuck + #blurb {
			padding-top: 60px;
		}
		</style>
		<body>
			<div id = 'greeting'>
				<h1>Welcome to the {$module} App!</h1>
				<h3>Your one-stop shop for all things {$module}!</h3>
			</div><br><br><br><br><br><br>
			<div id = 'navigation'>
				<a id = 'create' href='create'>Create $aan $module</a>
				<a id = 'edit' href='edit'>Edit $aan $module</a>
				<a id = 'view' href='view'>View $aan $module</a>
			</div><br><br><br><br><br><br>
			<div id = 'blurb'>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vulputate eget velit vestibulum tristique. Donec sagittis, lacus id hendrerit luctus, tellus elit ullamcorper metus, a tincidunt lorem arcu quis dui. Maecenas elementum pretium rutrum. Nulla blandit lectus at velit finibus, eget consectetur orci congue. Fusce malesuada justo ligula, eget aliquam mauris mattis ac. Suspendisse neque tortor, dapibus in nulla non, porta volutpat mauris. In mauris tellus, mattis in facilisis mattis, rhoncus non arcu. Duis ipsum urna, sollicitudin a ornare at, venenatis et nunc. Donec non dolor et risus mollis pellentesque. Vivamus non nibh ac neque lacinia suscipit. In congue, dolor a auctor tincidunt, elit arcu condimentum turpis, et maximus tortor nisi ac justo. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.<br><br><br><br><br><br>


Phasellus iaculis, orci in euismod ornare, nibh diam facilisis purus, cursus ultricies nunc sapien vitae urna. Vivamus vulputate gravida ex, quis tempus orci sollicitudin faucibus. Nullam accumsan leo nunc, a imperdiet neque egestas sit amet. Ut ornare luctus neque, eget varius arcu. Duis bibendum nisi ut dapibus hendrerit. Aenean sagittis eleifend porta. Mauris vel nibh imperdiet, tristique sem ac, fringilla ligula. Ut viverra laoreet turpis, quis fermentum risus tempor sit amet. Vestibulum faucibus, magna convallis feugiat aliquam, quam erat lacinia lacus, id consequat nunc nisl vitae leo. Pellentesque volutpat nec nisi nec dapibus.<br><br><br><br><br><br>


Praesent hendrerit dolor ut ante mollis, sed convallis orci maximus. Nullam commodo dapibus congue. Nulla facilisi. Praesent nunc risus, luctus a massa a, malesuada auctor enim. Quisque faucibus gravida urna, rhoncus tempor purus consequat et. Vestibulum vel turpis lorem. Vestibulum a quam egestas, accumsan felis eu, viverra mi. Cras auctor nibh diam, quis hendrerit odio hendrerit sit amet. Proin feugiat ligula lacus, eu vehicula ante accumsan eget. Quisque mollis enim hendrerit arcu volutpat, ut pretium metus venenatis. Pellentesque gravida molestie varius. Morbi vitae neque nisl.<br><br><br><br><br><br>


Nulla gravida risus turpis, ac imperdiet nisl fermentum hendrerit. Nam a turpis a felis bibendum egestas. Mauris vitae ex viverra, placerat turpis et, porta quam. Praesent urna augue, dictum et fringilla a, suscipit et lectus. Duis vel tortor urna. Donec vehicula sodales convallis. Curabitur luctus ante ac est blandit tincidunt. In id enim malesuada, lobortis elit sit amet, dapibus lectus. Nunc tincidunt, orci quis facilisis vulputate, augue elit pretium libero, vitae tristique dui ligula eu libero. Maecenas massa mi, vehicula sed tellus quis, tincidunt gravida elit. Sed mattis libero rutrum ornare euismod. Fusce et odio ex. Praesent faucibus erat in mi suscipit ornare. Praesent nec metus id ante imperdiet eleifend. Suspendisse auctor, nunc id elementum iaculis, tortor enim hendrerit velit, et luctus augue libero vel sapien.<br><br><br><br><br><br>


Cras a vestibulum eros. Nunc posuere dignissim venenatis. Fusce malesuada sem augue, quis ornare lorem malesuada nec. In hac habitasse platea dictumst. Nullam bibendum orci a tristique eleifend. Vivamus at luctus leo. Donec sapien enim, aliquet et nisi quis, luctus malesuada mi. Cras malesuada tincidunt lacus, ut fringilla massa sodales euismod. In bibendum venenatis eleifend. Mauris ac lectus vel neque congue tempus rutrum lacinia massa.<br><br><br><br><br><br>


				<button type='submit'>Get Started</button>
			</div>
		</body>
				<script>				
			window.onscroll = function() {stickNavigation()};
			var navigation = document.getElementById('navigation');
			var stuck = navigation.offsetTop;

			function stickNavigation() {
			  if (window.pageYOffset >= stuck) {
			    navigation.classList.add('stuck')
			  } else {
			    navigation.classList.remove('stuck');
			  }
			}
		</script>";
	} else if($file == "create") {
		$code .= "
		<body>
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
			</div>
		</body>";

	} else if ($file == "show") {
		$code .= "
		<body>
			<div id = 'show".$module."'>
				<h3><?= $".$module."->name ?></h3>
			</div>
		</body>";

	} else if ($file == "edit") {
		$code .= "
		<body>
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
			</div>
		</body>";
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