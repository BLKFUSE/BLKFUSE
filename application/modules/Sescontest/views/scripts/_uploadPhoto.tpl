<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _uploadPhoto.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $baseURL = Zend_Registry::get('StaticBaseUrl');?>
 <?php if ($baseURL):?>
   <?php  $baseurl = $baseURL;?>
<?php else:?>
  <?php $baseurl = '/';?>
<?php endif;?>
<?php $redirect_uri = ( isset($_SERVER["HTTPS"]) && (strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . '/sesrecord.php'.'?media_type=image';?>
<iframe id="audovideo-record" src="<?php echo $redirect_uri;?>" style="display:none;"></iframe>

<script type="text/javascript">
  en4.core.runonce.add(function() {
    var photoType = 1;
    scriptJquery('#uploadWebCamPhoto').click(function(e){
      scriptJquery('#fromurl-wrapper').hide();
      scriptJquery('#dragandrophandlerbackground').hide();
      scriptJquery('#contest_main_photo_preview').hide();
      scriptJquery('#contest_link_photo_preview').hide();
      scriptJquery('#fromurl-wrapper').hide();
      scriptJquery('#remove_fromurl_image-wrapper').hide();
      scriptJquery('#contest_url_photo_preview-wrapper').hide();
      scriptJquery('#removeimage-wrapper').hide();
      scriptJquery('#audovideo-record').show();
      scriptJquery('#uploadimage').removeClass('active');
      scriptJquery('#uploadWebCamPhoto').addClass('active');
      scriptJquery('#sescontest_content_link').removeClass('active');
      scriptJquery('#sescontest_from_url').removeClass('active');
      scriptJquery('#remove_link_image-wrapper').hide();
      photoType = 2;
      scriptJquery('#uploaded_content_type').val(photoType);
    });
    scriptJquery('#sescontest_from_url').click(function(e){
      scriptJquery('#dragandrophandlerbackground').hide();
      scriptJquery('#contest_main_photo_preview-wrapper').hide();
      scriptJquery('#contest_main_photo_preview').hide();
      scriptJquery('#contest_link_photo_preview').hide();
      scriptJquery('#removeimage-wrapper').hide();
      if(scriptJquery('#contest_url_photo_preview').attr('src') == '') {
        scriptJquery('#fromurl-wrapper').show();
      }
      else {
        document.getElementById('contest_url_photo_preview-wrapper').style.display = 'block';
        document.getElementById('remove_fromurl_image-wrapper').style.display = 'block';
      }
      scriptJquery('#uploadimage').removeClass('active');
      scriptJquery('#sescontest_from_url').addClass('active');
      scriptJquery('#sescontest_content_link').removeClass('active');
      scriptJquery('#uploadWebCamPhoto').removeClass('active');
      scriptJquery('#remove_link_image-wrapper').hide();
      scriptJquery('#audovideo-record').hide();
      photoType = 4;
      scriptJquery('#uploaded_content_type').val(photoType);
    });
   scriptJquery('#uploadimage').click(function(e){
    scriptJquery('#contest_link_photo_preview').hide();
    scriptJquery('#fromurl-wrapper').hide();
    scriptJquery('#remove_fromurl_image-wrapper').hide();
    scriptJquery('#contest_url_photo_preview-wrapper').hide();
    if(document.getElementById('photouploader-wrapper'))
	document.getElementById('photouploader-wrapper').style.display = 'block';
    scriptJquery('#dragandrophandlerbackground').show();
    if(scriptJquery('#contest_main_photo_preview').attr('src') != '') {
      scriptJquery('#contest_main_photo_preview').show();
      scriptJquery('#contest_main_photo_preview-wrapper').show();
      scriptJquery('#removeimage-wrapper').show();
      scriptJquery('#remove_link_image-wrapper').show();
    }
    else {
      scriptJquery('#contest_main_photo_preview').hide();
      scriptJquery('#contest_main_photo_preview-wrapper').hide();
      scriptJquery('#removeimage-wrapper').hide();
      scriptJquery('#remove_link_image-wrapper').hide();
    }
    if(document.getElementById('contest_link_photo_preview'))
	document.getElementById('contest_link_photo_preview-wrapper').style.display = 'none';
    scriptJquery('#uploadimage').addClass('active');
    scriptJquery('#sescontest_content_link').removeClass('active');
    scriptJquery('#uploadWebCamPhoto').removeClass('active');
    scriptJquery('#sescontest_from_url').removeClass('active');
    scriptJquery('#audovideo-record').hide();
    photoType = 1;
    scriptJquery('#uploaded_content_type').val(photoType);
   });
  });
      
  function resetPhotoData() {
    document.getElementById("audovideo-record").src = "<?php echo $redirect_uri;?>";
    return false;
  }
  scriptJquery(document).on('click','#upload_from_url',function(e){
	e.preventDefault();
    var img = new Image();
    var url = scriptJquery('#from_url_upload').val();
    scriptJquery(img).load(function () {
      var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
      if((ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG' || ext == 'gif' || ext == 'GIF' || ext == "webp")){
        document.getElementById('contest_url_photo_preview').style.display = 'block';
        document.getElementById('contest_url_photo_preview-wrapper').style.display = 'block';
        document.getElementById('contest_url_photo_preview').src = url;
        scriptJquery('#sescontest_url_id').val(url);
        document.getElementById('remove_fromurl_image-wrapper').style.display = 'block';
        document.getElementById('removefromurlimage').style.display = 'block';
        document.getElementById('from_url_upload').value = '';
        document.getElementById('fromurl-wrapper').style.display = 'none';
        resetPhotoData();
        removeImage(); 
        removeLinkImage();
      }
    })
    // if there was an error loading the image, react accordingly
    .error(function () {
      alert('Image Does Not Exist !');
    })
    // *finally*, set the src attribute of the new image to our image
    .attr('src', url);
});
</script>
