<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Categories.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Model_DbTable_Categories extends Engine_Db_Table {

  protected $_rowClass = 'Sescontest_Model_Category';
  protected $_searchTriggers = false;

  public function getCategoryId($slug = null) {

    if ($slug) {
      $tableName = $this->info('name');
      $select = $this->select()
              ->from($tableName)
              ->where($tableName . '.slug = ?', $slug);
      $row = $this->fetchRow($select);
      if (empty($row))
        $category_id = $slug;
      else
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

  public function getCategory($params = array(), $customParams = array()) {

    if (isset($params['column_name'])) {
      $column = $params['column_name'];
    } else
      $column = '*';

    $tableName = $this->info('name');
    $category_select = $this->select()
            ->from($tableName, $column)
            ->where($tableName . '.subcat_id = ?', 0)
            ->where($tableName . '.subsubcat_id = ?', 0);

    if (isset($params['criteria']) && $params['criteria'] == 'alphabetical')
      $category_select->order($tableName . '.category_name');

    $contestTableName = Engine_Api::_()->getDbTable('contests', 'sescontest')->info('name');
    $category_select = $category_select->setIntegrityCheck(false);
    $category_select = $category_select->joinLeft($contestTableName, "$contestTableName.category_id=$tableName.category_id AND (`is_approved` = 1 AND `" . $contestTableName . "`.`draft` != 0)", array("total_contest_categories" => "COUNT($contestTableName.contest_id)"));
    $category_select = $category_select->group("$tableName.category_id");
    if (isset($params['criteria']) && $params['criteria'] == 'most_contest')
      $category_select->order('total_contest_categories DESC');
    if (isset($params['criteria']) && $params['criteria'] != 'most_contest')
      $category_select = $category_select->order('order DESC');
    if (isset($params['category_id']) && !empty($params['category_id']))
      $category_select = $category_select->where($tableName . '.category_id = ?', $params['category_id']);
    if (isset($params['hasContest'])) {
      $currentTime = date('Y-m-d H:i:s');
      $tomorrow_date = date("Y-m-d H:i:s", strtotime("+ 1 day"));
      $nextWeekDate = date("Y-m-d H:i:s", strtotime("+ 7 day"));
      $category_select = $category_select->having('total_contest_categories > 0');
      if (isset($params['order']) && !empty($params['order'])) {
        if ($params['order'] == 'featured')
          $category_select->where('featured = ?', '1');
        elseif ($params['order'] == 'sponsored')
          $category_select->where('sponsored = ?', '1');
        elseif ($params['order'] == 'hot')
          $category_select->where('hot = ?', '1');
        else if ($params['order'] == 'ended')
          $category_select->where("$contestTableName.endtime < FROM_UNIXTIME('" . time() . "')");
        else if ($params['order'] == 'upcoming')
          $category_select->where("endtime > FROM_UNIXTIME('" . time() . "') && starttime > FROM_UNIXTIME('" . time() . "')");
        if ($params['order'] == 'week') {
          $category_select->where("((YEARWEEK(starttime) = YEARWEEK('$currentTime')) || YEARWEEK(endtime) = YEARWEEK('$currentTime'))  || (DATE(starttime) <= DATE('$currentTime') AND DATE(endtime) >= DATE('$currentTime'))");
        } elseif ($params['order'] == 'today') {
          $category_select->where("$contestTableName.starttime <= ?", $currentTime)->where("$contestTableName.endtime >= ?", $currentTime);
        } elseif ($params['order'] == 'tomorrow') {
          $category_select->where("$contestTableName.starttime <= ?", $tomorrow_date)->where("$contestTableName.endtime >= ?", $tomorrow_date);
        } elseif ($params['order'] == 'nextweek') {
          $category_select->where("((YEARWEEK($contestTableName.starttime) = YEARWEEK('$nextWeekDate')) || YEARWEEK($contestTableName.endtime) = YEARWEEK('$nextWeekDate'))  || (DATE($contestTableName.starttime) <= DATE('$nextWeekDate') AND DATE($contestTableName.endtime) >= DATE('$nextWeekDate'))");
        } elseif ($params['order'] == 'month') {
          $category_select->where("((YEAR(starttime) > YEAR('$currentTime')) || YEAR(starttime) <= YEAR('$currentTime') AND (MONTH(starttime) <= MONTH('$currentTime'))) AND ((YEAR(endtime) > YEAR('$currentTime') || MONTH(endtime) >= MONTH('$currentTime')) AND YEAR(endtime) >= YEAR('$currentTime'))");
        } elseif ($params['order'] == 'ongoing') {
          $category_select->where($contestTableName . ".endtime >= ?", $currentTime)->where($contestTableName . ".starttime <= ?", $currentTime);
        } elseif ($params['order'] == 'ongoingSPupcomming') {
          $category_select->where("(endtime >= '" . $currentTime . "') || (endtime > '" . $currentTime . "' && starttime > '" . $currentTime . "')");
        }
      }
    }
    if (engine_count($customParams) && isset($customParams['paginator'])) {
      return Zend_Paginator::factory($category_select);
    }
    if (isset($params['limit']))
      $category_select->limit($params['limit']);
    $category_select->order('order DESC');
    return $this->fetchAll($category_select);
  }

  public function order($categoryTypeId, $categoryType = 'category_id') {
    // Get a list of all corresponding category, by order
    $table = Engine_Api::_()->getItemTable('sescontest_category');
    $currentOrder = $table->select()
            ->from($table, 'category_id')
            ->order('order DESC');
    if ($categoryType != 'category_id')
      $currentOrder = $currentOrder->where($categoryType . ' = ?', $categoryTypeId);
    else
      $currentOrder = $currentOrder->where('subcat_id = ?', 0)->where('subsubcat_id = ?', 0);
    return $currentOrder->query()->fetchAll(Zend_Db::FETCH_COLUMN);
  }

  public function getMapping($params = array()) {
    $select = $this->select()->from($this->info('name'), $params);
    $mapping = $this->fetchAll($select);
    if (!empty($mapping)) {
      return $mapping->toArray();
    }
    return null;
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

  public function getCategoriesAssoc($params = array()) {
    $stmt = $this->select()
            ->from($this, array('category_id', 'category_name'))
            ->where('subcat_id = ?', 0)
            ->where('subsubcat_id = ?', 0);
    if (isset($params['module'])) {
      $stmt = $stmt->where('resource_type = ?', $params['module']);
    }
    $viewerId = Engine_Api::_()->user()->getViewer()->getIdentity();
    if ($viewerId && (isset($params['member_levels']) && $params['member_levels'] == 1)) {
      $levelId = Engine_Api::_()->user()->getViewer()->level_id;
      $stmt->where('CONCAT(engine4_sescontest_categories.member_levels," ") LIKE "%' . $levelId . '%"');
    }
    $stmt = $stmt->order('order DESC')
            ->query()
            ->fetchAll();
    $data = array();
    if (isset($params['module']) && $params['module'] == 'group') {
      $data[] = '';
    }
    foreach ($stmt as $category) {
      if (!$category['category_id'] && !isset($params['uncategories']))
        continue;
      $data[$category['category_id']] = $category['category_name'];
    }
    return $data;
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
    $category_select = $this->select()
            ->from($tableName, $params['column_name']);
    if (isset($params['category_id']) && !empty($params['category_id']))
      $category_select = $category_select->where($tableName . '.subcat_id = ?', $params['category_id']);
    else if (isset($params['getcategory0']))
      $category_select = $category_select->where($tableName . '.subcat_id = ?', '-1');
    if (isset($params['countContests'])) {
      $contestTable = Engine_Api::_()->getDbTable('contests', 'sescontest')->info('name');
      $category_select = $category_select->setIntegrityCheck(false);
      $category_select = $category_select->joinLeft($contestTable, "$contestTable.subcat_id=$tableName.category_id", array("total_contests_categories" => "COUNT($contestTable.photo_id)"));
      $category_select = $category_select->group("$tableName.category_id");
      $category_select->order('total_contests_categories DESC');
    }
    $category_select = $category_select->order('order DESC');
    if (isset($params['limit']))
      $category_select->limit($params['limit']);
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
    else if (isset($params['getcategory0']))
      $category_select = $category_select->where($tableName . '.subcat_id = ?', $params['category_id']);
    if (isset($params['countContests'])) {
      $contestTable = Engine_Api::_()->getDbTable('contests', 'sescontest')->info('name');
      $category_select = $category_select->setIntegrityCheck(false);
      $category_select = $category_select->joinLeft($contestTable, "$contestTable.subsubcat_id=$tableName.category_id", array("total_contests_categories" => "COUNT($contestTable.contest_id)"));

      $category_select = $category_select->group("$tableName.category_id");
      $category_select->order('total_contests_categories DESC');
    }
    $category_select = $category_select->order('order DESC');
    if (isset($params['limit']))
      $category_select->limit($params['limit']);
    return $this->fetchAll($category_select);
  }

}
