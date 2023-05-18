<?php
$domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('eandlivestreaming.licensekey');
$licensekey = @base64_encode($licensekey);

$sesdomainauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('eandlivestreaming.sesdomainauth');
$seslkeyauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('eandlivestreaming.seslkeyauth');

if(($domain_name == $sesdomainauth) && ($licensekey == $seslkeyauth)) {
	Zend_Registry::set('eandlivestreaming_adminmenu', 1);
	Zend_Registry::set('eandlivestreaming_browse', 1);
	Zend_Registry::set('elivestreaming_adminmenu', 1);
	Zend_Registry::set('elivestreaming_widgets', 1);
} else {
	Zend_Registry::set('eandlivestreaming_adminmenu', 0);
	Zend_Registry::set('eandlivestreaming_browse', 0);
	Zend_Registry::set('elivestreaming_adminmenu', 0);
	Zend_Registry::set('elivestreaming_widgets', 0);
}