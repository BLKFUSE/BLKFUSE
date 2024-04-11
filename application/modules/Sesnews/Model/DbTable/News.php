<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: News.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Model_DbTable_News extends Engine_Db_Table {

  protected $_rowClass = "Sesnews_Model_News";

  public function isNewsUrlExist($url) {
    return $this->select()
	      ->from($this->info('name'), 'news_id')
	      ->where('news_link =?', $url)
	      ->query()
	      ->fetchColumn();
  }

  public function getAllNews($rss_id) {

    $rName = $this->info('name');

    $select = $this->select()
                    ->from($rName)->where('rss_id =?', $rss_id);
    return $this->fetchAll($select);
  }

  /**
   * Gets a paginator for sesnews
   *
   * @param Core_Model_Item_Abstract $user The user to get the messages for
   * @return Zend_Paginator
   */
  public function getSesnewsPaginator($params = array(), $customFields = array()) {

    $paginator = Zend_Paginator::factory($this->getSesnewsSelect($params, $customFields));
    if( !empty($params['page']) )
    $paginator->setCurrentPageNumber($params['page']);
    if( !empty($params['limit']) )
    $paginator->setItemCountPerPage($params['limit']);

    if( empty($params['limit']) ) {
      $page = (int) Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.page', 10);
      $paginator->setItemCountPerPage($page);
    }

    return $paginator;
  }

  /**
   * Gets a select object for the user's sesnews entries
   *
   * @param Core_Model_Item_Abstract $user The user to get the messages for
   * @return Zend_Db_Table_Select
   */
  public function getSesnewsSelect($params = array(), $customFields = array()) {

    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $tableLocationName = Engine_Api::_()->getDbtable('locations', 'sesbasic')->info('name');
    $newsTable = Engine_Api::_()->getDbtable('news', 'sesnews');
    $newsTableName = $newsTable->info('name');
    $select = $newsTable->select()->setIntegrityCheck(false)->from($newsTableName);

    if( !empty($params['user_id']) && is_numeric($params['user_id']) )
    $select->where($newsTableName.'.owner_id = ?', $params['user_id']);

    if(isset($params['parent_type']))
      $select->where($newsTableName.'.parent_type = ?', $params['parent_type']);

    if( !empty($params['user']) && $params['user'] instanceof User_Model_User )
    $select->where($newsTableName.'.owner_id = ?', $params['user']->getIdentity());

    if (isset($params['show']) && $params['show'] == 2 && $viewer->getIdentity()) {
      $users = $viewer->membership()->getMembershipsOfIds();
      if ($users)
      $select->where($newsTableName . '.owner_id IN (?)', $users);
      else
      $select->where($newsTableName . '.owner_id IN (?)', 0);
    }

    if(empty($params['miles']))
      $params['miles'] = 200;

    //Location Based search
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('seslocation') && Engine_Api::_()->getApi('settings', 'core')->getSetting('seslocationenable', 1) && empty($params['lat']) && !empty($_COOKIE['sesbasic_location_data']) && $params['manage-widget'] != 1) {
      $params['location'] = $_COOKIE['sesbasic_location_data'];
      $params['lat'] = $_COOKIE['sesbasic_location_lat'];
      $params['lng'] = $_COOKIE['sesbasic_location_lng'];
      $params['miles'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('seslocation.searchmiles', 50);
    }

    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { 
      if (isset($params['lat']) && isset($params['miles']) && $params['miles'] != 0 && isset($params['lng']) && $params['lat'] != '' && $params['lng'] != '' && ((isset($params['location']) && $params['location'] != '' && strtolower($params['location']) != 'world'))) {
        $origLat = $params['lat'];
        $origLon = $params['lng'];
        if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.search.type', 1) == 1)
        $searchType = 3956;
        else
        $searchType = 6371;
        //This is the maximum distance (in miles) away from $origLat, $origLon in which to search
        $dist = $params['miles'];
        $asinSort = array('lat', 'lng', 'distance' => new Zend_Db_Expr(($searchType . " * 2 * ASIN(SQRT( POWER(SIN(($origLat - abs(lat))*pi()/180/2),2) + COS($origLat*pi()/180 )*COS(abs(lat)*pi()/180) *POWER(SIN(($origLon-lng)*pi()/180/2),2)))")));
        $select->joinLeft($tableLocationName, $tableLocationName . '.resource_id = ' . $newsTableName . '.news_id AND ' . $tableLocationName . '.resource_type = "sesnews_news" ', $asinSort);
        $select->where($tableLocationName . ".lng between ($origLon-$dist/abs(cos(radians($origLat))*69)) and ($origLon+$dist/abs(cos(radians($origLat))*69)) and " . $tableLocationName . ".lat between ($origLat-($dist/69)) and ($origLat+($dist/69))");
        $select->order('distance');
        $select->having("distance < $dist");
      }
      else {
        $select->joinLeft($tableLocationName, $tableLocationName . '.resource_id = ' . $newsTableName . '.news_id AND ' . $tableLocationName . '.resource_type = "sesnews_news" ', array('lat', 'lng'));
      }
    } else {
      $select->joinLeft($tableLocationName, $tableLocationName . '.resource_id = ' . $newsTableName . '.news_id AND ' . $tableLocationName . '.resource_type = "sesnews_news" ', array('lat', 'lng'));
      
      if(!empty($params['location']) && !Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) {
        $select->where('`' . $newsTableName . '`.`location` LIKE ?', '%' . $params['location'] . '%');
      }

      if (!empty($params['city'])) {
        $select->where('`' . $tableLocationName . '`.`city` LIKE ?', '%' . $params['city'] . '%');
      }
      if (!empty($params['state'])) {
        $select->where('`' . $tableLocationName . '`.`state` LIKE ?', '%' . $params['state'] . '%');
      }
      if (!empty($params['country'])) {
        $select->where('`' . $tableLocationName . '`.`country` LIKE ?', '%' . $params['country'] . '%');
      }
      if (!empty($params['zip'])) {
        $select->where('`' . $tableLocationName . '`.`zip` LIKE ?', '%' . $params['zip'] . '%');
      }
      if (!empty($params['venue'])) {
        $select->where('`' . $tableLocationName . '`.`venue` LIKE ?', '%' . $params['venue'] . '%');
      }
      //End Location Work
    }

    if( !empty($params['tag']) ) {
      $tmName = Engine_Api::_()->getDbtable('TagMaps', 'core')->info('name');
      $select->setIntegrityCheck(false)->joinLeft($tmName, "$tmName.resource_id = $newsTableName.news_id")
	    ->where($tmName.'.resource_type = ?', 'sesnews_news')
	    ->where($tmName.'.tag_id = ?', $params['tag']);
    }

    if (!empty($params['alphabet']) && $params['alphabet'] != 'all')
    $select->where($newsTableName . ".title LIKE ?", $params['alphabet'] . '%');

    $currentTime = date('Y-m-d H:i:s');

    if(isset($params['popularCol']) && !empty($params['popularCol'])) {
			if($params['popularCol'] == 'week') {
				$endTime = date('Y-m-d H:i:s', strtotime("-1 week"));
				$select->where("DATE(".$newsTableName.".creation_date) between ('$endTime') and ('$currentTime')");
			}
			elseif($params['popularCol'] == 'month') {
				$endTime = date('Y-m-d H:i:s', strtotime("-1 month"));
        $select->where("DATE(".$newsTableName.".creation_date) between ('$endTime') and ('$currentTime')");
			}
			else {
				$select = $select->order($newsTableName . '.' .$params['popularCol'] . ' DESC');
			}
    }

    if (isset($params['fixedData']) && !empty($params['fixedData']) && $params['fixedData'] != '')
    $select = $select->where($newsTableName . '.' . $params['fixedData'] . ' =?', 1);

    if (isset($params['featured']) && !empty($params['featured']))
    $select = $select->where($newsTableName . '.featured =?', 1);

    if (isset($params['hot']) && !empty($params['hot']))
    $select = $select->where($newsTableName . '.hot =?', 1);

    if (isset($params['latest']) && !empty($params['latest']))
    $select = $select->where($newsTableName . '.latest =?', 1);

    if (isset($params['verified']) && !empty($params['verified']))
    $select = $select->where($newsTableName . '.verified =?', 1);

    if (isset($params['sponsored']) && !empty($params['sponsored']))
    $select = $select->where($newsTableName . '.sponsored =?', 1);

    if (!empty($params['rss_id']))
    $select = $select->where($newsTableName . '.rss_id =?', $params['rss_id']);

    if (!empty($params['category_id']))
    $select = $select->where($newsTableName . '.category_id =?', $params['category_id']);

    if (!empty($params['subcat_id']))
    $select = $select->where($newsTableName . '.subcat_id =?', $params['subcat_id']);

    if (!empty($params['subsubcat_id']))
    $select = $select->where($newsTableName . '.subsubcat_id =?', $params['subsubcat_id']);

    if( isset($params['draft']) )
    $select->where($newsTableName.'.draft = ?', $params['draft']);

    if( !empty($params['text']) )
    $select->where($newsTableName.".title LIKE ? OR ".$newsTableName.".body LIKE ?", '%'.$params['text'].'%');

    if( !empty($params['date']) )
    $select->where("DATE_FORMAT(" . $newsTableName.".creation_date, '%Y-%m-%d') = ?", date('Y-m-d', strtotime($params['date'])));

		if( !empty($params['start_date']) )
    $select->where($newsTableName.".creation_date = ?", date('Y-m-d', $params['start_date']));

    if( !empty($params['end_date']) )
    $select->where($newsTableName.".creation_date < ?", date('Y-m-d', $params['end_date']));

    if( !empty($params['visible']) )
    $select->where($newsTableName.".search = ?", $params['visible']);

		if(!isset($params['manage-widget'])) {
			$select->where($newsTableName . ".publish_date <= '$currentTime' OR " . $newsTableName . ".publish_date = ''");
			$select->where($newsTableName.'.is_approved = ?',(bool) 1)->where($newsTableName.'.draft = ?',(bool) 0)->where($newsTableName.'.search = ?', (bool) 1);
			//check package query
			if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesnewspackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnewspackage.enable.package', 1)){
				$order = Engine_Api::_()->getDbTable('orderspackages','sesnewspackage');
				$orderTableName = $order->info('name');
				$select->joinLeft($orderTableName, $orderTableName . '.orderspackage_id = ' . $newsTableName . '.orderspackage_id',null);
				$select->where($orderTableName . '.expiration_date  > "'.date("Y-m-d H:i:s").'" || expiration_date IS NULL || expiration_date = "0000-00-00 00:00:00"');
			}
		}else
			$select->where($newsTableName.'.owner_id = ?',$viewerId);

		if (isset($params['criteria'])) {
			if ($params['criteria'] == 1)
			$select->where($newsTableName . '.featured =?', '1');
			else if ($params['criteria'] == 2)
			$select->where($newsTableName . '.sponsored =?', '1');
			else if ($params['criteria'] == 3)
			$select->where($newsTableName . '.featured = 1 OR ' . $newsTableName . '.sponsored = 1');
			else if ($params['criteria'] == 4)
			$select->where($newsTableName . '.featured = 0 AND ' . $newsTableName . '.sponsored = 0');
			else if ($params['criteria'] == 6)
                $select->where($newsTableName . '.verified =?', '1');
			else if ($params['criteria'] == 8)
                $select->where($newsTableName . '.hot =?', '1');
			else if ($params['criteria'] == 9)
                $select->where($newsTableName . '.latest =?', '1');
		}

		if (isset($params['order']) && !empty($params['order'])) {
      if ($params['order'] == 'week') {
        $endTime = date('Y-m-d H:i:s', strtotime("-1 week"));
        $select->where("DATE(".$newsTableName.".creation_date) between ('$endTime') and ('$currentTime')");
      } elseif ($params['order'] == 'month') {
        $endTime = date('Y-m-d H:i:s', strtotime("-1 month"));
        $select->where("DATE(".$newsTableName.".creation_date) between ('$endTime') and ('$currentTime')");
      }
    }

    if (isset($params['widgetName']) && !empty($params['widgetName']) && $params['widgetName'] == 'Similar News') {
      if(!empty($params['widgetName'])) {
        $select->where($newsTableName.'.category_id =?', $params['category_id']);
      }
    }

		if(isset($params['similar_news']))
		$select->where($newsTableName . '.parent_id =?', $params['news_id']);

		if (isset($customFields['has_photo']) && !empty($customFields['has_photo'])) {
      $select->where($newsTableName . '.photo_id != ?', "0");
    }

		if (isset($params['criteria'])) {
			switch ($params['info']) {
				case 'recently_created':
					$select->order($newsTableName . '.creation_date DESC');
					break;
				case 'most_viewed':
					$select->order($newsTableName . '.view_count DESC');
					break;
				case 'most_liked':
					$select->order($newsTableName . '.like_count DESC');
					break;
				case 'most_favourite':
					$select->order($newsTableName . '.favourite_count DESC');
					break;
				case 'most_commented':
					$select->order($newsTableName . '.comment_count DESC');
					break;
				case 'most_rated':
					$select->order($newsTableName . '.rating DESC');
					break;
				case 'random':
					$select->order('Rand()');
					break;
			}
		}
		
		if(!empty($params['getnews']))	{
			$select->where($newsTableName.".title LIKE ? OR ".$newsTableName.".body LIKE ?", '%'.$params['textSearch'].'%')->where($newsTableName.".search = ?", 1);
		}
		
		if(!empty($params['search']) && $params['search'] != '1')	{
			$select->where($newsTableName.".title LIKE ? OR ".$newsTableName.".body LIKE ?", '%'.$params['search'].'%')->where($newsTableName.".search = ?", 1);
		}

    //don't show other module news
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.other.modulenews', 1) && empty($params['resource_type'])) {
      $select->where($newsTableName . '.resource_type IS NULL')
              ->where($newsTableName . '.resource_id =?', 0);
    } else if (!empty($params['resource_type']) && !empty($params['resource_id'])) {
      $select->where($newsTableName . '.resource_type =?', $params['resource_type'])
              ->where($newsTableName . '.resource_id =?', $params['resource_id']);
    } else if(!empty($params['resource_type'])) {
      $select->where($newsTableName . '.resource_type =?', $params['resource_type']);
    }
    //don't show other module news

    $select->order( !empty($params['orderby']) ? $params['orderby'].' DESC' : $newsTableName.'.creation_date DESC' );

    if(isset($params['fetchAll'])) {
      if(!isset($params['rss'])) {
        if(empty($params['limit']))
        $select->limit(3);
        else
        $select->limit($params['limit']);
      }
      return $this->fetchAll($select);
    }
    else
    return $select;
  }

  public function getNews($params = array()) {

    $table = Engine_Api::_()->getDbtable('news', 'sesnews');
    $newsTableName = $table->info('name');
    $select = $table->select()
                    ->where($newsTableName.'.draft = ?', 0)
                    ->where($newsTableName.".title LIKE ? OR ".$newsTableName.".body LIKE ?", '%'.$params['text'].'%')
                    ->where($newsTableName.".search = ?", 1)
                    ->order('creation_date DESC');

    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.other.modulenews', 1) && empty($params['resource_type'])) {
      $select->where($newsTableName . '.resource_type IS NULL')->where($newsTableName . '.resource_id =?', 0);
    }

    return $this->fetchAll($select);
  }

  /**
   * Returns an array of dates where a given user created a sesnews entry
   *
   * @param User_Model_User user to calculate for
   * @return Array Dates
   */
  public function getArchiveList($spec) {

    if( !($spec instanceof User_Model_User) )
    return null;

    $localeObject = Zend_Registry::get('Locale');
    if( !$localeObject )
    $localeObject = new Zend_Locale();

    $dates = $this->select()
        ->from($this, 'creation_date')
        ->where('owner_type = ?', 'user')
        ->where('owner_id = ?', $spec->getIdentity())
        ->where('draft = ?', 0)
        ->order('news_id DESC')
        ->query()
        ->fetchAll(Zend_Db::FETCH_COLUMN);

    $time = time();

    $archive_list = array();
    foreach( $dates as $date ) {

      $date = strtotime($date);
      $ltime = localtime($date, true);
      $ltime["tm_mon"] = $ltime["tm_mon"] + 1;
      $ltime["tm_year"] = $ltime["tm_year"] + 1900;

      // LESS THAN A YEAR AGO - MONTHS
      if( $date + 31536000 > $time ) {
        $date_start = mktime(0, 0, 0, $ltime["tm_mon"], 1, $ltime["tm_year"]);
        $date_end = mktime(0, 0, 0, $ltime["tm_mon"] + 1, 1, $ltime["tm_year"]);
        $type = 'month';

        $dateObject = new Zend_Date($date);
        $format = $localeObject->getTranslation('yMMMM', 'dateitem', $localeObject);
        $label = $dateObject->toString($format, $localeObject);
      }
      // MORE THAN A YEAR AGO - YEARS
      else {
        $date_start = mktime(0, 0, 0, 1, 1, $ltime["tm_year"]);
        $date_end = mktime(0, 0, 0, 1, 1, $ltime["tm_year"] + 1);
        $type = 'year';

        $dateObject = new Zend_Date($date);
        $format = $localeObject->getTranslation('yyyy', 'dateitem', $localeObject);
        if( !$format )
        $format = $localeObject->getTranslation('y', 'dateitem', $localeObject);

        $label = $dateObject->toString($format, $localeObject);
      }

      if( !isset($archive_list[$date_start]) ) {
        $archive_list[$date_start] = array(
          'type' => $type,
          'label' => $label,
          'date' => $date,
          'date_start' => $date_start,
          'date_end' => $date_end,
          'count' => 1
        );
      }
      else
      $archive_list[$date_start]['count']++;
    }
    return $archive_list;
  }

  public function getOfTheDayResults() {
    return $this->select()
	      ->from($this->info('name'), 'news_id')
	      ->where('offtheday =?', 1)
	      ->where('starttime <= DATE(NOW())')
	      ->where('endtime >= DATE(NOW())')
	      ->order('RAND()')
	      ->query()
	      ->fetchColumn();
  }

  public function checkCustomUrl($value = '', $news_id = '') {
    $select = $this->select('news_id')->where('custom_url = ?', $value);
    if ($news_id)
      $select->where('news_id !=?', $news_id);
    return $select->query()->fetchColumn();
  }

  public function getNewsId($slug = null) {
    if ($slug) {
      $tableName = $this->info('name');
      $select = $this->select()
      ->from($tableName)
      ->where($tableName . '.custom_url = ?', $slug);
      $row = $this->fetchRow($select);
      if (empty($row)) {
      $news_id = $slug;
      } else
      $news_id = $row->news_id;
      return $news_id;
    }
    return '';
  }
}
