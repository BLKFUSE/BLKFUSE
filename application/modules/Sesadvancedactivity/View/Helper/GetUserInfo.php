<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: GetUserInfo.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedactivity_View_Helper_GetUserInfo extends Zend_View_Helper_Abstract
{
  public function getUserInfo($user)
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $userInfo = array();
    if(!($user instanceof Core_Model_Item_Abstract)){
      return json_encode($userInfo,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_NUMERIC_CHECK);
    } 
    $userInfo = array(
      'type'  => 'user',
      'id'    => $user->getIdentity(),
      'name'  => $user->getTitle(),
      'value' => $user->getTitle(),
      'avatar' => htmlspecialchars(str_replace('"',"'",$this->view->itemPhoto($user, 'thumb.icon')),ENT_QUOTES),
    );
    return json_encode($userInfo,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_NUMERIC_CHECK);
  } 
}