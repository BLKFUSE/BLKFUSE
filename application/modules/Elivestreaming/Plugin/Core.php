<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Elivestreaming
 * @package    Edocument
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php 2016-07-23 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Elivestreaming_Plugin_Core {
    public function onRenderLayoutDefaultSimple($event) {
        return $this->onRenderLayoutDefault($event,'simple');
    }
    public function onRenderLayoutMobileDefault($event) {
        return $this->onRenderLayoutDefault($event,'simple');
    }
    public function onRenderLayoutMobileDefaultSimple($event) {
        return $this->onRenderLayoutDefault($event,'simple');
    }
	public function onRenderLayoutDefault($event,$type = "simple"){

		$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
		$viewer = Engine_Api::_()->user()->getViewer();
		$request = Zend_Controller_Front::getInstance()->getRequest();


		$headScript = new Zend_View_Helper_HeadScript();
		$headScript->appendFile(Zend_Registry::get('StaticBaseUrl') .'application/modules/Elivestreaming/externals/scripts/core.js');

        $dataLiveStream =  Engine_Api::_()->elivestreaming()->getPermission(true);
        if($dataLiveStream) {
            $dataLiveStream["loggedinUserId"] = $viewer->getIdentity();
            $script = "var elLiveStreamingCheckContentData = " . json_encode($dataLiveStream) . ";";
        }else{
            $script = "var elLiveStreamingCheckContentData = false;";
        }
		 $view->headScript()->appendScript($script);
	}

}
