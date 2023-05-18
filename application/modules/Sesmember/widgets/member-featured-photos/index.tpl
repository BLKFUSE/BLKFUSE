<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmember/externals/styles/styles.css'); ?> 
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>
<div class="sesbasic_sidebar_block sesmember_featured_photos_block sesbasic_bxs sesbasic_clearfix">
	<?php $classnumber = ''; ?>
	
  <?php if(engine_count($this->photos) == 1):?>
    <?php $classnumber = 1;?>
  <?php elseif(engine_count($this->photos) == 2):?>
    <?php $classnumber = 2;?>
  <?php elseif(engine_count($this->photos) == 3):?>
    <?php $classnumber = 3;?>
  <?php elseif(engine_count($this->photos) == 4):?>
    <?php $classnumber = 4;?>
  <?php elseif(engine_count($this->photos) == 5):?>
    <?php $classnumber = 5;?>
  <?php endif;?>
  <div class="sesmember_featured_photos_block_photos sm_f_photo<?php echo $classnumber?>">
    <?php 
    $limit = 0;
    foreach($this->photos as $photo): ?>
    <?php $photo = Engine_Api::_()->getItem('photo',$photo->photo_id);
    	if(!$photo)
      	continue;
     ?>
      <div class="sesmember_featured_photos_block_item">
      <?php if(!$this->sesalbumenabled){ ?>
				<a href="<?php echo $photo->getHref().'?featured=1'; ?>"><img src="<?php echo $photo->getPhotoUrl('thumb.normalmain');?>" alt=""></a>
      <?php }else{ ?>
      	<?php $imageURL = Engine_Api::_()->sesalbum()->getImageViewerHref($photo,array('status'=>'member-featured','limit'=>$limit)); ?>
      	<a class="ses-image-viewer" onclick="getRequestedAlbumPhotoForImageViewer('<?php echo $photo->getPhotoUrl(); ?>','<?php echo $imageURL	; ?>')" href="<?php echo Engine_Api::_()->sesalbum()->getHrefPhoto($photo->getIdentity(),$photo->album_id); ?>"><img src="<?php echo $photo->getPhotoUrl('thumb.normalmain');?>"></a>
      <?php } ?>
      </div>
    <?php  $limit++;endforeach;
   
    ?>
  </div>
  <?php if($this->user_id == $this->viewer_id):?>
    <?php if(engine_count($this->photos) < 1):?>
      <div class="sesmember_featured_photos_block_link">
	<a href="<?php echo $this->url(array('action'=>'featured-block'), 'sesmember_general'); ?>" class="sessmoothbox sesbasic_icon_add"><?php echo $this->translate('Add Featured Photos'); ?></a>
      </div>
    <?php else:?>
      <a href="<?php echo $this->url(array('action'=>'featured-block', 'featured' => 1), 'sesmember_general'); ?>" class="sessmoothbox sesbasic_icon_edit sesbasic_button sesmember_featured_photos_edit" title="<?php echo $this->translate('Edit Photos'); ?>"></a>
    <?php endif;?>
    <div class="sesmember_featured_photos_block_overlay" style="display:none;"></div>
  <?php endif;?>
</div>
