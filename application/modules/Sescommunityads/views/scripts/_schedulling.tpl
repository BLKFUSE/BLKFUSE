<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _schedulling.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<div>
<div class="sescmads_format_content sesbasic_bxs">
 <div class="sescommunityads_left_text">
  <div class="sescommunityads_popup_header"><span><?php echo $this->translate('Ad scheduling'); ?></span></div>
    <div class="sescmads_format_inner">
      <div class="sescommunityads_select_content">
        <label><?php echo $this->translate('Start Date'); ?></label>
        <input type="text" class="form-control"/>
      </div>
      <div class="sescommunityads_select_content">
        <label><?php echo $this->translate('End Date'); ?></label>
        <input type="text" class="form-control"/>
      </div>
      <div class="_run_this">
         <input type="checkbox" class="form-control" />
         <label><?php echo $this->translate('Run this ad till it expires'); ?></label>
      </div>
  </div>
 </div>
 <div class="sescommunityads_right_preview">
    <div class="sescommunityads_popup_header"><span><?php echo $this->translate('Ad Preview'); ?></span></div>
    <div class="sescommunityads_ad_preview">
       <div class="_preview_top">
          <div class="_left">
            <img src="application/modules/Sescommunityads/externals/images/logo.png" />
            <span class="_preview_name"></span>
          </div>
          <div class="_right">
            <i class="far fa-thumbs-up"></i>
            <i class="fa fa-ellipsis-h"></i>
          </div>
       </div>
      <div class="_preview_content_img">
         <span class="_preview_des"></span>
         <img src="application/modules/Sescommunityads/externals/images/preview.png" />
      </div>
      <div class="_preview_counts"> 
         <span class="_count"><i class="far fa-thumbs-up"></i><?php echo $this->translate('Like'); ?></span>
         <span class="_count"><i class="far fa-comment"></i><?php echo $this->translate('Comment'); ?></span>
         <span class="_count"><i class="fa fa-share"></i><?php echo $this->translate('Share'); ?></span>
      </div>
    </div>
 </div>
</div>
 <div class="sescommmunity_popup_footer sesbasic_bg sesbasic_clearfix">
   <div class="_left"> <a href="javascript:;" class="sesbasic_button" onclick="sescomm_back_btn(<?php echo $count-1; ?>);"><?php echo $this->translate('Back'); ?></a> </div>
   <div class="_left"> <a href="javascript:;" class="sesbasic_button" onclick="submitAd();"><?php echo $this->translate('Submit'); ?></a> </div>
   <div class="_right"> </div>
 </div>
</div>