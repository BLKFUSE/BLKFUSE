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
<?php @$randonNumber = @$randonNumber ? @$randonNumber : $this->widgetId; ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Egifts/externals/styles/styles.css'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Egifts/externals/scripts/core.js'); ?>
<div class="egifts_sidebar_listing egifts_browse_listing sesbasic_bxs">
  <ul id="egift-browse-widget_<?php echo $randonNumber; ?>">
  <?php foreach($this->paginator as $item): ?>
    <?php $item = Engine_Api::_()->getItem('egifts_gift', $item->gift_id); ?>
      <?php if(isset($this->params['title_truncation'])):?>
        <?php $titleLimit = $this->params['title_truncation'];?>
      <?php endif;?>
      <?php if(strlen($item->getTitle()) > $titleLimit):?>
        <?php $title = mb_substr($item->getTitle(),0,$titleLimit).'...';?>
      <?php else:?>
        <?php $title = $item->getTitle();?>
      <?php endif; ?>

      <?php if(isset($this->params['description_truncation'])):?>
        <?php $descriptionLimit = $this->params['description_truncation'];?>
      <?php endif;?>
      <?php if(strlen($item->getTitle()) > $descriptionLimit):?>
        <?php $description = mb_substr($item->description,0,$descriptionLimit).'...';?>
      <?php else:?>
        <?php $description = $item->description;?>
      <?php endif; ?>
  	<li class="egifts_sidebar_listing_item">
    	<article>
        <?php if(isset($this->imageActive)){ ?>
        	<div class="egifts_sidebar_listing_item_thumb" style="height:<?php echo is_numeric($this->params['height']) ? $this->params['height'].'px' : $$this->params['height']; ?>;width:<?php echo is_numeric($this->params['width']) ? $this->params['width'].'px' : $this->params['width']; ?>;">
          	<a href="<?php echo $item->getHref(); ?>"><img src="<?php echo $item->getPhotoUrl(); ?>" alt="" /></a>
          </div>	
        <?php } ?>
        <div class="egifts_sidebar_listing_item_info">
          <?php if(isset($this->titleActive)){ ?>
        	  <div class="egifts_sidebar_listing_item_title"><a href="<?php echo $item->getHref(); ?>"><?php echo $title; ?></a></div>
          <?php } ?>
          <?php if(isset($this->priceActive)){ ?>
          	<div class="egifts_sidebar_listing_item_price">
            	<span class="_price sesbasic_text_hl"><?php echo Engine_Api::_()->egifts()->getCurrencyPrice($item->price); ?></span>
          	</div>
          <?php } ?>
          <?php if(isset($this->descriptionActive)): ?>
            <div class="egifts_sidebar_listing_item_des">
            	 <?php echo $description; ?>
            </div>
          <?php endif; ?>
        </div>
      </article>
      <div class="egifts_sidebar_listing_item_buttons">
        <?php if(isset($this->sendButtonActive)): ?>
          <div class="_sendbtn"><?php  include APPLICATION_PATH .  '/application/modules/Egifts/views/scripts/_sendButton.tpl';?></div>
        <?php endif; ?>
        <?php  include APPLICATION_PATH .  '/application/modules/Egifts/views/scripts/_dataButtons.tpl';?>
      </div>
    </li>
  <?php endforeach; ?>  
  </ul> 
</div>
