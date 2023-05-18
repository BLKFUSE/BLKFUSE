<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Campaign.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Model_Campaign extends Core_Model_Item_Abstract {
  protected $_searchTriggers = false;
  function ctr(){
    return number_format(($this->click_count()/$this->views_count())*100 ,4);
  }
  protected function _delete() {
    if ($this->_disableHooks)
      return;
    $db = Engine_Db_Table::getDefaultAdapter();
    $adsTable = Engine_Api::_()->getDbTable('sescommunityads', 'sescommunityads');
    $select = $adsTable->select()->where('campaign_id =?',$this->getIdentity());
    $ads = $adsTable->fetchAll($select);
    //delete all ads in this campaign
    foreach($ads as $ad){
      $db->query("DELETE FROM engine4_sescommunityads_targetads WHERE sescommunityad_id = " . $ad->getIdentity());
      $db->query("DELETE FROM engine4_sescommunityads_attachments WHERE sescommunityad_id = " . $ad->getIdentity());
      $orderPackageId = $ad->orderspackage_id;
      $db->query("DELETE FROM engine4_sescommunityads_orderspackages WHERE orderspackage_id = $orderPackageId && package_id = " . $ad->package_id);
      $db->query("DELETE FROM engine4_sescommunityads_transactions WHERE orderspackage_id = $orderPackageId && package_id = " . $ad->package_id);
      //$ad->is_deleted = 1;
      //$ad->save();
      $ad->delete();
    }
    parent::_delete();
  }
  public function engine_count() {
    $table = Engine_Api::_()->getItemTable('sescommunityads');
    return $table->select()
                    ->from($table, new Zend_Db_Expr('COUNT(sescommunityad_id)'))
                    ->where('campaign_id = ?', $this->campaign_id)
                    ->where('is_deleted =?',0)
                    ->limit(1)
                    ->query()
                    ->fetchColumn();
  }
  public function views_count() {
    return $this->views_count;
  }
  public function click_count() {
    return $this->click_count;
  }
}
