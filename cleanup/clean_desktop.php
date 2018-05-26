<?php
require_once("../.local_paths.php");

function removeFiles($extension)
{
	echo "Removing {$extension} files..." . PHP_EOL;
	foreach(glob("{$desktop}/{$extension}") as $file) {

	}
}
// Remove .jpg files
$jpgs = false;
echo "Removing .jpg files" . PHP_EOL;
foreach(glob("{$desktop}/*.jpg") as $file) {
	$jpgs = true;
	echo "Moving file: " . basename($file) . " off of Desktop..." . PHP_EOL;
	rename($file, "{$desktop}/pictures/" . basename($file));
}
if($jpgs == false) {
	echo "No .jpg files to remove..." . PHP_EOL;
}

// Remove .png files
$pngs = false;
echo "Removing .png files" . PHP_EOL;
foreach(glob("{$desktop}/*.png") as $file)

// Remove .pdf files

// Remove Screenshots
?>