<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Core.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */


class Sesadvancedactivity_Plugin_Core extends Zend_Controller_Plugin_Abstract {

	public function routeShutdown(Zend_Controller_Request_Abstract $request) {
	
		if (!empty($_SESSION['sesadvancedactivity_twitter_token']) && empty($_GET['restApi'])) {
			$params = $request->getParams();
			if ($params['module'] == 'user' && $params['controller'] == "auth" && $params['action'] == "twitter") {
				$_SESSION['sesadvancedactivity_twitter_token'] = false;
				$request->setModuleName('sesadvancedactivity');
				$request->setControllerName('auth');
				$request->setActionName("twitter");
				//$request->setParam('moduleName', 'sesadvancedactivity');
			}
		}
	}
	
  public function onRenderLayoutMobileDefault($event) {
    return $this->onRenderLayoutDefault($event,'simple');
  }
  
	public function onRenderLayoutMobileDefaultSimple($event) {
    return $this->onRenderLayoutDefault($event,'simple');
  }
  
	public function onRenderLayoutDefaultSimple($event) {
    return $this->onRenderLayoutDefault($event,'simple');
  }
  
	public function onRenderLayoutDefault($event, $mode = null) {
    if( defined('_ENGINE_ADMIN_NEUTER') && _ENGINE_ADMIN_NEUTER ) return;
		$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
		$viewer = Engine_Api::_()->user()->getViewer();
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$moduleName = $request->getModuleName();
		$actionName = $request->getActionName();
		$controllerName = $request->getControllerName();

		$script = '';
    $script .= "var sesEmojiEnabled = 0;";
    $script .= "var sesFeedBgEnabled = 0;";
		$script .=
      "var sesAdvancedActivity = 1;";
    $script .=
    "var sesadvancedactivitybigtext = ".Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.bigtext',1).";
    var sesAdvancedactivityfonttextsize = ".Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.fonttextsize',18).";
    var sesAdvancedactivitytextlimit = ".Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.textlimit',120).";
    var sesadvServerLimit = '".ini_get('upload_max_filesize')."';
    ";
    $view->headScript()->appendScript($script);
    if(!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')){
      $emojiContent = $view->partial('emojicontent.tpl','sesadvancedactivity',array());
      $search = array(
          '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
          '/[^\S ]+\</s',  // strip whitespaces before tags, except space
          '/(\s)+/s'       // shorten multiple whitespace sequences
      );
      $replace = array(
          '>',
          '<',
          '\\1'
      );
      $emojiContent = preg_replace($search, $replace, $emojiContent);

      $script = "scriptJquery(document).ready(function() {
        scriptJquery('".$emojiContent.'<a href="javascript:;" class="exit_emoji_btn notclose" style="display:none;">'."').appendTo('body');
      });";


      $view->headScript()->appendScript($script);
    }

    //GIF Work
    if(defined('SESFEEDGIFENABLED') && !Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')) {
      $gifContent = $view->partial('gifcontent.tpl','sesadvancedactivity',array());
      $search = array(
          '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
          '/[^\S ]+\</s',  // strip whitespaces before tags, except space
          '/(\s)+/s'       // shorten multiple whitespace sequences
      );
      $replace = array(
          '>',
          '<',
          '\\1'
      );
      $gifContent = preg_replace($search, $replace, $gifContent);
      $script = "scriptJquery(document).ready(function() {
        scriptJquery('".$gifContent.'<a href="javascript:;" class="exit_gif_btn notclose" style="display:none;">'."').appendTo('body');
      });";
      $view->headScript()->appendScript($script);
    }
    //GIF Work

    //Feeling Work
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesemoji') && !Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')) {

      $feeling_emojiContent = $view->partial('feeling_emojicontent.tpl','sesemoji',array());
      $search = array(
          '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
          '/[^\S ]+\</s',  // strip whitespaces before tags, except space
          '/(\s)+/s'       // shorten multiple whitespace sequences
      );
      $replace = array(
          '>',
          '<',
          '\\1'
      );
      $feeling_emojiContent = preg_replace($search, $replace, $feeling_emojiContent);
      $script = "scriptJquery(document).ready(function() {
        scriptJquery('".$feeling_emojiContent.'<a href="javascript:;" class="feeling_exit_emoji_btn notclose" style="display:none;">'."').appendTo('body');
      });";

      $view->headScript()->appendScript($script);

      $script =
      "var sesfeelingactivityEnable = 1;";
      $script =
      "var sesEmojiEnabled = 1;";

      $view->headScript()->appendScript($script);
    }
    //Feeling Work End

    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesfeedbg')) {
      $script =
      "var sesFeedBgEnabled = 1;";
      $view->headScript()->appendScript($script);
    }

	}
  public function onItemDeleteBefore($event)
  {
    $item = $event->getPayload();

    Engine_Api::_()->getDbtable('actions', 'sesadvancedactivity')->delete(array(
      'subject_type = ?' => $item->getType(),
      'subject_id = ?' => $item->getIdentity(),
    ));

    Engine_Api::_()->getDbtable('actions', 'sesadvancedactivity')->delete(array(
      'object_type = ?' => $item->getType(),
      'object_id = ?' => $item->getIdentity(),
    ));

    if( $item instanceof User_Model_User ) {
      Engine_Api::_()->getDbtable('notifications', 'sesadvancedactivity')->delete(array(
        'user_id = ?' => $item->getIdentity(),
      ));
    }

    Engine_Api::_()->getDbtable('notifications', 'sesadvancedactivity')->delete(array(
      'subject_type = ?' => $item->getType(),
      'subject_id = ?' => $item->getIdentity(),
    ));

    Engine_Api::_()->getDbtable('notifications', 'sesadvancedactivity')->delete(array(
      'object_type = ?' => $item->getType(),
      'object_id = ?' => $item->getIdentity(),
    ));

    Engine_Api::_()->getDbtable('stream', 'sesadvancedactivity')->delete(array(
      'subject_type = ?' => $item->getType(),
      'subject_id = ?' => $item->getIdentity(),
    ));

    Engine_Api::_()->getDbtable('stream', 'sesadvancedactivity')->delete(array(
      'object_type = ?' => $item->getType(),
      'object_id = ?' => $item->getIdentity(),
    ));

    // Delete all attachments and parent posts
    $attachmentTable = Engine_Api::_()->getDbtable('attachments', 'sesadvancedactivity');
    $attachmentSelect = $attachmentTable->select()
      ->where('type = ?', $item->getType())
      ->where('id = ?', $item->getIdentity())
      ;

    $attachmentActionIds = array();
    foreach( $attachmentTable->fetchAll($attachmentSelect) as $attachmentRow )
    {
      $attachmentActionIds[] = $attachmentRow->action_id;
    }

    if( !empty($attachmentActionIds) ) {
      $attachmentTable->delete('action_id IN('.join(',', $attachmentActionIds).')');
      Engine_Api::_()->getDbtable('stream', 'sesadvancedactivity')->delete('action_id IN('.join(',', $attachmentActionIds).')');
    }

  }
   public function onUserLogoutAfter(){
    //unset linkedin session content
     unset($_SESSION['linkedin_lock']);
     unset($_SESSION['linkedin_uid']);
     unset($_SESSION['linkedin_secret']);
     unset($_SESSION['linkedin_token']);
     unset($_SESSION['linkedin_token']);
     unset($_SESSION['linkedin_access']);
  }
  public function getActivity($event)
  {
    // Detect viewer and subject
    $payload = $event->getPayload();
    $user = null;
    $subject = null;
    if( $payload instanceof User_Model_User ) {
      $user = $payload;
    } else if( is_array($payload) ) {
      if( isset($payload['for']) && $payload['for'] instanceof User_Model_User ) {
        $user = $payload['for'];
      }
      if( isset($payload['about']) && $payload['about'] instanceof Core_Model_Item_Abstract ) {
        $subject = $payload['about'];
      }
    }
    if( null === $user ) {
      $viewer = Engine_Api::_()->user()->getViewer();
      if( $viewer->getIdentity() ) {
        $user = $viewer;
      }
    }
    if( null === $subject && Engine_Api::_()->core()->hasSubject() ) {
      $subject = Engine_Api::_()->core()->getSubject();
    }
    $event->addResponse(array(
          'type' => 'friend',
          'data' => Engine_Api::_()->user()->getViewer()->getIdentity(),
        ));
    // Members Lists
    if( $user ) {
      $data = Engine_Api::_()->getDbTable('actions','sesadvancedactivity')->getListsIds();
      if( !empty($data) ) {
        $event->addResponse(array(
          'type' => 'members_list',
          'data' => $data,
        ));
      }
    }

  }

  public function onActivityActionCreateAfter($event)
  {
    $settings = Engine_Api::_()->getApi('settings', 'core');
    if ($settings->getSetting('sesadvancedactivity.pluginactivated')) {// GitHub Issue #119
      $item = $event->getPayload();
      if (!empty($item->action_id)) {
        $detailsTable = Engine_Api::_()->getDbTable('details', 'sesadvancedactivity');
        $isRowExists = $detailsTable->isRowExists($item->action_id);
        if (empty($isRowExists)) {
          $row = $detailsTable->createRow();
          $row->action_id = $item->action_id;
          $row->save();
        }
      }
    }
  }


  public function addActivity($event)
  {}
}
