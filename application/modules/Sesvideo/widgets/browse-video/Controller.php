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
class Sesvideo_Widget_BrowseVideoController extends Engine_Content_Widget_Abstract {
  public function indexAction() {
    // Default option
    if (isset($_POST['params']))
      $params = $_POST['params'];
		if (isset($_POST['searchParams']) && $_POST['searchParams']) {
			if(engine_in_array($_POST['searchParams']))
				$searchArray = $_POST['searchParams'];
			elseif(is_string($_POST['searchParams']))
				parse_str($_POST['searchParams'], $searchArray);
		}
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
		$this->view->gridblock = $gridblock = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '4');
    $page = $this->_getParam('page', 1);
    $this->view->identityForWidget = $identityForWidget = isset($_POST['identity']) ? $_POST['identity'] : $this->view->identity;
    $this->view->loadOptionData = $loadOptionData = isset($params['pagging']) ? $params['pagging'] : $this->_getParam('pagging', 'auto_load');
    
    $this->view->socialshare_enable_listviewplusicon = $socialshare_enable_listviewplusicon = isset($params['socialshare_enable_listviewplusicon']) ? $params['socialshare_enable_listviewplusicon'] : $this->_getParam('socialshare_enable_listviewplusicon', 1);
    $this->view->socialshare_icon_listviewlimit = $socialshare_icon_listviewlimit = isset($params['socialshare_icon_listviewlimit']) ? $params['socialshare_icon_listviewlimit'] : $this->_getParam('socialshare_icon_listviewlimit', 2);
    
    $this->view->socialshare_enable_gridviewplusicon = $socialshare_enable_gridviewplusicon = isset($params['socialshare_enable_gridviewplusicon']) ? $params['socialshare_enable_gridviewplusicon'] : $this->_getParam('socialshare_enable_gridviewplusicon', 1);
    $this->view->socialshare_icon_gridviewlimit = $socialshare_icon_gridviewlimit = isset($params['socialshare_icon_gridviewlimit']) ? $params['socialshare_icon_gridviewlimit'] : $this->_getParam('socialshare_icon_gridviewlimit', 2);
    
    $this->view->socialshare_enable_pinviewplusicon = $socialshare_enable_pinviewplusicon = isset($params['socialshare_enable_pinviewplusicon']) ? $params['socialshare_enable_pinviewplusicon'] : $this->_getParam('socialshare_enable_pinviewplusicon', 1);
    $this->view->socialshare_icon_pinviewlimit = $socialshare_icon_pinviewlimit = isset($params['socialshare_icon_pinviewlimit']) ? $params['socialshare_icon_pinviewlimit'] : $this->_getParam('socialshare_icon_pinviewlimit', 2);
    
    
    $this->view->height_list = $defaultHeightList = isset($params['height_list']) ? $params['height_list'] : $this->_getParam('height_list','160');
    $this->view->width_list = $defaultWidthList = isset($params['width_list']) ? $params['width_list'] : $this->_getParam('width_list','140');
		$this->view->height_grid = $defaultHeightGrid = isset($params['height_grid']) ? $params['height_grid'] : $this->_getParam('height_grid','160');
    $this->view->width_grid = $defaultWidthGrid = isset($params['width_grid']) ? $params['width_grid'] : $this->_getParam('width_grid','140');
		$this->view->width_pinboard = $defaultWidthPinboard = isset($params['width_pinboard']) ? $params['width_pinboard'] : $this->_getParam('width_pinboard','300');
    $this->view->title_truncation_list = $title_truncation_list = isset($params['title_truncation_list']) ? $params['title_truncation_list'] : $this->_getParam('title_truncation_list', '100');
    $this->view->title_truncation_grid = $title_truncation_grid = isset($params['title_truncation_grid']) ? $params['title_truncation_grid'] : $this->_getParam('title_truncation_grid', '100');
		$this->view->title_truncation_pinboard = $title_truncation_pinboard = isset($params['title_truncation_pinboard']) ? $params['title_truncation_pinboard'] : $this->_getParam('title_truncation_pinboard', '100');
    $this->view->description_truncation_list = $description_truncation_list = isset($params['description_truncation_list']) ? $params['description_truncation_list'] : $this->_getParam('description_truncation_list', '100');
		$this->view->description_truncation_grid = $description_truncation_grid = isset($params['description_truncation_grid']) ? $params['description_truncation_grid'] : $this->_getParam('description_truncation_grid', '100');
		$this->view->description_truncation_pinboard = $description_truncation_pinboard = isset($params['description_truncation_pinboard']) ? $params['description_truncation_pinboard'] : $this->_getParam('description_truncation_pinboard', '100');
    $text =  isset($searchArray['search']) ? $searchArray['search'] : (!empty($params['search']) ? $params['search'] : (isset($_GET['search']) && ($_GET['search'] != '') ? $_GET['search'] : ''));
    //search data
    $value['category_id'] =  isset($searchArray['category_id']) ? $searchArray['category_id'] : (isset($_GET['category_id']) ? $_GET['category_id'] : (isset($params['category_id']) ? $params['category_id'] : ''));
    $value['sort'] = isset($searchArray['sort']) ? $searchArray['sort'] : (isset($_GET['sort']) ? $_GET['sort'] : (isset($params['sort']) ? $params['sort'] : $this->_getParam('sort', 'mostSPliked')));
    $value['subcat_id'] = isset($searchArray['subcat_id']) ? $searchArray['subcat_id'] :  (isset($_GET['subcat_id']) ? $_GET['subcat_id'] : (isset($params['subcat_id']) ? $params['subcat_id'] : ''));
    $value['subsubcat_id'] = isset($searchArray['subsubcat_id']) ? $searchArray['subsubcat_id'] : (isset($_GET['subsubcat_id']) ? $_GET['subsubcat_id'] : (isset($params['subsubcat_id']) ? $params['subsubcat_id'] : ''));    
    $value['alphabet'] = isset($_GET['alphabet']) ? $_GET['alphabet'] : (isset($params['alphabet']) ? $params['alphabet'] : '');
    $value['tag'] = isset($_GET['tag_id']) ? $_GET['tag_id'] : (isset($params['tag_id']) ? $params['tag_id'] : '');
    $show_criterias = isset($params['show_criterias']) ? $params['show_criterias'] : $this->_getParam('show_criteria', array('like', 'comment', 'rating', 'by', 'title', 'featuredLabel', 'sponsoredLabel', 'watchLater', 'category', 'description_list','description_grid','description_pinboard', 'duration', 'hotLabel', 'favouriteButton', 'playlistAdd', 'likeButton', 'socialSharing', 'view'));
		$value['location'] = isset($searchArray['location']) ? $searchArray['location'] :  (isset($_GET['location']) ? $_GET['location'] : (isset($params['location']) ?  $params['location'] : ''));
		$value['lat'] = isset($searchArray['lat']) ? $searchArray['lat'] :  (isset($_GET['lat']) ? $_GET['lat'] : (isset($params['lat']) ?  $params['lat'] : ''));
		$value['show'] = isset($searchArray['show']) ? $searchArray['show'] :  (isset($_GET['show']) ? $_GET['show'] : (isset($params['show']) ?  $params['show'] : ''));
		$value['lng'] = isset($searchArray['lng']) ? $searchArray['lng'] :  (isset($_GET['lng']) ? $_GET['lng'] : (isset($params['lng']) ?  $params['lng'] : ''));
		$value['miles'] = isset($searchArray['miles']) ? $searchArray['miles'] :  (isset($_GET['miles']) ? $_GET['miles'] : (isset($params['miles']) ?  $params['miles'] : ''));
		
		$value['country'] = isset($searchArray['country']) ? $searchArray['country'] :  (isset($_GET['country']) ? $_GET['country'] : (isset($params['country']) ?  $params['country'] : ''));
		$value['state'] = isset($searchArray['state']) ? $searchArray['state'] :  (isset($_GET['state']) ? $_GET['state'] : (isset($params['state']) ?  $params['state'] : ''));
		$value['city'] = isset($searchArray['city']) ? $searchArray['city'] :  (isset($_GET['city']) ? $_GET['city'] : (isset($params['city']) ?  $params['city'] : ''));
		$value['zip'] = isset($searchArray['zip']) ? $searchArray['zip'] :  (isset($_GET['zip']) ? $_GET['zip'] : (isset($params['zip']) ?  $params['zip'] : ''));
		
		
		$value['price'] = isset($searchArray['price']) ? $searchArray['price'] :  (isset($_GET['price']) ? $_GET['price'] : (isset($params['price']) ?  $params['price'] : ''));
		
		
		$value['user_id'] = isset($_GET['user_id']) ? $_GET['user_id'] : (isset($params['user_id']) ?  $params['user_id'] : '');
    if(is_array($show_criterias)){
			foreach ($show_criterias as $show_criteria)
      	$this->view->{$show_criteria . 'Active'} = $show_criteria;
		}
		$this->view->bothViewEnable = false;
    if (!$is_ajax) {
      $this->view->optionsEnable = $optionsEnable = $this->_getParam('enableTabs', array('list', 'grid', 'pinboard'));
      $view_type = $this->_getParam('openViewType', 'list');
      if (!empty($optionsEnable)) {
        $this->view->bothViewEnable = true;
      }
    }
    $sesvideo_browse = Zend_Registry::isRegistered('sesvideo_browse') ? Zend_Registry::get('sesvideo_browse') : null;
    if(empty($sesvideo_browse)) {
      return $this->setNoRender();
    }
		$this->view->limit_data_pinboard = $limit_data_pinboard = isset($params['limit_data_pinboard']) ? $params['limit_data_pinboard'] : $this->_getParam('limit_data_pinboard', '10');
		$this->view->limit_data_grid = $limit_data_grid = isset($params['limit_data_grid']) ? $params['limit_data_grid'] : $this->_getParam('limit_data_grid', '10');
		$this->view->limit_data_list = $limit_data_list = isset($params['limit_data_list']) ? $params['limit_data_list'] : $this->_getParam('limit_data_list', '10');
    $this->view->view_type = $view_type = (isset($_POST['type']) ? $_POST['type'] : (isset($params['view_type']) ? $params['view_type'] : $view_type));
		$this->view->viewTypeStyle = $viewTypeStyle = (isset($_POST['viewTypeStyle']) ? $_POST['viewTypeStyle'] : (isset($params['viewTypeStyle']) ? $params['viewTypeStyle'] : $this->_getParam('viewTypeStyle','fixed')));
		$limit_data = $this->view->{'limit_data_'.$view_type};
    $params = array('gridblock' => $gridblock, 'height_list' => $defaultHeightList, 'width_list' => $defaultWidthList,'height_grid' => $defaultHeightGrid, 'width_grid' => $defaultWidthGrid,'width_pinboard' => $defaultWidthPinboard,'limit_data_pinboard' => $limit_data_pinboard,'limit_data_list'=>$limit_data_list,'limit_data_grid'=>$limit_data_grid,'pagging' => $loadOptionData, 'show_criterias' => $show_criterias, 'view_type' => $view_type,  'description_truncation_list' => $description_truncation_list, 'title_truncation_list' => $title_truncation_list, 'title_truncation_grid' => $title_truncation_grid,'title_truncation_pinboard'=>$title_truncation_pinboard,'description_truncation_grid'=>$description_truncation_grid,'description_truncation_pinboard'=>$description_truncation_pinboard,'category_id' => $value['category_id'], 'subcat_id' => $value['subcat_id'], 'subsubcat_id' => $value['subsubcat_id'], 'sort' => $value['sort'], 'tag' => $value['tag'],'location'=>$value['location'],'show'=>$value['show'],'lat'=>$value['lat'],'lng'=>$value['lng'] ,'miles'=>$value['miles'],'price'=>$value['price'],'user_id'=>$value['user_id'],'viewTypeStyle'=>$viewTypeStyle, 'socialshare_enable_listviewplusicon' => $socialshare_enable_listviewplusicon, 'socialshare_icon_listviewlimit' => $socialshare_icon_listviewlimit, 'socialshare_enable_gridviewplusicon' => $socialshare_enable_gridviewplusicon, 'socialshare_icon_gridviewlimit' => $socialshare_icon_gridviewlimit, 'socialshare_enable_pinviewplusicon' => $socialshare_enable_pinviewplusicon, 'socialshare_icon_pinviewlimit' => $socialshare_icon_pinviewlimit,'country'=>$value['country'],'state'=>$value['state'],'city'=>$value['city'],'zip'=>$value['zip']);
    $this->view->loadMoreLink = $this->_getParam('openTab') != NULL ? true : false;
    $this->view->loadJs = true;
    // custom list grid view options
		$this->view->can_create = Engine_Api::_()->authorization()->isAllowed('video', null, 'create');
    if (!$is_ajax) {
      // Make form	
      if (!empty($_GET['tag_id'])) {
        $this->view->tag = Engine_Api::_()->getItem('core_tag', $_GET['tag_id'])->text;
      }
    }
    $this->view->category = $value['category_id'];
    $this->view->text = $value['text']  = stripslashes($text);
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
        case 'hot':
          $value['popularCol'] = 'is_hot';
					$value['fixedData'] = 'is_hot';
          break;
        case 'sponsored':
          $value['popularCol'] = 'is_sponsored';
					$value['fixedData'] = 'is_sponsored';
          break;
				case 'featured':
          $value['popularCol'] = 'is_featured';
					$value['fixedData'] = 'is_featured';
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
    $value['status'] = 1;
    $value['watchLater'] = true;
		$value['search'] = 1;
    $this->view->widgetName = 'browse-video';			
    // for some specific user video upload.
    $user_id = $this->_getParam('user',0);
    if ($user_id) {
      $values['user_id'] = $user_id;
    }

    // Video based video browse page work
    $type = '';
    $page_id = Engine_Api::_()->sesvideo()->getWidgetPageId($identityForWidget);
    if($page_id) {
      $pageName = Engine_Db_Table::getDefaultAdapter()->select()
              ->from('engine4_core_pages', 'name')
              ->where('page_id = ?', $page_id)
              ->limit(1)
              ->query()
              ->fetchColumn();
      if($pageName) {
        $explode = explode('sesvideo_index_', $pageName);
        if(is_numeric($explode[1])) {
          $type = Engine_Db_Table::getDefaultAdapter()->select()
                ->from('engine4_sesvideo_integrateothermodules', 'content_type')
                ->where('integrateothermodule_id = ?', $explode[1])
                ->limit(1)
                ->query()
                ->fetchColumn();
          if($type) {
            $value['parent_type'] = $type;
          }
        }
      }
    }
    $this->view->type = $type;
    // Video based video browse page work
    
    $params = array_merge($params, $value,array('search' => addslashes($text)));
    
    //Hashtag page work
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $value['moduleName'] = $request->getModuleName();
    if($value['moduleName'] == 'core') {
      unset($value['search']);
      //unset($value['text']);
      $value['tag'] = Engine_Api::_()->getDbTable('tags', 'core')->getTagId($text);
    }
    
    // Get videos
    $paginator = Engine_Api::_()->getDbTable('videos', 'sesvideo')->getVideo(array_merge($value,array('search'=>1)));
    $this->view->paginator = $paginator;
    // Set item count per page and current page number
    $paginator->setItemCountPerPage($limit_data);
    $this->view->page = $page;
    $paginator->setCurrentPageNumber($page);
		unset($params['text']);
    $this->view->params = $params;
    if ($is_ajax) {
      $this->getElement()->removeDecorator('Container');
    } else {
      // Do not render if nothing to show
      if ($paginator->getTotalItemCount() <= 0) {
        //silence
      }
    }
  }
}
