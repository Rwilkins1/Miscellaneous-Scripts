<?php
require_once("../.local_paths.php");
$jpgs = false;
echo $desktop . PHP_EOL;
foreach(glob("{$desktop}/*.jpg") as $file) {
	$jpgs = true;
	echo "Moving file: " . basename($file) . " off of Desktop..." . PHP_EOL;
	rename($file, "{$desktop}/pictures/" . basename($file));
}
if($jpgs == false) {
	echo "No .jpg files to remove..." . PHP_EOL;
}
?>