<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Filter.php  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Form_Statistics_Filter extends Engine_Form {

  public function init() {
    $this
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));

    $this
            ->setAttribs(array(
                'id' => 'filter_form',
                'class' => 'global_form_box',
            ));

    // Init mode
    $this->addElement('Select', 'mode', array(
        'label' => 'See',
        'multiOptions' => array(
            'normal' => 'All',
            'cumulative' => 'Cumulative',
            'delta' => 'Change in',
        ),
        'value' => 'normal',
    ));

    $this->mode->clearDecorators()
            ->addDecorator('ViewHelper')
            ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
            ->addDecorator('HtmlTag', array('tag' => 'div'));

    $this->addElement('Select', 'type', array(
        'label' => 'Metric',
        'multiOptions' => array(
            'all' => 'All',
            'view' => 'Views',
            'click' => 'Clicks',
            'CTR' => 'CTR'
        ),
        'value' => 'all',
    ));

    $this->type->clearDecorators()
            ->addDecorator('ViewHelper')
            ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
            ->addDecorator('HtmlTag', array('tag' => 'div'));

    // Init period
    $this->addElement('Select', 'period', array(
        'label' => 'Duration',
        'multiOptions' => array(         
            Zend_Date::WEEK => 'This Week',
            Zend_Date::MONTH => 'This Month',
            Zend_Date::YEAR => 'This Year',
        ),
        'value' => 'month',
        'onchange' => 'return filterDropdown($(this))',
    ));
    $this->period->clearDecorators()
            ->addDecorator('ViewHelper')
            ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
            ->addDecorator('HtmlTag', array('tag' => 'div'));
    // Init chunk
    $this->addElement('Select', 'chunk', array(
        'label' => 'Time Summary',
        'multiOptions' => array(
            Zend_Date::DAY => 'By Day',
        ),
        'value' => 'day',
    ));
    $this->chunk->clearDecorators()
            ->addDecorator('ViewHelper')
            ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
            ->addDecorator('HtmlTag', array('tag' => 'div'));
    // Init submit
    $this->addElement('Button', 'submit', array(
        'label' => 'Filter',
        'type' => 'submit',
        'onclick' => 'return processStatisticsFilter($(this).getParent("form"))',
    ));
  }

}