<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php
	if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')) {
		include APPLICATION_PATH .  '/application/modules/Sesadvancedcomment/views/scripts/_jsFiles.tpl';
	}
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
                 .'application/modules/Sesalbum/externals/scripts/core.js'); ?> 
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesalbum/externals/styles/styles.css'); ?>
<?php 
if(isset($this->canEdit)){
// First, include the Webcam.js JavaScript Library 
  $base_url = $this->layout()->staticBaseUrl;
  $this->headScript()->appendFile($base_url . 'application/modules/Sesbasic/externals/scripts/webcam.js'); 
  }
?>
<?php if($this->widgetPlaced == 'home'){ ?>
 <h3><?php echo $this->translate('Hi %1$s!', $this->viewer()->getTitle()); ?></h3>
<?php } ?>
<input type="file" name="sesalbum_profile_upload_direct" onchange="readImageUrlsesalbum(this)" id="sesalbum_profile_upload_direct" style="display:none;"  />
<div id='profile_photo' class="sesalbum_member_profile_photo sesbasic_bxs">
	<div class="sesalbum_member_photo_loading" id="sesalbum-profile-upload-loading" style="display:none;"></div>
 <?php if($this->widgetPlaced == 'home'){ ?>
  <a href="<?php echo Engine_Api::_()->user()->getViewer()->getHref(); ?>">
  <?php echo $this->itemPhoto($this->viewer()); ?>
 <?php }else{ ?>
  <?php echo $this->itemPhoto($this->subject());
  	}
   ?>
  <?php if($this->widgetPlaced == 'home'){ ?>
  	</a>
  <?php } ?>
 <?php if(isset($this->canEdit)){ ?>
  <div class="sesalbum_album_coverphoto_op" id="sesalbum_profile_change">
      <a href="javascript:;" id="profile_change_btn"><i class="fa fa-camera" id="profile_change_btn_i"></i><span id="change_profile_txt"><?php echo $this->translate("Update Profile Picture"); ?></span></a>
      <div class="sesalbum_album_coverphoto_op_box sesalbum_option_box">
      	<i class="sesalbum_album_coverphoto_op_box_arrow"></i>
          <a id="uploadWebCamPhoto" href="javascript:;" class="sesalbum_icon_camera"><?php echo $this->translate("Take Photo"); ?></a>
          <a id="uploadProfilePhoto" href="javascript:;" class="sesalbum_icon_upload"><?php echo $this->translate("Upload Photo"); ?></a>
          <a id="fromExistingAlbum" href="javascript:;" class="sesalbum_icon_photos"><?php echo $this->translate("Choose From Existing"); ?></a>
      </div>
    </div>
 <?php } ?>
</div>
<?php if(isset($this->canEdit)){ ?>
<script type="application/javascript">
scriptJquery('<div class="sesalbum_photo_update_popup sesbasic_bxs" id="sesalbum_popup_cam_upload" style="display:none"><div class="sesalbum_photo_update_popup_overlay"></div><div class="sesalbum_photo_update_popup_container sesalbum_photo_update_webcam_container"><div class="sesalbum_photo_update_popup_header"><?php echo $this->translate("Click to Take Photo") ?><a class="fas fa-times" href="javascript:;" onclick="hideProfilePhotoUpload()" title="<?php echo $this->translate("Close") ?>"></a></div><div class="sesalbum_photo_update_popup_webcam_options"><div id="sesalbum_camera" style="background-color:#ccc;"></div><div class="centerT sesalbum_photo_update_popup_btns">   <button onclick="take_snapshot()" style="margin-right:3px;" ><?php echo $this->translate("Take Photo") ?></button><button onclick="hideProfilePhotoUpload()" ><?php echo $this->translate("Cancel") ?></button></div></div></div></div><div class="sesalbum_photo_update_popup sesbasic_bxs" id="sesalbum_popup_existing_upload" style="display:none"><div class="sesalbum_photo_update_popup_overlay"></div><div class="sesalbum_photo_update_popup_container" id="sesalbum_popup_container_existing"><div class="sesalbum_photo_update_popup_header"><?php echo $this->translate("Select a photo") ?><a class="fas fa-times" href="javascript:;" onclick="hideProfilePhotoUpload()" title="<?php echo $this->translate("Close") ?>"></a></div><div class="sesalbum_photo_update_popup_content"><div id="sesalbum_album_existing_data"></div><div id="sesalbum_profile_existing_img" style="display:none;text-align:center;"><img src="application/modules/Sesbasic/externals/images/loading.gif" alt="<?php echo $this->translate("Loading"); ?>" style="margin-top:10px;"  /></div></div></div></div>').appendTo('body');
var canPaginatePageNumber = 1;
function existingPhotosGet(){
	scriptJquery('#sesalbum_profile_existing_img').show();
	var URL = en4.core.baseUrl+'albums/index/existing-photos/';
	(scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': URL ,
      'data': {
        format: 'html',
        page: canPaginatePageNumber,
        is_ajax: 1
      },
      success: function(responseHTML) {
				scriptJquery('#sesalbum_album_existing_data').append(responseHTML);
      	scriptJquery('#sesalbum_album_existing_data').slimscroll({
					 height: 'auto',
					 alwaysVisible :true,
					 color :'#000',
					 railOpacity :'0.5',
					 disableFadeOut :true,					 
					});
					scriptJquery('#sesalbum_album_existing_data').slimScroll().bind('slimscroll', function(event, pos){
					 if(canPaginateExistingPhotos == '1' && pos == 'bottom' && scriptJquery('#sesalbum_profile_existing_img').css('display') != 'block'){
						 	scriptJquery('#sesalbum_profile_existing_img').css('position','absolute').css('width','100%').css('bottom','5px');
							existingPhotosGet();
					 }
					});
					scriptJquery('#sesalbum_profile_existing_img').hide();
		}
    }));	
}
scriptJquery(document).on('click','a[id^="sesalbum_profile_upload_existing_photos_"]',function(event){
	event.preventDefault();
	var id = scriptJquery(this).attr('id').match(/\d+/)[0];
	if(!id)
		return;
	scriptJquery('#sesalbum-profile-upload-loading').show();
	hideProfilePhotoUpload();
	var URL = en4.core.baseUrl+'albums/index/upload-existingphoto/';
	(scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': URL ,
      'data': {
        format: 'html',
        id: id,
				user_id:'<?php echo $this->user_id; ?>'
      },
      success: function(responseHTML) {
				text = JSON.parse(responseHTML);
				if(text.status == 'true'){
					if(text.src != '')
					scriptJquery('#profile_photo').find('.thumb_profile').attr('src',text.src);
			}
			scriptJquery('#sesalbum-profile-upload-loading').hide();
			}
    }));	
});
scriptJquery(document).on('click','a[id^="sesalbum_existing_album_see_more_"]',function(event){
	event.preventDefault();
	var thatObject = this;
	scriptJquery(thatObject).parent().hide();
	var id = scriptJquery(this).attr('id').match(/\d+/)[0];
	var pageNum = parseInt(scriptJquery(this).attr('data-src'),10);
	scriptJquery('#sesalbum_existing_album_see_more_loading_'+id).show();
	if(pageNum == 0){
		scriptJquery('#sesalbum_existing_album_see_more_page_'+id).remove();
		return;
	}
	var URL = en4.core.baseUrl+'albums/index/existing-albumphotos/';
	(scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': URL ,
      'data': {
        format: 'html',
        page: pageNum+1,
        id: id,
      },
      success: function(responseHTML) {
        scriptJquery('#sesalbum_photo_content_'+id).append(responseHTML);
				var dataSrc = scriptJquery('#sesalbum_existing_album_see_more_page_'+id).html();
      	scriptJquery('#sesalbum_existing_album_see_more_'+id).attr('data-src',dataSrc);
				scriptJquery('#sesalbum_existing_album_see_more_page_'+id).remove();
				if(dataSrc == 0)
					scriptJquery('#sesalbum_existing_album_see_more_'+id).parent().remove();
				else
					scriptJquery(thatObject).parent().show();
				scriptJquery('#sesalbum_existing_album_see_more_loading_'+id).hide();
		}
    }));	
});
scriptJquery(document).on('click','#fromExistingAlbum',function(){
	scriptJquery('#sesalbum_popup_existing_upload').show();
	existingPhotosGet();
});
scriptJquery(document).on('click','#uploadProfilePhoto',function(){
		document.getElementById('sesalbum_profile_upload_direct').click();
});
function readImageUrlsesalbum(input){
	var url = input.files[0].name;
	var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
	if((ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG' || ext == 'gif' || ext == 'GIF' || ext == "webp")){
		var formData = new FormData();
		formData.append('webcam', input.files[0]);
		formData.append('user_id', '<?php echo $this->user_id; ?>');
		scriptJquery('#sesalbum-profile-upload-loading').show();
 scriptJquery.ajax({
		xhr:  function() {
		var xhrobj = scriptJquery.ajaxSettings.xhr();
		if (xhrobj.upload) {
				xhrobj.upload.addEventListener('progress', function(event) {
						var percent = 0;
						var position = event.loaded || event.position;
						var total = event.total;
						if (event.lengthComputable) {
								percent = Math.ceil(position / total * 100);
						}
						//Set progress
				}, false);
		}
		return xhrobj;
		},
    url:  en4.core.baseUrl+'albums/index/edit-profilephoto/',
    type: "POST",
    contentType:false,
    processData: false,
		cache: false,
		data: formData,
		success: function(response){
			text = JSON.parse(response);
			if(text.status == 'true'){
				if(text.src != '')
				scriptJquery('#profile_photo').find('img').attr('src',text.src);
			}
			scriptJquery('#sesalbum-profile-upload-loading').hide();
		}
    });
	}
}
scriptJquery(document).on('click','#uploadWebCamPhoto',function(){
	scriptJquery('#sesalbum_popup_cam_upload').show();
	<!-- Configure a few settings and attach camera -->
	Webcam.set({
		width: 320,
		height: 240,
		image_format:'jpeg',
		jpeg_quality: 90
	});
	Webcam.attach('#sesalbum_camera');
});
<!-- Code to handle taking the snapshot and displaying it locally -->
function take_snapshot() {
	// take snapshot and get image data
	Webcam.snap(function(data_uri) {
		Webcam.reset();
		scriptJquery('#sesalbum_popup_cam_upload').hide();
		scriptJquery('#sesalbum-profile-upload-loading').show();
		// upload results
		
		 Webcam.upload( data_uri, en4.core.baseUrl+'albums/index/edit-profilephoto/user_id/<?php echo $this->user_id; ?>' , function(code, text) {
			 	text = JSON.parse(text);
				if(text.status == 'true'){
					if(text.src != '')
					scriptJquery('#profile_photo').find('img').attr('src',text.src);
				}
				scriptJquery('#sesalbum-profile-upload-loading').hide();
			} );
	});
}
function hideProfilePhotoUpload(){
	if(typeof Webcam != 'undefined')
	 Webcam.reset();
	canPaginatePageNumber = 1;
	scriptJquery('#sesalbum_popup_cam_upload').hide();
	scriptJquery('#sesalbum_popup_existing_upload').hide();
	if(typeof Webcam != 'undefined'){
		scriptJquery('.slimScrollDiv').remove();
		scriptJquery('.sesalbum_photo_update_popup_content').html('<div id="sesalbum_album_existing_data"></div><div id="sesalbum_profile_existing_img" style="display:none;text-align:center;"><img src="application/modules/Sesbasic/externals/images/loading.gif" alt="Loading" style="margin-top:10px;"  /></div>');
	}
}
scriptJquery(document).click(function(event){
	if(event.target.id == 'change_profile_txt' || event.target.id == 'profile_change_btn_i' || event.target.id == 'profile_change_btn'){
		if(scriptJquery('#sesalbum_profile_change').hasClass('active'))
			scriptJquery('#sesalbum_profile_change').removeClass('active')
		else
			scriptJquery('#sesalbum_profile_change').addClass('active')
	}else{
			scriptJquery('#sesalbum_profile_change').removeClass('active')
	}
});
</script>
<?php } ?>
