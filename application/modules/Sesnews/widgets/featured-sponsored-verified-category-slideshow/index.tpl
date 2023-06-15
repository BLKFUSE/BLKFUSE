<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesnews
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2016-07-23 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/scripts/core.js'); ?> 

<?php 
  $allParams=$this->allParams;
  $baseUrl = $this->layout()->staticBaseUrl;
  $this->headLink()->appendStylesheet($baseUrl . 'application/modules/Sesnews/externals/styles/styles.css');
  $this->headLink()->appendStylesheet($baseUrl . 'application/modules/Sesnews/externals/styles/slideshow.css');
?>
<?php
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js');
?>
<div class="sesnews_slideshow_news_wrapper sesbasic_clearfix sesbasic_bxs <?php echo $this->isfullwidth ? 'isfull_width' : '' ; ?>" style="height:<?php echo $this->height ?>px;">
  <div class="sesnews_slideshow_news_container owl-carousel" style="height:<?php echo $this->height ?>px;">
    <?php foreach($this->paginator as $item): ?>
    <?php $user = $item->getOwner();
        $oldTimeZone = date_default_timezone_get();
        $convert_date = strtotime($item->creation_date);
        date_default_timezone_set($user->timezone);
        ?>
      <div class="sesnews_slideshow_inner_view sesbasic_clearfix" style="height:<?php echo $this->height ?>px;">
        <div class="sesnews_slideshow_inside">
          <div class="sesnews_slideshow_thumb sesnews_thumb"  style="height:<?php echo $this->height ?>px;">       
            <a href="<?php echo $item->getHref(); ?>" class="sesnews_slideshow_thumb_img">
              <!-- <span style="background-image:url(<?php // echo $item->getPhotoUrl(''); ?>);"></span> -->
              <span><img src="<?php echo $item->getPhotoUrl(''); ?>" alt"" /></span>
            </a>
            <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive)): ?>
              <div class="sesnews_grid_labels">
                <?php if(isset($this->sponsoredLabelActive) && $item->sponsored == 1):?>
                  <p class="sesnews_label_sponsored" title="<?php echo $this->translate('Sponsored');?>"><i class="fa fa-star"></i></p>
                <?php endif;?>
                <?php if(isset($this->featuredLabelActive) && $item->featured == 1):?>
                  <p class="sesnews_label_featured" title="<?php echo $this->translate('Featured');?>"><i class="fa fa-star"></i></p>
                  <?php endif;?>
              </div>
            <?php endif;?>
          </div>
        </div>
        <div class="sesnews_slideshow_inside_contant">
          <div class="slideshow_contant">
            <div class="sesnews_slideshow_info sesbasic_clearfix ">
              <div class="sesnews_slideshow_cont_header">
                <?php if(isset($this->byActive)){ ?>
                  <div class="admin_teg sesnews_list_stats">
                    <span><?php echo $this->translate("Posted by") ?>  <?php echo $this->htmlLink($item->getOwner()->getParent(), $this->itemPhoto($item->getOwner()->getParent(), 'thumb.icon')); ?> <?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle() ) ?></span>
                  </div>
                <?php } ?>
                <?php if(isset($this->creationDateActive)) { ?>
                  <div class="admin_teg sesnews_list_stats"><span> on <?php echo ' '.date('M d, Y',$convert_date);?></span></div>
                <?php } ?>
              </div>
              <?php if(isset($this->titleActive) ){ ?>
                <span class="sesnews_slideshow_info_title">
                  <?php if(strlen($item->getTitle()) > $this->title_truncation){ 
                    $title = mb_substr($item->getTitle(),0,$this->title_truncation).'...';
                    echo $this->htmlLink($item->getHref(),$title) ?>
                  <?php }else{ ?>
                    <?php echo $this->htmlLink($item->getHref(),$item->getTitle() ) ?>
                  <?php } ?>
                  <?php if(isset($this->verifiedLabelActive) && $item->verified == 1): ?>
                  <i class="sesbasic_verified_icon" title="Verified"></i>
                  <?php endif;?>
                </span>
              <?php } ?>
              <div class="sesnews_slideshow_header_two">
                <?php if(isset($this->categoryActive)) { ?>
                  <?php if($item->category_id != '' && intval($item->category_id) && !is_null($item->category_id)):?> 
                    <?php $categoryItem = Engine_Api::_()->getItem('sesnews_category', $item->category_id);?>
                    <?php if($categoryItem):?>
                      <div class="category_tag sesbasic_clearfix">
                        <span><a href="<?php echo $categoryItem->getHref(); ?>"><?php echo $categoryItem->category_name; ?></a></span>
                      </div>
                    <?php endif;?>
                  <?php endif;?>
                <?php } ?>
                <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enablereadtime', 1) && isset($this->readtimeActive) && !empty($item->readtime)) { ?>
                  <div class="sesnews_list_stats">
                    <span><i class="far fa-clock"></i><?php echo $item->readtime ?>. <?php echo $this->translate("read"); ?></span>
                  </div>
                <?php } ?>
                <?php if(Engine_Api::_()->getApi('core', 'sesnews')->allowReviewRating() && isset($this->ratingStarActive)): ?>
                  <?php echo $this->partial('_newsRating.tpl', 'sesnews', array('rating' => $item->rating, 'class' => 'sesnews_list_rating sesnews_list_view_ratting', 'style' => 'margin-bottom:0px;'));?>
                <?php endif;?>
              </div>
              <div class="sesnews_slideshow-des">
                <?php if(isset($this->descriptionActive)){ ?>
                  <p><?php echo $item->getDescription('150');?></p>
                <?php  } ?>
              </div>
              <div class="sesnews_slideshow_footer">
                <div class="sesnews_list_stats">
                  <?php if(isset($this->likeActive) && isset($item->like_count)) { ?>
                    <span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $item->like_count; ?></span>
                  <?php } ?>
                  <?php if(isset($this->commentActive) && isset($item->comment_count)) { ?>
                    <span title="<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $item->comment_count;?> </span>
                  <?php } ?>
                  <?php if(isset($this->favouriteActive) && isset($item->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)) { ?>
                    <span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $item->favourite_count;?> </span>
                  <?php } ?>
                  <?php if(isset($this->viewActive) && isset($item->view_count)) { ?>
                    <span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $item->view_count; ?></span>
                  <?php } ?>
                  <?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/_newsRatingStat.tpl';?>
                </div>
                <?php if((isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive)):?>
                  <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
                  <div class="sesnews_list_share_btns">
                    <?php if(isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)): ?>
                      <?php echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item,  'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
                    <?php endif;?>
                    <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 ): ?>
                      <?php $canComment =  $item->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');?>
                      <?php if(isset($this->likeButtonActive) && $canComment): ?>
                        <?php $LikeStatus = Engine_Api::_()->sesnews()->getLikeStatus($item->news_id,$item->getType()); ?>
                        <a href="javascript:;" data-url="<?php echo $item->news_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesnews_like_sesnews_news_<?php echo $item->news_id ?> sesnews_like_sesnews_news <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count; ?></span></a>
                      <?php endif;?>
                      <?php if(isset($this->favouriteButtonActive) && isset($item->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)): ?>
                        <?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesnews')->isFavourite(array('resource_type'=>'sesnews_news','resource_id'=>$item->news_id)); ?>
                        <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesnews_favourite_sesnews_news_<?php echo $item->news_id ;?>  sesbasic_icon_fav_btn sesnews_favourite_sesnews_news <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $item->news_id ; ?>"><i class="fa fa-heart"></i><span><?php echo $item->favourite_count; ?></span></a>
                      <?php endif;?>
                    <?php endif;?>
                  </div>
                <?php endif;?> 
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php date_default_timezone_set($oldTimeZone); ?>
    <?php endforeach; ?>
  </div>
</div>
<script type="text/javascript">
sesowlJqueryObject(document).ready(function() {
	<?php if($allParams['autoplay']) { ?>
		var autoplay_<?php echo $this->identity; ?> = true;
	<?php } else { ?>
		var autoplay_<?php echo $this->identity; ?> = false;
	<?php } ?>
  sesowlJqueryObject(".sesnews_slideshow_news_container").owlCarousel({
    nav:true,
    dots:false,
		loop:sesowlJqueryObject(".sesnews_slideshow_inner_view").length <= 1 ? false : true,
    items:1,
    responsiveClass:true,
    autoplay: autoplay_<?php echo $this->identity; ?>,
		autoplaySpeed:5000,
    responsive:{
      0:{
          items:1,
      },
      600:{
          items:1,
      },
    },
  })
  sesowlJqueryObject(".owl-prev").html('<i class="fa fa-long-arrow-alt-left"></i>');
  sesowlJqueryObject(".owl-next").html('<i class="fa fa-long-arrow-alt-right"></i>');
});
</script>
