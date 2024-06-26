<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Album
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Category.php 9747 2012-07-26 02:08:08Z john $
 * @author     Jung
 */

/**
 * @category   Application_Extensions
 * @package    Album
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Album_Model_Category extends Core_Model_Category
{
  // Properties
  protected $_route = 'album_general';
  protected $_searchTriggers = false;

  // General

  public function getTitle()
  {
    return $this->category_name;
  }

  public function getTable()
  {
    if( is_null($this->_table) )
    {
      $this->_table = Engine_Api::_()->getDbtable('categories', 'album');
    }

    return $this->_table;
  }


  public function getUsedCount(){
    $table  = Engine_Api::_()->getDbTable('albums', 'album');
    $rName = $table->info('name');
    $select = $table->select()
                    ->from($rName)
                    ->where($rName.'.category_id = ?', $this->category_id);
    $row = $table->fetchAll($select);
    $total = engine_count($row);
    return $total;
  }
  
  // Ownership

  public function isOwner($owner)
  {
    if( $owner instanceof Core_Model_Item_Abstract )
    {
      return ( $this->getIdentity() == $owner->getIdentity() && $this->getType() == $owner->getType() );
    }

    else if( is_array($owner) && engine_count($owner) === 2 )
    {
      return ( $this->getIdentity() == $owner[1] && $this->getType() == $owner[0] );
    }

    else if( is_numeric($owner) )
    {
      return ( $owner == $this->getIdentity() );
    }

    return false;
  }

  public function getOwner($recurseType = null)
  {
    return $this;
  }
}
