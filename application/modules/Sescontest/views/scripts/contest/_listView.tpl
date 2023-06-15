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
  <?php if(strlen($contest->getTitle()) > $this->params['list_title_truncation']):?>
    <?php $title = mb_substr($contest->getTitle(),0,$this->params['list_title_truncation']).'...';?>
  <?php else: ?>
    <?php $title = $contest->getTitle();?>
  <?php endif; ?>
  <?php $owner = $contest->getOwner();?>
<div class="sescontest_list_item">
  <article class="sesbasic_clearfix">
    <div class="sescontest_list_item_thumb sescontest_list_thumb" style="height:<?php echo $height ?>px;width:<?php echo $width ?>px;">
      <a href="<?php echo $contest->getHref();?>" class="sescontest_list_item_img"><span style="background-image:url(<?php echo $contest->getPhotoUrl('thumb.profile'); ?>);"></span></a>
      <div class="sescontest_list_labels sesbasic_animation">
        <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataLabel.tpl';?>
      </div>
      <div class="sescontest_list_thumb_over">
        <a href="<?php echo $contest->getHref();?>"></a>
        <div class="sescontest_list_btns">
          <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataSharing.tpl';?>
        </div>
      </div>
      <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_mediaType.tpl';?>
  	</div>
    <div class="sescontest_list_item_cont">
      <?php if(isset($this->titleActive)):?>
        <div class="sescontest_list_item_title">
          <a href="<?php echo $contest->getHref();?>"><?php echo $title;?></a> <?php if(isset($this->verifiedLabelActive)&& $contest->verified):?><i class="sescontest_label_verified fa fa-check-circle" title='<?php echo $this->translate("Verified");?>'></i><?php endif;?>
        </div>
      <?php endif;?>
      <div class="sescontest_list_item_owner sesbasic_clearfix sesbasic_text_light">
        <?php if(isset($this->byActive)):?>
          <span><?php echo $this->translate('by');?>&nbsp;<?php echo $this->htmlLink($owner->getHref(), $owner->getTitle());?></span>
        <?php endif;?>
        <?php if(isset($category) && isset($this->categoryActive)):?>
          <span><?php echo $this->translate('in');?>&nbsp;<a href="<?php echo $category->getHref(); ?>"><?php echo $this->translate($category->category_name) ?></a></span>
        <?php endif;?>
      </div>
      <div class="sescontest_list_item_sep"></div>
      <?php if(isset($this->startenddateActive)):?>
      <div class="sescontest_list_item_date">
        <i class="fa fa-calendar"></i>
        <?php echo $this->contestStartEndDates($contest, $dateinfoParams);?>
      </div>
      <?php endif;?>
      <div class="sescontest_list_item_stats">
        <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataStatics.tpl';?>
      </div>
      <?php if(isset($this->listdescriptionActive)):?>
        <div class="sescontest_list_item_des">
          <p><?php echo $this->string()->truncate($this->string()->stripTags($contest->description), $this->params['list_description_truncation']) ?></p>
        </div>
      <?php endif;?>
      <div class="sescontest_list_item_total">
        <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataBar.tpl';?>
      </div>
    </div>
  </article>
</div>