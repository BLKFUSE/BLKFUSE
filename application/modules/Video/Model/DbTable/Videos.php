<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Video
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Videos.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Video
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Video_Model_DbTable_Videos extends Engine_Db_Table
{
  protected $_rowClass = "Video_Model_Video";
    
  public function isVideoExists($category_id, $categoryType = 'category_id') {
    return $this->select()
      ->from($this->info('name'), 'video_id')
      ->where($categoryType . ' = ?', $category_id)
      ->query()
      ->fetchColumn();
  }
}
