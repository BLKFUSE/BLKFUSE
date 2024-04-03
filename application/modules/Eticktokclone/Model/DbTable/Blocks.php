<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Blocks.php 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eticktokclone_Model_DbTable_Blocks extends Engine_Db_Table
{

    public function anyOneBlocked($params = array()) {

        $viewer = Engine_Api::_()->user()->getViewer();

        $rName = $this->info('name');

        $select = $this->select()
                        ->where("user_id = ".$viewer->getIdentity().' || user_id = '.$params['user_id'])
                        ->Where("blocked_user_id = ".$viewer->getIdentity().' || blocked_user_id = '.$params['user_id']);

        $row = $this->fetchRow($select);
        // echo $select;die;
        return $row;
    }

    public function isBlocked($params = array()) {

        $viewer = Engine_Api::_()->user()->getViewer();

        $rName = $this->info('name');

        $select = $this->select()
                        ->where("user_id =?",$viewer->getIdentity())
                        ->where("blocked_user_id =?",$params["user_id"]);

        $row = $this->fetchRow($select);
        if(!empty($params["remove"])){
            if($row){
                $row->delete();
                return "delete";
            }
        }
        return $row;
    }
}