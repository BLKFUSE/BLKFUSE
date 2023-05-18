<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: create.tpl 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<div class="sesstories_add_story_popup sesstories_bxs">
	<div class="sesstories_add_story_popup_header">
  	<?php echo $this->translate('Create New Story'); ?>
  </div>
  <form method="post" class="submit_stories">
    <div class="sesstories_add_story_popup_content">

      <div class="sesstories_add_story_field sesstories_story_uploader_main">
      	<div class="sesstories_story_uploader sesstories_story_uploader_img">
        	<article class="multi_upload_sesstories" data-type="imagevideo">
            <i class="fas fa-photo-video"></i>
            <span class="_label"><?php echo $this->translate("Upload Photo or Video"); ?></span>
          </article>
        </div>	

      	<div class="sesstories_story_uploader sesstories_story_uploader_text">
        	<article class="text_sesstories" data-type="text">
            <i class="fas fa-font"></i>
            <span class="_label"><?php echo $this->translate("Create a text story"); ?></span>
          </article>
        </div>	
      </div>

      <div class="sesstories_add_story_field">
      	<textarea placeholder="<?php echo $this->translate('Add description...'); ?>" id="sesstories_description"></textarea>
      </div>

      <?php $backgrounds = Engine_Api::_()->getDbTable('backgrounds', 'sesstories')->getBackgrounds(array('fetchAll' => 1, 'admin' => 1)); ?>
      <?php if(is_countable($backgrounds) && engine_count($backgrounds)) { ?>
        <div id="sesstories_add_bg_images" class="sesstories_add_bg_images" style="display:none;">
          <?php foreach($backgrounds as $background) { ?>
            <?php $photo = Engine_Api::_()->storage()->get($background->file_id, '');
              if($photo) {
                $photo = $photo->getPhotoUrl(); ?>
              <a class="background_img" id="background_<?php echo $background->background_id; ?>" href="javascript:void(0);" onclick="selectfeedbgimage('<?php echo $background->background_id; ?>');"><img id="select_feed_bg_image_<?php echo $background->background_id; ?>" data-id="<?php echo $background->background_id; ?>" alt="" src="<?php echo $photo; ?>" /></a>
            <?php } ?>
          <?php } ?>

        </div>
      <?php } ?>

    </div>
    <input type="hidden" id="story_type" style="display: none">
    <input type="hidden" id="background_id" style="display: none">
    <input type="file" id="file_multi_sesstories" style="display: none" onchange="readImageUrlsesstories(this)">
    <div class="sesstories_add_story_popup_footer">
    	<button type="submit" class="sesstories_btn_submit" disabled>Publish</button>
    </div>
  </form>
</div>
