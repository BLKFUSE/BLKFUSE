<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: existin-photos.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl .'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>
<?php foreach( $this->paginator as $album ){
			$counterAlbum = 0;
?>
<div class="sescontest_update_album_row sesbasic_clearfix">
	<div id="sescontest_photo_content_<?php echo $album->album_id; ?>">
	<?php $photos = Engine_Api::_()->getDbTable('photos', 'sesalbum')->getPhotoSelect(array('album_id'=>$album->album_id,'pagNator'=>true));  
                  $photos->setItemCountPerPage($this->limit);
                  $photos->setCurrentPageNumber(1);
        if($photos->getTotalItemCount() > 0){
          foreach($photos as $photo){ ?>
          <?php if($counterAlbum == 0){ ?>
            <span class="sescontest_name"><?php echo $album->title; ?></span>
          <?php } ?>
            <div class="sescontest_thumb">
            	<a href="javascript:void(0);" id="sescontest_profile_upload_existing_photos_<?php echo $photo->photo_id; ?>" data-src="<?php echo $photo->photo_id; ?>" class="sescontest_thumb_img">
                <span class="bg_item_photo" style="background-image:url(<?php echo $photo->getPhotoUrl('thumb.normalmain'); ?>);"></span>
              </a>
            </div>
        <?php
            $counterAlbum++;
          } ?>
 </div>
 			<?php if($photos->count() != $photos->getCurrentPageNumber()){ ?>
        <div class="album_more_photos floatR clear">
          <a href="javascript:;" id="sescontest_existing_album_see_more_<?php echo $album->album_id; ?>" data-src="1">
            <?php echo $this->translate("See More"); ?> &raquo;
          </a>
        </div>
      <?php } ?>
      <div class="clear" style="text-align:center;display:none;" id="sescontest_existing_album_see_more_loading_<?php echo $album->album_id; ?>">
      	<img src="application/modules/Core/externals/images/loading.gif" alt="Loading"  />
      </div>
  <?php }  ?>
</div>
<?php } ?>
<?php  if($this->paginator->getTotalItemCount() == 0){  ?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are currently no albums");?>
      <?php echo $this->translate('Be the first to %1$screate%2$s one!', 
      '<a href="'.$this->url(array('action' => 'create','controller'=>'index'),'sesalbum_general',true).'">', '</a>'); 
      ?>
    </span>
  </div>    
<?php } ?>
<script type="application/javascript">
canPaginateExistingPhotos = "<?php echo ($this->paginator->count() == 0 ? '0' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? '0' : '1' ))  ?>";
canPaginatePageNumber = "<?php echo $this->page + 1; ?>";
</script>
<?php die; ?>