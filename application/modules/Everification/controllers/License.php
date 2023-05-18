<?php
//folder name or directory name.
$module_name = 'everification';

//product title and module title.
$module_title = 'Member Verification via KYC Documents Plugin';

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
  $postdata['licenseKey'] = @base64_encode($_POST['everification_licensekey']);
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
  if (1) {

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('everification.pluginactivated')) {

      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      
      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("everification_admin_main_manage", "everification", "Manage Documents", "", \'{"route":"admin_default","module":"everification","controller":"manage"}\', "sesuserdocverion_admin_main", "", 2),
      ("everification_settings_documents", "everification", "Manage Documents", "", \'{"route":"everification_extended","module":"everification","controller":"settings","action":"manage"}\', "user_settings", "", 999);');
      
      $db->query('DROP TABLE IF EXISTS `engine4_everification_documents`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_everification_documents` (
      `document_id` int(11) unsigned NOT NULL auto_increment,
      `file_id` int(11) unsigned NOT NULL,
      `user_id` int(11) unsigned NOT NULL,
      `storage_path` varchar(128) NOT NULL,
      `verified` tinyint(1) NOT NULL DEFAULT "0",
      `documenttype_id` INT(11) NOT NULL DEFAULT "0",
      `submintoadmin` TINYINT(1) NOT NULL DEFAULT "1",
      `note` TEXT NULL,
      PRIMARY KEY  (`document_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;');
      
      $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
      ("everi_superadmin", "everification", \'{item:$subject} has submitted a document for verified badge and is waiting for approval.\', 0, ""),
      ("everi_verified", "everification", \'Your document has been verified.\', 0, ""),
      ("everi_reject", "everification", \'Your document has been rejected.\', 0, "");');
      
      $db->query('INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
      ("notify_everi_superadmin", "everification", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]"),
      ("notify_everi_verified", "everification", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]"),
      ("notify_everi_reject", "everification", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]");');



      Engine_Api::_()->getApi('settings', 'core')->setSetting('everification.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('everification.licensekey', $_POST['everification_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
    $licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('everification.licensekey');
    $licensekey = @base64_encode($licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('everification.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('everification.seslkeyauth', $licensekey);
    $error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('everification.licensekey', $_POST['everification_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
