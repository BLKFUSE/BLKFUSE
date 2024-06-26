<?php

 /**
 * socialnetworking.solutions
 *

 * @category   Application_Modules
 * @package    Sesapi

 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: IndexController.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Announcement_IndexController extends Sesapi_Controller_Action_Standard
{
  public function init() 
  {

  }
   public function indexAction()
  {
  	// Only Display Items that match member's Network, Member Level, or  Profile Type
        $user = Engine_Api::_()->user()->getViewer();

        if (isset($user['user_id'])) {
            $user_id = $user['user_id'];
        } else {
            $user_id = null;
        }

        // Get Member Level ID
        if (isset($user['level_id'])) {
            $user_member_level_id = $user['level_id'];
        } else {
            // Get Public Member Level ID
            $auth_level_table = Engine_Api::_()->getDbtable('levels', 'authorization');
            $auth_level_select = $auth_level_table->select('level_id')->where('flag = ?', 'public');
            $auth_level_id_query = $auth_level_table->fetchRow($auth_level_select);
            $user_member_level_id = $auth_level_id_query['level_id'];
        }
        // Get Network IDs
        if ($user_id != null) {
            $network_table = Engine_Api::_()->getDbtable('membership', 'network');
            $network_select = $network_table->select('resource_id')->where('user_id = ?', $user_id);
            $network_id_query = $network_table->fetchAll($network_select);
            $network_id_query_count = engine_count($network_id_query);
            $network_id_array = array();
            for ($i = 0; $i < $network_id_query_count; $i++) {
                $network_id_array[$i] = $network_id_query[$i]['resource_id'];
            }

            // Get Profile Type
            $profile_table = Engine_Api::_()->fields()->getTable('user', 'values');
            $profile_select = $profile_table->select('value')->where('field_id = 1 AND item_id = ?', $user_id);
            $profile_type_query = $profile_table->fetchRow($profile_select);
            $profile_type_id = $profile_type_query['value'];
        } else {
            $network_id_array = 0;
            $profile_type_id = 0;
        }

       
        // Get Announcements
        // Get paginator
        $table = Engine_Api::_()->getDbtable('announcements', 'announcement');
        $announcement_select = $table->select()->order('creation_date DESC');
        $announcement_query = $table->fetchAll($announcement_select);
       
        // Keep only Relevent Announcements
        $announcement_keep_list = array();
        $announcement_count = engine_count($announcement_query);
        // Expand JSON Arrays into Annoucement Arrays
        for ($i = 0; $i < $announcement_count; $i++) {
            // Convert JSON strings to Arrays
            $continue = false;
            $isValid = true;
            $network_array = json_decode($announcement_query[$i]['networks']);

            $memberlevel_condition = $announcement_query[$i]['memberlevel_condition'];
            $profiletype_condition = $announcement_query[$i]['profiletype_condition'];


            // Check if Member Networks Match Annoucement Networks
            if ($network_array != null) {
                if($network_id_array) {
                    foreach ($network_array as $value) {
                        if ($network_id_array != null && engine_in_array($value, $network_id_array) != false) {
                            $isValid = true;
                            break;
                        }else{
                            $isValid = false;
                        }
                    }
                }else{
                    $isValid = false;
                }
            }

            $member_level_array = (array) json_decode($announcement_query[$i]['member_levels'], true);

            // Check if Member Level Matches Annoucement Level
            if ($member_level_array != null) {
                if(engine_in_array($user_member_level_id, $member_level_array) != false) {
                    if($memberlevel_condition == "OR"){
                        $isValid = true;
                    }
                }else{
                    if($memberlevel_condition == "AND" || !$network_id_array)
                        $isValid = false;
                }
            }

            $profile_type_array = (array) json_decode($announcement_query[$i]['profile_types'], true);

            // Check Member Profile Type Matches Anncounement Profile Type
            if ($profile_type_array != null) {
                if(!$profile_type_id){
                    if($profiletype_condition == "AND" && engine_count($member_level_array))
                        $isValid = false;
                    else if($profiletype_condition == "OR" && $isValid == true &&  engine_count($member_level_array))
                        $isValid = true;
                    else
                        $isValid = false;
                }else if(engine_in_array($profile_type_id, $profile_type_array) != false) {
                    if($profiletype_condition == "OR")
                        $isValid = true;
                }else{
                    if($profiletype_condition == "AND")
                        $isValid = false;
                }
            }

            if($isValid){ 
                array_push($announcement_keep_list, $announcement_query[$i]);
            }
        }

        $paginator = Zend_Paginator::factory($announcement_keep_list);
        $paginator->setItemCountPerPage($this->_getParam('limit', 10));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
 
        $counter = 0;
        $data=array();
        foreach ($paginator as $announcement) {
					$data[$counter] = $announcement->toArray();
					$data[$counter]['body'] = $data[$counter]['body'] . '<style>img{border-radius: 20px;max-width:100%;}</style>';
					$counter++;
				}
       
        $result['announcement'] = $data;
        $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
        $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
        $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
        $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;

        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));
    }
}
