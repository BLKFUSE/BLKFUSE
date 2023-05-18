<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AjaxController.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_AjaxController extends Core_Controller_Action_Standard {

  public function reportAction() {

    $value['description'] = $this->_getParam('text','');
    $value['value'] = $this->_getParam('value','');

    $table = Engine_Api::_()->getDbTable('reports','sescommunityads');
    $report = $table->createRow();
    $value['item_id'] = $this->_getParam('sescommunityad_id');
    $value['user_id'] = $this->view->viewer()->getIdentity();
    $value['ip'] = $_SERVER['REMOTE_ADDR'];
    $report->setFromArray($value);
    $report->save();
    echo 1;die;
  }
   public function usefulAction() {
    $sescommunityad_id = $this->_getParam('sescommunityad_id');
    $ad = Engine_Api::_()->getItem('sescommunityads',$sescommunityad_id);
    $isUseful = $ad->isUseful();
    if(!$isUseful){
      $table = Engine_Api::_()->getDbTable('usefulads','sescommunityads');
      $usefulads = $table->createRow();
      $value['item_id'] = $this->_getParam('sescommunityad_id');
      $value['user_id'] = $this->view->viewer()->getIdentity();
      $value['ip'] = $_SERVER['REMOTE_ADDR'];
      $usefulads->setFromArray($value);
      $usefulads->save();
    }else{
      $isUseful->delete();
    }
    echo 1;die;
  }
}
