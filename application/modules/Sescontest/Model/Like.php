<?php
class Sescontest_Model_Like extends Core_Model_Like
{  
  protected $_type = "core_like";
  protected function _postInsert()
  {
    $likedItem = $this->getResource();
    $poster = $this->getPoster();
    $owner = $likedItem->getOwner();
    if( $owner->getType() == 'participant' || $owner->getIdentity() == $poster->getIdentity() ) {
      Engine_Hooks_Dispatcher::getInstance()
        ->callEvent('onCoreLikeCreateAfter', $this);
    }
    parent::_postInsert();
  }
}
