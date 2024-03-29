<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbasic
 * @package    Sesbasic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php 2015-10-28 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sesbasic_Widget_ColumnLayoutWidthController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
	  $layoutColumnWidthType = $this->_getParam('layoutColumnWidthType', '%');
	  $columnWidth = $this->_getParam('columnWidth', null);
	  $this->view->finalValue = $columnWidth . $layoutColumnWidthType;
  }

}
