<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesuserimport
 * @package    Sesuserimport
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminSettigsController.php  2018-11-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesuserimport_AdminSettingsController extends Core_Controller_Action_Admin {

    public function indexAction() {

        $db = Engine_Db_Table::getDefaultAdapter();

        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesuserimport_admin_main', array(), 'sesuserimport_admin_main_settings');

        $this->view->form = $form = new Sesuserimport_Form_Admin_Settings_Global();

        if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
            $values = $form->getValues();
            include_once APPLICATION_PATH . "/application/modules/Sesuserimport/controllers/License.php";
            if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesuserimport.pluginactivated')) {
                foreach ($values as $key => $value) {
                if($value != '')
                Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
                }
                $form->addNotice('Your changes have been saved.');
                if($error)
                $this->_helper->redirector->gotoRoute(array());
            }
        }
    }

    public function addummyMembersAction() {

        $db = Engine_Db_Table::getDefaultAdapter();

        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesuserimport_admin_main', array(), 'sesuserimport_admin_main_adddummymembers');

        $this->view->form = $form = new Sesuserimport_Form_Admin_Settings_AddDummyMembers();

        $userTable = Engine_Api::_()->getDbTable('users', 'user');

        if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
            $values = $form->getValues();

            if($values['sesuserimport_adsusertypes'] == 1) {
                $filepath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Sesuserimport' . DIRECTORY_SEPARATOR . "settings" .DIRECTORY_SEPARATOR.'dummy_male.csv';
            } else if($values['sesuserimport_adsusertypes'] == 2) {
                $filepath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Sesuserimport' . DIRECTORY_SEPARATOR . "settings" .DIRECTORY_SEPARATOR.'dummy_female.csv';
            } else if($values['sesuserimport_adsusertypes'] == 3) {
                $filepath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Sesuserimport' . DIRECTORY_SEPARATOR . "settings" .DIRECTORY_SEPARATOR.'alldummymembers.csv';
            }

            $db = $userTable->getAdapter();
            $db->beginTransaction();
            try {

                $csvFile = explode(".", $filepath);

//                 if (($csvFile[1] != "csv")) {
//                     $itemError = Zend_Registry::get('Zend_Translate')->_("Choose only CSV file.");
//                     $form->addError($itemError);
//                     return;
//                 }

                $csv_file = $filepath; // specify CSV file path

                $csvfile = fopen($csv_file, 'r');
                $theData = fgets($csvfile);
                $thedata = explode('|',$theData);

                $email_address = $displayname = $username =  $password = $first_name = $last_name = $gender = $birthdate = $counter = 0;

                foreach($thedata as $data) {

                    //Direct CSV
                    if(trim(strtolower($data)) == '[Email Address]'){
                        $email_address = $counter;
                    } else if(trim(strtolower($data)) == '[Username]'){
                        $username = $counter;
                    } else if(trim(strtolower($data)) == '[Display Name]'){
                        $displayname = $counter;
                    } else if(trim(strtolower($data)) == '[First Name]'){
                        $first_name = $counter;
                    } else if(trim(strtolower($data)) == '[Last Name]'){
                        $last_name = $counter;
                    } else if(trim(strtolower($data)) == '[Gender (Male/Female)]'){
                        $gender = $counter;
                    } else if(trim(strtolower($data)) == '[Birthdate (yyyy-mm-dd)]'){
                        $birthdate = $counter;
                    }
                    $counter++;
                }

                $i = 0;
                $importedData = array();
                while (!feof($csvfile))
                {
                    $csv_data[] = fgets($csvfile, 1024);
                    $csv_array = explode("|", $csv_data[$i]);

                    if(!engine_count($csv_array))
                        continue;

                    if(isset($csv_array[$email_address]))
                        $importedData[$i]['email'] = @$csv_array[0]; //$csv_array[$email_address];

                    if(isset($csv_array[$username]))
                        $importedData[$i]['username'] = @$csv_array[1]; //$csv_array[$password];

                    if(isset($csv_array[$displayname]))
                        $importedData[$i]['displayname'] = @$csv_array[2]; //$csv_array[$password];

                    if(isset($csv_array[$first_name]))
                        $importedData[$i]['first_name'] = @$csv_array[3]; //$csv_array[$first_name];

                    if(isset($csv_array[$last_name]))
                        $importedData[$i]['last_name'] = @$csv_array[4]; //$csv_array[$last_name];

                    if(isset($csv_array[$gender]))
                        $importedData[$i]['gender'] = @$csv_array[5]; //$csv_array[$gender];

                    if(isset($csv_array[$birthdate]))
                        $importedData[$i]['birthdate'] = @$csv_array[6]; //$csv_array[$birthdate];

                    $i++;
                }
                fclose($csvfile);

                foreach($importedData as $result) {

                    $isEmailExist = Engine_Api::_()->sesuserimport()->isEmailExist($result['email']);
                    if(!empty($isEmailExist)) continue;

                    if(empty($isEmailExist) && isset($result['email']) && !empty($result['email'])) {

                        $email = explode('@', $result['email']);
                        $values = array_merge($values, $result);
                        $values['email'] = $email[0].'@'.$values['sesuserimport_domainname'];
                        $isEmailExist = Engine_Api::_()->sesuserimport()->isEmailExist($values['email']);
                        if(empty($isEmailExist))  {
                            $usernameExist = Engine_Api::_()->sesuserimport()->isUserExist($values['username']);
                            if(!empty($usernameExist)) {
                                $values['username'] = $userName.rand();
                            }
                            Engine_Api::_()->sesuserimport()->saveUser($values);
                        }
                    }
                }
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }

            $this->_helper->redirector->gotoRoute(array());

        }
    }

    public function supportAction() {
        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesuserimport_admin_main', array(), 'sesuserimport_admin_main_support');
    }
}
