<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Tutorials.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sestutorial_Model_DbTable_Tutorials extends Engine_Db_Table {

  protected $_rowClass = "Sestutorial_Model_Tutorial";
  
  public function countTutorials($params = array()) {
  
    $select = $this->select()->from($this->info('name'), array('*'))->where($this->info('name') . ".status = ?",1);
    if(isset($params['category_id'])) {
      $select->where('category_id =?', $params['category_id']);
    }
    if(isset($params['subcat_id'])) {
      $select->where('subcat_id =?', $params['subcat_id']);
    }
		if(isset($params['fetchAll'])){
			return  $this->fetchAll($select);
		}
    return Zend_Paginator::factory($select);
  }
  
  public function getTutorialPaginator($params = array()) {
    return Zend_Paginator::factory($this->getTutorialSelect($params));
  }
  
  public function getCategoryTutorialSelect($params = array()) {

    $tutorialTableName = $this->info('name');

    $select = $this->select()
                ->from($tutorialTableName)
                ->where("status = ?",1)
                ->where('search =?', 1);
    if($params['onlyTutorial'] == '1') {
      $select->where("category_id  = ?", $params['category_id'])
            ->where("subcat_id  = ?",0)
            ->where("subsubcat_id  = ?",0);
    } else if($params['onlyTutorial'] == 2) { 
      $select->where("category_id  = ?", $params['category_id'])
            ->where("subcat_id  = ?", $params['subcat_id'])
            ->where("subsubcat_id  = ?",0);
    } else if($params['onlyTutorial'] == 3) { 
      $select->where("category_id  = ?", $params['category_id'])
            ->where("subcat_id  = ?", $params['subcat_id'])
            ->where("subsubcat_id  = ?", $params['subsubcat_id']);
    }

    return $this->fetchAll($select);
  }
  
  public function getTutorialSelect($params = array()) {
 
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

 
    $tutorialTableName = $this->info('name');

    $categoriesTable = Engine_Api::_()->getDbtable('categories', 'sestutorial');
    $categoriesTableName = $categoriesTable->info('name');
    
    $select = $this->select()
            ->from($tutorialTableName)
            ->setIntegrityCheck(false)
            ->where("$tutorialTableName.status = ?",1)
            ->where($tutorialTableName . '.search =?', 1);
    
    if($viewer_id) {
      $level_id = $viewer->level_id;
      $select->where($tutorialTableName . '.memberlevels LIKE "%:\"'.$level_id.'\";%"');
    } else {
      $level_id = 5;
      $select->where($tutorialTableName . '.memberlevels LIKE "%:\"'.$level_id.'\";%"');
    }

    if (isset($params['title']) && $params['title']) {
      $search_text = $params['title'];
      $select->where($tutorialTableName.".description LIKE '%$search_text%' or ".$tutorialTableName.".title LIKE '%$search_text%'");
    }
    
    if( !empty($params['tag']) ) {
      $tmName = Engine_Api::_()->getDbtable('TagMaps', 'core')->info('name');
      $select->setIntegrityCheck(false)->joinLeft($tmName, "$tmName.resource_id = $tutorialTableName.tutorial_id")
            ->where($tmName.'.resource_type = ?', 'sestutorial_tutorial')
            ->where($tmName.'.tag_id = ?', $params['tag']);
    }
    
    if(isset($params['tutorial_id']) && !empty($params['tutorial_id'])) {
      $select->where("$tutorialTableName.tutorial_id  <> ?",$params['tutorial_id']);
    }
    
    if(isset($params['category_id']) && !empty($params['category_id'])) {
      $select->where("$tutorialTableName.category_id  = ?",$params['category_id']);
    }
    if(isset($params['subcat_id']) && !empty($params['subcat_id'])) {
      $select->where("$tutorialTableName.subcat_id  = ?",$params['subcat_id']);
    }
    if(isset($params['subsubcat_id']) && !empty($params['subsubcat_id'])) {
      $select->where("$tutorialTableName.subsubcat_id  = ?",$params['subsubcat_id']);
    }
    if (isset($params['limit'])) {
      $select->limit($params['limit']);
    }
    
    if (isset($params['order']) && !empty($params['order'])) {
      if ($params['order'] == 'featured')
        $select->where('featured = ?', '1');
      elseif ($params['order'] == 'sponsored')
        $select->where('sponsored = ?', '1');
    }
		if(isset($params['order']) && $params['order'] != ''){
      $select->order($tutorialTableName.'.'.$params['order'].' DESC');
		}  else {
      $select->order('order ASC');
		}

    if (isset($params['fetchAll'])) {
      return $this->fetchAll($select);
    } else {
      return $select;
    }
  }
}