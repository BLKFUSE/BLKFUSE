<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesmusic_Plugin_Core extends Zend_Controller_Plugin_Abstract {
	 public function onStatistics($event) {
    $table = Engine_Api::_()->getDbTable('albums', 'sesmusic');
    $select = new Zend_Db_Select($table->getAdapter());
    $select->from($table->info('name'), 'COUNT(*) AS count');
    $event->addResponse($select->query()->fetchColumn(0), 'music albums');
  }
  public function onRenderLayoutDefault($event, $mode = null) {

    $request = Zend_Controller_Front::getInstance()->getRequest();
    $moduleName = $request->getModuleName();
    $actionName = $request->getActionName();
    $controllerName = $request->getControllerName();
    $viewer = Engine_Api::_()->user()->getViewer();
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    		
		$checkWelcomePage = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.check.welcome', 2);
    $checkWelcomeEnable = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.enable.welcome', 1);
    $checkWelcomePage = (($checkWelcomePage == 1 && $viewer->getIdentity() == 0) ? true : (($checkWelcomePage == 0 && $viewer->getIdentity() != 0) ? true : (($checkWelcomePage == 2) ? true : false)));
    if (!$checkWelcomeEnable)
      $checkWelcomePage = false;
    if($actionName == 'welcome' && $controllerName == 'index' && $moduleName == 'sesmusic'){
      $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
      if (!$checkWelcomePage)
        $redirector->gotoRoute(array('module' => 'sesmusic', 'controller' => 'index', 'action' => 'home'), 'sesmusic_general', false);
      else if ($checkWelcomeEnable == 2)
        $redirector->gotoRoute(array('module' => 'sesmusic', 'controller' => 'index', 'action' => 'browse'), 'sesmusic_general', false);
    }

    $script = <<<EOF
  //Cookie get and set function
  function setMusicCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires+"; path=/"; 
  } 

  function getMusicCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
    }
    return "";
  }
EOF;

    //Create New Music Icon in this plugin only
    if ($viewer->getIdentity() != 0) {
      $script .=
              "var showAddnewMusicIconShortCut = " . Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.enable.addmusichortcut', 1) . ";
      ";
      $headScript = new Zend_View_Helper_HeadScript();
      $headScript->appendFile(Zend_Registry::get('StaticBaseUrl') . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js');
      $script .= 'sesBasicAutoScroll(document).ready(function() {
      if(sesBasicAutoScroll("body").attr("id").search("sesmusic") > -1 && typeof showAddnewMusicIconShortCut != "undefined" && showAddnewMusicIconShortCut ){
      sesBasicAutoScroll("<a class=\'sesmusic_create_btn sesmusic_animation\' href=\'music/album/create\' title=\'Create New Music Album\'><i class=\'fa fa-plus\'></i></a>").appendTo("body");
      }
      });';
    }

    if ($moduleName == 'sesmusic') {
      $script .= "
        scriptJquery(document).ready(function() {
         scriptJquery('.core_main_sesmusic').parent().addClass('active');
        });";
    }
    $view->headScript()->appendScript($script);
  }

  public function onRenderLayoutMobileDefault($event) {
    return $this->onRenderLayoutDefault($event);
  }
  
  public function onUserDeleteBefore($event) {
  
    $payload = $event->getPayload();
    if( $payload instanceof User_Model_User ) {
    
      // Delete Music Albums
      $musicalbumsTable = Engine_Api::_()->getDbtable('albums', 'sesmusic');
      $select = $musicalbumsTable->select()->where('owner_id = ?', $payload->getIdentity());
      foreach( $musicalbumsTable->fetchAll($select) as $result ) {
      
        //All songs delete from the album
        foreach ($result->getSongs() as $song) {
        
          Engine_Api::_()->getDbtable('ratings', 'sesmusic')->delete(array('resource_id =?' => $song->albumsong_id, 'resource_type =?' => 'sesmusic_albumsong'));
          
          Engine_Api::_()->getDbtable('playlistsongs', 'sesmusic')->delete(array('albumsong_id =?' => $song->albumsong_id));
          
          Engine_Api::_()->getDbtable('recentlyviewitems', 'sesmusic')->delete(array('resource_id =?' => $song->albumsong_id, 'resource_type =?' => 'sesmusic_albumsong'));
          
          Engine_Api::_()->getDbtable('favourites', 'sesmusic')->delete(array('resource_id =?' => $song->albumsong_id, 'resource_type =?' => 'sesmusic_albumsong'));
          
          $song->deleteUnused();
        }
        //Delete rating accociate with deleted album
        Engine_Api::_()->getDbtable('favourites', 'sesmusic')->delete(array('resource_id =?' => $result->album_id, 'resource_type =?' => 'sesmusic_album'));
        
        Engine_Api::_()->getDbtable('recentlyviewitems', 'sesmusic')->delete(array('resource_id =?' => $result->album_id, 'resource_type =?' => 'sesmusic_album'));
        
        Engine_Api::_()->getDbtable('ratings', 'sesmusic')->delete(array('resource_id =?' => $result->album_id, 'resource_type =?' => 'sesmusic_album'));
        
        $result->delete();
      }
    }
  }
}
