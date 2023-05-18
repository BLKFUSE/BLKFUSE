<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Customthemes.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_Model_DbTable_Customthemes extends Engine_Db_Table {

  protected $_rowClass = "Sesdating_Model_Customtheme";

  public function getCustomThemes($param = array()) {
  
    $tableName = $this->info('name');
    $select = $this->select()->from($tableName);
    if(empty($param['all'])) {
      $select->where('`default` = ?', '1');
    }
    return $this->fetchAll($select);
  }

}
