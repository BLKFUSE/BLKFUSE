<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Widget_ProfileInfoController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    // Don't render this if not authorized
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    if (!Engine_Api::_()->core()->hasSubject()) {
      return $this->setNoRender();
    }

    // Get subject and check auth
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject('user');
    if (!$subject->authorization()->isAllowed($viewer, 'view')) {
      return $this->setNoRender();
    }

    $show_criterias = $this->_getParam('show_criteria', array('like', 'view', 'rating', 'friendCount', 'profileType', 'mutualFriendCount', 'joinInfo', 'updateInfo', 'network', 'location',));
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;


    // Member type
    $subject = Engine_Api::_()->core()->getSubject();
    $fieldsByAlias = Engine_Api::_()->fields()->getFieldsObjectsByAlias($subject);

    if (!empty($fieldsByAlias['profile_type'])) {
      $optionId = $fieldsByAlias['profile_type']->getValue($subject);
      if ($optionId) {
        $optionObj = Engine_Api::_()->fields()
                ->getFieldsOptions($subject)
                ->getRowMatching('option_id', $optionId->value);
        if ($optionObj) {
          $this->view->memberType = $optionObj->label;
        }
      }
    }
    $sesmember_profilemembers = Zend_Registry::isRegistered('sesmember_profilemembers') ? Zend_Registry::get('sesmember_profilemembers') : null;
    if (empty($sesmember_profilemembers))
      return $this->setNoRender();
    // Networks
    $select = Engine_Api::_()->getDbtable('membership', 'network')->getMembershipsOfSelect($subject)
            ->where('hide = ?', 0);
    $this->view->networks = Engine_Api::_()->getDbtable('networks', 'network')->fetchAll($select);

    // Friend count
    $this->view->friendCount = $subject->membership()->getMemberCount($subject);
    
    // Multiple friend mode
    $select = $subject->membership()->getMembersOfSelect();
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
  }

}
