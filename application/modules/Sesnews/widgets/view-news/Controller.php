<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Widget_ViewNewsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    // Check permission
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();
    $id = Zend_Controller_Front::getInstance()->getRequest()->getParam('news_id', null);
    $this->view->news_id = $news_id = Engine_Api::_()->getDbtable('news', 'sesnews')->getNewsId($id);
    if(!Engine_Api::_()->core()->hasSubject())
    $sesnews = Engine_Api::_()->getItem('sesnews_news', $news_id);
    else
    $sesnews = Engine_Api::_()->core()->getSubject();
    $sesnews_profilenews = Zend_Registry::isRegistered('sesnews_profilenews') ? Zend_Registry::get('sesnews_profilenews') : null;
    $show_criterias = $this->_getParam('showcriteria', array('title', 'description', 'photo', 'socialShare', 'ownerOptions', 'rating', 'postComment', 'likeButton', 'favouriteButton', 'view', 'like', 'comment', 'review', 'statics','shareButton','smallShareButton', 'originalnews'));
    if(is_array($show_criterias)){
      foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;
    }

    $this->view->socialshare_enable_plusicon = $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $this->_getParam('socialshare_icon_limit', 2);

    if (empty($sesnews_profilenews))
      return $this->setNoRender();
    // Prepare data
    $this->view->sesnews = $sesnews;
    $this->view->owner = $owner = $sesnews->getOwner();
    $this->view->viewer = $viewer;

    if( !$sesnews->isOwner($viewer) ) {
      Engine_Api::_()->getDbtable('news', 'sesnews')->update(array(
        'view_count' => new Zend_Db_Expr('view_count + 1'),
      ), array(
        'news_id = ?' => $sesnews->getIdentity(),
      ));
    }
    $this->view->canEdit = Engine_Api::_()->authorization()->isAllowed('sesnews_news', $viewer, 'edit');
    $this->view->canDelete = Engine_Api::_()->authorization()->isAllowed('sesnews_news', $viewer, 'delete');

    // Get tags
    $this->view->sesnewsTags = $sesnews->tags()->getTagMaps();

    // Get category
    if( !empty($sesnews->category_id) )
    $this->view->category = Engine_Api::_()->getDbtable('categories', 'sesnews')->find($sesnews->category_id)->current();

    // Get styles
    $table = Engine_Api::_()->getDbtable('styles', 'core');
    $style = $table->select()
      ->from($table, 'style')
      ->where('type = ?', 'user_sesnews')
      ->where('id = ?', $owner->getIdentity())
      ->limit(1)
      ->query()
      ->fetchColumn();
    if( !empty($style) ) {
      try {
        $this->view->headStyle()->appendStyle($style);
      }
      // silence any exception, exceptin in development mode
      catch (Exception $e) {
        if (APPLICATION_ENV === 'development') {
          throw $e;
        }
      }
    }
  }

}
