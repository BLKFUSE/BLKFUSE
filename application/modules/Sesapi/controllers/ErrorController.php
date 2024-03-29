<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesapi
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: ErrorController.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesapi_ErrorController extends Core_Controller_Action_Standard
{
  public function errorAction()
  {
    //$request = Zend_Controller_Front::getInstance()->getRequest();
    $error = $this->_getParam('error_handler');
    $this->view->error_code = $error_code = Engine_Api::getErrorCode(true);
    // Handle missing pages
    switch( $error->type ) {
      case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
      case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        return $this->_forward('notfound');
        break;

      default:
        break;
    }
    if(empty($_GET['debug'])){
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=> Zend_Registry::get('Zend_Translate')->_('An error has occurred'), 'result' => array()));
    }
    // Log this message
    if( isset($error->exception) &&
        Zend_Registry::isRegistered('Zend_Log') &&
        ($log = Zend_Registry::get('Zend_Log')) instanceof Zend_Log ) {
      // Only log if in production or the exception is not an instance of Engine_Exception
      $e = $error->exception;
      if( 'production' === APPLICATION_ENV || !($e instanceof Engine_Exception) ) {
        $output = '';
        $output .= PHP_EOL . 'Error Code: ' . $error_code . PHP_EOL;
        $output .= $e->__toString();
        $log->log($output, Zend_Log::CRIT, array(
          'exception' => $e
        ));
      }
    }
    
    //$this->getResponse()->setRawHeader('HTTP/1.1 500 Internal server error');
    $this->view->status = false;
    $this->view->errorName = get_class($error->exception);

    if( APPLICATION_ENV != 'production' ) {
      if( $error->exception instanceof Exception ){
        $this->view->error = $error->exception->__toString();
      }
    } else {
      $this->view->message = Zend_Registry::get('Zend_Translate')->_('An error has occurred');
    }
  }

  public function notfoundAction()
  {
    if(empty($_GET['debug'])){
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=> Zend_Registry::get('Zend_Translate')->_('The requested resource could not be found.'), 'result' => array()));
    }
    // 404 error -- controller or action not found
    $this->getResponse()->setRawHeader($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    $this->view->status = false;
    $this->view->error = Zend_Registry::get('Zend_Translate')->_('The requested resource could not be found.');

    $static = 'jpg|gif|png|ico|flv|htm|html|php|css|js';
    $ext = pathinfo($this->getRequest()->getRequestUri(), PATHINFO_EXTENSION);
    if( engine_in_array($ext, explode('|', $static)) ) {
      $this->getResponse()->sendHeaders();
      echo $this->view->error;
      exit;
    }
  }

  public function requiresubjectAction()
  {
    if(empty($_GET['debug'])){
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=> Zend_Registry::get('Zend_Translate')->_('The requested resource could not be found.'), 'result' => array()));
    }
    return $this->_forward('notfound');
  }

  public function requireauthAction()
  {
    if(empty($_GET['debug'])){
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=> Zend_Registry::get('Zend_Translate')->_('You are not authorized to access this resource.'), 'result' => array()));
    }
     // 403 error -- authorization failed
    if( !$this->_helper->requireUser()->isValid() ) return;
    $this->getResponse()->setRawHeader($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    $this->view->status = false;
    $this->view->error = Zend_Registry::get('Zend_Translate')->_('You are not authorized to access this resource.');
  }

  public function requireuserAction()
  {
    if(empty($_GET['debug'])){
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=> Zend_Registry::get('Zend_Translate')->_('You are not authorized to access this resource.'), 'result' => array()));
    }
    // 403 error -- authorization failed
    $this->getResponse()->setRawHeader($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    $this->view->status = false;
    $this->view->error = Zend_Registry::get('Zend_Translate')->_('You are not authorized to access this resource.');

    // Show the login form for them :P
    $this->view->form = $form = new User_Form_Login();
    $form->addError('Please sign in to continue..');
    $form->return_url->setValue(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
    
    // Render
    $this->_helper->content
        //->setNoRender()
        ->setEnabled()
        ;
  }

  public function requireadminAction()
  {
    return $this->_forward('notfound');
  }
}
