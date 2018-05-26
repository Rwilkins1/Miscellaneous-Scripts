<?php
/*
Author: Reagan Wilkins
reagan.wilkins@gmail.com
gitub.com/Rwilkins1
05/25/2018

Installation script to set the upload directory, root directory,
and default language of the sugar/suite crm instance.

Must be run before csvEnum.php can start running.
*/
  function getInput()
  {
    $handle = fopen("php://stdin", "r");
    $input = trim(fgets($handle));
    fclose($handle);
    return $input;
  }

  function getLanguage()
  {
    echo "Enter the language key corresponding to your system's default language, or type 'cancel' to default to American English" . PHP_EOL;
    $language = getInput();
    $languagesArray = ["zh_TW", "zh_CN", "uk_UA", "tr_TR", "th_TH", "sv_SE",
    "sr_RS", "sq_AL", "sk_SK", "ru_RU", "ro_RO", "pt_PT", "pt_BR", "pl_PL",
    "nl_NL", "nb_NO", "lv_LV", "lt_LT", "ko_KR", "ja_JP", "it_it", "hu_HU",
    "hr_HR", "he_IL", "fr_FR", "fi_FI", "et_EE", "es_LA", "es_ES", "en_us",
    "en_UK", "el_EL", "de_DE", "da_DK", "cs_CZ", "ca_ES", "bg_BG", "ar_SA"];
    if(array_search($language, $languagesArray) || $language == $languagesArray[0]) {
      return $language;
    } else if(strtolower($language) == 'cancel') {
      return 'en_us';
    } else {
      return false;
    }
  }

  function setConfiguration($root, $upload, $language)
  {
    echo "
    Setting configuration:
    Root Directory: {$root}
    Upload Directory: {$upload}
    Default Language: {$language}" . PHP_EOL;

    $code = "
    <?php
      ini_set('auto_detect_line_endings', true);
      " . '$defaultRoot' . " = '{$root}';
      " . '$defaultUpload' . " = '{$upload}';
      " . '$defaultLanguage' . " = '{$language}';
    ?>
    ";
    $fh = fopen("directoryConfig.php", 'w');
    if($fh === false) {
      die("There was an error creating config file" . PHP_EOL);
    }
    fwrite($fh, $code);
    fclose($fh);
  }

  function getConfiguration()
  {
    echo "Please enter the full path to the custom directory of your Sugar or Suite Instance:" . PHP_EOL;
    $root = getInput();
    if(substr($root, -1) != "/") {
      $root .= "/";
    }

    echo "Please enter the full path to the directory that your csvs will be stored in:" . PHP_EOL;
    $upload = getInput();
    if(substr($upload, -1) != "/") {
      $upload .= "/";
    }

    echo "Should your default language be American English (Y/N)?" . PHP_EOL;
    $englishDefault = getInput();
    if($englishDefault == "Y") {
      $language = "en_us";
    } else {
      $language = getLanguage();
      while($language === false) {
        echo "The language key you entered is invalid, please try again" . PHP_EOL;
        $language = getLanguage();
      }
    }
    setConfiguration($root, $upload, $language);
  }

  function checkIfInstallAlreadyRun()
  {
    if(filesize('directoryConfig.php') > 0) {
      echo "
      Configuration has already been set. Running the installer again will override your previous configuration.
      Are you sure you'd like to re-run the installer (Y/N)?
      " . PHP_EOL;
      $response = getInput();
      if($response == "Y") {
        getConfiguration();
      } else {
        die("Goodbye!" . PHP_EOL);
      }
    } else {
      getConfiguration();
    }
  }

  if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
    system('cls');
  } else {
    system('clear');
  }
  checkIfInstallAlreadyRun();
?>