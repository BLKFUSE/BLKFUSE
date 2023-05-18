<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Sharetypes.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_Model_DbTable_SocialShareHistories extends Engine_Db_Table
{

  protected $_name = 'siteshare_socialshare_histories';

  public function getSharePageUrls()
  {
    $select = $this->select()->group('share_url');
    $results = $this->fetchAll($select);
    $urls = array();
    foreach( $results as $row ) {
      $urls[$row->share_url] = $row->share_url;
    }
    return $urls;
  }

  public function getShareCounts($params)
  {
    $select = $this
      ->select()
      ->from($this->info('name'), array('*', 'COUNT(*) as total_share'))
      ->group('service_type');
    if( !empty($params['pageUrl']) ) {
      $select->where('share_url = ?', $params['pageUrl']);
    }
    if( !empty($params['start']) ) {
      $select->where('creation_date >= ?', $params['start']);
    }
    if( !empty($params['end']) ) {
      $select->where('creation_date <= ?', $params['end']);
    }
    $results = $select->query()
      ->fetchAll();
    $data = array();
    $totalShareCount = 0;
    foreach( $results as $row ) {
      $data[$row['service_type']] = $row['total_share'];
      $totalShareCount += $row['total_share'];
    }
    return array('total' => $totalShareCount, 'data' => $data);
  }

}
