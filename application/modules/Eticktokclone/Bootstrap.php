<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Bootstrap.php 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eticktokclone_Bootstrap extends Engine_Application_Bootstrap_Abstract {

  public function __construct($application) {
    parent::__construct($application);
  
//     //Overwrite User Model forPhoto URL function
//     if(Engine_Api::_()->getApi('settings', 'core')->getSetting('eticktokclone.pluginactivated') && !class_exists('User_Model_DbTable_Users', false)) {
//       include_once APPLICATION_PATH .'/application/modules/User/Model/DbTable/Users.php';
//       Engine_Api::_()->getDbTable('users', 'user')->setRowClass('Eticktokclone_Model_User');
//     }
  }
    
  protected function _initFrontController() {
    include APPLICATION_PATH . '/application/modules/Eticktokclone/controllers/Checklicense.php';
  }
}
