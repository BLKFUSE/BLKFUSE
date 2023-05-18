<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _boostPost.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php if(empty($this->is_ajax)){ ?>
 <div class="sescmads_create_step">
  <div class="sescmads_create_step_header"><?php echo $this->translate('Select a Post to Boost'); ?></div>
  <div class="sescmads_create_step_content">
    <ul class="sescmads_select_post">
<?php } ?>
      <div id="sescmads_select_post_overlay" class="sesbasic_loading_cont_overlay" style="display:block;"></div>    
       <?php if(empty($this->is_ajax)){ ?>
    </ul>
    <div class="sesbasic_load_btn" style="display:none;" id="sescommunityads_boost_feed_viewmore" style="display: none;">
      <a href="javascript:void(0);" id="sescommunityads_boost_feed_viewmore_link" class="sesbasic_animation sesbasic_link_btn"><i class="fa fa-sync"></i><span><?php echo $this->translate('View More');?></span></a>
   </div>
    <div class="sesbasic_load_btn" id="sescommunityads_boost_feed_loading" style="display: none;">
      <span class="sesbasic_link_btn"><i class="far fa-circle-notch fa-spin"></i></span>
    </div>
  </div>
  <div class="sescmads_create_step_footer sesbasic_clearfix">
    <div class="floatL"><a href="javascript:;" class="sesbasic_button sesbasic_animation" onclick="sescomm_back_btn(2);"><?php echo $this->translate("Back"); ?></a> </div>
    <div class="floatL"><a href="javascript:;" class="sesbasic_animation _btnh" onclick="sesCommBoostPost(this);"><?php echo $this->translate("Next"); ?></a> </div>
  </div>
  </div>
  
 <?php  } ?>
 <script type="application/javascript">
  getBoostPostData("<?php echo !empty($this->ad) && $this->ad->type == "boost_post_cnt" ? $this->ad->resources_id : (!empty($this->action_id) ? $this->action_id : ''); ?>");
 </script>
 <style>
 .sesact_boost_btn{display:none;}
 </style>
