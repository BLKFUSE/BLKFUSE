<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: preview-reaction.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesadvancedcomment/views/scripts/_jsFiles.tpl'; ?>
<div class="ses_emoji_store_preview sesbasic_clearfix">
	<div class="ses_emoji_store_preview_back_link">
  	<a href="javascript:;" class="sesact_back_store">
    	<i class="fa fa-chevron-left"></i>
    	<span><?php echo $this->translate("Sticker Store"); ?></span>
    </a>
  </div>
  <?php $gallery = $this->gallery; ?>
  <div class="sesbasic_custom_scroll sesbasic_clearfix ses_emoji_store_preview_cont">
  	<div class="ses_emoji_store_preview_info sesbasic_clearfix">
      <?php if(Engine_Api::_()->storage()->get($gallery->file_id, '')) { ?>
      <div class="floatL ses_emoji_store_preview_info_img">
        <img src="<?php echo Engine_Api::_()->storage()->get($gallery->file_id, '')->getPhotoUrl(); ?>">
      </div>
      <?php } ?>
      <div class="ses_emoji_store_preview_info_cont">
        <div class="ses_emoji_store_preview_title">
          <?php echo $gallery->getTitle(); ?>
        </div>
        <div class="ses_emoji_store_preview_des">
        	 <?php echo $gallery->getDescription(); ?>
        </div>
        <div class="ses_emoji_store_preview_btn">
          <?php if($this->useremotions && Engine_Api::_()->storage()->get($gallery->file_id, '')){ ?>
            <button type="button" data-gallery="<?php echo $gallery->getIdentity(); ?>" data-remove="<?php echo $this->translate('Remove'); ?>" data-add="<?php echo $this->translate('Add') ?>" class="sesadv_reaction_remove_emoji  sesadv_reaction_remove_emoji_<?php echo $gallery->getIdentity(); ?>" data-title="<?php echo $gallery->getTitle(); ?>" data-src="<?php echo Engine_Api::_()->storage()->get($gallery->file_id, '')->getPhotoUrl(); ?>"><?php echo $this->translate('Remove'); ?></button>
          <?php }else if(Engine_Api::_()->storage()->get($gallery->file_id, '')){ ?>
           <button type="button" data-gallery="<?php echo $gallery->getIdentity(); ?>" data-remove="<?php echo $this->translate('Remove'); ?>" data-add="<?php echo $this->translate('Add') ?>"  class="sesadv_reaction_add_emoji  sesadv_reaction_add_emoji_<?php echo $gallery->getIdentity(); ?>" data-title="<?php echo $gallery->getTitle(); ?>" data-src="<?php echo Engine_Api::_()->storage()->get($gallery->file_id, '')->getPhotoUrl(); ?>"><?php echo $this->translate('Add'); ?></button>
          <?php } ?>
        </div>
      </div>
    </div>
    <div class="ses_emoji_store_preview_stickers sesbasic_clearfix">
      <?php 
        $files =  Engine_Api::_()->getItemTable('sesadvancedcomment_emotionfile')->getFiles(array('fetchAll'=>true,'gallery_id'=>$gallery->getIdentity()));
        foreach($files as $file){ ?>
        	<div class="ses_emoji_store_preview_stickers_icon">
          	<span style="background-image:url(<?php echo Engine_Api::_()->storage()->get($file->photo_id, '')->getPhotoUrl(); ?>);"></span>
          </div>
        <?php } ?>
    </div>
  </div>
</div>
<?php die; ?>
