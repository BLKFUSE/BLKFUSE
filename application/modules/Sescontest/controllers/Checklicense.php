<?php
$domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.licensekey');
$licensekey = @base64_encode($licensekey);

$sesdomainauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.sesdomainauth'); 
$seslkeyauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.seslkeyauth');

if(($domain_name == $sesdomainauth) && ($licensekey == $seslkeyauth)) {
	Zend_Registry::set('sescontest_adminmenu', 1);
  Zend_Registry::set('sescontest_create', 1);
  Zend_Registry::set('sescontest_browsemenu', 1);
  Zend_Registry::set('sescontest_browse', 1);
  Zend_Registry::set('sescontest_browsesearch', 1);
  Zend_Registry::set('sescontest_manage', 1);
  Zend_Registry::set('sescontest_categories', 1);
  Zend_Registry::set('sescontest_widget', 1);
} else {
	Zend_Registry::set('sescontest_adminmenu', 0);
  Zend_Registry::set('sescontest_create', 0);
  Zend_Registry::set('sescontest_browsemenu', 0);
  Zend_Registry::set('sescontest_browse', 0);
  Zend_Registry::set('sescontest_browsesearch', 0);
  Zend_Registry::set('sescontest_manage', 0);
  Zend_Registry::set('sescontest_categories', 0);
  Zend_Registry::set('sescontest_widget', 0);
}
