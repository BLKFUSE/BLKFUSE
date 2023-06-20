<?php
$domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.licensekey');
$licensekey = @base64_encode($licensekey);

$sesdomainauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.sesdomainauth'); 
$seslkeyauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.seslkeyauth');

if(($domain_name == $sesdomainauth) && ($licensekey == $seslkeyauth)) {
	Zend_Registry::set('sesalbum_adminmenu', 1);
  Zend_Registry::set('sesalbum_create', 1);
  Zend_Registry::set('sesalbum_browsemenu', 1);
  Zend_Registry::set('sesalbum_browse', 1);
  Zend_Registry::set('sesalbum_browsesearch', 1);
  Zend_Registry::set('sesalbum_manage', 1);
  Zend_Registry::set('sesalbum_categories', 1);
  Zend_Registry::set('sesalbum_widget', 1);
} else {
	Zend_Registry::set('sesalbum_adminmenu', 0);
  Zend_Registry::set('sesalbum_create', 0);
  Zend_Registry::set('sesalbum_browsemenu', 0);
  Zend_Registry::set('sesalbum_browse', 0);
  Zend_Registry::set('sesalbum_browsesearch', 0);
  Zend_Registry::set('sesalbum_manage', 0);
  Zend_Registry::set('sesalbum_categories', 0);
  Zend_Registry::set('sesalbum_widget', 1);
}
