<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescomadbanr
 * @package    Sescomadbanr
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Banner.php  2019-03-08 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sescomadbanr_Model_DbTable_Banners extends Engine_Db_Table {

  protected $_rowClass = "Sescomadbanr_Model_Banner";

  public function getBanner($param = array()) {

    $tableName = $this->info('name');
    $select = $this->select()
            ->from($tableName)->order('banner_id DESC');
    if (isset($param['fetchAll'])) {
        $select->where('enabled =?', 1);
        return $this->fetchAll($select);
    }
    return Zend_Paginator::factory($select);
  }
}
