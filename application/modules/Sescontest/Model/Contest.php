<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Contest.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Model_Contest extends Core_Model_Item_Abstract {

  protected $_parent_type = 'user';
  protected $_owner_type = 'user';
  protected $_type = 'contest';
  protected $_statusChanged;
  
  public function _postInsert() {
    parent::_postInsert();
    // Create auth stuff
    $context = Engine_Api::_()->authorization()->context;
    $context->setAllowed($this, 'everyone', 'view', true);
    $context->setAllowed($this, 'registered', 'comment', true);
    $viewer = Engine_Api::_()->user()->getViewer();
  }

  public function setPhoto($photo, $isShareContent = false) {
    if ($photo instanceof Zend_Form_Element_File) {
      $file = $photo->getFileName();
    } elseif (is_array($photo) && !empty($photo['tmp_name'])) {
      $file = $photo['tmp_name'];
    } elseif (is_string($photo) && file_exists($photo)) {
      $file = $photo;
    } else {
      throw new Sescontest_Model_Exception('Invalid argument passed to setPhoto: ' . print_r($photo, 1));
    }

    $name = basename($file);
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $params = array(
        'parent_type' => 'contest',
        'parent_id' => $this->getIdentity()
    );

    $core_settings = Engine_Api::_()->getApi('settings', 'core');
    $main_height = $core_settings->getSetting('sescontest.mainheight', 1600);
    $main_width = $core_settings->getSetting('sescontest.mainwidth', 1600);
    $normal_height = $core_settings->getSetting('sescontest.normalheight', 500);
    $normal_width = $core_settings->getSetting('sescontest.normalwidth', 500);

    // Save
    $storage = Engine_Api::_()->storage();

    // Resize image (main)
    $image = Engine_Image::factory();
    $image->open($file)
            ->resize($main_width, $main_height)
            ->write($path . '/m_' . $name)
            ->destroy();

    // Resize image (profile)
    $image = Engine_Image::factory();
    $image->open($file)
            ->resize($normal_width, $normal_height)
            ->write($path . '/p_' . $name)
            ->destroy();

    // Resize image (normal)
    $image = Engine_Image::factory();
    $image->open($file)
            ->resize($normal_width, $normal_height)
            ->write($path . '/in_' . $name)
            ->destroy();

    // Resize image (icon)
    $image = Engine_Image::factory();
    $image->open($file);

    $size = min($image->height, $image->width);
    $x = ($image->width - $size) / 2;
    $y = ($image->height - $size) / 2;

    $image->resample($x, $y, $size, $size, 48, 48)
            ->write($path . '/is_' . $name)
            ->destroy();

    // Store
    $iMain = $storage->create($path . '/m_' . $name, $params);
    $iProfile = $storage->create($path . '/p_' . $name, $params);
    $iIconNormal = $storage->create($path . '/in_' . $name, $params);
    $iSquare = $storage->create($path . '/is_' . $name, $params);

    $iMain->bridge($iProfile, 'thumb.profile');
    $iMain->bridge($iIconNormal, 'thumb.normal');
    $iMain->bridge($iSquare, 'thumb.icon');

    // Remove temp files
    @unlink($path . '/p_' . $name);
    @unlink($path . '/m_' . $name);
    @unlink($path . '/in_' . $name);
    @unlink($path . '/is_' . $name);

    // Update row
    $this->modified_date = date('Y-m-d H:i:s');
    if ($isShareContent == false)
      $this->photo_id = $iMain->getIdentity();
    $this->save();

    return $this;
  }

  public function setBackgroundPhoto($photo) {
    if ($photo instanceof Zend_Form_Element_File) {
      $file = $photo->getFileName();
      $name = $photo->getFileName();
    } else if (is_array($photo) && !empty($photo['tmp_name'])) {
      $file = $photo['tmp_name'];
      $name = $photo['name'];
    } else if (is_string($photo) && file_exists($photo)) {
      $file = $photo;
      $name = $photo;
    } else {
      throw new Sescontest_Model_Exception('invalid argument passed to setPhoto');
    }
    $name = basename($name);
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $params = array(
        'parent_id' => $this->getIdentity(),
        'parent_type' => 'contest'
    );

    // Save
    $storage = Engine_Api::_()->storage();
    // Resize image (main)
    copy($file, $path . '/m_' . $name);
    // Store
    $iMain = $storage->create($path . '/m_' . $name, $params);
    // Remove temp files
    @unlink($path . '/m_' . $name);
    // Update row
    $this->background_photo_id = $iMain->file_id;
    $this->save();
    return $this;
  }

  public function setCoverPhoto($photo) {
    if ($photo instanceof Zend_Form_Element_File) {
      $file = $photo->getFileName();
      $fileName = $file;
    } else if ($photo instanceof Storage_Model_File) {
      $file = $photo->temporary();
      $fileName = $photo->name;
    } else if ($photo instanceof Core_Model_Item_Abstract && !empty($photo->file_id)) {
      $tmpRow = Engine_Api::_()->getItem('storage_file', $photo->file_id);
      $file = $tmpRow->temporary();
      $fileName = $tmpRow->name;
    } else if (is_array($photo) && !empty($photo['tmp_name'])) {
      $file = $photo['tmp_name'];
      $fileName = $photo['name'];
    } else if (is_string($photo) && file_exists($photo)) {
      $file = $photo;
      $fileName = $photo;
      $unlink = false;
    } else {
      throw new User_Model_Exception('invalid argument passed to setPhoto');
    }
    $name = basename($file);
    $extension = ltrim(strrchr($fileName, '.'), '.');
    $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');

    if (!$fileName) {
      $fileName = $file;
    }
    $filesTable = Engine_Api::_()->getDbtable('files', 'storage');
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $params = array(
        'parent_type' => $this->getType(),
        'parent_id' => $this->getIdentity(),
        'user_id' => $this->user_id,
        'name' => $fileName,
    );
    // Resize image (main)
    $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;
    $image = Engine_Image::factory();
    $image->open($file)
            ->resize(1400, 1400)
            ->write($mainPath)
            ->destroy();

    // Store
    try {
      $iMain = $filesTable->createFile($mainPath, $params);
    } catch (Exception $e) {
      @unlink($file);
      // Remove temp files
      @unlink($mainPath);

      // Throw
      if ($e->getCode() == Storage_Model_DbTable_Files::SPACE_LIMIT_REACHED_CODE) {
        throw new Sesevent_Model_Exception($e->getMessage(), $e->getCode());
      } else {
        throw $e;
      }
    }
    if (!isset($unlink))
      @unlink($file);
    // Remove temp files
    @unlink($mainPath);

    // Update row
    $this->cover = $iMain->file_id;
    $this->save();
    // Delete the old file?
    if (!empty($tmpRow)) {
      $tmpRow->delete();
    }
    return $this;
  }

  public function getDescription($length = 255) {
    // @todo decide how we want to handle multibyte string functions
    $tmpBody = strip_tags($this->description);
    return ( Engine_String::strlen($tmpBody) > $length ? Engine_String::substr($tmpBody, 0, $length) . '...' : $tmpBody );
  }

  public function getTitle() {
    return $this->title;
  }

  /**
   * Gets an absolute URL to the page to view this item
   *
   * @return string
   */
  public function getHref($params = array()) {
    $params = array_merge(array(
        'route' => 'sescontest_profile',
        'reset' => true,
        'id' => $this->custom_url,
            ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
                    ->assemble($params, $route, $reset);
  }

  protected function _delete() {
    if ($this->_disableHooks)
      return;
    $db = Engine_Db_Table::getDefaultAdapter();

    // Delete all albums
    $participantTable = Engine_Api::_()->getItemTable('participant');
    $participantSelect = $participantTable->select()->where('contest_id = ?', $this->getIdentity());
    foreach ($participantTable->fetchAll($participantSelect) as $participant) {
      $participant->delete();
    }
    $db->query("DELETE FROM engine4_sescontest_favourites WHERE resource_type = 'contest' && resource_id = " . $this->getIdentity());
    $db->query("DELETE FROM engine4_sescontest_followers WHERE resource_type = 'contest' && resource_id = " . $this->getIdentity());
    parent::_delete();
  }

  public function categoryName() {
    $categoryTable = Engine_Api::_()->getDbtable('categories', 'sescontest');
    return $categoryTable->select()
                    ->from($categoryTable, 'title')
                    ->where('category_id = ?', $this->category_id)
                    ->limit(1)
                    ->query()
                    ->fetchColumn();
  }

  /**
   * Gets a proxy object for the tags handler
   *
   * @return Engine_ProxyObject
   * */
  public function tags() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('tags', 'core'));
  }

  /**
   * Gets a proxy object for the comment handler
   *
   * @return Engine_ProxyObject
   * */
  public function comments() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('comments', 'core'));
  }

  /**
   * Gets a proxy object for the like handler
   *
   * @return Engine_ProxyObject
   * */
  public function likes() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('likes', 'core'));
  }

  public function getCoverPhotoUrl() {
    $photo_id = $this->cover;
    if ($photo_id) {
      $file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->cover);
      if ($file)
        return $file->map();
    }
    if ($this->contest_type == 1)
      $type = 'textContCPhoto';
    elseif ($this->contest_type == 2)
      $type = 'photoContCPhoto';
    elseif ($this->contest_type == 3)
      $type = 'videoContCPhoto';
    else
      $type = 'musicContCPhoto';
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)) {
      $params = Engine_Api::_()->getItem('sescontestpackage_package', $this->package_id)->params;
      $params = json_decode($params, true);
      if (isset($params[$type]))
        $defaultPhoto = $params[$type];
      else
        $defaultPhoto = '';
    }
    else {
      $user = Engine_Api::_()->getItem('user', $this->user_id);
      if($user->getIdentity()) {
        $defaultPhoto = Engine_Api::_()->authorization()->getPermission($user, 'contest', $type);
      }
    }
    if (!$defaultPhoto) {
      $defaultPhoto = 'application/modules/Sescontest/externals/images/blank.png';
    }
    return $defaultPhoto;
  }

  public function getPhotoUrl($type = NULL) {
    $photo_id = $this->photo_id;
    if ($photo_id) {
      $file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->photo_id, $type);
      if ($file)
        return $file->map();
      else {
        $file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->photo_id, 'thumb.profile');
        if ($file)
          return $file->map();
      }
    }
    if ($this->contest_type == 1)
      $type = 'textContPhoto';
    elseif ($this->contest_type == 2)
      $type = 'photoContPhoto';
    elseif ($this->contest_type == 3)
      $type = 'videoContPhoto';
    else
      $type = 'musicContPhoto';

    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)) {
      $params = Engine_Api::_()->getItem('sescontestpackage_package', $this->package_id)->params;
      $params = json_decode($params, true);
      if (isset($params[$type]))
        $defaultPhoto = $params[$type];
      else
        $defaultPhoto = '';
    } else {
      $user = Engine_Api::_()->getItem('user', $this->user_id);
      if($user->getIdentity())
        $defaultPhoto = Engine_Api::_()->authorization()->getPermission($user, 'contest', $type);
    }
    if (!$defaultPhoto) {
      $defaultPhoto = 'application/modules/Sescontest/externals/images/nophoto_contest_thumb_profile.png';
    }
    return $defaultPhoto;
  }

  /**
   * Get a generic media type. Values:
   * contest
   *
   * @return string
   */
  public function getMediaType() {
    return "contest";
  }

  public function cancel() {
    $package = $this->getPackage();
    if ($package->isFree()) {
      return true;
    }
    //update transaction_id to other contest of same package contest
    if ($this->transaction_id) {
      $transaction = $this->getTransaction();
      $table = Engine_Api::_()->getDbTable('contests', 'sescontest');
      $tableName = $table->info('name');
      //select contest in package with our transaction id.
      $select = $table->select()->from($tableName)->where('transaction_id =?', '')->where('orderspackage_id =?', $this->orderspackage_id);
      $contest = $table->fetchRow($select);
      if ($contest) {
        $contest->transaction_id = $this->transaction_id;
        $contest->save();
        //update order
        $order_id = $transaction->order_id;
        $order = Engine_Api::_()->getItem('payment_order', $order_id);
        if ($order) {
          $order->source_id = $contest->getIdentity();
          $order->save();
        }
        //update item count in order package
        $orderpackage = Engine_Api::_()->getItem('sescontestpackage_orderspackage', $this->orderspackage_id);
        $orderpackage->item_count = $orderpackage->item_count + 1;
        $orderpackage->save();
        return true;
      } else {
        //delete order package
        $orderpackage = Engine_Api::_()->getItem('sescontestpackage_orderspackage', $this->orderspackage_id);
        if ($orderpackage)
          $orderpackage->delete();
      }
    }else {
      //update item count in order package
      $orderpackage = Engine_Api::_()->getItem('sescontestpackage_orderspackage', $this->orderspackage_id);
      $orderpackage->item_count = $orderpackage->item_count + 1;
      $orderpackage->save();
      return true;
    }
    // Try to cancel recurring payments in the gateway
    if (!empty($transaction->gateway_id) && !empty($transaction->gateway_profile_id) && empty($transaction->gateway_transaction_id)) {
      try {

        $gateway = Engine_Api::_()->getItem('sescontestpackage_gateway', $transaction->gateway_id);
        if ($gateway) {
          $gatewayPlugin = $gateway->getPlugin();
          if (method_exists($gatewayPlugin, 'cancelContest')) {
            $gatewayPlugin->cancelContest($transaction->gateway_profile_id);
          }
        }
      } catch (Exception $e) {
        // Silence?
      }
    }
    return $this;
  }

  public function getPackage() {
    return Engine_Api::_()->getItem('sescontestpackage_package', $this->package_id);
  }

  public function getTransaction() {
    return Engine_Api::_()->getItem('sescontestpackage_transaction', $this->transaction_id);
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
      $this->is_approved = 1;
    }
    $this->save();
    return $this;
  }

  public function changeApprovedStatus($approved = 0) {
    $transaction = $this->getTransaction();
    $orderPackageId = $this->orderspackage_id;
    if ($transaction && $orderPackageId) {
      $this->is_approved = $approved;
      $this->save();
      Engine_Api::_()->getDbtable('contests', 'sescontest')->update(array('is_approved' => $approved), array('orderspackage_id' => $orderPackageId));
      if ($approved) {
        $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
        $action = $activityApi->addActivity(Engine_Api::_()->user()->getViewer(), $this, 'sescontest_create');
        if ($action) {
          $activityApi->attachActivity($action, $this);
        }
      }
    }
  }

  public function onPaymentSuccess() {
    $this->_statusChanged = false;
    $transaction = $this->getTransaction();
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
        //get custom feature of package
        $params = json_decode($package->params, true);
        $approved = 0;
        if (isset($params['contest_approve']) && $params['contest_approve'])
          $approved = 1;
        if (isset($params['contest_featured']) && $params['contest_featured'])
          $this->featured = 1;
        if (isset($params['contest_sponsored']) && $params['contest_sponsored'])
          $this->sponsored = 1;
        if (isset($params['contest_verified']) && $params['contest_verified'])
          $this->verified = 1;
        if (isset($params['contest_hot']) && $params['contest_hot'])
          $this->hot = 1;
        $this->save();

        if (!$approved) {
          Engine_Api::_()->sescontest()->sendMailNotification(array('contest' => $this));
        }

        //check isonetime condition and renew exiration date if left
        $daysLeft = 0;
        if ($package->isOneTime() && !empty($transaction->expiration_date) && $transaction->expiration_date != '3000-00-00 00:00:00') {
          $datediff = strtotime($transaction->expiration_date) - time();
          $daysLeft = floor($datediff / (60 * 60 * 24));
        }
        $orderPackage = Engine_Api::_()->getItem('sescontestpackage_orderspackage', $this->orderspackage_id);

        if ($expiration) {
          $expiration_date = date('Y-m-d H:i:s', $expiration);
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
          $transaction->expiration_date = '3000-00-00 00:00:00';
          $orderPackage->expiration_date = '3000-00-00 00:00:00';
          $orderPackage->save();
        }
        //update all items in the transaction
        $this->changeApprovedStatus($approved);
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
      $this->changeApprovedStatus(0);
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
      $this->changeApprovedStatus(0);
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
      $this->changeApprovedStatus(0);
      // Change status
      if ($transaction->state != 'expired') {
        $transaction->state = 'expired';
        $this->_statusChanged = true;
        $transaction->save();
      }
    }

    return $this;
  }

  public function onRefund() {
    $this->_statusChanged = false;
    $transaction = $this->getTransaction();
    if ($transaction && engine_in_array($transaction->state, array('initial', 'trial', 'pending', 'active', 'refunded'))) {
      //update all items in the transaction
      $this->changeApprovedStatus(0);
      // Change status
      if ($transaction->state != 'refunded') {
        $transaction->state = 'refunded';
        $this->_statusChanged = true;
        $transaction->save();
      }
    }
    return $this;
  }

}
