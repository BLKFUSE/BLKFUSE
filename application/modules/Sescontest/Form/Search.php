<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Search.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Form_Search extends Engine_Form {

  public function init() {
     $restapi=Zend_Controller_Front::getInstance()->getRequest()->getParam( 'restApi', null );
     if ($restapi == 'Sesapi'){
          $coreContentTable = Engine_Api::_()->getDbTable('content', 'core');
        $coreContentTableName = $coreContentTable->info('name');
        $corePagesTable = Engine_Api::_()->getDbTable('pages', 'core');
        $corePagesTableName = $corePagesTable->info('name');
        $select = $corePagesTable->select()
            ->setIntegrityCheck(false)
            ->from($corePagesTable, null)
            ->where($coreContentTableName.'.name=?','sescontest.browse-search')
            ->joinLeft($coreContentTableName, $corePagesTableName . '.page_id = ' . $coreContentTableName . '.page_id',$coreContentTableName.'.content_id' )
            ->where($corePagesTableName.'.name = ?','sescontest_index_browse');
        $id = $select->query()->fetchColumn();
        $params = Engine_Api::_()->sescontest()->getWidgetParams($id);
     }else{
         $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $params = Engine_Api::_()->sescontest()->getWidgetParams($view->identity);
     }
    
    
    $show_criterias = $params['show_option'];
    $viewerId = Engine_Api::_()->user()->getViewer()->getIdentity();

    $this->setAttribs(array('id' => 'filter_form', 'class' => 'global_form_box'))->setMethod('GET');
    if ($restapi != 'Sesapi'){
        $this->setAction($view->url(array('module' => 'sescontest', 'controller' => 'index', 'action' => 'index'), 'default', true));
    }
    $viewer = Engine_Api::_()->user()->getViewer();
    if (engine_in_array('searchContestTitle', $show_criterias)) {
      $this->addElement('Text', 'search', array(
          'label' => 'Search Contests'
      ));
    }

    if (engine_in_array('browseBy', $show_criterias)) {
      switch ($params['default_search_type']) {
        case 'recentlySPcreated':
          $default_search_type = 'creation_date';
          break;
        case 'mostSPviewed':
          $default_search_type = 'view_count';
          break;
        case 'mostSPliked':
          $default_search_type = 'like_count';
          break;
        case 'mostSPcommented':
          $default_search_type = 'comment_count';
          break;
        case 'mostSPfavourite':
          $default_search_type = 'favourite_count';
          break;
        case 'mostSPfollower':
          $default_search_type = 'follow_count';
          break;
        case 'entrymintomax':
          $default_search_type = 'join_count ASC';
          break;
        case 'entrymaxtomin':
          $default_search_type = 'join_count DESC';
          break;
        case 'featured':
          $default_search_type = 'featured';
          break;
        case 'sponsored':
          $default_search_type = 'sponsored';
          break;
        case 'verified':
          $default_search_type = 'verified';
          break;
        case 'hot':
          $default_search_type = 'hot';
          break;
      }

      $filterOptions = array();
      foreach ($params['search_type'] as $key => $widgetOption) {
        if (is_numeric($key))
          $columnValue = $widgetOption;
        else
          $columnValue = $key;
        switch ($columnValue) {
          case 'recentlySPcreated':
            $columnValue = 'creation_date';
            $value = 'Newest';
            break;
          case 'mostSPviewed':
            $columnValue = 'view_count';
            $value = 'Views';
            break;
          case 'mostSPliked':
            $columnValue = 'like_count';
            $value = 'Likes';
            break;
          case 'mostSPcommented':
            $columnValue = 'comment_count';
            $value = 'Comments';
            break;
          case 'mostSPfavourite':
            $columnValue = 'favourite_count';
            $value = 'Favourites';
            break;
          case 'mostSPfollower':
            $columnValue = 'follow_count';
            $value = 'Followers';
            break;
          case 'entrymintomax':
            $columnValue = 'join_count ASC';
            $value = 'Entry Min to Max';
            break;
          case 'entrymaxtomin':
            $columnValue = 'join_count DESC';
            $value = 'Entry Max to Min';
            break;
          case 'featured':
            $columnValue = 'featured';
            $value = 'Featured';
            break;
          case 'sponsored':
            $columnValue = 'sponsored';
            $value = 'Sponsored';
            break;
          case 'verified':
            $columnValue = 'verified';
            $value = 'Verified';
            break;
          case 'hot':
            $columnValue = 'hot';
            $value = 'Hot';
            break;
        }
        $filterOptions[$columnValue] = ucwords($value);
      }
      $filterOptions = array('' => '') + $filterOptions;
      $this->addElement('Select', 'sort', array(
          'label' => 'Browse By',
          'multiOptions' => $filterOptions,
          'value' => $default_search_type,
      ));
    }
    if (engine_in_array('view', $show_criterias)) {
      $filterOptions = array();
      foreach ($params['criteria'] as $key => $viewOption) {
        if (!$viewerId && ($key == 1 || $key == 2 || $key == 3))
          continue;
        if (is_numeric($key))
          $columnValue = $viewOption;
        else
          $columnValue = $key;
        switch ($columnValue) {
          case '0':
            $value = 'All Contests';
            break;
          case 'ongoingSPupcomming':
            $value = 'Active & Coming Soon Contests';
            break;
          case 'ongoing':
            $value = 'Active Contests';
            break;
          case 'ended':
            $value = 'Ended Contests';
            break;
          case 'upcoming':
            $value = 'Coming Soon Contests';
            break;
          case '1':
            $value = 'Only My Friend\'s Contests';
            break;
          case '2':
            $value = 'Only My Network Contests';
            break;
          case '3':
            $value = 'Only My Contests';
            break;
          case 'today':
            $value = 'Today';
            break;
          case 'tomorrow':
            $value = 'Tomorrow';
            break;
          case 'week':
            $value = 'This Week';
            break;
          case 'nextweek':
            $value = 'Next Week';
            break;
          case 'month':
            $value = 'This Month';
            break;
        }
        $filterOptions[$columnValue] = ucwords($value);
      }
      $this->addElement('Select', 'show', array(
          'label' => 'Show',
          'multiOptions' => $filterOptions,
          'value' => isset($params['default_view_search_type'])?$params['default_view_search_type']:'',
      ));
    }
    if (engine_in_array('alphabet', $show_criterias)) {
      $alphabetArray[] = '';
      foreach (range('A', 'Z') as $char) {
        $alphabetArray[strtolower($char)] = $char;
      }
      $this->addElement('Select', 'alphabet', array(
          'label' => 'Alphabet:',
          'multiOptions' => $alphabetArray,
          'filters' => array(
              new Engine_Filter_Censor(),
              new Engine_Filter_HtmlSpecialChars(),
          ),
      ));
    }
    if (engine_in_array('mediaType', $show_criterias)) {
      $this->addElement('Select', 'media_type', array(
          'label' => 'Media Type',
          'multiOptions' => array('' => '', '1' => 'Text', '2' => 'Photo', '3' => 'Video', '4' => 'Music'),
      ));
    }
    if (engine_in_array('chooseDate', $show_criterias)) {
      if (isset($_GET['starttime']) && isset($_GET['endtime']))
        $dateRange = $_GET['starttime'] . '-' . $_GET['endtime'];
      else
        $dateRange = '';
	if ($restapi == 'Sesapi'){
       $dateRange = new Engine_Form_Element_Date('show_date_field');
           $dateRange->setLabel("Choose Date Range");
            $dateRange->setAllowEmpty(false);
            $dateRange->setRequired(true);  
            $this->addElement($dateRange);
	}else{
		 $this->addElement('Text', 'show_date_field', array(
          'label' => 'Choose Date Range',
          'value' => $dateRange,
      ));
	}
    }
    $categories = Engine_Api::_()->getDbtable('categories', 'sescontest')->getCategoriesAssoc();
    if (engine_count($categories) > 0 && engine_in_array('Categories', $show_criterias)) {
      $categories = array('0' => '') + $categories;
      $this->addElement('Select', 'category_id', array(
          'label' => 'Category',
          'multiOptions' => $categories,
          'onchange' => "showSubCategory(this.value);",
      ));
      //Add Element: Sub Category
      $this->addElement('Select', 'subcat_id', array(
          'label' => "2nd-level Category",
          'allowEmpty' => true,
          'required' => false,
          'multiOptions' => array('0' => ''),
          'registerInArrayValidator' => false,
          'onchange' => "showSubSubCategory(this.value);"
      ));
      //Add Element: Sub Sub Category
      $this->addElement('Select', 'subsubcat_id', array(
          'label' => "3rd-level Category",
          'allowEmpty' => true,
          'registerInArrayValidator' => false,
          'required' => false,
          'multiOptions' => array('0' => ''),
      ));
    }
    $this->addElement('Hidden', 'page', array(
        'order' => 100
    ));
    $this->addElement('Hidden', 'tag', array(
        'order' => 101
    ));
    $this->addElement('Button', 'submit', array(
        'label' => 'Search',
        'type' => 'submit'
    ));
  }

}
