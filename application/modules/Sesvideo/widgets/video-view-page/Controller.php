<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesvideo_Widget_VideoViewPageController extends Engine_Content_Widget_Abstract {

	public function indexAction() {
		$this->view->allowOptions = $this->_getParam('advSearchOptions',array('likeCount','commentCount','favouriteCount','rateCount','openVideoLightbox','editVideo','deleteVideo','shareAdvance','reportVideo','addToPlaylist','watchLater','favouriteCount'));
		$this->view->allowAdvShareOptions = $this->_getParam('advShareOptions',array('privateMessage','siteShare','quickShare','embed'));
		$this->view->limitLike = $this->_getParam('likelimit_data',11);
		$this->view->limitArtist = $this->_getParam('artistlimit_data',11);
		$this->view->limitFavourite = $this->_getParam('favouritelimit_data',11);
		$autoPlay = $this->_getParam('autoplay',0);
		$video = Engine_Api::_()->core()->getSubject('video');

		$viewer = Engine_Api::_()->user()->getViewer();
    //check dependent module sesprofile install or not
		$sesprofilelock_enable_module = is_string(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesprofilelock.enable.modules', 'a:2:{i:0;s:8:"sesvideo";i:1;s:8:"sesalbum";}')) ? unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesprofilelock.enable.modules', 'a:2:{i:0;s:8:"sesvideo";i:1;s:8:"sesalbum";}')) : Engine_Api::_()->getApi('settings', 'core')->getSetting('sesprofilelock.enable.modules', 'a:2:{i:0;s:8:"sesvideo";i:1;s:8:"sesalbum";}');
    //check dependent module sesprofile install or not
		if (Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('sesprofilelock'))  && engine_in_array('sesvideo',$sesprofilelock_enable_module) && $viewer->getIdentity() != $video->owner_id) {
      //member level check for lock videos
      $viewer = Engine_Api::_()->user()->getViewer();
      if ($viewer->getIdentity() == 0)
        $level = Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
      else
        $level = $viewer;
      $cookieData = isset($_COOKIE['sesvideo_lightbox_value']) ? $_COOKIE['sesvideo_lightbox_value'] : '';

      if ($video->is_locked && !engine_in_array($video->video_id,explode(',',$cookieData))) {
        $this->view->locked = true;
      } else {
        $this->view->locked = false;
      }
      $this->view->password = $video->password;
    } else
      $this->view->password = true;


    $this->view->videoTags = $video->tags()->getTagMaps();

    // check if embedding is allowed
    $can_embed = true;
		if (isset($video->allow_embed) && !$video->allow_embed) {
      $can_embed = false;
    }
    $this->view->can_embed = $can_embed;
    // increment count
    $embedded = "";
    $mine = true;
    if ($video->status == 1) {
      if (!$video->isOwner($viewer)) {
        $video->view_count++;
        $video->save();
        $mine = false;
      }
      $embedded = $video->getRichContent(true,array(),'',$autoPlay);
    }
    $this->view->videoEmbedded = $embedded;
    if ($video->type == 3 && $video->status == 1) {
      if (!empty($video->file_id)) {
        //Video sell work
        if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideosell') && $video->type == 3 && $video->price > 0 && $viewer->getIdentity() != $video->owner_id) {
          $sampleVideo = Engine_Api::_()->getItem('storage_file', $video->file_id);
          //$sampleVideo = Engine_Api::_()->getItem('sesvideo_samplevideo', $video->samplevideo_id);
          $storage_file = Engine_Api::_()->storage()->get($sampleVideo->file_id, $video->getType());
        }
        //Video sell work
        else {
          $storage_file = Engine_Api::_()->getItem('storage_file', $video->file_id);
        }
        if ($storage_file) {
          $this->view->video_location = $storage_file->map();
          $this->view->video_extension = $storage_file->extension;
          if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('videovod')){
            $this->view->str_url=$storage_file->map();
            $this->view->v_id=$video->video_id;
            $this->view->video_location = Engine_Api::_()->videovod()->returncurrentvideo($video->video_id,$storage_file->map());
            $this->view->video_extension = $storage_file->extension;
          }
        }
      }
    }
    // rating code
    $this->view->allowShowRating = $allowShowRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.ratevideo.show', 1);
    $this->view->allowRating = $allowRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.video.rating', 1);
    $this->view->getAllowRating = $allowRating;
    if ($allowRating == 0) {
      if ($allowShowRating == 0)
        $showRating = false;
      else
        $showRating = true;
    } else
      $showRating = true;
    $this->view->showRating = $showRating;
    if ($showRating) {
      $this->view->canRate = $canRate = Engine_Api::_()->authorization()->isAllowed('video', $viewer, 'rating');
      $this->view->allowRateAgain = $allowRateAgain = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.ratevideo.again', 1);
      $this->view->allowRateOwn = $allowRateOwn = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.ratevideo.own', 1);
      if ($canRate == 0 || $allowRating == 0)
        $allowRating = false;
      else
        $allowRating = true;
      if ($allowRateOwn == 0 && $mine)
        $allowMine = false;
      else
        $allowMine = true;

      $this->view->allowMine = $allowMine;
      $this->view->allowRating = $allowRating;
      $this->view->rating_type = $rating_type = 'video';
      $this->view->rating_count = $rating_count = Engine_Api::_()->getDbTable('ratings', 'sesvideo')->ratingCount($video->getIdentity(), $rating_type);
      $this->view->rated = $rated = Engine_Api::_()->getDbTable('ratings', 'sesvideo')->checkRated($video->getIdentity(), $viewer->getIdentity(), $rating_type);
      $rating_sum = Engine_Api::_()->getDbTable('ratings', 'sesvideo')->getSumRating($video->getIdentity(), $rating_type);
      if ($rating_count != 0) {
        $this->view->total_rating_average = $rating_sum / $rating_count;
      } else {
        $this->view->total_rating_average = 0;
      }
      if (!$allowRateAgain && $rated) {
        $rated = false;
      } else {
        $rated = true;
      }
      $this->view->ratedAgain = $rated;
    }
		$this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
		$this->view->can_edit = 0;
		$this->view->can_delete = 0;
		if($viewer->getIdentity() != 0){
			$this->view->can_edit = $canEdit = $video->authorization()->isAllowed($viewer, 'edit');
			$this->view->can_delete = $canDelete = $video->authorization()->isAllowed($viewer, 'delete');
		}
		$getmodule = Engine_Api::_()->getDbTable('modules', 'core')->getModule('core');
		if (!empty($getmodule->version) && version_compare($getmodule->version, '4.8.8') >= 0){
			$this->view->doctype('XHTML1_RDFA');
			$this->view->docActive = true;
		}
    // end rating code
    $this->view->viewer_id = $viewer->getIdentity();
    $this->view->video = $video;
    if ($video->category_id) {
      $this->view->category = Engine_Api::_()->sesvideo()->getCategory($video->category_id);
    }
	}
}
