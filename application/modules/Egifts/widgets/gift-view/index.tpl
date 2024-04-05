<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2020-06-13 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Egifts/externals/styles/styles.css'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Egifts/externals/scripts/core.js'); ?>
<?php $item = $this->item; ?>
<?php $title = $item->getTitle();?>
<?php $description = $item->description;?>
<div class="egifts_view_wrapper sesbasic_bxs" >
	<div class="egifts_view">
  	<div class="egifts_view_top">
      <?php if(isset($this->imageActive)){ ?>
      	<div class="egifts_view_top_img">
        	<img src="<?php echo $item->getPhotoUrl(); ?>" alt="" />
        </div>
      <?php } ?>
      <div class="egifts_view_top_info">
        <?php if(isset($this->titleActive)){ ?>
      	   <h1><?php echo $this->translate(Engine_Api::_()->sesbasic()->textTruncation($title,16)); ?></h1>
        <?php } ?>
        <div class="egifts_view_top_stats sesbasic_text_light">
          <?php  include APPLICATION_PATH .  '/application/modules/Egifts/views/scripts/_dataStatics.tpl';?>
        </div>
        <?php if(isset($this->priceActive)){ ?>
          <div class="egifts_view_top_price">
            <span class="_price sesbasic_text_hl"><?php echo Engine_Api::_()->payment()->getCurrencyPrice($item->price); ?></span>
          </div>
        <?php } ?>
        <div class="egifts_view_top_buttons">
        	 <?php  include APPLICATION_PATH .  '/application/modules/Egifts/views/scripts/_dataButtons.tpl';?>
          <?php if(isset($this->sendButtonActive)): ?>
           <div class="_sendbtn">
            <?php include APPLICATION_PATH .  '/application/modules/Egifts/views/scripts/_sendButton.tpl';?>
           </div>
          <?php endif; ?>
        </div>
    	</div>
    </div>
    <?php if(isset($this->descriptionActive)): ?>
      <div class="egifts_view_bottom">
      	<div class="egifts_view_des_head sesbasic_text_hl"><b><?php echo $this->translate("Description"); ?></b></div>
      	<div class="egifts_view_des sesbasic_html_block">
  		      <?php echo $description; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
