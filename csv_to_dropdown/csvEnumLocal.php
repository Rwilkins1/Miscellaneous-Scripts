More accessible local version

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
  define('ROOT_DIRECTORY', '/Users/rwilkins/Documents/');
  if($argv[1] == "--help") {
    showHelpPage();
    return;
  }
  echo "Enter the name of the CSV file" . PHP_EOL;
  $handle = fopen("php://stdin", "r");
  $file = preg_replace('/[^A-ZA-z0-9_.\-]/', '', fgets($handle));
  $fh = fopen(ROOT_DIRECTORY . $file, 'r');
  // echo $fh . PHP_EOL;
  if($fh === false) {
    die("File Handle is false. Please check filpath for root directory: " . ROOT_DIRECTORY . PHP_EOL);
  }
  $header = false;
  $header = fgetcsv($fh);

  echo "Enter the column name to turn into a dropdown" . PHP_EOL;
  $handle = fopen("php://stdin", "r");
  $column = preg_replace('/[^A-ZA-z0-9_.\-]/', '', fgets($handle));
  fclose($handle);

  echo "Enter the name of the dropdown list to create" . PHP_EOL;
  $handle = fopen("php://stdin", "r");
  $list = preg_replace('/[^A-ZA-z0-9_.\-]/', '', fgets($handle));
  fclose($handle);

  $code = '<?php

  $app_list_strings["'.$list.'"]=array (
    ';
  $accountedItems = array();
  echo $column . PHP_EOL;
  echo $list . PHP_EOL;
  while(!feof($fh) && $row = fgetcsv($fh)) {
    if(!$header) {
      $header = array_keys($row);
      continue;
    }
    $dropdownItem = $row[array_search($column, $header)];
    if(array_search($dropdownItem, $accountedItems) || $dropdownItem == "" || $dropdownItem == $accountedItems[0]) {
      continue;
    } else {
      echo $dropdownItem . PHP_EOL;
      $code .= "'".$dropdownItem."' => '".$dropdownItem."',
    ";
      $accountedItems[] = $dropdownItem;
    }
  }
  fclose($fh);
  $code .= "'' => '',
);";
  createDropdown($list, $code);
}

function createDropdown($list, $code)
{
  $fh = fopen(ROOT_DIRECTORY . "/en_us." . $list . ".php", 'w');
  if($fh === false) {
    die("File Handle is false. Please check filepath for root directory: " . ROOT_DIRECTORY . PHP_EOL);
  }
  fwrite($fh, $code);
}

function showHelpPage()
{
  die("Required Syntax:\n
  php csvEnum.php <path> <column name> <dropdown list>

  <path>          :: Path to csv from sugar root directory
  <column name>   :: Name of the column in the CSV
  <dropdown list> :: Name of the dropdown list. Will override if name exists

  Run a Quick Repair and Rebuild after script execution
  ");
}
openCsv($argv);

?>