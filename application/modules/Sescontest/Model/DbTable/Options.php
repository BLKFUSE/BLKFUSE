<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Options.php 2016-07-23 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Model_DbTable_Options extends Engine_Db_Table {

  protected $_rowClass = 'Sescontest_Model_Option';
  protected $_name = 'contest_fields_options';

  public function getOptionsLabel($option_id) {
    return $this->select()
            ->from($this->info('name'), array('label'))
            ->where('option_id = ?', $option_id)
            ->query()
            ->fetchColumn();
  }
  public function getAllOptions() {
    return $this->fetchAll($this->select()->from($this->info('name'), '*'));
  }
}