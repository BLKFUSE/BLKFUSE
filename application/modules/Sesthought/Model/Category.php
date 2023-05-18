<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Category.php  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesthought_Model_Category extends Core_Model_Item_Abstract {

  protected $_searchTriggers = false;

  //Get category title
  public function getTitle() {
		if(!$this)
			return 'Deleted Category';
    return $this->category_name;
  }

  public function getPhotoUrl($type = NULL) {
		if(!$this)
			return 'application/modules/Sesthought/externals/images/nophoto_thought_thumb_profile.png';
    $thumbnail = $this->thumbnail;
    if ($thumbnail) {
      $file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->thumbnail, $type);
			if($file)
      	return $file->map();
    }
		return 'application/modules/Sesthought/externals/images/nophoto_thought_thumb_profile.png';
  }
  public function getHref($params = array()) {
    if($this->subsubcat_id)
         return Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index'), 'sesthought_general').'?subcat_id='.$this->subsubcat_id.'&subsubcat_id='.$this->category_id;
  else if($this->subcat_id)
         return Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index'), 'sesthought_general').'?category_id='.$this->subcat_id.'&subcat_id='.$this->category_id;
   else
        return Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index'), 'sesthought_general').'?category_id='.$this->category_id;
  }
}
