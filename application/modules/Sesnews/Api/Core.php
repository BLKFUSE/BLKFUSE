<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Api_Core extends Core_Api_Abstract {

  public function getFileUrl($image) {
    
    $table = Engine_Api::_()->getDbTable('files', 'core');
    $result = $table->select()
                ->from($table->info('name'), 'storage_file_id')
                ->where('storage_path =?', $image)
                ->query()
                ->fetchColumn();
    if(!empty($result)) {
      $storage = Engine_Api::_()->getItem('storage_file', $result);
      return $storage->map();
    } else {
      return $image;
    }
  }
  
		public function geLinkContents($URL) {
			$ch = curl_init($URL);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36');
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
			//curl_setopt($ch, CURLOPT_ENCODING, '');
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}
		
		public function getImageURL($uri) {

			$doc = new DOMDocument("1.0", 'utf-8');
			$html = $this->geLinkContents($uri);

			$encoding = 'utf-8';
			preg_match('/<html(.*?)>/i', $html, $regMatches);
			preg_match('/<meta[^<].*charset=["]?([\w-]*)["]?/i', $html, $charSetMatches);
			if (isset($charSetMatches[1])) {
				$encoding = $charSetMatches[1];
			} elseif(isset($regMatches[1])) {
				preg_match('/lang=["|\'](.*?)["|\']/is', $regMatches[1], $languages);
				if(isset($languages[1]) && in_array($languages[1], ['uk'])) {
					$encoding = 'Windows-1251';
				}
			}

			$contentType = '<meta http-equiv="Content-Type" content="text/html; charset=' . $encoding . '">';
			$html = str_replace('<head>', '<head>' . $contentType, $html);

			if (function_exists('mb_convert_encoding')) {
				@$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', $encoding));
			} else {
				@$doc->loadHTML($html);
			}
			
			$metaList = $doc->getElementsByTagName("meta");
			foreach ($metaList as $iKey => $meta) {
				$type = $meta->getAttribute('property');
				$content = $meta->getAttribute('content');
				if(empty($type)) {
					$type = $meta->getAttribute('name');
				}
				$iframely[$type] = $content;
			}
			$imageLink = '';
			//Get OG Image
			if (!empty($iframely['og:image'])) {
				$imageLink = $iframely['og:image'];
			}
			return $imageLink;
		}

  
    public function getApiResults($news_link) {

        $mercurykey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.mercurykey', '');
        if(empty($mercurykey))
            return;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $news_link);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        $headers = array();
        $headers[] = "X-Api-Key: ".$mercurykey;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $results = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close ($ch);

        $results = json_decode($results);
        return $results;
    }

    public function getRSSNews($rss) {

        //$importNews = Zend_Feed::import($rss->rss_link);
        $rss_id = $rss->getIdentity();

        $rss2jsonApiKey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.mercurykey', '');
        $rss_url = $rss->rss_link;
        $url = 'https://api.rss2json.com/v1/api.json?rss_url='.urlencode($rss_url).'&api_key='.$rss2jsonApiKey;
        $importNews = json_decode(file_get_contents($url), true);

        $table = Engine_Api::_()->getDbtable('news', 'sesnews');
        $roleTable = Engine_Api::_()->getDbtable('roles', 'sesnews');

        // Auth
        $auth = Engine_Api::_()->authorization()->context;
        $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

        $viewMax = array_search($rss->view_privacy, $roles);
        $commentMax = array_search($rss->view_privacy, $roles);

        //rss owner is viewer
        $owner = Engine_Api::_()->getItem('user', $rss->owner_id);

        $news_count = 0;
        $allow_count = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.maxfetchnews', 10);

        // Get notification table
        $notificationTable = Engine_Api::_()->getDbtable('notifications', 'activity');

        $getAllSubscribers = Engine_Api::_()->getDbTable('rsssubscriptions', 'sesnews')->getAllSubscribers($rss_id);

        foreach($importNews['items'] as $importurls) {

            if($allow_count >= $news_count) {

                //$news_link = 'https://mercury.postlight.com/parser?url='.$importurls->link();
                $newsResult = $importurls; //(array) Engine_Api::_()->sesnews()->getApiResults($news_link);

                $news_title = $newsResult['title'];
                if(empty($news_title))
                    continue;

                $isNewsUrlExist = Engine_Api::_()->getDbTable('news', 'sesnews')->isNewsUrlExist($newsResult['link']);
                if(!empty($isNewsUrlExist))
                    continue;

								if(isset($newsResult['thumbnail']) && !empty($newsResult['thumbnail'])) {
									$image_url = $newsResult['thumbnail'];
								} elseif(isset($newsResult['enclosure']['thumbnail']) && !empty($newsResult['enclosure']['thumbnail'])) {
										$image_url = $newsResult['enclosure']['thumbnail'];
								} elseif(isset($newsResult['enclosure']['link']) && !empty($newsResult['enclosure']['link'])) {
										$image_url = $newsResult['enclosure']['link'];
								} else {
									$image_url = $this->getImageURL($newsResult['link']);
								}

                $PhotoExtension = pathinfo($image_url, PATHINFO_EXTENSION);
								
								if(strpos(strtolower($image_url), '.jpg') === false && strpos(strtolower($image_url), '.png') === false && strpos(strtolower($image_url), '.gif') === false && strpos(strtolower($image_url), '.jpeg') === false && strpos(strtolower($image_url), '.webp') === false) {
									continue;
								}
//                 if(!exif_imagetype($image_url)) {
// 									continue;
//                 }
                //if(!in_array($PhotoExtension, array('jpg','png','gif','jpeg'))) continue;				

                $news_params['title'] = htmlspecialchars_decode(htmlspecialchars_decode($newsResult['title']));
                $news_params['body'] = $newsResult['content'] ? $newsResult['content'] : $newsResult['description'];
                $news_params['rss_id'] = $rss_id;
                //$news_params['custom_url'] = $this->getSlug($news_title);
                $news_params['owner_type'] = 'user';
                $news_params['owner_id'] = $rss->owner_id;
                $news_params['category_id'] = $rss->category_id;
                $news_params['subcat_id'] = $rss->subcat_id;
                $news_params['subsubcat_id'] = $rss->subsubcat_id;
                $news_params['news_link'] = $newsResult['link'];
                $news_params['image_url'] = $image_url;

//                 $isNewsUrlExist = Engine_Api::_()->getDbTable('news', 'sesnews')->isNewsUrlExist($newsResult['url']);
//                 if(!empty($isNewsUrlExist))
//                     continue;
//
//                 $image_url = $newsResult['lead_image_url'];
//
//                 $news_params['title'] = $newsResult['title'];
//                 $news_params['body'] = $newsResult['content'];
//                 $news_params['rss_id'] = $rss_id;
//                 //$news_params['custom_url'] = $this->getSlug($news_title);
//                 $news_params['owner_type'] = 'user';
//                 $news_params['owner_id'] = $rss->owner_id;
//                 $news_params['category_id'] = $rss->category_id;
//                 $news_params['subcat_id'] = $rss->subcat_id;
//                 $news_params['subsubcat_id'] = $rss->subsubcat_id;
//                 $news_params['news_link'] = $newsResult['url'];

                //Create News
                $news = $table->createRow();
                $news->setFromArray($news_params);
                $news->save();
                //SET custom url
                $news->custom_url = $news->getSlug();
                $news->save();

                $newsroles = $roleTable->createRow();
                $newsroles->news_id = $news->news_id;
                $newsroles->user_id = $rss->owner_id;
                $newsroles->save();

                //Privacy of new news
                foreach( $roles as $i => $role ) {
                    $auth->setAllowed($news, $role, 'view', ($i <= $viewMax));
                    $auth->setAllowed($news, $role, 'comment', ($i <= $commentMax));
                }

                //Photo import work
                if(!empty($image_url)) {
                    $photo_id = Engine_Api::_()->sesbasic()->setPhoto(current(explode('?',$image_url)), true,false,'sesnews','sesnews_news','',$news,true);
                    if(empty($photo_id)) {
											$news->delete();
											continue;
                    }
                    $news->photo_id = $photo_id;
                    $news->save();
                }

                $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($owner, $news, 'sesnews_new');
                // make sure action exists before attaching the sesnews to the activity
                if( $action ) {
                    Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $news);
                }

                // Send notifications to all subscribers
                foreach($getAllSubscribers as $user ) {
                    $user = Engine_Api::_()->getItem('user', $user);
                    $notificationTable->addNotification($user, $owner, $news, 'sesnews_subscribed_new');
                }
                $news_count++;
                $rss->news_count++;
                $rss->save();
            }
        }
    }

  public function checkPrivacySetting($news_id) {

    $news = Engine_Api::_()->getItem('sesnews_news', $news_id);
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();

    if ($viewerId)
      $level_id = $viewer->level_id;
    else
      $level_id = 5;

    $levels = $news->levels;
    $member_level = explode(",",$news->levels); //json_decode($levels);

    if (!empty($member_level)  && !empty($item->levels)) {
      if (!engine_in_array($level_id, $member_level))
        return false;
    } else
      return true;


    if ($viewerId) {
      $network_table = Engine_Api::_()->getDbtable('membership', 'network');
      $network_select = $network_table->select('resource_id')->where('user_id = ?', $viewerId);
      $network_id_query = $network_table->fetchAll($network_select);
      $network_id_query_count = engine_count($network_id_query);
      $network_id_array = array();
      for ($i = 0; $i < $network_id_query_count; $i++) {
        $network_id_array[$i] = $network_id_query[$i]['resource_id'];
      }

      if (!empty($network_id_array)) {
        $networks = explode(",",$news->networks); //json_decode($news->networks);

        if (!empty($networks)) {
          if (!array_intersect($network_id_array, $networks))
            return false;
        } else
          return true;
      }
    }
    return true;
  }

  /* get other module compatibility code as per module name given */
  public function getPluginItem($moduleName) {
		//initialize module item array
    $moduleType = array();
    $filePath =  APPLICATION_PATH . "/application/modules/" . ucfirst($moduleName) . "/settings/manifest.php";
		//check file exists or not
    if (is_file($filePath)) {
			//now include the file
      $manafestFile = include $filePath;
			$resultsArray =  Engine_Api::_()->getDbtable('integrateothermodules', 'sesnews')->getResults(array('module_name'=>$moduleName));
      if (is_array($manafestFile) && isset($manafestFile['items'])) {
        foreach ($manafestFile['items'] as $item)
          if (!engine_in_array($item, $resultsArray))
            $moduleType[$item] = $item.' ';
      }
    }
    return $moduleType;
  }

  public function getWidgetPageId($widgetId) {

    $db = Engine_Db_Table::getDefaultAdapter();
    $params = $db->select()
            ->from('engine4_core_content', 'page_id')
            ->where('`content_id` = ?', $widgetId)
            ->query()
            ->fetchColumn();
    return json_decode($params, true);
  }

  function multiCurrencyActive(){
    if(!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])){
      return Engine_Api::_()->sesmultiplecurrency()->multiCurrencyActive();
    }else{
      return false;
    }
  }
  function isMultiCurrencyAvailable(){
    if(!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])){
      return Engine_Api::_()->sesmultiplecurrency()->isMultiCurrencyAvailable();
    }else{
      return false;
    }
  }

  function getCurrencyPrice($price = 0, $givenSymbol = '', $change_rate = '',$returnValue = false){
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $precisionValue = $settings->getSetting('sesmultiplecurrency.precision', 2);
    $defaultParams['precision'] = $precisionValue;
    if(!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])){
      return Engine_Api::_()->sesmultiplecurrency()->getCurrencyPrice($price, $givenSymbol, $change_rate,$returnValue);
    }else{
      return Zend_Registry::get('Zend_View')->locale()->toCurrency($price, $givenSymbol, $defaultParams);
    }
  }
  function getCurrentCurrency(){
    $settings = Engine_Api::_()->getApi('settings', 'core');
    if(!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])){
      return Engine_Api::_()->sesmultiplecurrency()->getCurrentCurrency();
    }else{
      return $settings->getSetting('payment.currency', 'USD');
    }
  }
  function defaultCurrency(){
    if(!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])){
      return Engine_Api::_()->sesmultiplecurrency()->defaultCurrency();
    }else{
      $settings = Engine_Api::_()->getApi('settings', 'core');
      return $settings->getSetting('payment.currency', 'USD');
    }
  }

  /* people like item widget paginator */
  public function likeItemCore($params = array()) {

    $parentTable = Engine_Api::_()->getItemTable('core_like');
    $parentTableName = $parentTable->info('name');
    $select = $parentTable->select()
            ->from($parentTableName)
            ->where('resource_type = ?', $params['type'])
            ->order('like_id DESC');
    if (isset($params['id']))
      $select = $select->where('resource_id = ?', $params['id']);
    if (isset($params['poster_id']))
      $select = $select->where('poster_id =?', $params['poster_id']);
    return Zend_Paginator::factory($select);
  }

  function truncate($text, $length = 100, $options = array()) {
    $default = array(
        'ending' => '...', 'exact' => true, 'html' => false
    );
    $options = array_merge($default, $options);
    extract($options);

    if ($html) {
        if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        $totalLength = mb_strlen(strip_tags($ending));
        $openTags = array();
        $truncate = '';

        preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
        foreach ($tags as $tag) {
            if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                    array_unshift($openTags, $tag[2]);
                } else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                    $pos = array_search($closeTag[1], $openTags);
                    if ($pos !== false) {
                        array_splice($openTags, $pos, 1);
                    }
                }
            }
            $truncate .= $tag[1];

            $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
            if ($contentLength + $totalLength > $length) {
                $left = $length - $totalLength;
                $entitiesLength = 0;
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                    foreach ($entities[0] as $entity) {
                        if ($entity[1] + 1 - $entitiesLength <= $left) {
                            $left--;
                            $entitiesLength += mb_strlen($entity[0]);
                        } else {
                            break;
                        }
                    }
                }

                $truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
                break;
            } else {
                $truncate .= $tag[3];
                $totalLength += $contentLength;
            }
            if ($totalLength >= $length) {
                break;
            }
        }
    } else {
        if (mb_strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
        }
    }
    if (!$exact) {
        $spacepos = mb_strrpos($truncate, ' ');
        if (isset($spacepos)) {
            if ($html) {
                $bits = mb_substr($truncate, $spacepos);
                preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                if (!empty($droppedTags)) {
                    foreach ($droppedTags as $closingTag) {
                        if (!engine_in_array($closingTag[1], $openTags)) {
                            array_unshift($openTags, $closingTag[1]);
                        }
                    }
                }
            }
            $truncate = mb_substr($truncate, 0, $spacepos);
        }
    }
    $truncate .= $ending;

    if ($html) {
        foreach ($openTags as $tag) {
            $truncate .= '</'.$tag.'>';
        }
    }

    return $truncate;
}
   public function getCustomFieldMapDataNews($news) {
    if ($news) {
      $db = Engine_Db_Table::getDefaultAdapter();
      return $db->query("SELECT GROUP_CONCAT(value) AS `valuesMeta`,IFNULL(TRIM(TRAILING ', ' FROM GROUP_CONCAT(DISTINCT(engine4_sesnews_news_fields_options.label) SEPARATOR ', ')),engine4_sesnews_news_fields_values.value) AS `value`, `engine4_sesnews_news_fields_meta`.`label`, `engine4_sesnews_news_fields_meta`.`type` FROM `engine4_sesnews_news_fields_values` LEFT JOIN `engine4_sesnews_news_fields_meta` ON engine4_sesnews_news_fields_meta.field_id = engine4_sesnews_news_fields_values.field_id LEFT JOIN `engine4_sesnews_news_fields_options` ON engine4_sesnews_news_fields_values.value = engine4_sesnews_news_fields_options.option_id AND (`engine4_sesnews_news_fields_meta`.`type` = 'multi_checkbox' || `engine4_sesnews_news_fields_meta`.`type` = 'radio') WHERE (engine4_sesnews_news_fields_values.item_id = ".$news->news_id.") AND (engine4_sesnews_news_fields_values.field_id != 1) GROUP BY `engine4_sesnews_news_fields_meta`.`field_id`,`engine4_sesnews_news_fields_options`.`field_id`")->fetchAll();
    }
    return array();
  }

  public function getwidgetizePage($params = array()) {

    $corePages = Engine_Api::_()->getDbtable('pages', 'core');
    $corePagesName = $corePages->info('name');
    $select = $corePages->select()
            ->from($corePagesName, array('*'))
            ->where('name = ?', $params['name'])
            ->limit(1);
    return $corePages->fetchRow($select);
  }

     /**
   * Gets an absolute URL to the page to view this item
   *
   * @return string
  */
  public function getHref($albumId = '', $slug = '') {
//     if (is_numeric($albumId)) {
//       $slug = $this->getSlug(Engine_Api::_()->getItem('sesnews_album', $albumId)->getTitle());
//     }
    $params = array_merge(array(
        'route' => 'sesnews_specific_album',
        'reset' => true,
        'album_id' => $albumId,
       // 'slug' => $slug,
    ));
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
                    ->assemble($params, $route, $reset);
  }
      //get album photo
  function getAlbumPhoto($albumId = '', $photoId = '', $limit = 4) {
    if ($albumId != '') {
      $albums = Engine_Api::_()->getItemTable('sesnews_album');
      $albumTableName = $albums->info('name');
      $photos = Engine_Api::_()->getItemTable('sesnews_photo');
      $photoTableName = $photos->info('name');
      $select = $photos->select()
              ->from($photoTableName)
              ->limit($limit)
              ->where($albumTableName . '.album_id = ?', $albumId)
              ->where($photoTableName . '.photo_id != ?', $photoId)
              ->setIntegrityCheck(false)
              ->joinLeft($albumTableName, $albumTableName . '.album_id = ' . $photoTableName . '.album_id', null);
      if ($limit == 3)
        $select = $select->order('rand()');
      return $photos->fetchAll($select);
    }
  }
       //get photo URL
  public function photoUrlGet($photo_id, $type = null) {
    if (empty($photo_id)) {
      $photoTable = Engine_Api::_()->getItemTable('sesnews_photo');
      $photoInfo = $photoTable->select()
              ->from($photoTable, array('photo_id', 'file_id'))
              ->where('album_id = ?', $this->album_id)
              ->order('order ASC')
              ->limit(1)
              ->query()
              ->fetch();
      if (!empty($photoInfo)) {
        $this->photo_id = $photo_id = $photoInfo['photo_id'];
        $this->save();
        $file_id = $photoInfo['file_id'];
      } else {
        return;
      }
    } else {
      $photoTable = Engine_Api::_()->getItemTable('sesnews_photo');
      $file_id = $photoTable->select()
              ->from($photoTable, 'file_id')
              ->where('photo_id = ?', $photo_id)
              ->query()
              ->fetchColumn();
    }
    if (!$file_id) {
      return;
    }
    $file = Engine_Api::_()->getItemTable('storage_file')->getFile($file_id, $type);
    if (!$file) {
      $file = Engine_Api::_()->getItemTable('storage_file')->getFile($file_id, '');
    }
    return $file->map();
  }


    public function getPreviousPhoto($album_id = '', $order = '') {
    $table = Engine_Api::_()->getDbTable('photos', 'sesnews');
    $select = $table->select()
            ->where('album_id = ?', $album_id)
            ->where('`order` < ?', $order)
            ->order('order DESC')
            ->limit(1);
    $photo = $table->fetchRow($select);
    if (!$photo) {
      // Get last photo instead
      $select = $table->select()
              ->where('album_id = ?', $album_id)
              ->order('order DESC')
              ->limit(1);
      $photo = $table->fetchRow($select);
    }
    return $photo;
  }

  	public function getNextPhoto($album_id = '', $order = '') {
    $table = Engine_Api::_()->getDbTable('photos', 'sesnews');
    $select = $table->select()
            ->where('album_id = ?', $album_id)
            ->where('`order` > ?', $order)
            ->order('order ASC')
            ->limit(1);
    $photo = $table->fetchRow($select);
    if (!$photo) {
      // Get first photo instead
      $select = $table->select()
              ->where('album_id = ?', $album_id)
              ->order('order ASC')
              ->limit(1);
      $photo = $table->fetchRow($select);
    }
    return $photo;
  }

    //Get Event like status
  public function getLikeStatusNews($news_id = '', $moduleName = '') {
    if ($moduleName == '')
      $moduleName = 'sesnews_news';
    if ($news_id != '') {
      $userId = Engine_Api::_()->user()->getViewer()->getIdentity();
      if ($userId == 0)
        return false;
      $coreLikeTable = Engine_Api::_()->getDbtable('likes', 'core');
      $total_likes = $coreLikeTable->select()
              ->from($coreLikeTable->info('name'), new Zend_Db_Expr('COUNT(like_id) as like_count'))
              ->where('resource_type =?', $moduleName)
              ->where('poster_id =?', $userId)
              ->where('poster_type =?', 'user')
              ->where('	resource_id =?', $news_id)
              ->query()
              ->fetchColumn();
      if ($total_likes > 0)
        return true;
      else
        return false;
    }
    return false;
  }


  /**
   * Get Widget Identity
   *
   * @return $identity
  */
  public function getIdentityWidget($name, $type, $corePages) {

    if((isset($_SESSION['sespwa']['sespwa']) && !empty($_SESSION['sespwa']['sespwa'])) || (isset($_SESSION['sespwa']['mobile']) && !empty($_SESSION['sespwa']['mobile']))) {
      $widgetTable = Engine_Api::_()->getDbTable('content', 'sespwa');
      $widgetPages = Engine_Api::_()->getDbTable('pages', 'sespwa')->info('name');
    } else {
      $widgetTable = Engine_Api::_()->getDbTable('content', 'core');
      $widgetPages = Engine_Api::_()->getDbTable('pages', 'core')->info('name');
    }
    $identity = $widgetTable->select()
            ->setIntegrityCheck(false)
            ->from($widgetTable, 'content_id')
            ->where($widgetTable->info('name') . '.type = ?', $type)
            ->where($widgetTable->info('name') . '.name = ?', $name)
            ->where($widgetPages . '.name = ?', $corePages)
            ->joinLeft($widgetPages, $widgetPages . '.page_id = ' . $widgetTable->info('name') . '.page_id')
            ->query()
            ->fetchColumn();
    return $identity;
  }

  function tagCloudItemCore($fetchtype = '', $news_id = '') {

    $tableTagmap = Engine_Api::_()->getDbtable('tagMaps', 'core');
    $tableTagName = $tableTagmap->info('name');
    $tableTag = Engine_Api::_()->getDbtable('tags', 'core');
    $tableMainTagName = $tableTag->info('name');
    $selecttagged_photo = $tableTagmap->select()
            ->from($tableTagName)
            ->setIntegrityCheck(false)
            ->where('resource_type =?', 'sesnews_news')
            ->where('tag_type =?', 'core_tag')
            ->joinLeft($tableMainTagName, $tableMainTagName . '.tag_id=' . $tableTagName . '.tag_id', array('text'))
            ->group($tableTagName . '.tag_id');
    if($news_id) {
      $selecttagged_photo->where($tableTagName.'.resource_id =?', $news_id);
    }
    $selecttagged_photo->columns(array('itemCount' => ("COUNT($tableTagName.tagmap_id)")));
    if ($fetchtype == '')
      return Zend_Paginator::factory($selecttagged_photo);
    else
      return $tableTagmap->fetchAll($selecttagged_photo);
  }

  function getNewsgers() {
    $userTable = Engine_Api::_()->getItemTable('user');
    $userTableName = $userTable->info('name');
    $newsTable = Engine_Api::_()->getDbTable('news', 'sesnews');
    $newsTableName = $newsTable->info('name');
    $select = $userTable->select()
			->from($userTable, array('COUNT(*) AS news_count', 'user_id', 'displayname'))
			->setIntegrityCheck(false)
			->join($newsTableName, $newsTableName . '.owner_id=' . $userTableName . '.user_id')
			->group($userTableName . '.user_id')->order('news_count DESC');
    return Zend_Paginator::factory($select);
  }

    // get item like status
  public function getLikeStatus($news_id = '', $resource_type = '') {

    if ($news_id != '') {
      $userId = Engine_Api::_()->user()->getViewer()->getIdentity();
      if ($userId == 0)
        return false;
      $coreLikeTable = Engine_Api::_()->getDbtable('likes', 'core');
      $total_likes = $coreLikeTable->select()->from($coreLikeTable->info('name'), new Zend_Db_Expr('COUNT(like_id) as like_count'))->where('resource_type =?', $resource_type)->where('poster_id =?', $userId)->where('poster_type =?', 'user')->where('resource_id =?', $news_id)->limit(1)->query()->fetchColumn();
      if ($total_likes > 0)
        return true;
      else
        return false;
    }
    return false;
  }

  public function getCustomFieldMapData($item) {
    if ($item) {
      $db = Engine_Db_Table::getDefaultAdapter();
      return $db->query("SELECT GROUP_CONCAT(value) AS `valuesMeta`,IFNULL(TRIM(TRAILING ', ' FROM GROUP_CONCAT(DISTINCT(engine4_sesnews_review_fields_options.label) SEPARATOR ', ')),engine4_sesnews_review_fields_values.value) AS `value`, `engine4_sesnews_review_fields_meta`.`label`, `engine4_sesnews_review_fields_meta`.`type` FROM `engine4_sesnews_review_fields_values` LEFT JOIN `engine4_sesnews_review_fields_meta` ON engine4_sesnews_review_fields_meta.field_id = engine4_sesnews_review_fields_values.field_id LEFT JOIN `engine4_sesnews_review_fields_options` ON engine4_sesnews_review_fields_values.value = engine4_sesnews_review_fields_options.option_id AND `engine4_sesnews_review_fields_meta`.`type` = 'multi_checkbox' WHERE (engine4_sesnews_review_fields_values.item_id = ".$item->getIdentity().") AND (engine4_sesnews_review_fields_values.field_id != 1) GROUP BY `engine4_sesnews_review_fields_meta`.`field_id`,`engine4_sesnews_review_fields_options`.`field_id`")->fetchAll();
    }
    return array();
  }

  public function getSpecialAlbum(User_Model_User $user, $type = 'sesnews_news') {
    $table = Engine_Api::_()->getItemTable('album');
    $select = $table->select()
        ->where('owner_type = ?', $user->getType())
        ->where('owner_id = ?', $user->getIdentity())
        ->where('type = ?', $type)
        ->order('album_id ASC')
        ->limit(1);
    $album = $table->fetchRow($select);
    // Create wall photos album if it doesn't exist yet
    if( null === $album ) {
      $translate = Zend_Registry::get('Zend_Translate');
      $album = $table->createRow();
      $album->owner_type = 'user';
      $album->owner_id = $user->getIdentity();
      $album->title = $translate->_(ucfirst($type) . ' Photos');
      $album->type = $type;
      $album->search = 1;
      $album->save();
      // Authorizations
			$auth = Engine_Api::_()->authorization()->context;
			$auth->setAllowed($album, 'everyone', 'view',    true);
			$auth->setAllowed($album, 'everyone', 'comment', true);
    }
    return $album;
  }

	public function allowReviewRating() {
		if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.allow.review', 1))
		return true;

		return false;
	}

	public function isNewsAdmin($news = null, $privacy = null) {
	  $viewer = Engine_Api::_()->user()->getViewer();
	  if($viewer->getIdentity()) {
      if($viewer->level_id == 1 || $viewer->level_id == 2)
      return 1;
	  }
	  if(!isset($news->owner_id))
	  return 0;
	  $level_id = Engine_Api::_()->getItem('user', $news->owner_id)->level_id;
	  if($privacy == 'create') {
	   if($news->authorization()->isAllowed(null, 'video'))
	   return 1;
	   elseif($this->checkNewsAdmin($news))
	   return 1;
	   else
	   return 0;
	  }
	  elseif($privacy == 'music_create') {
	   if(Engine_Api::_()->authorization()->isAllowed('sesmusic_album', 'create'))
	   return 1;
	   elseif($this->checkNewsAdmin($news))
	   return 1;
	   else
	   return 0;
	  }
	  else {
			if(!Engine_Api::_()->authorization()->getPermission($level_id, 'sesnews_news', $privacy))
			return 0;
			else {
				$newsAdmin = $this->checkNewsAdmin($news);
				if($newsAdmin)
				return 1;
				else
				return 0;
			}
	  }
	}

	public function checkNewsAdmin($news = null) {
	   $viewerId = Engine_Api::_()->user()->getViewer()->getIdentity();
	   $roleTable = Engine_Api::_()->getDbTable('roles', 'sesnews');
	   return $roleTable->select()->from($roleTable->info('name'), 'role_id')
	                    ->where('news_id = ?', $news->news_id)
	                    ->where('user_id =?', $viewerId)
	                    ->query()
	                    ->fetchColumn();

	}

	public function deleteNews($sesnews = null){
		if(!$sesnews)
			return false;
		$owner_id = $sesnews->owner_id;
		//Delete album
		$sesnewsAlbumTable = Engine_Api::_()->getDbtable('albums', 'sesnews');
		$sesnewsAlbumTable->delete(array(
			'owner_id = ?' => $owner_id,
		));

		//Delete Photos
		$sesnewsPhotosTable = Engine_Api::_()->getDbtable('photos', 'sesnews');
		$sesnewsPhotosTable->delete(array(
			'user_id = ?' => $owner_id,
		));

		//Delete Favourites
		$sesnewsFavouritesTable = Engine_Api::_()->getDbtable('favourites', 'sesnews');
		$sesnewsFavouritesTable->delete(array(
			'user_id = ?' => $owner_id,
		));
		//Delete Reviews
		$sesnewsReviewsTable = Engine_Api::_()->getDbtable('reviews', 'sesnews');
		$sesnewsReviewsTable->delete(array(
			'owner_id = ?' => $owner_id,
		));
		//Delete Reviews Parameters
		$sesnewsReviewsParametersTable = Engine_Api::_()->getDbtable('parametervalues', 'sesnews');
		$sesnewsReviewsParametersTable->delete(array(
			'user_id = ?' => $owner_id,
		));
		//Delete Roles
		$sesnewsRolesTable = Engine_Api::_()->getDbtable('roles', 'sesnews');
		$sesnewsRolesTable->delete(array(
			'user_id = ?' => $owner_id,
		));

		$sesnews->delete();
	}

	public function checkNewsStatus() {
		$table = Engine_Api::_()->getDbTable('news', 'sesnews');
		$select = $table->select()
		->where('publish_date is NOT NULL AND publish_date <= "'.date('Y-m-d H:i:s').'"')
		->where('draft =?', 0)
        ->where('is_approved =?', 1)
		->where('is_publish =?', 0);
		$news = $table->fetchAll($select);
		if(engine_count($news) > 0) {
			foreach($news as $news) {
			  $sesnews = Engine_Api::_()->getItem('sesnews_news', $news->news_id);
				$action = Engine_Api::_()->getDbtable('actions', 'activity')->getActionsByObject($sesnews);
				if(engine_count($action->toArray()) <= 0) {
					$viewer = Engine_Api::_()->getItem('user', $news->owner_id);
					$action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $sesnews, 'sesnews_new');
					// make sure action exists before attaching the sesnews to the activity
					if( $action ) {
						Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $sesnews);
					}
					Engine_Api::_()->getItemTable('sesnews_news')->update(array('is_publish' => 1,'publish_date'=>date('Y-m-d H:i:s')), array('news_id = ?' => $news->news_id));
				}
			}
		}
	}

	public function getTotalReviews($newsId = null) {
	  $reviewTable = Engine_Api::_()->getDbTable('reviews', 'sesnews');
	  return $reviewTable->select()
	  ->from($reviewTable->info('name'), new Zend_Db_Expr('COUNT(review_id)'))
	  ->where('news_id =?', $newsId)
	  ->query()
	  ->fetchColumn();
	}
}
