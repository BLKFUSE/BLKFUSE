<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesapi
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Fields.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class User_Plugin_Signup_Fields extends Core_Plugin_FormSequence_Abstract
{
  protected $_name = 'fields';

  protected $_formClass = 'User_Form_Signup_Fields';

  protected $_script = array('signup/form/fields.tpl', 'user');

  protected $_adminFormClass = 'User_Form_Admin_Signup_Fields';

  protected $_adminScript = array('admin-signup/fields.tpl', 'user');

  public function getForm()
  {
    if( is_null($this->_form) )
    {
      $formArgs = array();

      // Preload profile type field stuff
      $profileTypeField = $this->getProfileTypeField();
      if( $profileTypeField ) {
        $accountSession = new Zend_Session_Namespace('User_Plugin_Signup_Account');
        $profileTypeValue = @$accountSession->data['profile_type'];
        if( $profileTypeValue ) {
          $formArgs = array(
            'topLevelId' => $profileTypeField->field_id,
            'topLevelValue' => $profileTypeValue,
          );
        }
        else{
          $topStructure = Engine_Api::_()->fields()->getFieldStructureTop('user');
          if( engine_count($topStructure) == 1 && $topStructure[0]->getChild()->type == 'profile_type' ) {
            $profileTypeField = $topStructure[0]->getChild();
            $options = $profileTypeField->getOptions();
            if( engine_count($options) == 1 ) {
              $formArgs = array(
                'topLevelId' => $profileTypeField->field_id,
                'topLevelValue' => $options[0]->option_id,
              );
            }
          }
        }
      }

      // Create form
      Engine_Loader::loadClass($this->_formClass);
      $class = $this->_formClass;
      $this->_form = new $class($formArgs);
      $data = $this->getSession()->data;


      if( !empty($_SESSION['facebook_signup']) ) {
        try {
          $facebookTable = Engine_Api::_()->getDbtable('facebook', 'user');
          $facebook = $facebookTable->getApi();
          $settings = Engine_Api::_()->getDbtable('settings', 'core');
          if( $facebook && $settings->core_facebook_enable ) {
            // Load Faceboolk data
            $apiInfo = $facebook->api('/me?fields=first_name,last_name,birthday,picture');
            $fb_data = array();
            $fb_keys = array('first_name', 'last_name','birthday', 'birthdate');
            foreach( $fb_keys as $key ) {
              if( isset($apiInfo[$key]) ){
                $fb_data[$key] = $apiInfo[$key];
              }
            }
            if( isset($apiInfo['birthday']) && !empty($apiInfo['birthday']) ){
              $fb_data['birthdate'] = date("Y-m-d",strtotime($fb_data['birthday']));
            }
            
            // populate fields, using Facebook data
            $struct = $this->_form->getFieldStructure();
            foreach( $struct as $fskey => $map ){
              $field = $map->getChild();
              if( $field->isHeading() ) continue;
              
              if( isset($field->type) && engine_in_array($field->type, $fb_keys) ) {
                $el_key = $map->getKey();
                $el_val = $fb_data[$field->type];
                $el_obj = $this->_form->getElement($el_key);
                if( $el_obj instanceof Zend_Form_Element &&
                    !$el_obj->getValue() ) {
                  $el_obj->setValue($el_val);
                }
              }
            }
            
          }
        } catch( Exception $e ) {
          // Silence?
        }
      }

      if (!empty($_SESSION['google_signup'])) {
        try {
            $googleTable = Engine_Api::_()->getDbtable('google', 'sessociallogin');
            if ($googleTable->isConnected()) {
                // Load Linkedin data
                $fb_data = array();
                $fb_keys = array('first_name', 'last_name', 'birthday', 'birthdate');
                $apiInfo = $_SESSION['signup_fields'];
                foreach ($fb_keys as $key) {
                    if (isset($apiInfo[$key])) {
                        $fb_data[$key] = $apiInfo[$key];
                    }
                }
                if (isset($apiInfo['birthday']) && !empty($apiInfo['birthday'])) {
                    $fb_data['birthdate'] = date("Y-m-d", strtotime($fb_data['birthday']));
                }

                // populate fields, using Facebook data
                $struct = $this->_form->getFieldStructure();
                foreach ($struct as $fskey => $map) {
                    $field = $map->getChild();
                    if ($field->isHeading())
                        continue;

                    if (isset($field->type) && engine_in_array($field->type, $fb_keys)) {
                        $el_key = $map->getKey();
                        $el_val = $fb_data[$field->type];
                        $el_obj = $this->_form->getElement($el_key);
                        if ($el_obj instanceof Zend_Form_Element &&
                                !$el_obj->getValue()) {
                            $el_obj->setValue($el_val);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // Silence?
        }
    }


      if( !empty($data) ) {
        foreach( $data as $key => $val ) {
          $el = $this->_form->getElement($key);
          if( $el instanceof Zend_Form_Element ) {
            $el->setValue($val);
          }
        }
      }
    }

    return $this->_form;
  }

  public function onView()
  {
  }
  
  public function onSubmit(Zend_Controller_Request_Abstract $request)
  {
    // Form was valid
    if( $this->getForm()->isValid($request->getPost()) )
    {
      $this->getSession()->data = $this->getForm()->getProcessedValues();
      $this->getSession()->active = false;
      $this->onSubmitIsValid();
      return true;
    }

    // Form was not valid
    else
    {
      $this->getSession()->active = true;
      $this->onSubmitNotIsValid();
      return false;
    }
  }
  
  public function onProcess()
  {
    // In this case, the step was placed before the account step.
    // Register a hook to this method for onUserCreateAfter
    if( !$this->_registry->user ) {
      // Register temporary hook
      Engine_Hooks_Dispatcher::getInstance()->addEvent('onUserCreateAfter', array(
        'callback' => array($this, 'onProcess'),
      ));
      return;
    }
    $user = $this->_registry->user;


    // Preload profile type field stuff
    $profileTypeField = $this->getProfileTypeField();
    if( $profileTypeField ) {
      $accountSession = new Zend_Session_Namespace('User_Plugin_Signup_Account');
      $profileTypeValue = @$accountSession->data['profile_type'];
      if( $profileTypeValue ) {
        $values = Engine_Api::_()->fields()->getFieldsValues($user);
        $valueRow = $values->createRow();
        $valueRow->field_id = $profileTypeField->field_id;
        $valueRow->item_id = $user->getIdentity();
        $valueRow->value = $profileTypeValue;
        $valueRow->save();
      }
      else{
        $topStructure = Engine_Api::_()->fields()->getFieldStructureTop('user');
        if( engine_count($topStructure) == 1 && $topStructure[0]->getChild()->type == 'profile_type' ) {
          $profileTypeField = $topStructure[0]->getChild();
          $options = $profileTypeField->getOptions();
          if( engine_count($options) == 1 ) {
            $values = Engine_Api::_()->fields()->getFieldsValues($user);
            $valueRow = $values->createRow();
            $valueRow->field_id = $profileTypeField->field_id;
            $valueRow->item_id = $user->getIdentity();
            $valueRow->value = $options[0]->option_id;
            $valueRow->save();
          }
        }
      }
    }

    // Save them values
    $form = $this->getForm()->setItem($user);
    $form->setProcessedValues($this->getSession()->data);
    $form->saveValues();

    $aliasValues = Engine_Api::_()->fields()->getFieldsValuesByAlias($user);
    $user->setDisplayName($aliasValues);
    $user->save();
    
    // Send Welcome E-mail
    if( isset($this->_registry->mailType) && $this->_registry->mailType ) {
      $mailType   = $this->_registry->mailType;
      $mailParams = $this->_registry->mailParams;
      Engine_Api::_()->getApi('mail', 'core')->sendSystem(
        $user,
        $mailType,
        $mailParams
      );
    }
    
    // Send Notify Admin E-mail
    if( isset($this->_registry->mailAdminType) && $this->_registry->mailAdminType ) {
      $mailAdminType   = $this->_registry->mailAdminType;
      $mailAdminParams = $this->_registry->mailAdminParams;
      Engine_Api::_()->getApi('mail', 'core')->sendSystem(
        $user,
        $mailAdminType,
        $mailAdminParams
      );
    }    
  }

  public function getProfileTypeField() {
    $topStructure = Engine_Api::_()->fields()->getFieldStructureTop('user');
    if( engine_count($topStructure) == 1 && $topStructure[0]->getChild()->type == 'profile_type' ) {
      return $topStructure[0]->getChild();
    }
    return null;
  }
}
