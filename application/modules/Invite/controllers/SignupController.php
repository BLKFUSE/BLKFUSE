<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Invite
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: SignupController.php 9747 2012-07-26 02:08:08Z john $
 * @author     Steve
 */

/**
 * @category   Application_Extensions
 * @package    Invite
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Invite_SignupController extends Engine_Controller_Action
{
  public function __call($method, $args)
  {
    // Psh, you're already signed up
    $viewer = Engine_Api::_()->user()->getViewer();
    if( $viewer && $viewer->getIdentity() ) {
      return $this->_helper->redirector->gotoRoute(array(), 'default', true);
    }
    
    //Referral work
    $referralCode = $this->_getParam('referral_code');
    if (!empty($referralCode)) {
      $referral = Engine_Api::_()->getDbTable('users', 'user')->getUserExist('', $referralCode);
      if ($referral) {
        $session = new Zend_Session_Namespace('invite_referral_signup');
        $session->user_id = $referral->user_id;
        $session->referral_code = $referral->referral_code;
      }
    }

    // Get invite params
    $session = new Zend_Session_Namespace('invite');
    $session->invite_code = $this->_getParam('code') ? $this->_getParam('code') : $referralCode;
    $session->invite_email = $this->_getParam('email');

    // Check code now if set
    $settings = Engine_Api::_()->getApi('settings', 'core');
    if( $settings->getSetting('user.signup.inviteonly') > 0 ) {
      // Tsk tsk no code
      if( empty($session->invite_code) ) {
        return $this->_helper->redirector->gotoRoute(array(), 'default', true);
      }
      
      // Check code
      $inviteTable = Engine_Api::_()->getDbtable('invites', 'invite');
      $inviteSelect = $inviteTable->select()
        ->where('code = ?', $session->invite_code);

      // Check email
      if( $settings->getSetting('user.signup.checkemail') ) {
        // Tsk tsk no email
        if( empty($session->invite_email) ) {
          return $this->_helper->redirector->gotoRoute(array(), 'default', true);
        }
        $inviteSelect
          ->where('recipient = ?', $session->invite_email);
      }

      $inviteRow = $inviteTable->fetchRow($inviteSelect);

      // No invite or already signed up
      if( !$inviteRow || $inviteRow->new_user_id ) {
        return $this->_helper->redirector->gotoRoute(array(), 'default', true);
      }
    }

    return $this->_helper->redirector->gotoRoute(array(), 'user_signup', true);
  }
}
