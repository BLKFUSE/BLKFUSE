<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Item.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedactivity_Model_Helper_Item extends Sesadvancedactivity_Model_Helper_Abstract
{
  /**
   * Generates text representing an item
   * 
   * @param mixed $item The item or item guid
   * @param string $text (OPTIONAL)
   * @param string $href (OPTIONAL)
   * @return string
   */
  public function direct($item, $text = null, $href = null,$separator = ' &rarr; ')
  {
    $item = $this->_getItem($item, false);

    // Check to make sure we have an item
    if( !($item instanceof Core_Model_Item_Abstract) )
    {
      return false;
    }
    
    $translate = Zend_Registry::get('Zend_Translate');
    if( !isset($text) ) {
      if($item->getType() == 'user') {
        $text = $item->getTitle();
        if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('everification')) {
          $verifieddocuments = $verifieddocuments = Engine_Api::_()->getDbTable('documents', 'everification')->getAllUserDocuments(array('user_id' => $item->getIdentity(), 'verified' => '1', 'fetchAll' => '1'));
          if(count($verifieddocuments) > 0) {
            $text .= '<i class="sesbasic_verify_icon" title="'.$translate->translate('Verified').'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M15.67 7.06l-1.08-1.34c-.17-.22-.28-.48-.31-.77l-.19-1.7a1.51 1.51 0 0 0-1.33-1.33l-1.7-.19c-.3-.03-.56-.16-.78-.33L8.94.32c-.55-.44-1.33-.44-1.88 0L5.72 1.4c-.22.17-.48.28-.77.31l-1.7.19c-.7.08-1.25.63-1.33 1.33l-.19 1.7c-.03.3-.16.56-.33.78L.32 7.05c-.44.55-.44 1.33 0 1.88l1.08 1.34c.17.22.28.48.31.77l.19 1.7c.08.7.63 1.25 1.33 1.33l1.7.19c.3.03.56.16.78.33l1.34 1.08c.55.44 1.33.44 1.88 0l1.34-1.08c.22-.17.48-.28.77-.31l1.7-.19c.7-.08 1.25-.63 1.33-1.33l.19-1.7c.03-.3.16-.56.33-.78l1.08-1.34c.44-.55.44-1.33 0-1.88zM6.5 12L3 8.5 4.5 7l2 2 5-5L13 5.55 6.5 12z"/></svg></i>';
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
    
    return '<a '
      . 'class="feed_item_username ses_tooltip" '
      . ( $href ? 'href="'.$href.'"' : '' )
      . ($href ? 'data-src="'.$item->getGuid().'"' : '')
      . '>'
      . $text
      . '</a>';
  }
}
