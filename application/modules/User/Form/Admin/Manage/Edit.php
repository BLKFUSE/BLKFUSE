<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Edit.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class User_Form_Admin_Manage_Edit extends Engine_Form
{
    protected $_userIdentity;

    public function setUserIdentity($userIdentity)
    {
        $this->_userIdentity = (int) $userIdentity;
        return $this;
    }

    public function init()
    {
        $this
            ->setAttrib('id', 'admin_members_edit')
            ->setTitle('Edit Member')
            ->setDescription('You can change the details of this member\'s account here.')
            ->setAction($_SERVER['REQUEST_URI']);

        // init email
        $this->addElement('Text', 'email', array(
            'label' => 'Email Address',
            'required' => true,
            'allowEmpty' => false,
            'validators' => array(
                array('NotEmpty', true),
                array('EmailAddress', true),
                array('Db_NoRecordExists', true, array(
                    Engine_Db_Table::getTablePrefix() . 'users', 'email', array(
                        'field' => 'user_id',
                        'value' => (int) $this->_userIdentity
                    )))
            ),
            'filters' => array(
                'StringTrim'
            )
        ));
        $this->email->getValidator('EmailAddress')->getHostnameValidator()->setValidateTld(false);
        // init username
        if( Engine_Api::_()->getApi('settings', 'core')->getSetting('user.signup.username', 1) > 0 ) {
            $this->addElement('Text', 'username', array(
              'label' => 'Username',
              'required' => true,
              'allowEmpty' => false,
              'validators' => array(
                array('NotEmpty', true),
                array('Alnum', true),
                array('StringLength', true, array(4, 64)),
                array('Regex', true, array('/^[a-z][a-z0-9]*$/i')),
                //array('Db_NoRecordExists', true, array(Engine_Db_Table::getTablePrefix() . 'users', 'username'))
              ),
            ));
            $this->username->getDecorator('Description')->setOptions(array('placement' => 'APPEND', 'escape' => false));
            $this->username->getValidator('NotEmpty')->setMessage('Please enter a valid profile address.', 'isEmpty');
            //$this->username->getValidator('Db_NoRecordExists')->setMessage('Someone has already picked this profile address, please use another one.', 'recordFound');
            $this->username->getValidator('Regex')->setMessage('Profile addresses must start with a letter.', 'regexNotMatch');
            $this->username->getValidator('Alnum')->setMessage('Profile addresses must be alphanumeric.', 'notAlnum');

            // Add banned username validator
            $bannedUsernameValidator = new Engine_Validate_Callback(array($this, 'checkBannedUsername'), $this->username);
            $bannedUsernameValidator->setMessage("This profile address is not available, please use another one.");
            $this->username->addValidator($bannedUsernameValidator);
        }

        // init password
        $this->addElement('Password', 'password', array(
            'label' => 'Password',
        ));
        
        //Work For Show and Hide Password
        $this->addElement('dummy', 'showhidepassword', array(
          'decorators' => array(array('ViewScript', array(
            'viewScript' => 'application/modules/User/views/scripts/_showhidepassword.tpl',
          ))),
        ));
        $this->addDisplayGroup(array('password', 'showhidepassword'), 'password_settings_group');
        
        $this->addElement('Password', 'password_conf', array(
            'label' => 'Password Again',
        ));
        
        //Work For Show and Hide Password
        $this->addElement('dummy', 'showhideconfirmpassword', array(
          'decorators' => array(array('ViewScript', array(
            'viewScript' => 'application/modules/User/views/scripts/_showhideconfirmpassword.tpl',
          ))),
        ));
        $this->addDisplayGroup(array('password_conf', 'showhideconfirmpassword'), 'password_confirm_settings_group');

        // Init level
        $levelMultiOptions = array(); //0 => ' ');
        $levels = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll();
        foreach( $levels as $row ) {
            $levelMultiOptions[$row->level_id] = $row->getTitle();
        }
        $this->addElement('Select', 'level_id', array(
            'label' => 'Member Level',
            'multiOptions' => $levelMultiOptions
        ));

        // Init level
        $networkMultiOptions = array(); //0 => ' ');
        $networks = Engine_Api::_()->getDbtable('networks', 'network')->fetchAll();
        foreach( $networks as $row ) {
            $networkMultiOptions[$row->network_id] = $row->getTitle();
        }
        $this->addElement('Multiselect', 'network_id', array(
            'label' => 'Networks',
            'multiOptions' => $networkMultiOptions
        ));

        // Init approved
        $this->addElement('Checkbox', 'approved', array(
            'label' => 'Approved?',
        ));

        // Init verified
        $this->addElement('Checkbox', 'verified', array(
            'label' => 'Is Email Verified?'
        ));

        // Init enabled
        $this->addElement('Checkbox', 'enabled', array(
            'label' => 'Enabled?',
        ));
        
        // Init verified
        $this->addElement('Checkbox', 'is_verified', array(
            'label' => 'Verified?'
        ));
        
        $this->addElement('Checkbox', 'donotsellinfo', array(
            'label' => 'Do Not Sell My Personal Information',
        ));
        // Init disable email
        $this->addElement('Checkbox', 'disable_email', array(
            'label' => 'Disable all site emails?',
        ));

        // Element: token
        $this->addElement('Hash', 'token');

        // Buttons
        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array('ViewHelper')
        ));

        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'prependText' => ' or ',
            'onclick' => 'parent.Smoothbox.close();',
            'decorators' => array(
                'ViewHelper'
            )
        ));

        $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
        $button_group = $this->getDisplayGroup('buttons');
        $button_group->addDecorator('DivDivDivWrapper');
    }
    
    public function checkBannedUsername($value, $usernameElement)
    {
      $bannedUsernamesTable = Engine_Api::_()->getDbtable('BannedUsernames', 'core');
      return !$bannedUsernamesTable->isUsernameBanned($value);
    }
}
