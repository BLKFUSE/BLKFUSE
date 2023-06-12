<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Core.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egames_Api_Core extends Core_Api_Abstract {
  public function getCategories() {
    $table = Engine_Api::_()->getDbTable('categories', 'egames');
    return $table->fetchAll($table->select()->where('subcat_id =?', 0)->where('subsubcat_id =?', 0)->order('category_name ASC'));
  }
  public function getWidgetPageId($widgetId) {

    $db = Engine_Db_Table::getDefaultAdapter();
    $params = $db->select()
            ->from('engine4_core_content', 'page_id')
            ->where('`content_id` = ?', $widgetId)
            ->query()
            ->fetchColumn();
    return json_decode($params, true);
  }
  public function getwidgetizePage($params = array()) {

    $corePages = Engine_Api::_()->getDbtable('pages', 'core');
    $corePagesName = $corePages->info('name');
    $select = $corePages->select()
            ->from($corePagesName, array('*'))
            ->where('name = ?', $params['name'])
            ->limit(1);
    return $corePages->fetchRow($select);
  }
  public function getIdentityWidget($name, $type, $corePages) {
    if((isset($_SESSION['sespwa']['sespwa']) && !empty($_SESSION['sespwa']['sespwa'])) || (isset($_SESSION['sespwa']['mobile']) && !empty($_SESSION['sespwa']['mobile']))) {
      $widgetTable = Engine_Api::_()->getDbTable('content', 'sespwa');
      $widgetPages = Engine_Api::_()->getDbTable('pages', 'sespwa')->info('name');
    } else {
      $widgetTable = Engine_Api::_()->getDbTable('content', 'core');
      $widgetPages = Engine_Api::_()->getDbTable('pages', 'core')->info('name');
    }
    $identity = $widgetTable->select()
            ->setIntegrityCheck(false)
            ->from($widgetTable, 'content_id')
            ->where($widgetTable->info('name') . '.type = ?', $type)
            ->where($widgetTable->info('name') . '.name = ?', $name)
            ->where($widgetPages . '.name = ?', $corePages)
            ->joinLeft($widgetPages, $widgetPages . '.page_id = ' . $widgetTable->info('name') . '.page_id')
            ->query()
            ->fetchColumn();
    return $identity;
  }
}
 