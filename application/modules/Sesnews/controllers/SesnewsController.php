<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: SesnewsController.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_SesnewsController extends Core_Controller_Action_Standard
{
  public function init()
  {
    // Get viewer
    $viewer = Engine_Api::_()->user()->getViewer();

    // only show to member_level if authorized
    if( !$this->_helper->requireAuth()->setAuthParams('sesnews_news', null, 'view')->isValid() ) return;

    // Get subject
    if( ($sesnews_id = $this->_getParam('news_id',  $this->_getParam('id'))) &&
        ($sesnews = Engine_Api::_()->getItem('sesnews')) instanceof Sesnews_Model_Sesnews ) {
      Engine_Api::_()->core()->setSubject($sesnews);
    } else {
      $sesnews = null;
    }

    // Must have a subject
    if( !$this->_helper->requireSubject()->isValid() ) {
      return;
    }

    // Must be allowed to view this sesnews
    if( !$this->_helper->requireAuth()->setAuthParams($sesnews, $viewer, 'view')->isValid() ) {
      return;
    }
  }
}
