<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_Plugin_Core extends Zend_Controller_Plugin_Abstract{
   public function routeShutdown(Zend_Controller_Request_Abstract $request) {
  
    if (substr($request->getPathInfo(), 1, 5) == "admin") {
      $params = $request->getParams();
      if($params['module'] == 'sesdating' &&  $params['controller'] == "admin-menu") {
        $request->setModuleName('sesbasic');
        $request->setControllerName('admin-menu');
        $request->setActionName($params['action']);
        $request->setParam('moduleName', 'sesdating');
        
      }
    }
    
   }
  
	public function onRenderLayoutDefault() {
	
		$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;

    //Theme Responsive Layout work
    $theme_name = $view->layout()->themes[0];
    $enable_responseive_leyout = Engine_Api::_()->sesdating()->getContantValueXML('sesdating_responsive_layout');
    if ($theme_name == 'sesdating' && $enable_responseive_leyout == '1') {
      include APPLICATION_PATH . '/application/modules/Sesdating/views/scripts/responsive_layout.tpl';
      
    }
		
    //Google Font Work
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $usegoogleFont = $settings->getSetting('sesdating.googlefonts', 0);
    if(!empty($usegoogleFont)) {
      $string = '//fonts.googleapis.com/css?family=';
      
      $bodyFontFamily = Engine_Api::_()->sesdating()->getContantValueXML('sesdating_body_fontfamily');
      $string .= str_replace('"', '', $bodyFontFamily);
      
      $headingFontFamily = Engine_Api::_()->sesdating()->getContantValueXML('sesdating_heading_fontfamily');
      $string .= '|'.str_replace('"', '', $headingFontFamily);
      
      $mainmenuFontFamily = Engine_Api::_()->sesdating()->getContantValueXML('sesdating_mainmenu_fontfamily');
      $string .= '|'.str_replace('"', '', $mainmenuFontFamily);
      
      $tabFontFamily = Engine_Api::_()->sesdating()->getContantValueXML('sesdating_tab_fontfamily');
      $string .= '|'.str_replace('"', '', $tabFontFamily);;

      $view->headLink()->appendStylesheet($string);
    
    }
    
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$moduleName = $request->getModuleName();
		$actionName = $request->getActionName();
		$controllerName = $request->getControllerName();
		
		$setting = Engine_Api::_()->getApi('settings', 'core');		
		$script = '
			scriptJquery(window).ready(function(e){
        var height = scriptJquery(".layout_page_header").height();
				if(document.getElementById("global_wrapper")) {
					scriptJquery("#global_wrapper").css("margin-top", height+"px");
				}
			});';
    $view->headScript()->appendScript($script);
    
		if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.header.transparent', 0)) {
      $script = '
			scriptJquery(document).ready(function() {
				$(document.body).addClass("global_header_transparent"); 
				scriptJquery(document).on("scroll",function(event){
          if(window.getScrollTop() > 50 ){
            $(document.body).addClass("global_header_opacity");
          }else{
            $(document.body).removeClass("global_header_opacity");
          }
        });
			});';
			$view->headScript()->appendScript($script);
		}
		
		
	  //if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.header.design', 2) == 2){
      $view->headLink()->appendStylesheet($view->layout()->staticBaseUrl.'application/modules/Sesbasic/externals/styles/customscrollbar.css');
      $view->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl').'application/modules/Sesbasic/externals/scripts/jquery.min.js');
      $view->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl').'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js');
    //}
	}
}
