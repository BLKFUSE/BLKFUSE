<?php

/**
 * SocialEngine - Search Widget Controller
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2012 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Controller.php 9747 2012-07-26 02:08:08Z john $
 * @author     Matthew
 */
class Core_Widget_SearchMiniController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
    if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('serenity')) {
      $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
      $headerloggedinoptions = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('serenity.headerloggedinoptions', 'a:4:{i:0;s:6:"search";i:1;s:8:"miniMenu";i:2;s:8:"mainMenu";i:3;s:4:"logo";}'));
      $headernonloggedinoptions = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('serenity.headernonloggedinoptions', 'a:4:{i:0;s:6:"search";i:1;s:8:"miniMenu";i:2;s:8:"mainMenu";i:3;s:4:"logo";}'));
      if(!empty($viewer_id))
        empty($headerloggedinoptions) ? $this->setNoRender() : ((!engine_in_array('search', $headerloggedinoptions)) ? $this->setNoRender() : '');
      else 
        empty($headernonloggedinoptions) ? $this->setNoRender() : ((!engine_in_array('search', $headernonloggedinoptions)) ? $this->setNoRender() : '');
    }
    
    if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('elpis')) {
      $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
      $headerloggedinoptions = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('elpis.headerloggedinoptions', 'a:4:{i:0;s:6:"search";i:1;s:8:"miniMenu";i:2;s:8:"mainMenu";i:3;s:4:"logo";}'));
      $headernonloggedinoptions = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('elpis.headernonloggedinoptions', 'a:4:{i:0;s:6:"search";i:1;s:8:"miniMenu";i:2;s:8:"mainMenu";i:3;s:4:"logo";}'));
      if(!empty($viewer_id))
        empty($headerloggedinoptions) ? $this->setNoRender() : ((!engine_in_array('search', $headerloggedinoptions)) ? $this->setNoRender() : '');
      else 
        empty($headernonloggedinoptions) ? $this->setNoRender() : ((!engine_in_array('search', $headernonloggedinoptions)) ? $this->setNoRender() : '');
    }
    
    if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('prism')) {
      $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
      $headerloggedinoptions = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('prism.headerloggedinoptions', 'a:4:{i:0;s:6:"search";i:1;s:8:"miniMenu";i:2;s:8:"mainMenu";i:3;s:4:"logo";}'));
      $headernonloggedinoptions = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('prism.headernonloggedinoptions', 'a:4:{i:0;s:6:"search";i:1;s:8:"miniMenu";i:2;s:8:"mainMenu";i:3;s:4:"logo";}'));
      if(!empty($viewer_id))
        (empty($headerloggedinoptions) ? $this->setNoRender() : ((!engine_in_array('search', $headerloggedinoptions)) ? $this->setNoRender() : ''));
      else 
        (empty($headernonloggedinoptions) ? $this->setNoRender() : ((!engine_in_array('search', $headernonloggedinoptions)) ? $this->setNoRender() : ''));
    }

    $requireCheck = Engine_Api::_()->getApi('settings', 'core')->core_general_search;
    if( !$requireCheck && !Zend_Controller_Action_HelperBroker::getStaticHelper('RequireUser')->checkRequire() ) {
      $this->setNoRender();
      return;
    }
  }
}
