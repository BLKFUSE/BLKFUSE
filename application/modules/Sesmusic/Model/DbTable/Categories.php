<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Categories.php 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesmusic_Model_DbTable_Categories extends Engine_Db_Table {

  protected $_rowClass = 'Sesmusic_Model_Category';

   public function getMapping($params = array()) {
	   $data = array();
	   $counter = 0;
	   if(isset($params['category_id']) && $params['category_id'] == true){
		   $data[$counter] = 'category_id';
	   }
	   if(isset($params['profile_type']) && $params['profile_type'] == true){
		   $data[$counter] = 'profile_type';
	   }


    $select = $this->select()->from($this->info('name'), $data);
	if(isset($params['param']) && $params['param'] == true){
		$select->where('param = ?',$params['param'] );
	}
    $mapping = $this->fetchAll($select);
    if (!empty($mapping)) {
      return $mapping->toArray();
    }
    return null;
  }
  public function getCategory($params = array()) {

    $select = $this->select()
            ->from($this->info('name'), $params['column_name'])
            ->where('subcat_id = ?', 0)
            ->where('subsubcat_id = ?', 0);

    if (isset($params['category_id']) && !empty($params['category_id']))
      $select = $select->where('subcat_id = ?', $params['category_id']);

    if (isset($params['image']) && !empty($params['image']))
      $select = $select->where('cat_icon !=?', '');

    if (isset($params['param']) && !empty($params['param']))
      $select = $select->where('param =?', $params['param']);
    return $this->fetchAll($select);
  }
	public function orderNext($params = array()) {
		$category_select = $this->select()
				->from($this->info('name'), '*')
				->limit(1)
				->order('order DESC');
		if (isset($params['category_id'])) {
		  $category_select = $category_select->where('subcat_id = ?', 0)->where('subsubcat_id = ?', 0);
		} else if (isset($params['subsubcat_id'])) {
		  $category_select = $category_select->where('subsubcat_id = ?', $params['subsubcat_id']);
		} else if (isset($params['subcat_id'])) {
		  $category_select = $category_select->where('subcat_id = ?', $params['subcat_id']);
		}

		$category_select = $this->fetchRow($category_select);
		if (empty($category_select))
		  $order = 1;
		else
		  $order = $category_select['order'] + 1;
		return $order;
	}
	public function deleteCategory($params = array()) {
		$isValid = false;
		if (!empty($params)) {
		  if ($params->subcat_id != 0) {
			$Subcategory = $this->getModuleSubsubcategory(array('column_name' => '*', 'category_id' => $params->category_id));
			if (is_countable($Subcategory) && engine_count($Subcategory) > 0)
			  $isValid = false;
			else
			  $isValid = true;
		  } else if ($params->subsubcat_id != 0) {
			$isValid = true;
		  } else {
			$category = $this->getModuleSubcategory(array('column_name' => '*', 'category_id' => $params->category_id));
			if (is_countable($category) && engine_count($category) > 0)
			  $isValid = false;
			else
			  $isValid = true;
		  }
		}
		return $isValid;
	}
	public function slugExists($slug = '', $id = '') {

		if ($slug != '') {

		  $tableName = $this->info('name');
		  $select = $this->select()
				  ->from($tableName)
				  ->where($tableName . '.slug = ?', $slug);

		  if ($id != '') {
			$select = $select->where('category_id != ?', $id);
		  }

		  $row = $this->fetchRow($select);
		  if (empty($row)) {
			return true;
		  } else
			return false;
		}
		return false;
	  }

  public function getCategoriesAssoc($params = array()) {

    $select = $this->select()
            ->from($this->info('name'), array('category_id', 'category_name'))
            ->where('subcat_id = ?', 0)
            ->where('subsubcat_id = ?', 0);

    if (isset($params['module']))
      $select = $select->where('resource_type = ?', $params['module']);

    $select = $select->order('category_name ASC')
            ->query()
            ->fetchAll();

    $data = array();
    if (isset($params['module']) && $params['module'] == 'group') {
      $data[] = '';
    }

    foreach ($select as $category) {
      $data[$category['category_id']] = $category['category_name'];
    }

    return $data;
  }

  public function getColumnName($params = array()) {

    $select = $this->select()
            ->from($this->info('name'), $params['column_name']);

    if (isset($params['category_id']))
      $select = $select->where('category_id = ?', $params['category_id']);

    return $select = $select->query()->fetchColumn();
  }
	public function getMapId($categoryId = '', $type = 'profile_type') {
    $tableName = $this->info('name');
    if ($categoryId) {
      $category_map_id = $this->select()
              ->from($tableName, $type)
              ->where('category_id = ?', $categoryId);
      $category_map_id = $this->fetchAll($category_map_id);

      if (isset($category_map_id[0]) && isset($category_map_id[0]->{$type})) {
        return $category_map_id[0]->{$type};
      } else
        return 0;
    }
  }

  public function getSubCatMapId($subcategoryId = '', $type = 'profile_type') {
    $tableName = $this->info('name');
    if ($subcategoryId != '') {
      $category_map_id = $this->select()
              ->from($tableName, $type)
              ->where('category_id = ?', $subcategoryId);
      $category_map_id = $this->fetchAll($category_map_id);
      if (isset($category_map_id[0]->{$type})) {
        return $category_map_id[0]->{$type};
      } else
        return 0;
    }
  }

  public function getSubSubCatMapId($subsubcategoryId = '', $type = 'profile_type') {
    $tableName = $this->info('name');
    if ($subsubcategoryId != '') {
      $category_map_id = $this->select()
              ->from($tableName, $type)
              ->where('category_id = ?', $subsubcategoryId);
      $category_map_id = $this->fetchAll($category_map_id);
      if (isset($category_map_id[0]->{$type})) {
        return $category_map_id[0]->{$type};
      } else
        return 0;
    }
  }
  public function getModuleSubcategory($params = array()) {

    $select = $this->select()
            ->from($this->info('name'), $params['column_name']);

    if (isset($params['category_id']))
      $select = $select->where('subcat_id = ?', $params['category_id']);

    if (isset($params['param']))
      $select = $select->where('param = ?', $params['param']);

    return $this->fetchAll($select);
  }

  public function getModuleSubsubcategory($params = array()) {

    $select = $this->select()
            ->from($this->info('name'), $params['column_name']);

    if (isset($params['category_id']))
      $select = $select->where('subsubcat_id = ?', $params['category_id']);

    if (isset($params['param']))
      $select = $select->where('param = ?', $params['param']);


    return $this->fetchAll($select);
  }

}

