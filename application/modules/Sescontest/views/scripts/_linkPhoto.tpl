<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _linkPhoto.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
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
<script type="text/javascript">
  en4.core.runonce.add(function() {
   scriptJquery('<div class="sescontest_photo_update_popup sesbasic_bxs" id="sescontest_popup_existing_upload" style="display:none"><div class="sescontest_photo_update_popup_overlay"></div><div class="sescontest_photo_update_popup_container" id="sescontest_popup_container_existing"><div class="sescontest_photo_update_popup_header"><?php echo $this->translate("Select a photo") ?><a class="fa fa-times" href="javascript:;" onclick="hideContentPhotoUpload()" title="<?php echo $this->translate("Close") ?>"></a></div><div class="sescontest_photo_update_popup_content"><div id="sescontest_album_existing_data"></div><div id="sescontest_profile_existing_img" style="display:none;text-align:center;"><img src="application/modules/Sesbasic/externals/images/loading.gif" alt="<?php echo $this->translate("Loading"); ?>" style="margin-top:10px;"  /></div></div></div></div>').appendTo('body');
    scriptJquery(document).on('click','#sescontest_content_link',function(){
           if(document.getElementById('photouploader-wrapper'))
	document.getElementById('photouploader-wrapper').style.display = 'none';
        scriptJquery('#sescontest_content_link').addClass('active');
        scriptJquery('#uploadWebCamPhoto').removeClass('active');
        scriptJquery('#uploadimage').removeClass('active');
        scriptJquery('#sescontest_from_url').removeClass('active');
        scriptJquery('#audovideo-record').hide();
        scriptJquery('#removeimage-wrapper').hide();
        scriptJquery('#fromurl-wrapper').hide();
        scriptJquery('#remove_fromurl_image-wrapper').hide();
        scriptJquery('#contest_url_photo_preview-wrapper').hide();
	    document.getElementById('contest_main_photo_preview-wrapper').style.display = 'none';
        if(scriptJquery('#sescontest_link_id').val() != '') {
          document.getElementById('remove_link_image-wrapper').style.display = 'block';
          scriptJquery('#removelinkimage').show();
        }
        if(document.getElementById('contest_link_photo_preview'))
	     document.getElementById('contest_link_photo_preview-wrapper').style.display = 'block';
       document.getElementById('contest_link_photo_preview').style.display = 'block';
       scriptJquery('#uploaded_content_type').val(3);
    });
    scriptJquery(document).on('click','#contest_link_photo_preview',function(e){
        e.preventDefault();
        scriptJquery('#sescontest_popup_existing_upload').show();
        existingMyPhotosGet();
    });
  });
      var canPaginatePageNumber = 1;
function existingMyPhotosGet(){
	scriptJquery('#sescontest_profile_existing_img').show();
	var URL = en4.core.baseUrl+'sescontest/join/existing-photos/contest_id/'+"<?php echo $this->contest_id;?>";
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
    var src = scriptJquery('#sescontest_profile_upload_existing_photos_'+id).find('span').css('background-image');
    src = src.replace('url("','');
    src = src.replace('")','');
    document.getElementById('contest_link_photo_preview').style.display = 'block';
	document.getElementById('contest_link_photo_preview-wrapper').style.display = 'block';
	document.getElementById('contest_link_photo_preview').src = src;
    scriptJquery('#sescontest_link_id').val(id);
    document.getElementById('remove_link_image-wrapper').style.display = 'block';
    scriptJquery('#removelinkimage').show();
    resetPhotoData();
    removeImage();
    removeFromurlImage();
    hideContentPhotoUpload();
});

  function hideContentPhotoUpload(){
	canPaginatePageNumber = 1;
    scriptJquery('#sescontest_album_existing_data').html('');
	scriptJquery('#sescontest_popup_existing_upload').hide();
}
  function resetPhotoData() {
    document.getElementById("audovideo-record").src = "<?php echo $redirect_uri;?>";
    return false;
  }
</script>
