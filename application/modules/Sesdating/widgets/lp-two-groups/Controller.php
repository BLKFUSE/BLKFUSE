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
class Sesdating_Widget_LpTwoGroupsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->heading = $this->_getParam('heading', 'Recently Active Groups');
    $limit = $this->_getParam('limit', 3);
    $popularitycriteria = $this->_getParam('popularitycriteria', 'creation_date');

    if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesgroup')) {
        $table = Engine_Api::_()->getDbTable('groups', 'sesgroup');
    } else {
        $table = Engine_Api::_()->getDbTable('groups', 'group');
    }
    $tableName = $table->info('name');

    $select = $table->select()->from($tableName)->limit($limit);

    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

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
