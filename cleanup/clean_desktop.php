<?php
$jpgs = false;
foreach(glob("/path/to/desktop/*.jpg") as $file) {
	$jpgs = true;
	echo "Moving file: " . $file . " off of Desktop..." . PHP_EOL;
}
?>