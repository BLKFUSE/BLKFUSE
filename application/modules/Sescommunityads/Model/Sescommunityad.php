<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Sescommunityad.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Model_Sescommunityad extends Core_Model_Item_Abstract {
  protected $_statusChanged;
  protected $_type = "sescommunityads";
  protected $_searchTriggers = false;

  public function getTitle(){
    if($this->title){
      return $this->title;
    }else if($this->type == "boost_post_cnt"){
      return "Boost Post";
    }
  }
  public function getHref($params = array()){
    $sescommunityad_id = $this->sescommunityad_id;
    if(!empty($params['subject'])){
      $token = 'CDSsubject'.$sescommunityad_id;
       unset($params['subject']);
    }else{
      $token = 'CDSs'.$sescommunityad_id;
    }
    $crypted_token = urlencode(Engine_Api::_()->sescommunityads()->encrypt($token));
    unset($token);

    $params = array_merge(array(
      'route' => 'sescommunityads_redirect',
      'reset' => true,
      'redirect' => $crypted_token,
    ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
      ->assemble($params, $route, $reset);
  }

  public function getCampaign(){
    return Engine_Api::_()->getItem('sescommunityads_campaign',$this->campaign_id);
  }
  function ctr(){
    return number_format(($this->click_count/$this->views_count)*100 ,4);
  }

  public function adType(){
    $string = "";
    $type = "";
    if($this->type == "boost_post_cnt"){
      $string = "Boost A Post";

    }else if($this->type == "promote_page_cnt"){
      $string = "Promote Your Page";
    }else if($this->type == "promote_content_cnt"){
      $string = "Promote Your Content";
      if($this->subtype == "image")
        $type = "Single Image";
      else if($this->subtype == "video")
        $type = "Single Video";
      else
        $type = "Carousel";
    }else if($this->type == "promote_website_cnt"){
      $string = "Get More Website Visitors";
    }
    $subtype = (!empty($type) ? '('.$type.")" : "");
    return $string.$subtype;
  }



  public function cancel() {
    $package = $this->getPackage();
    if ($package->isFree()) {
      return true;
    }
    //update transaction_id to other ad of same package ads
    if ($this->transaction_id) {
      $transaction = $this->getTransaction();
      $table = Engine_Api::_()->getDbTable('sescommunityads', 'sescommunityads');
      $tableName = $table->info('name');
      //select ad in package with our transaction id.
      $select = $table->select()->from($tableName)->where('transaction_id =?', '')->where('orderspackage_id =?', $this->orderspackage_id);
      $ad = $table->fetchRow($select);
      if ($ad) {
        $ad->transaction_id = $this->transaction_id;
        $ad->save();
        //update order
        $order_id = $transaction->order_id;
        $order = Engine_Api::_()->getItem('payment_order', $order_id);
        if ($order) {
          $order->source_id = $ad->getIdentity();
          $order->save();
        }
        //update item count in order package
        $orderpackage = Engine_Api::_()->getItem('sescommunityads_orderspackage', $this->orderspackage_id);
        $orderpackage->item_count = $orderpackage->item_count + 1;
        $orderpackage->save();
        return true;
      } else {
        //delete order package
        $orderpackage = Engine_Api::_()->getItem('sescommunityads_orderspackage', $this->orderspackage_id);
        if ($orderpackage)
          $orderpackage->delete();
      }
    }else {
      //update item count in order package
      $orderpackage = Engine_Api::_()->getItem('sescommunityads_orderspackage', $this->orderspackage_id);
      $orderpackage->item_count = $orderpackage->item_count + 1;
      $orderpackage->save();
      return true;
    }
    // Try to cancel recurring payments in the gateway
    if (!empty($transaction->gateway_id) && !empty($transaction->gateway_profile_id) && empty($transaction->gateway_transaction_id)) {
      try {

        $gateway = Engine_Api::_()->getItem('sescommunityads_gateway', $transaction->gateway_id);
        if ($gateway) {
          $gatewayPlugin = $gateway->getPlugin();
          if (method_exists($gatewayPlugin, 'cancelAd')) {
            $gatewayPlugin->cancelAd($transaction->gateway_profile_id);
          }
        }
      } catch (Exception $e) {
        // Silence?
      }
    }
    return $this;
  }

  public function getPackage() {
    return Engine_Api::_()->getItem('sescommunityads_packages', $this->package_id);
  }

  public function getTransaction() {
    return Engine_Api::_()->getItem('sescommunityads_transaction', $this->transaction_id);
  }

  // Cntests
  public function clearStatusChanged() {
    $this->_statusChanged = null;
    return $this;
  }

  public function didStatusChange() {
    return (bool) $this->_statusChanged;
  }

  // Active
  public function setActive($flag = true, $deactivateOthers = null) {
    //$this->active = true;
    if ((true === $flag && null === $deactivateOthers) ||
            $deactivateOthers === true) {
      $package = Engine_Api::_()->getItem('sescommunityads_package',$this->package_id);
      if($package){
        if($package->auto_approve){
          $this->is_approved = 1;
            if(!$this->approved_date)
              $this->approved_date = date('Y-m-d H:i:s');
          $this->status = 1;
          $this->save();
        }
      }
    }

    return $this;
  }

  public function changeApprovedStatus($approved = '') {
    $transaction = $this->getTransaction();
    $orderPackageId = $this->orderspackage_id;
    if ($transaction && $orderPackageId && $approved) {
      if($approved){
        $state = "active";
      }else{
        $state = $approved;
      }
      Engine_Api::_()->getDbTable('sescommunityads', 'sescommunityads')->update(array('state' => $state), array('orderspackage_id' => $orderPackageId));
      /*if (!$this->activity_created) {
        $this->activity_created = 1;
        $this->save();
        $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
        $action = $activityApi->addActivity(Engine_Api::_()->user()->getViewer(), $this, 'sescommunityads_create');
        if ($action) {
          $activityApi->attachActivity($action, $this);
        }
      }*/
    }
  }

  public function onPaymentSuccess() {
    $this->_statusChanged = false;
    $transaction = $this->getTransaction();
    $this->expiry_notification = 0;
    $this->save();
    if ($transaction) {
      if (engine_in_array($transaction->state, array('initial', 'trial', 'pending', 'active'))) {
        // If the package is in initial or pending, set as active and
        // cancel any other active subscriptions
        if (engine_in_array($transaction->state, array('initial', 'pending'))) {
          $this->setActive(true);
        }

        // Update expiration to expiration + recurrence or to now + recurrence?
        $package = $this->getPackage();
        $expiration = $package->getExpirationDate();

        //featured
        $this->featured = $package->featured;
        if($package->featured_days){
            $this->featured_date = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s') + "+ ".$package->featured_days." days"));
        }else{
            $this->featured_date = NULL;
        }
        //sponsored
        $this->sponsored = $package->sponsored;
        if($package->sponsored_days){
            $this->sponsored_date = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s') + "+ ".$package->sponsored_days." days"));
        }else{
            $this->sponsored_date = NULL;
        }
        $this->state = "active";
        $this->ad_type = $package->click_type;
        if($package->click_limit) {
          if($package->click_type == "perday"){
            $this->ad_expiration_date = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s') + "+ ".$package->click_limit." days"));
            $this->ad_limit = $package->click_limit;
          }else{
             $this->ad_limit = $package->click_limit;
          }
        }else{
          //unlimited
          $this->ad_limit = "-1";
        }

        if($this->enddate != "0000-00-00 00:00:00" && $this->startdate != "0000-00-00 00:00:00"){
           $timediff = strtotime($this->enddate) - strtotime($this->startdate);
           $this->enddate = date('Y-m-d H:i:s',time() + $timediff);
        }
        $this->save();
        //check isonetime condition and renew exiration date if left
        $daysLeft = 0;
        if ($package->isOneTime() && !empty($transaction->expiration_date) && $transaction->expiration_date != '3000-00-00 00:00:00') {
          $datediff = strtotime($transaction->expiration_date) - time();
          $daysLeft = floor($datediff / (60 * 60 * 24));
        }
        $orderPackage = Engine_Api::_()->getItem('sescommunityads_orderspackage', $this->orderspackage_id);
        
        if(filter_var($expiration, FILTER_VALIDATE_INT) !== false){
          $expiration = date('Y-m-d H:i:s',$expiration);  
        }
        
        if ($expiration) {
          $expiration_date = date('Y-m-d H:i:s', strtotime($expiration));
          //check days left or not
          if ($daysLeft >= 1) {
            //reniew condition
            $expiration_date = date('Y-m-d H:i:s', strtotime($transaction->expiration_date . '+ ' . $daysLeft . ' days'));
          }
          $transaction->expiration_date = $expiration_date;
          $orderPackage->expiration_date = $expiration_date;
          $orderPackage->save();
        } else {
          //make it a future contest(never expired)
          //3000-00-00 00:00:00
          $transaction->expiration_date = "";
          $orderPackage->expiration_date = '';
          $orderPackage->save();
        }
        //update all items in the transaction
        $this->changeApprovedStatus('active');
        // Change status
        if ($transaction->state != 'active') {
          $transaction->state = 'active';
          $this->_statusChanged = true;
        }
        $transaction->save();
      }
    }
    return $transaction;
  }

  public function onPaymentPending() {
    $this->_statusChanged = false;
    $transaction = $this->getTransaction();
    if ($transaction && ( engine_in_array($transaction->state, array('initial', 'trial', 'pending', 'active')))) {
      //update all items in the transaction
      $this->changeApprovedStatus(0);
      // Change status
      if ($transaction->state != 'pending') {
        $transaction->state = 'pending';
        $this->_statusChanged = true;
        $transaction->save();
      }
    }
    return $this;
  }

  public function onPaymentFailure() {
    $this->_statusChanged = false;
    $transaction = $this->getTransaction();

    if ($transaction && engine_in_array($transaction->state, array('initial', 'trial', 'pending', 'active', 'overdue'))) {
      //update all items in the transaction
      $this->changeApprovedStatus('failure');
      // Change status
      if ($transaction->state != 'overdue') {
        $transaction->state = 'overdue';
        $this->_statusChanged = true;
        $transaction->save();
      }
    }

    return $this;
  }

  public function onCancel() {
    $this->_statusChanged = false;
    $transaction = $this->getTransaction();
    if ($transaction && ( engine_in_array($transaction->state, array('initial', 'trial', 'pending', 'active', 'overdue', 'cancelled', 'okay')) )) {
      //update all items in the transaction
      $this->changeApprovedStatus('cancelled');
      // Change status
      if ($transaction->state != 'cancelled') {
        $transaction->state = 'cancelled';
        $this->_statusChanged = true;
        $transaction->save();
      }
    }

    return $this;
  }

  public function onExpiration() {
    $this->_statusChanged = false;
    $transaction = $this->getTransaction();
    if ($transaction && ( engine_in_array($this->state, array('initial', 'trial', 'pending', 'active', 'expired', 'overdue')) )) {
      //update all items in the transaction
      $this->changeApprovedStatus('expired');
      // Change status
      if ($transaction->state != 'expired') {
        $transaction->state = 'expired';
        $this->_statusChanged = true;
        $transaction->save();
      }
    }

    return $this;
  }
function setMorePhoto($photo,$website = false){
    if ($photo instanceof Zend_Form_Element_File) {
      $file = $photo->getFileName();
      $name = basename($file);
    } elseif (is_array($photo) && !empty($photo['tmp_name'])) {
      $file = $photo['tmp_name'];
      $name = $photo['name'];
    } elseif (is_string($photo) && file_exists($photo)) {
      $file = $photo;
      $name = basename($file);
    } else {
      throw new Core_Model_Exception('Invalid argument passed to setPhoto: ' . print_r($photo, 1));
    }

    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $params = array(
        'parent_type' => 'sescommunityads',
        'parent_id' => $this->getIdentity()
    );
    if(!$website){
      $normal_height = 800;
      $normal_width = 800;
    }else{
      $normal_height = 200;
      $normal_width = 200;
    }
    // Save
    $storage = Engine_Api::_()->storage();

    // Resize image (main)
    $image = Engine_Image::factory();
    $image->open($file)
            ->autoRotate()
            ->resize($normal_width, $normal_height)
            ->write($path . '/m_' . $name)
            ->destroy();
    // Store
    $iMain = $storage->create($path . '/m_' . $name, $params);
    // Remove temp files
    @unlink($path . '/m_' . $name);
    // Update row
    if(!$website){
      $this->modified_date = date('Y-m-d H:i:s');
      $this->more_image = $iMain->getIdentity();
      $this->save();
      return $this;
    }else{
      return $iMain->getIdentity();
    }
  }
  public function onRefund() {
    $this->_statusChanged = false;
    $transaction = $this->getTransaction();
    if ($transaction && engine_in_array($transaction->state, array('initial', 'trial', 'pending', 'active', 'refunded'))) {
      //update all items in the transaction
      $this->changeApprovedStatus('refunded');
      // Change status
      if ($transaction->state != 'refunded') {
        $transaction->state = 'refunded';
        $this->_statusChanged = true;
        $transaction->save();
      }
    }
    return $this;
  }
  public function isUseful(){
      $user = Engine_Api::_()->user()->getViewer();
      $ip = $_SERVER['REMOTE_ADDR'];
      $table = Engine_Api::_()->getDbTable('usefulads','sescommunityads');
      $select = $table->select()->where("user_id = ".$user->getIdentity() .' || (user_id IS NULL &&ip = "'.$ip.'")');
      $select->where('item_id =?',$this->sescommunityad_id)->limit(1);
      return $table->fetchRow($select);
  }
  function shortCodeData($code){
      $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
      return  $data = $view->partial('_shortCode.tpl','sescommunityads',array('ad'=>$this));
  }
}
