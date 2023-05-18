<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesvideo_Widget_VideoLocationController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
		if(! Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo_enable_location', 1))
			return setNoRender();
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
		if (isset($_POST['searchParams']) && $_POST['searchParams']) {
			if(engine_in_array($_POST['searchParams']))
				$searchArray = $_POST['searchParams'];
			elseif(is_string($_POST['searchParams']))
				parse_str($_POST['searchParams'], $searchArray);
		}
    if (!$is_ajax)
      $value['locationWidget'] = true;
    $value['category_id'] = isset($searchArray['category_id']) ? $searchArray['category_id'] : (isset($_GET['category_id']) ? $_GET['category_id'] : (isset($params['category_id']) ? $params['category_id'] : ''));
    $value['sort'] = isset($searchArray['sort']) ? $searchArray['sort'] : (isset($_GET['sort']) ? $_GET['sort'] : (isset($params['sort']) ? $params['sort'] : $this->_getParam('sort', 'mostSPliked')));
    $value['subcat_id'] = isset($searchArray['subcat_id']) ? $searchArray['subcat_id'] : (isset($_GET['subcat_id']) ? $_GET['subcat_id'] : (isset($params['subcat_id']) ? $params['subcat_id'] : ''));
    $value['subsubcat_id'] = isset($searchArray['subsubcat_id']) ? $searchArray['subsubcat_id'] : (isset($_GET['subsubcat_id']) ? $_GET['subsubcat_id'] : (isset($params['subsubcat_id']) ? $params['subsubcat_id'] : ''));
    $value['search'] = 1;
    $value['location'] = isset($searchArray['location']) ? $searchArray['location'] : (isset($_GET['location']) ? $_GET['location'] : (isset($params['location']) ? $params['location'] : ''));
    if($value['location']) {
      $this->view->lat = $value['lat'] = isset($searchArray['lat']) ? $searchArray['lat'] : (isset($_GET['lat']) ? $_GET['lat'] : (isset($params['lat']) ? $params['lat'] : $this->_getParam('lat', '26.9110600')));
      $this->view->lng = $value['lng'] = isset($searchArray['lng']) ? $searchArray['lng'] : (isset($_GET['lng']) ? $_GET['lng'] : (isset($params['lng']) ? $params['lng'] : $this->_getParam('lng', '75.7373560')));
    } else {
      $this->view->lat = '26.9110600';
      $this->view->lng = '75.7373560';
    }
    $value['show'] = isset($searchArray['show']) ? $searchArray['show'] : (isset($_GET['show']) ? $_GET['show'] : (isset($params['show']) ? $params['show'] : ''));
    
    $value['miles'] = isset($searchArray['miles']) ? $searchArray['miles'] : (isset($_GET['miles']) ? $_GET['miles'] : (isset($params['miles']) ? $params['miles'] : $this->_getParam('miles', '1000')));
    $value['text'] = $text = isset($searchArray['search']) ? $searchArray['search'] : (!empty($params['search']) ? $params['search'] : (isset($_GET['search']) && ($_GET['search'] != '') ? $_GET['search'] : ''));
    if (isset($value['sort']) && $value['sort'] != '') {
      $value['getParamSort'] = str_replace('SP', '_', $value['sort']);
    } else
      $value['getParamSort'] = 'creation_date';
    if (isset($value['getParamSort'])) {
      switch ($value['getParamSort']) {
        case 'most_viewed':
          $value['popularCol'] = 'view_count';
          break;
        case 'most_liked':
          $value['popularCol'] = 'like_count';
          break;
        case 'most_commented':
          $value['popularCol'] = 'comment_count';
          break;
        case 'most_favourite':
          $value['popularCol'] = 'favourite_count';
          break;
        case 'featured':
          $value['popularCol'] = 'is_featured';
          break;
        case 'hot':
          $value['popularCol'] = 'is_hot';
          break;
        case 'sponsored':
          $value['popularCol'] = 'is_sponsored';
          break;
        case 'most_rated':
          $value['popularCol'] = 'rating';
          break;
        case 'recently_created':
        default:
          $value['popularCol'] = 'creation_date';
          break;
      }
    }
    $this->view->paginator = $paginator = Engine_Api::_()->getDbtable('videos', 'sesvideo')->getVideo($value, true);
    $paginator->setItemCountPerPage($this->_getParam('itemCountPerPage', 100));
    $paginator->setCurrentPageNumber(1);
    $this->view->widgetName = 'video-location';
    if ($is_ajax)
      $this->makeAjaxCallData($paginator);
  }

  function makeAjaxCallData($paginator) {
    if ($paginator->getTotalItemCount() > 0) {
      $array = array();
      $counter = 0;
			$allowRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.video.rating',1);
			$allowShowPreviousRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.ratevideo.show',1);
			if($allowRating == 0){
				if($allowShowPreviousRating == 0)
					$ratingShow = false;
				 else
					$ratingShow = true;
			}else
				$ratingShow = true;
			$likeButton = $favouriteButton = $addToplaylist = '';
			
    foreach ($paginator as $item) {
			if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0){
				$likeButton = '<a href="javascript:;" data-url="'.$item->getIdentity().'" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesvideo_like_sesvideo_video "> <i class="fa fa-thumbs-up"></i><span>'.$item->like_count.'</span></a>';
				$favouriteButton = '<a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesvideo_favourite_sesvideo_video " data-url="'.$item->getIdentity().'"><i class="fa fa-heart"></i><span>'.$item->favourite_count.'</span></a>';
                if(Engine_Api::_()->authorization()->getPermission(Engine_Api::_()->user()->getViewer()->level_id, 'video', 'addplayl_video')) {
                    $addToplaylist = '<a href="javascript:;" class="sesbasic_icon_btn sesvideo_add_playlist" onclick="opensmoothboxurl(' . "'" . $this->view->url(array('action' => 'add', 'module' => 'sesvideo', 'controller' => 'playlist', 'video_id' => $item->video_id), 'default', true) . "'" . ')" title="' . $this->view->translate('Add To Playlist') . '" data-url="' . $item->getIdentity() . '"><i class="fa fa-plus"></i></a>';
                }
			}
		$user = Engine_Api::_()->getItem('user',$item->owner_id);
		$owner = $item->getOwner();
		$ratings = '';
		$urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref());
		if($ratingShow && isset($item->rating) && $item->rating > 0 ): 
		$ratings =   '<span  title="'.$this->view->translate(array('%s rating', '%s ratings', round($item->rating,1)), $this->view->locale()->toNumber(round($item->rating,1))).'"><i class="far fa-star"></i>'. round($item->rating,1).'/5'.'</span>';
		endif;
		$owner =  '<div class="sesvideo_grid_date sesvideo_list_stats sesbasic_text_light"><span><i class="far fa-user"></i>'.$this->view->translate("by").$this->view->htmlLink($owner->getHref(),$owner->getTitle() ).'</span></div>';
	
	 $stats = '<div class="sesbasic_largemap_stats sesvideo_list_stats sesbasic_clearfix"><span title="'.$this->view->translate(array('%s like', '%s likes', $item->like_count), $this->view->locale()->toNumber($item->like_count)).'"><i class="sesbasic_icon_like_o"></i>'.$item->like_count.'</span> <span title="'.$this->view->translate(array('%s comment', '%s comments', $item->comment_count), $this->view->locale()->toNumber($item->comment_count)).'"><i class="sesbasic_icon_comment_o"></i>'.$item->comment_count.'</span><span title="'.$this->view->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->view->locale()->toNumber($item->favourite_count)).'"><i class="sesbasic_icon_favourite_o"></i>'. $item->favourite_count.'</span><span title="'. $this->view->translate(array('%s view', '%s views', $item->view_count), $this->view->locale()->toNumber($item->view_count)).'"><i class="sesbasic_icon_view"></i>'.$item->view_count.'</span>'.$ratings.'</div>';
	
	$socialshare = '<div class="sesbasic_largemap_btns sesvideo_list_btns">';
	
	$socialshare .= $this->view->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'param' => 'feed'));

	$socialshare .= $likeButton.$favouriteButton.$addToplaylist.'</div>';
	
        $array[$counter]['id'] = $item->video_id;
				$array[$counter]['owner'] = $owner;
				$array[$counter]['stats'] = $stats;
				$array[$counter]['socialshare'] = $socialshare;
        $array[$counter]['lat'] = $item['lat'];
        $array[$counter]['lng'] = $item['lng'];
        $array[$counter]['iframe_url'] = $item->getRichContent(true, array(), true);
        $array[$counter]['image_url'] = $item->getPhotoUrl();
        $array[$counter]['title'] = '<a href="'.$item->getHref().'">'.$item['title'].'</a>';
        $array[$counter]['description'] = $item['description'];
        $counter++;
      }
      echo json_encode($array);die;
    } else {
      echo false;
      die;
    }
  }

}
