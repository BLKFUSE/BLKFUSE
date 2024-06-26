<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Tagcloudcategory.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_Admin_Tagcloudcategory extends Engine_Form {

  public function init() {

		$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
		$this->addElement('Text', "color", array(
			'label' => sprintf('%s to choose the color of category text. This setting will work then you chosoe Cloud View from below settings.', '<a href="' . $view->baseUrl() . "/admin/sesbasic/settings/color-chooser" . '" target="_blank">Click Here</a>'),
			'value' => '#00f',
    ));
		$this->getElement('color')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

		$this->addElement('Select', 'showType', array(
			'label' => 'View Type',
			'multiOptions' => array(
				'simple' => 'Hierarchy View',
				'tagcloud' => 'Cloud View',
			),
			'value' => 'simple',
		));

		$this->addElement('Text', "text_height", array(
			'label' => "Choose height of category text for cloud view.",
			'value' => '15',
			 'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));

		$this->addElement('Text', "height", array(
			'label' => "Choose height of category container for cloud view (in pixels).",
			'value' => '300',
			 'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));

		$this->addElement('Text', "itemCountPerPage", array(
			'label' => "Count (number of categories to show).",
			'value' => '50',
			 'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
  }

}
