<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egames_Widget_BrowseGamesController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->getElement()->removeDecorator('Title');
    if(isset($_POST['searchParamsGames']) && $_POST['searchParamsGames'])
			parse_str($_POST['searchParamsGames'], $searchArray);

    $params = array();
    if(!empty($searchArray)){
      $params = array_merge($params,$searchArray);
    }else if(!empty($_GET)){
      $params = array_merge($params,$_GET);
      unset($params['rewrite']);
      $this->view->searchData = http_build_query($params);
    }
    $this->view->canCreate = Engine_Api::_()->authorization()->isAllowed('egames_game', null, 'create');
    if(engine_count($params)){
      $this->view->search = true;
    }
    $params['page'] = $this->view->page = $this->_getParam("page",1);
    $params['limit'] = $this->_getParam("limit",10);
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable("games",'egames')->getGamesPaginator($params);

    $this->view->viewmore = $this->_getParam("viewmore",false);

    if ($this->view->viewmore)
      $this->getElement()->removeDecorator('Container');
  }
}
