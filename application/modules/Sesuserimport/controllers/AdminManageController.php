<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesuserimport
 * @package    Sesuserimport
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminManageController.php  2018-11-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesuserimport_AdminManageController extends Core_Controller_Action_Admin {

    public function indexAction() {

        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesuserimport_admin_main', array(), 'sesuserimport_admin_main_addsinglemember');

        $this->view->defaultProfileId = $defaultProfileId = 1;

        $this->view->form = $form = new Sesuserimport_Form_Admin_AddSingleMember(array('defaultProfileId' => $defaultProfileId));

        $userTable = Engine_Api::_()->getDbTable('users', 'user');

        // If not post or form not valid, return
        if( !$this->getRequest()->isPost() ) {
            return;
        }

        if( ! $form->isValid( $this->getRequest()->getPost() ) ) {
            $form->populate( $form->getValues() );
            return;
        }

        $values = $form->getValues();
        $db = $userTable->getAdapter();
        $db->beginTransaction();
        try {
            $isEmailExist = Engine_Api::_()->sesuserimport()->isEmailExist($values['email']);
            if(empty($isEmailExist)) {
                if(isset($values['username']) && !empty($values['username'])) {
                  $usernameExist = Engine_Api::_()->sesuserimport()->isUserExist($values['username']);
                  if(!empty($usernameExist)) {
                      $values['username'] = $userName.rand();
                  }
                }
                Engine_Api::_()->sesuserimport()->saveUser($values, $form->photo, $form->cover, $form);
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        $this->_redirect('admin/sesuserimport/manage');
    }

}
