<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Privacy.php 9747 2012-07-26 02:08:08Z john $
 * @author     Steve
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class User_Form_Settings_Privacy extends Engine_Form
{
  public    $saveSuccessful  = FALSE;
  protected $_roles           = array('owner', 'member', 'network', 'owner_network','registered', 'everyone');
  protected $_item;

  public function setItem(User_Model_User $item)
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
    $viewer = Engine_Api::_()->user()->getViewer();


    $this->setTitle('Privacy Settings')
      ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
      ;

    // Init blocklist
    $this->addElement('Hidden', 'blockList', array(
      'label' => 'Blocked Members',
      'description' => 'Adding a person to your block list makes your profile (and all of your other content) unviewable to them and vice-versa. Blocked users will not be able to message you or view things you post. Any connections you have to the blocked person will be canceled. To add someone to your block list, visit that person\'s profile page.',
      'order' => -1
    ));
    Engine_Form::addDefaultDecorators($this->blockList);
    
    // Init search
    $this->addElement('Checkbox', 'search', array(
      'label' => 'Do not display me in searches, browsing members, or the "Online Members" list.',
      'checkedValue' => 0,
      'uncheckedValue' => 1,
    ));

    // Init showprofileviews
    /*
    $this->addElement('Checkbox', 'show_profileviewers', array(
      'label' => 'Yes, display users who viewed my profile.',
      //'description' => 'Show Profile Views',
    ));
    */
    
    $availableLabels = array(
      'owner'       => 'Only Me',
      'member'      => 'Only My Friends',
      'network'     => 'Friends & Networks',
      'registered'  => 'All Registered Members',
      'everyone'    => 'Everyone',
    );
    
    // Init profile view
    $view_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'auth_view');
    $view_options = array_intersect_key($availableLabels, array_flip($view_options));

    $this->addElement('Select', 'privacy', array(
      'label' => 'Profile Privacy',
      'description' => 'Who can view your profile?',
      'multiOptions' => $view_options,
    ));

    foreach( $this->_roles as $role ) {
      if( 1 === $auth->isAllowed($user, $role, 'view') ) {
        $this->privacy->setValue($role);
      }
    }

    if( Engine_Api::_()->authorization()->getPermission($viewer, 'user', 'lastLoginShow') ) {
      // Init Login Date
      $lastLoginDate_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'lastLoginDate');
      $lastLoginDate_options = array_intersect_key($availableLabels, array_flip($lastLoginDate_options));

      $this->addElement('Select', 'lastLoginDate', array(
        'label' => 'Last Login Date',
        'description' => 'Who can view your Last Login Date?',
        'multiOptions' => $lastLoginDate_options,
      ));

      foreach( $this->_roles as $role ) {
        if( 1 === $auth->isAllowed($user, $role, 'lastLoginDate') ) {
          $this->lastLoginDate->setValue($role);
        }
      }
    }

    if (Engine_Api::_()->authorization()->getPermission($viewer, 'user', 'lastUpdateShow')) {
      // Init Last Update
      $lastUpdateDate_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'lastUpdateDate');
      $lastUpdateDate_options = array_intersect_key($availableLabels, array_flip($lastUpdateDate_options));

      $this->addElement('Select', 'lastUpdateDate', array(
        'label' => 'Last Update Date',
        'description' => 'Who can view your Last Update Date?',
        'multiOptions' => $lastUpdateDate_options,
      ));

      foreach ($this->_roles as $role) {
        if (1 === $auth->isAllowed($user, $role, 'lastUpdateDate')) {
          $this->lastUpdateDate->setValue($role);
        }
      }
    }

    if (Engine_Api::_()->authorization()->getPermission($viewer, 'user', 'inviteeShow')) {
    // Init Invitee
      $inviteeName_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'inviteeName');
      $inviteeName_options = array_intersect_key($availableLabels, array_flip($inviteeName_options));

      $this->addElement('Select', 'inviteeName', array(
        'label' => 'Name of Invitee',
        'description' => 'Who can view your Invitee Name?',
        'multiOptions' => $inviteeName_options,
      ));

      foreach( $this->_roles as $role ) {
        if( 1 === $auth->isAllowed($user, $role, 'inviteeName') ) {
          $this->inviteeName->setValue($role);
        }
      }
    }

    if (Engine_Api::_()->authorization()->getPermission($viewer, 'user', 'profileTypeShow')) {
    // Init profile view
      $profileType_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'profileType');
      $profileType_options = array_intersect_key($availableLabels, array_flip($profileType_options));

      $this->addElement('Select', 'profileType', array(
        'label' => 'Profile Type',
        'description' => 'Who can view your Profile Type?',
        'multiOptions' => $profileType_options,
      ));

      foreach( $this->_roles as $role ) {
        if( 1 === $auth->isAllowed($user, $role, 'profileType') ) {
          $this->profileType->setValue($role);
        }
      }
    }

    if (Engine_Api::_()->authorization()->getPermission($viewer, 'user', 'memberLevelShow')) {
    // Init profile view
      $memberLevel_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'memberLevel');
      $memberLevel_options = array_intersect_key($availableLabels, array_flip($memberLevel_options));

      $this->addElement('Select', 'memberLevel', array(
        'label' => 'Member Level',
        'description' => 'Who can view your Member Level?',
        'multiOptions' => $memberLevel_options,
      ));

      foreach( $this->_roles as $role ) {
        if( 1 === $auth->isAllowed($user, $role, 'memberLevel') ) {
          $this->memberLevel->setValue($role);
        }
      }
    }

    if (Engine_Api::_()->authorization()->getPermission($viewer, 'user', 'profileViewsShow')) {
    // Init Member Level
      $profileViews_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'profileViews');
      $profileViews_options = array_intersect_key($availableLabels, array_flip($profileViews_options));

      $this->addElement('Select', 'profileViews', array(
        'label' => 'Profile Views',
        'description' => 'Who can view your Profile Views?',
        'multiOptions' => $profileViews_options,
      ));

      foreach( $this->_roles as $role ) {
        if( 1 === $auth->isAllowed($user, $role, 'profileViews') ) {
          $this->profileViews->setValue($role);
        }
      }
    }

    if (Engine_Api::_()->authorization()->getPermission($viewer, 'user', 'joinedDateShow')) {
    // Init Joined Date Level
      $joinedDate_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'joinedDate');
      $joinedDate_options = array_intersect_key($availableLabels, array_flip($joinedDate_options));

      $this->addElement('Select', 'joinedDate', array(
        'label' => 'Joined Date',
        'description' => 'Who can view your Joined Date?',
        'multiOptions' => $joinedDate_options,
      ));

      foreach( $this->_roles as $role ) {
        if( 1 === $auth->isAllowed($user, $role, 'joinedDate') ) {
          $this->joinedDate->setValue($role);
        }
      }
    }

    if (Engine_Api::_()->authorization()->getPermission($viewer, 'user', 'friendsCountShow')) {
      // Init Friends Count Level
      $friendsCount_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'friendsCount');
      $friendsCount_options = array_intersect_key($availableLabels, array_flip($friendsCount_options));

      $this->addElement('Select', 'friendsCount', array(
        'label' => 'Friends Count',
        'description' => 'Who can view your Friends Count?',
        'multiOptions' => $friendsCount_options,
      ));

      foreach ($this->_roles as $role) {
        if (1 === $auth->isAllowed($user, $role, 'friendsCount')) {
          $this->friendsCount->setValue($role);
        }
      }
    }

    $availableLabelsComment = array(
      'owner'       => 'Only Me',
      'member'      => 'Only My Friends',
      'network'     => 'Friends & Networks',
      'registered'  => 'All Registered Members',
    );

    // Init profile comment
    $commentOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'auth_comment');
    $commentOptions = array_intersect_key($availableLabelsComment, array_flip($commentOptions));

    $this->addElement('Select', 'comment', array(
      'label' => 'Profile Posting Privacy',
      'description' => 'Who can post on your profile?',
      'multiOptions' => $commentOptions,
    ));

    $commentRoles = array_intersect($this->_roles, array_flip($commentOptions));
    foreach( $commentRoles as $role ) {
      if( 1 === $auth->isAllowed($user, $role, 'comment') ) {
        $this->comment->setValue($role);
      }
    }

    $availableOptions = array(
        'owner'       => 'Only Me',
        'member'      => 'My Friends',
        'network'    => 'Friends & Networks',
        'owner_network' => 'Network',
        'registered'  => 'All Registered Members',
    );
    $userMention_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'mention');
    $userMention_options = array_intersect_key($availableOptions, array_flip($userMention_options));
   
    $this->addElement('Select', 'mention', array(
      'label' => 'User @ Mentions',
      'description' => 'Who can @ mention you?',
      'multiOptions' => $userMention_options,
    ));
    foreach ($this->_roles as $role) {
      if (1 === $auth->isAllowed($user, $role, 'mention')) {
        $this->mention->setValue($role);
      }
    }
    
    if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('poke')) {
      //Poke Privacy
      $userPoke_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'pokeAction');
      $userPoke_options = array_intersect_key($availableOptions, array_flip($userPoke_options));

      $this->addElement('Select', 'pokeAction', array(
        'label' => 'Poke Privacy',
        'description' => 'Who can Poke or Send Action to you?',
        'multiOptions' => $userPoke_options,
      ));
      foreach ($this->_roles as $role) {
        if (1 === $auth->isAllowed($user, $role, 'pokeAction')) {
          $this->pokeAction->setValue($role);
        }
      }
    }
    
    $birthdayOptions = array(
      'monthday' => 'Month/Day',
      'monthdayyear' => 'Month/Day/Year',
    );
    $birthday_options = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'birthday_options');

    if (Engine_Api::_()->authorization()->getPermission($viewer, 'user', 'allow_birthday') && engine_count($birthday_options) > 1) {
      $birthday_options = array_intersect_key($birthdayOptions, array_flip($birthday_options));
      $this->addElement('Select', 'birthday_format', array(
        'label' => 'Birthday Privacy Setting',
        'description' => 'How to show your Birthday?',
        'multiOptions' => $birthday_options,
      ));
    }

    // Init publishtypes
    if( Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.publish', true) ) {
      $this->addElement('MultiCheckbox', 'publishTypes', array(
        'label' => 'Recent Activity Privacy',
        'description' => 'Which of the following things do you want to have published about you in the recent activity feed? Note that changing this setting will only affect future news feed items.',
      ));
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
    $privacy_value = $this->getValue('privacy');

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
      $auth->setAllowed($user, $role, 'view', ($i <= $privacy_max_role) );


    /* lastLoginDate */
    // Process member profile profile type privacy
    $lastLoginDate_value = $this->getValue('lastLoginDate');
    if( empty($lastLoginDate_value) ) {
      $lastLoginDate_setting = end(Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'lastLoginDate'));
      $lastLoginDate_value = empty($lastLoginDate_setting)
                     ? 'everyone'
                     : $lastLoginDate_setting;
    }
    $lastLoginDate_max_role = array_search($lastLoginDate_value, $this->_roles);
    foreach( $this->_roles as $i => $role )
      $auth->setAllowed($user, $role, 'lastLoginDate', ($i <= $lastLoginDate_max_role) );

    /* lastUpdateDate */
    // Process member profile profile type privacy
    $lastUpdateDate_value = $this->getValue('lastUpdateDate');
    if( empty($lastUpdateDate_value) ) {
      $lastUpdateDate_setting = end(Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'lastUpdateDate'));
      $lastUpdateDate_value = empty($lastUpdateDate_setting)
                     ? 'everyone'
                     : $lastUpdateDate_setting;
    }
    $lastUpdateDate_max_role = array_search($lastUpdateDate_value, $this->_roles);
    foreach( $this->_roles as $i => $role )
      $auth->setAllowed($user, $role, 'lastUpdateDate', ($i <= $lastUpdateDate_max_role) );

    /* inviteeName */
    // Process member profile profile type privacy
    $inviteeName_value = $this->getValue('inviteeName');
    if( empty($inviteeName_value) ) {
      $inviteeName_setting = end(Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'inviteeName'));
      $inviteeName_value = empty($inviteeName_setting)
                     ? 'everyone'
                     : $inviteeName_setting;
    }
    $inviteeName_max_role = array_search($inviteeName_value, $this->_roles);
    foreach( $this->_roles as $i => $role )
      $auth->setAllowed($user, $role, 'inviteeName', ($i <= $inviteeName_max_role) );


    // Process member profile profile type privacy
    $profileType_value = $this->getValue('profileType');
    if( empty($profileType_value) ) {
      $profileType_setting = end(Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'profileType'));
      $profileType_value = empty($profileType_setting)
                     ? 'everyone'
                     : $profileType_setting;
    }
    $profileType_max_role = array_search($profileType_value, $this->_roles);
    foreach( $this->_roles as $i => $role )
      $auth->setAllowed($user, $role, 'profileType', ($i <= $profileType_max_role) );

    /* memberLevel */
    // Process member profile profile type privacy
    $memberLevel_value = $this->getValue('memberLevel');
    if( empty($memberLevel_value) ) {
      $memberLevel_setting = end(Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'memberLevel'));
      $memberLevel_value = empty($memberLevel_setting)
                     ? 'everyone'
                     : $memberLevel_setting;
    }
    $memberLevel_max_role = array_search($memberLevel_value, $this->_roles);
    foreach( $this->_roles as $i => $role )
      $auth->setAllowed($user, $role, 'memberLevel', ($i <= $memberLevel_max_role) );

    /* profileViews */
    $profileViews_value = $this->getValue('profileViews');
    if( empty($profileViews_value) ) {
      $profileViews_setting = end(Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'profileViews'));
      $profileViews_value = empty($profileViews_setting)
                     ? 'everyone'
                     : $profileViews_setting;
    }
    $profileViews_max_role = array_search($profileViews_value, $this->_roles);
    foreach( $this->_roles as $i => $role )
      $auth->setAllowed($user, $role, 'profileViews', ($i <= $profileViews_max_role) );

    /* profileViews */
    $profileViews_value = $this->getValue('profileViews');
    if( empty($profileViews_value) ) {
      $profileViews_setting = end(Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'profileViews'));
      $profileViews_value = empty($profileViews_setting)
                     ? 'everyone'
                     : $profileViews_setting;
    }
    $profileViews_max_role = array_search($profileViews_value, $this->_roles);
    foreach( $this->_roles as $i => $role )
      $auth->setAllowed($user, $role, 'profileViews', ($i <= $profileViews_max_role) );

    /* joinedDate */
    $joinedDate_value = $this->getValue('joinedDate');
    if( empty($joinedDate_value) ) {
      $joinedDate_setting = end(Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'joinedDate'));
      $joinedDate_value = empty($joinedDate_setting)
                     ? 'everyone'
                     : $joinedDate_setting;
    }
    $joinedDate_max_role = array_search($joinedDate_value, $this->_roles);
    foreach( $this->_roles as $i => $role )
      $auth->setAllowed($user, $role, 'joinedDate', ($i <= $joinedDate_max_role) );

    /* Friends Count */
    $friendsCount_value = $this->getValue('friendsCount');
    if( empty($friendsCount_value) ) {
      $friendsCount_setting = end(Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'friendsCount'));
      $friendsCount_value = empty($friendsCount_setting)
                     ? 'everyone'
                     : $friendsCount_setting;
    }
    $friendsCount_max_role = array_search($friendsCount_value, $this->_roles);
    foreach( $this->_roles as $i => $role )
      $auth->setAllowed($user, $role, 'friendsCount', ($i <= $friendsCount_max_role) );
    
    // Process member profile commenting privacy
    $comment_value = $this->getValue('comment');
    if( empty($comment_value) ) {
      $comment_setting = end(Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'auth_comment'));
      $comment_value = empty($comment_setting)
                     ? 'registered'
                     : $comment_setting;
    }

    $comment_max_role = array_search($comment_value, $this->_roles);
    foreach( $this->_roles as $i => $role )
      $auth->setAllowed($user, $role, 'comment', ($i <= $comment_max_role) );

    // Process member mention privacy
    $mention_value = $this->getValue('mention');
    if( empty($mention_value) ) {
      $mention_setting = end(Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'mention'));
      $mention_value = empty($mention_setting)
                     ? 'registered'
                     : $mention_setting;
    }

    $mention_max_role = array_search($mention_value, $this->_roles);
    foreach( $this->_roles as $i => $role )
      $auth->setAllowed($user, $role, 'mention', ($i <= $mention_max_role) );
    
    // Process member poke privacy
    if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('poke')) {
      $poke_action_value = $this->getValue('pokeAction');
      if( empty($poke_action_value) ) {
        $poke_action_setting = end(Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $user, 'pokeAction'));
        $poke_action_value = empty($poke_action_setting) ? 'registered' : $poke_action_setting;
      }

      $poke_max_role = array_search($poke_action_value, $this->_roles);
      foreach( $this->_roles as $i => $role )
        $auth->setAllowed($user, $role, 'pokeAction', ($i <= $poke_max_role) );
    }
  }
}
