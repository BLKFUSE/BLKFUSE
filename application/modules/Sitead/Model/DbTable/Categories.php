<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitead_Model_DbTable_Categories extends Engine_Db_Table
{
  protected $_rowClass = 'Sitead_Model_Category';
  
  public function getCategoriesAssoc()
  {
    $stmt = $this->select()
        ->from($this, array('category_id', 'category_name'))
        ->order('category_id ASC')
        ->query();
    
    $data = array();
    $data['0'] = 'No Button';
    foreach( $stmt->fetchAll() as $category ) {
      $data[$category['category_name']] = $category['category_name'];
    }
    
    return $data;
  }
  
}
