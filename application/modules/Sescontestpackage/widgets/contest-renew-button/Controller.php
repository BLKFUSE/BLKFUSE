<?php

class Sescontestpackage_Widget_ContestRenewButtonController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $contest_id = $this->_getParam('contest_id', false);
    if ((!Engine_Api::_()->core()->hasSubject() && !$contest_id ) || !Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage')) {
      return $this->setNoRender();
    }
    if ($contest_id)
      $contest = $this->view->contest = Engine_Api::_()->getItem('contest', $contest_id);
    else
      $contest = $this->view->contest = Engine_Api::_()->core()->getSubject();

    $this->view->transaction = Engine_Api::_()->getDbTable('transactions', 'sescontestpackage')->getItemTransaction(array('order_package_id' => $contest->orderspackage_id, 'contest' => $contest));
    $this->view->package = Engine_Api::_()->getItem('sescontestpackage_package', $contest->package_id);
    if (!$this->view->package)
      return $this->setNoRender();
  }

}
