<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Userad.php 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Model_Userad extends Core_Model_Item_Abstract {

  // Properties
  protected $_parent_type = 'userad';
  protected $_parent_is_owner = true;
  protected $_package;
  protected $_statusChanged;

  // SET THE VALUE IN THE DATA BASE.
  public function getAdTypeTitle($type) { 
    
    $table=Engine_Api::_()->getDbtable('adtypes', 'sitead');
    $name = $table->info('name');
    $select = $table->select()->from($name, array('title'))->where('type =?', $type);
    return $select->query()
                    ->fetchColumn('title');
  }

  public function getPackage() {
    if (empty($this->package_id)) {
      return null;
    }
    if (null === $this->_package) {
      $this->_package = Engine_Api::_()->getItem('package', $this->package_id);
    }
    return $this->_package;
  }

  public function setActive() {

    $check_result_show = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.check.var');
    $base_result_time = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.base.time');
    $get_result_show = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.get.path');
    $sitead_time_var = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.time.var');
    $currentbase_time = time();
    $word_name = strrev('lruc');
    $file_path = APPLICATION_PATH . '/application/modules/' . $get_result_show;
    if (($currentbase_time - $base_result_time > $sitead_time_var) && empty($check_result_show)) {
      $is_file_exist = file_exists($file_path);
      if (!empty($is_file_exist)) {
        $fp = fopen($file_path, "r");
        while (!feof($fp)) {
          $get_file_content .= fgetc($fp);
        }
        fclose($fp);
        $sitead_set_type = strstr($get_file_content, $word_name);
      }
      if (empty($sitead_set_type)) {
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitead.ads.field', 1);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitead.flag.info', 1);
        return;
      } else {
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitead.check.var', 1);
      }
    }

    $package = $this->getPackage();
    $approved = $this->approved;
    if (empty($this->approved))
      $this->approved = $package->auto_aprove;

    if (empty($this->status) || $this->status == 3) {
      $this->status = 1;
      $this->enable = true;
    }

    if (!empty($this->approved)) {

      if (empty($this->approve_date))
        $this->approve_date = new Zend_Db_Expr('NOW()');
      switch ($this->price_model) {
        case "Pay/view":


          if ($this->limit_view != -1) {

            if ($package->model_detail == -1)
              $this->limit_view = $package->model_detail;
            else
              $this->limit_view += $package->model_detail;
          }

          break;

        case "Pay/click":
          if ($package->model_detail == -1)
            $this->limit_click = $package->model_detail;
          else
            $this->limit_click += $package->model_detail;
          break;
        case "Pay/period":

          $diff_days = 0;
          if (!empty($this->expiry_date) && date('Y-m-d', strtotime($this->expiry_date)) > date('Y-m-d')) {
            $diff_days = round((strtotime($this->expiry_date) - strtotime(date('Y-m-d'))) / 86400);
          }

          if (($this->expiry_date !== '2250-01-01') || empty($this->expiry_date)) {
            if ($diff_days < 0)
              $diff_days = 0;
            if ($package->model_detail == -1) {
              $this->expiry_date = '2250-01-01';
            } else {

              $this->expiry_date = Engine_Api::_()->sitead()->getExpiryDate($package->model_detail + $diff_days);
            }
          }
          break;
      }
    }

    $this->save();

    if ($this->approved && empty($approved)) {
      // SEND APPROVED MAIL HERE
      Engine_Api::_()->sitead()->sendMail("ACTIVE", $this->userad_id);
    } elseif (empty($this->approved)) {
      // SEND DISAPPROVED MAIL HERE
      Engine_Api::_()->sitead()->sendMail("APPROVAL_PENDING", $this->userad_id);
    }


    return $this;
  }

  public function didStatusChange() {
    return (bool) $this->_statusChanged;
  }

  public function onPaymentSuccess() {
    $this->_statusChanged = false;

    if (in_array($this->payment_status, array('initial', 'trial', 'pending', 'active', 'overdue', 'expired'))) {

      $this->setActive(true);

      // Change status
      if ($this->payment_status != 'active') {
        $this->payment_status = 'active';
        $this->_statusChanged = true;
      }
    }
    $this->save();
    return $this;
  }


   public function getIconUrl($type = null) {
      if( empty($this->icon_id) ) {
        return null;
      }

      $file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->icon_id, $type);
      if( !$file ) {
        return null;
      }
      
      return $file->map();
    }

  public function onPaymentPending() {
    $this->_statusChanged = false;
    if (in_array($this->payment_status, array('initial', 'trial', 'pending', 'active', 'overdue', 'expired'))) {
      // Change status
      if ($this->payment_status != 'pending') {
        $this->payment_status = 'pending';
        $this->_statusChanged = true;
      }
    }
    $this->save();
    return $this;
  }

  public function onPaymentFailure() {
    $this->_statusChanged = false;
    if (in_array($this->payment_status, array('initial', 'trial', 'pending', 'active', 'overdue', 'expired'))) {
      // Change status
      if ($this->payment_status != 'overdue') {
        $this->payment_status = 'overdue';
        $this->_statusChanged = true;
      }

      $session = new Zend_Session_Namespace('Payment_Userads');
      $session->unsetAll();
    }
    $this->save();
    return $this;
  }

  public function onExpiration() {
    $this->_statusChanged = false;
    if (in_array($this->payment_status, array('initial', 'trial', 'pending', 'active', 'expired'))) {
      // Change status
      if ($this->payment_status != 'expired') {
        $this->payment_status = 'expired';
        $this->approved = 0;
        $this->enable = 0;
        $this->status = 3;

        $this->_statusChanged = true;
      }
    }
    $this->save();
    return $this;
  }

  public function onRefund() {
    $this->_statusChanged = false;
    if (in_array($this->payment_status, array('initial', 'trial', 'pending', 'active', 'refunded'))) {
      // Change status
      if ($this->payment_status != 'refunded') {
        $this->payment_status = 'refunded';
        $this->_statusChanged = true;
      }
    }
    $this->save();
    return $this;
  }

/**
   * Process ipn of ad transaction
   *
   * @param Payment_Model_Order $order
   * @param Engine_Payment_Ipn $ipn
   */
  public function onPaymentIpn(Payment_Model_Order $order, Engine_Payment_Ipn $ipn) {
    $gateway = Engine_Api::_()->getItem('sitead_gateway', $order->gateway_id);
    $gateway->getPlugin()->onUseradTransactionIpn($order, $ipn);
    return true;
  }

  public function setFile($file) {
    $maxW = 140;
    $maxH = 160;

    $name = $file['name'];
    $name = basename($name);
    $pathName = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/sitead/temporary/' . $name;
    @chmod($pathName, 0777);

    $image = Engine_Image::factory();
    $image->open($file['tmp_name'])
    ->resize($maxW, $maxH)
    ->write($pathName)
    ->destroy();
    $photoName = $pathName;

    $storage = Engine_Api::_()->getItemTable('storage_file');
    $storageObject = $storage->createFile($photoName, array(
      'parent_id' => $this->getIdentity(),
      'parent_type' =>'sitead',
    ));

      // Remove temporary file
     // @unlink($file['tmp_name']);
    if (is_file($photoName)) {
      @chmod($photoName, 0777);
      @unlink($photoName);
    }
    $this->photo_id = $storageObject->file_id;
    $this->save();
  }


  /**
     * Set location
     *
     */
    public function setLocation($params = null, $location_name = null) {
        $id = $this->userad_id;
        $locationFieldEnable = Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.location', 0);

        if ($locationFieldEnable) {
            $sitead = $this;

            if (empty($params)) {
                if (!empty($sitead))
                    $location = $sitead->location;
            } else {
                $location = $params;
            }

            if (!empty($location)) {
                $locationTable = Engine_Api::_()->getDbtable('locations', 'sitead');
                $locationName = $locationTable->info('name');

                if (empty($params)) {
                    $locationRow = Engine_Api::_()->getDbtable('locations', 'sitead')->getLocation(array('id' => $id));
                }
                $locationRow = $locationTable->getLocation(array('location' => $location));
                if (isset($_POST['locationParams']) && $_POST['locationParams']) {
                    if (is_string($_POST['locationParams']))
                        $_POST['locationParams'] = Zend_Json_Decoder::decode($_POST['locationParams']);

                    try {
                        $loctionV = $_POST['locationParams'];
                        $flage = $_POST['location_type'];
                        $radius = $_POST['location_distance'];
                        if (empty($flage)) {
                          $radius = $radius * (0.621371192);
                        }
                        $loctionV['userad_id'] = $id;
                        $loctionV['location_distance'] = $radius;
                        if (empty($locationRow))
                            $locationRow = $locationTable->createRow();
                        $locationRow->setFromArray($loctionV);
                        $locationRow->save();
                    } catch (Exception $e) {
                        throw $e;
                    }

                    return;
                }

            }
        }
    }

}
