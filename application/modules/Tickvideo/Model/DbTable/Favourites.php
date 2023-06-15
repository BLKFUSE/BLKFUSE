<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Favourites.php 2020-11-03  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Tickvideo_Model_DbTable_Favourites extends Engine_Db_Table
{
    protected $_rowClass = "Tickvideo_Model_Favourite";


    public function isFavourite($params = array())
    {

        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $select = $this->select()
            ->where('resource_type = ?', $params['resource_type'])
            ->where('resource_id = ?', $params['resource_id'])
            ->where('user_id = ?', $viewer_id)
            ->query()
            ->fetchColumn();
        return $select;
    }

    public function getItemfav($resource_type, $itemId)
    {
        $tableFav = Engine_Api::_()->getDbtable('favourites', 'tickvideo');
        $tableMainFav = $tableFav->info('name');
        $select = $tableFav->select()->from($tableMainFav)->where('resource_type =?', $resource_type)->where('user_id =?', Engine_Api::_()->user()->getViewer()->getIdentity())->where('resource_id =?', $itemId);
        return $tableFav->fetchRow($select);
    }
}