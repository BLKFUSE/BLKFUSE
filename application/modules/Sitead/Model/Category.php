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

class Sitead_Model_Category extends Core_Model_Category
{
  
  public function getTitle()
  {
    return $this->category_name;
  }
  
  public function getUsedCount()
  {
    $blogTable = Engine_Api::_()->getItemTable('blog');
    return $blogTable->select()
        ->from($blogTable, new Zend_Db_Expr('COUNT(blog_id)'))
        ->where('category_id = ?', $this->category_id)
        ->query()
        ->fetchColumn();
  }
}
