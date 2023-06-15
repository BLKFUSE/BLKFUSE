<?php
/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesbasic
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Publish.php 2016-07-23 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesbasic_Plugin_Task_Publish extends Core_Plugin_Task_Abstract {

  public function execute() {

    //Blog plugin
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesblog') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesblog.pluginactivated')) {
      Engine_Api::_()->sesblog()->checkBlogStatus();
    }
    
    //Recipe plugin
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesrecipe') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesrecipe.pluginactivated')) {
      Engine_Api::_()->sesrecipe()->checkRecipeStatus();
    }
    
    //Listing plugin
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('seslisting') && Engine_Api::_()->getApi('settings', 'core')->getSetting('seslisting.pluginactivated')) {
      Engine_Api::_()->seslisting()->checkListingStatus();
    }
    
    //Product plugin
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesproduct') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesproduct.pluginactivated')) {
      Engine_Api::_()->sesproduct()->checkProductStatus();
    }
    
    //Article plugin
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesarticle') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesarticle.pluginactivated')) {
      Engine_Api::_()->sesarticle()->checkArticleStatus();
    }
    
    //Job plugin
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesjob') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesjob.pluginactivated')) {
      Engine_Api::_()->sesjob()->checkJobStatus();
      
      //Job Expired
      $expiration_days = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesjob.expirationtime', '30');
      $table = Engine_Api::_()->getDbTable('jobs', 'sesjob');
      $select = $table->select()
                  ->where('draft =?', 0)
                  ->where('is_approved =?', 1)
                  ->where('expired =?', 0)
                  ->where('is_publish =?', 0)
                  ->where('creation_date <= now() - INTERVAL '.$expiration_days.' DAY');
      $jobs = $table->fetchAll($select);
      if(engine_count($jobs) > 0) {
        foreach($jobs as $job) {
          Engine_Api::_()->getItemTable('sesjob_job')->update(array('expired' => 1), array('job_id = ?' => $job->job_id));
        }
      }
    }
    
    //Activity plugin for cleanup
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.pluginactivated')) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->query('DELETE from engine4_activity_stream WHERE action_id NOT IN (SELECT action_id FROM engine4_activity_actions);');
      
      $db->query('DELETE from engine4_activity_notifications WHERE object_id NOT IN (SELECT comment_id FROM engine4_activity_comments) AND object_type = "activity_comment";');
      
      $db->query('DELETE from engine4_activity_notifications WHERE object_id NOT IN (SELECT comment_id FROM engine4_core_comments) AND object_type = "core_comment";');
      
      $db->query('DELETE from engine4_activity_comments WHERE poster_id NOT IN (SELECT user_id FROM engine4_users) AND poster_type = "user";');

      $db->query('DELETE from engine4_activity_likes WHERE poster_id NOT IN (SELECT user_id FROM engine4_users) AND poster_type = "user";');

      $db->query('DELETE from engine4_core_likes WHERE poster_id NOT IN (SELECT user_id FROM engine4_users) AND poster_type = "user";');

      $db->query('DELETE from engine4_core_comments WHERE poster_id NOT IN (SELECT user_id FROM engine4_users) AND poster_type = "user";');
    }
    
    //discussion plugin remove new lable
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesdiscussion') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdiscussion.pluginactivated')) {
    
      $db = Engine_Db_Table::getDefaultAdapter();
      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdiscussion.automaticallymarkasnew', 0)) {
        $days = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdiscussion.newdays', 2);
        $discussionTable = Engine_Api::_()->getDbTable('discussions', 'sesdiscussion');
        $discussionTableName = $discussionTable->info('name');
        $minustime =  strtotime(date('Y-m-d H:i:s', strtotime('-'.$days.' day')));
        $select = $discussionTable->select()
                  ->from($discussionTableName)
                  ->where('new =?', 1)
                  ->where("creation_date <= FROM_UNIXTIME(?)", $minustime);
        $paginator = Zend_Paginator::factory($select);
        foreach($paginator as $discussion) {
          $discussion->new = 0;
          $discussion->save();
        }
      }
    }
    
    //News plugin
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesnews') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.pluginactivated')) {
      Engine_Api::_()->sesnews()->checkNewsStatus();
    }

  }
}
