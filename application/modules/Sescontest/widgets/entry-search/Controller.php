<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Widget_EntrySearchController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $widgetId = (array) $this->_getParam('widget_id');
    $params = Engine_Api::_()->sescontest()->getWidgetParams($widgetId);
    $widgetOptions = $params['sorting'];
    $this->view->form = $searchForm = new Sescontest_Form_EntrySearch();

    $filterOptions = array();
    foreach ($widgetOptions as $key => $widgetOption) {
      if (is_numeric($key))
        $columnValue = $widgetOption;
      else
        $columnValue = $key;
      $value = str_replace(array('SP', ''), array(' ', ' '), $columnValue);
      switch ($columnValue) {
        case 'Newest':
          $columnValue = 'creation_date';
          break;
        case 'Oldest':
          $columnValue = 'old';
          break;
        case 'mostSPViewed':
          $columnValue = 'view_count';
          break;
        case 'mostSPvoted':
          $columnValue = 'vote_count';
          break;
        case 'mostSPliked':
          $columnValue = 'like_count';
          break;
        case 'mostSPcommented':
          $columnValue = 'comment_count';
          break;
        case 'mostSPfavorite':
          $columnValue = 'favourite_count';
          break;
      }
      $filterOptions[$columnValue] = ucwords($value);
    }
    $filterOptions = array('' => '') + $filterOptions;
    $searchForm->sort->setMultiOptions($filterOptions);

    $request = Zend_Controller_Front::getInstance()->getRequest();
    $searchForm->setMethod('get')->populate($request->getParams());
  }

}
