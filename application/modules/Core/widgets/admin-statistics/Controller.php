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
class Core_Widget_AdminStatisticsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    // Statistics
    $statistics = array();

    // views
    $statistics['core.views'] = array(
        'label' => 'Page Views',
        'today' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('core.views', 'day'),
        'total' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('core.views'),
    );

    // signups
    $statistics['user.creations'] = array(
        'label' => 'Members',
        'today' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('user.creations', 'day'),
        'total' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('user.creations'),
    );

    // logins
    $statistics['user.logins'] = array(
        'label' => 'Sign-ins',
        'today' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('user.logins', 'day'),
        'total' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('user.logins'),
    );

    // messages
    $statistics['messages.creations'] = array(
        'label' => 'Messages',
        'today' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('messages.creations', 'day'),
        'total' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('messages.creations'),
    );

    // friendships
    // @todo this only works properly for two-way, verified friendships for now
    $statistics['user.friendships'] = array(
        'label' => 'Friendships',
        'today' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('user.friendships', 'day'),
        'total' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('user.friendships'),
    );

    // comments
    // @todo this doesn't include activity feed, users, group, or events for now
    $statistics['core.comments'] = array(
        'label' => 'Comments',
        'today' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('core.comments', 'day'),
        'total' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('core.comments'),
    );

    // reports
    $statistics['core.reports'] = array(
        'label' => 'Abuse Reports',
        'today' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('core.reports', 'day'),
        'total' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('core.reports'),
    );

    // announcements
    $statistics['announcement.creations'] = array(
        'label' => 'Announcements',
        'today' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('announcement.creations', 'day'),
        'total' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('announcement.creations'),
    );

    // emails
    $statistics['core.emails'] = array(
        'label' => 'Emails Sent',
        'today' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('core.emails', 'day'),
        'total' => Engine_Api::_()->getDbtable('statistics', 'core')->getTotal('core.emails'),
    );



    // Hooks
    $event = Engine_Hooks_Dispatcher::getInstance()->callEvent('onAdminStatistics');
    $statistics += (array) $event->getResponses();




    // Online users
    $onlineTable = Engine_Api::_()->getDbtable('online', 'user');
    $onlineUserCount = $onlineTable->select()
        ->from($onlineTable->info('name'), new Zend_Db_Expr('COUNT(DISTINCT user_id)'))
        ->where('user_id > ?', 0)
        ->where('active > ?', new Zend_Db_Expr('DATE_SUB(NOW(),INTERVAL 20 MINUTE)'))
        ->query()
        ->fetchColumn(0);

    $statistics['users.online'] = array(
        'label' => 'Online Members',
        'today' => null,
        'total' => $onlineUserCount,
    );
    // Assign
    $this->view->statistics = $statistics;
  }
}
