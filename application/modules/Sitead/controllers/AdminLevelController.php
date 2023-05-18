<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminLevelController.php 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_AdminLevelController extends Core_Controller_Action_Admin {

    public function indexAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitead_admin_main', array(), 'sitead_admin_level_settings');

        // Get level id
        if (null !== ($id = $this->_getParam('id'))) {
            $level = Engine_Api::_()->getItem('authorization_level', $id);
        } else {
            $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();
        }

        if (!$level instanceof Authorization_Model_Level) {
            throw new Engine_Exception('missing level');
        }

        $id = $level->level_id;
        // Make form
        $this->view->form = $form = new Sitead_Form_Admin_Settings_Level(array(
            'public' => ( in_array($level->type, array('public')) ),
            'moderator' => ( in_array($level->type, array('admin', 'moderator'))),
        ));
        $form->level_id->setValue($id);

        // Populate data
        $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
        $form->populate($permissionsTable->getAllowed('sitead', $id, array_keys($form->getValues())));

        // Check post
        if (!$this->getRequest()->isPost()) {
            return;
        }
        // Check validitiy
        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }
        // Process
        $values = $form->getValues();

        $db = $permissionsTable->getAdapter();
        $db->beginTransaction();

        try {
            // Set permissions
            $permissionsTable->setAllowed('sitead', $id, $values);

            // Commit
            $db->commit();
            $form->addNotice('Your changes have been saved.');
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

}
