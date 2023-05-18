<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesuserimport
 * @package    Sesuserimport
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminManageImportsController.php  2018-11-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sesuserimport_AdminManageImportsController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesuserimport_admin_main', array(), 'sesuserimport_admin_main_manageimport');
  }

  public function importAction() {


    $this->_helper->layout->setLayout('admin-simple');

    $this->view->form = $form = new Sesuserimport_Form_Admin_Import();
    $userTable = Engine_Api::_()->getDbTable('users', 'user');

    if ($this->getRequest()->isPost()) {
        $db = $userTable->getAdapter();
        $db->beginTransaction();
        try {
            $csvFile = explode(".", $_FILES['csvfile']['name']);


            if (($csvFile[1] != "csv")) {
                $itemError = Zend_Registry::get('Zend_Translate')->_("Choose only CSV file.");
                $form->addError($itemError);
                return;
            }

            $csv_file = $_FILES['csvfile']['tmp_name']; // specify CSV file path

            $csvfile = fopen($csv_file, 'r');
            $theData = fgets($csvfile);
            $thedata = explode('|',$theData);


            $email_address = $password = $first_name = $last_name = $gender = $birthdate = $counter = 0;

            foreach($thedata as $data) {

                //Direct CSV
                if(trim(strtolower($data)) == '[Email Address]'){
                $email_address = $counter;
                } else if(trim(strtolower($data)) == '[Password]'){
                $password = $counter;
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

                if(isset($csv_array[$password]))
                    $importedData[$i]['password'] = @$csv_array[1]; //$csv_array[$password];

                if(isset($csv_array[$first_name]))
                    $importedData[$i]['first_name'] = @$csv_array[2]; //$csv_array[$first_name];

                if(isset($csv_array[$last_name]))
                    $importedData[$i]['last_name'] = @$csv_array[3]; //$csv_array[$last_name];

                if(isset($csv_array[$gender]))
                    $importedData[$i]['gender'] = @$csv_array[4]; //$csv_array[$gender];

                if(isset($csv_array[$birthdate]))
                    $importedData[$i]['birthdate'] = @$csv_array[5]; //$csv_array[$birthdate];

                $i++;
            }
            fclose($csvfile);

            $values = $form->getValues();

            foreach($importedData as $result) {

                $isEmailExist = Engine_Api::_()->sesuserimport()->isEmailExist($result['email']);
                if(empty($isEmailExist)) {
                    if(empty($isEmailExist) && isset($result['email']) && !empty($result['email'])) {
                        $values = array_merge($values, $result);
                        Engine_Api::_()->sesuserimport()->saveUser($values);
                    }
                }
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }

        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 10,
            'parentRefresh' => 10,
            'messages' => array('You have successfully imported FAQ.')
        ));
    }
  }

  public function downloadAction() {

    $filepath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Sesuserimport' . DIRECTORY_SEPARATOR . "settings" .DIRECTORY_SEPARATOR.'default_template.csv';

    //KILL ZEND'S OB
    while (ob_get_level() > 0) {
      ob_end_clean();
    }

    @chmod($filepath, 0777);
    $default_template = '[Email Address]|[Password]|[First Name]|[Last Name]|[Gender (Male/Female)]|[Birthdate (yyyy-mm-dd)]';
    $fp = fopen(APPLICATION_PATH . '/temporary/default_template.csv', 'w+');
    fwrite($fp, $default_template);
    fclose($fp);

    $filepath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary'. DIRECTORY_SEPARATOR . 'default_template.csv';

    header("Content-Disposition: attachment; filename=" . urlencode(basename($filepath)), true);
    header("Content-Type: application/force-download", true);
    header("Content-Type: application/octet-stream", true);
    header("Content-Transfer-Encoding: Binary", true);
    header("Content-Type: application/download", true);
    header("Content-Description: File Transfer", true);
    header("Content-Length: " . filesize($filepath), true);
    readfile("$filepath");
    exit();
    return;
  }
}
