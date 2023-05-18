<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesbday_Api_Core extends Core_Api_Abstract {
    function getFriendBirthday($todaysDate,$birthdayId,$paginator = false){
        $viewer = Engine_Api::_()->user()->getViewer();
        if(!$viewer->getIdentity()){
            return array();
        }
        $db = Engine_Db_Table::getDefaultAdapter();
		
        $birthdateFieldId = $db->query("Select GROUP_CONCAT(field_id) as field_id FROM engine4_user_fields_meta WHERE type = 'birthdate'")->fetchAll();
        if(engine_count($birthdateFieldId) > 0){
            $field_ids = $birthdateFieldId[0]['field_id'];

            $userTable = Engine_Api::_()->getDbTable('users','user');
            $userTableName = $userTable->info('name');
            $fielValuesTableName  = "engine4_user_fields_values";
			$viewer_id = $viewer->getIdentity();
            $select = $userTable->select()->from($userTable->info('name'),array('*','wish_id'=>new Zend_Db_Expr("(SELECT wish_id FROM engine4_sesbday_wishes WHERE user_id = $viewer_id AND subject_id = engine4_users.user_id AND creation_date LIKE '".date('Y-m-d')."%')")))
                ->setIntegrityCheck(false);

            $select->joinLeft($fielValuesTableName,$fielValuesTableName.'.item_id = '.$userTable->info('name').'.user_id AND field_id IN ('.$field_ids.')','value');
            $select->where($fielValuesTableName.'.value IS NOT NULL AND '.$fielValuesTableName.'.value != ""' );

			if($birthdayId== 5)
			{
				$select->where("DATE_FORMAT(" . $fielValuesTableName." .value, '%m-%d') = ?",date('m-d', strtotime($todaysDate)));
				$userData['date']= $todaysDate;
				$select->order("DATE_FORMAT(" . $fielValuesTableName.".value, '%m-%d') ASC");
				if(empty($paginator))
					$userData['data']= $userTable->fetchAll($select);
				else
					$userData['data']= Zend_Paginator::factory($select);
				return $userData;
			}
            //get loggedin user friends
            $friends = $viewer->membership()->getMembershipsOfIds();
            if ($friends)
                $select->where($userTableName . '.user_id IN (?)', $friends);
            else 
                $select->where($userTableName . '.user_id IN (?)', 0);

			if($birthdayId==1)
			{
				$select->where("DATE_FORMAT(" . $fielValuesTableName." .value, '%m-%d') = ?",date('m-d', strtotime($todaysDate)));
				$userData['date']= $todaysDate;
			}
			else if($birthdayId==2)
			{
				$date = new DateTime('last day of this month');
				$numDaysOfCurrentMonth = $date->format('Y-m-d');
				$addedDate = date('Y-m-d',strtotime("+7 days",time()));
				if(strtotime($addedDate) > strtotime($numDaysOfCurrentMonth)){
					$nextDate = date('m-d',strtotime($numDaysOfCurrentMonth));
					$userData['laterExists']= true;
				}else{
					$nextDate = date('m-d',strtotime($addedDate));
				}
				$day = date('m-d', strtotime("+1 day",strtotime($todaysDate)));
				$select->where("DATE_FORMAT(" . $fielValuesTableName." .value, '%m-%d') BETWEEN \"$day\" AND \"$nextDate\"");
				$userData['date']= $todaysDate;
				
			}
			else if($birthdayId==3)
			{
				$select->where("DATE_FORMAT(" . $fielValuesTableName.".value, '%m') = ?",strlen($todaysDate) == 1 ? "0".$todaysDate : $todaysDate);
				$userData['date']= $todaysDate;
				
			}
			else if($birthdayId==4)
			{
				$date = new DateTime('last day of this month');
				$numDaysOfCurrentMonth = $date->format('m-d');
				$addedDate = date('m-d',strtotime("+8 days",time()));
				$select->where("DATE_FORMAT(" . $fielValuesTableName." .value, '%m-%d') BETWEEN \"$addedDate\" AND \"$numDaysOfCurrentMonth\"  ");
				$userData['date']= $todaysDate;
			}
			$select->order("DATE_FORMAT(" . $fielValuesTableName.".value, '%m-%d') ASC");
			if(empty($paginator))
				$userData['data']= $userTable->fetchAll($select);
			else
				$userData['data']= Zend_Paginator::factory($select);
            return $userData;
        }
        return array();
    }
}
