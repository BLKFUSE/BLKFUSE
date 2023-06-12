<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Stats.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescommunityads_Form_Admin_Stats extends Engine_Form {

  public function init() {
    $headScript = new Zend_View_Helper_HeadScript();
    $headScript->appendFile(Zend_Registry::get('StaticBaseUrl') . 'externals/ses-scripts/jscolor/jscolor.js');
    
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $script = "var hashSign = '#';";
    $view->headScript()->appendScript($script);
    
    $this->addElement('Text', "ctlinecolor", array(
        'label' => 'Choose the color of graph line for CTL.',
        'value' => '#fff',
        'class' => 'SEScolor',
    ));
    $this->addElement('Text', "clicklinecolor", array(
        'label' => 'Choose the color of graph line for Clicks.',
        'value' => '#ea623d',
        'class' => 'SEScolor',
    ));
   
    $this->addElement('Text', "viewlinecolor", array(
        'label' => 'Choose the color of graph line for Views.',
        'value' => '#ea623d',
        'class' => 'SEScolor',
    ));
   
    $this->addElement('MultiCheckbox', 'criteria', array(
        'label' => "Interval for the dates on x-axis.",
        'multiOptions' => array(
            'hourly' => 'Hourly',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
        ),
        'escape' => false,
    ));

    $this->addElement('Select', 'openViewType', array(
        'label' => "Choose the view type which you want to display by default. (Settings will apply, if you have selected more than one option in above tab.)",
        'multiOptions' => array(
            'hourly' => 'Hourly',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
        ),
        'value' => 'daily',
    ));
  }

}
