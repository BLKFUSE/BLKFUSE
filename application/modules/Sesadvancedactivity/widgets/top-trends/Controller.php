<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedactivity_Widget_TopTrendsController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
    $limit = $this->_getParam('limit',10);
    $hashTag = Engine_Api::_()->getDbTable('hashtags','sesadvancedactivity'); 
    $hashTagName = $hashTag->info('name'); 
    $select = $hashTag->select()->from($hashTagName,array('*','total'=>"COUNT(hashtag_id)"))->group('title')
              ->order('COUNT(hashtag_id) DESC')
              ->limit($limit);
    $trends = $hashTag->fetchAll($select);
    if(!engine_count($trends))
      return $this->setNoRender();
    
    $this->view->trends = $trends; 
    $sesadvancedactivity_toptrend = Zend_Registry::isRegistered('sesadvancedactivity_toptrend') ? Zend_Registry::get('sesadvancedactivity_toptrend') : null;
    if(empty($sesadvancedactivity_toptrend)) {
      return $this->setNoRender();
    }
  }
}
