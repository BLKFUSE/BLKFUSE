<?php
$domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('eioslivestreaming.licensekey');
$licensekey = @base64_encode($licensekey);

$sesdomainauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('eioslivestreaming.sesdomainauth');
$seslkeyauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('eioslivestreaming.seslkeyauth');

if(($domain_name == $sesdomainauth) && ($licensekey == $seslkeyauth)) {
	Zend_Registry::set('eioslivestreaming_adminmenu', 1);
	Zend_Registry::set('eioslivestreaming_browse', 1);
  Zend_Registry::set('elivestreaming_adminmenu', 1);
	Zend_Registry::set('elivestreaming_widgets', 1);
} else {
	Zend_Registry::set('eioslivestreaming_adminmenu', 0);
	Zend_Registry::set('eioslivestreaming_browse', 0);
	Zend_Registry::set('elivestreaming_adminmenu', 0);
	Zend_Registry::set('elivestreaming_widgets', 0);
}