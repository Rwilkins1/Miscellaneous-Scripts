<?php
require_once("../.local_paths.php");
$jpgs = false;
echo $desktop . PHP_EOL;
foreach(glob("{$desktop}/*.jpg") as $file) {
	$jpgs = true;
	echo "Moving file: " . $file . " off of Desktop..." . PHP_EOL;
}
?>