<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: featured-block.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<div class="sesmember_featured_photos_popup sesbasic_bxs sesbasic_clearfix">
  <div class="sesmember_photo_update_popup_header"><?php echo $this->translate('Edit Featured Photos');?></div>
  <p><?php echo $this->translate('Choose up to 5 photos you\'d like to feature.');?></p>
  <div class="sesmember_featured_photos_popup_cont clearfix">
    <?php if(engine_count($this->photos)):?>
      <?php $count = 1;?>
      <?php if(engine_count($this->photos) == 5):?>
	<?php foreach($this->photos as $photo):
  	$photo = Engine_Api::_()->getItem('photo',$photo->photo_id);
    if(!$photo)
      	continue;
  ?>
	  <div id="block_<?php echo $count?>" class="sesmember_featured_photos_popup_blank_img">
	    <img src="<?php echo $photo->getPhotoUrl('thumb.normalmain');?>" />
	    <a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock('block_<?php echo $count?>', <?php echo $count?>);" title="<?php echo $this->translate('Remove');?>"></a>
	  </div>
	  <input type="hidden" id="featured_photo_<?php echo $count?>" value="<?php echo $photo->photo_id;?>" />
	  <?php $count++;?>
	<?php endforeach;?>
      <?php elseif(engine_count($this->photos) == 4):?>
        <?php foreach($this->photos as $photo):
        $photo = Engine_Api::_()->getItem('photo',$photo->photo_id);
    if(!$photo)
      	continue;
        ?>
	  <div id="block_<?php echo $count?>" class="sesmember_featured_photos_popup_blank_img">
	    <img src="<?php echo $photo->getPhotoUrl('thumb.normalmain');?>" />
	    <a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock('block_<?php echo $count?>', <?php echo $count?>);" title="<?php echo $this->translate('Remove');?>"></a>
	  </div>
	  <input type="hidden" id="featured_photo_<?php echo $count?>" value="<?php echo $photo->photo_id;?>" />
	  <?php $count++;?>
	<?php endforeach;?>
	<div id="block_5" class="sesmember_featured_photos_popup_blank_img">
	  <a href="javascript:void(0)" title="" id="featured_image_5" data-src='featured_image_5' class="fromExistingAlbumPhoto"><i class="fa fa-plus"></i></a>
	  <div id="hide_cancel_5"></div>
	</div>
	<input type="hidden" id="featured_photo_5" value="" />
      <?php elseif(engine_count($this->photos) == 3):?>
        <?php foreach($this->photos as $photo):
        $photo = Engine_Api::_()->getItem('photo',$photo->photo_id);
    if(!$photo)
      	continue;
        ?>
	  <div id="block_<?php echo $count?>" class="sesmember_featured_photos_popup_blank_img">
	    <img src="<?php echo $photo->getPhotoUrl('thumb.normalmain');?>" />
	    <a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock('block_<?php echo $count?>', <?php echo $count?>);" title="<?php echo $this->translate('Remove');?>"></a>
	  </div>
	  <input type="hidden" id="featured_photo_<?php echo $count?>" value="<?php echo $photo->photo_id;?>" />
	  <?php $count++;?>
	<?php endforeach;?>
	<div id="block_4" class="sesmember_featured_photos_popup_blank_img">
	  <a href="javascript:void(0)" title="" id="featured_image_4" data-src='featured_image_4' class="fromExistingAlbumPhoto"><i class="fa fa-plus"></i></a>
	  <div id="hide_cancel_4"></div>
	</div>
	<input type="hidden" id="featured_photo_4" value="" />
	<div id="block_5" class="sesmember_featured_photos_popup_blank_img">
	  <a href="javascript:void(0)" title="" id="featured_image_5" data-src='featured_image_5' class="fromExistingAlbumPhoto"><i class="fa fa-plus"></i></a>
	  <div id="hide_cancel_5"></div>
	</div>
	<input type="hidden" id="featured_photo_5" value="" />
      <?php elseif(engine_count($this->photos) == 2):?>
        <?php foreach($this->photos as $photo):
        	$photo = Engine_Api::_()->getItem('photo',$photo->photo_id);
    if(!$photo)
      	continue;
        ?>
	  <div id="block_<?php echo $count?>" class="sesmember_featured_photos_popup_blank_img">
	   <img src="<?php echo $photo->getPhotoUrl('thumb.normalmain');?>" />
	   <a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock('block_<?php echo $count?>', <?php echo $count?>);" title="<?php echo $this->translate('Remove');?>"></a>
	  </div>
	  <input type="hidden" id="featured_photo_<?php echo $count?>" value="<?php echo $photo->photo_id;?>" />
	  <?php $count++;?>
	<?php endforeach;?>
        <div id="block_3" class="sesmember_featured_photos_popup_blank_img">
	  <a href="javascript:void(0)" title="" id="featured_image_3" data-src='featured_image_3' class="fromExistingAlbumPhoto"><i class="fa fa-plus"></i></a>
	  <div id="hide_cancel_3"></div>
	</div>
	<input type="hidden" id="featured_photo_3" value="" />
	<div id="block_4" class="sesmember_featured_photos_popup_blank_img">
	  <a href="javascript:void(0)" title="" id="featured_image_4" data-src='featured_image_4' class="fromExistingAlbumPhoto"><i class="fa fa-plus"></i></a>
	  <div id="hide_cancel_4"></div>
	</div>
	<input type="hidden" id="featured_photo_4" value="" />
	<div id="block_5" class="sesmember_featured_photos_popup_blank_img">
	  <a href="javascript:void(0)" title="" id="featured_image_5" data-src='featured_image_5' class="fromExistingAlbumPhoto"><i class="fa fa-plus"></i></a>
	  <div id="hide_cancel_5"></div>
	</div>
	<input type="hidden" id="featured_photo_5" value="" />
      <?php elseif(engine_count($this->photos) == 1):?>
        <?php foreach($this->photos as $photo):
        	$photo = Engine_Api::_()->getItem('photo',$photo->photo_id);
    if(!$photo)
      	continue;
        ?>
	  <div id="block_<?php echo $count?>" class="sesmember_featured_photos_popup_blank_img">
	    <a href="javascript:void(0)" title="" id="featured_image_<?php echo $count?>" data-src='featured_image_<?php echo $count?>' class="fromExistingAlbumPhoto"><img src="<?php echo $photo->getPhotoUrl('thumb.normalmain');?>" /></a>
	    <a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock('block_<?php echo $count?>', <?php echo $count?>);" title="<?php echo $this->translate('Remove');?>"></a>
	  </div>
	  <input type="hidden" id="featured_photo_<?php echo $count?>" value="<?php echo $photo->photo_id;?>" />
	  <?php $count++;?>
	<?php endforeach;?>
	<div id="block_2" class="sesmember_featured_photos_popup_blank_img">
	  <a href="javascript:void(0)" title="" id="featured_image_2" data-src='featured_image_2' class="fromExistingAlbumPhoto"><i class="fa fa-plus"></i></a>
	  <div id="hide_cancel_2"></div>
	</div>
	<input type="hidden" id="featured_photo_2" value="" />
        <div id="block_3" class="sesmember_featured_photos_popup_blank_img">
	  <a href="javascript:void(0)" title="" id="featured_image_3" data-src='featured_image_3' class="fromExistingAlbumPhoto"><i class="fa fa-plus"></i></a>
	  <div id="hide_cancel_3"></div>
	</div>
	<input type="hidden" id="featured_photo_3" value="" />
	<div id="block_4" class="sesmember_featured_photos_popup_blank_img">
	  <a href="javascript:void(0)" title="" id="featured_image_4" data-src='featured_image_4' class="fromExistingAlbumPhoto"><i class="fa fa-plus"></i></a>
	  <div id="hide_cancel_4"></div>
	</div>
	<input type="hidden" id="featured_photo_4" value="" />
	<div id="block_5" class="sesmember_featured_photos_popup_blank_img">
	  <a href="javascript:void(0)" title="" id="featured_image_5" data-src='featured_image_5' class="fromExistingAlbumPhoto"><i class="fa fa-plus"></i></a>
	  <div id="hide_cancel_5"></div>
	</div>
	<input type="hidden" id="featured_photo_5" value="" />
      <?php endif;?>
    <?php else:?>
      <div id="block_1" class="sesmember_featured_photos_popup_blank_img">
	<a href="javascript:void(0)" title="" id="featured_image_1" data-src='featured_image_1' class="fromExistingAlbumPhoto"><i class="fa fa-plus"></i></a>
	<div id="hide_cancel_1"></div>
      </div>
      <input type="hidden" id="featured_photo_1" value="" />
      <div id="block_2" class="sesmember_featured_photos_popup_blank_img">
	<a href="javascript:void(0)" title="" id="featured_image_2" data-src='featured_image_2' class="fromExistingAlbumPhoto"><i class="fa fa-plus"></i></a>
	<div id="hide_cancel_2"></div>
      </div>
      <input type="hidden" id="featured_photo_2" value="" />
      <div id="block_3" class="sesmember_featured_photos_popup_blank_img">
	<a href="javascript:void(0)" title="" id="featured_image_3" data-src='featured_image_3' class="fromExistingAlbumPhoto"><i class="fa fa-plus"></i></a>
	<div id="hide_cancel_3"></div>
      </div>
      <input type="hidden" id="featured_photo_3" value="" />
      <div id="block_4" class="sesmember_featured_photos_popup_blank_img">
	<a href="javascript:void(0)" title="" id="featured_image_4" data-src='featured_image_4' class="fromExistingAlbumPhoto"><i class="fa fa-plus"></i></a>
	<div id="hide_cancel_4"></div>
      </div>		
      <input type="hidden" id="featured_photo_4" value="" />
      <div id="block_5" class="sesmember_featured_photos_popup_blank_img">
	<a href="javascript:void(0)" title="" id="featured_image_5" data-src='featured_image_5' class="fromExistingAlbumPhoto"><i class="fa fa-plus"></i></a>
	<div id="hide_cancel_5"></div>
      </div>
      <input type="hidden" id="featured_photo_5" value="" />
    <?php endif;?>
  </div>
  <div class="sesmember_photo_update_popup_footer">
    <a href="javascript:void(0);" class="sesbasic_button" onclick="javascript:sessmoothboxclose();"><?php echo $this->translate('Cancel');?></a>
    <a href="javascript:void(0)" id="save_featured_photo" class="sesbasic_button"><?php echo $this->translate('Save');?></a>
  </div>
</div>

<script type="text/javascript">
  function showHtml() {
    scriptJquery('<div class="sesmember_photo_update_popup sesbasic_bxs" id="sesmember_popup_existing_upload" style="display:block; z-index:100;"><div class="sesmember_photo_update_popup_overlay"></div><div class="sesmember_photo_update_popup_container" id="sesmember_popup_container_existing"><div class="sesmember_photo_update_popup_header"><?php echo $this->translate("Select a photo") ?><a class="fa fa-times" href="javascript:;" onclick="hideProfileAlbumPhotoUpload()" title="<?php echo $this->translate("Close") ?>"></a></div><div class="sesmember_photo_update_popup_content"><div id="sesmember_album_existing_data"></div><div id="sesmember_profile_existing_img" style="display:none;text-align:center;"><img src="application/modules/Sesbasic/externals/images/loading.gif" alt="<?php echo $this->translate("Loading"); ?>" style="margin-top:10px;"  /></div></div></div></div>').appendTo('body');
  }
  var canPaginatePageNumber = 1;
  function existingAlbumPhotosGet(){
    scriptJquery('#sesmember_profile_existing_img').show();
    var URL = en4.core.baseUrl+'sesmember/index/existing-photos/';
    var photoRequest = scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': URL ,
      'data': {
        format: 'html',
        page: canPaginatePageNumber,
        is_ajax: 1
      },
      success: function(responseHTML) {
        scriptJquery('#sesmember_album_existing_data').append(responseHTML);

        scriptJquery('#sesmember_album_existing_data').slimscroll({
          height: 'auto',
          alwaysVisible :true,
          color :'#000',
          railOpacity :'0.5',
          disableFadeOut :true,
        });
        scriptJquery('#sesmember_album_existing_data').slimScroll().bind('slimscroll', function(event, pos){
          if(canPaginateExistingPhotos == '1' && pos == 'bottom' && scriptJquery('#sesmember_profile_existing_img').css('display') != 'block'){
            scriptJquery('#sesmember_profile_existing_img').css('position','absolute').css('width','100%').css('bottom','5px');
            existingAlbumPhotosGet();
          }
          });
        scriptJquery('#sesmember_profile_existing_img').hide();
      }
    });
  }
  
  function hideProfileAlbumPhotoUpload(){
    canPaginatePageNumber = 1;
    scriptJquery('#sesmember_popup_existing_upload').remove();
    scriptJquery('#sesmember_popup_cam_upload').hide();
    scriptJquery('#sesmember_popup_existing_upload').hide();
  }
  
  function removeBlock(id, position) {
    document.getElementById(id).innerHTML = '<a href="javascript:void(0)" title="" id="featured_image_'+position+'"'+' data-src="featured_image_"'+position+ ' class="fromExistingAlbumPhoto"><i class="fa fa-plus"></i></a>';
    document.getElementById('featured_photo_'+position).value = '';
  }

function sessmoothboxcallback(){
	var isvalid = false;
	for(var i = 1; i<= 5; i++){
		if(scriptJquery('#featured_photo_'+i).val()){
			isvalid = true;	
		}
	}
	if(!isvalid)
		scriptJquery('#save_featured_photo').css('pointer-events','none').css('cursor','default');
}
</script>
