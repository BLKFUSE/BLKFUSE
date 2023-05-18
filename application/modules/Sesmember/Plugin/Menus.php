<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Menus.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Plugin_Menus {

  public function canViewProfileTypepage($row) {
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    if(empty($viewer_id))
      return false;
    else
      return true;
  }
  
  public function canEditLocation() {
    if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1))
      return false;
    $viewer = Engine_Api::_()->user()->getViewer();
    if(empty($viewer->location))
      return false;
    $subject = Engine_Api::_()->getItem('user', $viewer->getIdentity());
    $getLoggedInuserLocation = Engine_Api::_()->getDbTable('locations', 'sesbasic')->getLocationData($subject->getType(), $subject->getIdentity());
    if(empty($getLoggedInuserLocation))
      return false;
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $label = $view->translate("Edit Location");
    $action = 'edit-location';
    $class = '';
    if (empty($subject->location)) {
      $label = $view->translate("Add Location");
      $action = 'add-location';
      $class = 'smoothbox';
    }
    if ($subject->authorization()->isAllowed($viewer, 'edit')) {
      if (!$subject->getIdentity()) {
        $id = $viewer->getIdentity();
      } else {
        $id = $subject->getIdentity();
      }
      return array(
          'label' => $label,
          'route' => 'sesmember_general',
          'params' => array(
              'module' => 'sesmember',
              'controller' => 'index',
              'action' => $action,
          //'id' => $id,
          ),
          'class' => $class,
      );
    }
    else
      return false;
  }

  public function onMenuInitialize_SesmemberLocationEdit($row) {
    if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1))
      return false;
    $viewer = Engine_Api::_()->user()->getViewer();
    $subject = Engine_Api::_()->core()->getSubject();
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $label = $view->translate('Edit Location');
    $action = 'edit-location';
    $class = '';
    $getLoggedInuserLocation = Engine_Api::_()->getDbTable('locations', 'sesbasic')->getLocationData($subject->getType(), $subject->getIdentity());
    if (empty($getLoggedInuserLocation)) {
      $label = $view->translate('Add Location');
      $action = 'add-location';
      $class = 'smoothbox';
    }
    if ($subject->authorization()->isAllowed($viewer, 'edit') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.enable.location', 1)) {
      return array(
          'label' => $label,
          'route' => 'sesmember_general',
          'params' => array(
              'module' => 'sesmember',
              'controller' => 'index',
              'action' => $action,
              'id' => ( $subject->getIdentity() ),
          ),
          'class' => $class,
      );
    }
    return false;
  }

  public function nearestMember() {
    if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1))
      return false;
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!$viewer)
      return false;
    else {
      $getLoggedInuserLocation = Engine_Api::_()->getDbTable('locations', 'sesbasic')->getLocationData($viewer->getType(), $viewer->getIdentity());
      if (!$getLoggedInuserLocation)
        return false;
      else
        return true;
    }
  }

  public function locationEnable() {
    if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1))
      return false;
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.enable.location', 1)) {
      return false;
    }
    return true;
  }

  public function reviewEnable() {
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.allow.review', 1) || !Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'view')) {
      return false;
    }
    return true;
  }

  public function onMenuInitialize_SesmemberReviewProfileEdit() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $review = Engine_Api::_()->core()->getSubject();
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    if (!(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.allow.review', 0)))
      return false;
    if (!$viewer->getIdentity())
      return false;
    if (!$review->authorization()->isAllowed($viewer, 'edit'))
      return false;
    return array(
        'label' => $view->translate('Edit Review'),
        'class' => 'smoothbox sesbasic_icon_edit',
        'route' => 'sesmember_review_view',
        'params' => array(
            'action' => 'edit-review',
            'review_id' => $review->getIdentity(),
            'slug' => $review->getSlug(),
        )
    );
  }

  public function onMenuInitialize_SesmemberReviewProfileReport() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $review = Engine_Api::_()->core()->getSubject();
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;

    if (!(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.show.report', 1)))
      return false;

    if (!$viewer->getIdentity())
      return false;

    return array(
        'label' => $view->translate('Report'),
        'class' => 'smoothbox sesbasic_icon_report',
        'route' => 'default',
        'params' => array(
            'module' => 'core',
            'controller' => 'report',
            'action' => 'create',
            'subject' => $review->getGuid(),
            'format' => 'smoothbox',
        ),
    );
  }

  public function onMenuInitialize_SesmemberReviewProfileShare() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $review = Engine_Api::_()->core()->getSubject();
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;

    if (!(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.allow.share', 1)))
      return false;

    if (!$viewer->getIdentity())
      return false;

    if (!$viewer->getIdentity())
      return false;

    return array(
        'label' => $view->translate('Share'),
        'class' => 'smoothbox sesbasic_icon_share',
        'route' => 'default',
        'params' => array(
            'module' => 'activity',
            'controller' => 'index',
            'action' => 'share',
            'type' => $review->getType(),
            'id' => $review->getIdentity(),
            'format' => 'smoothbox',
        ),
    );
  }

  public function onMenuInitialize_SesmemberReviewProfileDelete() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $review = Engine_Api::_()->core()->getSubject();
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    if (!$viewer->getIdentity())
      return false;
    if (!$review->authorization()->isAllowed($viewer, 'delete'))
      return false;
    return array(
        'label' => $view->translate('Delete Review'),
        'class' => 'smoothbox sesbasic_icon_delete',
        'route' => 'sesmember_review_view',
        'params' => array(
            'action' => 'delete',
            'review_id' => $review->getIdentity(),
            'format' => 'smoothbox',
        ),
    );
  }
}
