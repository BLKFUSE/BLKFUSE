<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Banners.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_Model_DbTable_Banners extends Engine_Db_Table {

  protected $_rowClass = "Sesdating_Model_Banner";

  public function getBanner($param = array()) {
    $tableName = $this->info('name');
    $select = $this->select()
            ->from($tableName);
    if (isset($param['fetchAll'])) {
      $select->where('enabled =?', 1);
      return $this->fetchAll($select);
      }
    return Zend_Paginator::factory($select);
  }

}
