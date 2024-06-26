<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Controller.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Core_Widget_AdminPluginStatisticsController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
    // members, friends
    $table = Engine_Api::_()->getItemTable('user');
    $info = $table->select()
      ->from($table, array(
        'COUNT(*) AS count',
        'SUM(member_count) AS friends',
      ))
      ->where('enabled = ?', true)
      ->query()
      ->fetch();
    $this->view->member_count = $info['count'];
    $this->view->friend_count = $info['friends'];

    $friendship_types = Engine_Api::_()->getDbtable('membership', 'user');
    if( $friendship_types->isReciprocal() ) {
      $this->view->friend_count = round($info['friends'] / 2);
    }

    // posts
    $table = Engine_Api::_()->getDbTable('actions', 'activity');
    $this->view->post_count = $table->select()
      ->from($table, array(
        'COUNT(*) AS count',
      ))
      ->query()
      ->fetchColumn();

    // comments
    $comment_count = 0;
    
    $table = Engine_Api::_()->getDbTable('comments', 'activity');
    $comment_count += (int) $table->select()
      ->from($table, array(
        'COUNT(*) AS count',
      ))
      ->query()
      ->fetchColumn();

    $table = Engine_Api::_()->getDbTable('comments', 'core');
    $comment_count += (int) $table->select()
      ->from($table, array(
        'COUNT(*) AS count',
      ))
      ->query()
      ->fetchColumn();

    $this->view->comment_count = $comment_count;

    // plugin hook
    $this->view->hooked_stats = array();
    $events     = Engine_Hooks_Dispatcher::getInstance()->callEvent('onStatistics');
    $events_res = $events->getResponses();
    if (is_array($events_res))
      $this->view->hooked_stats = $events_res;
  }
}
