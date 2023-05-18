<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesdating_Widget_LpTwoStoriesController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->heading = $this->_getParam('heading', 'Sweet stories from our Lovers');
    $limit = $this->_getParam('limit', 3);
    $popularitycriteria = $this->_getParam('popularitycriteria', 'creation_date');
  
    if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesvideo')) {
      $table = Engine_Api::_()->getDbTable('videos', 'sesvideo');
    } else {
      $table = Engine_Api::_()->getDbTable('videos', 'video');
    }
    $tableName = $table->info('name');

    $select = $table->select()->from($tableName)->where('photo_id <> ?', 0)->limit($limit);

    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    $sesdating_header = Zend_Registry::isRegistered('sesdating_header') ? Zend_Registry::get('sesdating_header') : null;
    if(empty($sesdating_header))
      return $this->setNoRender();
    $popularitycriteria_exist = $db->query("SHOW COLUMNS FROM ".$tableName." LIKE '".$popularitycriteria."'")->fetch();

    if (!empty($popularitycriteria_exist)) {
        $select->order("$popularitycriteria DESC");
    } else {
        $select->order('creation_date DESC');
    }

    $this->view->results = $results = $table->fetchAll($select);

    if(!engine_count($results))
        return $this->setNoRender();

  }
}
