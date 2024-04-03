<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: ProfileController.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_ProfileController extends Core_Controller_Action_Standard {

  public function init() {
    // @todo this may not work with some of the content stuff in here, double-check
    $subject = null;
    if (!Engine_Api::_()->core()->hasSubject() &&
            ($id = $this->_getParam('id'))) {
      $contest_id = Engine_Api::_()->getDbtable('contests', 'sescontest')->getContestId($id);
      if ($contest_id) {
        $contest = Engine_Api::_()->getItem('contest', $contest_id);
        if ($contest)
          Engine_Api::_()->core()->setSubject($contest);
        else
          return $this->_forward('requireauth', 'error', 'core');
      } else
        return $this->_forward('requireauth', 'error', 'core');
    }

    $this->_helper->requireSubject();
    $this->_helper->requireAuth()->setNoForward()->setAuthParams(
            $subject, Engine_Api::_()->user()->getViewer(), 'view'
    );
  }

  public function indexAction() {
    $subject = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!$viewer->getIdentity())
      $level_id = 0;
    else
      $level_id = $viewer->level_id;
    if ((!$subject->is_approved || !$subject->draft ) && $level_id != 1 && $level_id != 2) {
      return $this->_forward('notfound', 'error', 'core');
    }

    if (!$this->_helper->requireAuth()->setAuthParams($subject, $viewer, 'view')->isValid()) {
      return;
    }

    // Check block
    if ($viewer->isBlockedBy($subject)) {
      return $this->_forward('requireauth', 'error', 'core');
    }

    // Increment view count
    if (!$subject->getOwner()->isSelf($viewer)) {
      $subject->view_count++;
      $subject->save();
    }

    /* Insert data for recently viewed widget */
    if ($viewer->getIdentity() != 0) {
      $dbObject = Engine_Db_Table::getDefaultAdapter();
      $dbObject->query('INSERT INTO engine4_sescontest_recentlyviewitems (resource_id, resource_type,owner_id,creation_date ) VALUES ("' . $subject->getIdentity() . '", "' . $subject->getType() . '","' . $viewer->getIdentity() . '",NOW())	ON DUPLICATE KEY UPDATE	creation_date = NOW()');
    }

    $getmodule = Engine_Api::_()->getDbTable('modules', 'core')->getModule('core');
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    if (!empty($getmodule->version) && version_compare($getmodule->version, '4.8.8') >= 0) {
      $view->doctype('XHTML1_RDFA');
      if ($subject->seo_title)
        $view->headTitle($subject->seo_title, 'SET');
      if ($subject->seo_keywords)
        $view->headMeta()->appendName('keywords', $subject->seo_keywords);
      if ($subject->seo_description)
        $view->headMeta()->appendName('description', $subject->seo_description);
    }
    if ($subject->conteststyle == 1)
      $page = 'sescontest_profile_index_1';
    elseif ($subject->conteststyle == 2)
      $page = 'sescontest_profile_index_2';
    elseif ($subject->conteststyle == 3)
      $page = 'sescontest_profile_index_3';
    elseif ($subject->conteststyle == 4)
      $page = 'sescontest_profile_index_4';

    $this->_helper->content->setContentName($page)->setEnabled();
  }

  //update cover photo function
  public function uploadCoverAction() {

    $contest = Engine_Api::_()->core()->getSubject();
    if (!$contest)
      return;
    $cover_photo = $contest->cover;
    if (isset($_FILES['Filedata']))
      $data = $_FILES['Filedata'];
    else if (isset($_FILES['webcam']))
      $data = $_FILES['webcam'];
    $contest->setCoverPhoto($data);
    if ($cover_photo != 0) {
      $im = Engine_Api::_()->getItem('storage_file', $cover_photo);
      $im->delete();
    }
    echo json_encode(array('file' => $contest->getCoverPhotoUrl()));
    die;
  }

  public function removeCoverAction() {
    $contest = Engine_Api::_()->core()->getSubject();
    if (!$contest)
      return false;
    if (isset($contest->cover) && $contest->cover > 0) {
      $im = Engine_Api::_()->getItem('storage_file', $contest->cover);
      $contest->cover = 0;
      $contest->save();
      $im->delete();
    }
    echo json_encode(array('file' => $contest->getCoverPhotoUrl()));
    die;
  }

  public function repositionCoverAction() {
    $contest_id = $this->_getParam('id', '0');
    if ($contest_id == 0)
      return;
    $contest = Engine_Api::_()->getItem('contest', $contest_id);
    if (!$contest)
      return;

    $position = $this->_getParam('position', '0');
    $contest->cover_position = $position;
    $contest->save();
    echo json_encode(array('status' => "1"));
    die;
  }

}
