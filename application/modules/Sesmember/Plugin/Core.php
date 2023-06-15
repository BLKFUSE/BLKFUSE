<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Core.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Plugin_Core extends Zend_Controller_Plugin_Abstract {

  public function routeShutdown(Zend_Controller_Request_Abstract $request) {

    $module = $request->getModuleName();
    $controller = $request->getControllerName();
    $action = $request->getActionName();
    //don't change module if call is from webserice (ios and android)
    if (defined("_SESAPI_R_TARG"))
      return;
    if ($module == "user") {
      if ($controller == "index" && $action == "browse") {
        $request->setModuleName('sesmember');
        $request->setControllerName('index');
        $request->setActionName('browse');
      } elseif ($controller == "index" && $action == "home") {
        $viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer->getIdentity())
          return;
        $checkLevelId = Engine_Api::_()->getDbtable('homepages', 'sesmember')->checkLevelId($viewer->level_id, '0', 'home');
        if ($checkLevelId) {
          $request->setModuleName('sesmember');
          $request->setControllerName('index');
          $request->setActionName('home');
        }
      } elseif ($controller == "profile" && $action == "index") {
        $requestParams = $request->getParams();
        $action_id = !empty($requestParams['action_id']) ? $requestParams['action_id'] : '';
        $id = Zend_Controller_Front::getInstance()->getRequest()->getParam('id');
        if (null !== $id) {
          $subject = Engine_Api::_()->user()->getUser($id);
        }

        if($action_id){
          $actionActivity = Engine_Api::_()->getItem('activity_action',$action_id);
          $action_id_url = '/action_id/'.$action_id;
        }else
          $action_id_url = '';
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $getmodule = Engine_Api::_()->getDbTable('modules', 'core')->getModule('core');
        if ( $this->getRequest()->isSecure() ) {
          $url = 'https://'.$_SERVER['HTTP_HOST'].$subject->getHref().$action_id_url;
        }else
          $url = 'http://'.$_SERVER['HTTP_HOST'].$subject->getHref().$action_id_url;
        if (strpos($subject->getPhotoUrl(), 'http') === FALSE)
          $image = 'http://' . $_SERVER['HTTP_HOST'] . $subject->getPhotoUrl();
        else
          $image = $subject->getPhotoUrl();
        if(!empty($actionActivity)){
          $description = $actionActivity->body;
          //if(!preg_match('/[A-Z]+[a-z]+[0-9]+/', $description))
            //$description = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.description');
        }else
          $description = '';
        if (!empty($getmodule->version) && version_compare($getmodule->version, '4.8.8') >= 0) {
          $view->doctype('XHTML1_RDFA');
          $view->headMeta()->setProperty('og:type', 'website');
          $view->headMeta()->setProperty('og:url', $url);
          $view->headMeta()->setProperty('og:title', strip_tags($subject->getTitle()));
          $view->headMeta()->setProperty('og:description', $description);
          $view->headMeta()->setProperty('og:image', $image);
          $view->headMeta()->setProperty('twitter:card', 'summary_large_image');
          $view->headMeta()->setProperty('twitter:url', $url);
          $view->headMeta()->setProperty('twitter:title', strip_tags($subject->getTitle()));
          $view->headMeta()->setProperty('twitter:description', $description);
          $view->headMeta()->setProperty('twitter:image',$image);
        }

        if($subject && isset($subject->level_id)) {
          $checkLevelId = Engine_Api::_()->getDbtable('homepages', 'sesmember')->checkLevelId(@$subject->level_id, '0', 'profile');
          if ($checkLevelId) {
            $request->setModuleName('sesmember');
            $request->setControllerName('profile');
            $request->setActionName('index');
          }
        }
      }
    }
  }

  public function onRenderLayoutDefaultSimple($event) {
    return $this->onRenderLayoutDefault($event, 'simple');
  }

  public function onRenderLayoutMobileDefault($event) {
    return $this->onRenderLayoutDefault($event, 'simple');
  }

  public function onRenderLayoutMobileDefaultSimple($event) {
    return $this->onRenderLayoutDefault($event, 'simple');
  }

  public function onRenderLayoutDefault($event) {

    $request = Zend_Controller_Front::getInstance()->getRequest();
    $module = $request->getModuleName();
    $controller = $request->getControllerName();
    $action = $request->getActionName();
		if ($module == 'user' && $action == 'index' && $controller == 'signup') {
			$headScript = new Zend_View_Helper_HeadScript();
			$headScript->appendFile(Zend_Registry::get('StaticBaseUrl') . 'application/modules/Sesmember/externals/scripts/core.js');
		}
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $view->headTranslate(array(
        Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.unfollowtext', 'Unfollow'), Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.followtext', 'Follow')
    ));
    
    $headScript = new Zend_View_Helper_HeadScript();
    $headScript->appendFile(Zend_Registry::get('StaticBaseUrl') . 'application/modules/Sesmember/externals/scripts/core.js');

    $script = '';
    $script .= "var sesmemeberLocation = '" . Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.showsignup.location', 1). "';";
    $script .= "var enableLocation = '" . Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1). "';";
    $script .= "var sesmemeberFollow = '" . Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.followtext', 'Follow') . "';
    var sesmemberUnfollow = '" . Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.unfollowtext', 'Unfollow') . "';
    ";

    if ($module == 'sesmember') {
      $script .= "scriptJquery(document).ready(function() {
        if(!(scriptJquery('.core_main_home').parent().hasClass('active')))
        scriptJquery('.core_main_sesmember').parent().addClass('active');
      });
      ";
    }
    
    //Location fields show on signup page
    $viewerId = Engine_Api::_()->user()->getViewer()->getIdentity();
    if ($module == 'user' && $controller == 'signup' && $action == 'index' && empty($viewerId) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.showsignup.location', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) {
			$headScript = new Zend_View_Helper_HeadScript();
			$headScript->appendFile(Zend_Registry::get('StaticBaseUrl') . 'application/modules/Sesmember/externals/scripts/core.js');
		}
    
    $view->headScript()->appendScript($script);

    
    if (($module == 'user' || $module == 'sesmember') && $controller == 'profile' && $action == 'index' && $viewerId) {
      $subject = Engine_Api::_()->core()->getSubject();
      if (!$subject)
        return;
      $dbGetInsert = Engine_Db_Table::getDefaultAdapter();
      $viewer = Engine_Api::_()->user()->getViewer();
      if ($viewer->getIdentity() == $subject->getIdentity())
        return;
      $dbGetInsert->query("INSERT INTO engine4_sesmember_userviews (resource_id, user_id, creation_date) VALUES (" . $subject->getIdentity() . "," . $viewer->getIdentity() . ",'" . date('Y-m-d H:i:s') . "') 	ON DUPLICATE KEY UPDATE 	`creation_date` = '" . date('Y-m-d H:i:s') . "'");
    }
  }

  public function onUserCreateAfter($event) {

    $user = $event->getPayload();
    
    $userinfoTable = Engine_Api::_()->getItemTable('sesmember_userinfo');

    $dbGetInsert = Engine_Db_Table::getDefaultAdapter();
    $dbGetInsert->query('INSERT IGNORE INTO engine4_sesmember_userinfos (user_id) VALUES ("'.$user->user_id.'")');

    if (isset($_SESSION['ses_lat']) && isset($_SESSION['ses_lng']) && $_SESSION['ses_lat'] != '' && $_SESSION['ses_lng'] != '' && !empty($_SESSION['ses_location'])) {
        $userinfoTable->update(array(
            'location' => $_SESSION['ses_location'],
                ), array(
            'user_id = ?' => $user->user_id,
        ));
      $dbGetInsert->query('INSERT IGNORE INTO engine4_sesbasic_locations (resource_id, lat, lng ,city,state,zip,country, resource_type) VALUES ("' . $user->user_id . '","' . $_SESSION['ses_lat'] . '","' . $_SESSION['ses_lng'] . '","' . $_SESSION['ses_city'] . '","' . $_SESSION['ses_state'] . '","' . $_SESSION['ses_zip'] . '","' . $_SESSION['ses_country'] . '", "user")');
    }
    
    //Mobile App Work
    if(isset($_SESSION['Sesapi_Form_Signup_Account'])) {
      if (isset($_SESSION['Sesapi_Form_Signup_Account']['data']['ses_lat']) && isset($_SESSION['Sesapi_Form_Signup_Account']['data']['ses_lng']) && $_SESSION['Sesapi_Form_Signup_Account']['data']['ses_lat'] != '' && $_SESSION['Sesapi_Form_Signup_Account']['data']['ses_lng'] != '' && !empty($_SESSION['Sesapi_Form_Signup_Account']['data']['ses_location'])) {
          $userinfoTable->update(array(
              'location' => $_SESSION['Sesapi_Form_Signup_Account']['data']['ses_location'],
                  ), array(
              'user_id = ?' => $user->user_id,
          ));
        $dbGetInsert->query('INSERT IGNORE INTO engine4_sesbasic_locations (resource_id, lat, lng ,city,state,zip,country, resource_type) VALUES ("' . $user->user_id . '","' . $_SESSION['Sesapi_Form_Signup_Account']['data']['ses_lat'] . '","' . $_SESSION['Sesapi_Form_Signup_Account']['data']['ses_lng'] . '","' . $_SESSION['Sesapi_Form_Signup_Account']['data']['ses_city'] . '","' . $_SESSION['Sesapi_Form_Signup_Account']['data']['ses_state'] . '","' . $_SESSION['Sesapi_Form_Signup_Account']['data']['ses_zip'] . '","' . $_SESSION['Sesapi_Form_Signup_Account']['data']['ses_country'] . '", "user")');
      }
    }
  }

  public function onCoreLikeCreateAfter($payload) {

    $isAutoApproved = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.user.approved', 1);
    $isLikeBased = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.approve.criteria', 1);

    if (empty($isAutoApproved) || empty($isLikeBased))
      return;

    $item = $payload->getPayload();
    if ($item->resource_type != 'user')
      return;
    Engine_Api::_()->sesmember()->updateUserInfo($item->resource_id, 'create');
  }

  public function onCoreLikeDeleteAfter($payload) {
    $item = $payload->getPayload();
    Engine_Api::_()->sesmember()->updateUserInfo($item['identity'], 'delete');
  }

  public function onCoreLikeDeleteBefore($payload) {
    $item = $payload->getPayload();
    if ($item->resource_type != 'user') {
      unset($_SESSION['sesmember_content_like_id']);
      return;
    }
    $_SESSION['sesmember_content_like_id'] = $item->resource_id;
  }

  public function onFieldMetaCreate($payload) {
    $item = $payload->getPayload();
    Engine_Db_Table::getDefaultAdapter()->update('engine4_user_fields_meta', array('ses_field' => $_POST['ses_field']), array('field_id = ?' => $item->field_id));
  }

  public function onFieldMetaEdit($payload) {
    $item = $payload->getPayload();
    Engine_Db_Table::getDefaultAdapter()->update('engine4_user_fields_meta', array('ses_field' => $_POST['ses_field']), array('field_id = ?' => $item->field_id));
  }

  public function onItemCreateAfter($event) {

    $payload = $event->getPayload();
    $type = $payload->getType();
    $id = $payload->getIdentity();
    $viewer = Engine_Api::_()->user()->getViewer();
    //Start Follow Work: notification and email to all followers

    $notification_type = $itemtype = '';
    if ($type == 'album' || $type == 'classified' || $type == 'blog' || $type == 'group' || $type == 'event' || $type == 'forum_topic') {
      $notification_type = 'sesmember_follow_create';
      $item = Engine_Api::_()->getItem($type, $id);
      if($item)
      $itemtype = $item->getShortType();
    }
    if ($notification_type) {
      $followersIds = Engine_Api::_()->getDbTable('follows', 'sesmember')->getFollowers($viewer->getIdentity());
      if ($followersIds) {
        foreach ($followersIds as $followersId) {
          $user = Engine_Api::_()->getItem('user', $followersId->resource_id);
          if($user->getIdentity() && $item && $item->getIdentity())
          Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($user, $viewer, $item, $notification_type, array("itemtype" => $itemtype));
        }
      }
    }
    //End Follow Work: notification and email work
  }

  public function onUserDeleteBefore($event) {
    $payload = $event->getPayload();
    if ($payload instanceof User_Model_User) {
      // Delete videos
      $reviewTable = Engine_Api::_()->getDbtable('reviews', 'sesmember');
      $reviewVoteTable = Engine_Api::_()->getDbtable('reviewvotes', 'sesmember');
      $reviewSelect = $reviewTable->select()->where('owner_id = ?', $payload->getIdentity());
      foreach ($reviewTable->fetchAll($reviewSelect) as $review) {
        $reviewTable->delete(array('review_id =?' => $review['review_id']));
        $reviewVoteTable->delete(array('review_id =?' => $review['review_id']));
      }
      $reviewSelect = $reviewTable->select()->where('user_id = ?', $payload->getIdentity());
      foreach ($reviewTable->fetchAll($reviewSelect) as $review) {
        $reviewTable->delete(array('review_id =?' => $review['review_id']));
        $reviewVoteTable->delete(array('review_id =?' => $review['review_id']));
      }
      $reviewParameterTable = Engine_Api::_()->getDbtable('parametervalues', 'sesmember');
      $reviewParameterSelect = $reviewParameterTable->select()->where('resources_id = ?', $payload->getIdentity());
      foreach ($reviewParameterTable->fetchAll($reviewParameterSelect) as $reviewParameter) {
        $reviewParameterTable->delete(array('parametervalue_id =?' => $reviewParameter['parametervalue_id']));
      }
      $reviewParameterSelect = $reviewParameterTable->select()->where('user_id = ?', $payload->getIdentity());
      foreach ($reviewParameterTable->fetchAll($reviewParameterSelect) as $reviewParameter) {
        $reviewParameterTable->delete(array('parametervalue_id =?' => $reviewParameter['parametervalue_id']));
      }

      $userViewsTable = Engine_Api::_()->getDbtable('userviews', 'sesmember');
      $viewTableSelect = $userViewsTable->select()->where('resource_id = ?', $payload->getIdentity());
      foreach ($userViewsTable->fetchAll($viewTableSelect) as $result) {
        $userViewsTable->delete(array('view_id =?' => $result['view_id']));
      }
      $viewTableSelect = $userViewsTable->select()->where('user_id = ?', $payload->getIdentity());
      foreach ($userViewsTable->fetchAll($viewTableSelect) as $result) {
        $userViewsTable->delete(array('view_id =?' => $result['view_id']));
      }

      $userFollowsTable = Engine_Api::_()->getDbtable('follows', 'sesmember');
      $followTableSelect = $userFollowsTable->select()->where('resource_id = ?', $payload->getIdentity());
      foreach ($userFollowsTable->fetchAll($followTableSelect) as $result) {
        $userFollowsTable->delete(array('follow_id =?' => $result['follow_id']));
      }
      $followTableSelect = $userFollowsTable->select()->where('user_id = ?', $payload->getIdentity());
      foreach ($userFollowsTable->fetchAll($followTableSelect) as $result) {
        $userFollowsTable->delete(array('follow_id =?' => $result['follow_id']));
      }
    }
  }
}
