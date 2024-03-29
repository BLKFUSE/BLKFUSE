<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Controller.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class User_Widget_HomeLinksController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        // Don't render this if not logged in
        $viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer->getIdentity()) {
            return $this->setNoRender();
        }

        $this->view->navigation = Engine_Api::_()
            ->getApi('menus', 'core')
            ->getNavigation('user_home');
        
        // Should we show user photo and name?
        $this->view->showPhoto = $this->_getParam('showPhoto', 1);
        $this->view->showMenuIcon = $this->_getParam('showMenuIcon', 1);
    }

    public function getCacheKey()
    {
        $viewer = Engine_Api::_()->user()->getViewer();
        $translate = Zend_Registry::get('Zend_Translate');
        return $viewer->getIdentity() . $translate->getLocale();
    }
}
