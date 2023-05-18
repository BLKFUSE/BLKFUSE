<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesthought_Widget_CategoryIconsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
    $this->view->allParams = $allParams = $this->_getAllParams();
		$this->view->gridblock = $gridblock = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '3');
    
    $this->getElement()->removeDecorator('Title');
    
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('categories', 'sesthought')->getCategory(array('criteria' => $allParams['criteria'], 'countThoughts' => true, 'limit' => $allParams['limit_data']));
    if (is_countable($paginator) && engine_count($paginator) == 0)
      return $this->setNoRender();
  }
}
