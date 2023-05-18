<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Categories.php 2020-11-03  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Tickvideo_Model_DbTable_Categories extends Engine_Db_Table
{
  protected $_rowClass = "Tickvideo_Model_Category";


    public function categories($params = array()) {

        $viewer = Engine_Api::_()->user()->getViewer();

        $table = Engine_Api::_()->getDbtable('categories', 'tickvideo');
        $rName = $table->info('name');

        $select = $this->select()
            ->order(!empty($params['orderby']) ? $params['orderby'] . ' DESC' : $rName . '.category_id DESC');

        if(!empty($params['status'])){
            $select->where('status =?',$params['status']);
        }
        return $select;
    }

    public function getPaginator($params = array()) {


        $paginator = Zend_Paginator::factory($this->categories($params));

        return $paginator;
    }
}