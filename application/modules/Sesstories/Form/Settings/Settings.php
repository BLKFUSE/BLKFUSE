<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Settings.php 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesstories_Form_Settings_Settings extends Engine_Form
{
  public    $saveSuccessful  = FALSE;
  protected $_roles           = array('owner', 'owner_member', 'owner_network', 'registered');
  protected $_item;

  public function setItem( $item)
  {
    $this->_item = $item;
  }

  public function getItem()
  {
    if( null === $this->_item ) {
      throw new User_Model_Exception('No item set in ' . get_class($this));
    }

    return $this->_item;
  }

  public function init()
  {
    $auth = Engine_Api::_()->authorization()->context;
    $user = $this->getItem();

    $this->setTitle('Privacy Settings')
      ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));

    $availableLabels = array(
      'owner'       => 'Only Me',
      'owner_member'      => 'Only My Friends',
      //'owner_network'     => 'Friends & Networks',
      'registered'  => 'All Registered Members',
    );

    // Init profile view
    //$view_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'auth_view');
    //$view_options = array_intersect_key($availableLabels, array_flip($view_options));
      $isPemissionSet =  Engine_Api::_()->getDbTable('settings', 'user')->getSetting($user,'sesstores_set');

    $this->addElement('Radio', 'story_privacy', array(
      'label' => 'Story View Privacy',
      'description' => 'Who can view your story?',
      'multiOptions' => $availableLabels,
      'value' =>'registered'
    ));
    if($isPemissionSet) {
        foreach ($this->_roles as $role) {
            if (1 === $auth->isAllowed($user, $role, 'story_view')) {
                $this->story_privacy->setValue($role);
            }
        }
    }
    $availableLabelsComment = array(
      'owner'       => 'Only Me',
      'owner_member'      => 'Only My Friends',
      //'owner_network'     => 'Friends & Networks',
      'registered'  => 'All Registered Members',
    );

    // Init profile comment
    //$commentOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'auth_comment');
    //$commentOptions = array_intersect_key($availableLabelsComment, array_flip($commentOptions));

        $this->addElement('Radio', 'story_comment', array(
            'label' => 'Story Comment Privacy',
            'description' => 'Who can comment and like on your story?',
            'multiOptions' => $availableLabelsComment,
            'value' => 'registered'
        ));
      if($isPemissionSet) {
          //$commentRoles = array_intersect($this->_roles, array_flip($commentOptions));
          foreach ($this->_roles as $role) {
              if (1 === $auth->isAllowed($user, $role, 'story_comment')) {
                  $this->story_comment->setValue($role);
              }
          }
      }
    // Init submit
    $this->addElement('Button', 'submit', array(
      'label' => 'Save Changes',
      'type' => 'submit',
      'ignore' => true
    ));

    return $this;
  }

  public function save()
  {
    $auth = Engine_Api::_()->authorization()->context;
    $user = $this->getItem();

    // Process member profile viewing privacy
    $privacy_value = $this->getValue('story_privacy');
      Engine_Api::_()->getDbTable('settings', 'user')->setSetting($user,'sesstores_set',1);
    Engine_Api::_()->sesstories()->isExist($user->getIdentity(), $privacy_value);
    if( empty($privacy_value) ) {
      $privacy_setting = end(Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'auth_view'));
      // If admin did not choose any options, make it everyone.
      // If not, use the one option they have set since the only option may not aways be set to 'everyone'.
      $privacy_value = empty($privacy_setting)
                     ? 'everyone'
                     : $privacy_setting;
    }

    $privacy_max_role = array_search($privacy_value, $this->_roles);
    foreach( $this->_roles as $i => $role )
      $auth->setAllowed($user, $role, 'story_view', ($i <= $privacy_max_role) );


    // Process member profile commenting privacy
    $comment_value = $this->getValue('story_comment');
    if( empty($comment_value) ) {
      $comment_setting = end(Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'auth_comment'));
      $comment_value = empty($comment_setting)
                     ? 'registered'
                     : $comment_setting;
    }

    $comment_max_role = array_search($comment_value, $this->_roles);
    foreach( $this->_roles as $i => $role )
      $auth->setAllowed($user, $role, 'story_comment', ($i <= $comment_max_role) );
      
    
  }
} // end public function save()
