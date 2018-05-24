<?php
/*
Author: Reagan Wilkins
reagan.wilkins@gmail.com
github.com/Rwilkins1
05/22/2018

Converts a column from a csv into a dropdown list in a Sugar
or Suite CRM environment.

php csvEnum.php <path to csv> <Index of column> <Name of dropdown list>

Run Quick Repair and Rebuild after execution.
*/

function openCsv($argv)
{
  ini_set('auto_detect_line_endings', true);
  define('ROOT_DIRECTORY', '/path/to/sugar/custom/');
    if($argv[1] == "--help") {
    showHelpPage();
    return;
  }
  $fh = fopen(ROOT_DIRECTORY . $argv[1], 'r');
  if($fh === false) {
    die("File Handle is false. Please check filpath for root directory: " . ROOT_DIRECTORY . PHP_EOL);
  }
  $header = false;
  $header = fgetcsv($fh);
  $code = '<?php

  $app_list_strings["'.$argv[3].'"]=array (
    ';
  $accountedItems = array();
  while(!feof($fh) && $row = fgetcsv($fh)) {
    if(!$header) {
      $header = array_keys($row);
      continue;
    }
    $dropdownItem = $row[array_search($argv[2], $header)];
    $cleanItem = cleanDropdownItem($dropdownItem);
    if(array_search($dropdownItem, $accountedItems) || $dropdownItem == "" || $dropdownItem == $accountedItems[0]) {
      continue;
    } else {
      echo $dropdownItem . PHP_EOL;
      $code .= "'".$cleanItem."' => '".$dropdownItem."',
    ";
      $accountedItems[] = $dropdownItem;
    }
  }
  fclose($fh);
  $code .= "'' => '',
);";
  createDropdown($argv[3], $code);
}

function cleanDropdownItem($item)
{
    str_replace('-', '_', $item);
    preg_replace('/[^A-Za-z0-9\-]/', '', $item);
    str_replace(' ', '_', $item);
    return $item;
}

function createDropdown($list, $code)
{
  $fh = fopen(ROOT_DIRECTORY . "Extension/application/Ext/Language/en_us." . $list . ".php", 'w');
  if($fh === false) {
    die("File Handle is false. Please check filepath for root directory: " . ROOT_DIRECTORY . PHP_EOL);
  }
  fwrite($fh, $code);
}

function showHelpPage()
{
  die("
  php csvEnum.php <path> <column name> <dropdown list>

  <path>          :: Path to csv from sugar root directory
  <column name>   :: Name of the column in the CSV
  <dropdown list> :: Name of the dropdown list. Will override if name exists

  Run a Quick Repair and Rebuild after script execution
  ");
}
openCsv($argv);

?>