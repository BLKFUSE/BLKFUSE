<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Checklicense.php 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

$domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('eticktokclone.licensekey');
$licensekey = @base64_encode($licensekey);

$sesdomainauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('eticktokclone.sesdomainauth');
$seslkeyauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('eticktokclone.seslkeyauth');

if(($domain_name == $sesdomainauth) && ($licensekey == $seslkeyauth)) {
	Zend_Registry::set('eticktokclone_adminmenu', 1);
	Zend_Registry::set('eticktokclone_browse', 1);
} else {
	Zend_Registry::set('eticktokclone_adminmenu', 0);
	Zend_Registry::set('eticktokclone_browse', 0);
}
