<?php
/*
Author: Reagan Wilkins
reagan.wilkins@gmail.com
github.com/Rwilkins1
05/22/2018

Converts a column from a csv into a dropdown list in a Sugar
or Suite CRM environment.

Run Quick Repair and Rebuild after execution.
*/

function setConfiguration($argv)
{
  ini_set('auto_detect_line_endings', true);
  if($argv[1] == "--help") {
    showHelpPage();
    return;
  }
  $overrideRoot = array_search("-or", $argv);
  if($overrideRoot) {
    define('ROOT_DIRECTORY', $argv[$overrideRoot + 1]);
  } else {
    define('ROOT_DIRECTORY', '/Users/rwilkins/Documents/');
  }

  $overrideUpload = array_search("-ou", $argv);
  if($overrideUpload)  {
    echo "Overriding Upload Directory..." . PHP_EOL;
    sleep(2);
    define('UPLOAD_DIRECTORY', $argv[$overrideUpload + 1]);
  } else {
    define('UPLOAD_DIRECTORY', '/Users/rwilkins/Documents/');
  }

  $overrideLanguage = array_search("-l", $argv);
  if($overrideLanguage) {
    $languagesArray = ["zh_TW", "zh_CN", "uk_UA", "tr_TR", "th_TH", "sv_SE",
    "sr_RS", "sq_AL", "sk_SK", "ru_RU", "ro_RO", "pt_PT", "pt_BR", "pl_PL",
    "nl_NL", "nb_NO", "lv_LV", "lt_LT", "ko_KR", "ja_JP", "it_it", "hu_HU",
    "hr_HR", "he_IL", "fr_FR", "fi_FI", "et_EE", "es_LA", "es_ES", "en_us",
    "en_UK", "el_EL", "de_DE", "da_DK", "cs_CZ", "ca_ES", "bg_BG", "ar_SA"];
    $input = $argv[$overrideLanguage + 1];
    if(array_search($input, $languagesArray) || $input == $languagesArray[0]) {
      define('LANGUAGE', $input);
    } else {
      die("Sorry, the specified language key is invalid. Please enter a valid language key" . PHP_EOL);
    }
  } else {
    define('LANGUAGE', 'en_us');
  }
  openCsv();
}

function openCsv()
{
  echo "Enter the name of the CSV file" . PHP_EOL;
  $handle = fopen("php://stdin", "r");
  $file = preg_replace('/[^A-ZA-z0-9_.\-]/', '', fgets($handle));
  $fh = fopen(UPLOAD_DIRECTORY . $file, 'r');
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
  $fh = fopen(ROOT_DIRECTORY . "/" . LANGUAGE . ".{$list}.php", 'w');
  if($fh === false) {
    die("File Handle is false. Please check filepath for root directory: " . ROOT_DIRECTORY . PHP_EOL);
  }
  fwrite($fh, $code);
}

function showHelpPage()
{
  if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
    system('cls');
  } else {
    system('clear');
  }
  die("
  ------------------------------------------------------
  ------------------------------------------------------

  Author: Reagan Wilkins
  reagan.wilkins@gmail.com
  github.com/Rwilkins1
  05/22/2018

  Converts a column from a csv into a Sugar/Suite CRM
  compatible dropdown language file

  Run a Quick Repair and Rebuild after script execution

  ------------------------------------------------------
  ------------------------------------------------------

  Flags:

  -or       :: Override the root directory (path to Sugar/Suite instance)
  -ou       :: Override the upload directory (path to csv file)
  -l        :: Specify a language other than American English



  ");
}

setConfiguration($argv);

?>