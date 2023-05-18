<?php
$domain_name = @base64_encode($_SERVER['HTTP_HOST']);
$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads.licensekey');
$licensekey = @base64_encode($licensekey);

$sesdomainauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads.sesdomainauth');
$seslkeyauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads.seslkeyauth');

if(($domain_name == $sesdomainauth) && ($licensekey == $seslkeyauth)) {
	Zend_Registry::set('sescommunityads_demouser', 1);
	Zend_Registry::set('sescommunityads_admin', 1);
	Zend_Registry::set('sescomadbanr_admin', 1);
} else {
	Zend_Registry::set('sescommunityads_demouser', 0);
	Zend_Registry::set('sescommunityads_admin', 0);
	Zend_Registry::set('sescomadbanr_admin', 0);
}
