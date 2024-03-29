<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>

<?php $baseUrl = $this->layout()->staticBaseUrl; ?>
  <?php if(isset($this->bannerImage) && !empty($this->bannerImage)){ ?>
    <div class="sesvideo_category_cover sesbasic_bxs sesbm">
      <div class="sesvideo_category_cover_inner" style="background-image:url(<?php echo Engine_Api::_()->sesvideo()->getFileUrl($this->bannerImage); ?>);">
        <div class="sesvideo_category_cover_content">
          <div class="sesvideo_category_cover_blocks">
            <div class="sesvideo_category_cover_block_img">
              <span style="background-image:url(<?php echo Engine_Api::_()->sesvideo()->getFileUrl($this->bannerImage); ?>);"></span>
            </div>
            <div class="sesvideo_category_cover_block_info">
              <?php if(isset($this->title) && !empty($this->title)): ?>
                <div class="sesvideo_category_cover_title"> 
                  <?php echo $this->title; ?>
                </div>
              <?php endif; ?>
              <?php if(isset($this->description) && !empty($this->description)): ?>
                <div class="sesvideo_category_cover_des clear sesbasic_custom_scroll">
                  <?php echo $this->description;?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php }else{ ?>
    <div class="sesvideo_browse_cat_top sesbm">
      <?php if(isset($this->title) && !empty($this->title)): ?>
        <div class="sesvideo_catview_title"> 
          <?php echo $this->title; ?>
        </div>
      <?php endif; ?>
      <?php if(isset($this->description) && !empty($this->description)): ?>
        <div class="sesvideo_catview_des">
          <?php echo $this->description;?>
        </div>
      <?php endif; ?>
    </div>
  <?php } ?>
