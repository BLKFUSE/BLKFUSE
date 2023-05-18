<?php
$domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.licensekey');
$licensekey = @base64_encode($licensekey);

$sesdomainauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.sesdomainauth'); 
$seslkeyauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.seslkeyauth');

if(($domain_name == $sesdomainauth) && ($licensekey == $seslkeyauth)) {
	Zend_Registry::set('sesmusic_adminmenu', 1);
  Zend_Registry::set('sesmusic_create', 1);
  Zend_Registry::set('sesmusic_browsemenu', 1);
  Zend_Registry::set('sesmusic_browse', 1);
  Zend_Registry::set('sesmusic_browsesearch', 1);
  Zend_Registry::set('sesmusic_manage', 1);
  Zend_Registry::set('sesmusic_categories', 1);
  Zend_Registry::set('sesmusic_browseartist', 1);
  Zend_Registry::set('sesmusic_profilealbum', 1);
  Zend_Registry::set('sesmusic_widget', 1);
} else {
	Zend_Registry::set('sesmusic_adminmenu', 0);
  Zend_Registry::set('sesmusic_create', 0);
  Zend_Registry::set('sesmusic_browsemenu', 0);
  Zend_Registry::set('sesmusic_browse', 0);
  Zend_Registry::set('sesmusic_browsesearch', 0);
  Zend_Registry::set('sesmusic_manage', 0);
  Zend_Registry::set('sesmusic_categories', 0);
  Zend_Registry::set('sesmusic_browseartist', 1);
  Zend_Registry::set('sesmusic_profilealbum', 1);
  Zend_Registry::set('sesmusic_widget', 1);
}
