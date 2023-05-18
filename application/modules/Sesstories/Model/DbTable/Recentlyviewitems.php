<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Recentlyviewitems.php 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesstories_Model_DbTable_Recentlyviewitems extends Engine_Db_Table {

    protected $_name = 'sesstories_recentlyviewitems';
    protected $_rowClass = 'Sesstories_Model_Recentlyviewitem';

    public function isAlreadyView($params = array()) {

        return $this->select()
                        ->from($this->info('name'), array('recentlyviewed_id'))
                        ->where('owner_id = ?', $params['owner_id'])
                        ->where('resource_id = ?', $params['resource_id'])
                        ->query()
                        ->fetchColumn();
    }
}
