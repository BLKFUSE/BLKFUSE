<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_Widget_MenuFooterController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->storage = Engine_Api::_()->storage();
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('core_footer');
    $this->view->quickLinksMenu = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesdating_quicklinks_footer');

    //Languages
    $this->view->languageNameList = Engine_Api::_()->getApi('languages', 'core')->getLanguages();
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->view->footerlogo =  $settings->getSetting('sesdating.footerlogo', '');
    $sesdating_header = Zend_Registry::isRegistered('sesdating_header') ? Zend_Registry::get('sesdating_header') : null;
    if(empty($sesdating_header))
      return $this->setNoRender();
    $this->view->aboutusdescription =  $settings->getSetting('sesdating.aboutusdescription', 'Lorem Ipsum Is Simply Dummy Text Of The Printing And Typesetting Industry.');
    $this->view->quicklinksenable =  $settings->getSetting('sesdating.quicklinksenable', '1');
    $this->view->quicklinksheading =  $settings->getSetting('sesdating.quicklinksheading', 'QUICK LINKS');
    $this->view->helpenable =  $settings->getSetting('sesdating.helpenable', '1');
    $this->view->helpheading =  $settings->getSetting('sesdating.helpheading', 'HELP');
    $this->view->socialenable =  $settings->getSetting('sesdating.socialenable', '1');
    $this->view->socialheading =  $settings->getSetting('sesdating.socialheading', 'SOCIAL');
    $this->view->facebookurl =  $settings->getSetting('sesdating.facebookurl', 'http://www.facebook.com/');
    $this->view->googleplusurl =  $settings->getSetting('sesdating.googleplusurl', 'http://plus.google.com/');
    $this->view->twitterurl =  $settings->getSetting('sesdating.twitterurl', 'https://www.twitter.com/');
    $this->view->pinteresturl =  $settings->getSetting('sesdating.pinteresturl', 'https://www.pinterest.com/');
  }
}
