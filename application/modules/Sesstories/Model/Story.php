<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Story.php 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesstories_Model_Story extends Core_Model_Item_Abstract {
  protected $_searchTriggers = false;
  protected $_type = 'sesstories_story';

  public function likes()
  {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('likes', 'core'));
  }
  public function getHref(){
      return "sesstories/index/view/story_id/".$this->getIdentity();
  }
  protected function _delete()
  {
//
//    // delete poll options
//    Engine_Api::_()->getDbtable('stories', 'sesstories')->delete(array(
//      'story_id = ?' => $this->getIdentity(),
//    ));

    parent::_delete();
  }
}
