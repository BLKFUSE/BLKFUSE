<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminHelpController.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_AdminHelpController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $db = Engine_Db_Table::getDefaultAdapter();
		
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_help');

    //$this->view->form = $form = new Sescommunityads_Form_Admin_Global();

    
  }
}