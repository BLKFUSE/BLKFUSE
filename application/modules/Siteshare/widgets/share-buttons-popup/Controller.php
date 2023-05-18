<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.tpl 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_Widget_ShareButtonsPopupController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $coreSettings = Engine_Api::_()->getApi('settings', 'core');
    if( !$coreSettings->getSetting('siteshare.share.bookmarks.enabled', 1) ) {
      return $this->setNoRender();
    }
    if( empty($viewer->getIdentity()) && !$coreSettings->getSetting('siteshare.share.public.enabled', 1) ) {
      return $this->setNoRender();
    }

    $defaultParams = array(
      'alignment' => 'center',
      'buttonLabel' => '1',
      'heading' => 'Share This Page',
      'message' => 'If you liked, this page share it with family and friends',
      'numberOfButtons' => 4,
      'columns' => 2,
      'statsCount' => 0,
      'totalStats' => 0,
      'round' => 0,
      'moreButton' => 1,
    );
    $this->view->params = array_merge($defaultParams, $this->_getAllParams());

    $this->view->socialNavigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('siteshare_social_link');
  }

}

?>