<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _videoPreview.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<div class="sescmads_create_preview sescmads_create_preview_video sescommunityads_ad_preview" style="display:none;">
  <div class="preview_header sesbasic_clearfix">
    <div class="_img sescommunity_preview_sub_image" style="display:none;"> 
      <a href="javascript:;">
      
        <?php
            $imageSrc = "";
            if(!empty($this->ad) && $this->ad->type == "promote_website_cnt"){
                $image = Engine_Api::_()->getItem('storage_file',$this->ad->website_image);
                if($image)
                  $imageSrc = $image->map();  
                
              } ?>
        <img src="<?php echo !empty($this->ad) && $this->ad->resources_type ? $this->ad->description : ($imageSrc ? $imageSrc : "application/modules/Sescommunityads/externals/images/transprant-bg.png"); ?>" class="thumb_icon _preview_content_img">
      </a> 
    </div>
    <div class="_cont">
      <div class="preview_title"><a href="javascript:;" class="sescommunityads_carousel_title" data-original=""><?php echo !empty($this->ad) && ($this->ad->resources_type || $this->ad->type == "promote_website_cnt") ? $this->ad->title : ""; ?></a></div>
      <?php if($this->package->sponsored || $this->package->featured){ ?>
      <div class="_txt sesbasic_text_light sescommunity_sponsored" style="display:none;"><?php echo $this->package->sponsored ? $this->translate('Sponsored') : ($this->package->featured ? $this->translate('Featured') : ""); ?></div>
      <?php } ?>
    </div>
    <div class="_optionbtn"><i class="sesbasic_text_light fa fa-angle-down"></i></div>
  </div>
  <?php if(empty($this->ad) || !engine_count($this->attachment)){ ?>
  <div class="sescmads_create_preview_item">
    <div class="_img">
      <video width="400" class="sescomm_img" poster="application/modules/Sescommunityads/externals/images/transprant-bg.png" controls style="display:none" data-original=""> <?php echo $this->translate("Your browser does not support HTML5 video."); ?> </video>
      <span class="_badge sescommunity_badge" style="display:none;"></span> </div>
    <div class="_cont">
      <div class="_button _preview_call_to sescomm_call_to_action" style="display:none;"><a href="javascript:;" class="sesbasic_button"></a></div>
      <div class="_details">
        <div class="_url sesbasic_text_light _preview_url"></div>
        <div class="_headline _preview_title" data-original=""></div>
        <div class="_des _preview_des" data-original=""></div>
      </div>
      <a href="javascript:;" class="_link"></a> </div>
  </div>
  <?php }else{
  $counter = 1;
      foreach($this->attachment as $attachment){ 
      $image = Engine_Api::_()->getItem('storage_file',$attachment->file_id);
    $imageSrc = "";
            if($image)
              $imageSrc = $image->map();

      $videosrc = 'application/modules/Sescommunityads/externals/images/transprant-bg.png';
      $video = Engine_Api::_()->getItem('storage_file',$ad->video_src);

      if($video)
        $videosrc = $video->map();

      ?>
  <div class="sescmads_create_preview_item">
    <div class="_img">
      <video width="400" class="sescomm_img" poster="data:image/gif,AAAA" src="<?php  echo $imageSrc; ?>" style="background-image: url(<?php echo $videosrc; ?>);" controls data-original=""> Your browser does not support HTML5 video. </video>
      <span class="_badge sescommunity_badge" style="display:none;"></span> </div>
    <div class="_cont">
      <div class="_button _preview_call_to sescomm_call_to_action" style="display:<?php echo $this->ad->calltoaction ? 'block' : 'none;' ?>;"><a href="javascript:;" class="sesbasic_button"><?php echo $this->translate(ucwords(str_replace('_',' ',$this->ad->calltoaction ? $this->ad->calltoaction : ""))); ?></a></div>
      <div class="_details">
        <div class="_url sesbasic_text_light _preview_url"><?php echo $this->ad->type == "promote_website_cnt" ? $this->ad->description : ""; ?></div>
        <div class="_headline _preview_title" data-original=""><?php echo $attachment->title; ?></div>
        <div class="_des _preview_des" data-original=""><?php echo $attachment->description; ?></div>
      </div>
      <a href="javascript:;" class="_link"></a> </div>
  </div>
  <?php } ?>
  <?php } ?>
</div>
