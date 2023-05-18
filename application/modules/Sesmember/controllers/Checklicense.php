<?php
$domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.licensekey');
$licensekey = @base64_encode($licensekey);

$sesdomainauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.sesdomainauth'); 
$seslkeyauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.seslkeyauth');

if(($domain_name == $sesdomainauth) && ($licensekey == $seslkeyauth)) {
	Zend_Registry::set('sesmember_browsemembers', 1);
	Zend_Registry::set('sesmember_reviews', 1);
	Zend_Registry::set('sesmember_nearestmembers', 1);
	Zend_Registry::set('sesmember_locations', 1);
	Zend_Registry::set('sesmember_topmembers', 1);
	Zend_Registry::set('sesmember_tabbed', 1);
	Zend_Registry::set('sesmember_compliments', 1);
	Zend_Registry::set('sesmember_profilemembers', 1);
	Zend_Registry::set('sesmember_followbutton', 1);
	Zend_Registry::set('sesmember_recentlyview', 1);
} else {
	Zend_Registry::set('sesmember_browsemembers', 0);
	Zend_Registry::set('sesmember_reviews', 0);
	Zend_Registry::set('sesmember_nearestmembers', 0);
	Zend_Registry::set('sesmember_locations', 0);
	Zend_Registry::set('sesmember_topmembers', 0);
	Zend_Registry::set('sesmember_tabbed', 0);
	Zend_Registry::set('sesmember_compliments', 0);
	Zend_Registry::set('sesmember_profilemembers', 0);
	Zend_Registry::set('sesmember_followbutton', 0);
	Zend_Registry::set('sesmember_recentlyview', 0);
}
