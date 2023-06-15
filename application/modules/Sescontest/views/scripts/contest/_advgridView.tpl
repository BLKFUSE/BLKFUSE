<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _advgridView.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php if(isset($widgetType) && ($widgetType == 'browse-contest' || $widgetType == 'profile-contest')):?>
<?php if(strlen($contest->getTitle()) > $this->params['advgrid_title_truncation']):?>
<?php $title = mb_substr($contest->getTitle(),0,$this->params['advgrid_title_truncation']).'...';?>
<?php else: ?>
<?php $title = $contest->getTitle();?>
<?php endif; ?>
<?php endif;?>
<?php $owner = $contest->getOwner();?>

<div class="col-lg-<?php echo $this->gridblock; ?> col-md-6 col-sm-6 col-12">
  <div class="sescontest_advgrid_item">
    <article>
      <div class="sescontest_advgrid_item_header"> <a href="<?php echo $contest->getHref();?>"><?php echo $title;?></a>
        <?php if(isset($this->verifiedLabelActive)&& $contest->verified):?>
        <i class="sescontest_label_verified fa fa-check-circle" title='<?php echo $this->translate("Verified");?>'></i>
        <?php endif;?>
      </div>
      <div class="sescontest_advgrid_item_thumb sescontest_list_thumb" style="height:<?php echo $height ?>px;"> <a href="<?php echo $contest->getHref();?>" class="sescontest_advgrid_img"> <span class="sesbasic_animation" style="background-image:url(<?php echo $contest->getPhotoUrl('thumb.profile'); ?>);"></span> </a>
        <div class="sescontest_advgrid_item_owner"> <span class="sescontest_advgrid_item_owner_img"> <?php echo $this->htmlLink($owner->getHref(), $this->itemPhoto($owner, 'thumb.icon', $owner->getTitle()), array('title'=>$owner->getTitle())) ?> </span>
          <?php if(isset($this->socialSharingActive) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive) || isset($this->followButtonActive)):?>
          <span class="useroption"> <a href="javascript:void(0);" class="fa fa-angle-down"></a>
          <div class="sescontest_advgrid_item_btns">
            <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataSharing.tpl';?>
          </div>
          </span>
          <?php endif;?>
          <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_mediaType.tpl';?>
          <span class="itemcont">
          <?php if(isset($this->byActive)):?>
          <span class="ownername"> <?php echo $this->translate('by');?>&nbsp;<?php echo $this->htmlLink($owner->getHref(), $owner->getTitle());?> </span>
          <?php endif;?>
          <?php if(isset($category) && isset($this->categoryActive)):?>
          <span><?php echo $this->translate('in');?>&nbsp;<a href="<?php echo $category->getHref(); ?>"><?php echo $this->translate($category->category_name) ?></a></span>
          <?php endif;?>
          </span> </div>
        <div class="sescontest_list_labels sesbasic_animation">
          <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataLabel.tpl';?>
        </div>
        <a href="<?php echo $contest->getHref();?>" class="_viewbtn sesbasic_link_btn sesbasic_animation"><?php echo $this->translate("View Contest");?></a> </div>
      <div class="sescontest_advgrid_item_footer">
        <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataBar.tpl';?>
      </div>
    </article>
  </div>
</div>
