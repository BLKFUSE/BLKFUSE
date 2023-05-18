<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: all-photos.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>

<?php $randonNumber = 'sesphotolightbox_123'; ?>
<?php if(!$this->is_ajax){ ?>
<div class="ses_ml_overlay"></div>
<div class="ses_ml_photos_panel_wrapper sesbasic_clearfix sesbasic_bxs" id="ses_ml_photos_panel_wrapper">
  <div class="ses_ml_photos_panel_header">
      <span class="photoscount"><?php echo $this->translate("All Photos")."(".$this->allPhotos->getTotalItemCount().")" ; ?></span>
      <a href="javascript:;" id="close-all-photos" class="photospanel_closebtn">
        <i class="fa fa-times" id="a_btn_btn"></i>
      </a>
  </div>
  <div class="ses_ml_photos_panel_content" style="height:200px;">
      <div id="all-photo-container">
      <div class="ses_media_lightbox_all_photo" id="ses_media_lightbox_all_photo_id">
<?php } ?>
			<?php $limit = $this->limit; ?>
       <?php foreach($this->allPhotos as $valuePhoto){ 
       		  if (!$valuePhoto instanceof Sesvideo_Model_Chanelphoto)
            	$valuePhoto = Engine_Api::_()->getItem('chanelphoto', $valuePhoto->chanelphoto_id);
       ?>
          <?php $imageURL = Engine_Api::_()->sesvideo()->getImageViewerHref($valuePhoto,array_merge($this->params,array('limit' => $limit))); ?>
          <a id="photo-lightbox-id-<?php echo $valuePhoto->chanelphoto_id; ?>" onclick="getRequestedAlbumPhotoForImageViewer('<?php echo $valuePhoto->getPhotoUrl(); ?>','<?php echo $imageURL	; ?>')" href="<?php echo $valuePhoto->getHref(); ?>" class="ses-image-viewer ses_ml_photos_panel_photo_thumb">
            <span style="background-image:url(<?php echo $valuePhoto->getPhotoUrl('thumb.icon'); ?>);"></span>
         </a>
       <?php 
       	$limit++;
       	} ?>
<?php if(!$this->is_ajax){ ?>
       </div>
      </div>
  </div>
</div>
<?php } ?>
<script type="application/javascript">
viewMoreHide_<?php echo $randonNumber; ?>();
  function viewMoreHide_<?php echo $randonNumber; ?>() {
    canPaginateAllPhoto = "<?php echo ($this->allPhotos->count() == 0 ? '0' : ($this->allPhotos->count() == $this->allPhotos->getCurrentPageNumber() ? '0' : '1' ))  ?>";
  }
function <?php echo $randonNumber; ?> (album_id,photo_id){
    (scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': requestPhotoSesalbumURL,
      'data': {
        format: 'html',
        page: <?php echo $this->page + 1; ?>,    
				is_ajax : 1,
				identity : '<?php echo $randonNumber; ?>',
      },
      success: function(responseHTML) {
        scriptJquery('#ses_media_lightbox_all_video_id').append(responseHTML);
				scriptJquery('#all-photo-container').slimscroll({
					 height: 'auto',
					 alwaysVisible :true,
					 color :'#ffffff',
					 railOpacity :'0.5',
					 disableFadeOut :true,					 
					});
      }
    }));
    return false;
  }
</script>
