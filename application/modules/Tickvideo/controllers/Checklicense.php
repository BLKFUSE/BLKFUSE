<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Checklicense.php 2020-11-03  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
$domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('tickvideo.licensekey');
$licensekey = @base64_encode($licensekey);

$sesdomainauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('tickvideo.sesdomainauth');
$seslkeyauth = Engine_Api::_()->getApi('settings', 'core')->getSetting('tickvideo.seslkeyauth');

if(($domain_name == $sesdomainauth) && ($licensekey == $seslkeyauth)) {
    Zend_Registry::set('tickvideo_adminmenu', 1);
    Zend_Registry::set('tickvideo_widgets', 1);
} else {
    Zend_Registry::set('tickvideo_adminmenu', 0);
    Zend_Registry::set('tickvideo_widgets', 0);
}
