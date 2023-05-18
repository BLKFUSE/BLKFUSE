<?php
$domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.licensekey');
$licensekey = @base64_encode($licensekey);

$sesdomainauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.sesdomainauth'); 
$seslkeyauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.seslkeyauth');

if(($domain_name == $sesdomainauth) && ($licensekey == $seslkeyauth)) {
	Zend_Registry::set('sesvideo_adminmenu', 1);
  Zend_Registry::set('sesvideo_create', 1);
  Zend_Registry::set('sesvideo_browsemenu', 1);
  Zend_Registry::set('sesvideo_browse', 1);
  Zend_Registry::set('sesvideo_browsesearch', 1);
  Zend_Registry::set('sesvideo_manage', 1);
  Zend_Registry::set('sesvideo_categories', 1);
  Zend_Registry::set('sesvideo_widget', 1);
} else {
	Zend_Registry::set('sesvideo_adminmenu', 0);
  Zend_Registry::set('sesvideo_create', 0);
  Zend_Registry::set('sesvideo_browsemenu', 0);
  Zend_Registry::set('sesvideo_browse', 0);
  Zend_Registry::set('sesvideo_browsesearch', 0);
  Zend_Registry::set('sesvideo_manage', 0);
  Zend_Registry::set('sesvideo_categories', 0);
  Zend_Registry::set('sesvideo_widget', 0);
}