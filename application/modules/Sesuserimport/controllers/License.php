<?php
//folder name or directory name.
$module_name = 'sesuserimport';

//product title and module title.
$module_title = 'Administration Tool: Bulk Importing, Creating New / Dummy Users Plugin';

if (!$this->getRequest()->isPost()) {
  return;
}

if (!$form->isValid($this->getRequest()->getPost())) {
  return;
}

if ($this->getRequest()->isPost()) {

    $postdata = array();
    //domain name
    $postdata['domain_name'] = $_SERVER['HTTP_HOST'];
    //license key
    $postdata['licenseKey'] = @base64_encode($_POST['sesuserimport_licensekey']);
    $postdata['module_title'] = @base64_encode($module_title);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://socialnetworking.solutions/licensecheck.php");
    curl_setopt($ch, CURLOPT_POST, 1);

    // in real life you should use something like:
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));

    // receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);

    $error = 0;
    if (curl_error($ch)) {
        $error = 1;
    }
    curl_close($ch);

  //here we can set some variable for checking in plugin files.
  if ($server_output == "OK" && $error != 1) {

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesuserimport.pluginactivated')) {

        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
        ("sesuserimport_admin_main_addsinglemember", "sesuserimport", "Add Single Member", "", \'{"route":"admin_default","module":"sesuserimport","controller":"manage"}\', "sesuserimport_admin_main", "", 2),
        ("sesuserimport_admin_main_adddummymembers", "sesuserimport", "Add Dummy Members", "", \'{"route":"admin_default","module":"sesuserimport","controller":"settings", "action":"addummy-members"}\', "sesuserimport_admin_main", "", 3),
        ("sesuserimport_admin_main_manageimport", "sesuserimport", "Import Members", "", \'{"route":"admin_default","module":"sesuserimport","controller":"manage-imports", "action":"index"}\', "sesuserimport_admin_main", "", 4);');

        include_once APPLICATION_PATH . "/application/modules/Sesuserimport/controllers/defaultsettings.php";

        Engine_Api::_()->getApi('settings', 'core')->setSetting('sesuserimport.pluginactivated', 1);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sesuserimport.licensekey', $_POST['sesuserimport_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
    $licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesuserimport.licensekey');
    $licensekey = @base64_encode($licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesuserimport.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesuserimport.seslkeyauth', $licensekey);
    $error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesuserimport.licensekey', $_POST['sesuserimport_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
