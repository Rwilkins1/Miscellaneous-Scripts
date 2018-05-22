<?php
/*
arg1 = path to csv file
arg2 = index of row
arg3 = path to dropdown file
*/

function openCsv($argv) {
  ini_set('auto_detect_line_endings', true);
  define('ROOT_DIRECTORY', '/Users/rwilkins/Documents');
  if($argv[1] == "--help") {
    die("You've reached the help page" . PHP_EOL);
  }
  $fh = fopen(ROOT_DIRECTORY . "/" . $argv[1], 'r');
  if($fh === false) {
    die("File Handle is false. Please check filpath for root directory: " . ROOT_DIRECTORY . PHP_EOL);
  }
  $header = false;
  $header = fgetcsv($fh);
  $html = "<select>\n";
  $accountedItems = array();
  while(!feof($fh) && $row = fgetcsv($fh)) {
    if(!$header) {
      $header = array_keys($row);
      continue;
    }
    $dropdownItem = $row[$argv[2]];
    if(array_search($dropdownItem, $accountedItems) || $dropdownItem == "" || $dropdownItem == $accountedItems[0]) {
      continue;
    } else {
      echo $dropdownItem . PHP_EOL;
      $html .= "<option value='{$dropdownItem}'>" . $dropdownItem . "</option>\n";
      $accountedItems[] = $dropdownItem;
    }
  }
  fclose($fh);
  $html .= "</select>\n";
  createDropdown($argv[3], $html);
}