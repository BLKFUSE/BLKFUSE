<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Sharetypes.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_Model_DbTable_Sharetypes extends Engine_Db_Table
{

  protected $_rowClass = 'Siteshare_Model_Sharetype';
  protected $_serializedColumns = array('params');

  public function getShareableOptions()
  {
    $modules = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
    $viewObject = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $select = $this->select()->from($this->info('name'), array('title', 'type'))
      ->where('enabled = ? ', 1)
      ->where('module_name  IN(?) ', $modules)
      ->order("order ASC");
    $options = $this->fetchAll($select);
    $shareOptions = array();
    foreach( $options as $option ) {
      $shareOptions[$option->type] = $viewObject->translate( $option->title );
    }
    return $shareOptions;
  }

  public function getItemTypeRow($type = null)
  {
    if( empty($type) ) {
      return;
    }
    $modules = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
    $select = $this->select()
      ->where('enabled = ? ', 1)
      ->where('module_name  IN(?) ', $modules)
      ->where("type = ?", $type);
    return $this
        ->fetchRow($select);
  }

}
