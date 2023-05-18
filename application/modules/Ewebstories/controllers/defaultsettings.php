<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Ewebstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: defaultsettings.php 2020-03-20 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

$db = Zend_Db_Table_Abstract::getDefaultAdapter();
//Member Home Page
$page_id = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'user_index_home')
        ->limit(1)
        ->query()
        ->fetchColumn();
if($page_id) {

  $topContainerId = $db->select()
    ->from('engine4_core_content', 'content_id')
    ->where('page_id = ?', $page_id)
    ->where('type = ?', 'container')
    ->where('name = ?', 'main')
    ->limit(1)
    ->query()
    ->fetchColumn();

  $middleContainerId = $db->select()
    ->from('engine4_core_content', 'content_id')
    ->where('page_id = ?', $page_id)
    ->where('type = ?', 'container')
    ->where('name = ?', 'middle')
    ->where('parent_content_id = ?', $topContainerId)
    ->limit(1)
    ->query()
    ->fetchColumn();

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'ewebstories.all-stories',
    'page_id' => $page_id,
    'parent_content_id' => $middleContainerId,
    'order' => 0,
  ));
}
