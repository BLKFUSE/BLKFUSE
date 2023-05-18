<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siterecaptcha
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Recaptcha.php 2016-05-18 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_View_Helper_SocialServiceStats extends Zend_View_Helper_Abstract
{

  protected $_stats = array();
  protected $_currentPageURL;

  public function socialServiceStats($url = null)
  {
    $this->_currentPageURL = $url ? $this->view->absoluteUrl($url) : $this->getPageUrl();
    if( empty($this->_stats[$this->_currentPageURL]) ) {
      $socialShareHistories = Engine_Api::_()->getDbTable('socialShareHistories', 'siteshare');
      $this->_stats[$this->_currentPageURL] = $socialShareHistories->getShareCounts(array('pageUrl' => $this->_currentPageURL));
    }
    return $this;
  }

  public function getServiceCount($service)
  {
    return !empty($this->_stats[$this->_currentPageURL]['data'][$service]) ? $this->_stats[$this->_currentPageURL]['data'][$service] : '';
  }

  public function getTotalCount()
  {
    return !empty($this->_stats[$this->_currentPageURL]['total']) ? $this->_stats[$this->_currentPageURL]['total'] : '';
  }

  private function getPageUrl()
  {
    if( !Engine_Api::_()->core()->hasSubject() ) {
      $url = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
    } else {
      $url = $this->view->subject()->getHref();
    }
    return $this->view->serverUrl($url);
  }

}
