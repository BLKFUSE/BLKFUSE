<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Menus.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Plugin_Menus {

  public function canCreateSesnews() {
    // Must be logged in
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !$viewer || !$viewer->getIdentity() )
    return false;

    // Must be able to create sesnews
    if( !Engine_Api::_()->authorization()->isAllowed('sesnews_news', $viewer, 'create') )
    return false;

    return true;
  }

  public function canCreateRss() {
    // Must be logged in
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !$viewer || !$viewer->getIdentity() )
        return false;

    if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.rss.enable', 1))
        return false;

    if(empty(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.mercurykey', '')))
        return false;

    if( !Engine_Api::_()->authorization()->isAllowed('sesnews_rss', $viewer, 'create') )
        return false;

    return true;
  }

  public function canNewsContributors() {

    if(!Engine_Api::_()->getDbtable("modules", "core")->isModuleEnabled("sesmember"))
      return false;
    else
      return true;
  }

  public function canViewSesnewsRequest() {
    // Must be logged in
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !$viewer || !$viewer->getIdentity() )
    return false;

    // Must be able to create sesnews
    if( !Engine_Api::_()->authorization()->isAllowed('sesnews_news', $viewer, 'create') )
    return false;

    return true;
  }

  public function canViewSesnews() {
    $viewer = Engine_Api::_()->user()->getViewer();
    // Must be able to view sesnews
    if( !Engine_Api::_()->authorization()->isAllowed('sesnews_news', $viewer, 'view') )
    return false;

    return true;
  }

  public function canViewRssnews() {
    $viewer = Engine_Api::_()->user()->getViewer();
    // Must be able to view sesnews
    if( !Engine_Api::_()->authorization()->isAllowed('sesnews_rss', $viewer, 'view') )
    return false;

    if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.rss.enable', 1))
        return false;
    if(empty(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.mercurykey', '')))
        return false;
    return true;
  }

  public function onMenuInitialize_SesnewsQuickStyle($row) {
    $viewer = Engine_Api::_()->user()->getViewer();
    $request = Zend_Controller_Front::getInstance()->getRequest();
    //if( $request->getParam('module') != 'sesnews' || $request->getParam('action') != 'manage' )
    return false;

    // Must be able to style sesnews
    if( !Engine_Api::_()->authorization()->isAllowed('sesnews_news', $viewer, 'style') )
    return false;

    return true;
  }

  public function onMenuInitialize_SesnewsGutterShare($row) {
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !$viewer->getIdentity() || !Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1))
    return false;

    if( !Engine_Api::_()->core()->hasSubject() )
    return false;

    $subject = Engine_Api::_()->core()->getSubject();
    if( !($subject instanceof Sesnews_Model_News) )
    return false;

    // Modify params
    $params = $row->params;
    $params['params']['type'] = $subject->getType();
    $params['params']['id'] = $subject->getIdentity();
    $params['params']['format'] = 'smoothbox';
    return $params;
  }

  public function onMenuInitialize_SesnewsGutterReport($row) {
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !$viewer->getIdentity() || !Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.report', 1))
    return false;

    if( !Engine_Api::_()->core()->hasSubject() )
    return false;

    $subject = Engine_Api::_()->core()->getSubject();
    if( ($subject instanceof Sesnews_Model_News) &&
        $subject->owner_id == $viewer->getIdentity() ) {
      return false;
    } else if( $subject instanceof User_Model_User &&
        $subject->getIdentity() == $viewer->getIdentity() ) {
      return false;
    }

    // Modify params
    $subject = Engine_Api::_()->core()->getSubject();
    $params = $row->params;
    $params['params']['subject'] = $subject->getGuid();
    return $params;
  }

  public function onMenuInitialize_SesnewsGutterCreate($row) {

    $viewer = Engine_Api::_()->user()->getViewer();
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $owner = Engine_Api::_()->getItem('user', $request->getParam('user_id'));

    if( $viewer->getIdentity() != $owner->getIdentity() )
    return false;

    if( !Engine_Api::_()->authorization()->isAllowed('sesnews_news', $viewer, 'create') )
    return false;

    return true;
  }


  public function onMenuInitialize_SesnewsGutterStyle($row){
			return true;
	}
	public function onMenuInitialize_SesnewsGutterEdit($row){
		 return true;
	}
  public function onMenuInitialize_SesnewsGutterDashboard($row) {

    if( !Engine_Api::_()->core()->hasSubject())
        return false;

    $viewer = Engine_Api::_()->user()->getViewer();
    $sesnews = Engine_Api::_()->core()->getSubject('sesnews_news');
    if( !Engine_Api::_()->authorization()->getPermission($viewer, 'sesnews_news', 'edit'))
        return false;

    // Modify params
    $params = $row->params;
    $params['params']['news_id'] = $sesnews->custom_url;
    return $params;
  }

  public function onMenuInitialize_SesnewsGutterDelete($row) {

    if( !Engine_Api::_()->core()->hasSubject())
    return false;

    $viewer = Engine_Api::_()->user()->getViewer();
    $sesnews = Engine_Api::_()->core()->getSubject('sesnews_news');

    if( !$sesnews->authorization()->isAllowed($viewer, 'delete'))
    return false;

    // Modify params
    $params = $row->params;
    $params['params']['news_id'] = $sesnews->getIdentity();
    return $params;
  }

  public function onMenuInitialize_SesnewsreviewProfileEdit() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $review = Engine_Api::_()->core()->getSubject();

    if($review->owner_id != $viewer->getIdentity())
	  return false;

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.allow.review', 1))
    return false;

    if (!$viewer->getIdentity())
    return false;

    if (!$review->authorization()->isAllowed($viewer, 'edit'))
    return false;

    return array(
        'label' => 'Edit Review',
        'icon' => 'application/modules/Sesbasic/externals/images/edit.png',
        'route' => 'sesnewsreview_view',
        'params' => array(
            'action' => 'edit',
            'review_id' => $review->getIdentity(),
            'slug' => $review->getSlug(),
        )
    );
  }

  public function onMenuInitialize_SesnewsreviewProfileReport() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $review = Engine_Api::_()->core()->getSubject();

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.show.report', 1) || !Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.report', 1))
    return false;

    if($review->owner_id == $viewer->getIdentity())
	  return false;

    if (!$viewer->getIdentity())
    return false;

    return array(
        'label' => 'Report',
        'icon' => 'application/modules/Sesbasic/externals/images/report.png',
        'class' => 'smoothbox',
        'route' => 'default',
        'params' => array(
            'module' => 'core',
            'controller' => 'report',
            'action' => 'create',
            'subject' => $review->getGuid(),
            'format' => 'smoothbox',
        ),
    );
  }

  public function onMenuInitialize_SesnewsreviewProfileShare() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $review = Engine_Api::_()->core()->getSubject();

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.allow.share', 1) || !Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1))
    return false;

    if (!$viewer->getIdentity())
    return false;

    return array(
        'label' => 'Share',
        'icon' => 'application/modules/Sesbasic/externals/images/share.png',
        'class' => 'smoothbox',
        'route' => 'default',
        'params' => array(
            'module' => 'activity',
            'controller' => 'index',
            'action' => 'share',
            'type' => $review->getType(),
            'id' => $review->getIdentity(),
            'format' => 'smoothbox',
        ),
    );
  }

  public function onMenuInitialize_SesnewsreviewProfileDelete() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $review = Engine_Api::_()->core()->getSubject();

    if (!$viewer->getIdentity())
    return false;

    if($review->owner_id != $viewer->getIdentity())
	  return false;

		if (!$review->authorization()->isAllowed($viewer, 'delete'))
    return false;

    return array(
        'label' => 'Delete Review',
        'icon' => 'application/modules/Sesbasic/externals/images/delete.png',
        'class' => 'smoothbox',
        'route' => 'sesnewsreview_view',
        'params' => array(
            'action' => 'delete',
            'review_id' => $review->getIdentity(),
            'format' => 'smoothbox',
        ),
    );
  }

  public function reviewEnable() {
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.allow.review', 1) || !Engine_Api::_()->sesbasic()->getViewerPrivacy('sesnews_review', 'view')) {
      return false;
    }
    return true;
  }

  public function locationEnable() {
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.location', 1)) {
      return false;
    }
    if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1))
      return false;
    return true;
  }

}
