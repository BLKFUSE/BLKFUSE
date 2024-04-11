<?php
$domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.licensekey');
$licensekey = @base64_encode($licensekey);

$sesdomainauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.sesdomainauth');
$seslkeyauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.seslkeyauth');

if(1) {
  Zend_Registry::set('sesadvancedactivity_adminmenu', 1);
  Zend_Registry::set('sesadvancedactivity_feedwidget', 1);
  Zend_Registry::set('sesadvancedactivity_profilelink', 1);
  Zend_Registry::set('sesadvancedactivity_toptrend', 1);
  Zend_Registry::set('sesadvancedcomment_loadcommntswidget', 1);
  Zend_Registry::set('sesadvancedcomment_eplycomment', 1);
  Zend_Registry::set('sesfeelingactivity_adminmenu', 1);
  Zend_Registry::set('sesfeelingactivity_showfeeling', 1);
  Zend_Registry::set('sesfeedbg_adminmenu', 1);
  Zend_Registry::set('sesfeedbg_showbg', 1);
} else {
  Zend_Registry::set('sesadvancedactivity_adminmenu', 0);
  Zend_Registry::set('sesadvancedactivity_feedwidget', 0);
  Zend_Registry::set('sesadvancedactivity_profilelink', 0);
  Zend_Registry::set('sesadvancedactivity_toptrend', 0);
  Zend_Registry::set('sesadvancedcomment_loadcommntswidget', 0);
  Zend_Registry::set('sesadvancedcomment_eplycomment', 0);
  Zend_Registry::set('sesfeelingactivity_adminmenu', 0);
  Zend_Registry::set('sesfeelingactivity_showfeeling', 0);
  Zend_Registry::set('sesfeedbg_adminmenu', 0);
  Zend_Registry::set('sesfeedbg_showbg', 0);
}
