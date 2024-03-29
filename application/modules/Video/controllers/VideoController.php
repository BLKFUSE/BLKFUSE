<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Video
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: VideoController.php 9747 2012-07-26 02:08:08Z john $
 * @author     Jung
 */

/**
 * @category   Application_Extensions
 * @package    Video
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Video_VideoController extends Core_Controller_Action_Standard
{
    public function init()
    {
        // Must be able to use videos
        if (!$this->_helper->requireAuth()->setAuthParams('video', null, 'view')->isValid()) {
            return;
        }

        // Get subject
        $video = null;
        $id = $this->_getParam('video_id', $this->_getParam('id', null));
        if ($id) {
            $video = Engine_Api::_()->getItem('video', $id);
            if ($video) {
                Engine_Api::_()->core()->setSubject($video);
            }
        }

        // Require subject
        if (!$this->_helper->requireSubject()->isValid()) {
            return;
        }

        // Require auth
        if (!$this->_helper->requireAuth()->setAuthParams($video, null, 'view')->isValid()) {
            return;
        }
    }

    public function embedAction()
    {
        // Get subject
        $this->view->video = $video = Engine_Api::_()->core()->getSubject('video');

        // Check if embedding is allowed
        if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('video.embeds', 1)) {
            $this->view->error = 1;
            return;
        } elseif (isset($video->allow_embed) && !$video->allow_embed) {
            $this->view->error = 2;
            return;
        }

        // Get embed code
        $this->view->embedCode = $video->getEmbedCode();
    }

    public function externalAction()
    {
        // Get subject
        $this->view->video = $video = Engine_Api::_()->core()->getSubject('video');

        // Check if embedding is allowed
        if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('video.embeds', 1)) {
            $this->view->error = 1;
            return;
        } elseif (isset($video->allow_embed) && !$video->allow_embed) {
            $this->view->error = 2;
            return;
        }

        // Get embed code
        $embedded = "";
        if ($video->status == 1) {
            $video->view_count++;
            $video->save();
            $embedded = $video->getRichContent(true);
        }

        // Track views from external sources
        Engine_Api::_()->getDbtable('statistics', 'core')
            ->increment('video.embedviews');

        // Get file location
        if ($video->type == 'upload' && $video->status == 1) {
            if (!empty($video->file_id)) {
                $storage_file = Engine_Api::_()->getItem('storage_file', $video->file_id);
                if ($storage_file) {
                    $this->view->video_location = $storage_file->map();
                    $this->view->video_extension = $storage_file->extension;
                }
            }
        }

        $this->view->rating_count = Engine_Api::_()->getDbTable('ratings', 'video')->ratingCount($video->getIdentity());
        $this->view->video = $video;
        $this->view->videoEmbedded = $embedded;
        if ($video->category_id != 0) {
            $this->view->category = Engine_Api::_()->getDbTable('ratings', 'video')->getCategory($video->category_id);
        }
    }
}
