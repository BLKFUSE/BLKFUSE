<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbasic
 * @package    Sesbasic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php 2015-07-25 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesbasic_Plugin_Core extends Zend_Controller_Plugin_Abstract {

	public function onRenderLayoutDefaultSimple($event) {
    return $this->onRenderLayoutDefault($event,'simple');
  }

	public function onRenderLayoutMobileDefault($event) {
    return $this->onRenderLayoutDefault($event,'simple');
  }

	public function onRenderLayoutMobileDefaultSimple($event) {
    return $this->onRenderLayoutDefault($event,'simple');
  }
  
	public function onRenderLayoutDefault($event) {

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
		
		//write code to hide header footer 
    if (isset($_GET['removeSiteHeaderFooter']) && Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesapi')){ 
       $view->headLink()->appendStylesheet($view->layout()->staticBaseUrl . 'application/modules/Sesapi/externals/styles/style.css'); 
    } 

    $themeName = $view->layout()->themes[0];
    if ($themeName == 'sesmodern' || $themeName == 'sesclean')
      include APPLICATION_PATH . '/application/modules/Sesbasic/views/scripts/theme_responsive.tpl';

		$request = Zend_Controller_Front::getInstance()->getRequest();
		$moduleName = $request->getModuleName();
		$actionName = $request->getActionName();
		$controllerName = $request->getControllerName();
		
		$script =
"var videoURLsesbasic;
 var moduleName;
 var itemType;
 var sestweet_text;
 var sesbasicdisabletooltip = ".Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbasic.disable.tooltip',0).";
 var sesbasicShowInformation = '".Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbasic.show.information',0)."';
 ";
$script .=
            "var openVideoInLightBoxsesbasic = " . Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbasic.enable.lightbox', 1) . ";
";

    $singlecart = Engine_Api::_()->getApi('settings', 'core')->getSetting('site.enble.singlecart', 0); 
    $sesproduct_enable_module = Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesproduct');
    $courses_enable_module = Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('courses');
    if($singlecart && (!$sesproduct_enable_module || !$courses_enable_module)){
      Engine_Api::_()->sesbasic()->updateCart(0);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('site_enble_singlecart', 0);
    }
    
    // Common photo lightbox work
    $viewer = Engine_Api::_()->user()->getViewer();
		if($viewer->getIdentity() == 0)
			$level = Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
		else
			$level = $viewer;
    $type = Engine_Api::_()->authorization()->getPermission($level,'album','imageviewer');
    $headScript = new Zend_View_Helper_HeadScript();
    if($type == 1) {
        $headScript->appendFile(Zend_Registry::get('StaticBaseUrl')
        . 'application/modules/Sesbasic/externals/scripts/sesimagevieweradvance/photoswipe.min.js')
        ->appendFile(Zend_Registry::get('StaticBaseUrl')
        . 'application/modules/Sesbasic/externals/scripts/sesimagevieweradvance/photoswipe-ui-default.min.js')
        ->appendFile(Zend_Registry::get('StaticBaseUrl')
        . 'application/modules/Sesbasic/externals/scripts/sesimagevieweradvance/sesalbumimagevieweradvance.js')
        ->appendFile(Zend_Registry::get('StaticBaseUrl') . 'application/modules/Sesbasic/externals/scripts/flexcroll.js');
        $view->headLink()->appendStylesheet($view->layout()->staticBaseUrl
        . 'application/modules/Sesbasic/externals/styles/photoswipe.css');
    } else {
      $headScript->appendFile(Zend_Registry::get('StaticBaseUrl').'application/modules/Sesbasic/externals/scripts/sesimagevieweradvance/sesalbumimageviewerbasic.js')
      ->appendFile(Zend_Registry::get('StaticBaseUrl') . 'application/modules/Sesbasic/externals/scripts/zoom-image/wheelzoom.js');
      $view->headLink()->appendStylesheet($view->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/medialightbox.css');
    }
    
    //Load google map
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('ses.mapApiKey', '') && Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) {
      $headScript->prependFile('https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=' . Engine_Api::_()->getApi('settings', 'core')->getSetting('ses.mapApiKey', ''));
      $headScript->appendFile($view->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/richMarker.js');
    }

    $script .=
    "var openPhotoInLightBoxSesalbum = ".Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.enable.lightbox',1).";
    var sesshowShowInfomation = ".Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.show.information', 0).";
    ";
    // Common photo lightbox work
    $sesproduct_enable_module = Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesproduct');
    $courses_enable_module = Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('courses');
    if($singlecart){
			$script .= "scriptJquery(document).on('click','.site_add_cart_dropdown',function(e){
				e.preventDefault();
				var totalcartItems = '';
				if(scriptJquery(this).hasClass('active')){
						scriptJquery('.site_single_cart_dropdown').hide();
						scriptJquery('.site_add_cart_dropdown').removeClass('active');
						return;
				}
				scriptJquery('.site_add_cart_dropdown').addClass('active');
				if(!scriptJquery(this).parent().find('.site_single_cart_dropdown').length){
						scriptJquery(this).parent().append('<div class=\"site_single_cart_dropdown sesbasic_cart_pulldown sesbasic_header_pulldown sesbasic_clearfix sesbasic_bxs\"><div class=\"sesbasic_header_pulldown_inner\"><div class=\"sesbasic_header_pulldown_loading\"><img src=\"application/modules/Core/externals/images/loading.gif\" alt=\"Loading\" /></div></div></div>');
				}
        scriptJquery('.site_single_cart_dropdown').show();";
        if ($courses_enable_module) {
                    $script .= "scriptJquery.post('courses/cart/view',{cart_page:cartviewPage},function(res){
                          
                          scriptJquery('.site_single_cart_dropdown').find('.sesbasic_header_pulldown_inner').each(function(){
                               if(scriptJquery(this).find('.sesbasic_header_pulldown_tip').length)
                                 scriptJquery(this).remove();
                          });
                          totalcartItems = totalcartItems + res;
                          scriptJquery('.site_single_cart_dropdown').html(totalcartItems); 
                    });";
        }
        if ($sesproduct_enable_module) {
         $script .= "scriptJquery.post('sesproduct/cart/view',{},function(res){
                       
                        scriptJquery('.site_single_cart_dropdown').find('.sesbasic_header_pulldown_inner').each(function(){
                            if(scriptJquery(this).find('.sesbasic_header_pulldown_tip').length)
                              scriptJquery(this).remove();
                        });
                        totalcartItems = totalcartItems + res;
                        scriptJquery('.site_single_cart_dropdown').html(totalcartItems); 
                    });";
        }
        $script .= "
        });";
        $script .= "
          scriptJquery(document).click(function(e){
          totalcartItems = '';
          var elem = scriptJquery('.site_single_cart_dropdown').parent();
          if(!elem.has(e.target).length){
            scriptJquery('.site_single_cart_dropdown').hide();
            scriptJquery('.site_add_cart_dropdown').removeClass('active');
          }
        });";
		}
    
		if($viewer->getIdentity()) {
			if($viewer->level_id != 1) {
				$script .= '
					scriptJquery(document).ready(function() {
						scriptJquery("#sesmemveroth-sesmemverothadminverificationrequests").parent().remove();
					});';
			}
		}
		
    $sesalbum_enable_module = Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('sesalbum'));
    $sesvideo_enable_module = Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('sesvideo'));
    if($actionName == 'index' && $controllerName == 'index' && $moduleName == 'core'){
     } else {
      if(($sesalbum_enable_module || $sesvideo_enable_module) && Engine_Api::_()->getApi('settings', 'core')->getSetting('ses.allow.adult.filtering',0)){
        $getvalue =  Engine_Api::_()->getApi('core', 'sesbasic')->checkAdultContent();
        if($getvalue)
          $attr = 'checked=""';
        else
          $attr = '';
        $contentAdultFiltering = '<li class="onoffswitch-wrapper"><div class="onoffswitch"><input id="myonoffswitch" name="onoffswitch"  class="onoffswitch-checkbox onoffswitch-checkbox-round" type="checkbox" '.$attr.'><label for="myonoffswitch"></label></div><span>Allow 18+ Content</span></li>';
        $script .= 'scriptJquery(document).ready(function(e){
        scriptJquery("#core_menu_mini_menu").find("ul").first().append(\''.$contentAdultFiltering.'\');
        })';
      }
    }
    $view->headScript()->appendScript($script);
  }

  public function onUserFormSignupAccountInitAfter($event) {
    $form = $event->getPayload();
    if($form->getElement('username') !== null) {
        $bannedUsernameValidator = new Engine_Validate_Callback(array(new Sesbasic_Plugin_Core(), 'checkBannedUsername'), $form->getElement('username')->getvalue());
        $bannedUsernameValidator->setMessage("This profile address is not available, please use another one.");
        $form->username->addValidator($bannedUsernameValidator);
    }
  }

  public function checkBannedUsername($value) {
    return (Engine_Api::_()->sesbasic()->checkBannedWord($value,"")) ? false : true;
  }
  public function checkBannedUsernameEditProfile($value) {
    return (Engine_Api::_()->sesbasic()->checkBannedWord($value,Engine_Api::_()->user()->getViewer()->username)) ? false : true;
  }
  public function onUserFormSettingsGeneralInitAfter($event) {
    $form = $event->getPayload();
    if($form->getElement('username') !== null) {
	$bannedUsernameValidator = new Engine_Validate_Callback(array(new Sesbasic_Plugin_Core(), 'checkBannedUsernameEditProfile'), $form->getElement('username')->getvalue());
    $bannedUsernameValidator->setMessage("This profile address is not available, please use another one.");
    $form->username->addValidator($bannedUsernameValidator);
    }
  }
}
