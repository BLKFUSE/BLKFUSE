<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Elivestreaming
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Elivehost.php 2019-10-01 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Elivestreaming_Model_Elivehost extends Core_Model_Item_Abstract
{
  protected $_searchTriggers = false;
  protected $_type = 'elivehost';

  public function getTitle()
  {
    return $this->name;
  }
  function getParent($recurseType = NULL){
		return 	Engine_Api::_()->getItem('user', $this->user_id);
	}
  public function getHref()
  {
      return "javascript:void(0)";
  }
    public function getRichContent()
    {
      $class  = "";
      if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('eweblivestreaming')){
        $class = "elivestreaming_data_a";
      }

        $data = '<span class="feed_attachment_elivehost">
                    <div>                                                                                            
                        <a href="javascript:;" class="'.$class.'" data-hostid="'.$this->elivehost_id.'" data-user="'.$this->user_id.'" data-action="'.$this->action_id.'" data-story="'.$this->story_id.'"><img src="'.$this->getPhotoUrl().'" class="_sesactpinimg thumb_main item_photo_elivehost "></a>                 
                        <div style="display: none;">
                            <div class="feed_item_link_title">
                              <a href="javascript:;">Gene Brooks</a>                        
                            </div>
                            <div class="feed_item_link_desc"></div>
                        </div>
                    </div>
                  </span>';
        return $data;
    }

    public function getPhotoUrl($type = NULL)
  {
    $defaultPhoto = '';
    if (!$defaultPhoto) {
      $defaultPhoto = 'application/modules/Elivestreaming/externals/images/elive_streaming_thumb.jpg';
    }
    return $defaultPhoto;
  }
  protected function _delete()
  {
    if ($this->_disableHooks)
      return;
    $notificationReceiverTable = Engine_Api::_()->getDbTable('notificationreceivers', 'elivestreaming');
    $notificationReceivers = $notificationReceiverTable->getAllnotificationReceivers(array('elivehost_id' => $this->getIdentity()));
    $allNotificationReceivers = $notificationReceiverTable->fetchAll($notificationReceivers);
    foreach ($allNotificationReceivers as $NotificationReceiver) {
      $NotificationReceiver->delete();
    }
  }
  /**
   * Gets a proxy object for the tags handler
   *
   * @return Engine_ProxyObject
   * */
  public function tags()
  {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbTable('tags', 'core'));
  }

  /**
   * Gets a proxy object for the comment handler
   *
   * @return Engine_ProxyObject
   * */
  public function comments()
  {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbTable('comments', 'core'));
  }

  /**
   * Gets a proxy object for the like handler
   *
   * @return Engine_ProxyObject
   * */
  public function likes()
  {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbTable('likes', 'core'));
  }
}
