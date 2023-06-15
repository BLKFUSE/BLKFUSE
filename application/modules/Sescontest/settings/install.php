<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: install.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Installer extends Engine_Package_Installer_Module {


  public function onInstall() {
    parent::onInstall();
  }

  public function onPostInstall() {

    if (!class_exists('Engine_Translate_Parser_Csv')) {
      include APPLICATION_PATH . "/application/libraries/Engine/Translate/Parser/Csv.php";
      include APPLICATION_PATH . "/application/libraries/Engine/Translate/Writer/Csv.php";
    }

    //START TEXT CHNAGE WORK IN CSV FILE
    $db = $this->getDb();
    $singularWord = $db->select()
            ->from('engine4_core_settings', 'value')
            ->where('name = ?', 'sescontest.text.singular')
            ->limit(1)
            ->query()
            ->fetchColumn();
    if (!$singularWord)
      $singularWord = 'contest';

    $pluralWord = $db->select()
            ->from('engine4_core_settings', 'value')
            ->where('name = ?', 'sescontest.text.plural')
            ->limit(1)
            ->query()
            ->fetchColumn();
    if (!$pluralWord)
      $pluralWord = 'contests';

    $oldSigularWord = 'contest';
    $oldPluralWord = 'contests';
    $newSigularWord = $singularWord;
    $newPluralWord = $pluralWord;
    $newSigularWordUpper = ucfirst($newSigularWord);
    $newPluralWordUpper = ucfirst($newPluralWord);

    $tmp = Engine_Translate_Parser_Csv::parse(APPLICATION_PATH . '/application/languages/en/sescontest.csv', 'null', array('delimiter' => ';', 'enclosure' => '"',));

    if (!empty($tmp['null']) && is_array($tmp['null']))
      $inputData = $tmp['null'];
    else
      $inputData = array();

    $OutputData = array();
    $chnagedData = array();
    foreach ($inputData as $key => $input) {
      $chnagedData = str_replace(array($oldSigularWord, $oldPluralWord, ucfirst($oldSigularWord), ucfirst($oldPluralWord), strtoupper($oldSigularWord), strtoupper($oldPluralWord)), array($newSigularWord, $newPluralWord, ucfirst($newSigularWord), ucfirst($newPluralWord), strtoupper($newSigularWord), strtoupper($newPluralWord)), $input);
      $OutputData[$key] = $chnagedData;
    }

    $targetFile = APPLICATION_PATH . '/application/languages/en/sescontest.csv';
    if (file_exists($targetFile))
      @unlink($targetFile);

    touch($targetFile);
    chmod($targetFile, 0777);

    $writer = new Engine_Translate_Writer_Csv($targetFile);
    $writer->setTranslations($OutputData);
    $writer->write();
    //END CSV FILE WORK
  }


  //Funcation for check depandencey plugin
  private function getAllPluginVersion() {

    $db = $this->getDb();
    $baseURL = Zend_Controller_Front::getInstance()->getBaseUrl();
    $pluginArrays = array(
      'sescontestjoinfees' => '4.10.3',
      'sescontestpackage' => '4.10.3',
    );
    $sespluginupgrademessage = '';
    foreach ($pluginArrays as $key=>$pluginArray) {
      $modulesExist = $db->query("SELECT * FROM  `engine4_core_modules` WHERE  `name` LIKE  '".$key."'")->fetch();
      if (!empty($modulesExist) && !empty($modulesExist['version'])) {
        $modulesExistSES = $this->checkpluginversion($modulesExist['version'], $pluginArray);
        if (empty($modulesExistSES)) {
          $sespluginupgrademessage .= '<div><span style="border-radius: 3px;border: 2px solid #cd4545;background-color: #da5252;padding: 10px;display: block;margin-bottom: 15px;"><p style="color:#fff;font-weight:bold;">Note: Your website does not has the latest version of "' . $modulesExist['title'] . '". Please upgrade "' . $modulesExist['title'] . '" on your website. Please <a href="' . $baseURL . '/manage" style="color:#fff;text-decoration:underline;font-weight:bold;">Click here</a> to go Manage Packages.</p></span></div>';
        }
      }
    }
    return $sespluginupgrademessage;
  }

  //Funcation for check versions
  private function checkpluginversion($pluginVersion, $plugin_currentversion) {

    $sesbasicSiteversion = @explode('p', $plugin_currentversion);
    $sesbasiCurrentversionE = @explode('p', $pluginVersion);

    if(isset($sesbasiCurrentversionE[0]))
      $sesbasiCurrentVersion = @explode('.', $sesbasiCurrentversionE[0]);

    if(isset($sesbasiCurrentversionE[1]))
      $sesbasiCurrentVersionP = $sesbasiCurrentversionE[1];

    $finalVersion = 1;
    $versionB  = false;

    foreach($sesbasicSiteversion as $versionSite) {
      $sesVersion = explode('.', $versionSite);
      if(engine_count($sesVersion) > 1){
      $counterV = 0;
      foreach($sesVersion as $key => $version) {
        if(isset($sesbasiCurrentVersion[$key]) && $version < $sesbasiCurrentVersion[$key]){
          $versionB = true;
          $finalVersion = 1;
          break;
        }
        if(isset($sesbasiCurrentVersion[$key]) && $version > $sesbasiCurrentVersion[$key] && 	$version != $sesbasiCurrentVersion[$key]) {
          $finalVersion = 0;
          break;
        }
        $counterV++;
      }
      } else {
        //string after p
        if(isset($sesbasiCurrentVersionP)){
          if( $versionSite > $sesbasiCurrentVersionP && $versionSite != $sesbasiCurrentVersionP) {
            $finalVersion = 0;
            break;
          }
        } else {
          $finalVersion = 0;
          break;
        }
      }
      //check if final result is false exit
      if(!$finalVersion || $versionB)
        break;
    }
    return $finalVersion;
  }
}
