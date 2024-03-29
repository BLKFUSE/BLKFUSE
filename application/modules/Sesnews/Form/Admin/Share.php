<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Share.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesnews_Form_Admin_Share extends Engine_Form {

  public function init() {
			$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    	$fileLink = $view->baseUrl() . '/admin/sesbasic/settings/global';
			$headScript = new Zend_View_Helper_HeadScript();
			
			$script='
			scriptJquery(document).ready(function(){
        var text = scriptJquery("#advShareOptions-addThis").parent().find("label").html().replace(/\&lt;/g,"<");
        text = text.replace(/\&gt;/g,">");
        scriptJquery("#advShareOptions-addThis").parent().find("label").html(text);
			})';
		$view->headScript()->appendScript($script);
      $this->addElement(
					'MultiCheckbox',
					'advShareOptions',
					array(
						'label' => "Choose from below the options to be shown in this widget.",
						'multiOptions' => array(
							'privateMessage' => 'Send as Message [Private Message]',
							'siteShare' => 'Share via Activity Feed [with Message]',
							'quickShare' => 'Quick AJAX Share via Activity Feed',
							'addThis' => 'Add This Share Options [Enter your "Add This Publisher Id" from <a target="_blank" href="' . $fileLink . '">here</a> to enable this option.]',
						),
					)
				);
  }

}
