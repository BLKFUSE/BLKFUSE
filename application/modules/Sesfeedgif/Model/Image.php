<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesfeedgif
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Image.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesfeedgif_Model_Image extends Core_Model_Item_Abstract
{
  protected $_searchTriggers = false;
  
  /**
   * Gets a proxy object for the tags handler
   *
   * @return Engine_ProxyObject
   **/
  public function tags()
  {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('tags', 'core'));
  }
}