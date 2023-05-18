<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesfeedgif
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: IndexController.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesfeedgif_IndexController extends Core_Controller_Action_Standard
{
  public function gifAction() {
    $this->view->edit = $this->_getParam('edit',false);
    $this->renderScript('_gif.tpl');
  }
  
  public function searchGifAction() {
  
    $page = $this->_getParam('page', 1);
    $text = $this->_getParam('text','ha');
    $this->view->is_ajax = $this->_getParam('is_ajax', 1);
    $this->view->searchvalue = $this->_getParam('searchvalue', 0);
    $paginator = $this->view->paginator = Engine_Api::_()->getDbTable('images', 'sesfeedgif')->searchGif($text);
		$paginator->setItemCountPerPage(10);
		$this->view->page = $page ;
		$paginator->setCurrentPageNumber($page);
  }
}
