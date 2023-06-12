<?php
$domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('eiosstories.licensekey');
$licensekey = @base64_encode($licensekey);

$sesdomainauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('eiosstories.sesdomainauth');
$seslkeyauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('eiosstories.seslkeyauth');

if(($domain_name == $sesdomainauth) && ($licensekey == $seslkeyauth)) {
	Zend_Registry::set('eiosstories_adminmenu', 1);
	Zend_Registry::set('eiosstories_browse', 1);
} else {
	Zend_Registry::set('eiosstories_adminmenu', 0);
	Zend_Registry::set('eiosstories_browse', 0);
}