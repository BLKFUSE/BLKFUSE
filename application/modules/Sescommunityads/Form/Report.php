<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Report.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Form_Report extends Engine_Form {
 public function init() {
    
    //get current logged in user
    $user = Engine_Api::_()->user()->getViewer();
    $this->setTitle('Generate Report')
            ->setDescription('You can generate report of Campaigns/Advertisements')
            ->setAttrib('id', 'sescommunityads_report')
						->setAttrib('class', 'global_form')
            
            ->setMethod("POST")
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
		
		$this->addElement('Select', 'format_type', array(
          'label' => 'Report By',
          'multiOptions' => array('campaign'=>'Campaign','ads'=>'Ads'),
          'onChange'=>'formate(this.value);'
    ));
        
    $this->addElement('MultiCheckbox', 'campaign', array(
          'label' => 'Campaign',
          'multiOptions' => array(),
    ));
    
    $this->addElement('MultiCheckbox', 'ads', array(
          'label' => 'Ads',
          'multiOptions' => array(),
    ));
    
    $this->addElement('Select', 'type', array(
          'label' => 'Duration',
          'multiOptions' => array('month'=>'Month Wise','day'=>'Day Wise'),
          'onChange'=>'changeType(this.value)',
					'value'=>'day',
    ));
     $this->addElement('Select', 'month_start', array(
            'label' => '',
            'multiOptions' => array(
                    '01' => 'January',
                    '02' => 'February',
                    '03' => 'March',
                    '04' => 'April',
                    '05' => 'May',
                    '06' => 'June',
                    '07' => 'July',
                    '08' => 'August',
                    '09' => 'September',
                    '10' => 'October',
                    '11' => 'November',
                    '12' => 'December',
            ),
            'value' => '01',
            'decorators' => array(
                    'ViewHelper'),
    ));

    $this->addElement('Select', 'year_start', array(
            'multiOptions' => array(
            ),
            'decorators' => array(
                    'ViewHelper',
            ),
    ));

    $this->addDisplayGroup(array('month_start', 'year_start'), 'start_group');
    $button_group = $this->getDisplayGroup('start_group');
    $button_group->setDescription('From');
    $button_group->setDecorators(array(
            'FormElements',
            array('Description', array('placement' => 'PREPEND', 'tag' => 'div', 'class' => 'form-label')),
            array('HtmlTag', array('tag' => 'div', 'class' => 'form-wrapper', 'id' => 'start_group', 'style' => 'display:none;'))
    ));

    $this->addElement('Select', 'month_end', array(
      'multiOptions' => array(
              '01' => 'January',
              '02' => 'February',
              '03' => 'March',
              '04' => 'April',
              '05' => 'May',
              '06' => 'June',
              '07' => 'July',
              '08' => 'August',
              '09' => 'September',
              '10' => 'October',
              '11' => 'November',
              '12' => 'December',
      ),
      'value' => '12',
      'decorators' => array(
              'ViewHelper'),
    ));

    $this->addElement('Select', 'year_end', array(
      'multiOptions' => array(
      ),
      'decorators' => array(
              'ViewHelper',
      ),
    ));
    $this->addDisplayGroup(array('month_end', 'year_end'), 'end_group');
    $group = $this->getDisplayGroup('end_group');
    $group->setDescription('To');
    $group->setDecorators(array(
            'FormElements',
            array('Description', array('placement' => 'PREPEND', 'tag' => 'div', 'class' => 'form-label')),
            array('HtmlTag', array('tag' => 'div', 'class' => 'form-wrapper', 'id' => 'end_group', 'style' => 'display:none;'))
    ));
    
    $start = new Engine_Form_Element_CalendarDateTime('start');
    $start->setLabel("From");
    $start->setValue(date('Y-m-d H:i:s', strtotime("-1 Days")));
    $this->addElement($start);
    $end = new Engine_Form_Element_CalendarDateTime('end');
    $end->setLabel("To");
    $end->setValue(date('Y-m-d H:i:s'));
    $this->addElement($end);
    $this->addDisplayGroup(array('start', 'end'), 'cal');
    $group = $this->getDisplayGroup('cal');
    $group->setDecorators(array(
            'FormElements',
            'Fieldset',
            array('HtmlTag', array('tag' => 'div', 'id' => 'cal_grp', 'style' => "width:100%"))
    ));		
    $this->addElement('Select', 'format_name', array(
          'label' => 'Format',
          'multiOptions' => array('excel'=>'Excel','csv'=>'CSV'),
    ));
		// Buttons
    $this->addElement('Button', 'submit_form_sales_report', array(
        'label' => 'Generate Report',
        'type' => 'submit',
        'ignore' => true
    ));
 }
}