<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Encode.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
class Sescontest_Plugin_Job_Encode extends Core_Plugin_Job_Abstract {

  protected function _execute() {
    // Get job and params
    $job = $this->getJob();

    // No contest id?
    if (!($contest_id = $this->getParam('participant_id'))) {
      $this->_setState('failed', 'No contest identity provided.');
      $this->_setWasIdle();
      return;
    }

    // Get contest object
    $contest = Engine_Api::_()->getItem('participant', $contest_id);
    if (!$contest || !($contest instanceof Sescontest_Model_Participant)) {
      $this->_setState('failed', 'Contest is missing.');
      $this->_setWasIdle();
      return;
    }
    $type = $this->getParam('type');
    $type = empty($type) ? 'flv' : $this->getParam('type');

    // Process
    try {
      $this->_process($contest, $type);
      $this->_setIsComplete(true);
    } catch (Exception $e) {
      $this->_setState('failed', 'Exception: ' . $e->getMessage());

      // Attempt to set contest state to failed
      try {
        if (1 != $contest->status) {
          $contest->status = 3;
          $contest->save();
        }
      } catch (Exception $e) {
        $this->_addMessage($e->getMessage());
      }
    }
  }

  private function getFFMPEGPath() {
    // Check we can execute
    if (!function_exists('shell_exec')) {
      throw new Sescontest_Model_Exception('Unable to execute shell commands using shell_exec(); the function is disabled.');
    }

    if (!function_exists('exec')) {
      throw new Sescontest_Model_Exception('Unable to execute shell commands using exec(); the function is disabled.');
    }

    // Make sure FFMPEG path is set
    $ffmpeg_path = Engine_Api::_()->getApi('settings', 'core')->sescontest_ffmpeg_path;
    if (!$ffmpeg_path) {
      throw new Sescontest_Model_Exception('Ffmpeg not configured');
    }

    // Make sure FFMPEG can be run
    if (!@file_exists($ffmpeg_path) || !@is_executable($ffmpeg_path)) {
      $output = null;
      $return = null;
      exec($ffmpeg_path . ' -version', $output, $return);

      if ($return > 0) {
        throw new Sescontest_Model_Exception('Ffmpeg found, but is not executable');
      }
    }

    return $ffmpeg_path;
  }

  private function getTmpDir() {
    // Check the contest temporary directory
    $tmpDir = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary' .
            DIRECTORY_SEPARATOR . 'sescontest';

    if (!is_dir($tmpDir) && !mkdir($tmpDir, 0777, true)) {
      throw new Sescontest_Model_Exception('Contest temporary directory did not exist and could not be created.');
    }

    if (!is_writable($tmpDir)) {
      throw new Sescontest_Model_Exception('Contest temporary directory is not writable.');
    }

    return $tmpDir;
  }

  private function getContest($contest) {
    // Get the contest object
    if (is_numeric($contest)) {
      $contest = Engine_Api::_()->getItem('participant', $contest);
    }

    if (!($contest instanceof Sescontest_Model_Participant)) {
      throw new Sescontest_Model_Exception('Argument was not a valid contest');
    }

    return $contest;
  }

  private function getStorageObject($contest) {
    // Pull contest from storage system for encoding
    $storageObject = Engine_Api::_()->getItem('storage_file', $contest->file_id);

    if (!$storageObject) {
      throw new Sescontest_Model_Exception('Contest storage file was missing');
    }

    return $storageObject;
  }

  private function getOriginalPath($storageObject) {
    $originalPath = $storageObject->temporary();

    if (!file_exists($originalPath)) {
      throw new Sescontest_Model_Exception('Could not pull to temporary file');
    }

    return $originalPath;
  }

  private function getContestFilters($contest, $width, $height) {
    $filters = "scale=$width:$height";

    if ($contest->rotation > 0) {
      $filters = "pad='max(iw,ih*($width/$height))':ow/($width/$height):(ow-iw)/2:(oh-ih)/2,$filters";

      if ($contest->rotation == 180)
        $filters = "hflip,vflip,$filters";
      else {
        $transpose = array(90 => 1, 270 => 2);

        if (empty($transpose[$contest->rotation]))
          throw new Sescontest_Model_Exception('Invalid rotation value');

        $filters = "transpose=${transpose[$contest->rotation]},$filters";
      }
    }

    return $filters;
  }

  private function conversionSucceeded($contest, $contestOutput, $outputPath) {
    $success = true;

    // Unsupported format
    if (preg_match('/Unknown format/i', $contestOutput) ||
            preg_match('/Unsupported codec/i', $contestOutput) ||
            preg_match('/patch welcome/i', $contestOutput) ||
            preg_match('/Audio encoding failed/i', $contestOutput) ||
            !is_file($outputPath) ||
            filesize($outputPath) <= 0) {
      $success = false;
      $contest->status = 3;
    }

    // This is for audio files
    else if (preg_match('/video:0kB/i', $contestOutput)) {
      $success = false;
      $contest->status = 5;
    }

    return $success;
  }

  private function notifyOwner($contest, $owner) {
    $translate = Zend_Registry::get('Zend_Translate');
    $language = !empty($owner->language) && $owner->language != 'auto' ? $owner->language : null;

    $notificationMessage = '';
    $exceptionMessage = 'Unknown encoding error.';

    if ($contest->status == 3) {
      $exceptionMessage = 'Contest format is not supported by FFMPEG.';
      $notificationMessage = 'Contest conversion failed. Contest format is not supported by FFMPEG. Please try %1$sagain%2$s.';
    } else if ($contest->status == 5) {
      $exceptionMessage = 'Audio-only files are not supported.';
      $notificationMessage = 'Contest conversion failed. Audio files are not supported. Please try %1$sagain%2$s.';
    } else if ($contest->status == 7) {
      $notificationMessage = 'Contest conversion failed. You may be over the site upload limit.  Try %1$suploading%2$s a smaller file, or delete some files to free up space.';
    }

    $notificationMessage = $translate->translate(sprintf($notificationMessage, '', ''), $language);

    Engine_Api::_()->getDbtable('notifications', 'activity')
            ->addNotification($owner, $owner, $contest, 'sescontest_processed_failed', array(
                'message' => $notificationMessage,
                'message_link' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'manage'), 'sescontest_general', true),
    ));

    return $exceptionMessage;
  }

  private function getDuration($contestOutput) {
    $duration = 0;

    if (preg_match('/Duration:\s+(.*?)[.]/i', $contestOutput, $matches)) {
      list($hours, $minutes, $seconds) = preg_split('[:]', $matches[1]);
      $duration = ceil($seconds + ($minutes * 60) + ($hours * 3600));
    }

    return $duration;
  }

  private function generateThumbnail($outputPath, $output, $thumb_splice, $thumbPath, $log, $contest) {
    if ($contest->photo_id && !is_null($contest->photo_id))
      return false;
    $ffmpeg_path = $this->getFFMPEGPath();
    // Thumbnail process command
    $thumbCommand = $ffmpeg_path . ' '
            . '-i ' . escapeshellarg($outputPath) . ' '
            . '-f image2' . ' '
            . '-ss ' . $thumb_splice . ' '
            . '-vframes 1' . ' '
            . '-v 2' . ' '
            . '-y ' . escapeshellarg($thumbPath) . ' '
            . '2>&1';

    // Process thumbnail
    $thumbOutput = $output .
            $thumbCommand . PHP_EOL .
            shell_exec($thumbCommand);

    // Log thumb output
    if ($log) {
      $log->log($thumbOutput, Zend_Log::INFO);
    }

    // Check output message for success
    $thumbSuccess = true;
    if (preg_match('/video:0kB/i', $thumbOutput)) {
      $thumbSuccess = false;
    }

    // Resize thumbnail
    if ($thumbSuccess) {
      try {
        $image = Engine_Image::factory();
        $image->open($thumbPath)
                ->resize(500, 500)
                ->write($thumbPath)
                ->destroy();
      } catch (Exception $e) {
        $this->_addMessage((string) $e->__toString());
        $thumbSuccess = false;
      }
    }

    return $thumbSuccess;
  }

  private function buildContestCmd($contest, $width, $height, $type, $originalPath, $outputPath, $compatibilityMode = false) {
    $ffmpeg_path = $this->getFFMPEGPath();

    $contestCommand = $ffmpeg_path . ' '
            . '-i ' . escapeshellarg($originalPath) . ' '
            . '-ab 64k' . ' '
            . '-ar 44100' . ' '
            . '-qscale 5' . ' '
            . '-r 25' . ' ';

    if ($type == 'mp4')
      $contestCommand .= '-vcodec libx264' . ' '
              . '-acodec aac' . ' '
              . '-strict experimental' . ' '
              . '-preset veryfast' . ' '
              . '-f mp4' . ' '
      ;
    else
      $contestCommand .= '-vcodec flv -f flv ';

   // if ($compatibilityMode) {
      $contestCommand .= "-s ${height}x${width}" . ' ';
   // } else {
    // $filters = $this->getContestFilters($contest, $width, $height);
  //  }

    $contestCommand .=
            '-y ' . escapeshellarg($outputPath) . ' '
            . '2>&1';

    return $contestCommand;
  }

  protected function _process($contest, $type, $compatibilityMode = false) {
    $tmpDir = $this->getTmpDir();
    $contest = $this->getContest($contest);

    // Update to encoding status
    $contest->status = 2;
    $contest->type = 3;
    $contest->save();

    // Prepare information
    $owner = $contest->getOwner();

    // Pull contest from storage system for encoding
    $storageObject = $this->getStorageObject($contest);
    $originalPath = $this->getOriginalPath($storageObject);

    $outputPath = $tmpDir . DIRECTORY_SEPARATOR . $contest->getIdentity() . '_vconverted.' . $type;
    $thumbPath = $tmpDir . DIRECTORY_SEPARATOR . $contest->getIdentity() . '_vthumb.jpg';

    $width = 500;
    $height = 500;
    
    // Pull video from storage system for encoding
    $storageObject = $this->getStorageObject($contest);
    $ffmpeg_path = $this->getFFMPEGPath();

    $filePath = APPLICATION_PATH . DIRECTORY_SEPARATOR.$storageObject->storage_path;
        $pos = strrpos($ffmpeg_path, 'ffmpeg');
        if($pos !== false) {
          $ffmpeg_path = substr_replace($ffmpeg_path, 'ffprobe', $pos, strlen('ffmpeg'));
        }
        $dimentions = shell_exec($ffmpeg_path.' '.escapeshellarg($originalPath).' 2>&1');
    if($dimentions){
        preg_match('(\b[^0]\d+x[^0]\d+\b)', $dimentions, $matches);
        if(!empty($matches[0])){
            list($width,$height) = explode("x",$matches[0]);
        }
    }

    $contestCommand = $this->buildContestCmd($contest, $width, $height, $type, $originalPath, $outputPath, $compatibilityMode);

    // Prepare output header
    $output = PHP_EOL;
    $output .= $originalPath . PHP_EOL;
    $output .= $outputPath . PHP_EOL;
    $output .= $thumbPath . PHP_EOL;

    // Prepare logger
    $log = new Zend_Log();
    $log->addWriter(new Zend_Log_Writer_Stream(APPLICATION_PATH . '/temporary/log/contest.log'));

    // Execute contest encode command
    $contestOutput = $output .
            $contestCommand . PHP_EOL .
            shell_exec($contestCommand);

    // Log
    if ($log) {
      $log->log($contestOutput, Zend_Log::INFO);
    }

    // Check for failure
    $success = $this->conversionSucceeded($contest, $contestOutput, $outputPath);

    // Failure
    if (!$success) {
      if (!$compatibilityMode) {
        $this->_process($contest, true);
        return;
      }

      $exceptionMessage = '';

      $db = $contest->getTable()->getAdapter();
      $db->beginTransaction();

      try {
        $contest->save();
        $exceptionMessage = $this->notifyOwner($contest, $owner);
        $db->commit();
      } catch (Exception $e) {
        $contestOutput .= PHP_EOL . $e->__toString() . PHP_EOL;

        if ($log) {
          $log->write($e->__toString(), Zend_Log::ERR);
        }

        $db->rollBack();
      }

      // Write to additional log in dev
      if (APPLICATION_ENV == 'development') {
        file_put_contents($tmpDir . '/' . $contest->participant_id . '.txt', $contestOutput);
      }

      throw new Sescontest_Model_Exception($exceptionMessage);
    }

    // Success
    else {
      // Get duration of the contest to caculate where to get the thumbnail
      $duration = $this->getDuration($contestOutput);

      // Log duration
      if ($log) {
        $log->log('Duration: ' . $duration, Zend_Log::INFO);
      }

      // Fetch where to take the thumbnail
      $thumb_splice = $duration / 2;

      $thumbSuccess = $this->generateThumbnail($outputPath, $output, $thumb_splice, $thumbPath, $log, $contest);

      // Save contest and thumbnail to storage system
      $params = array(
          'parent_id' => $contest->getIdentity(),
          'parent_type' => $contest->getType(),
          'user_id' => $contest->owner_id
      );

      $db = $contest->getTable()->getAdapter();
      $db->beginTransaction();

      try {
        $storageObject->setFromArray($params);
        $storageObject->store($outputPath);

        if ($thumbSuccess) {
          $thumbFileRow = Engine_Api::_()->storage()->create($thumbPath, $params);
        }

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();

        // delete the files from temp dir
        unlink($originalPath);
        unlink($outputPath);

        if ($thumbSuccess) {
          unlink($thumbPath);
        }

        $contest->status = 7;
        $contest->save();

        $this->notifyOwner($contest, $owner);

        throw $e; // throw
      }

      // Contest processing was a success!
      // Save the information
      if ($thumbSuccess) {
        $contest->photo_id = $thumbFileRow->file_id;
      }

      $contest->duration = $duration;
      $contest->status = 1;
      $contest->save();

      // delete the files from temp dir
      unlink($originalPath);
      unlink($outputPath);
      unlink($thumbPath);

      // insert action in a separate transaction if contest status is a success
      $actionsTable = Engine_Api::_()->getDbtable('actions', 'activity');
      $db = $actionsTable->getAdapter();
      $db->beginTransaction();

      try {
        // new action
    
        // notify the owner
        Engine_Api::_()->getDbtable('notifications', 'activity')
                ->addNotification($owner, $owner, $contest, 'sescontest_processed');

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e; // throw
      }
    }
  }

}
