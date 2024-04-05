<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Reviewmetas.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Model_DbTable_Reviewmetas extends Engine_Db_Table {

  protected $_name = 'sesnews_review_fields_meta';
  protected $_rowClass = 'Sesnews_Model_Reviewmeta';

  public function profileFieldId() {

    return $this->select()
                    ->from($this->info('name'), array('field_id'))
                    ->where('alias = ?', 'profile_type')
                    ->where('type = ?', 'profile_type')
                    ->query()
                    ->fetchColumn();
  }

}
