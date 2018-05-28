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
if(filesize('.directoryConfig.php') == 0) {
  die("
  The installer has not been run. Please enter the following command:
  'php install.php'
  and follow the prompts to complete the installation process.
  " . PHP_EOL);
}

function getInput()
{
  $handle = fopen("php://stdin", "r");
  $input = trim(fgets($handle));
  fclose($handle);
  return $input;
}

function getLanguage($language, $defaultLanguage)
{
  $languagesArray = ["zh_TW", "zh_CN", "uk_UA", "tr_TR", "th_TH", "sv_SE",
  "sr_RS", "sq_AL", "sk_SK", "ru_RU", "ro_RO", "pt_PT", "pt_BR", "pl_PL",
  "nl_NL", "nb_NO", "lv_LV", "lt_LT", "ko_KR", "ja_JP", "it_it", "hu_HU",
  "hr_HR", "he_IL", "fr_FR", "fi_FI", "et_EE", "es_LA", "es_ES", "en_us",
  "en_UK", "el_EL", "de_DE", "da_DK", "cs_CZ", "ca_ES", "bg_BG", "ar_SA"];

  if(array_search($language, $languagesArray) || $language == $languagesArray[0]) {
    return $language;
  } else if(strtolower($language) == 'cancel') {
    return $defaultLanguage;
  } else {
    return false;
  }
}

function setConfiguration($argv)
{
  require_once('directoryConfig.php');
  if($argv[1] == "--help") {
    showHelpPage();
    return;
  }
  $overrideRoot = array_search("-or", $argv);
  if($overrideRoot) {
    echo "Overriding Root Directory..." . PHP_EOL;
    sleep(3);
    if(substr($argv[$overrideRoot + 1], -1) != "/") {
      $argv[$overrideRoot + 1] .= "/";
    }
    define('ROOT_DIRECTORY', $argv[$overrideRoot + 1]);
  } else {
    define('ROOT_DIRECTORY', $defaultRoot);
  }

  $overrideUpload = array_search("-ou", $argv);
  if($overrideUpload)  {
    echo "Overriding Upload Directory..." . PHP_EOL;
    sleep(3);
    if(substr($argv[$overrideUpload + 1], -1) != "/") {
      $argv[$overrideUpload + 1] .= "/";
    }
    define('UPLOAD_DIRECTORY', $argv[$overrideUpload + 1]);
  } else {
    define('UPLOAD_DIRECTORY', $defaultUpload);
  }

  $overrideLanguage = array_search("-l", $argv);
  if($overrideLanguage) {
    $language = getLanguage($argv[$overrideLanguage + 1], $defaultLanguage);
    while($language === false) {
      echo "The language key you entered is invalid, please enter a valid language key or type 'cancel' to default to American English" . PHP_EOL;
      $input = getInput();
      $language = getLanguage($input, $defaultLanguage);
    }
    if($language != $defaultLanguage) {
      echo "Overriding Language..." . PHP_EOL;
      sleep(3);
      define('LANGUAGE', $language);
    } else {
      define('LANGUAGE', $defaultLanguage);
    }
  } else {
    define('LANGUAGE', $defaultLanguage);
  }
  openCsv();
}

function openCsv()
{
  echo "Enter the name of the CSV file" . PHP_EOL;
  $file = getInput();
  $fh = fopen(UPLOAD_DIRECTORY . $file, 'r');
  // echo $fh . PHP_EOL;
  if($fh === false) {
    die("File Handle is false. Please check filpath for root directory: " . ROOT_DIRECTORY . PHP_EOL);
  }
  $header = false;
  $header = fgetcsv($fh);

  echo "Enter the column name to turn into a dropdown" . PHP_EOL;
  $column = getInput();

  echo "Enter the name of the dropdown list to create" . PHP_EOL;
  $list = getInput();

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

  createDropdown($list, $code);
}

function cleanDropdownItem($item)
{
    str_replace('-', '_', $item);
    preg_replace('/[^A-Za-z0-9_\-]/', '', $item);
    str_replace(' ', '_', $item);
    return $item;
}

function createDropdown($list, $code)
{
  $fh = fopen(ROOT_DIRECTORY . "Extension/application/Ext/Language/" . LANGUAGE . ".{$list}.php", 'w');
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