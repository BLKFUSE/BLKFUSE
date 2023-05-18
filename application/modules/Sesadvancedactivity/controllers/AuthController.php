<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AuthController.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
require(realpath(dirname(__FILE__) . '/..') . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR . 'twitter'.DIRECTORY_SEPARATOR.'autoload.php');
    use Abraham\TwitterOAuth\TwitterOAuth;
class Sesadvancedactivity_AuthController extends Core_Controller_Action_Standard {

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
    $this->view->error = true;
    $this->view->success = false;
    // Enabled?
    if( !$facebook || 'none' == $settings->core_facebook_enable ) {
      $this->view->error = true;
    }
    
    // Already connected
    if( $facebook->getUser() ) {
       $code = $facebook->getPersistentData('code');
        $this->view->success = true;
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

    // Not connected
    else {
      
      // Okay
      if( !empty($_GET['code']) ) {
       $this->view->error = true;
      }
      
      // Error
      else if( !empty($_GET['error']) ) {
       $this->view->error = true;;
      }

      // Redirect to auth page
      else {
        $url = $facebook->getLoginUrl(array(
          'redirect_uri' => (_ENGINE_SSL ? 'https://' : 'http://') 
              . $_SERVER['HTTP_HOST'] . $this->view->url(),
          'scope' => join(',', array(
            'email',
            'user_birthday',
            'publish_actions',
          )),
        ));
        return $this->_helper->redirector->gotoUrl($url, array('prependBase' => false));
      }
    }
  }
 
  public function twitterAction()
  {
    $this->view->error = true;
    $this->view->success = false;
    // Clear
    if( null !== $this->_getParam('clear') ) {
        unset($_SESSION['twitter_lock']);
        unset($_SESSION['twitter_token']);
        unset($_SESSION['twitter_secret']);
        unset($_SESSION['twitter_token2']);
        unset($_SESSION['twitter_secret2']);
    }

    if( $this->_getParam('denied') ) {
        $this->view->error = 'Access Denied!';
        return;
    }

    // Setup
    $viewer = Engine_Api::_()->user()->getViewer();
    $twitterTable = Engine_Api::_()->getDbtable('twitter', 'user');
    $twitter = $twitterTable->getApi();
    $twitterOauth = $twitterTable->getOauth();
    $settings = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.twitter');
    $db = Engine_Db_Table::getDefaultAdapter();
    $ipObj = new Engine_IP();
    $ipExpr = new Zend_Db_Expr($db->quoteInto('UNHEX(?)', bin2hex($ipObj->toBinary())));
    $callback = ((_ENGINE_SSL ? "https://" : "http://") . $_SERVER['HTTP_HOST']) . Zend_Registry::get('StaticBaseUrl') . 'sesadvancedactivity/auth/twitter';

    define('CONSUMER_KEY', $settings['key']);  // add your app consumer key between single quotes
    define('CONSUMER_SECRET', $settings['secret']); // add your app consumer                                       secret key between single quotes
    define('OAUTH_CALLBACK', $callback); // your app callback URL i.e. 

    // Check
    if( !$twitter || !$twitterOauth ) {
      return;
    }

    // Connect
    try {

        $accountInfo = null;
        if(isset($_SESSION['oauth_token']) && isset($_GET['oauth_token'])){
            // Try to login?
            //if( !$viewer->getIdentity() ) {
                // Get account info
                try {
                  $oauth_token=$_SESSION['oauth_token'];unset($_SESSION['oauth_token']);
                  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
                 //necessary to get access token other wise u will not have permision to get user info
                  $params=array("oauth_verifier" => $_GET['oauth_verifier'],"oauth_token"=>$_GET['oauth_token']);
                  $access_token = $connection->oauth("oauth/access_token", $params);
                  //now again create new instance using updated return oauth_token and oauth_token_secret because old one expired if u dont u this u will also get token expired error
                  $connection = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET,
                  $access_token['oauth_token'],$access_token['oauth_token_secret']);
                  $twitter_token = $access_token['oauth_token'];
                  $twitter_secret = $access_token['oauth_token_secret'];
                  $accountInfo = $connection->get("account/verify_credentials");
                    // Reload api?
            $twitterTable->clearApi();
            $twitter = $twitterTable->getApi();

            // Save to settings table (if logged in)
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
                $this->view->success = true;
                return;
                } catch( Exception $e ) {
                    // This usually happens when the application is modified after connecting
                    unset($_SESSION['twitter_token']);
                    unset($_SESSION['twitter_secret']);
                    unset($_SESSION['twitter_token2']);
                    unset($_SESSION['twitter_secret2']);
                    $twitterTable->clearApi();
                    $twitter = $twitterTable->getApi();
                    $twitterOauth = $twitterTable->getOauth();
                }
            //}
        }

        if(isset($_SESSION['oauth_token']) && isset($_GET['oauth_token']) && false){ 
            $twitterOauth->getAccessToken('https://twitter.com/oauth/access_token', $_GET['oauth_verifier']);

            $_SESSION['twitter_token2'] = $twitter_token;
            $_SESSION['twitter_secret2'] = $twitter_secret;

            // Reload api?
            $twitterTable->clearApi();
            $twitter = $twitterTable->getApi();

            // Save to settings table (if logged in)
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
                $this->view->success = true;

            } else { // Otherwise try to login?
              return;
            }
        } else {

            unset($_SESSION['twitter_token']);
            unset($_SESSION['twitter_secret']);
            unset($_SESSION['twitter_token2']);
            unset($_SESSION['twitter_secret2']);
            $_SESSION['sesadvancedactivity_twitter_token'] = 1;
            // Reload api?
            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
          $temporary_credentials = $connection->oauth('oauth/request_token', array("oauth_callback" =>$callback));
          $_SESSION['oauth_token']=$temporary_credentials['oauth_token'];   
          $_SESSION['oauth_token_secret']=$temporary_credentials['oauth_token_secret'];
          $url = $connection->url("oauth/authorize", array("oauth_token" => $temporary_credentials['oauth_token']));
        // REDIRECTING TO THE URL
          header('Location: ' . $url); 
        }
    } catch( Services_Twitter_Exception $e ) {
        if( engine_in_array($e->getCode(), array(500, 502, 503)) ) {
            $this->view->error = 'Twitter is currently experiencing technical issues, please try again later.';
            return;
        } else {
            throw $e;
        }
    } catch( Exception $e ) {
        throw $e;
    }
    
  }
  public function linkedinAction(){
    $this->view->error = true;
    $this->view->success = false;
    
   if( null !== $this->_getParam('clear') && empty($_GET['oauth_verifier'])) {
     unset($_SESSION['linkedin_lock']);
     unset($_SESSION['linkedin_uid']);
     unset($_SESSION['linkedin_secret']);
     unset($_SESSION['linkedin_token']);
     unset($_SESSION['linkedin_token']);
     unset($_SESSION['linkedin_access']);
   }
   if( $this->_getParam('denied') ) {
      $this->view->error = 'Access Denied!';
      return;
   }
    // Setup
    $viewer = Engine_Api::_()->user()->getViewer();
    $likedinTable = Engine_Api::_()->getDbtable('linkedin', 'sesadvancedactivity');
    $likedin = $likedinTable->getApi();
    $access = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.linkedin.access','');
    $secret = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.linkedin.secret','');
    $db = Engine_Db_Table::getDefaultAdapter();
    
    // Check
    if(empty($likedin)) {
      $this->error = true;
    } 
    try{
      if(empty($_GET['oauth_verifier'])){
        $likedin->setCallbackUrl((_ENGINE_SSL ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $this->view->url());
        $likedin->setTokenAccess(NULL);
        $result = $likedin->retrieveTokenRequest();
        if ($result['success'] === TRUE) {
          $_SESSION['linkedin_token'] = $result['linkedin']['oauth_token'];
          $_SESSION['oauth_token_secret']  = $result['linkedin']['oauth_token_secret'];
          header('Location: ' . Linkedin::_URL_AUTH . $result['linkedin']['oauth_token']);
      }
      }else if(!empty($_GET['oauth_verifier'])){
         $likedin->setCallbackUrl((_ENGINE_SSL ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $this->view->url());
         $result = $likedin->retrieveTokenAccess($_SESSION['linkedin_token'], $_SESSION['oauth_token_secret'], $_GET['oauth_verifier']);
      if ($result['success'] == TRUE) {
        $_SESSION['linkedin_token'] = $token = $result['linkedin']['oauth_token'];
        $_SESSION['linkedin_secret'] = $secret = $result['linkedin']['oauth_token_secret'];        
        $_SESSION['linkedin_access'] = $result['linkedin'];

        // Get account info
        $user = $likedin->profile('~:(id)');

        $user = json_decode(json_encode((array) simplexml_load_string($user['linkedin'])), 1);
        $userid = $user['id'];
        if(!$userid)
          return;
        $_SESSION['linkedin_lock'] = true;
        $_SESSION['linkedin_uid'] = $userid;
       }
       // Save to settings table (if logged in)
        if( $viewer->getIdentity() ) {
          $info = $likedinTable->select()
              ->from($likedinTable)
              ->where('user_id = ?', $viewer->getIdentity())
              ->query()
              ->fetch();

          if( !empty($info) ) {
            $likedinTable->update(array(
              'linkedin_uid' => $userid,
              'access_token' => $_SESSION['linkedin_token'],
              'code' => $_SESSION['linkedin_secret'],
            ), array(
              'user_id = ?' => $viewer->getIdentity(),
            ));
          } else {
            $likedinTable->insert(array(
              'user_id' => $viewer->getIdentity(),
              'linkedin_uid' => $userid,
              'access_token' => $_SESSION['linkedin_token'],
              'code' => $_SESSION['linkedin_secret'],
            ));
          }

	  // Redirect
         $this->view->success = true;

        }
      }
      
    }catch(Exception $e){
      throw $e;
      $this->view->error = true;  
    }
  }
}
