<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Install.php  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Installer extends Engine_Package_Installer_Module {

    function onInstall() {
        $db = $this->getDb();
        $sitead_time_set = time();
        $db->query("INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES
          ('sitead.base.time', $sitead_time_set ),
          ('sitead.check.var', 0 ), 
          ('sitead.time.var', 3456000 )");
        $db->query("INSERT IGNORE INTO `engine4_core_tasks` (`title`, `module`, `plugin`, `timeout`, `processes`, `semaphore`, `started_last`, `started_count`, `completed_last`, `completed_count`, `failure_last`, `failure_count`, `success_last`, `success_count`) VALUES ('Ad Statistics Maintenance', 'sitead', 'Sitead_Plugin_Task_StatsMaintenance', '86400', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0')");
        // Create widgetized page
        $this->_addAdBoardPage();
        $this->_addPackagePage();
        $this->_addCreateAdPage();
        $this->_addEditAdPage();
        $this->_addManageCampaignPage();
        $this->_addManageAdsPage();
        $this->_addViewAdPage();
        $this->_addReportAdPage();
        parent::onInstall();
        //ADD COLUMN IF FAQ PLUGIN IS INSTALLED
        $check_sitefaq = $db->select()
        ->from('engine4_core_modules', array('enabled'))
        ->where('name = ?', 'sitefaq')
        ->limit(1)
        ->query()
        ->fetchColumn();

        $table_exist = $db->query("SHOW TABLES LIKE 'engine4_sitead_faqs'")->fetch();
        if (!empty($table_exist) && !empty($check_sitefaq)) {
            $column_exist = $db->query("SHOW COLUMNS FROM engine4_sitead_faqs LIKE 'import'")->fetch();
            if (empty($column_exist)) {
                $db->query("ALTER TABLE `engine4_sitead_faqs` ADD `import` TINYINT( 1 ) NOT NULL DEFAULT '0'");
            }
        }
        
        // Add SiteReview Plugin In Sitead Module table
        $isSitereviewEnabled = $db->select()
                                ->from('engine4_core_modules', array('enabled'))
                                ->where('name = ?', 'sitereview')
                                ->limit(1)
                                ->query()
                                ->fetchColumn();
          if( !empty($isSitereviewEnabled) ) {
                $getListingType = $db->query("SELECT * FROM `engine4_sitereview_listingtypes` LIMIT 0 , 30")->fetchAll();
                if( !empty($getListingType) ) {
                  foreach($getListingType as $listingType) {
                    $temTableName = "sitereview_listing_" . $listingType["listingtype_id"];

                    $isAdsExist = $db->query("SELECT * FROM `engine4_sitead_modules` WHERE `table_name` LIKE '" . $temTableName . "' LIMIT 1")->fetch();
                    if( empty($isAdsExist) ) {
                      $db->insert('engine4_sitead_modules', array(
                         'module_name' => "sitereview",
                         'module_title' => $listingType["title_singular"],
                         'table_name' => $temTableName,
                         'title_field' => 'title',
                         'body_field' => 'body',
                         'owner_field' => 'owner_id',
                         'displayable' => '7',
                         'is_delete' => '1',
                      ));  
                    }
                 }
              }
          }

        $page_id = $db->select()
        ->from('engine4_core_pages', array('page_id'))
        ->where('name = ?', 'user_index_home')
        ->limit(1)
        ->query()
        ->fetchColumn();
        if (!empty($page_id)) {
           $fetchMainContentId = $db->select()
           ->from('engine4_core_content', array('content_id'))
           ->where('page_id =?', $page_id)
           ->where('type = ?', 'container')
           ->where('name =?', 'main')
           ->limit(1)
           ->query()
           ->fetchColumn();
           if (!empty($fetchMainContentId)) {
            $mainContentId = $fetchMainContentId;
            $fetchRightContentId = $db->select()
            ->from('engine4_core_content', array('content_id'))
            ->where('page_id =?', $page_id)
            ->where('type = ?', 'container')
            ->where('name = ?', 'right')
            ->where('parent_content_id = ?', $mainContentId)
            ->limit(1)
            ->query()
            ->fetchColumn();
            if (!empty($fetchRightContentId)) {
              $rightContentId = $fetchRightContentId;
              $selectWidgetId = $db->select()
              ->from('engine4_core_content', array('content_id'))
              ->where('page_id =?', $page_id)
              ->where('type = ?', 'widget')
              ->where('name = ?', 'sitead.create-ad')
              ->where('parent_content_id = ?', $rightContentId)
              ->limit(1)
              ->query()
              ->fetchColumn();
              if (empty($selectWidgetId)) {
                $db->insert('engine4_core_content', array(
                    'type' => 'widget',
                    'name' => 'sitead.create-ad',
                    'page_id' => $page_id,
                    'order' => 4,
                    'parent_content_id' => $rightContentId,
                    'params' => '{"title":"Want more Customers?","titleCount":"true"}',
                ));
            }
        }              
    }         

}
$this->_addDefaultPackage();
}

protected function _addDefaultPackage() {

    $db = $this->getDb();
    $modules = $db->select()
    ->from('engine4_core_modules', array('name'))
    ->where('enabled = ?', true)
    ->query()
    ->fetchAll();

    $packages = $db->select()
    ->from('engine4_sitead_package', array('title'))
    ->query()
    ->fetchAll();
    if(empty($packages)) {
        $enabledModuleNames = array();
        foreach ($modules as $module) {
            $enabledModuleNames[] = $module['name'];
        }

        $freePackageModule = array('album', 'classified', 'blog', 'event', 'forum', 'group', 'music', 'poll', 'video', 'list', 'document', 'sitepage', 'recipe');

        $queary_info = array_intersect($enabledModuleNames, $freePackageModule);
        $urloption = '';
        $addCategories = 'boost,content,page,website';
        if (!empty($queary_info)) {
            foreach ($queary_info as $module) {
                $urloption .= ',' . $module;
            }
            if( !(in_array("sitepage", $queary_info)) ) {
                $addCategories = 'boost,content,website';
            }
        }

        if(empty($urloption)) {
            $addCategories = 'boost,website';
        }
        
        $db->insert('engine4_sitead_package', array(
            'title' => 'Free Ad Package',
            'desc' => 'This is a free ad package. An advertiser does not need to pay for creating an ad of this package.',
            'price' => 0,
            'sponsored' => 0,
            'featured' => 0,
            'add_categories' => $addCategories,
            'urloption' => $urloption,
            'enabled' => 1,
            'network' => 1,
            'public' => 1,
            'carousel' => 1,
            'image' => 1,
            'video' => 1,
            'price_model' => 'Pay/click',
            'model_detail' => -1,
            'allow_ad' => 0,
            'creation_date' => date('Y-m-d H:i:s'),
            'renew' => 0,
            'renew_before' => 0,
            'auto_aprove' => 1,
            'type' => 'default',
        ));
    }
}

protected function _addAdBoardPage() {
    $db = $this->getDb();

        // profile page
    $page_id = $db->select()
    ->from('engine4_core_pages', 'page_id')
    ->where('name = ?', 'sitead_display_adboard')
    ->limit(1)
    ->query()
    ->fetchColumn();

        // insert if it doesn't exist yet
    if (!$page_id) {
            // Insert page
        $db->insert('engine4_core_pages', array(
            'name' => 'sitead_display_adboard',
            'displayname' => 'Community Ad - Ad Board',
            'title' => 'Ad Board',
            'description' => 'This page displays advertisements.',
            'custom' => 0,
        ));
        $page_id = $db->lastInsertId();

            // Insert top
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'top',
            'page_id' => $page_id,
            'order' => 1,
        ));
        $top_id = $db->lastInsertId();

            // Insert main
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'main',
            'page_id' => $page_id,
            'order' => 2,
        ));
        $main_id = $db->lastInsertId();

            // Insert top-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $top_id,
        ));
        $top_middle_id = $db->lastInsertId();

            // Insert main-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 2,
        ));
        $main_middle_id = $db->lastInsertId();

            // Insert menu
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitead.user-navigation',
            'page_id' => $page_id,
            'parent_content_id' => $top_middle_id,
            'order' => 1,
        ));

            // Insert content
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'core.content',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
    }
}

protected function _addManageCampaignPage() {
    $db = $this->getDb();

        // profile page
    $page_id = $db->select()
    ->from('engine4_core_pages', 'page_id')
    ->where('name = ?', 'sitead_statistics_index')
    ->limit(1)
    ->query()
    ->fetchColumn();

        // insert if it doesn't exist yet
    if (!$page_id) {
            // Insert page
        $db->insert('engine4_core_pages', array(
            'name' => 'sitead_statistics_index',
            'displayname' => 'Community Ad - Manage Campaign',
            'title' => 'My Campaigns',
            'description' => 'This page is use to manage Campaigns.',
            'custom' => 0,
        ));
        $page_id = $db->lastInsertId();

            // Insert top
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'top',
            'page_id' => $page_id,
            'order' => 1,
        ));
        $top_id = $db->lastInsertId();

            // Insert main
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'main',
            'page_id' => $page_id,
            'order' => 2,
        ));
        $main_id = $db->lastInsertId();

            // Insert top-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $top_id,
        ));
        $top_middle_id = $db->lastInsertId();

            // Insert main-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 2,
        ));
        $main_middle_id = $db->lastInsertId();

            // Insert menu
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitead.user-navigation',
            'page_id' => $page_id,
            'parent_content_id' => $top_middle_id,
            'order' => 1,
        ));

            // Insert content
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'core.content',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
    }
}

protected function _addManageAdsPage() {
    $db = $this->getDb();

        // profile page
    $page_id = $db->select()
    ->from('engine4_core_pages', 'page_id')
    ->where('name = ?', 'sitead_statistics_browse-ad')
    ->limit(1)
    ->query()
    ->fetchColumn();

        // insert if it doesn't exist yet
    if (!$page_id) {
            // Insert page
        $db->insert('engine4_core_pages', array(
            'name' => 'sitead_statistics_browse-ad',
            'displayname' => 'Community Ad - Manage Advertisements',
            'title' => 'My Campaigns',
            'description' => 'This page is use to manage Advertisements.',
            'custom' => 0,
        ));
        $page_id = $db->lastInsertId();

            // Insert top
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'top',
            'page_id' => $page_id,
            'order' => 1,
        ));
        $top_id = $db->lastInsertId();

            // Insert main
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'main',
            'page_id' => $page_id,
            'order' => 2,
        ));
        $main_id = $db->lastInsertId();

            // Insert top-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $top_id,
        ));
        $top_middle_id = $db->lastInsertId();

            // Insert main-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 2,
        ));
        $main_middle_id = $db->lastInsertId();

            // Insert menu
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitead.user-navigation',
            'page_id' => $page_id,
            'parent_content_id' => $top_middle_id,
            'order' => 1,
        ));

            // Insert content
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'core.content',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
    }
}

protected function _addViewAdPage() {
    $db = $this->getDb();

        // profile page
    $page_id = $db->select()
    ->from('engine4_core_pages', 'page_id')
    ->where('name = ?', 'sitead_statistics_view-ad')
    ->limit(1)
    ->query()
    ->fetchColumn();

        // insert if it doesn't exist yet
    if (!$page_id) {
            // Insert page
        $db->insert('engine4_core_pages', array(
            'name' => 'sitead_statistics_view-ad',
            'displayname' => 'Community Ad - View Ad',
            'title' => 'My Campaigns',
            'description' => 'This page is use to View Advertisements.',
            'custom' => 0,
        ));
        $page_id = $db->lastInsertId();

            // Insert top
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'top',
            'page_id' => $page_id,
            'order' => 1,
        ));
        $top_id = $db->lastInsertId();

            // Insert main
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'main',
            'page_id' => $page_id,
            'order' => 2,
        ));
        $main_id = $db->lastInsertId();

            // Insert top-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $top_id,
        ));
        $top_middle_id = $db->lastInsertId();

            // Insert main-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 2,
        ));
        $main_middle_id = $db->lastInsertId();

            // Insert menu
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitead.user-navigation',
            'page_id' => $page_id,
            'parent_content_id' => $top_middle_id,
            'order' => 1,
        ));

            // Insert content
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'core.content',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
    }
}

protected function _addPackagePage() {
    $db = $this->getDb();

        // profile page
    $page_id = $db->select()
    ->from('engine4_core_pages', 'page_id')
    ->where('name = ?', 'sitead_index_index')
    ->limit(1)
    ->query()
    ->fetchColumn();

        // insert if it doesn't exist yet
    if (!$page_id) {
            // Insert page
        $db->insert('engine4_core_pages', array(
            'name' => 'sitead_index_index',
            'displayname' => 'Community Ad - Select Package',
            'title' => 'Create an Ad',
            'description' => 'This page is use to Select package for ads.',
            'custom' => 0,
        ));
        $page_id = $db->lastInsertId();

            // Insert top
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'top',
            'page_id' => $page_id,
            'order' => 1,
        ));
        $top_id = $db->lastInsertId();

            // Insert main
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'main',
            'page_id' => $page_id,
            'order' => 2,
        ));
        $main_id = $db->lastInsertId();

            // Insert top-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $top_id,
        ));
        $top_middle_id = $db->lastInsertId();

            // Insert main-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 2,
        ));
        $main_middle_id = $db->lastInsertId();

            // Insert menu
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitead.user-navigation',
            'page_id' => $page_id,
            'parent_content_id' => $top_middle_id,
            'order' => 1,
        ));

            // Insert content
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'core.content',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
    }
}

protected function _addCreateAdPage() {
    $db = $this->getDb();

        // profile page
    $page_id = $db->select()
    ->from('engine4_core_pages', 'page_id')
    ->where('name = ?', 'sitead_index_create')
    ->limit(1)
    ->query()
    ->fetchColumn();

        // insert if it doesn't exist yet
    if (!$page_id) {
            // Insert page
        $db->insert('engine4_core_pages', array(
            'name' => 'sitead_index_create',
            'displayname' => 'Community Ad - Create an Ad',
            'title' => 'Create an Ad',
            'description' => 'This page is use to create Ad.',
            'custom' => 0,
        ));
        $page_id = $db->lastInsertId();

            // Insert top
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'top',
            'page_id' => $page_id,
            'order' => 1,
        ));
        $top_id = $db->lastInsertId();

            // Insert main
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'main',
            'page_id' => $page_id,
            'order' => 2,
        ));
        $main_id = $db->lastInsertId();

            // Insert top-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $top_id,
        ));
        $top_middle_id = $db->lastInsertId();

            // Insert main-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 2,
        ));
        $main_middle_id = $db->lastInsertId();

            // Insert menu
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitead.user-navigation',
            'page_id' => $page_id,
            'parent_content_id' => $top_middle_id,
            'order' => 1,
        ));

            // Insert content
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'core.content',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
    }
}

protected function _addEditAdPage() {
    $db = $this->getDb();

        // profile page
    $page_id = $db->select()
    ->from('engine4_core_pages', 'page_id')
    ->where('name = ?', 'sitead_index_edit')
    ->limit(1)
    ->query()
    ->fetchColumn();

        // insert if it doesn't exist yet
    if (!$page_id) {
            // Insert page
        $db->insert('engine4_core_pages', array(
            'name' => 'sitead_index_edit',
            'displayname' => 'Community Ad - Edit an Ad',
            'title' => 'Edit an Ad',
            'description' => 'This page is use to Edit Ad.',
            'custom' => 0,
        ));
        $page_id = $db->lastInsertId();

            // Insert top
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'top',
            'page_id' => $page_id,
            'order' => 1,
        ));
        $top_id = $db->lastInsertId();

            // Insert main
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'main',
            'page_id' => $page_id,
            'order' => 2,
        ));
        $main_id = $db->lastInsertId();

            // Insert top-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $top_id,
        ));
        $top_middle_id = $db->lastInsertId();

            // Insert main-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 2,
        ));
        $main_middle_id = $db->lastInsertId();

            // Insert menu
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitead.user-navigation',
            'page_id' => $page_id,
            'parent_content_id' => $top_middle_id,
            'order' => 1,
        ));

            // Insert content
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'core.content',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
    }
}

protected function _addReportAdPage() {
    $db = $this->getDb();

        // profile page
    $page_id = $db->select()
    ->from('engine4_core_pages', 'page_id')
    ->where('name = ?', 'sitead_statistics_export-report')
    ->limit(1)
    ->query()
    ->fetchColumn();

        // insert if it doesn't exist yet
    if (!$page_id) {
            // Insert page
        $db->insert('engine4_core_pages', array(
            'name' => 'sitead_statistics_export-report',
            'displayname' => 'Community Ad - Export Report',
            'title' => 'Report an Ad',
            'description' => 'This page is use to export ad reports.',
            'custom' => 0,
        ));
        $page_id = $db->lastInsertId();

            // Insert top
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'top',
            'page_id' => $page_id,
            'order' => 1,
        ));
        $top_id = $db->lastInsertId();

            // Insert main
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'main',
            'page_id' => $page_id,
            'order' => 2,
        ));
        $main_id = $db->lastInsertId();

            // Insert top-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $top_id,
        ));
        $top_middle_id = $db->lastInsertId();

            // Insert main-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 2,
        ));
        $main_middle_id = $db->lastInsertId();

            // Insert menu
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sitead.user-navigation',
            'page_id' => $page_id,
            'parent_content_id' => $top_middle_id,
            'order' => 1,
        ));

            // Insert content
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'core.content',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
    }
}

}
