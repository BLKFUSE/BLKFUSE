<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: existing-videos.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl .'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>
<?php foreach( $this->paginator as $video ){
	
?>
<div class="sescontest_update_video_row sesbasic_clearfix">
	<div id="sescontest_photo_content_<?php echo $video->video_id;?>">
            <?php $urlIframe = ($video->getRichContent(true,array(),'',false));?>
            <div id="sescontest_thumb_<?php echo $video->video_id; ?>" class="sescontest_thumb">
            <div style="display: none;">
        <?php echo str_replace(array('<iframe','</iframe>','<script','</script'),array('[sesframe','[/sesframe]','[script','[/script'),$urlIframe); ?>
      </div>
            <a href="javascript:void(0);" title="<?php echo $video->title;?>" id="sescontest_profile_upload_existing_photos_<?php echo $video->video_id; ?>" data-src="<?php echo $video->video_id; ?>" class="sescontest_thumb_img">
                <span class="bg_item_photo" style="background-image:url(<?php echo $video->getPhotoUrl('thumb.normal'); ?>); display:block;height:100px;width:100px"></span>
                <p class="photo_tittle"><?php echo $video->title;?></p>
              </a>
            </div>
</div>    
 			<?php if(0){ ?>
        <div class="album_more_photos floatR clear">
          <a href="javascript:;" id="sescontest_existing_album_see_more_<?php echo $video->album_id; ?>" data-src="1">
            <?php echo $this->translate("See More"); ?> &raquo;
          </a>
        </div>
      <?php } ?>
      <div class="clear" style="text-align:center;display:none;" id="sescontest_existing_album_see_more_loading_<?php echo $video->video_id; ?>">
      	<img src="application/modules/Core/externals/images/loading.gif" alt="Loading"  />
      </div>
</div>
<?php } ?>
<?php  if($this->paginator->getTotalItemCount() == 0){  ?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are currently no videos");?>
      <?php echo $this->translate('Be the first to %1$screate%2$s one!', 
      '<a href="'.$this->url(array('action' => 'create','controller'=>'index'),'sesvideo_general',true).'">', '</a>'); 
      ?>
    </span>
  </div>    
<?php } ?>
<script type="application/javascript">
canPaginateExistingPhotos = "<?php echo ($this->paginator->count() == 0 ? '0' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? '0' : '1' ))  ?>";
canPaginatePageNumber = "<?php echo $this->page + 1; ?>";
</script>
<?php die; ?>