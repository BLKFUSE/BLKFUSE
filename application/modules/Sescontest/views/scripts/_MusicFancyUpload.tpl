<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _MusicFancyUpload.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $baseURL = Zend_Registry::get('StaticBaseUrl');?>
 <?php if ($baseURL):?>
   <?php  $baseurl = $baseURL;?>
<?php else:?>
  <?php $baseurl = '/';?>
<?php endif;?>
<?php $uploadSize = str_replace('M','',ini_get('upload_max_filesize'));?>
<?php $redirect_uri = ( isset($_SERVER["HTTPS"]) && (strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . '/sesrecord.php'.'?media_type=audio';?>
<iframe id="audovideo-record" src="<?php echo $redirect_uri;?>" style="display:none;"></iframe>

<script type="text/javascript">
  var audioType = 1;
  en4.core.runonce.add(function() {
    scriptJquery('#uploadWebCamAudio').click(function(e){
      scriptJquery('#demo-fallback').hide();
      if(document.getElementById('contest_link_audio_data-wrapper'))
      document.getElementById('contest_link_audio_data-wrapper').style.display = 'none';
      if(document.getElementById('contest_link_audio_preview-wrapper'))
      document.getElementById('contest_link_audio_preview-wrapper').style.display = 'none';
      if(document.getElementById('remove_link_audio-wrapper'))
      document.getElementById('remove_link_audio-wrapper').style.display = 'none';
      scriptJquery('#audovideo-record').show();
      scriptJquery('#uploadaudio').removeClass('active');
      scriptJquery('#sescontest_audio_link').removeClass('active');
      scriptJquery('#uploadWebCamAudio').addClass('active');
      audioType = 2;
      scriptJquery('#uploaded_content_type').val(audioType);
    });
    scriptJquery('#uploadaudio').click(function(e){
     if(document.getElementById('contest_link_audio_data-wrapper'))
      document.getElementById('contest_link_audio_data-wrapper').style.display = 'none';
     if(document.getElementById('contest_link_audio_preview-wrapper'))
      document.getElementById('contest_link_audio_preview-wrapper').style.display = 'none';
     if(document.getElementById('remove_link_audio-wrapper'))
      document.getElementById('remove_link_audio-wrapper').style.display = 'none';
     scriptJquery('#demo-fallback').show();
     scriptJquery('#uploadaudio').addClass('active');
     scriptJquery('#sescontest_audio_link').removeClass('active');
     scriptJquery('#uploadWebCamAudio').removeClass('active');
     scriptJquery('#audovideo-record').hide();
     audioType = 1;
     scriptJquery('#uploaded_content_type').val(audioType);
    });
  });
  function resetData() {
    document.getElementById("audovideo-record").src = "<?php echo $redirect_uri;?>";
    return false;
  }
  function resetFullData(obj) {
    if("<?php echo $uploadSize ;?>" < obj.files[0].size/1024/1024) {
      alert(en4.core.language.translate('Audio you are selecting is exceeding the size. You can upload the audio of max size ')+ "<?php echo $uploadSize ;?>"+" MB");
      scriptJquery('#sescontest_audio_file').val('');
    }
    else {
    var fieldValue = scriptJquery('#sescontest_audio_file').val();
      var fileExt = fieldValue.split('.').pop();
      if(fileExt != 'mp3') {
        scriptJquery('#sescontest_audio_file').val('');
        alert(en4.core.language.translate('You can upload audio of mp3 type extension only from here.'));
      }
    }
    document.getElementById("audovideo-record").src = "<?php echo $redirect_uri;?>";
    document.getElementById('remove_link_audio-wrapper').style.display = 'none';
	scriptJquery('#contest_link_audio_preview-wrapper').html('<div id="contest_link_video_preview-label" class="form-label">&nbsp;</div><div id="contest_link_video_preview-element" class="form-element">'+en4.core.language.translate('Select Your Audio')+'</div>');
    scriptJquery('#contest_link_audio_data-wrapper').html('');
    document.getElementById('contest_link_audio_data-wrapper').style.display = 'none';
    scriptJquery('#sescontest_link_id').val('');
    scriptJquery('#sescontest_url_id').val('');
    return false;
  }
</script>

<fieldset id="demo-fallback">
  <label for="demo-musiclabel">
    <?php echo $this->translate('Upload Music:') ?>
    <input id="<?php echo $this->element->getName(); ?>" type="file" name="<?php echo $this->element->getName() ?>"
           value="<?php (is_array($this->element->getValue()) ? '' : $this->element->getValue()) ?>"
           accept="audio/*" onchange="resetFullData(this);"/>
  </label>
</fieldset>


