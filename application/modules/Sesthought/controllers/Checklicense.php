<?php
$domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.licensekey');
$licensekey = @base64_encode($licensekey);

$sesdomainauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.sesdomainauth'); 
$seslkeyauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.seslkeyauth');

if(($domain_name == $sesdomainauth) && ($licensekey == $seslkeyauth)) {
	Zend_Registry::set('sesthought_adminmenu', 1);
  Zend_Registry::set('sesthought_thoughtcreate', 1);
  Zend_Registry::set('sesthought_browsemenu', 1);
  Zend_Registry::set('sesthought_browsethought', 1);
  Zend_Registry::set('sesthought_browsesearch', 1);
  Zend_Registry::set('sesthought_managethought', 1);
  Zend_Registry::set('sesthought_qcategories', 1);
} else {
	Zend_Registry::set('sesthought_adminmenu', 0);
  Zend_Registry::set('sesthought_thoughtcreate', 0);
  Zend_Registry::set('sesthought_browsemenu', 0);
  Zend_Registry::set('sesthought_browsethought', 0);
  Zend_Registry::set('sesthought_browsesearch', 0);
  Zend_Registry::set('sesthought_managethought', 0);
  Zend_Registry::set('sesthought_qcategories', 0);
}