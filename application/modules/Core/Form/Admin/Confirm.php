<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Install
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Confirm.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Install
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */

class Core_Form_Admin_Confirm extends Engine_Form
{
  protected $_title = 'Confirm';

  protected $_description = 'Are you sure you want to peform this action?';

  protected $_submitLabel = 'Confirm';

  protected $_cancelHref = 'javascript:history.go(-1);';

  protected $_useToken = false;

  public function setSubmitLabel($submitLabel)
  {
    $this->_submitLabel = $submitLabel;
    return $this;
  }

  public function getSubmitLabel()
  {
    return $this->_submitLabel;
  }

  public function setCancelHref($cancelHref)
  {
    $this->_cancelHref = $cancelHref;
    return $this;
  }

  public function getCancelHref()
  {
    return $this->_cancelHref;
  }

  public function setUseToken($flag = true)
  {
    $this->_useToken = (bool) $flag;
    return $this;
  }

  public function getUseToken()
  {
    return (bool) $this->_useToken;
  }
  
  public function init()
  {
    $this->setMethod('POST')
      ->setAction($_SERVER['REQUEST_URI']);
    
    $this->addElement('Button', 'execute', array(
      'type' => 'submit',
      'label' => $this->getSubmitLabel(),
      'decorators' => array(
        'ViewHelper',
      ),
    ));
    
    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'link' => true,
      'prependText' => ' or ',
      'href' => '',
      'onClick'=> 'javascript:parent.Smoothbox.close();',
      'decorators' => array(
        'ViewHelper'
      )
    ));

    if( $this->getUseToken() ) {
      $this->addElement('Hash', 'token', array(

      ));
    }

    $this->addDisplayGroup(array('execute', 'cancel'), 'buttons');
  }
}
