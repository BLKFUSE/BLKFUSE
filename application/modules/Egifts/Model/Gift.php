<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Gift.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Egifts_Model_Gift extends Core_Model_Item_Abstract
{
  // Properties

 // protected $_parent_type = 'user';

  //protected $_owner_type = 'user';

 // protected $_parent_is_owner = true;

  /**
   * Gets a proxy object for the tags handler
   *
   * @return Engine_ProxyObject
   **/
  public function getHref($params = array()) {
    $params = array_merge(array(
      'route' => 'egifts_profile',
      'reset' => true,
      'gift_id'=> $this->gift_id,
    ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
      ->assemble($params, $route, $reset);
  }
	public function getPhotoUrl($type = null)
	{
    if(!empty($this->icon_id))
    {
       $file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->icon_id, null);
       if(!empty($file))
        return $file->map();
    }
    return "";
	}
	public function getTitle()
	{
	  return $this->title;
	}

}
