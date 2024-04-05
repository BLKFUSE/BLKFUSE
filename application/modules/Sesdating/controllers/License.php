<?php
//folder name or directory name.
$module_name = 'sesdating';

//product title and module title.
$module_title = 'Responsive Dating Theme';

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
  $postdata['licenseKey'] = @base64_encode($_POST['sesdating_licensekey']);
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

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.pluginactivated')) {

        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
        ("sesdating_admin_main_menus", "sesdating", "Manage Header", "", \'{"route":"admin_default","icon":"fas fa-heart","module":"sesdating","controller":"manage", "action":"header-settings"}\', "sesdating_admin_main", "", 3),
        ("sesdating_admin_main_menusfooter", "sesdating", "Manage Footer", "", \'{"route":"admin_default","module":"sesdating","controller":"manage", "action":"footer-settings"}\', "sesdating_admin_main", "", 4),
        ("sesdating_admin_main_styling", "sesdating", "Color Schemes", "", \'{"route":"admin_default","module":"sesdating","controller":"settings", "action":"styling"}\', "sesdating_admin_main", "", 5),
        ("sesdating_admin_main_customcss", "sesdating", "Custom CSS", "", \'{"route":"admin_default","module":"sesdating","controller":"custom-theme", "action":"index"}\', "sesdating_admin_main", "", 6),
        ("sesdating_admin_main_managebanners", "sesdating", "Manage Banners", "", \'{"route":"admin_default","module":"sesdating","controller":"manage-banner","action":"index"}\', "sesdating_admin_main", "", 7),
        ("sesdating_admin_main_typography", "sesdating", "Typography", "", \'{"route":"admin_default","module":"sesdating","controller":"settings", "action":"typography"}\', "sesdating_admin_main", "", 50);
        ');

        $db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`, `order`) VALUES
        ("sesdating_quicklinks_footer", "standard", "SNS - Responsive Dating Theme - Footer Quicklinks", 1);');

        $db->query('DROP TABLE IF EXISTS `engine4_sesdating_banners`;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesdating_banners` (
            `banner_id` int(11) unsigned NOT NULL auto_increment,
            `banner_name` VARCHAR(255)  NULL ,
            `creation_date` datetime NOT NULL,
            `modified_date` datetime NOT NULL,
            `enabled` TINYINT(1) NOT NULL DEFAULT "1",
            PRIMARY KEY (`banner_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;'
        );

        $db->query('DROP TABLE IF EXISTS `engine4_sesdating_customthemes`;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesdating_customthemes` (
            `customtheme_id` int(11) unsigned NOT NULL auto_increment,
            `name` VARCHAR(255) NOT NULL,
            `description` text,
            PRIMARY KEY (`customtheme_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=14;
        ');

        $db->query('DROP TABLE IF EXISTS `engine4_sesdating_slides`;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesdating_slides` (
            `slide_id` int(11) unsigned NOT NULL auto_increment,
            `banner_id` int(11) DEFAULT NULL,
            `title` varchar(255) DEFAULT NULL,
            `title_button_color` varchar(255) DEFAULT NULL,
            `description` text,
            `description_button_color` varchar(255) DEFAULT NULL,
            `file_type` varchar(255) DEFAULT NULL,
            `file_id` INT(11) DEFAULT "0",
            `status` ENUM("1","2","3") NOT NULL DEFAULT "1",
            `extra_button_linkopen` TINYINT(1) NOT NULL DEFAULT "0",
            `extra_button` tinyint(1) DEFAULT "0",
            `extra_button_text` varchar(255) DEFAULT NULL,
            `extra_button_link` varchar(255) DEFAULT NULL,
            `order` tinyint(10) NOT NULL DEFAULT "0",
            `creation_date` datetime NOT NULL,
            `modified_date` datetime NOT NULL,
            `enabled` TINYINT(1) NOT NULL DEFAULT "1",
            PRIMARY KEY (`slide_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;
        ');

        $db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`, `order`) VALUES
        ("sesdating_extra_menu", "standard", "SNS - Dating - Extra Header Menu", 999);');

        include_once APPLICATION_PATH . "/application/modules/Sesdating/controllers/defaultsettings.php";

        Engine_Api::_()->getApi('settings', 'core')->setSetting('sesdating.pluginactivated', 1);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sesdating.licensekey', $_POST['sesdating_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
    $licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.licensekey');
    $licensekey = @base64_encode($licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesdating.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesdating.seslkeyauth', $licensekey);
    $error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesdating.sesdomainauth', '');
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesdating.seslkeyauth', '');
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesdating.licensekey', $_POST['sesdating_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
