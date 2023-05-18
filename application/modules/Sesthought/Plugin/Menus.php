<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Menus.php  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesthought_Plugin_Menus
{
  public function canCreateThoughts()
  {
    // Must be logged in
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !$viewer || !$viewer->getIdentity() ) {
      return false;
    }

    // Must be able to create thoughts
    if( !Engine_Api::_()->authorization()->isAllowed('sesthought_thought', $viewer, 'create') ) {
      return false;
    }

    return true;
  }

  public function canViewThoughts()
  {
    $viewer = Engine_Api::_()->user()->getViewer();

    return true;
  }
}