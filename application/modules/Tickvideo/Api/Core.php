<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Core.php 2020-11-03  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Tickvideo_Api_Core extends Core_Api_Abstract
{
// handle video upload
    public function createVideo($params, $file, $values,$video_date = false) {

        if ($file instanceof Storage_Model_File) {
            $params['file_id'] = $file->getIdentity();
        } else {
            // create video item
            if(!$video_date){
                $video = Engine_Api::_()->getDbtable('videos', 'sesvideo')->createRow();
                $file_ext = pathinfo($file['name']);
                $file_ext = $file_ext['extension'];
            }else{
                $video = $video_date;
            }

            // Store video in temporary storage object for ffmpeg to handle
            $storage = Engine_Api::_()->getItemTable('storage_file');
            $params = array(
                'parent_id' => $video->getIdentity(),
                'parent_type' => $video->getType(),
                'user_id' => $video->owner_id,
                'mime_major' => 'video',
                'mime_minor' => $file_ext,
            );
            if(!$video_date){
                $video->code = $file_ext;
                $storageObject = $storage->createFile($file, $params);
                $video->file_id = $file_id = $storageObject->file_id;
            }
            // Remove temporary file
            //@unlink($file['tmp_name']);
            $video->save();

            $video->status = 2;
            $video->save();
            // Add to jobs
            Engine_Api::_()->getDbtable('jobs', 'core')->addJob('tickvideo_encode', array(
                'video_id' => $video->getIdentity(),
                'type' => 'mp4',
            ));

        }
        return $video;
    }
}

