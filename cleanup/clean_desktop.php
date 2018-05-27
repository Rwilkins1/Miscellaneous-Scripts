<?php
/*
Author: Reagan Wilkins
reagan.wilkins@gmail.com
github.com/Rwilkins1
05/26/2018

For use in cleaning up the Desktop directory on a local machine
*/

function removeFiles($extension)
{
	require("../.local_paths.php");
	$files = false;
	echo "Removing {$extension} files..." . PHP_EOL;
	echo "{$desktop}/{$extension}" . PHP_EOL;
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
removeFiles("*.png");
removeFiles("*.pdf");
removeFiles("*.jpeg");
removeFiles("Screen Shot*");
?>