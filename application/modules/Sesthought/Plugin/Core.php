<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2016-2017 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php  2017-01-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesthought_Plugin_Core {
  
	public function onRenderLayoutDefault($event,$mode=null) {
	
		$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
		$script = '';
		if(Engine_Api::_()->getApi('settings', 'core')->getSetting('core.iframely.secretIframelyKey')) {
      $script .= "var iframlyEndbled = 1;";
    } else { 
      $script .= "var iframlyEndbled = 0;";
    }
    $view->headScript()->appendScript($script);
	}
}