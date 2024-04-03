<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _linkVideo.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $baseURL = Zend_Registry::get('StaticBaseUrl');?>
 <?php if ($baseURL):?>
   <?php  $baseurl = $baseURL;?>
<?php else:?>
  <?php $baseurl = '/';?>
<?php endif;?>
<?php $redirect_uri = ( isset($_SERVER["HTTPS"]) && (strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] .$baseurl. '/sesrecord.php'.'?media_type=image';?>
<script type="text/javascript">
  en4.core.runonce.add(function() {
   scriptJquery('<div class="sescontest_photo_update_popup sesbasic_bxs" id="sescontest_popup_existing_upload" style="display:none"><div class="sescontest_photo_update_popup_overlay"></div><div class="sescontest_photo_update_popup_container" id="sescontest_popup_container_existing"><div class="sescontest_photo_update_popup_header"><?php echo $this->translate("Select a Video") ?><a class="fa fa-times" href="javascript:;" onclick="hideContentVideoUpload()" title="<?php echo $this->translate("Close") ?>"></a></div><div class="sescontest_photo_update_popup_content"><div id="sescontest_album_existing_data"></div><div id="sescontest_profile_existing_img" style="display:none;text-align:center;"><img src="application/modules/Sesbasic/externals/images/loading.gif" alt="<?php echo $this->translate("Loading"); ?>" style="margin-top:10px;"  /></div></div></div></div>').appendTo('body');
    scriptJquery(document).on('click','#sescontest_video_link',function(){
           if(document.getElementById('photouploader-wrapper'))
	document.getElementById('photouploader-wrapper').style.display = 'none';
        document.getElementById('contest_link_video_preview-wrapper').style.display = 'block';
        scriptJquery('#sescontest_video_link').addClass('active');
        scriptJquery('#uploadWebCamVideo').removeClass('active');
        scriptJquery('#uploadvideo').removeClass('active');
        scriptJquery('#audovideo-record').hide();
        scriptJquery('#demo-fallback').hide();
        scriptJquery('#removeimage-wrapper').hide();
        if(scriptJquery('#contest_link_video_preview-wrapper').find('iframe').length > 0)
          scriptJquery('#remove_link_video-wrapper').show();
         
	     //document.getElementById('contest_main_photo_preview-wrapper').style.display = 'none';
       
        if(document.getElementById('contest_link_photo_preview'))
	     document.getElementById('contest_link_photo_preview-wrapper').style.display = 'block';
     //  document.getElementById('contest_link_photo_preview').style.display = 'block';
       scriptJquery('#uploaded_content_type').val(3);
    });
    scriptJquery(document).on('click','#contest_link_video_preview-element',function(e){
        e.preventDefault();
        scriptJquery('#sescontest_popup_existing_upload').show();
        existingMyVideosGet();
    });
  });
      var canPaginatePageNumber = 1;
function existingMyVideosGet(){
	scriptJquery('#sescontest_profile_existing_img').show();
	var URL = en4.core.baseUrl+'sescontest/join/existing-videos/contest_id/'+"<?php echo $this->contest_id;?>";
	(scriptJquery.ajax({
      method: 'post',
      'url': URL ,
      'data': {
        format: 'html',
        page: canPaginatePageNumber,
        is_ajax: 1
      },
      success: function(responseHTML) {
        scriptJquery('#sescontest_album_existing_data').append(responseHTML);
				
      	scriptJquery('#sescontest_album_existing_data').slimscroll({
					 height: 'auto',
					 alwaysVisible :true,
					 color :'#000',
					 railOpacity :'0.5',
					 disableFadeOut :true,					 
					});
					scriptJquery('#sescontest_album_existing_data').slimScroll().bind('slimscroll', function(event, pos){
					 if(canPaginateExistingPhotos == '1' && pos == 'bottom' && scriptJquery('#sescontest_profile_existing_img').css('display') != 'block'){
						 	scriptJquery('#sescontest_profile_existing_img').css('position','absolute').css('width','100%').css('bottom','5px');
							existingMyPhotosGet();
					 }
					});
					scriptJquery('#sescontest_profile_existing_img').hide();
		}
    }));	
}
  scriptJquery(document).on('click','a[id^="sescontest_profile_upload_existing_photos_"]',function(event){
	event.preventDefault();
	var id = scriptJquery(this).attr('id').match(/\d+/)[0];
	if(!id)
      return;
    var videoCode = scriptJquery('#sescontest_thumb_'+id).find('div').html();
    videoCode = videoCode.replace('[sesframe',' <iframe');
    videoCode = videoCode.replace('[/sesframe]','</iframe>');
    videoCode = videoCode.replace('[script','<script');
    videoCode = videoCode.replace('[/script',' </script');
    videoCode = videoCode.replace(']','>');
   
	document.getElementById('contest_link_video_preview-wrapper').style.display = 'block';
	scriptJquery('#contest_link_video_preview-wrapper').html(videoCode);
    scriptJquery('#sescontest_link_id').val(id);
    scriptJquery('#removelinkvideo').show();
    scriptJquery('#remove_link_video-wrapper').show();
    resetVideoData();
    scriptJquery('#sescontest_video_file').val('');
    hideContentVideoUpload();
});

function removeLinkVideo() {
	document.getElementById('remove_link_video-wrapper').style.display = 'none';
	scriptJquery('#contest_link_video_preview-wrapper').html('<div id="contest_link_video_preview-label" class="form-label">&nbsp;</div><div id="contest_link_video_preview-element" class="form-element">'+en4.core.language.translate('Select Your Video')+'</div>');
    
    scriptJquery('#sescontest_link_id').val('');
    scriptJquery('#sescontest_url_id').val('');
}

  function hideContentVideoUpload(){
	canPaginatePageNumber = 1;
    scriptJquery('#sescontest_album_existing_data').html('');
	scriptJquery('#sescontest_popup_existing_upload').hide();
}
</script>
