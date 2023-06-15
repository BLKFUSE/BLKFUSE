<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _pinboardView.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php if(isset($widgetType) && ($widgetType == 'browse-contest' || $widgetType == 'profile-contest')):?>
  <?php if(isset($this->pinboarddescriptionActive)):?>
    <?php $descriptionLimit = $this->params['pinboard_description_truncation'];?>
  <?php else:?>
    <?php $descriptionLimit = 0;?>
  <?php endif;?>
  <?php if(strlen($contest->getTitle()) > $this->params['pinboard_title_truncation']):?>
    <?php $title = mb_substr($contest->getTitle(),0,$this->params['pinboard_title_truncation']).'...';?>
  <?php else: ?>
    <?php $title = $contest->getTitle();?>
  <?php endif; ?>
<?php endif;?>
<?php $owner = $contest->getOwner();?>
<div class="sescontest_pinboard_item sesbasic_bxs">
	<div class="sescontest_pinboard_item_inner sesbm sesbasic_clearfix">
  	<header class="sesbasic_clearfix">
      <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_mediaType.tpl';?>
      <p class="_title"><a href="<?php echo $contest->getHref();?>"><?php echo $title;?></a><?php if(isset($this->verifiedLabelActive)&& $contest->verified):?><i class="sescontest_label_verified fa fa-check-circle" title='<?php echo $this->translate("Verified");?>'></i><?php endif;?></p>
      <?php if(isset($category) && isset($this->categoryActive)):?>
        <p class="_category">
          <?php echo $this->translate('in');?> <a href="<?php echo $category->getHref(); ?>"><?php echo $this->translate($category->category_name) ?></a>
        </p> 
      <?php endif;?>
      <div class="_owner">
        <?php if(isset($this->byActive)):?>
          <div class="_img">
            <?php echo $this->htmlLink($owner->getHref(), $this->itemPhoto($owner, 'thumb.icon', $owner->getTitle()), array('title'=>$owner->getTitle())) ?>
          </div>
        <?php endif;?>
        <div class="_cont">
          <?php if(isset($this->byActive)):?>
            <p class="_meta sesbasic_text_light"><?php echo $this->translate('by');?>&nbsp;<?php echo $this->htmlLink($owner->getHref(), $owner->getTitle());?></p>
          <?php endif;?>
          <p class="_stats">
            <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataStatics.tpl';?>
          </p>
        </div>
      </div>
    </header>
		<div class="_thumb sesbasic_clearfix">
    	<div class="_img"><a href="<?php echo $contest->getHref();?>"><img class="" src="<?php echo $contest->getPhotoUrl(); ?>" alt="" /></a></div>
      <div class="sescontest_list_btns">
        <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataSharing.tpl';?> 
      </div>
      <span class="_overlay"></span>
      <a href="<?php echo $contest->getHref();?>" class="_link"  data-url="<?php echo $contest->getType() ?>"></a>
      <div class="sescontest_list_labels sesbasic_animation">
        <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataLabel.tpl';?>
      </div>
      <div class="_total">
        <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataBar.tpl';?>
      </div>
		</div>
    <div class="_info sesbasic_clearfix">
      <?php if(isset($this->startenddateActive)):?>
        <div class="_date">
          <i class="fa fa-calendar sesbasic_text_light"></i>
          <?php echo $this->contestStartEndDates($contest, $dateinfoParams);?>
        </div>
      <?php endif;?>
      <?php if($descriptionLimit):?>
        <p class="_des1"><?php echo $this->string()->truncate($this->string()->stripTags($contest->description), $descriptionLimit) ?></p>
      <?php endif;?>
  	</div>
  </div>
</div>