<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesalbum_Plugin_Core
{
  public function onStatistics($event)
  {
    $table  = Engine_Api::_()->getDbTable('photos', 'sesalbum');
    $select = new Zend_Db_Select($table->getAdapter());
    $select->from($table->info('name'), 'COUNT(photo_id) AS count');
		$select->where('album_id !=?','0');
    $event->addResponse($select->query()->fetchColumn(), 'photo');
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
	public function onRenderLayoutDefault($event,$mode=null){
		$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
		$viewer = Engine_Api::_()->user()->getViewer();
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$moduleName = $request->getModuleName();
		$actionName = $request->getActionName();
		$controllerName = $request->getControllerName();
		if(empty($_SERVER['HTTP_REFERER'])){
			$_COOKIE['sesalbum_lightbox_value']	= '';
		}
		$enableWelcomePageLoggedInUser = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.enable.welcome.logged.in',1);
    $enableWelcomePageNonloggedInUser = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.enable.welcome.nonlogged.in',1);
    if($viewer->getIdentity() != 0){
		if($actionName == 'welcome' && $controllerName == 'index' && $moduleName == 'sesalbum'){
		  $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
			if (!$enableWelcomePageLoggedInUser)
        $redirector->gotoRoute(array('action' => 'home'), 'sesalbum_general', false);
			else if ($enableWelcomePageLoggedInUser == 2)
        $redirector->gotoRoute(array('action' => 'browse'), 'sesalbum_general', false);
      else if ($enableWelcomePageLoggedInUser == 3)
        $redirector->gotoRoute(array('action' => 'photo-home'), 'sesalbum_general', false);
      else if ($enableWelcomePageLoggedInUser == 4)
        $redirector->gotoRoute(array('action' => 'browse-photo'), 'sesalbum_general', false);
		}}
    if($viewer->getIdentity() == 0){
    if($actionName == 'welcome' && $controllerName == 'index' && $moduleName == 'sesalbum'){
      $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
      if (!$enableWelcomePageNonloggedInUser)
        $redirector->gotoRoute(array('action' => 'home'), 'sesalbum_general', false);
      else if ($enableWelcomePageNonloggedInUser == 2)
        $redirector->gotoRoute(array('action' => 'browse'), 'sesalbum_general', false);
      else if ($enableWelcomePageNonloggedInUser == 3)
        $redirector->gotoRoute(array('action' => 'photo-home'), 'sesalbum_general', false);
      else if ($enableWelcomePageNonloggedInUser == 4)
        $redirector->gotoRoute(array('action' => 'browse-photo'), 'sesalbum_general', false);
    }}
		if($viewer->getIdentity() == 0)
			$level = Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
		else
			$level = $viewer;

			$type = Engine_Api::_()->authorization()->getPermission($level,'album','imageviewer');
			$headScript = new Zend_View_Helper_HeadScript();
      
		$script = '';
		//get default enable module.
		$db = Zend_Db_Table_Abstract::getDefaultAdapter();
		$activeManageModulePhotoType = $db->query("SELECT GROUP_CONCAT(content_type_photo SEPARATOR ', .feed_attachment_') as managemodulephototype FROM engine4_sesbasic_integrateothermodules WHERE enabled = 1")->fetchColumn();
		if($activeManageModulePhotoType){
			$script .= "scriptJquery(document).on('click','.feed_attachment_".$activeManageModulePhotoType."',function(e){
				if(typeof scriptJquery(this).find('div').find('a').attr('onclick') != 'undefined')
					return ;
				e.preventDefault();
				var href = scriptJquery(this).find('div').find('a').attr('href');
				if(openPhotoInLightBoxSesalbum == 0 || (openGroupPhotoInLightBoxSesalbum == 0 && href.indexOf('group_id') > -1 ) || (openEventPhotoInLightBoxSesalbum == 0 && href.indexOf('event_id') > -1)){
					window.location.href = href;
					return;
				}
				openLightBoxForSesPlugins(href);
			});
";
		}
		if($moduleName == 'sesalbum'){
			$script .=
"scriptJquery(document).ready(function(){
     scriptJquery('.core_main_sesalbum').parent().addClass('active');
    });
";
		}
		$script .=

"var openPhotoInLightBoxSesalbum = ".Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.enable.lightbox',1).";
var sesshowShowInfomation = ".Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.show.information', 1).";
var sesalbuminstall = 1;
var openGroupPhotoInLightBoxSesalbum = ".Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.enable.lightboxForGroup',1).";
var openEventPhotoInLightBoxSesalbum = ".Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.enable.lightboxForEvent',1).";
var showAddnewPhotoIconShortCut = ".Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.enable.addphotoshortcut',1).";
";
        $canCreate =  Engine_Api::_()->authorization()->isAllowed('album',Engine_Api::_()->user()->getViewer(), 'create');
if($viewer->getIdentity() != 0 && empty($_GET['format']) && $canCreate){
	$script .= 'scriptJquery(document).ready(function() {
	if(scriptJquery("body").attr("id").search("sesalbum") > -1 && typeof showAddnewPhotoIconShortCut != "undefined" && showAddnewPhotoIconShortCut ){
		scriptJquery("<a class=\'sesbasic_create_button sesbasic_animation\' href=\'albums/create\' title=\'Add New Photos\'><i class=\'fa fa-plus\'></i></a>").appendTo("body");
	}
});';
}
    $view->headScript()->appendScript($script);
	}
  public function onUserPhotoUpload($event){
    $this->onUserProfilePhotoUpload($event);
  }
  public function onUserProfilePhotoUpload($event)
  {
		/*check album plugin enable or not ,if yes then return*/
		if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('album'))
			return;
    $payload = $event->getPayload();
    if( empty($payload['user']) || !($payload['user'] instanceof Core_Model_Item_Abstract) ) {
      return;
    }
    if( empty($payload['file']) || !($payload['file'] instanceof Storage_Model_File) ) {
      return;
    }
    $viewer = $payload['user'];
    $file = $payload['file'];
    // Get album
    $table = Engine_Api::_()->getDbtable('albums', 'sesalbum');
    $album = $table->getSpecialAlbum($viewer, 'profile');
    $photoTable = Engine_Api::_()->getDbtable('photos', 'sesalbum');
    $photo = $photoTable->createRow();
    $photo->setFromArray(array(
      'owner_type' => 'user',
      'owner_id' => $viewer->getIdentity()
    ));
    $photo->save();
    $photo->setPhoto($file);
    $photo->album_id = $album->album_id;
    $photo->save();
    if( !$album->photo_id ) {
      $album->photo_id = $photo->getIdentity();
      $album->save();
    }
    $auth = Engine_Api::_()->authorization()->context;
    $auth->setAllowed($photo, 'everyone', 'view',    true);
    $auth->setAllowed($photo, 'everyone', 'comment', true);
    $auth->setAllowed($album, 'everyone', 'view',    true);
    $auth->setAllowed($album, 'everyone', 'comment', true);
    $event->addResponse($photo);
  }
  public function onUserDeleteAfter($event)
  {
    $payload = $event->getPayload();
    $user_id = $payload['identity'];
    $table   = Engine_Api::_()->getDbTable('albums', 'sesalbum');
    $select = $table->select()->where('owner_id = ?', $user_id);
    $select = $select->where('owner_type = ?', 'user');
    $rows = $table->fetchAll($select);
    foreach ($rows as $row)
    {
      $row->delete();
    }
    $table   = Engine_Api::_()->getDbTable('photos', 'sesalbum');
    $select = $table->select()->where('owner_id = ?', $user_id);
    $select = $select->where('owner_type = ?', 'user');
    $rows = $table->fetchAll($select);
    foreach ($rows as $row)
    {
      $row->delete();
    }
  }
}
