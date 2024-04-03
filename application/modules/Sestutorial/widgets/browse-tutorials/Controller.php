<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */



class Sestutorial_Widget_BrowseTutorialsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
  
    if (isset($_POST['params']))
      $params = json_decode($_POST['params'], true);
      
    $this->view->viewmore = $this->_getParam('viewmore', 0);
    $this->view->gridblock = $gridblock = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '6');
    
    $this->view->paginationType = $paginationType = $this->_getParam('paginationType', 1);
    
    $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : (isset($params['category_id']) ? $params['category_id'] : '');
    
    $subcat_id = isset($_GET['subcat_id']) ? $_GET['subcat_id'] : (isset($params['subcat_id']) ? $params['subcat_id'] : '');
    $tag = isset($_GET['tag_id']) ? $_GET['tag_id'] : (isset($params['tag_id']) ? $params['tag_id'] : '');
    $subsubcat_id = isset($_GET['subsubcat_id']) ? $_GET['subsubcat_id'] : (isset($params['subsubcat_id']) ? $params['subsubcat_id'] : '');
    
    $popularity = isset($_GET['popularity']) ? $_GET['popularity'] : (isset($params['popularity']) ? $params['popularity'] : '');
    
    $title = isset($_GET['title_name']) ? $_GET['title_name'] : (isset($params['title_name']) ? $params['title_name'] : '');
    
    if ($this->view->viewmore)
      $this->getElement()->removeDecorator('Container');

    $limit = isset($params['itemCount']) ? $params['itemCount'] : $this->_getParam('limitdatatutorial', 10);

    $this->view->gridblockheight = $gridblockheight = isset($params['gridblockheight']) ? $params['gridblockheight'] : $this->_getParam('gridblockheight', 250);
		
		$this->view->viewtype = $viewtype = isset($params['viewtype']) ? $params['viewtype'] : $this->_getParam('viewtype', 'listview');
		
		$this->view->showicons = $showicons = isset($params['showicons']) ? $params['showicons'] : $this->_getParam('showicons', 1);
		
		$this->view->tutorialtitlelimit = $tutorialtitlelimit = isset($params['tutorialtitlelimit']) ? $params['tutorialtitlelimit'] : $this->_getParam('tutorialtitlelimit', 60);
		
		$this->view->tutorialdescriptionlimit = $tutorialdescriptionlimit = isset($params['tutorialdescriptionlimit']) ? $params['tutorialtitlelimit'] : $this->_getParam('tutorialdescriptionlimit', 200);
		
		$this->view->showinformation = $showinformation = isset($params['showinformation']) ? $params['showinformation'] : $this->_getParam('showinformation', array('likecount', 'viewcount', 'commentcount', 'ratingcount', 'description', 'readmorelink'));

		$this->view->all_params = $values = array('gridblockheight' => $gridblockheight, 'viewtype' => $viewtype, 'showinformation' => $showinformation, 'category_id' => $category_id, 'subcat_id' => $subcat_id, 'subsubcat_id' => $subsubcat_id, 'alphabet' => $alphabet, 'title' => $title, 'showPhoto' => $showPhoto, 'order' => $popularity, 'itemCount' => $limit, 'viewType' => $viewType, 'tag' => $tag, 'tutorialtitlelimit' => $tutorialtitlelimit, 'tutorialdescriptionlimit' => $tutorialdescriptionlimit, 'showinformation' => $showinformation, 'showicons' => $showicons, 'itemCount' => $limit, 'category_id' => $category_id, 'subcat_id' => $subcat_id, 'subsubcat_id' => $subsubcat_id);
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getTutorialPaginator($values);
    $paginator->setItemCountPerPage($limit);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    $this->view->count = $paginator->getTotalItemCount();
  }
}
