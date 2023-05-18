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

class Egames_Widget_SimilarGamesController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    // $this->getElement()->removeDecorator('Title');
    
    $subject = Engine_Api::_()->core()->getSubject();
    $params = array();
    if(!$subject->category_id){
      return $this->setNoRender();
    }
    
    $params['category_id'] = $subject->category_id;
    $params['not_game_id'] = $subject->getIdentity();

    $params['page'] = $this->view->page = $this->_getParam("page",1);
    $params['limit'] = $this->_getParam("limit",5);
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable("games",'egames')->getGamesPaginator($params);
    if(!engine_count($paginator)){
      return $this->setNoRender();
    }
  }
}
