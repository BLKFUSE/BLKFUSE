<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Games.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egames_Model_DbTable_Games extends Engine_Db_Table
{
	protected $_rowClass = 'Egames_Model_Game';
    protected $_name = 'egames_games';
	
	function getGamesPaginator($params = array()){
        $paginator = Zend_Paginator::factory($this->getGames($params),true);
        if( !empty($params['page']) )
        {
            $paginator->setCurrentPageNumber($params['page']);
        }
        if( !empty($params['limit']) )
        {
            $paginator->setItemCountPerPage($params['limit']);
        }

        if( empty($params['limit']) )
        {
            $page = 10;
            $paginator->setItemCountPerPage($page);
        }
		return $paginator;
	}

	public function getGames($params = array(),$paginator = false){
		$tableName = $this->info('name');
		
		$select = $this->select()
			->from($tableName)
			->setIntegrityCheck(false);
			
		if(isset($params['sort'])){
				switch($params['sort']){
					case 'recently_created':
						$select->order('creation_date DESC');
						break;
					case 'most_viewed':
						$select->order('view_count DESC');
						break;
					case 'most_liked':
						$select->order('like_count DESC');
						break;
					
					case 'most_commented':
						$select->order('comment_count DESC');
						break;
					case 'most_played':
							$select->order('play_count DESC');
					break;
				}
			}else{
			if(!empty($params['order']))
				$select = $select->order($tableName.'.view_count DESC');
			else
				$select = $select->order("$tableName.game_id DESC");
			}
			$viewer = Engine_Api::_()->user()->getViewer();
		if (isset($params['show']) && $params['show'] == 2 && $viewer->getIdentity()) {
			$users = $viewer->membership()->getMembershipsOfIds();
			$select->where($tableName.'.owner_id IN (?)',count($users) ? $users : 0);
		}
		if (isset($params['search']) && $params['search'] != '') {
			$select->where("title  LIKE ? ", '%' . $params['search'] . '%');
		}
		if(empty($params['managePage'])){
			$select->where('search =?', true);
		}

		if(isset($params['owner_id']) && intval($params['owner_id'])) 
			$select->where('owner_id = ?',$params['owner_id']);

		if(!empty($params['not_game_id']))
			$select = $select->where($tableName.'.game_id !=?',$params['not_game_id']);
		if(!empty($params['category_id']))
			$select = $select->where($tableName.'.category_id =?',$params['category_id']);
		if(!empty($params['subcat_id']))
			$select = $select->where($tableName.'.subcat_id =?',$params['subcat_id']);
		if(!empty($params['subsubcat_id']))
			$select = $select->where($tableName.'.subsubcat_id =?',$params['subsubcat_id']);
		if(!empty($params['album_id']))
			$select = $select->where($tableName.'.album_id =?',$params['album_id']);

		if(!empty($params['limit_data']))
			$select = $select->limit($params['limit_data']);

			//echo $select;die;
		if(!$paginator)
			$paginator = $this->fetchAll($select);
		else
			$paginator = Zend_Paginator::factory($select);
		return  $paginator;
	}
	
}
