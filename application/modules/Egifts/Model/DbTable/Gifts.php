<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Gifts.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Egifts_Model_DbTable_Gifts extends Core_Model_Item_DbTable_Abstract
{
   protected $_rowClass = "Egifts_Model_Gift";
  /**
   * Gets a select object for the user's datingad entries
   *
   * @param Core_Model_Item_Abstract $user The user to get the messages for
   * @return Zend_Db_Table_Select
   */

	public function getGiftSelect($params = array())
	{
		$giftTableName = $this->info('name');
		$select = $this->select()->from($giftTableName)->where('status != 2');
		if(isset($params['search_type'])){
			$select->where('status != 0');
			switch ($params['search_type']) {
				case 'recentlySPcreated':
					$select->order('created_date DESC');
					break;
				case 'mostSPviewed':
					$select->order('view_count DESC');
					break;
				case 'mostSPliked':
					$select->order('like_count DESC');
					break;
				case 'mostSPfavourite':
					$select->order('like_count DESC');
					break;
				default:
					$select->order('created_date DESC');
					break;
			}
		}
		return $select;
	}


	public function getGiftPaginator($params = array())
	{
		$paginator = Zend_Paginator::factory($this->getGiftSelect($params));
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
			$page = 20;
			$paginator->setItemCountPerPage($page);
		}

		return $paginator;
	}


}
