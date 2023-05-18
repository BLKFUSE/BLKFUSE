<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Adtargets.php 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Model_DbTable_Adtargets extends Engine_Db_Table {

  protected $_name = 'sitead_adtargets';
  protected $_rowClass = 'Sitead_Model_Adtarget';
 
  public function getUserAdTargets($id=null) {
    // Get type
    $select = $this->select()
            ->where('userad_id = ?', $id);

    return $this->fetchRow($select);
  }

  // SET THE USER ADVERTISMENT TARGET FIELDS
  public function setUserAdTargets($params=array()) {

    if (!empty($params['userad_id'])) {
      $row = $this->getUserAdTargets($params['userad_id']);
    } else {
      return;
    }
    
    if ($row == null) {
      $row = $this->createRow();
    }
    if (null !== $row) {
      $row->setFromArray($params);
    }
    $row->save();

    $userad_id=$params['userad_id'];
   	$adtarget_id=$row->adtarget_id;
   	unset($params['userad_id']);
   	if(isset($params['birthday_enable'])){
   		unset($params['birthday_enable']);
   	}
   	$db = Zend_Db_Table_Abstract::getDefaultAdapter();
    foreach($params as $key => $value ) {
		  if(empty($value)){
			 	$sql=" UPDATE  `".$this->info('name')."` SET  `".$key."` = NULL WHERE  `".$this->info('name')."`.`adtarget_id` =".$adtarget_id." LIMIT 1 ";
				$db->query($sql);
			}		
    }
    return $row;
  }

}
