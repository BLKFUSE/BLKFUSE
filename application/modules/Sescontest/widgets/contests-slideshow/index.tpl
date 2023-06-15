<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $identity = $this->identity;?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>
<?php if($this->params['enableSlideshow']){ ?>
<?php
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js');
?>
 <?php } ?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<?php $viewerId = $viewer->getIdentity();?>
<div class="contests_slideshow_wrapper sesbasic_clearfix sesbasic_bxs">
  <div class="contests_slideshow_container sesbasic_clearfix">

    <div class="_right_col <?php if(empty($this->params['leftContest'])) {?>_noleftblock<?php } ?>" <?php if(empty($this->params['enableSlideshow'])) { ?> style="display:none;" <?php } ?>>
      <div class="contests_slideshow contests_slideshow_<?php echo $this->identity;?>" style="height:<?php echo $this->params['height'] ?>px;">
        <?php $leftData = array();?>
        <?php foreach($this->contests as $data):?>
          <?php if($data['type'] == 'left'):?>
             <?php $leftData[] = $data['contest_id']; ?>
             <?php continue;?>
          <?php endif;?>
          <?php $contest = Engine_Api::_()->getItem('contest', $data['contest_id']);?>
          <div class="contests_slideshow_item sesbasic_clearfix">
            <div class="contests_slideshow_thumb sescontest_thumb" style="height:<?php echo $this->params['height'] ?>px;">       
              <a href="<?php echo $contest->getHref(); ?>" class="contests_slideshow_thumb_img">
                <span style="background-image:url(<?php echo $contest->getPhotoUrl('thumb.profile'); ?>);"></span>
              </a>
              <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_mediaType.tpl';?>
              <div class="sescontest_list_btns"> 
                <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataSharing.tpl';?>
              </div>
              <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->hotLabel)):?>
                <div class="sescontest_slideshow_labels sesbasic_animation">
                  <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataLabel.tpl';?>
                </div>
              <?php endif;?>
            </div>
            <?php if(isset($this->categoryActive)):?>
              <div class="contests_slideshow_cat sesbasic_animation">
                <?php if (!empty($contest->category_id)):?>
                <?php $category = Engine_Api::_ ()->getDbtable('categories', 'sescontest')->find($contest->category_id)->current();?>
                <?php endif;?>
                <span><a href="<?php echo $contest->getHref();?>"><?php echo $category->category_name;?></a></span>
              </div>
            <?php endif;?>
            <div class="contests_slideshow_cont_wrap">
              <div class="contests_slideshow_cont">   
                <?php if(isset($this->titleActive)):?>
                  <?php if(strlen($contest->getTitle()) > $this->params['title_truncation']):?>
                    <?php $title = mb_substr($contest->getTitle(),0,$this->params['title_truncation']).'...';?>
                  <?php else: ?>
                    <?php $title = $contest->getTitle();?>
                  <?php endif; ?>
                  <div class="contests_slideshow_title">
                    <a href="<?php echo $contest->getHref();?>"><?php echo $title;?></a><?php if(isset($this->verifiedLabelActive)&& $contest->verified):?><i class="sescontest_label_verified fa fa-check-circle" title='<?php echo $this->translate("Verified");?>'></i><?php endif;?>
                  </div>
                <?php endif;?>
                <?php if(isset($this->descriptionActive)):?>
                  <div class="contests_slideshow_des">
                    <p><?php echo $this->string()->truncate($this->string()->stripTags($contest->description), $this->params['description_truncation']) ?></p>
                  </div>
                <?php endif;?>
                <div class="contests_slideshow_stats">
                  <?php if(isset($this->byActive)):?>
                    <?php $owner = $contest->getOwner();?>
                    <span><?php echo $this->translate('by');?>&nbsp;<?php echo $this->htmlLink($owner->getHref(), $owner->getTitle());?></span><span>|</span>
                  <?php endif;?>
                  <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataStatics.tpl';?>
                </div>
              </div>
              <div class="contests_slideshow_total">
                <?php include APPLICATION_PATH . '/application/modules/Sescontest/views/scripts/_dataBar.tpl'; ?>
              </div>
            </div>
          </div>	
        <?php endforeach;?>
      </div>
    </div>
    <?php if($this->params['leftContest']) { 
      $height = ($this->params['height'] -20) / 3;
    ?>
      <div class="_left_col <?php if(empty($this->params['enableSlideshow'])) {?>_norightblock<?php } ?>">
        <?php for($i=0;$i<count($leftData);$i++):?>
          <?php $contest = Engine_Api::_()->getItem('contest', $leftData[$i]);?>
          <div class="contests_slideshow_left_item">
            <div class="contests_slideshow_left_item_thumb" style="height:<?php echo $height ?>px;">
              <a href="<?php echo $contest->getHref(); ?>" class="contests_slideshow_left_item_thumb_img">
                <span style="background-image:url(<?php echo $contest->getPhotoUrl('thumb.profile'); ?>);"></span>
              </a>
              <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_mediaType.tpl';?>
              <div class="sescontest_list_btns"> 
                <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataSharing.tpl';?>
              </div>
              <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->hotLabel)):?>
                <div class="sescontest_slideshow_labels sesbasic_animation">
                  <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataLabel.tpl';?>
                </div>
              <?php endif;?>
            </div>
            <?php if(isset($this->categoryActive)):?>
              <div class="contests_slideshow_cat sesbasic_animation">
                <?php if (!empty($contest->category_id)):?>
                <?php $category = Engine_Api::_ ()->getDbtable('categories', 'sescontest')->find($contest->category_id)->current();?>
                <?php endif;?>
                <span><a href="<?php echo $contest->getHref();?>"><?php echo $category->category_name;?></a></span>
              </div>
            <?php endif;?>
            <div class="contests_slideshow_left_item_cont">    
              <?php if(isset($this->titleActive)):?>
                <div class="contests_slideshow_left_item_title">
                  <a href="<?php echo $contest->getHref();?>"><?php echo $contest->getTitle();?></a><?php if(isset($this->verifiedLabelActive)&& $contest->verified):?><i class="sescontest_label_verified fa fa-check-circle" title='<?php echo $this->translate("Verified");?>'></i><?php endif;?>
                </div>
              <?php endif;?>
              <div class="contests_slideshow_left_item_stats">
                <?php if(isset($this->byActive)):?>
                <?php $owner = $contest->getOwner();?>
                <span><?php echo $this->translate('by');?>&nbsp;<?php echo $this->htmlLink($owner->getHref(), $owner->getTitle());?></span><span>|</span>
                <?php endif;?>
                <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataStatics.tpl';?>
              </div>
            </div>  
          </div>
        <?php endfor;?>
      </div>
    <?php } ?>
  </div>
</div>
<?php if($this->enableSlideshow): ?>
  <style>
  .owl-prev, .owl-next {
    display:block !important;
  }
  </style>
<?php endif; ?>
<script type="text/javascript">
//Slideshow widget
sesowlJqueryObject(document).ready(function() {
  var sescontestElement = scriptJquery('.contests_slideshow_<?php echo $this->identity;?>');
	if(sescontestElement.length > 0) {
    var sescontestElements = sesowlJqueryObject('.contests_slideshow_<?php echo $this->identity;?>');
    sescontestElements.each(function(){
      sesowlJqueryObject(this).owlCarousel({
        loop:true,
        items:1,
        margin:0,
        autoHeight:true,
        autoplay:<?php echo $this->params['autoplay'] ?>,
        autoplayTimeout:<?php echo $this->params['speed'] ?>,
        autoplayHoverPause:true
      });
      sesowlJqueryObject(".owl-prev").html('<i class="fa fa-angle-left"></i>');
      sesowlJqueryObject(".owl-next").html('<i class="fa fa-angle-right"></i>');
    });
	}
});
</script>
<style type="text/css">
  <?php if($this->params['navigation'] == 2){?>
    .contests_slideshow_<?php echo $this->identity;?> .owl-dots{
      display:none;
    }
    .contests_slideshow_<?php echo $this->identity;?> .owl-nav > div{
      display:block !important;
    }
  <?php } else{ ?>
    .contests_slideshow_<?php echo $this->identity;?> .owl-nav{
       display:none;
    }
  <?php } ?>
</style>
