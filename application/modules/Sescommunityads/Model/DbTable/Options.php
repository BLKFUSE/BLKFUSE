<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Options.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Model_DbTable_Options extends Engine_Db_Table {

  protected $_name = 'user_fields_options';
  protected $_rowClass = 'Sescommunityads_Model_Option';
  public function getParticularProfileType($id) {
    $select = $this->select()
                    ->where('option_id = ?', $id);
    $result = $this->fetchRow($select);
    return $result->label;
  }
  public function getUserProfileTypes() {
    $select = $this->select()
                    ->where('field_id = ?', 1);
    $result = $this->fetchAll($select);
    return $result;
  }

}