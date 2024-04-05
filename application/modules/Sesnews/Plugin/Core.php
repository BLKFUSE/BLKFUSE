<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Plugin_Core {

  public function onStatistics($event) {
    $table  = Engine_Api::_()->getDbTable('news', 'sesnews');
    $select = new Zend_Db_Select($table->getAdapter());
    $select->from($table->info('name'), 'COUNT(*) AS count');
    $event->addResponse($select->query()->fetchColumn(0), 'news');
  }

	public function onRenderLayoutDefault($event){
    if( defined('_ENGINE_ADMIN_NEUTER') && _ENGINE_ADMIN_NEUTER ) return;
		$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
		$viewer = Engine_Api::_()->user()->getViewer();
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$moduleName = $request->getModuleName();
		$actionName = $request->getActionName();
		$controllerName = $request->getControllerName();

		$checkWelcomeEnable = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.welcome',1);
		if($actionName == 'welcome' && $controllerName == 'index' && $moduleName == 'sesnews') {
		  $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
			if(!$checkWelcomeEnable){
				$redirector->gotoRoute(array('module' => 'sesnews', 'controller' => 'index', 'action' => 'home'), 'sesnews_general', false);
			}
			elseif($checkWelcomeEnable == '2') {
				$redirector->gotoRoute(array('module' => 'sesnews', 'controller' => 'index', 'action' => 'browse'), 'sesnews_general', false);
			} else if($checkWelcomeEnable == '1') {

        $checkWelcomePage = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.check.welcome',2);

        $checkWelcomePage = (($checkWelcomePage == 1 && $viewer->getIdentity() == 0) ? true : (($checkWelcomePage == 0 && $viewer->getIdentity() != 0) ? true : (($checkWelcomePage == 2) ? true : false)));
        if(!$checkWelcomePage && $actionName == 'welcome' && $controllerName == 'index' && $moduleName == 'sesnews'){
          $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
          $redirector->gotoRoute(array('module' => 'sesnews', 'controller' => 'index', 'action' => 'home'), 'sesnews_general', false);
        }
			}
		}
    
		$script = '';
		if($moduleName == 'sesnews'){
			$script .=
"scriptJquery(document).ready(function(){
     scriptJquery('.core_main_sesnews').parent().addClass('active');
    });
";
		}
		$script .= "var newsURLsesnews = '" . Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.news.manifest', 'news') . "';";
		 $view->headScript()->appendScript($script);
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
  public function onUserDeleteBefore($event)
  {
    $payload = $event->getPayload();
    if( $payload instanceof User_Model_User ) {
      // Delete sesnews
      $sesnewsTable = Engine_Api::_()->getDbtable('news', 'sesnews');
      $sesnewsSelect = $sesnewsTable->select()->where('owner_id = ?', $payload->getIdentity());
      foreach( $sesnewsTable->fetchAll($sesnewsSelect) as $sesnews ) {
        Engine_Api::_()->sesnews()->deleteNews($sesnews);;
      }
    }
  }
}
