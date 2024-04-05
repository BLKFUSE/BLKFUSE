<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Core.php 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eticktokclone_Api_Core extends Core_Api_Abstract {

  public function getFollowStatus($user_id = 0) {
    if (!$user_id)
      return 0;
    $resource_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    if ($resource_id == 0)
      return false;
    $followTable = Engine_Api::_()->getDbtable('follows', 'eticktokclone');
    $follow = $followTable->select()->from($followTable->info('name'), new Zend_Db_Expr('COUNT(follow_id) as follow'))->where('resource_id =?', $resource_id)->where('user_id =?', $user_id)->limit(1)->query()->fetchColumn();
    if ($follow > 0) {
      return true;
    } else {
      return false;
    }
    return false;
  }
  public function getFollowCount($user_id = 0) {
    if (!$user_id)
      return 0;
    // $resource_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    // if ($resource_id == 0)
    //   return false;
    $followTable = Engine_Api::_()->getDbtable('follows', 'eticktokclone');
    $follow = $followTable->select()->from($followTable->info('name'), new Zend_Db_Expr('COUNT(follow_id) as follow'))->where('user_id =?', $user_id)->limit(1)->query()->fetchColumn();
    return $follow ? $follow : 0;
  }
  
  public function number_format_short( $n, $precision = 1 ) {
    if ($n < 900) {
      // 0 - 900
      $n_format = number_format($n, $precision);
      $suffix = '';
    } else if ($n < 900000) {
      // 0.9k-850k
      $n_format = number_format($n / 1000, $precision);
      $suffix = 'K';
    } else if ($n < 900000000) {
      // 0.9m-850m
      $n_format = number_format($n / 1000000, $precision);
      $suffix = 'M';
    } else if ($n < 900000000000) {
      // 0.9b-850b
      $n_format = number_format($n / 1000000000, $precision);
      $suffix = 'B';
    } else {
      // 0.9t+
      $n_format = number_format($n / 1000000000000, $precision);
      $suffix = 'T';
    }
    // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
    // Intentionally does not affect partials, eg "1.50" -> "1.50"
    if ( $precision > 0 ) {
      $dotzero = '.' . str_repeat( '0', $precision );
      $n_format = str_replace( $dotzero, '', $n_format );
    }
    return $n_format . $suffix;
  }
}
