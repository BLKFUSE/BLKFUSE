<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Plugin_Core {

  public function onRenderLayoutDefaultSimple($event) {
    return $this->onRenderLayoutDefault($event, 'simple');
  }

  public function onRenderLayoutDefault($event) {

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $viewer = Engine_Api::_()->user()->getViewer();
    $request = Zend_Controller_Front::getInstance()->getRequest(); 
    $moduleName = $request->getModuleName();
    $actionName = $request->getActionName();
    $controllerName = $request->getControllerName();
    $headScript = new Zend_View_Helper_HeadScript();
    $script = '';

    $checkWelcomePage = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.check.welcome', 2);
    $checkWelcomeEnable = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.enable.welcome', 1);
    $checkWelcomePage = (($checkWelcomePage == 1 && $viewer->getIdentity() == 0) ? true : (($checkWelcomePage == 0 && $viewer->getIdentity() != 0) ? true : (($checkWelcomePage == 2) ? true : false)));
    if (!$checkWelcomeEnable)
      $checkWelcomePage = false;
    if ($actionName == 'welcome' && $controllerName == 'index' && $moduleName == 'sescontest') {
      $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
      if (!$checkWelcomePage)
        $redirector->gotoRoute(array('module' => 'sescontest', 'controller' => 'index', 'action' => 'home'), 'sescontest_general', false);
      else if ($checkWelcomeEnable == 2)
        $redirector->gotoRoute(array('module' => 'sescontest', 'controller' => 'index', 'action' => 'browse'), 'sescontest_general', false);
    }

    if ($moduleName == 'sescontest' && $actionName == 'index' && $controllerName == 'profile') {
      $bagroundImageId = Engine_Api::_()->core()->getSubject('contest')->background_photo_id;
      if ($bagroundImageId != 0 && $bagroundImageId != '') {
        $backgroundImage = Engine_Api::_()->storage()->get($bagroundImageId, '')->getPhotoUrl();
      }
      if (isset($backgroundImage)) {
        $script .= "scriptJquery(document).ready(function() {
										document.getElementById('global_wrapper').style.backgroundImage = \"url('" . $backgroundImage . "')\";
									});
								";
      }
    }
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)) {
      $openPopup = 0;
    } else {
      $openPopup = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.icon.open.smoothbox', 1);
    }
    $script .= "var isOpenPopup = '" . $openPopup . "';var showAddnewContestIconShortCut = " . Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.enable.addcontestshortcut', 1) . ";
  ";
    if ($viewer->getIdentity() != 0 && Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'create')) {
      $script .= 'scriptJquery(document).ready(function() {
			if(scriptJquery("body").attr("id").search("sescontest") > -1 && typeof showAddnewContestIconShortCut != "undefined" && showAddnewContestIconShortCut && typeof isOpenPopup != "undefined" && isOpenPopup == 1){
				scriptJquery("<a class=\'sesbasic_create_button sescontest_quick_create_button sessmoothbox sesbasic_animation\' href=\'' . $view->url(array('action' => 'create'), 'sescontest_general') . '\' title=\'Add New Contest\'><i class=\'fa fa-plus\'></i></a>").appendTo("body");
			}
            else if(scriptJquery("body").attr("id").search("sescontest") > -1 && typeof showAddnewContestIconShortCut != "undefined" && showAddnewContestIconShortCut){
				scriptJquery("<a class=\'sesbasic_create_button sescontest_quick_create_button sesbasic_animation\' href=\'' . $view->url(array('action' => 'create'), 'sescontest_general') . '\' title=\'Add New Contest\'><i class=\'fa fa-plus\'></i></a>").appendTo("body");
			}
		});';
    }
    $view->headScript()->appendScript($script);
  }

  public function onCoreCommentCreateAfter($event) {
    $payload = $event->getPayload();
    $commentedItem = $payload->getResource();
    $viewer = $payload->getPoster();
    $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
    $owner = $commentedItem->getOwner();
    if ($commentedItem->getType() != 'contest' && $commentedItem->getType() != 'participant') {
      return;
    }
    if ($commentedItem->getType() == 'contest') {
      $contestFollowers = Engine_Api::_()->getDbTable('followers', 'sescontest')->getFollowers($commentedItem->contest_id);
      if (engine_count($contestFollowers) > 0) {
        foreach ($contestFollowers as $follower) {
          $user = Engine_Api::_()->getItem('user', $follower->user_id);
          if ($user->getIdentity()) {
            Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($user, $viewer, $commentedItem, 'comment_sescontest_followed');
            Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'comment_sescontest_followed', array('member_name' => $user->getTitle(), 'contest_title' => $commentedItem->getTitle(), 'object_link' => $commentedItem->getHref(), 'host' => $_SERVER['HTTP_HOST'], 'queue' => true));
          }
        }
      }
    } else if ($commentedItem->getType() == 'participant') {
      Engine_Api::_()->getApi('mail', 'core')->sendSystem($commentedItem->getOwner(), 'comment_sescontest_entry', array('member_name' => $commentedItem->getOwner()->getTitle(), 'entry_title' => $commentedItem->getTitle(), 'object_link' => $commentedItem->getHref(), 'host' => $_SERVER['HTTP_HOST'], 'queue' => false));
    }
  }

  public function getAdminNotifications($event) {
    // Awaiting approval
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.pluginactivated'))
      return;
    $contestTable = Engine_Api::_()->getItemTable('contest');
    $select = new Zend_Db_Select($contestTable->getAdapter());
    $select->from($contestTable->info('name'), 'COUNT(contest_id) as count')->where('is_approved = ?', 0);
    $data = $select->query()->fetch();
    if (empty($data['count'])) {
      return;
    }
    $translate = Zend_Registry::get('Zend_Translate');
    $message = vsprintf($translate->translate(array(
                'There is <a href="%s">%d new contest</a> awaiting your approval.',
                'There are <a href="%s">%d new contests</a> awaiting your approval.',
                $data['count']
            )), array(
        Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sescontest', 'controller' => 'manage'), 'admin_default', true) . '?is_approved=0',
        $data['count'],
    ));
    $event->addResponse($message);
  }
  
  public function onCoreLikeCreateAfter($event){
    $payload = $event->getPayload();
      if(!empty($payload->resource_type) && $payload->resource_type == 'participant') {
      $table = Engine_Api::_()->getDbTable('likes', 'sesbasic');
      $db = $table->getAdapter();
      $db->beginTransaction();
      try {
        $item = $table->createRow();
        $item->resource_type = 'participant';
        $item->resource_id = $payload->resource_id;
        $item->poster_type = "user";
        $item->poster_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $item->creation_date = date('Y-m-d H:i:s');
        $item->save();
        $db->commit();
      } catch (Exception $e) {}
    }
  }
  
  public function onCoreLikeDeleteBefore($event) {
    $payload = $event->getPayload();
    if ($payload) {
      if (!empty($payload->resource_type ) && @$payload->resource_type == 'participant') {
        $table = Engine_Api::_()->getDbTable('likes', 'sesbasic');
        $select = $table->select()->where('resource_id =?', $payload->resource_id)->where('resource_type =?', 'participant')->where('poster_id =?',Engine_Api::_()->user()->getViewer()->getIdentity());
        $result = $table->fetchRow($select);
        if ($result)
          $result->delete();
      }
    }
  }

}
