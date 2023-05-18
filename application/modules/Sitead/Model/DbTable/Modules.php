<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Modules.php 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Model_DbTable_Modules extends Engine_Db_Table {

    protected $_name = 'sitead_modules';
    protected $_rowClass = 'Sitead_Model_Module';

    // Function: Return the 'Module Name'array, which are available in the table.
    public function getModuleName() {
        // Queary which return the modules name which are already set by admin.
        $tableName = $this->info('name');
        $selectModule = $this->select()->from($tableName, array('module_name'));
        $fetchModule = $selectModule->query()->fetchAll();
        if (!empty($fetchModule)) {
            foreach ($fetchModule as $moduleName) {
                $moduleArray[] = $moduleName['module_name'];
            }
        }
        // Array: Which modules are not allow for advertisment.
        $not_alow_modules = array(
            'facebookse', 'facebooksefeed', 'facebooksepage', 'grouppoll', 'birthday', 'poke', 'sitelike', 'dbbackup', 'suggestion', 'mcard', 'groupdocument', 'siteslideshow', 'mapprofiletypelevel', 'peopleyoumayknow', 'userconnection', 'sitead', 'seaocore', 'feedback', 'advancedactivity', 'advancedactivitypost', 'advancedslideshow', 'birthdayemail', 'document', 'list', 'recipe', 'siteadvsearch', 'sitealbum', 'sitecontentcoverphoto', 'sitecoupon', 'siteestore', 'siteevent', 'siteeventadmincontact', 'siteeventdocument', 'siteeventemail', 'siteeventinvite', 'siteeventrepeat', 'sitefaq', 'sitemailtemplates', 'sitemenu', 'sitemobile', 'sitemobileapp', 'sitereview', 'sitereviewlistingtype', 'sitereviewpaidlisting', 'sitestaticpage', 'sitetagcheckin', 'sitetheme', 'siteusercoverphoto', 'siteverify', 'sitevideoview', 'sitepage', 'sitepageadmincontact', 'sitepagealbum', 'sitepagebadge', 'sitepagediscussion', 'sitepagedocument', 'sitepageform', 'sitepageevent', 'sitepageintegration', 'sitepageinvite', 'sitepagelikebox', 'sitepagegeolocation', 'sitepagemember', 'sitepagemusic', 'sitepagenote', 'sitepageoffer', 'sitepagepoll', 'sitepagereview', 'sitepageurl', 'sitepagevideo', 'sitepagewishlist', 'sitepagetwitter', 'sitestore', 'sitestoreadmincontact', 'sitestorealbum', 'sitestoredocument', 'sitestoreform', 'sitestoreintegration', 'sitestoreinvite', 'sitestorelikebox', 'sitestoreoffer', 'sitestoreproduct', 'sitestorereservation', 'sitestorereview', 'sitestoreurl', 'sitestorevideo', 'sitegroup', 'sitegroupadmincontact', 'sitegroupalbum', 'sitegroupbadge', 'sitegroupdiscussion', 'sitegroupdocument', 'sitegroupevent', 'sitegroupform', 'sitegroupintegration', 'sitegroupinvite', 'sitegrouplikebox', 'sitegroupmember', 'sitegroupmusic', 'sitegroupnote', 'sitegroupoffer', 'sitegrouppoll', 'sitegroupreview', 'sitegroupurl', 'sitegroupvideo', 'sitebusiness', 'sitebusinessadmincontact', 'sitebusinessalbum', 'sitebusinessbadge', 'sitebusinessdiscussion', 'sitebusinessform', 'sitebusinessdocument', 'sitebusinessevent', 'sitebusinessintegration', 'sitebusinessinvite', 'sitebusinesslikebox', 'sitebusinessmember', 'sitebusinessmusic', 'sitebusinessnote', 'sitebusinessoffer', 'sitebusinesspoll', 'sitebusinessreview', 'sitebusinessurl', 'sitebusinessvideo', 'siteadsponsored', 'eventdocument', 'nestedcomment', 'event', 'sitemobile', 'sitemobileandroidapp', 'sitemobileiosapp', 'sitemember', 'sitetheme', 'siteluminous', 'sitevideointegration'
        );
        $moduleArray = array_merge($moduleArray, $not_alow_modules);
        return $moduleArray;
    }

    public function ismoduleads_enabled($module_name) {
        $tableName = $this->info('name');
        $selectModule = $this->select()->from($tableName, array('module_name'))->where('module_name = ?', $module_name);
        $fetchModule = $this->fetchRow($selectModule);
        if (!empty($fetchModule->module_name)) {
            return true;
        } else {
            return false;
        }
    }

    // Function: Return the 'Table Name' of any modules.
    public function getModuleInfo($contentType) {
        if (empty($contentType)) {
            return;
        }
        $tableName = $this->info('name');
        if (strstr($contentType, "sitereview_")) {
            $isModuleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereview');
            if (empty($isModuleEnabled))
                return;

            $explodeReview = explode("_", $contentType);
            $selectModule = $this->select()->from($tableName)->where('module_id =?', $explodeReview[1]);
        }else {
            $selectModule = $this->select()->from($tableName)->where('module_name =?', $contentType)->orwhere('table_name =?', $contentType);
        }

        $fetchModule = $selectModule->query()->fetchAll();

        if (!empty($fetchModule) && !empty($fetchModule[0]) && !empty($fetchModule[0]['module_name'])) {
            $isModuleEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled($fetchModule[0]['module_name']);
            if (empty($isModuleEnabled))
                return;
        }

        if (!empty($fetchModule)) {
            if (strstr($contentType, "sitereview_")) {
                $fetchModule[0]['table_name'] = "sitereview_listing";
                return $fetchModule[0];
            } else if (strstr($contentType, "sitereview")) {
                return $fetchModule;
            } else {
                return $fetchModule[0];
            }
//      if( strstr($contentType, "sitereview") ) { return $fetchModule; }
//        else { return $fetchModule[0]; }
        } else {
            return;
        }
    }

    // Return the row acording to the "Table name". 
    public function getModuleType($contentType) {
        if (empty($contentType))
            return;

        $tableName = $this->info('name');


        if (strstr($contentType, "sitereview_")) {
            $explodeReview = explode("_", $contentType);
            $getTemModuleId = end($explodeReview);
            if (is_numeric($getTemModuleId)) {
                $selectModule = $this->select()->from($tableName)->where('module_id =?', $explodeReview[1]);
                $fetchModule = $selectModule->query()->fetchAll();
            }
        } else {
            $selectModule = $this->select()->from($tableName)->where('table_name =?', $contentType);
            $fetchModule = $selectModule->query()->fetchAll();
        }

        if (!empty($fetchModule)) {
            if (strstr($contentType, "sitereview_")) {
                $fetchModule[0]['table_name'] = "sitereview_listing";
                return $fetchModule[0];
            } else {
                return $fetchModule[0];
            }
        } else {
            return;
        }
    }
    
}
