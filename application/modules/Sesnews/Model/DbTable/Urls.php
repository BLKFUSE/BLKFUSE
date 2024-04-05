<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Urls.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Model_DbTable_Urls extends Core_Model_Item_DbTable_Abstract {

  protected $_rowClass = "Sesnews_Model_Url";

  public function getAllUrls($subject_id = null) {

    $select = $this->select()
              ->from($this->info('name'))->order('url_id DESC');
    return $this->fetchAll($select);
  }

  public function getColumnValue($params = array()) {

    return $this->select()
            ->from($this->info('name'), 'url_id')
            ->where('name = ?', $params['name'])
            ->where('enabled = ?', 1)
            ->query()
            ->fetchColumn();
  }
}
