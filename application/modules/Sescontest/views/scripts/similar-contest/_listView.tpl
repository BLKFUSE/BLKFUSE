<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _listView.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $owner = $contest->getOwner();?>
<div class="sescontest_sidebar_list_item sesbasic_clearfix">
  <div class="sescontest_sidebar_list_item_img" style="width:<?php echo is_numeric($width)?$width.'px':$width ?>;">
    <?php if($this->mediaTypeActive):?>
    	<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_mediaType.tpl';?>
    <?php endif;?>
    <span style="height:<?php echo is_numeric($height)?$height.'px':$height ?>;width:<?php echo is_numeric($width)?$width.'px':$width ?>;"><a href="<?php echo $contest->getHref();?>" class="floatL sescontest_thumb_img"><span class="bg_item_photo" style="background-image:url(<?php echo $contest->getPhotoUrl('thumb.profile'); ?>);"></span></a></span>
    <div class="sescontest_sidebar_list_labels sesbasic_animation">
      <?php if(isset($this->featuredLabelActive) && $contest->featured):?>
      <span class="sescontest_label_featured" title="<?php echo $this->translate('Featured');?>"><i class="fa fa-star"></i></span>
      <?php endif;?>
      <?php if(isset($this->sponsoredLabelActive) && $contest->sponsored):?>
      <span class="sescontest_label_sponsored" title="<?php echo $this->translate('Sponsored');?>"><i class="fa fa-star"></i></span>
      <?php endif;?>
      <?php if(isset($this->hotLabelActive) && $contest->hot):?>
      <span class="sescontest_label_hot" title="<?php echo $this->translate('Hot');?>"><i class="fa fa-star"></i></span>
      <?php endif;?>
    </div>
  </div>
  <div class="sescontest_sidebar_list_cont">
    <?php if(isset($this->socialSharingActive) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive) || isset($this->followButtonActive)):?>
      <div class="sescontest_sidebar_list_option">
        <a href="javascript:void(0);" class="sescontest_sidebar_option_btn"><i id="testcl" class="fa fa-angle-down sesbasic_text_light"></i></a>
        <div class="sescontest_sidebar_list_option_btns">
         <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataSharing.tpl';?>
        </div>
    	</div>
    <?php endif;?>
    <?php if(isset($this->titleActive)):?>
      <div class="sescontest_sidebar_list_title">
        <a href="<?php echo $contest->getHref();?>"><?php echo $title;?></a><?php if(isset($this->verifiedLabelActive)&& $contest->verified):?><i class="sescontest_label_verified fa fa-check-circle" title='<?php echo $this->translate("Verified");?>'></i><?php endif;?>
      </div>
    <?php endif;?>
    <?php if(isset($this->byActive)):?>
      <div class="sescontest_sidebar_list_stats sesbasic_clearfix sesbasic_text_light">
      	<i class="fa fa-user"></i>
        <span><?php echo $this->translate('by');?>&nbsp;<?php echo $this->htmlLink($owner->getHref(), $owner->getTitle());?></span>
      </div>
    <?php endif;?>
    <?php if(isset($category) && $this->categoryActive):?>
      <div class="sescontest_sidebar_list_stats sesbasic_clearfix sesbasic_text_light">
      	<i class="fa fa-folder-open"></i>
        <span><a href="<?php echo $category->getHref(); ?>"><?php echo $this->translate($category->category_name) ?></a></span>
      </div>
    <?php endif;?>
    <?php if(isset($this->startenddateActive)):?>
      <div class="sescontest_sidebar_list_stats sescontest_sidebar_list_date sesbasic_clearfix sesbasic_text_light">
        <i class="fa fa-calendar"></i>
        <?php echo $this->contestStartEndDates($contest, $dateinfoParams);?>
      </div>
    <?php endif;?>
    <div class="sescontest_sidebar_list_stats sesbasic_text_light sesbasic_clearfix">
      <span> 
        <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataStatics.tpl';?>
      </span>  
    </div>
    <div class="sescontest_sidebar_list_total sesbasic_clearfix">
    	<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataBar.tpl';?>
    </div>
  </div>
</div>
  