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

// If a directory is needed, builds it, if not, adds files to existing one
function buildAddDirectory($exists, $name)
{
	if($exists) {
		if(!file_exists($name)) {
			return false;
		} else {
			return true;
		}
	} else {
		if(mkdir($name)) {
			return true;
		} else {
			return false;
		}
	}
}

// creates the file in question
function buildFile($file)
{

}

// main function that drives the script
function kickstartProcess()
{
	$exists = getInput("Create new directory (0), or use existing directory (1)?");
	$name = getInput("Enter name of directory");
	$directorySet = buildAddDirectory($exists, $name);

	if($directorySet) {
		buildFile(c);
		buildFile(r);
		buildFile(u);
		buildFile(d);
	}
}

kickstartProcess();



?>