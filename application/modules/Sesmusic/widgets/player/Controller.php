<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesmusic_Widget_PlayerController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $callFromIframe =  $this->_getParam('fromIframe',false);
    if(!$callFromIframe && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sessiteiframe') && !$_SESSION['sespwa']['sespwa']){
      return $this->setNoRender();
    }
    $viewer = Engine_Api::_()->user()->getViewer();

    $this->view->downloadAlbumSong = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'download_song');
    $this->view->canAddPlaylistAlbumSong = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'playlist_song');
    //Songs settings.
    $this->view->songlink = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.songlink'));
    $canView = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'view');
    if (empty($canView))
      return $this->setNoRender();
  }

}
