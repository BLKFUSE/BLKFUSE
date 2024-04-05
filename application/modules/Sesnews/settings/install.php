<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: install.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Installer extends Engine_Package_Installer_Module {

  public function onInstall() {

    $db = $this->getDb();
    $this->_addHashtagSearchContent();
    parent::onInstall();
  }
  
  protected function _addHashtagSearchContent() {

    $db = $this->getDb();
    $select = new Zend_Db_Select($db);

    // hashtag search page
    $select
        ->from('engine4_core_pages')
        ->where('name = ?', 'core_hashtag_index')
        ->limit(1);
    $pageId = $select->query()->fetchObject()->page_id;

    // Check if it's already been placed
    $select = new Zend_Db_Select($db);
    $select
        ->from('engine4_core_content')
        ->where('page_id = ?', $pageId)
        ->where('type = ?', 'widget')
        ->where('name = ?', 'sesnews.browse-news');
    $info = $select->query()->fetch();

    if( empty($info) ) {

        // container_id (will always be there)
        $select = new Zend_Db_Select($db);
        $select
            ->from('engine4_core_content')
            ->where('page_id = ?', $pageId)
            ->where('type = ?', 'container')
            ->limit(1);
        $containerId = $select->query()->fetchObject()->content_id;

        // middle_id (will always be there)
        $select = new Zend_Db_Select($db);
        $select
            ->from('engine4_core_content')
            ->where('parent_content_id = ?', $containerId)
            ->where('type = ?', 'container')
            ->where('name = ?', 'middle')
            ->limit(1);
        $middleId = $select->query()->fetchObject()->content_id;

        // tab_id (tab container) may not always be there
        $select
            ->reset('where')
            ->where('type = ?', 'widget')
            ->where('name = ?', 'core.container-tabs')
            ->where('page_id = ?', $pageId)
            ->limit(1);
        $tabId = $select->query()->fetchObject();
        if( $tabId && @$tabId->content_id ) {
            $tabId = $tabId->content_id;
        } else {
            $tabId = null;
        }
        
        $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sesnews.browse-news',
          'page_id' => $pageId,
          'parent_content_id' => ($tabId ? $tabId : $middleId),
          'order' => 100,
          'params' => '{"enableTabs":["list","simplelist","advlist","grid","advgrid","supergrid","pinboard","map"],"openViewType":"advgrid","show_criteria":["verifiedLabel","favouriteButton","likeButton","socialSharing","like","favourite","comment","ratingStar","rating","view","title","category","by","readmore","creationDate","location","descriptionlist","descriptiongrid","descriptionpinboard","descriptionsimplelist","descriptionadvlist","descriptionadvgrid","descriptionsupergrid","enableCommentPinboard"],"sort":"recentlySPcreated","show_item_count":"1","title_truncation_list":"100","title_truncation_grid":"100","title_truncation_pinboard":"30","title_truncation_simplelist":"45","title_truncation_advlist":"45","title_truncation_advgrid":"45","title_truncation_supergrid":"45","description_truncation_list":"300","description_truncation_grid":"150","description_truncation_pinboard":"150","description_truncation_simplelist":"150","description_truncation_advlist":"150","description_truncation_advgrid":"150","description_truncation_supergrid":"200","height_list":"230","width_list":"461","height_grid":"270","width_grid":"307","height_simplelist":"230","width_simplelist":"260","height_advgrid":"230","width_advgrid":"461","height_supergrid":"250","width_supergrid":"461","width_pinboard":"280","limit_data_pinboard":"12","limit_data_grid":"12","limit_data_list":"12","limit_data_simplelist":"12","limit_data_advlist":"12","limit_data_advgrid":"12","limit_data_supergrid":"12","pagging":"button","title":"News","nomobile":"0","name":"sesnews.browse-news"}',
        ));
    }
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
                      ->where('name = ?', 'sesnews.text.singular')
                      ->limit(1)
                      ->query()
                      ->fetchColumn();
	  if(!$singularWord)
      $singularWord = 'news';

		$pluralWord = $db->select()
                    ->from('engine4_core_settings', 'value')
                    ->where('name = ?', 'sesnews.text.plural')
                    ->limit(1)
                    ->query()
                    ->fetchColumn();
	  if(!$pluralWord)
      $pluralWord = 'news';

		$oldSigularWord = 'news';
		$oldPluralWord = 'news';
		$newSigularWord = $singularWord;
		$newPluralWord = $pluralWord;
		$newSigularWordUpper = ucfirst($newSigularWord);
		$newPluralWordUpper = ucfirst($newPluralWord);

		$tmp = Engine_Translate_Parser_Csv::parse(APPLICATION_PATH . '/application/languages/en/sesnews.csv', 'null', array('delimiter' => ';','enclosure' => '"',));

		if( !empty($tmp['null']) && is_array($tmp['null']) )
      $inputData = $tmp['null'];
		else
      $inputData = array();

		$OutputData = array();
		$chnagedData = array();
		foreach($inputData as $key => $input) {
			$chnagedData = str_replace(array($oldSigularWord,$oldPluralWord,ucfirst($oldSigularWord),ucfirst($oldPluralWord),strtoupper($oldSigularWord),strtoupper($oldPluralWord)), array($newSigularWord, $newPluralWord, ucfirst($newSigularWord), ucfirst($newPluralWord), strtoupper($newSigularWord), strtoupper($newPluralWord)), $input);
			$OutputData[$key] = $chnagedData;
		}

		$targetFile = APPLICATION_PATH . '/application/languages/en/sesnews.csv';
		if (file_exists($targetFile))
      @unlink($targetFile);

		touch($targetFile);
		chmod($targetFile, 0777);

		$writer = new Engine_Translate_Writer_Csv($targetFile);
		$writer->setTranslations($OutputData);
		$writer->write();
		//END CSV FILE WORK
  }
}
