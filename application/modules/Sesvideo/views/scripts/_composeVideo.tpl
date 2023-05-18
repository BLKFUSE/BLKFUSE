<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _composeVideo.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>

<?php
$subject = Engine_Api::_()->core()->hasSubject() ? Engine_Api::_()->core()->getSubject() : false;
if($subject && $subject->getType() == "contest"){
return;
}
   $this->headScript()
      ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/composer_video.js');

  $allowed = 0;
  $user = Engine_Api::_()->user()->getViewer();
  $is_allowed_option = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('video', $user, 'video_uploadoptn');
  $iframely = (bool) engine_in_array('iframely',$is_allowed_option) ? 1 : 0;
  
// 	$youtube = (bool) engine_in_array('youtube',$is_allowed_option) ? 1 : 0;
// 	$vimeo = (bool) engine_in_array('vimeo',$is_allowed_option) ? 1 : 0;
// 	$dailymotion = (bool) engine_in_array('dailymotion',$is_allowed_option) ? 1 : 0;
//   
//   $fromURL = (bool) engine_in_array('url',$is_allowed_option) ? 1 : 0;
//   $embedcode = (bool) engine_in_array('embedcode',$is_allowed_option) ? 1 : 0;
//   $fbembedcode = (bool) engine_in_array('facebook',$is_allowed_option) ? 1 : 0;
//   $twitterembedcode = (bool) engine_in_array('twitter',$is_allowed_option) ? 1 : 0;
//   $streamableembedcode = (bool) engine_in_array('streamable',$is_allowed_option) ? 1 : 0;
  
	$myComputer = (bool) engine_in_array('myComputer',$is_allowed_option) ? 1 : 0;
  $myComputerCheck = 0;
  $allowed_upload = (bool) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('video', $user, 'upload');
  $ffmpeg_path = (bool) Engine_Api::_()->getApi('settings', 'core')->video_ffmpeg_path;
  //$youtubeEnabled = (bool) Engine_Api::_()->getApi('settings', 'core')->getSetting('video.youtube.apikey');
  $myComputerCheck = $myComputer && $ffmpeg_path ? 1 : 0;
  if($allowed_upload && $ffmpeg_path) $allowed = 1;
?>

<script type="text/javascript">
  en4.core.runonce.add(function() {
    var type = 'wall';
    if (composeInstance.options.type) type = composeInstance.options.type;
    composeInstance.addPlugin(new Composer.Plugin.Video({
      title : '<?php echo $this->translate('Add Video') ?>',
      iframelyCheck:<?php echo $iframely; ?>,
      fromURL:0,
      myComputerCheck:<?php echo $myComputerCheck; ?>,
      lang : {
        'Add Video' : '<?php echo $this->string()->escapeJavascript($this->translate('Add Video')) ?>',
        'Select File' : '<?php echo $this->string()->escapeJavascript($this->translate('Select File')) ?>',
        'cancel' : '<?php echo $this->string()->escapeJavascript($this->translate('cancel')) ?>',
        'Attach' : '<?php echo $this->string()->escapeJavascript($this->translate('Attach')) ?>',
        'Loading...' : '<?php echo $this->string()->escapeJavascript($this->translate('Loading...')) ?>',
        'Choose Source': '<?php echo $this->string()->escapeJavascript($this->translate('Choose Source')) ?>',
        'My Computer': '<?php echo $this->string()->escapeJavascript($this->translate('My Computer')) ?>',
        'External Sites': '<?php echo $this->string()->escapeJavascript($this->translate('External Sites')) ?>',
        'To upload a video from your computer, please use our full uploader.': '<?php echo $this->string()->escapeJavascript($this->translate('To upload a video from your computer, please use our <a href="%1$s">full uploader</a>.', $this->url(array('action' => 'create', 'type'=>3), 'sesvideo_general'))) ?>'
      },
      allowed : <?php echo $allowed;?>,
      type : type,
      advancedactvity: <?php echo (int) Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity'); ?>,
      requestOptions : {
        'url' : en4.core.baseUrl + 'sesvideo/index/compose-upload/format/json/c_type/'+type,
        'uploadurl' : en4.core.baseUrl + 'sesvideo/index/upload-video/format/json/c_type/'+type
      }
    }));
  });
</script>
