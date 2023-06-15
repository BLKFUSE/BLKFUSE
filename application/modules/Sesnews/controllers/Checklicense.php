<?php
$domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.licensekey');
$licensekey = @base64_encode($licensekey);

$sesdomainauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.sesdomainauth');
$seslkeyauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.seslkeyauth');

if(($domain_name == $sesdomainauth) && ($licensekey == $seslkeyauth)) {
  Zend_Registry::set('sesnews_adminmenu', 1);
	Zend_Registry::set('sesnews_browsenews', 1);
	Zend_Registry::set('sesnews_reviews', 1);
	Zend_Registry::set('sesnews_locations', 1);
	Zend_Registry::set('sesnews_topnewsgers', 1);
	Zend_Registry::set('sesnews_tabbed', 1);
	Zend_Registry::set('sesnews_photos', 1);
	Zend_Registry::set('sesnews_profilenews', 1);
	Zend_Registry::set('sesnews_favbutton', 1);
	Zend_Registry::set('sesnews_recentlyview', 1);
	Zend_Registry::set('sesnews_create', 1);
	Zend_Registry::set('sesnews_edit', 1);
	Zend_Registry::set('sesnews_category', 1);
} else {
  Zend_Registry::set('sesnews_adminmenu', 0);
	Zend_Registry::set('sesnews_browsenews', 0);
	Zend_Registry::set('sesnews_reviews', 0);
	Zend_Registry::set('sesnews_locations', 0);
	Zend_Registry::set('sesnews_topnewsgers', 0);
	Zend_Registry::set('sesnews_tabbed', 0);
	Zend_Registry::set('sesnews_photos', 0);
	Zend_Registry::set('sesnews_profilenews', 0);
	Zend_Registry::set('sesnews_favbutton', 0);
	Zend_Registry::set('sesnews_recentlyview', 0);
	Zend_Registry::set('sesnews_create', 0);
	Zend_Registry::set('sesnews_edit', 0);
	Zend_Registry::set('sesnews_category', 0);
}
