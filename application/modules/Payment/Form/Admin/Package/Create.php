<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Payment
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Create.php 10123 2013-12-11 17:29:35Z andres $
 * @author     John Boehr <j@webligo.com>
 */

/**
 * @category   Application_Core
 * @package    Payment
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Payment_Form_Admin_Package_Create extends Engine_Form
{
  public function init()
  {
    $this
      ->setTitle('Create Subscription Plan')
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
      ),
      'maxlength' => 250,
    ));
    
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $fileLink = $view->baseUrl() . '/admin/files/';

    $fileOptions = array('' => '');
    $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png', 'webp')));
    foreach( $files as $file ) {
      $fileOptions[$file->storage_path] = $file->name;
    }
    if (engine_count($fileOptions) > 1) {
      $description = $this->getTranslator()->translate('Choose an image to show with this plan. This image will show with this plan at the user panel of your site. [Note: You can add a new image from the "<a href="%1$s" target="_blank">File & Media Manager</a>" section. If you leave the field blank then nothing will show.]');
      $description = vsprintf($description, array($fileLink));

      $this->addElement('Select', 'photo_id', array(
        'label' => "Photo",
        'description' => $description,
        'multiOptions' => $fileOptions,
      ));
      $this->photo_id->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    } else {
      $description = $this->getTranslator()->translate('There are currently no images in the <a href="%1$s" target="_blank"> File & Media Manager </a> section of your site. Please begin by uploading an image to get started.');
      $description = vsprintf($description, array($fileLink));
      $description = "<div class='tip'><span>" . $description . "</span></div>";
      $this->addElement('Dummy', 'photo_id', array(
        'label' => "Photo",
        'description' => $description,
      ));
      $this->photo_id->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    }

    // Element: level_id
    $multiOptions = array('' => '');
    foreach( Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level ) {
      if( $level->type == 'public' || $level->type == 'admin' || $level->type == 'moderator' ) {
        continue;
      }
      $multiOptions[$level->getIdentity()] = $level->getTitle();
    }
    $this->addElement('Select', 'level_id', array(
      'label' => 'Member Level',
      //'required' => true,
      //'allowEmpty' => false,
      'description' => 'The member will be placed into this level upon ' .
          'subscribing to this plan. If left empty, the default level at the ' .
          'time a subscription is chosen will be used.',
      'multiOptions' => $multiOptions,
    ));

    // Element: downgrade_level_id
    $this->addElement('Select', 'downgrade_level_id', array(
      'label' => 'Downgrade Member Level',
      'description' =>'Choose from below the Member Level which will be changed for members when their current subscribed plan expires. You should choose a lower Member Level from the Member Level which is associated with this current plan.',
      'multiOptions' => $multiOptions,
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

    // Element: recurrence
    $this->addElement('Duration', 'recurrence', array(
      'label' => 'Billing Cycle',
      'description' => 'How often should members in this plan be billed?',
      'required' => true,
      'allowEmpty' => false,
      //'validators' => array(
        //array('Int', true),
        //array('GreaterThan', true, array(0)),
      //),
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
      'required' => true,
      'allowEmpty' => false,
      //'validators' => array(
      //  array('Int', true),
      //  array('GreaterThan', true, array(0)),
      //),
      'value' => array('0', 'forever'),
    ));
    //unset($this->getElement('duration')->options['day']);

    $this->addElement('Text', 'extra_day', array(
      'label' => 'Extra Days Limit for Member Level Upgrade',
      'description' => 'Enter number of additional days after which Member Level of members will be a downgrade. This time will be calculated after the normal expiration of the plan. Enter ‘0’ if you do not want to give extra days.',
      'validators' => array(
        array('Int', true),
        new Engine_Validate_AtLeast(0),
      ),
      'value' => 0,
    ));

    // Element: trial_duration
    /*
    $this->addElement('Duration', 'trial_duration', array(
      'label' => 'Trial Duration',
      'description' => 'NOT YET IMPLEMENTED. Please note that the way ' .
          'payment gateways implement this varies. PayPal implements this ' .
          'exactly, however 2Checkout uses a negative startup fee. For ' .
          '2Checkout, you must use a multiple of your billing ' .
          'cycle.',
      'validators' => array(
        array('Int', true),
        new Engine_Validate_AtLeast(0),
      ),
      'value' => array('0', 'forever'),
    ));
     *
     */
     
    // Element: enabled
    $this->addElement('Select', 'send_reminder', array(
      'label' => 'Send Reminders for Emails & Notifications',
      'description' => 'Do you want to send Reminders for Emails & Notifications?',
      'multiOptions' => array(
        '1' => 'Yes',
        '0' => 'No',
      ),
      'onchange' => 'sendReminder(this.value);',
      'value' => 1,
    ));
    $this->getElement('send_reminder')->getDecorator('description')->setOption('escape', false);

    // Element: reminder_email
    $this->addElement('Text', 'reminder_email', array(
      'label' => 'Reminders for Emails & Notifications',
      'description' => 'Choose the duration (in Days) from Plan Expiry before which Email and Notifications should be sent to members of your website. This duration will be calculated from the expiry date of Plan.',
      'validators' => array(
        array('Int', true),
        array('GreaterThan', true, array(0)),
      ),
      'required' => true,
      'allowEmpty' => false,
      'value' => 1,
    ));

    // Element: enabled
    $this->addElement('Select', 'enabled', array(
      'label' => 'Enabled?',
      'description' => 'Can members choose this plan? Please note that disabling this plan will <a href="https://en.wikipedia.org/wiki/Grandfather_clause" target="_blank">grandfather</a> in existing plan members until they pick a new plan.',
      'multiOptions' => array(
        '1' => 'Yes, members may select this plan.',
        '0' => 'No, members may not select this plan.',
      ),
      'value' => 1,
    ));
    $this->getElement('enabled')->getDecorator('description')->setOption('escape', false);
    // Element: signup
    $this->addElement('Select', 'signup', array(
      'label' => 'Show on signup?',
      'description' => 'Can members choose this plan on signup?',
      'multiOptions' => array(
        '1' => 'Yes, show this plan on signup.',
        '0' => 'No, do not show this plan on signup.',
      ),
      'value' => 1,
    ));

    // Element: after_signup
    $this->addElement('Select', 'after_signup', array(
      'label' => 'Show after signup?',
      'description' => 'Can members choose this plan after signup?',
      'multiOptions' => array(
        '1' => 'Yes, show this plan after signup.',
        '0' => 'No.',
      ),
      'value' => 1,
    ));

    // Element: default
    $this->addElement('Select', 'default', array(
      'label' => 'Default Plan?',
      'description' => 'If choosing a plan on signup is disabled, this plan ' .
          'will be assigned to new members. Selecting this option will ' .
          'switch this setting from the current default plan. Only a ' .
          'free plan may be the default plan.',
      'multiOptions' => array(
        '1' => 'Yes, this plan will be selected by default after signup.',
        '0' => 'No, this is not the default plan.',
      ),
      'value' => 0,
    ));

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
