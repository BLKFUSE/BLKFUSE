<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Create.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Form_Admin_Package_Create extends Engine_Form
{
  public function init()
  {
    $this
      ->setTitle('Create Ads Package Plan')
      ->setDescription('Please note that payment parameters (Price, ' .
          'Recurrence, Duration, Trial Duration) cannot be edited after ' .
          'creation. If you wish to change these, you will have to create a ' .
          'new plan and disable the current one.')
      ;

    // Element: title
    $this->addElement('Text', 'title', array(
      'label' => 'Title',
      'required' => true,
      'allowEmpty' => false,
      'filters' => array(
        'StringTrim',
      ),
    ));

    // Element: description
    $this->addElement('Textarea', 'description', array(
      'label' => 'Description',
      'validators' => array(
        array('StringLength', true, array(0, 250)),
      )
    ));
    $multiOptions = array(''=>'');
    // Element: level_id
    foreach( Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level ) {
      if( $level->type == 'public') {
        continue;
      }
      $multiOptions[$level->level_id] = $level->getTitle();
    }


    $this->addElement('multiselect', 'level_id', array(
      'label' => 'Member Level',
      //'required' => true,
      //'allowEmpty' => false,
      'description' => 'Which member level can select this plan',
      'multiOptions' => ($multiOptions),
    ));

    $modules = array();
    $select = Engine_Api::_()->getDbtable('modules', 'sescommunityads')->select()->where('enabled =?',1);
    foreach( Engine_Api::_()->getDbtable('modules', 'sescommunityads')->fetchAll($select) as $module ) {
      $modules[$module->content_type] = $module->content_type.' ('.ucfirst($module->module_name).')';
    }
     // Element: enabled
    $this->addElement('MultiSelect', 'modules', array(
      'label' => 'Modules',
      'description' => 'Select the module you want to enable for the Ads created under this package.',
      'multiOptions' => array_merge(array(''=>''),$modules),
			'class'=>'moduled-checkbox',
      'value' => '',
    ));


    $this->addElement('Select', 'package_type', array(
      'label' => 'Package Type',
      //'required' => true,
      //'allowEmpty' => false,
      'multiOptions' => array('recurring'=>'Recurring Package','nonRecurring'=>'Non recurring Package'),
      'value'=>'nonRecurring'
    ));
    $text = "";
      if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')) {
          $text = "In boost post ads click limit not work.";

      }
    $this->addElement('Select', 'click_type', array(
      'label' => 'Ad Type',
      //'required' => true,
      //'allowEmpty' => false,
      'description' => 'Select the Ad Type',
      'data-title-perclick'=>'Clicks Limit',
      'data-title-perview'=>'Views Limit',
      'data-title-perday'=>'Period (in days)',
      'data-description-perclick'=>'(0 for unlimited clicks) Note: A change in this setting later on will only apply on new ads that are created in this package.'.$text,
      'data-description-perview'=>'(0 for unlimited views) Note: A change in this setting later on will only apply on new ads that are created in this package.',
      'data-description-perday'=>'(0 for unlimited days) Note: A change in this setting later on will only apply on new ads that are created in this package.',
      'multiOptions' => array('perclick'=>'Pay for clicks','perview'=>'Pay for Views','perday'=>'Pay for Days'),
      'value'=>'perclick'
    ));

    $this->addElement('Text', 'click_limit', array(
      'label' => 'Periods (in Days)',
      'description' => 'write 0 for unlimited days',
      'value'=>'0'
    ));

    // Element: price
    $currency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency');
    $this->addElement('Text', 'price', array(
      'label' => 'Price',
      'description' => 'The amount to charge the member. This will be charged ' .
          'once for one-time plans, and each billing cycle for recurring ' .
          'plans. Setting this to zero will make this a free plan.',
      'required' => true,
      'allowEmpty' => false,
      'validators' => array(
        new Engine_Validate_AtLeast(0),
      ),
    ));
    if(!empty($_POST) && $_POST['package_type'] == "nonRecurring"){
      $allowEmpty = true;
      $required = false;
    }else{
      $allowEmpty = false;
      $required = true;
    }
    // Element: recurrence
    $this->addElement('Duration', 'recurrence', array(
      'label' => 'Billing Cycle',
      'description' => 'How often should members in this plan be billed?',
      'required' => $required,
      'allowEmpty' => $allowEmpty,
      'value' => array(1, 'month'),
    ));
    //unset($this->getElement('recurrence')->options['day']);
    //$this->getElement('recurrence')->options['forever'] = 'One-time';

    // Element: duration
    $this->addElement('Duration', 'duration', array(
      'label' => 'Billing Duration',
      'description' => 'When should this plan expire? For one-time ' .
        'plans, the plan will expire after the period of time set here. For ' .
        'recurring plans, the user will be billed at the above billing cycle ' .
        'for the period of time specified here.',
      'required' => $required,
      'allowEmpty' => $allowEmpty,
      'value' => array('0', 'forever'),
    ));
    //unset($this->getElement('duration')->options['day']);


    // renew
    $this->addElement('Select', 'is_renew_link', array(
        'description' => 'Renew Link',
        'label' => 'Want to show reniew link',
        'value' => 0,
				'multiOptions'=>array('1'=>'Yes, show reniew link','0'=>'No, don\'t show renew link'),
    ));
    $this->addElement('Text', 'renew_link_days', array(
        'label' => 'Days before show renew link',
        'description' => 'Show renewal link before how many days before expiry.',
        'required' => true,
        'allowEmpty' => false,
        'validators' => array(
            array('Int', true),
            new Engine_Validate_AtLeast(0),
        ),
        'value' => '0',
    ));
		$this->addElement('Checkbox', 'auto_approve', array(
					'description'=>'Approve Ads',
	        'label' => 'Do you want to make auto approve ads created under this package.',
	        'value' => 0
	  ));

    $this->addElement('Checkbox', 'featured', array(
					'description'=>'Featured',
	        'label' => 'Do you want to make Ads created under this package Featured',
	        'value' => 0
	  ));

    $this->addElement('Text', 'featured_days', array(
					'description'=>'(0 for unlimited days) Note: For how many days you want to make the ads featured in this package',
	        'label' => '',
	        'value' => 0
	  ));


    $this->addElement('Checkbox', 'sponsored', array(
					'description'=>'Sponsored',
	        'label' => 'Do you want to make Ads created under this package Sponsored',
	        'value' => 0
	  ));

    $this->addElement('Text', 'sponsored_days', array(
					'description'=>'(0 for unlimited days) Note: For how many days you want to make the ads sponsored in this package',
	        'label' => '',
	        'value' => 0
	  ));
      if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')) {
          // Element: enabled
          $this->addElement('Checkbox', 'boost_post', array(
              'description' => 'Boost Post',
              'label' => 'Do you want to enable boost post (Depend on Advanced Activity Plugin).',
              'multiOptions' => array(),
              'class' => 'moduled-checkbox',
              'value' => '1',
          ));
      }
    // Element: enabled
    $this->addElement('Checkbox', 'promote_page', array(
      'description' => 'Promote Your Page',
      'label' => 'Do you want to enable promote your page (Depends on Page Directories Plugin).',
      'multiOptions' => array(),
			'class'=>'moduled-checkbox',
      'value' => '1',
    ));

     // Element: enabled
    $this->addElement('Checkbox', 'promote_content', array(
      'description' => 'Promote Content',
      'label' => 'Do you want to enable promote your content.',
      'multiOptions' => array(),
			'class'=>'moduled-checkbox',
      'value' => '1',
    ));
     // Element: enabled
    $this->addElement('Checkbox', 'website_visitor', array(
      'description' => 'Get More Website Visitor',
      'label' => 'Do you want to enable get more website visitor.',
      'multiOptions' => array(),
			'class'=>'moduled-checkbox',
      'value' => '1',
    ));
     // Element: enabled
    $this->addElement('Checkbox', 'carousel', array(
      'description' => 'Carousel',
      'label' => 'Do you want to enable carousel view.',
      'multiOptions' => array(),
			'class'=>'moduled-checkbox',
      'value' => '1',
    ));
    $this->addElement('Checkbox', 'video', array(
					'description'=>'Single Video',
	        'label' => 'Do you want to enable Single Video',
	        'value' => 1,
	  ));

    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescomadbanr')) {
        $this->addElement('Checkbox', 'banner', array(
            'description'=>'Banner Image',
            'label' => 'Do you want to enable Banner Image View',
            'value' => 0,
        ));
    }

    $this->addElement('Checkbox', 'networking', array(
					'description'=>'Networking',
	        'label' => 'Do you want to enable Network targeting',
	        'value' => 1,
	  ));

    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesinterest')) {
        $this->addElement('Checkbox', 'interests', array(
            'description'=>'Interests',
            'label' => 'Do you want to enable Interests targeting',
            'value' => 0,
        ));
    }

     $this->addElement('Checkbox', 'targetting', array(
      'description' => 'Targeting',
      'label' => 'Do you want to enable targeting.',
      'multiOptions' => array(),
      'value' => '1',
    ));


     $this->addElement('Text', 'item_count', array(
					'description'=>'How many ads user can create in one campaign in this plan(0 for unlimited)',
	        'label' => 'Ads',
	        'value' => 0,
          'validators' => array(
            array('Int', true),
        )
	  ));


    // Element: enabled
    $this->addElement('Radio', 'enabled', array(
      'label' => 'Enabled?',
      'description' => 'Can members choose this plan? Please note that disabling this plan will <a href="https://en.wikipedia.org/wiki/Grandfather_clause" target="_blank">grandfather</a> in existing plan members until they pick a new plan.',
      'multiOptions' => array(
        '1' => 'Yes, members may select this plan.',
        '0' => 'No, members may not select this plan.',
      ),
      'value' => 1,
    ));
    $this->getElement('enabled')->getDecorator('description')->setOption('escape', false);

    // Element: default
    $this->addElement('Radio', 'default', array(
      'label' => 'Default Plan?',
      'description' => 'Do you want to make this plan default.',
      'multiOptions' => array(
        '1' => 'Yes, this is the default plan.',
        '0' => 'No, this is not the default plan.',
      ),
      'value' => 0,
    ));

    //Rented Package Work
    if(1) {
        $this->addElement('Radio', 'rentpackage', array(
        'label' => 'Rented Packages',
        'description' => 'Do you want to make this package for Rented Ads?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => 0,
        ));
    }

    // Element: execute
    $this->addElement('Button', 'execute', array(
      'label' => 'Create Plan',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array('ViewHelper'),
    ));

    // Element: cancel
    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'prependText' => ' or ',
      'ignore' => true,
      'link' => true,
      'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index', 'package_id' => null)),
      'decorators' => array('ViewHelper'),
    ));

    // DisplayGroup: buttons
    $this->addDisplayGroup(array('execute', 'cancel'), 'buttons', array(
      'decorators' => array(
        'FormElements',
        'DivDivDivWrapper',
      )
    ));
  }
}
