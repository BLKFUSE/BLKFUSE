<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Parametervalues.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Model_DbTable_Parametervalues extends Engine_Db_Table {
  protected $_rowClass = 'Sesnews_Model_Parametervalue';
	protected $_name  = 'sesnews_review_parametervalues';
	public function getParameters($params = array()){
		$pTable = Engine_Api::_()->getDbtable('parameters', 'sesnews');
    	$pTableName = $pTable->info('name');
		$tablename = $this->info('name');
		$select = $this->select()
							->from($tablename)
							->setIntegrityCheck(false)
            	->joinLeft($pTableName, "$pTableName.parameter_id = $tablename.parameter_id", array("title"))
							->where($tablename.'.content_id =?',$params['content_id'])
							->where($tablename.'.user_id =?',$params['user_id'])
							->where($pTableName.'.parameter_id != ?','');
			return $this->fetchAll($select);

	}
	public function ratingCount($parameter_id = NULL){
    $rName = $this->info('name');
    return $this->select()
		->from($rName,new Zend_Db_Expr('COUNT(parametervalue_id) as total_rating'))
		->where($rName.'.parameter_id = ?', $parameter_id)
		->limit(1)->query()->fetchColumn();
  }
   // rating functions
  public function getRating($parameter_id) {
    $rating_sum = $this->select()
            ->from($this->info('name'), new Zend_Db_Expr('SUM(rating)'))
            ->group('parameter_id')
            ->where('parameter_id = ?', $parameter_id)
            ->query()
            ->fetchColumn(0)
    ;
    $total = $this->ratingCount($parameter_id, $resource_type = 'sesnews_event');
    if ($total)
      $rating = $rating_sum / $total;
    else
      $rating = 0;
    return $rating;
  }
  public function getSumRating($parameter_id) {
    $rName = $this->info('name');
    $rating_sum = $this->select()
    ->from($rName, new Zend_Db_Expr('SUM(rating)'))
    ->where('parameter_id = ?', $parameter_id)
    ->group('parameter_id')
    ->query()
    ->fetchColumn();
    return $rating_sum;
  }
}
