<?php
require_once("../.local_paths.php");

function removeFiles($extension)
{
	$files = false;
	echo "Removing {$extension} files..." . PHP_EOL;
	foreach(glob("{$desktop}/{$extension}") as $file) {
		$files = true;
		echo "Moving file: " . basename($file) . " off of Desktop..." . PHP_EOL;
		rename($file, "{$desktop}/pictures/" . basename($file));
	}
	if(!$files) {
		echo "No {$extension} files to remove..." . PHP_EOL;
	}
}
// Remove .jpg files

removeFiles("*.jpg");
?>