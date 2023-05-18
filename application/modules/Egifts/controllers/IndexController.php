<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: IndexController.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_IndexController extends Core_Controller_Action_Standard
{
  public function indexAction()
  {
	  $db = Engine_Db_Table::getDefaultAdapter();
	  $this->view->giftlist=$db->select()->from('engine4_egifts_gifts')->where('status = 1')->order('created_date DESC')->query()->fetchAll();
	  $this->view->userid = $userid = isset($_GET['userid']) ? trim($_GET['userid']) : 0;
    $egifts_user = Zend_Registry::isRegistered('egifts_user') ? Zend_Registry::get('egifts_user') : null;
    if (empty($egifts_user))
      return $this->_forward('notfound', 'error', 'core');
	  if($userid==0)
	  {
	  	return false;
	  } 
  }
  
  public function myOrdersAction() {
    $this->_helper->content->setEnabled();
  }
  
  public function sendGiftAction()
  {
    $db = Engine_Db_Table::getDefaultAdapter();
    $this->view->giftlist=$db->select()->from('engine4_egifts_gifts')->where('status = 1')->order('created_date DESC')->query()->fetchAll();
    $this->view->giftid = $giftid = $this->_getParam("gift_id",0);
    if($giftid==0)
    {
      return false;
    } 
  }
	public function manageAction() {
		if( !$this->_helper->requireUser()->isValid() ) return;
		// Render
		// $this->_helper->content
			//->setNoRender()
			//->setEnabled();
		// Prepare data
		if(isset($_SESSION['giftpurchase_id']) && !empty($_SESSION['giftpurchase_id']))
		{
			$viewer = Engine_Api::_()->user()->getViewer();
			$this->view->item = Engine_Api::_()->getItem('egifts_giftpurchase', $_SESSION['giftpurchase_id']);
			unset($_SESSION['giftpurchase_id']);
		}
		else
		{
			return false;
		}
	}
  public function purchasegiftAction()
  {
	  $viewer = Engine_Api::_()->user()->getViewer();
    if(!isset($_POST['giftid']) || empty($_POST['giftid'])){
       echo json_encode(array('status'=>0,'message'=>$this->view->translate("Please select gift it required.")));die;
    }
    if(!isset($_POST['userid']) || empty($_POST['userid'])){
       echo json_encode(array('status'=>0,'message'=>$this->view->translate("Please select a member for sending gift it required.")));die;
    }
    if(!isset($_POST['message']) || empty($_POST['message'])){
       echo json_encode(array('status'=>0,'message'=>$this->view->translate("Please Type your message it required")));die;
    }
    $egifts_user = Zend_Registry::isRegistered('egifts_user') ? Zend_Registry::get('egifts_user') : null;
    if (empty($egifts_user))
      return $this->_forward('notfound', 'error', 'core');
  	if(isset($_POST['giftid']) && !empty($_POST['giftid']) && isset($_POST['message']) && $viewer->getIdentity())
	  {
     
      $db = Engine_Db_Table::getDefaultAdapter();
      $egiftordersTable = Engine_Api::_()->getDbTable('giftorders', 'egifts');
      $db->beginTransaction();
      $orderTotalAmount = 0;
      try{
   		  $gift_purchase = Engine_Api::_()->getDbtable('giftpurchases', 'egifts')->createRow();
        $gift_purchase->owner_id = $viewer->getIdentity();
        $gift_purchase->message = isset($_POST['message']) ? trim($_POST['message']) : null;
        $gift_purchase->purchase_user_id = isset($_POST['userid']) ? trim($_POST['userid']) : null;
        $gift_purchase->status = 1;
        $gift_purchase->is_private = $_POST['privacy'] ?? 0;
        $gift_purchase->created_date = date("Y-m-d H:i:s");
        $gift_purchase->gateway_transaction_id = null;
        $gift_purchase->transcation_status = 0;
        $gift_purchase->transcation_date = date("Y-m-d H:i:s");
        $gift_purchase->save();
        foreach((array)$_POST['giftid'] as $giftid):
          $gift =  Engine_Api::_()->getItem('egifts_gift', $giftid);
          if(empty($gift))
            continue;
          $egiftorder = $egiftordersTable->createRow();
          $egiftorder->gift_id = $gift->gift_id;
          $egiftorder->status = 1;
          $egiftorder->gift_title = $gift->title;
          $egiftorder->gift_icon_id = $gift->icon_id;
          $egiftorder->gift_price = $gift->price;
          $egiftorder->owner_id = $viewer->getIdentity();
          $egiftorder->giftpurchase_id = $gift_purchase->giftpurchase_id;
          $egiftorder->save();
          $orderTotalAmount += $gift->price;
        endforeach;
        $gift_purchase->total_amount = $orderTotalAmount;
        $db->commit();
        if($gift_purchase->save())
        {
          $url = $this->view->url(array('module'=>'egifts','controller'=>'payment','action'=>'index','giftpurchase_id'=>$gift_purchase->giftpurchase_id),'default',false);
          echo json_encode(array('url'=>$url,'status'=>1));die;
          exit();
        } 
		  } catch(Exception $e) {
        echo json_encode(array('status'=>0));die;
        throw $e;
		  }
	  }
    echo json_encode(array('status'=>0));die;
    exit();
  }

  public function removegiftAction()
  {
	  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && ((isset($_POST['gift_id']) && !empty($_POST['gift_id']))))
	  {
		  $gift = Engine_Api::_()->getItem('egifts_giftorder',$_POST['gift_id']);
		  $gift->status = 0;
		  $gift->save();
		  echo 1;
		  exit();
	  }
	  exit();
  }
  public function browseAction() {
    $this->_helper->content->setEnabled();
  }
  public function myGiftsAction() {
    $this->_helper->content->setEnabled();
  }
  public function getUserAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $text = $this->_getParam('text', null);
    $userTable = Engine_Api::_()->getItemTable('user');
    $selectUser = $userTable->select()->where('displayname  LIKE ? ', '%' .$text. '%')
    ->where('user_id != ?',$viewer->getIdentity());
    $members = $userTable->fetchAll($selectUser);
    $egifts_user = Zend_Registry::isRegistered('egifts_user') ? Zend_Registry::get('egifts_user') : null;
    if (empty($egifts_user))
      return $this->_forward('notfound', 'error', 'core');
    foreach ($members as $member) {
      $member_icon_photo = $this->view->htmlLink($member->getHref(), $this->view->itemPhoto($member, 'thumb.icon'), array('title' => $member->getTitle(), 'target' => '_parent'));
      $sesdata[] = array(
      'id' => $member->user_id,
      'label' => $member->getTitle(),
      'image' => $member_icon_photo,
      'photo' => $this->view->itemPhoto($member, 'thumb.icon'),
      'title'=>$this->view->htmlLink($member->getHref(), $member->getTitle(), array('title' => $member->getTitle(), 'target' => '_parent'))
      );
    }
    return $this->_helper->json($sesdata);
  }
}
