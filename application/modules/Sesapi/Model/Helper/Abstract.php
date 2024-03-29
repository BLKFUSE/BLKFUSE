<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesapi
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Abstract.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
abstract class Sesapi_Model_Helper_Abstract
{
  /**
   * Currently set action
   * 
   * @var Sesapi_Model_Action
   */
  protected $_action;

  /**
   * Set the current action
   * 
   * @param Sesapi_Model_Action $action
   * @return Sesapi_Model_Action
   */
  public function setAction(Sesapi_Model_Action $action)
  {
    $this->_action = $action;
    return $this;
  }

  /**
   * Get the currently set action
   * @return Sesapi_Model_Action
   */
  public function getAction()
  {
    return $this->_action;
  }

  /**
   * Accessor
   * 
   * @return string
   */
  public function direct($value, $noTranslate = false)
  {
    return '';
  }

  protected function _getItem($item, $throw = true)
  {
    // Accept string in form <type>_<id>
    if( is_string($item) && strpos($item, '_') !== false )
    {
      $item = explode('_', $item);
      $id = array_pop($item);
      $type = implode('_', $item);
      $item = array($type, $id);
    }

    // Accept array in form array(<type>, <id>)
    if( is_array($item) && engine_count($item) === 2 && is_string($item[0]) && is_numeric($item[1]) )
    {
      $item = Engine_Api::_()->getItem($item[0], $item[1]);
    }

    // Check to make sure we have an item
    if( !($item instanceof Core_Model_Item_Abstract) )
    {
      if( $throw ) {
        throw new Sesapi_Model_Exception('Not an item');
      } else {
        return false;
      }
    }

    return $item;
  }
}
