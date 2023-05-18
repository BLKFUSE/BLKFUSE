<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: existing-photos.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<?php foreach( $this->paginator as $album ):?>
  <?php $counterAlbum = 0;?>
  <div class="sesmember_update_album_row sesbasic_clearfix">
  <div id="sesmember_photo_content_<?php echo $album->album_id; ?>">
    <?php $photos = Engine_Api::_()->getDbTable('members', 'sesmember')->getPhotoSelect(array('album_id'=>$album->album_id,'pagNator'=>true)); 
    $photos->setItemCountPerPage($this->limit);
    $photos->setCurrentPageNumber(1);
    if($photos->getTotalItemCount() > 0){
      foreach($photos as $photo){ ?>
	<?php if($counterAlbum == 0){ ?>
	  <span class="sesmember_name"><?php echo $album->title; ?></span>
	<?php } ?>
	  <div class="sesmember_thumb">
	    <a href="javascript:void(0);" id="sesmember_profile_upload_existing_photos_<?php echo $photo->photo_id; ?>" data-src="<?php echo $photo->photo_id; ?>" class="sesmember_thumb_img">
	      <span style="background-image:url(<?php echo $photo->getPhotoUrl('thumb.normalmain'); ?>);"></span>
	    </a>
	  </div>
	<?php
	$counterAlbum++;
      } ?>
      </div>
      <?php if($photos->count() != $photos->getCurrentPageNumber()){ ?>
	<div class="album_more_photos floatR clear">
	  <a href="javascript:;" id="sesmember_existing_album_see_more_<?php echo $album->album_id; ?>" data-src="1">
	  <?php echo $this->translate("See More"); ?> &raquo;
	</a>
	</div>
      <?php } ?>
      <div class="clear" style="text-align:center;display:none;" id="sesmember_existing_album_see_more_loading_<?php echo $album->album_id; ?>">
	<img src="application/modules/Core/externals/images/loading.gif" alt="Loading"  />
      </div>
    <?php }  ?>
  </div>
<?php endforeach;?><br />
<?php  if($this->paginator->getTotalItemCount() == 0){  ?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are currently no albums yet.");?>
    </span>
  </div>    
<?php } ?>
<script type="application/javascript">
  canPaginateExistingPhotos = "<?php echo ($this->paginator->count() == 0 ? '0' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? '0' : '1' ))  ?>";
  canPaginatePageNumber = "<?php echo $this->page + 1; ?>";
  </script>
<?php die; ?>