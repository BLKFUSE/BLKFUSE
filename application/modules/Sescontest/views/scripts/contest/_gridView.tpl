<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _gridView.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php if(isset($widgetType) && ($widgetType == 'browse-contest' || $widgetType == 'profile-contest')):?>
<?php if(isset($this->griddescriptionActive)):?>
<?php $descriptionLimit = $this->params['grid_description_truncation'];?>
<?php else:?>
<?php $descriptionLimit = 0;?>
<?php endif;?>
<?php if(strlen($contest->getTitle()) > $this->params['grid_title_truncation']):?>
<?php $title = mb_substr($contest->getTitle(),0,$this->params['grid_title_truncation']).'...';?>
<?php else:?>
<?php $title = $contest->getTitle();?>
<?php endif; ?>
<?php endif;?>
<?php $owner = $contest->getOwner();?>

<div class="col-lg-<?php echo $this->gridblock; ?> col-md-6 col-sm-6 col-12">
  <div class="sescontest_grid_item">
    <article>
      <div class="sescontest_grid_thumb" style="height:<?php echo $height ?>px;"> <a href="<?php echo $contest->getHref();?>" class="sescontest_grid_img"> <span style="background-image:url(<?php echo $contest->getPhotoUrl('thumb.profile'); ?>);"></span> </a>
        <div class="sescontest_grid_top">
          <div class="sescontest_grid_top_img"> <?php echo $this->htmlLink($owner->getHref(), $this->itemPhoto($owner, 'thumb.icon', $owner->getTitle()), array('title'=>$owner->getTitle())) ?> </div>
          <div class="sescontest_grid_top_info">
            <div class="sescontest_grid_title"><a href="<?php echo $contest->getHref();?>"><?php echo $title;?></a>
              <?php if(isset($this->verifiedLabelActive)&& $contest->verified):?>
              <i class="sescontest_label_verified fa fa-check-circle" title='<?php echo $this->translate("Verified");?>'></i>
              <?php endif;?>
            </div>
            <div class="sescontest_grid_meta">
              <?php if(isset($this->byActive)):?>
              <?php echo $this->translate('by');?>&nbsp;<?php echo $this->htmlLink($owner->getHref(), $owner->getTitle());?>&nbsp;
              <?php endif;?>
              <?php if(isset($category) && isset($this->categoryActive)):?>
              <?php echo $this->translate('in');?>&nbsp;<a href="<?php echo $category->getHref(); ?>"><?php echo $this->translate($category->category_name) ?></a>
              <?php endif;?>
            </div>
          </div>
          <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_mediaType.tpl';?>
        </div>
        <div class="sescontest_grid_hover_box">
          <?php if(isset($this->startenddateActive)):?>
          <div class="sescontest_grid_item_date sesbasic_clearfix"> <?php echo $this->contestStartEndDates($contest, $dateinfoParams);?> </div>
          <?php endif;?>
          <div class="sescontest_grid_item_stats">
            <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataStatics.tpl';?>
          </div>
          <div class="sescontest_grid_btns">
            <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataSharing.tpl';?>
          </div>
        </div>
        <div class="sescontest_grid_labels sesbasic_animation">
          <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataLabel.tpl';?>
        </div>
      </div>
      <div class="sescontest_grid_item_bottom sesbasic_clearfix">
        <?php if($descriptionLimit):?>
        <p class="sescontest_grid_item_des"><?php echo $this->string()->truncate($this->string()->stripTags($contest->description), $descriptionLimit) ?></p>
        <?php endif;?>
        <div class="sescontest_grid_item_total">
          <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataBar.tpl';?>
        </div>
      </div>
    </article>
  </div>
</div>
