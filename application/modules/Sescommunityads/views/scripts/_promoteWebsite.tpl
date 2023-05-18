<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _promoteWebsite.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<div class="sescmads_create_step">
  <div class="sescmads_create_step_header"><?php echo $this->translate("SELECT CONTENT"); ?></div>
  <div class="sescmads_create_step_content">
    <div class="sescmads_create_campaign">	
      <div class="sescmads_create_campaign_field sesbasic_clearfix">
        <div class="sescmads_create_campaign_label">
          <label class="required"><?php echo $this->translate("Website URL"); ?></label>
        </div>
        <div class="sescmads_create_campaign_element">
          <input type="text" class="sescommunity_content_text required url" id="website_url" name="website_url" value="<?php echo !empty($this->ad) && $this->ad->type == "promote_website_cnt"  ? $this->ad->description : '' ?>">
          <span class="sescmads_create_hint sesbasic_text_light"><?php echo $this->translate('eg. http://www.example.com'); ?></span>
        </div>
      </div>
      <div class="sescmads_create_campaign_field sesbasic_clearfix">
        <div class="sescmads_create_campaign_label">
          <label class="required"><?php echo $this->translate("Website Title"); ?></label>
        </div>
        <div class="sescmads_create_campaign_element">
          <input type="text" class="sescommunity_content_text required website_title" id="website_title" name="website_title" value="<?php echo !empty($this->ad) && $this->ad->type == "promote_website_cnt"  ? $this->ad->title : '' ?>">
        </div>
      </div>
      
      <div class="sescmads_create_campaign_field sesbasic_clearfix">
        <div class="sescmads_create_campaign_label"><label class="required"><?php echo $this->translate("SESCOMMImage"); ?></label></div>
        <div class="sescmads_create_campaign_element"><input type="file" name="website_image" id="website_image" class="required _website_main_img" onchange="imagePreview(this);"></div>
      </div>
      
    </div>
  </div>
</div>