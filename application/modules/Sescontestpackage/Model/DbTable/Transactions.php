<?php

class Sescontestpackage_Model_DbTable_Transactions extends Engine_Db_Table {

  protected $_rowClass = 'Sescontestpackage_Model_Transaction';

  public function getItemTransaction($params = array()) {
    $tableName = $this->info('name');
    $select = $this->select()->from($tableName)->where('orderspackage_id =?', $params['order_package_id'])->where('gateway_profile_id !=?', '')->limit(1);
    if (empty($params['noCondition'])) {
      $select->where('state = "pending" || state = "complete" || state = "okay" || state = "active" ');
    }
    return $this->fetchRow($select);
  }

  public function getBenefitStatus(User_Model_User $user = null) {
    // Get benefit setting
    $benefitSetting = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.payment.mod.enable');
    if (!engine_in_array($benefitSetting, array('all', 'some', 'none'))) {
      $benefitSetting = 'all';
    }

    switch ($benefitSetting) {
      default:
      case 'all':
        return true;
        break;

      case 'some':
        if (!$user) {
          return false;
        }
        return (bool) $this->select()
                        ->from($this, new Zend_Db_Expr('TRUE'))
                        ->where('owner_id = ?', $user->getIdentity())
                        ->where('status = ?', 'okay')
                        ->limit(1);
        break;

      case 'none':
        return false;
        break;
    }

    return false;
  }

}
