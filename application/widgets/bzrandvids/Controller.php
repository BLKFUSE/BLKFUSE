<?php
/**
 * SocialEngine
 *
 * @category   Application_Widget
 * @package    BryZar Random Videos
 * @copyright  Copyright 2018 - BryZar
 * @license    https://www.bryzar.com/terms
 * @author     data66, BryZar/ScriptTechs
 * 
 */

class Widget_BzrandvidsController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
    // Horizontal or vertical alignment
        $this->view->bzAlign = $bzAlign = $this->_getParam('bzAlign', 1);
    // video text length
        $this->view->bzDesVlength = $bzDesVlength = $this->_getParam('bzDesVlength', 300);
    //Get videos
        $bzVidCat = $this->_getParam('bzVidCat');
        if ($bzVidCat >= 1){
        $table = Engine_Api::_()->getItemTable('video');
        $select = $table->select()      
            ->where('search = ?', 1)
            ->where('view_privacy = ?', "everyone")
            ->where('networks IS NULL')
            //->where('draft != ?', 1)    
            ->where('category_id = ?', $bzVidCat)    
            ->order('RAND()');
        }
        else{
            $table = Engine_Api::_()->getItemTable('video');
            $select = $table->select()      
            ->where('search = ?', 1)
            ->where('view_privacy = ?', "everyone")
            ->where('networks IS NULL')
            //->where('draft != ?', 1)    
            ->order('RAND()');
        }
        $this->view->paginator = $paginator = Zend_Paginator::factory($select);

        // Set item count per page and current page number
        $bzVidView = $this->_getParam('bzVidView', 4);
        $paginator->setItemCountPerPage($this->_getParam('itemCountPerPage', $bzVidView));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
   
        // Do not render if nothing to show
        if ($paginator->getTotalItemCount() <= 0) {
            return $this->setNoRender();
        }
  }
}