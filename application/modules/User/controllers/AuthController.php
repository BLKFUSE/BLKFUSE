<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: AuthController.php 10149 2014-03-26 19:59:07Z lucas $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
 
require(realpath(dirname(__FILE__) . '/..') . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR . 'Twitter'.DIRECTORY_SEPARATOR.'autoload.php');
use TwitterSE\TwitterOAuth\TwitterOAuth;

class User_AuthController extends Core_Controller_Action_Standard
{
    function timeDiff($seconds){
        // extract hours
        $hours = floor($seconds / (60 * 60));
        // extract minutes
        $divisor_for_minutes = $seconds % (60 * 60);
        $minutes = floor($divisor_for_minutes / 60);
        // extract the remaining seconds
        $divisor_for_seconds = $divisor_for_minutes % 60;
        $seconds = ceil($divisor_for_seconds);
        // return the final array
        $string = "";
        if($hours > 0)
            $string .= $hours.($hours != 1 ? " hours " : " hour ");
        if($minutes > 0)
            $string .= $minutes.($minutes != 1 ? " minutes " : " minute ");
        if($seconds > 0)
            $string .= $seconds.($seconds != 1 ? " seconds " : " second ");
        return trim($string," ");
    }
    public function loginAction()
    {
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $enableloginlogs = $settings->getSetting('core.general.enableloginlogs', 0);

        // Already logged in
        if( Engine_Api::_()->user()->getViewer()->getIdentity() ) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('You are already signed in.');
            if( null === $this->_helper->contextSwitch->getCurrentContext() ) {
                $this->_helper->redirector->gotoRoute(array(), 'default', true);;
            }
            return;
        }

        // Make form
        $this->view->form = $form = new User_Form_Login();
        $form->setAction($this->view->url(array('return_url' => null), 'user_login'));
        $form->populate(array(
            'return_url' => $this->_getParam('return_url'),
        ));

        // Render
        $disableContent = $this->_getParam('disableContent', 0);
        if( !$disableContent ) {
            $this->_helper->content
                ->setEnabled()
            ;
        }

        // Not a post
        if( !$this->getRequest()->isPost() ) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('No action taken');
            return;
        }

        // Form not valid
        if( !$form->isValid($this->getRequest()->getPost()) ) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid data');
            return;
        }

        // Check login creds
        extract($form->getValues()); // $email, $password, $remember
        
        $user_table = Engine_Api::_()->getDbtable('users', 'user');
        
        //login with username
        $emailField = 'email';
        if(Engine_Api::_()->getApi('settings', 'core')->getSetting('user.signup.username', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('user.signup.allowloginusername', 0)) {
          if (strpos($email, '@') == false) { 
            $emailField = 'username';
          }
        }
        
        $user_select = $user_table->select()
            ->where("`$emailField` = ?", $email);          // If post exists
        $user = $user_table->fetchRow($user_select);

        // Get ip address
        $db = Engine_Db_Table::getDefaultAdapter();
        $ipObj = new Engine_IP();
        $ipExpr = new Zend_Db_Expr($db->quoteInto('UNHEX(?)', bin2hex($ipObj->toBinary())));

        // Check if user exists
        if( empty($user) ) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('The credentials you have supplied are invalid. Please check your email and password, and try again.');
            $form->addError(Zend_Registry::get('Zend_Translate')->_('The credentials you have supplied are invalid. Please check your email and password, and try again.'));
            
            if(!empty($enableloginlogs)) {
              // Register login
              Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                  'email' => $email,
                  'ip' => $ipExpr,
                  'timestamp' => new Zend_Db_Expr('NOW()'),
                  'state' => 'no-member',
              )); 
            }
            return;
        }

        $lockAccount = ($settings
            ->getSetting('core.spam.lockaccount', 0));
        $lockAttempts = ($settings
            ->getSetting('core.spam.lockattempts', 3));
        $lockDuration = ($settings
            ->getSetting('core.spam.lockduration', 120));


        if($lockAccount && $user->login_attempt_count && $user->login_attempt_count >= $lockAttempts){
            if(strtotime($user->last_login_attempt) + $lockDuration > time()){
                $this->view->status = false;
                $timeDiff = $this->timeDiff(strtotime($user->last_login_attempt) + $lockDuration - time());
                $this->view->error = $this->view->translate('You have reached maximum login attempts. Please try after %s.',$timeDiff);
                $form->addError($this->view->translate('You have reached maximum login attempts. Please try after %s.',$timeDiff));
                $user->login_attempt_count = $user->login_attempt_count + 1;
                $user->save();
                return;
            }else{
                $user->last_login_attempt = NULL;
                $user->login_attempt_count = 0;
                $user->save();
            }
        }

        $isValidPassword = Engine_Api::_()->user()->checkCredential($user->getIdentity(), $password,$user);

        if (!$isValidPassword) {
            if($lockAccount){
                $user->last_login_attempt = date('Y-m-d H:i:s');
                $user->login_attempt_count = $user->login_attempt_count + 1;
                $user->save();
            }
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('The credentials you have supplied are invalid. Please check your email and password, and try again.');
            $form->addError(Zend_Registry::get('Zend_Translate')->_('The credentials you have supplied are invalid. Please check your email and password, and try again.'));
            
            if(!empty($enableloginlogs)) {
              // Register bad password login
              Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                  'user_id' => $user->getIdentity(),
                  'email' => $email,
                  'ip' => $ipExpr,
                  'timestamp' => new Zend_Db_Expr('NOW()'),
                  'state' => 'bad-password',
              ));
            }
            return;
        }

        // Check if user is verified and enabled
        if( !$user->enabled ) {
            if( !$user->verified ) {
                $this->view->status = false;

                $token = Engine_Api::_()->user()->getVerifyToken($user->getIdentity());
                $resend_url = $this->_helper->url->url(array('action' => 'resend', 'token'=> $token), 'user_signup', true);
                $translate = Zend_Registry::get('Zend_Translate');
                $error = $translate->translate('This account still requires either email verification or admin approval.');
                $error .= ' ';
                $error .= sprintf($translate->translate('Click <a href="%s">here</a> to resend the email.'), $resend_url);
                $form->getDecorator('errors')->setOption('escape', false);
                $form->addError($error);
                
                if(!empty($enableloginlogs)) {
                  // Register login
                  Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                      'user_id' => $user->getIdentity(),
                      'email' => $email,
                      'ip' => $ipExpr,
                      'timestamp' => new Zend_Db_Expr('NOW()'),
                      'state' => 'disabled',
                  ));
                }

                return;
            } else if( !$user->approved ) {
                $this->view->status = false;

                $translate = Zend_Registry::get('Zend_Translate');
                $error = $translate->translate('This account still requires admin approval.');
                $form->getDecorator('errors')->setOption('escape', false);
                $form->addError($error);

                if(!empty($enableloginlogs)) {
                  // Register login
                  Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                      'user_id' => $user->getIdentity(),
                      'email' => $email,
                      'ip' => $ipExpr,
                      'timestamp' => new Zend_Db_Expr('NOW()'),
                      'state' => 'disabled',
                  ));
                }

                return;
            }
            // Should be handled by hooks or payment
            //return;
        }

        // Handle subscriptions
        if( Engine_Api::_()->hasModuleBootstrap('payment') ) {
            // Check for the user's plan
            $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
            if( !$subscriptionsTable->check($user) ) {
              if(!empty($enableloginlogs)) {
                // Register login
                Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                    'user_id' => $user->getIdentity(),
                    'email' => $email,
                    'ip' => $ipExpr,
                    'timestamp' => new Zend_Db_Expr('NOW()'),
                    'state' => 'unpaid',
                ));
              }
              // Redirect to subscription page
              $subscriptionSession = new Zend_Session_Namespace('Payment_Subscription');
              $subscriptionSession->unsetAll();
              $subscriptionSession->user_id = $user->getIdentity();
              return $this->_helper->redirector->gotoRoute(array('module' => 'payment',
                  'controller' => 'subscription', 'action' => 'index'), 'default', true);
            }
        }

        // Run pre login hook
        $event = Engine_Hooks_Dispatcher::getInstance()->callEvent('onUserLoginBefore', $user);
        foreach( (array) $event->getResponses() as $response ) {
            if( is_array($response) ) {
                if( !empty($response['error']) && !empty($response['message']) ) {
                    $form->addError($response['message']);
                } else if( !empty($response['redirect']) ) {
                    $this->_helper->redirector->gotoUrl($response['redirect'], array('prependBase' => false));
                } else {
                    continue;
                }

                if(!empty($enableloginlogs)) {
                  // Register login
                  Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                      'user_id' => $user->getIdentity(),
                      'email' => $email,
                      'ip' => $ipExpr,
                      'timestamp' => new Zend_Db_Expr('NOW()'),
                      'state' => 'third-party',
                  ));
                }

                // Return
                return;
            }
        }

        // Version 3 Import compatibility
        if( empty($user->password) ) {
            $compat = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.compatibility.password');
            $migration = null;
            try {
                $migration = Engine_Db_Table::getDefaultAdapter()->select()
                    ->from('engine4_user_migration')
                    ->where('user_id = ?', $user->getIdentity())
                    ->limit(1)
                    ->query()
                    ->fetch();
            } catch( Exception $e ) {
                $migration = null;
                $compat = null;
            }
            if( !$migration ) {
                $compat = null;
            }

            if( $compat == 'import-version-3' ) {

                // Version 3 authentication
                $cryptedPassword = self::_version3PasswordCrypt($migration['user_password_method'], $migration['user_code'], $password);
                if( $cryptedPassword === $migration['user_password'] ) {
                    // Regenerate the user password using the given password
                    $user->salt = (string) rand(1000000, 9999999);
                    $user->password = $password;
                    $user->save();
                    Engine_Api::_()->user()->getAuth()->getStorage()->write($user->getIdentity());
                    // @todo should we delete the old migration row?
                } else {
                    $this->view->status = false;
                    $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid credentials');
                    $form->addError(Zend_Registry::get('Zend_Translate')->_('Invalid credentials supplied'));
                    return;
                }
                // End Version 3 authentication

            } else {
                $form->addError('There appears to be a problem logging in. Please reset your password with the Forgot Password link.');

                if(!empty($enableloginlogs)) {
                  // Register login
                  Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                      'user_id' => $user->getIdentity(),
                      'email' => $email,
                      'ip' => $ipExpr,
                      'timestamp' => new Zend_Db_Expr('NOW()'),
                      'state' => 'v3-migration',
                  ));
                }

                return;
            }
        }

        // Normal authentication
        else {
            $authResult = Engine_Api::_()->user()->authenticate($email, $password,$user);
            $authCode = $authResult->getCode();
            Engine_Api::_()->user()->setViewer();

            if( $authCode != Zend_Auth_Result::SUCCESS  ) {
                $this->view->status = false;
                $this->view->error = Zend_Registry::get('Zend_Translate')->_('The credentials you have supplied are invalid. Please check your email and password, and try again.');
                $form->addError(Zend_Registry::get('Zend_Translate')->_('The credentials you have supplied are invalid. Please check your email and password, and try again.'));

                if(!empty($enableloginlogs)) {
                  // Register login
                  Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                      'user_id' => $user->getIdentity(),
                      'email' => $email,
                      'ip' => $ipExpr,
                      'timestamp' => new Zend_Db_Expr('NOW()'),
                      'state' => 'bad-password',
                  ));
                }

                return;
            }
        }

        // -- Success! --

        // Register login
        $loginTable = Engine_Api::_()->getDbtable('logins', 'user');
        $loginTable->insert(array(
            'user_id' => $user->getIdentity(),
            'email' => $email,
            'ip' => $ipExpr,
            'timestamp' => new Zend_Db_Expr('NOW()'),
            'state' => 'success',
            'active' => true,
        ));
        $_SESSION['login_id'] = $login_id = $loginTable->getAdapter()->lastInsertId();

        // Remember
        if( @$remember ) {
            $lifetime = 1209600; // Two weeks
            Zend_Session::getSaveHandler()->setLifetime($lifetime, true);
            Zend_Session::rememberMe($lifetime);
        }

        // Increment sign-in count
        Engine_Api::_()->getDbtable('statistics', 'core')
            ->increment('user.logins');

        // Test activity @todo remove
        $viewer = Engine_Api::_()->user()->getViewer();
        if( $viewer->getIdentity() ) {
            $viewer->lastlogin_date = date("Y-m-d H:i:s");
            if( 'cli' !== PHP_SAPI ) {
                $viewer->lastlogin_ip = $ipExpr;
            }
            $viewer->save();
            Engine_Api::_()->getDbtable('actions', 'activity')
                ->addActivity($viewer, $viewer, 'login');
        }

        // Assign sid to view for json context
        $this->view->status = true;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('Login successful');
        $this->view->sid = Zend_Session::getId();
        $this->view->sname = Zend_Session::getOptions('name');

        // Run post login hook
        $event = Engine_Hooks_Dispatcher::getInstance()->callEvent('onUserLoginAfter', $viewer);

        // Do redirection only if normal context
        if( null === $this->_helper->contextSwitch->getCurrentContext() ) {
            // Redirect by form
            $uri = $form->getValue('return_url');
            if( $uri ) {
                if (substr($uri, 0, 3) == '64-') {
                    $uri = base64_decode(substr($uri, 3));
                    if (strpos($uri, "http://") === true || strpos($uri, "https://") === true) {
                        $url = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $this->view->layout()->staticBaseUrl;
                        return $this->_redirect($url, array('prependBase' => false));
                    } else {
                        $url = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'].$uri;
                        return $this->_redirect($url, array('prependBase' => false));
                    }
                } elseif(strlen($uri) > 0){
                    $url = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'].$uri;
                    return $this->_redirect($url, array('prependBase' => false));
                }
            }

            // Redirect by session
            $session = new Zend_Session_Namespace('Redirect');
            if( isset($session->uri) ) {
                $uri  = $session->uri;
                $opts = $session->options;
                $session->unsetAll();
                return $this->_redirect($uri, $opts);
            } else if( isset($session->route) ) {
                $session->unsetAll();
                return $this->_helper->redirector->gotoRoute($session->params, $session->route, $session->reset);
            }

            // Redirect by hook
            foreach( (array) $event->getResponses() as $response ) {
                if( is_array($response) ) {
                    if( !empty($response['error']) && !empty($response['message']) ) {
                        return $form->addError($response['message']);
                    } else if( !empty($response['redirect']) ) {
                        return $this->_helper->redirector->gotoUrl($response['redirect'], array('prependBase' => false));
                    }
                }
            }

            // Redirect to edit profile if user has no profile type
            $aliasedFields = $viewer->fields()->getFieldsObjectsByAlias();
            $profileType = isset($aliasedFields['profile_type']) ?
                (is_object($aliasedFields['profile_type']->getValue($user)) ?
                    $aliasedFields['profile_type']->getValue($viewer)->value : null
                ) : null;

//             if (empty($profileType)) {
//                 return $this->_helper->redirector->gotoRoute(array(
//                     'action' => 'profile',
//                     'controller' => 'edit',
//                 ), 'user_extended', false);
//             }

          //Redirection
          $afterLogin = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.after.login', 4);
          if($afterLogin == 4) {
            return $this->_helper->_redirector->gotoRoute(array('action' => 'home'), 'user_general', true);
          } else if($afterLogin == 3) {
            return $this->_helper->redirector->gotoRoute(array('id' => $viewer->getIdentity()), 'user_profile', true);
          } else if($afterLogin == 2) { 
            return $this->_helper->redirector->gotoRoute(array('controller' => 'edit','action' => 'profile'), 'user_extended', true);
          } else if($afterLogin == 1) {
            header('Location: '.Engine_Api::_()->getApi('settings', 'core')->getSetting('core.loginurl', ''));
          }
        }
    }

    public function logoutAction()
    {
        // Check if already logged out
        $viewer = Engine_Api::_()->user()->getViewer();
        if( !$viewer->getIdentity() ) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('You are already logged out.');
            if( null === $this->_helper->contextSwitch->getCurrentContext() ) {
                $this->_helper->redirector->gotoRoute(array(), 'default', true);
            }
            return;
        }

        // Run logout hook
        $event = Engine_Hooks_Dispatcher::getInstance()->callEvent('onUserLogoutBefore', $viewer);

        // Test activity @todo remove
        Engine_Api::_()->getDbtable('actions', 'activity')
            ->addActivity($viewer, $viewer, 'logout');

        // Update online status
        $onlineTable = Engine_Api::_()->getDbtable('online', 'user')
            ->delete(array(
                'user_id = ?' => $viewer->getIdentity(),
            ));

        // Logout
        Engine_Api::_()->user()->getAuth()->clearIdentity();

        if( !empty($_SESSION['login_id']) ) {
            Engine_Api::_()->getDbtable('logins', 'user')->update(array(
                'active' => false,
            ), array(
                'login_id = ?' => $_SESSION['login_id'],
            ));
            unset($_SESSION['login_id']);
        }


        // Run logout hook
        $event = Engine_Hooks_Dispatcher::getInstance()->callEvent('onUserLogoutAfter', $viewer);

        $doRedirect = true;

        // Clear twitter/facebook session info

        // facebook api
        $facebookTable = Engine_Api::_()->getDbtable('facebook', 'user');
        $facebook = $facebookTable->getApi();
        $settings = Engine_Api::_()->getDbtable('settings', 'core');
        if( $facebook && 'none' != $settings->core_facebook_enable ) {
            /*
            $logoutUrl = $facebook->getLogoutUrl(array(
              'next' => 'http://' . $_SERVER['HTTP_HOST'] . $this->view->url(array(), 'default', true),
            ));
            */
            if( method_exists($facebook, 'getAccessToken') &&
                ($access_token = $facebook->getAccessToken()) ) {
                $doRedirect = false; // javascript will run to log them out of fb
                $this->view->appId = $facebook->getAppId();
                $access_array = explode("|", $access_token);
                if ( ($session_key = $access_array[1]) ) {
                    $this->view->fbSession = $session_key;
                }
            }
            try {
                $facebook->clearAllPersistentData();
            } catch( Exception $e ) {
                // Silence
            }
        }

        unset($_SESSION['facebook_lock']);
        unset($_SESSION['facebook_uid']);

        // twitter api
        /*
        $twitterTable = Engine_Api::_()->getDbtable('twitter', 'user');
        $twitter = $twitterTable->getApi();
        $twitterOauth = $twitterTable->getOauth();
        if( $twitter && $twitterOauth ) {
          try {
            $result = $accountInfo = $twitter->account->end_session();
          } catch( Exception $e ) {
            // Silence
            echo $e;die();
          }
        }
        */
        unset($_SESSION['twitter_lock']);
        unset($_SESSION['twitter_token']);
        unset($_SESSION['twitter_secret']);
        unset($_SESSION['twitter_token2']);
        unset($_SESSION['twitter_secret2']);

        // Response
        $this->view->status = true;
        $this->view->message =  Zend_Registry::get('Zend_Translate')->_('You are now logged out.');
        if( $doRedirect && null === $this->_helper->contextSwitch->getCurrentContext() ) {
          //Redirection
          $afterLogout = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.after.logout', 3);
          if($afterLogout == 3) {
            return $this->_helper->redirector->gotoRoute(array(), 'default', true);
          } else if($afterLogout == 2) { 
            return $this->_helper->redirector->gotoRoute(array(), 'user_login', true);
          } else if($afterLogout == 1) {
            header('Location: '.Engine_Api::_()->getApi('settings', 'core')->getSetting('core.logouturl', ''));
          }
        }
    }

    public function forgotAction()
    {
        // Render
        $this->_helper->content
            //->setNoRender()
            ->setEnabled()
        ;

        // no logged in users
        if( Engine_Api::_()->user()->getViewer()->getIdentity() ) {
            return $this->_helper->redirector->gotoRoute(array('action' => 'home'), 'user_general', true);
        }

        // Make form
        $this->view->form = $form = new User_Form_Auth_Forgot();

        // Check request
        if( !$this->getRequest()->isPost() ) {
            return;
        }

        // Check data
        if( !$form->isValid($this->getRequest()->getPost()) ) {
            return;
        }

        // Check for existing user
        $user = Engine_Api::_()->getDbtable('users', 'user')
            ->fetchRow(array('email = ?' => $form->getValue('email')));
        if( !$user || !$user->getIdentity() ) {
            $form->addError('A user account with that email was not found.');
            return;
        }

        // Check to make sure they're enabled
        if( !$user->enabled ) {
            $form->addError('That user account has not yet been verified or disabled by an admin.');
            return;
        }

        // Ok now we can do the fun stuff
        $forgotTable = Engine_Api::_()->getDbtable('forgot', 'user');
        $db = $forgotTable->getAdapter();
        $db->beginTransaction();

        try
        {
            // Delete any existing reset password codes
            $forgotTable->delete(array(
                'user_id = ?' => $user->getIdentity(),
            ));

            // Create a new reset password code
            $code = base_convert(md5($user->salt . $user->email . $user->user_id . uniqid(time(), true)), 16, 36);
            $forgotTable->insert(array(
                'user_id' => $user->getIdentity(),
                'code' => $code,
                'creation_date' => date('Y-m-d H:i:s'),
            ));

            // Send user an email
            Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'core_lostpassword', array(
                'host' => $_SERVER['HTTP_HOST'],
                'email' => $user->email,
                'date' => time(),
                'recipient_title' => $user->getTitle(),
                'recipient_link' => $user->getHref(),
                'recipient_photo' => $user->getPhotoUrl('thumb.icon'),
                'object_link' => $this->_helper->url->url(array('action' => 'reset', 'code' => $code, 'uid' => $user->getIdentity())),
                'queue' => false,
            ));

            // Show success
            $this->view->sent = true;

            $db->commit();
        }

        catch( Exception $e )
        {
            $db->rollBack();
            throw $e;
        }
    }

    public function resetAction()
    {
        // no logged in users
        if( Engine_Api::_()->user()->getViewer()->getIdentity() ) {
            return $this->_helper->redirector->gotoRoute(array('action' => 'home'), 'user_general', true);
        }

        // Check for empty params
        $user_id = $this->_getParam('uid');
        $code = $this->_getParam('code');

        if( empty($user_id) || empty($code) ) {
            return $this->_helper->redirector->gotoRoute(array(), 'default', true);
        }

        // Check user
        $user = Engine_Api::_()->getItem('user', $user_id);
        if( !$user || !$user->getIdentity() ) {
            return $this->_helper->redirector->gotoRoute(array(), 'default', true);
        }

        // Check code
        $forgotTable = Engine_Api::_()->getDbtable('forgot', 'user');
        $forgotSelect = $forgotTable->select()
            ->where('user_id = ?', $user->getIdentity())
            ->where('code = ?', $code);

        $forgotRow = $forgotTable->fetchRow($forgotSelect);
        if( !$forgotRow || (int) $forgotRow->user_id !== (int) $user->getIdentity() ) {
            return $this->_helper->redirector->gotoRoute(array(), 'default', true);
        }

        // Code expired
        // Note: Let's set the current timeout for 1 hours for now
        $min_creation_date = time() - (3600 * 1);
        if( strtotime($forgotRow->creation_date) < $min_creation_date ) { // @todo The strtotime might not work exactly right
            return $this->_helper->redirector->gotoRoute(array(), 'default', true);
        }

        // Make form
        $this->view->form = $form = new User_Form_Auth_Reset();
        $form->setAction($this->_helper->url->url(array()));

        // Check request
        if( !$this->getRequest()->isPost() ) {
            return;
        }

        // Check data
        if( !$form->isValid($this->getRequest()->getPost()) ) {
            return;
        }

        // Process
        $values = $form->getValues();

        // Check same password
        if( $values['password'] !== $values['password_confirm'] ) {
            $form->addError('The passwords you entered did not match.');
            return;
        }

        // Db
        $db = $user->getTable()->getAdapter();
        $db->beginTransaction();

        try
        {
            // Delete the lost password code now
            $forgotTable->delete(array(
                'user_id = ?' => $user->getIdentity(),
            ));

            // This gets handled by the post-update hook
            $user->password = $values['password'];
            $user->save();
            if($form->resetalldevice->getValue()){ 
                Engine_Api::_()->getDbtable('session', 'core')->removeSessionByAuthId($user->user_id);
            }
            $db->commit();

            $this->view->reset = true;
            //return $this->_helper->redirector->gotoRoute(array(), 'user_login', true);
        } catch( Exception $e ) {
            $db->rollBack();
            throw $e;
        }
    }

    public function facebookAction()
    {
        // Clear
        if( null !== $this->_getParam('clear') ) {
            unset($_SESSION['facebook_lock']);
            unset($_SESSION['facebook_uid']);
        }

        $viewer = Engine_Api::_()->user()->getViewer();
        $facebookTable = Engine_Api::_()->getDbtable('facebook', 'user');
        $facebook = $facebookTable->getApi();
        $settings = Engine_Api::_()->getDbtable('settings', 'core');

        $db = Engine_Db_Table::getDefaultAdapter();
        $ipObj = new Engine_IP();
        $ipExpr = new Zend_Db_Expr($db->quoteInto('UNHEX(?)', bin2hex($ipObj->toBinary())));

        // Enabled?
        if( !$facebook || 'none' == $settings->core_facebook_enable ) {
            return $this->_helper->redirector->gotoRoute(array(), 'default', true);
        }

        // Already connected
        if( $facebook->getUser() ) {
            $code = $facebook->getPersistentData('code');

            // Attempt to login
            if( !$viewer->getIdentity() ) {
                $facebook_uid = $facebook->getUser();
                if( $facebook_uid ) {
                    $user_id = $facebookTable->select()
                        ->from($facebookTable, 'user_id')
                        ->where('facebook_uid = ?', $facebook_uid)
                        ->query()
                        ->fetchColumn();
                }
                if( $user_id &&
                    $viewer = Engine_Api::_()->getItem('user', $user_id) ) {
                    Zend_Auth::getInstance()->getStorage()->write($user_id);

                    // Register login
                    $viewer->lastlogin_date = date("Y-m-d H:i:s");

                    if( 'cli' !== PHP_SAPI ) {
                        $viewer->lastlogin_ip = $ipExpr;

                        Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                            'user_id' => $user_id,
                            'ip' => $ipExpr,
                            'timestamp' => new Zend_Db_Expr('NOW()'),
                            'state' => 'success',
                        ));
                    }

                    $viewer->save();
                } else if( $facebook_uid ) {
                    // They do not have an account
                    $_SESSION['facebook_signup'] = true;
                    return $this->_helper->redirector->gotoRoute(array(
                        //'action' => 'facebook',
                    ), 'user_signup', true);
                }
            } else {
                // Check for facebook user
                $facebookInfo = $facebookTable->select()
                    ->from($facebookTable)
                    ->where('facebook_uid = ?', $facebook->getUser())
                    ->limit(1)
                    ->query()
                    ->fetch();

                if (!empty($facebookInfo) && $facebookInfo['user_id'] != $viewer->getIdentity()) {
                    // Redirect to referer page
                    $url = $_SESSION['redirectURL'];
                    $parsedUrl = parse_url($url);
                    $separator = ($parsedUrl['query'] == NULL) ? '?' : '&';
                    $url .= $separator . 'already_integrated_fb_account=1';
                    $facebook->clearAllPersistentData();
                    return $this->_redirect($url, array('prependBase' => false));
                }
                // Attempt to connect account
                $info = $facebookTable->select()
                    ->from($facebookTable)
                    ->where('user_id = ?', $viewer->getIdentity())
                    ->limit(1)
                    ->query()
                    ->fetch();
                if( empty($info) ) {
                    $facebookTable->insert(array(
                        'user_id' => $viewer->getIdentity(),
                        'facebook_uid' => $facebook->getUser(),
                        'access_token' => $facebook->getAccessToken(),
                        'code' => $code,
                        'expires' => 0,
                    ));
                } else {
                    // Save info to db
                    $facebookTable->update(array(
                        'facebook_uid' => $facebook->getUser(),
                        'access_token' => $facebook->getAccessToken(),
                        'code' => $code,
                        'expires' => 0,
                    ), array(
                        'user_id = ?' => $viewer->getIdentity(),
                    ));
                }
            }

            // Redirect to referer page
            $url = $_SESSION['redirectURL'];
            return $this->_redirect($url, array('prependBase' => false));
        }

        // Not connected
        else {
            // Okay
            if( !empty($_GET['code']) ) {
                // This doesn't seem to be necessary anymore, it's probably
                // being handled in the api initialization
                return $this->_helper->redirector->gotoRoute(array(), 'default', true);
            }

            // Error
            else if( !empty($_GET['error']) ) {
                // @todo maybe display a message?
                return $this->_helper->redirector->gotoRoute(array(), 'default', true);
            }

            // Redirect to auth page
            else {
                $url = $facebook->getLoginUrl(array(
                    'redirect_uri' => (_ENGINE_SSL ? 'https://' : 'http://')
                        . $_SERVER['HTTP_HOST'] . $this->view->url(),
                    'scope' => join(',', array(
                        'email',
                    )),
                ));
                return $this->_helper->redirector->gotoUrl($url, array('prependBase' => false));
            }
        }
    }
    
    public function twitterAction() {

      $settings = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.twitter');
      
      $viewer = Engine_Api::_()->user()->getViewer();
      $twitterTable = Engine_Api::_()->getDbtable('twitter', 'user');
      $db = Engine_Db_Table::getDefaultAdapter();
      
      $ipObj = new Engine_IP();
      $ipExpr = new Zend_Db_Expr($db->quoteInto('UNHEX(?)', bin2hex($ipObj->toBinary())));

      //Add your app consumer key between single quotes
      define('CONSUMER_KEY', $settings['key']);
      //Add your app consumer secret key between single quotes
      define('CONSUMER_SECRET', $settings['secret']);

      $callback = ((_ENGINE_SSL ? "https://" : "http://") . $_SERVER['HTTP_HOST']) . Zend_Registry::get('StaticBaseUrl') . 'user/auth/twitter';
      //Your app callback URL i.e. 
      define('OAUTH_CALLBACK', $callback); 
      
      if(isset($_SESSION['oauth_token']) && isset($_GET['oauth_token'])) {
      
        $oauth_token = $_SESSION['oauth_token'];unset($_SESSION['oauth_token']);
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
        
        //necessary to get access token other wise u will not have permision to get user info
        $params=array("oauth_verifier" => $_GET['oauth_verifier'], "oauth_token" => $_GET['oauth_token']);
        $access_token = $connection->oauth("oauth/access_token", $params);
        
        //now again create new instance using updated return oauth_token and oauth_token_secret because old one expired if u dont u this u will also get token expired error
        $connection = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET, $access_token['oauth_token'],$access_token['oauth_token_secret']);
        
        $twitter_token = $access_token['oauth_token'];
        $twitter_secret = $access_token['oauth_token_secret'];
        $accountInfo = $connection->get("account/verify_credentials");
        
        if( $viewer->getIdentity() ) {
            $info = $twitterTable->select()
                ->from($twitterTable)
                ->where('user_id = ?', $viewer->getIdentity())
                ->query()
                ->fetch();
            if( !empty($info) ) {
                $twitterTable->update(array(
                    'twitter_uid' => $accountInfo->id,
                    'twitter_token' => $twitter_token,
                    'twitter_secret' => $twitter_secret,
                ), array(
                    'user_id = ?' => $viewer->getIdentity(),
                ));
            } else {
                $twitterTable->insert(array(
                    'user_id' => $viewer->getIdentity(),
                    'twitter_uid' => $accountInfo->id,
                    'twitter_token' => $twitter_token,
                    'twitter_secret' => $twitter_secret,
                ));
            }
            // Redirect
            return $this->_helper->redirector->gotoRoute(array(), 'default', true);
        } else { // Otherwise try to login?
            $info = $twitterTable->select()
                ->from($twitterTable)
                ->where('twitter_uid = ?', $accountInfo->id)
                ->query()
                ->fetch();
            if(empty($info) || empty($info['user_id'])) {
                // They do not have an account
                $_SESSION['twitter_signup'] = true;
                $name = explode(" ", $accountInfo->name);
                $fieldArray['id'] = $accountInfo->id;
                $fieldArray['photo'] = $accountInfo->profile_image_url;
                $fieldArray['first_name'] = @$name[0];
                $fieldArray['last_name'] =  @$name[1]; 
                $fieldArray['username'] =  $accountInfo->screen_name;
                $fieldArray['lang'] =  $accountInfo->lang;

                $_SESSION['twitter_token'] = $twitter_token;
                $_SESSION['twitter_secret'] = $twitter_secret;

                $_SESSION['twitter_uid'] = $accountInfo->id;
                $_SESSION['signup_fields'] = $fieldArray;
                return $this->_helper->redirector->gotoRoute(array(//'action' => 'twitter',
                ), 'user_signup', true);
            } else {
                Zend_Auth::getInstance()->getStorage()->write($info['user_id']);
                // Register login
                $viewer = Engine_Api::_()->getItem('user', $info['user_id']);
                $viewer->lastlogin_date = date("Y-m-d H:i:s");
                if( 'cli' !== PHP_SAPI ) {
                    $viewer->lastlogin_ip = $ipExpr;
                    Engine_Api::_()->getDbtable('logins', 'user')->insert(array(
                        'user_id' => $info['user_id'],
                        'ip' => $ipExpr,
                        'timestamp' => new Zend_Db_Expr('NOW()'),
                        'state' => 'success',
                        'source' => 'twitter',
                    ));
                }
                $viewer->save();
                // Redirect to referer page
                $url = $_SESSION['redirectURL'];
                return $this->_redirect($url, array('prependBase' => false));
            }
        }
      } else{
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
        $temporary_credentials = $connection->oauth('oauth/request_token', array("oauth_callback" =>$callback));
        $_SESSION['oauth_token']=$temporary_credentials['oauth_token'];   
        $_SESSION['oauth_token_secret']=$temporary_credentials['oauth_token_secret'];
        $url = $connection->url("oauth/authenticate", array("oauth_token" => $temporary_credentials['oauth_token']));
        // REDIRECTING TO THE URL
        header('Location: ' . $url); 
      }
    }

    static protected function _version3PasswordCrypt($method, $salt, $password)
    {
        // For new methods
        if( $method > 0 ) {
            if( !empty($salt) ) {
                list($salt1, $salt2) = str_split($salt, ceil(strlen($salt) / 2));
                $salty_password = $salt1.$password.$salt2;
            } else {
                $salty_password = $password;
            }
        }

        // Hash it
        switch( $method ) {
            // crypt()
            default:
            case 0:
                $user_password_crypt = crypt($password, '$1$'.str_pad(substr($salt, 0, 8), 8, '0', STR_PAD_LEFT).'$');
                break;

            // md5()
            case 1:
                $user_password_crypt = md5($salty_password);
                break;

            // sha1()
            case 2:
                $user_password_crypt = sha1($salty_password);
                break;

            // crc32()
            case 3:
                $user_password_crypt = sprintf("%u", crc32($salty_password));
                break;
        }

        return $user_password_crypt;
    }
}
