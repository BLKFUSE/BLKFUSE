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
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesstories/externals/scripts/core.js'); ?>
<div class="sesstories_add_story_popup">

  <form method="post" class="submit_stories">
    <div class="sesstories_add_story_popup_main">
      <div class="sesstories_add_story_popup_left">
        <section>
          <div class="_top">
            <div class="_header">
              <?php echo $this->translate('Create New Story'); ?>
            </div>
            <div class="_userinfo d-flex align-items-center">
              <?php echo $this->itemBackgroundPhoto($this->viewer(), 'thumb.icon') ?>
              <span class="_name"><?php echo $this->viewer()->getTitle(); ?></span>
            </div>
          </div>
          <div class="_cont">
            <div class="stories_description _field" style="display:none;">
              <textarea placeholder="<?php echo $this->translate('Add description...'); ?>" id="sesstories_description"></textarea>
            </div>

            <?php $backgrounds = Engine_Api::_()->getDbTable('backgrounds', 'sesstories')->getBackgrounds(array('fetchAll' => 1, 'admin' => 1)); ?>
            <?php if(is_countable($backgrounds) && engine_count($backgrounds)) { ?>
              <div id="sesstories_add_bg_images" class="sesstories_add_bg_images" style="display:none;">
                <label class="sesbasic_text_light"><?php echo $this->translate("Backgrounds")?></label>
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
        </section>
        <div class="stories_footer _footer" style="display:none;">
          <div>
            <a href="javascript:void(0);" class="sesbasic_link_btn discard_sesstories_btn"><?php echo $this->translate("Discard")?></a>
          </div>
          <div>
            <button type="submit" class="sesstories_btn_submit" disabled><?php echo $this->translate("Share Story")?></button>
          </div>
        </div>
      </div>
      <div class="sesstories_add_story_popup_content">

        <div class="sesstories_story_uploader_main">
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

        <div class="sesstories_story_uploader_preview" style="display:none;">
          <section>
            <div class="_head"><?php echo $this->translate("Preview");?></div>
            <div class="_preview d-flex align-items-center justify-content-center">
              <div class="_previewimg sestories_previewimg" style="background-image:url();">
                <div class="_previewtext sesstories_previewtext" id="sesstories_previewtext">
                </div>
              </div>
              
              <div class="_previewimg sestories_previewvideo">
                <video controls>
                  <source src="" type="video/mp4">
                </video>
              </div>
              
            </div>
          </section>
        </div>

      </div>
    </div>
    <input type="hidden" id="story_type" style="display: none">
    <input type="hidden" id="background_id" style="display: none">
    <input type="file" id="file_multi_sesstories" style="display: none" onchange="readImageUrlsesstories(this)">

  </form>
</div>

<div class="sesstories_confirm_popup_container"  id="discard_sesstories_cnt">
  <div class="sesstories_confirm_popup sesbasic_bg">
    <div class="_head text-center">
      <?php echo $this->translate("Discard story?"); ?>
    </div>
    <div class="_cont">
      <?php echo $this->translate("Are you sure you want to discard this story? Your story won't be saved."); ?>
    </div>
  <div class="_footer">
    <button id="cancel_discard_sesstories" class="sesbasic_link_btn">
      <?php echo $this->translate("Continue editing") ?>
    </button>
    <button id="confirm_discard_sesstories">
      <?php echo $this->translate("Discard") ?>
    </button>
  </div>
</div>

<script>
scriptJquery("#sessmoothbox_main").addClass("sesstories_add_story_popup_container");
</script>