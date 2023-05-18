<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Widget_PaymentStatusController extends Engine_Content_Widget_Abstract {
  public function indexAction() {
    $sescommunityad_id = $this->_getParam('sescommunityad_id', false);
    if ((!Engine_Api::_()->core()->hasSubject() && !$sescommunityad_id )) {
      return $this->setNoRender();
    }
    if ($sescommunityad_id)
      $ad = $this->view->ad = Engine_Api::_()->getItem('sescommunityads', $sescommunityad_id);
    else
      $ad = $this->view->ad = Engine_Api::_()->core()->getSubject();

    $this->view->transaction = Engine_Api::_()->getDbTable('transactions', 'sescommunityads')->getItemTransaction(array('order_package_id' => $ad->orderspackage_id, 'ad' => $ad));
    $this->view->package = Engine_Api::_()->getItem('sescommunityads_packages', $ad->package_id);
    if (!$this->view->package)
      return $this->setNoRender();
  }
}
