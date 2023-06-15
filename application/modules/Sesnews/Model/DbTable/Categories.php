<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Categories.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesnews_Model_DbTable_Categories extends Engine_Db_Table {

  protected $_rowClass = 'Sesnews_Model_Category';

  public function getCategoriesAssoc($params = array()) {
    $stmt = $this->select()
            ->from($this, array('category_id', 'category_name'))
            ->where('subcat_id = ?', 0)
            ->where('subsubcat_id = ?', 0);
    $viewerId = Engine_Api::_()->user()->getViewer()->getIdentity();
    if ($viewerId && isset($params['member_levels']) && $params['member_levels'] == 1) {
      $levelId = Engine_Api::_()->user()->getViewer()->level_id;
      $stmt->where('CONCAT(engine4_sesnews_categories.member_levels," ") LIKE "%' . $levelId . '%"');
    }
    $stmt = $stmt->order('order DESC')
            ->query()
            ->fetchAll();
    $data = array();
    if (isset($params['module']) && $params['module'] == 'group') {
      $data[] = '';
    }
    foreach ($stmt as $category) {
      $data[$category['category_id']] = $category['category_name'];
    }
    return $data;
  }

  public function getUserCategoriesAssoc($user) {
    if ($user instanceof User_Model_User) {
      $user = $user->getIdentity();
    } else if (!is_numeric($user)) {
      return array();
    }

    $stmt = $this->getAdapter()
            ->select()
            ->from('engine4_sesnews_categories', array('category_id', 'category_name'))
            ->joinLeft('engine4_sesnews_news', "engine4_sesnews_news.category_id = engine4_sesnews_categories.category_id")
            ->group("engine4_sesnews_categories.category_id")
            ->where('engine4_sesnews_news.owner_id = ?', $user)
            ->where('engine4_sesnews_news.draft = ?', "0")
            ->order('category_name ASC')
            ->query();

    $data = array();
    foreach ($stmt->fetchAll() as $category) {
      $data[$category['category_id']] = $category['category_name'];
    }

    return $data;
  }

  public function getCategoryId($slug = null) {
    if ($slug) {
      $tableName = $this->info('name');
      $select = $this->select()
              ->from($tableName)
              ->where($tableName . '.slug = ?', $slug);
      $row = $this->fetchRow($select);
      if (empty($row)) {
        $category_id = $slug;
      } else
        $category_id = $row->category_id;
    }
    if (isset($category_id))
      return $category_id;
    else
      return;
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
      }else if ($params->subsubcat_id != 0) {
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

  public function getMapping($params = array()) {
    $select = $this->select()->from($this->info('name'), $params);
    $mapping = $this->fetchAll($select);
    if (!empty($mapping)) {
      return $mapping->toArray();
    }
    return null;
  }

  public function getMapId($categoryId = '') {
    $tableName = $this->info('name');
    if ($categoryId) {
      $category_map_id = $this->select()
              ->from($tableName, 'profile_type')
              ->where('category_id = ?', $categoryId)
              ->order('order DESC');
      $category_map_id = $this->fetchAll($category_map_id);
      if (isset($category_map_id[0]->profile_type)) {
        return $category_map_id[0]->profile_type;
      } else
        return 0;
    }
  }

  public function getSubCatMapId($subcategoryId = '') {
    $tableName = $this->info('name');
    if ($subcategoryId) {
      $category_map_id = $this->select()
              ->from($tableName, 'profile_type')
              ->where('category_id = ?', $subcategoryId)
              ->order('order DESC');
      $category_map_id = $this->fetchAll($category_map_id);
      if (isset($category_map_id[0]->profile_type)) {
        return $category_map_id[0]->profile_type;
      } else
        return 0;
    }
  }

  public function getSubSubCatMapId($subsubcategoryId = '') {
    $tableName = $this->info('name');
    if ($subsubcategoryId) {
      $category_map_id = $this->select()
              ->from($tableName, 'profile_type')
              ->where('category_id = ?', $subsubcategoryId)
              ->order('order DESC');
      $category_map_id = $this->fetchAll($category_map_id);
      if (isset($category_map_id[0]->profile_type)) {
        return $category_map_id[0]->profile_type;
      } else
        return 0;
    }
  }

  public function getColumnName($params = array()) {
    $tableName = $this->info('name');
    $category_select = $this->select()
            ->from($tableName, $params['column_name']);
    if (isset($params['category_id']))
      $category_select = $category_select->where('category_id = ?', $params['category_id']);
    if (isset($params['subcat_id']))
      $category_select = $category_select->where('subcat_id = ?', $params['subcat_id']);
    return $category_select = $category_select->query()->fetchColumn();
  }

  public function getModuleSubcategory($params = array()) {
    $tableName = $this->info('name');
    $category_select = $this->select()->from($tableName, $params['column_name']);
    if (isset($params['category_id']))
      $category_select = $category_select->where($tableName . '.subcat_id = ?', $params['category_id']);
    if (isset($params['countNews'])) {
      $sesnewsTable = Engine_Api::_()->getDbTable('news', 'sesnews')->info('name');
      $category_select = $category_select->setIntegrityCheck(false);
      $category_select = $category_select->joinLeft($sesnewsTable, "$sesnewsTable.subcat_id=$tableName.category_id", array("total_news_categories" => "COUNT(news_id)"));
      $category_select = $category_select->group("$tableName.category_id");
      $category_select->order('total_news_categories DESC');
    }
    $category_select = $category_select->order('order DESC');
    return $this->fetchAll($category_select);
  }

  public function getModuleCategory($params = array()) {
    $category_select = $this->select()
            ->from($this->info('name'), $params['column_name']);
    if (isset($params['category_id']))
      $category_select = $category_select->where('category_id = ?', $params['category_id']);
    $category_select = $category_select->order('order DESC');
    return $this->fetchAll($category_select);
  }

  public function getModuleSubsubcategory($params = array()) {
    $tableName = $this->info('name');
    $category_select = $this->select()
            ->from($this->info('name'), $params['column_name']);
    if (isset($params['category_id']))
      $category_select = $category_select->where($tableName . '.subsubcat_id = ?', $params['category_id']);
    if (isset($params['countNews'])) {
      $sesnewsTable = Engine_Api::_()->getDbTable('news', 'sesnews')->info('name');
      $category_select = $category_select->setIntegrityCheck(false);
      $category_select = $category_select->joinLeft($sesnewsTable, "$sesnewsTable.subsubcat_id=$tableName.category_id", array("total_news_categories" => "COUNT(news_id)"));

      $category_select = $category_select->group("$tableName.category_id");
      $category_select->order('total_news_categories DESC');
    }
    $category_select = $category_select->order('order DESC');
    return $this->fetchAll($category_select);
  }

  public function getCategory($params = array(), $customParams = array(), $searchParams = array()) {

    if (isset($params['column_name']))
      $column = $params['column_name'];
    else
      $column = '*';

    $tableName = $this->info('name');
    $category_select = $this->select()
            ->from($tableName, $column)
            ->where($tableName . '.subcat_id = ?', 0)
            ->where($tableName . '.subsubcat_id = ?', 0);
    if (isset($params['criteria']) && $params['criteria'] == 'alphabetical')
      $category_select->order($tableName . '.category_name');

    $newsTable = Engine_Api::_()->getDbTable('news', 'sesnews')->info('name');

    if ((isset($params['hasNews']) && $params['hasNews']) || isset($params['countNews']) || (isset($params['criteria']) && $params['criteria'] == 'most_news')) {
      $take = true;
      $category_select = $category_select->setIntegrityCheck(false);
      $category_select = $category_select->joinLeft($newsTable, "$newsTable.category_id=$tableName.category_id", array("total_news_categories" => "COUNT(news_id)"));
      if (empty($params['countNews'])) {
        $category_select = $category_select->having("COUNT($newsTable.category_id) > 0")
                ->group("$newsTable.category_id");
        if (isset($params['newsDesc'])) {
          if ($params['criteria'] && $params['criteria'] == 'most_news') {
            $category_select->order('total_news_categories DESC');
          }
        }
      } else {
        $category_select = $category_select->group("$tableName.category_id");
        if ($params['criteria'] && $params['criteria'] == 'most_news') {
          $category_select->order('total_news_categories DESC');
        }
      }
    }

    if (isset($params['newsRequired'])) {
      if (empty($take)) {
        $category_select = $category_select->setIntegrityCheck(false);
        $category_select = $category_select->joinLeft($newsTable, "$newsTable.category_id=$tableName.category_id", array("total_news_categories" => "COUNT(news_id)"));
      }
      $category_select = $category_select->having("COUNT($newsTable.category_id) > 0")
              ->group("$newsTable.category_id");
    }

    if (isset($params['image']) && !empty($params['image']))
      $category_select = $category_select->where($tableName . '.cat_icon !=?', '');
    // if (isset($params['param']) && !empty($params['param']))
    //  $category_select = $category_select->where('param =?', $params['param']);
    $category_select = $category_select->order('order DESC');
    if (isset($params['category_id']) && !empty($params['category_id']))
      $category_select = $category_select->where($tableName . '.category_id = ?', $params['category_id']);
    if (is_countable($customParams) && engine_count($customParams) > 0) {
      return Zend_Paginator::factory($category_select);
    }
    if (isset($params['limit']) && $params['limit'])
      $category_select->limit($params['limit']);
    return $this->fetchAll($category_select);
  }

  public function getCloudCategory($params = array()) {

    $newsTable = Engine_Api::_()->getDbTable('news', 'sesnews');
    $newsTableName = $newsTable->info('name');
    if (isset($params['column_name']))
      $column = $params['column_name'];
    else
      $column = '*';
    $tableName = $this->info('name');
    $category_select = $this->select()
            ->from($tableName, $column)
            ->setIntegrityCheck(false)
            ->joinLeft($newsTableName, "$newsTableName.category_id=$tableName.category_id", array("total_news_categories" => "COUNT(news_id)"))
            ->where($tableName . '.subcat_id = ?', 0)
            ->where($tableName . '.subsubcat_id = ?', 0)
            ->group("$newsTableName.category_id")
            ->order('order DESC');
    if (is_countable($params) && engine_count($params) && isset($params['paginator']))
      return Zend_Paginator::factory($category_select);

    return $this->fetchAll($category_select);
  }

  public function slugExists($slug = '', $id = '') {
    if ($slug != '') {
      $tableName = $this->info('name');
      $select = $this->select()
              ->from($tableName)
              ->where($tableName . '.slug = ?', $slug);
      if ($id != '') {
        $select = $select->where('id != ?', $id);
      }
      $row = $this->fetchRow($select);
      if (empty($row)) {
        return true;
      } else
        return false;
    }
    return false;
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

  public function order($categoryTypeId, $categoryType = 'category_id') {
    // Get a list of all corresponding category, by order
    $table = Engine_Api::_()->getItemTable('sesnews_category');
    $currentOrder = $table->select()
            ->from($table, 'category_id')
            ->order('order DESC');
    if ($categoryType != 'category_id')
      $currentOrder = $currentOrder->where($categoryType . ' = ?', $categoryTypeId);
    else
      $currentOrder = $currentOrder->where('subcat_id = ?', 0)->where('subsubcat_id = ?', 0);
    return $currentOrder->query()->fetchAll(Zend_Db::FETCH_COLUMN);
  }

  public function getBreadcrumb($params = array()) {
    $category = false;
    $Subcategory = false;
    $subSubcategory = false;
    if (!empty($params)) {
      if ($params->subcat_id != 0) {
        $category = $this->getModuleCategory(array('column_name' => '*', 'category_id' => $params->subcat_id));
        $Subcategory = $this->getModuleCategory(array('column_name' => '*', 'category_id' => $params->category_id));
      } else if ($params->subsubcat_id != 0) {
        $subSubcategory = $this->getModuleCategory(array('column_name' => '*', 'category_id' => $params->category_id));
        $Subcategory = $this->getModuleCategory(array('column_name' => '*', 'category_id' => $params->subsubcat_id));
        $category = $this->getModuleCategory(array('column_name' => '*', 'category_id' => $Subcategory[0]['subcat_id']));
      } else
        $category = $this->getModuleCategory(array('column_name' => '*', 'category_id' => $params->category_id));
    }
    return array('category' => $category, 'subcategory' => $Subcategory, 'subSubcategory' => $subSubcategory);
  }

  public function hasCategory($params = array()) {

    $tableName = $this->info('name');
    $category_select = $this->select()
            ->from($tableName, 'category_id')
            ->where('category_name = ?', $params['category_name']);
    return $category_select->query()->fetchColumn();
  }

  public function hasCategoryId($params = array()) {

    $tableName = $this->info('name');
    $category_select = $this->select()
            ->from($tableName, 'category_id')
            ->where('senews_categoryid = ?', $params['senews_categoryid']);
    return $category_select->query()->fetchColumn();
  }

}
