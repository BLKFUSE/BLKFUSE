<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: VoteGraph.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Form_Admin_VoteGraph extends Engine_Form {

  public function init() {
    $headScript = new Zend_View_Helper_HeadScript();
    $headScript->appendFile(Zend_Registry::get('StaticBaseUrl') . 'externals/ses-scripts/jscolor/jscolor.js');
    
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $script = "var hashSign = '#';";
    $view->headScript()->appendScript($script);
    $this->addElement('Text', 'heading', array(
        'label' => 'Heading for the Graph (Inside look).',
        'value' => '',
    ));
    $this->addElement('Text', "votelinecolor", array(
        'label' => 'Choose the color of graph line for Votes.',
        'value' => '#fff',
        'class' => 'SEScolor',
    ));
    $this->addElement('Text', "likelinecolor", array(
        'label' => 'Choose the color of graph line for Likes.',
        'value' => '#ea623d',
        'class' => 'SEScolor',
    ));
    $this->addElement('Text', "commentlinecolor", array(
        'label' => 'Choose the color of graph line for Comments.',
        'value' => '#fff',
        'class' => 'SEScolor',
    ));
    $this->addElement('Text', "viewlinecolor", array(
        'label' => 'Choose the color of graph line for Views.',
        'value' => '#ea623d',
        'class' => 'SEScolor',
    ));
    $this->addElement('Text', "favouritelinecolor", array(
        'label' => 'Choose the color of graph line for Favourites',
        'value' => '#fff',
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
