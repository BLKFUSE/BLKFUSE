<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesapi
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Item.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesapi_Model_Helper_Item extends Sesapi_Model_Helper_Abstract
{
  /**
   * Generates text representing an item
   * 
   * @param mixed $item The item or item guid
   * @param string $text (OPTIONAL)
   * @param string $href (OPTIONAL)
   * @return string
   */
  public function direct($item, $text = null, $href = null, $hideVerifiedIcon = true)
  {
    $item = $this->_getItem($item, false);

    // Check to make sure we have an item
    if( !($item instanceof Core_Model_Item_Abstract) )
    {
      return false;
    }

    
    
    if( !isset($text) ) {
      if($item->getType() == 'user') {
        $text = $item->getTitle();
        if($hideVerifiedIcon && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('everification')) {
          $verifieddocuments = $verifieddocuments = Engine_Api::_()->getDbTable('documents', 'everification')->getAllUserDocuments(array('user_id' => $item->getIdentity(), 'verified' => '1', 'fetchAll' => '1'));
          if(count($verifieddocuments) > 0) {
            $text .= '&nbsp;<img src="https://blkfuse.com/application/modules/Sesbasic/externals/images/verify.png?v=1" />';
          }
        }
      } else {
        $text = $item->getTitle();
      }
    }


    // translate text
    $translate = Zend_Registry::get('Zend_Translate');
    if( !($item instanceof User_Model_User) && $translate instanceof Zend_Translate ) {
      $text = $translate->translate($text);
      // if the value is pluralized, only use the singular
      if (is_array($text))
        $text = $text[0];
    }

    if( !isset($href) )
    {
      $href = $item->getHref();
    }
    return (array('title'=>$text,'id'=>$item->getIdentity(),'type'=>$item->getType(),'module'=>strtolower($item->getModuleName()),'href'=>Engine_Api::_()->sesapi()->getBaseUrl(false).$href));    
  }
}
