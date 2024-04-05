<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Options.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesnews_Model_DbTable_Options extends Engine_Db_Table {

  protected $_rowClass = 'Sesnews_Model_Option';
  protected $_name = 'sesnews_news_fields_options';

  public function getOptionsLabel($option_id) {
    return $this->select()
                    ->from($this->info('name'), array('label'))
                    ->where('option_id = ?', $option_id)
                    ->query()
                    ->fetchColumn();
  }
	public function getAllOptions() {
    return $this->fetchAll($this->select()
                    ->from($this->info('name'), '*'));

  }
}
