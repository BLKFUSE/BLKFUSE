<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _header.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<div class="_header sesbasic_clearfix">
  <?php if($ad->type == "promote_content_cnt" || $ad->type == "promote_website_cnt"){ 
        $image = Engine_Api::_()->getItem('storage_file',$ad->website_image);
          $imageSrc = "";
          if($image)
            $imageSrc = $image->map();  
  if($ad->type != "promote_website_cnt" || $imageSrc){ 
  ?>
  <div class="_img"> 
    <a href="<?php echo $ad->getHref(array('subject'=>true)); ?>" target="_blank"> 
      <img class="thumb_icon item_photo_user" src="<?php echo !empty($ad) && $ad->resources_type ? $ad->description : ($imageSrc ? $imageSrc : "application/modules/Sescommunityads/externals/images/transprant-bg.png"); ?>" > 
    </a> 
  </div>
  <?php } ?>
  <?php } ?>
  <div class="_cont">
    <div class="_title"> <a href="<?php echo $ad->getHref(array('subject'=>true)); ?>" target="_blank"><?php echo $ad->title; ?></a> </div>
    <?php //if($ad->type == "promote_content_cnt"){ ?>
    <div class="_txt sesbasic_text_light">
      <?php $dot = "";
        if($ad->sponsored){ 
              echo $this->translate('Sponsored'); $dot= "&middot;"; 
        } ?>
        <?php echo $ad->featured && !$ad->sponsored ? $dot.$this->translate('Featured') : ""; ?>
    </div>
    <?php //} ?>
  </div>
  <?php if($ad->user_id != $this->viewer()->getIdentity()){ ?>
  <div class="_optionbtn sesbasic_pulldown_wrapper"> <a href="javascript:void(0);" class="sesbasic_pulldown_toggle"><i class="sesbasic_text_light fa fa-angle-down"></i></a>
    <div class="sesbasic_pulldown_options">
      <ul>
        <li><a href="javascript:;" class="sescomm_hide_ad"><?php echo $this->translate('hide ad'); ?></a></li>
        <!--<li><a target="_blank" href="<?php echo $this->url(array('module'=>'sescommunityads','controller'=>'index','action'=>'why-seeing'),'sescommunityads_whyseeing',false); ?>" class="sescomm_seeing_ad"><?php echo $this->translate('why am i seeing this?'); ?></a></li>-->
        <li>
            <?php $useful = $ad->isUseful(); ?>
            <a href="javascript:;" class="sescomm_useful_ad<?php $useful ? ' active' : ''; ?>" data-rel="<?php echo $ad->getIdentity() ?>" data-selected="<?php echo $this->translate('This ad is useful'); ?>" data-unselected="<?php echo $this->translate('Remove from useful'); ?>"><?php echo !$useful ? $this->translate('This ad is useful') : $this->translate('Remove from useful');?></a>
        </li>
      </ul>
    </div>
  </div>
  <?php } ?>
</div>
