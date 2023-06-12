<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _promoteContent.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

 ?>

<?php
//get attachments
  $table = Engine_Api::_()->getDbTable('attachments','sescommunityads');
  $select = $table->select()->where('sescommunityad_id =?',$ad->getIdentity());
  $attachment = $table->fetchAll($select);
?>
<?php if($ad->subtype == "image"){ ?>

<li class="sescmads_ads_listing_item" rel="<?php echo $ad->getIdentity(); ?>">
  <?php include('application/modules/Sescommunityads/views/scripts/widget-data/_hiddenData.php'); ?>
  <div class="sescmads_display_ad sescmads_ads_item_img">
    <?php include('application/modules/Sescommunityads/views/scripts/widget-data/_header.php'); ?>
    <?php if(engine_count($attachment)){
          $attach = $attachment[0];
          $image = Engine_Api::_()->getItem('storage_file',$attach->file_id);
            $imageSrc = "application/modules/Sescommunityads/externals/images/transprant-bg.png";
            if($image)
              $imageSrc = $image->map();
      ?>
    <div class="sescmads_display_ad_item">
      <div class="_img sescomm_img"> <a href="<?php echo $attach->getHref(); ?>" target="_blank"> <img src="<?php echo $imageSrc; ?>" /> </a> </div>
      <div class="_cont">
        <div class="_details">
          <?php if($ad->type == "promote_website_cnt"){
                   $description = $ad->description;
                   $description = str_replace('http://','',$description);
                   $description = str_replace('https://','',$description);
                   $description = explode('/',$description);
          ?>
          <div class="_url sesbasic_text_light"><?php echo $description[0]; ?></div>
          <?php } ?>
          <div class="_headline _preview_title"><?php echo $attach->title; ?></div>
          <?php if($attach->description){ ?>
          <div class="_des _preview_des"><?php echo $attach->description; ?></div>
          <?php } ?>
        </div>
        <?php if($ad->calltoaction){ ?>
       	 <div class="_button sescomm_call_to_action" style=""> <a href="<?php echo $attach->getHref(); ?>" class="sesbasic_button"><?php echo $this->translate(ucwords(str_replace('_',' ',$ad->calltoaction ? $ad->calltoaction : ""))); ?></a> </div>
        <?php } ?>
        <a href="<?php echo $attach->getHref(); ?>" target="_blank" class="_link"></a> </div>
    </div>
    <?php } ?>
  </div>
</li>
<?php }else if($ad->subtype == "banner"){ ?>
<?php $banner = Engine_Api::_()->getItem('sescomadbanr_banner', $ad->banner_id); ?>
<li class="sescmads_bannerad_item" rel="<?php echo $ad->getIdentity(); ?>">
  <?php include('application/modules/Sescommunityads/views/scripts/widget-data/_hiddenData.php'); ?>
  <div class="sescmads_bannerad_display">
    <?php //if($ad->type == "promote_content_cnt"){ ?>
    <div class="_label">
      <?php $dot = "";
        if($ad->sponsored){
              echo $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sescomadbanr.sponsored', 'Advertisement')); $dot= "&middot;";
        } ?>
        <?php echo $ad->featured && !$ad->sponsored ? $dot.$this->translate('Featured') : ""; ?>
    </div>
    <?php //} ?>
    <?php if($ad->user_id != $this->viewer()->getIdentity()){ ?>
      <div class="_optionbtn sesbasic_pulldown_wrapper"><a href="javascript:void(0);" class="sesbasic_pulldown_toggle">AD <i class="fa fa-angle-down"></i></a>
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
    <div class="sescmads_bannerad_holder" style="width:<?php echo $banner->width?>px;height: <?php echo $banner->height ?>px;">
      <?php if($ad->banner_type == 1) { ?>
        <?php if(engine_count($attachment)) {
          $attach = $attachment[0];
          $image = Engine_Api::_()->getItem('storage_file',$attach->file_id);
            $imageSrc = "application/modules/Sescommunityads/externals/images/transprant-bg.png";
            if($image)
              $imageSrc = $image->map();
        ?>
          <div class="_img"><a href="<?php echo $attach->getHref(); ?>" target="_blank"> <img src="<?php echo $imageSrc; ?>" /> </a>
          </div>
        <?php } ?>
      <?php } else { ?>
        <?php if(!empty($ad->html_code)) { ?>
          <div class="_html">
            <?php echo $ad->html_code; ?>
          </div>
        <?php } ?>
      <?php } ?>
    </div>
  </div>
</li>
<?php }else if($ad->subtype == "video"){ ?>
<li class="sescmads_ads_listing_item" rel="<?php echo $ad->getIdentity(); ?>">
  <?php include('application/modules/Sescommunityads/views/scripts/widget-data/_hiddenData.php'); ?>
  <div class="sescmads_display_ad sescmads_ads_item_img">
    <?php include('application/modules/Sescommunityads/views/scripts/widget-data/_header.php'); ?>
    <?php if(engine_count($attachment)){
          $attach = $attachment[0];
          $image = Engine_Api::_()->getItem('storage_file',$attach->file_id);
            $imageSrc = "application/modules/Sescommunityads/externals/images/transprant-bg.png";
            if($image)
              $imageSrc = $image->map();

        $videosrc = 'application/modules/Sescommunityads/externals/images/transprant-bg.png';
        $video = Engine_Api::_()->getItem('storage_file',$ad->video_src);
        if($video)
            $videosrc = $video->map();

      ?>
    <div class="sescmads_display_ad_item">
      <div class="_img sescomm_img _video">
        <video width="400" class="sescomm_img" controlslist="nodownload" poster="data:image/gif,AAAA" style="background-image: url(<?php echo $videosrc; ?>);" controls>
          <source src="<?php echo $imageSrc; ?>" type="video/mp4">
          Your browser does not support HTML5 video.</video>
      </div>
      <div class="_cont">
        <div class="_details">
          <?php if($ad->type == "promote_website_cnt"){
                   $description = $ad->description;
                   $description = str_replace('http://','',$description);
                   $description = str_replace('https://','',$description);
                   $description = explode('/',$description);
          ?>
            <div class="_url sesbasic_text_light"><?php echo $description[0]; ?></div>
          <?php } ?>
          <div class="_headline _preview_title"><?php echo $attach->title; ?></div>
          <?php if($attach->description){ ?>
          <div class="_des _preview_des"><?php echo $attach->description; ?></div>
          <?php } ?>
        </div>
        <?php if($ad->calltoaction){ ?>
        <div class="_button sescomm_call_to_action" style=""> <a href="<?php echo $attach->getHref(); ?>" class="sesbasic_button"><?php echo $this->translate(ucwords(str_replace('_',' ',$ad->calltoaction ? $ad->calltoaction : ""))); ?></a> </div>
        <?php  } ?>
        <a href="<?php echo $attach->getHref(); ?>" target="_blank" class="_link"></a> </div>
    </div>
    <?php } ?>
  </div>
</li>
<?php }else{ ?>
<li class="sescmads_ads_listing_item" rel="<?php echo $ad->getIdentity(); ?>">
  <?php include('application/modules/Sescommunityads/views/scripts/widget-data/_hiddenData.php'); ?>
  <div class="sescmads_display_ad sescmads_ads_item_img">
    <?php include('application/modules/Sescommunityads/views/scripts/widget-data/_header.php'); ?>
    <div class="sescmads_display_ad_carousel">
      <?php if(engine_count($attachment)){
      foreach($attachment as $attach){
          $image = Engine_Api::_()->getItem('storage_file',$attach->file_id);
            $imageSrc = "application/modules/Sescommunityads/externals/images/transprant-bg.png";
            if($image)
              $imageSrc = $image->map();
      ?>
      <div class="sescmads_display_ad_item">
        <div class="_img sescomm_img"> <a href="<?php echo $attach->getHref(); ?>" target="_blank"> <img src="<?php echo $imageSrc; ?>" /> </a>
          <?php if($ad->call_to_action_overlay){ ?>
          <span class="_badge sescommunity_badge sescomm_call_to_action_overlay" style=""><?php echo $this->translate(ucwords(str_replace('_',' ',$ad->call_to_action_overlay ? $ad->call_to_action_overlay : ""))); ?></span>
          <?php } ?>
        </div>
        <div class="_cont">
          <div class="_details">
            <div class="_headline _preview_title"><?php echo $attach->title; ?></div>
            <?php if($attach->description){ ?>
            <div class="_des _preview_des"><?php echo $attach->description; ?></div>
            <?php } ?>
          </div>
          <?php if($ad->calltoaction){ ?>
          <div class="_button sescomm_call_to_action" style=""><a href="<?php echo $attach->getHref(); ?>" class="sesbasic_button"><?php echo $this->translate(ucwords(str_replace('_',' ',$ad->calltoaction ? $ad->calltoaction : ""))); ?></a></div>
          <?php  } ?>
          <a href="<?php echo $attach->getHref(); ?>" target="_blank" class="_link"></a> </div>
      </div>
      <?php }
      } ?>
      <?php if($ad->more_image){
         $image = Engine_Api::_()->getItem('storage_file',$ad->more_image);
            $imageSrc = "application/modules/Sescommunityads/externals/images/transprant-bg.png";
            if($image)
              $imageSrc = $image->map();
       ?>
      <div class="sescmads_display_ad_item _more sescmads_display_ad_item_more">
        <div class="_img"> <a href="<?php echo $ad->getHref(); ?>" target="_blank"><img src="<?php echo $imageSrc; ?>"></a> </div>
        <div class="_cont">
          <div class="_details">
            <div class="_headline"><?php echo $this->translate('See more at'); ?></div>
            <div class="_des"><?php echo $ad->see_more_display_link; ?></div>
          </div>
          <a href="<?php echo $ad->getHref(); ?>" target="_blank" class="_link"></a> </div>
      </div>
      <?php } ?>
    </div>
  </div>
</li>
<?php } ?>
