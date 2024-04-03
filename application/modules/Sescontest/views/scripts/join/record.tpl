<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: record.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/video-js.min.css');?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/videojs.record.css');?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/style_webvideo.css');?>
<?php $this->headScript()->appendFile($base_url . 'application/modules/Sescontest/externals/scripts/video.min.js'); ?>
<?php $this->headScript()->appendFile($base_url . 'application/modules/Sescontest/externals/scripts/RecordRTC.js'); ?>
<?php $this->headScript()->appendFile($base_url . 'application/modules/Sescontest/externals/scripts/wavesurfer.min.js'); ?>
<?php $this->headScript()->appendFile($base_url . 'application/modules/Sescontest/externals/scripts/wavesurfer.microphone.min.js'); ?>
<?php $this->headScript()->appendFile($base_url . 'application/modules/Sescontest/externals/scripts/videojs.wavesurfer.min.js'); ?>
<?php $this->headScript()->appendFile($base_url . 'application/modules/Sescontest/externals/scripts/videojs.record.js'); ?>

  <style>
  /* change player background color */
  #myAudio {
      background-color: #9FD6BA;
  }
  </style>


<audio id="myAudio" class="video-js vjs-default-skin"></audio>

<script>
var player = videojs("myAudio",
{
    controls: true,
    width: 600,
    height: 300,
    plugins: {
        wavesurfer: {
            src: "live",
            waveColor: "black",
            progressColor: "#2E732D",
            debug: true,
            cursorWidth: 1,
            msDisplayMax: 20,
            hideScrollbar: true
        },
        record: {
            audio: true,
            video: false,
            maxLength: 20,
            debug: true
        }
    }
});
// error handling
player.on('deviceError', function()
{
    console.log('device error:', player.deviceErrorCode);
});

// user clicked the record button and started recording
player.on('startRecord', function()
{
    console.log('started recording!');
});

// user completed recording and stream is available
player.on('finishRecord', function()
{
    // the blob object contains the recorded data that
    // can be downloaded by the user, stored on server etc.
    console.log('finished recording: ', player.recordedData);
});
</script>