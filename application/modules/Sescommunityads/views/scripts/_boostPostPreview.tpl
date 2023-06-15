<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _boostPostPreview.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
 <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/scripts/core.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/styles/styles.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesadvancedcomment/externals/styles/styles.css'); ?>
<div class="sescommunityads_ad_preview sescmads_ad_preview_boost_post sescmads_create_preview" style="display:none;">
  <div class="_preview_top">
    <div class="_left"> 
      <!--<div class="campaign_logo"> <img src="application/modules/Sescommunityads/externals/images/logo.png" /> </div>--> 
      <span class="_preview_name sescommunityads_carousel_title" data-original="Aheadsofttech"></span> </div>
    <div class="_right"><i class="fa fa-ellipsis-h"></i> </div>
  </div>
  <div class="sescommunity_preview_content">
    <div class="sescommunity_preview_cnt">
      <div class="_preview_content_img"> <img src="application/modules/Sescommunityads/externals/images/preview.png" data-original="application/modules/Sescommunityads/externals/images/preview.png" />
        <video width="400" id="sescomm_video_div" controls style="display:none" data-original="http://techslides.com/demos/sample-videos/small.mp4"> Your browser does not support HTML5 video. </video>
        <span class="_preview_title" data-original="Lorem Ipsum is simply dummy text of the printing and typesetting industry.">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</span> <span class="_preview_des" data-original="Lorem Ipsum is simply dummy text of the printing and typesetting industry.">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</span> </div>
      <div class="_preview_call_to" style="display:none;">
        <button class="sesbasic_button"></button>
      </div>
    </div>
  </div>
  <div style="clear: both;"></div>
</div>
