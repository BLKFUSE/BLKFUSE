<?php
$domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.licensekey');
$licensekey = @base64_encode($licensekey);

$sesdomainauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.sesdomainauth');
$seslkeyauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.seslkeyauth');

if(($domain_name == $sesdomainauth) && ($licensekey == $seslkeyauth)) {
  Zend_Registry::set('sesdating_landingpage', 1);
  Zend_Registry::set('sesdating_bannerwidget', 1);
  Zend_Registry::set('sesdating_header', 1);
} else {
	Zend_Registry::set('sesdating_landingpage', 0);
  Zend_Registry::set('sesdating_bannerwidget', 0);
  Zend_Registry::set('sesdating_header', 0);
}
