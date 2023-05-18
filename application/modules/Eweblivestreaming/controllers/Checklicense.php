<?php
$domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('eweblivestreaming.licensekey');
$licensekey = @base64_encode($licensekey);

$sesdomainauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('eweblivestreaming.sesdomainauth');
$seslkeyauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('eweblivestreaming.seslkeyauth');

if(($domain_name == $sesdomainauth) && ($licensekey == $seslkeyauth)) {
	Zend_Registry::set('eweblivestreaming_adminmenu', 1);
	Zend_Registry::set('eweblivestreaming_user', 1);
	Zend_Registry::set('elivestreaming_adminmenu', 1);
	Zend_Registry::set('elivestreaming_widgets', 1);
} else {
	Zend_Registry::set('eweblivestreaming_adminmenu', 0);
	Zend_Registry::set('eweblivestreaming_user', 0);
	Zend_Registry::set('elivestreaming_adminmenu', 0);
	Zend_Registry::set('elivestreaming_widgets', 0);
}
