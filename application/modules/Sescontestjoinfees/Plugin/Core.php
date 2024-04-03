<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontestjoinfees_Plugin_Core extends Zend_Controller_Plugin_Abstract{
  function onSescontestParticipantCreateAfter($event){
     $payload = $event->getPayload();
     $contest = Engine_Api::_()->getItem('contest',$payload->contest_id);
     if($contest && $contest->entry_fees > 0){
       $getPaidOrder = Engine_Api::_()->sescontestjoinfees()->isUserHasPaidOrder();
       if($getPaidOrder){
        $getPaidOrder->entry_id = $payload->getIdentity();
        $getPaidOrder->save();  
       }
     }
  }
  public function routeShutdown(Zend_Controller_Request_Abstract $request) {
   if (substr($request->getPathInfo(), 1, 5) == "admin")
      return;
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $module = $request->getModuleName(); 
		$controller = $request->getControllerName();
		$action = $request->getActionName();
    if (($module == "sescontest") && $controller == "join" && $action == "create") {
      $front = Zend_Controller_Front::getInstance();
      $getParams =  $front->getRequest()->getParams();
      $enableEntryFees = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestjoinfees.allow.entryfees', 1);
      if(!empty( $getParams['contest_id']) && $enableEntryFees){
        $contest = Engine_Api::_()->getItem('contest',$getParams['contest_id']);
        if($contest && $contest->entry_fees > 0){
          //get payment status of user
          $isPaymentDone = Engine_Api::_()->sescontestjoinfees()->isUserHasPaidOrder();
          if(!$isPaymentDone){
            $participation = Engine_Api::_()->getDbTable('participants', 'sescontest')->hasParticipate($view->viewer()->getIdentity(), $getParams['contest_id']);
            if (isset($participation['can_join'])){
              $request->setModuleName('sescontestjoinfees');
              $request->setControllerName('index');
              $request->setActionName('join-contest');
              $request->setParams(array('contest_id' => $getParams['contest_id']));
            }
          }
        }
      }
    }
  }
}