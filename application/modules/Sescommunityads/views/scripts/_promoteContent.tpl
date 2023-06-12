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
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/scripts/core.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/datepicker/jquery.timepicker.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/datepicker/bootstrap-datepicker.css'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/datepicker/jquery.timepicker.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/datepicker/bootstrap-datepicker.js'); ?>
<style>.displayF{display:block !important;}</style>
<script type="application/javascript">
var sescommunityIsEditForm = "<?php echo $this->editName; ?>";
</script>
  <?php if($package->carousel){ ?>
  <form name="carousel_form" id="carousel_form" method="post">
  <div class="sescmads_create_step carousel_div hideall" style="display:none">
    <div class="sescmads_create_step_header"><span><?php echo $this->translate('Carousel'); ?></span></div>
    <div class="sescmads_create_fields">
      <div class="sescommunityads_post_descbox" style="display:none;">
        <label><?php echo $this->translate('SESCOMMText'); ?></label>
        <textarea class="form-control required sescommunityads_carousel_title_text" name="main_heading_title"></textarea>
      </div>
      <div class="sescmads_create_cont_nav">
        <ul class="sesbasic_clearfix" id="sescomm_ad_car_li">
          <?php if(empty($this->ad) || $this->ad->subtype != "carousel"){ ?>
          <li class="sescomm_active"><a href="javascript:;" data-rel="1">1</a> </li>
          <li> <a href="javascript:;" data-rel="2">2</a> </li>
          <?php }else{ ?>
            <?php 
            $counter = 1;
            foreach($this->attachment as $attachment){ ?>
              <li class="<?php echo $counter == 1 ? 'sescomm_active' : ''; ?>">
                <a href="javascript:;" data-rel="<?php echo $counter; ?>"><?php echo $counter; ?></a>
              </li>
            <?php $counter++;
              } ?>
          <?php } ?>
        </ul>
        <div class="sescmads_create_cont_nav_add">
          <button type="button" id="add-media">+</button>
        </div>
      </div>
      <div class="sescommunityads_choose">        
         <?php if(empty($this->ad) || engine_count($this->attachment)){ ?>
         <div class="sescmads_create_carousel_item sescomm_carousel_img">
            <div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('SESCOMMImage'); ?></label></div>
              <div class="sescmads_create_field_element"><input type="file" name="image[]" class="required" onChange="imagePreview(this);" /></div>
            </div>
            <div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('Heading'); ?></label></div>
              <div class="sescmads_create_field_element"> <input type="text" name="heading1[]" class="sescommunity_content_text required _preview_title"></div>
            </div>
            <div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label><?php echo $this->translate('Description'); ?></label></div>
              <div class="sescmads_create_field_element"><input type="text" name="description1[]" class="sescommunity_content_text _preview_des"></div>
            </div>
            <div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('Destination URL'); ?></label></div>
              <div class="sescmads_create_field_element"><input type="text" name="destinationurl1[]" class="sescommunity_content_text required url"><span class="sescmads_create_hint sesbasic_text_light"><?php echo $this->translate('eg. http://www.example.com'); ?></span></div>
            </div>
          </div>
         <div class="sescmads_create_carousel_item sescomm_carousel_img" style="display:none;">
            <div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('SESCOMMImage'); ?></label></div>
              <div class="sescmads_create_field_element"><input type="file" name="image[]" class="required" onChange="imagePreview(this);" /></div>
            </div>
            <div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('Heading'); ?></label></div>
              <div class="sescmads_create_field_element"> <input type="text" name="heading1[]" class="sescommunity_content_text required _preview_title"></div>
            </div>
            <div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label><?php echo $this->translate('Description'); ?></label></div>
              <div class="sescmads_create_field_element"><input type="text" name="description1[]" class="sescommunity_content_text _preview_des"></div>
            </div>
            <div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('Destination URL'); ?></label></div>
              <div class="sescmads_create_field_element"><input type="text" name="destinationurl1[]" class="sescommunity_content_text required url"><span class="sescmads_create_hint sesbasic_text_light"><?php echo $this->translate('eg. http://www.example.com'); ?></span></div>
            </div>
          </div>    
          <?php }else{ ?>
          <?php 
            $counter = 1;
            foreach($this->attachment as $attachment){ ?>
            <div class="sescmads_create_carousel_item sescomm_carousel_img" style="display:<?php echo $counter == 1 ? 'block' : 'none'; ?> ;">
              <div class="sescmads_create_field_row sesbasic_clearfix">
                <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('SESCOMMImage'); ?></label></div>
                <div class="sescmads_create_field_element">
                  <input type="file" name="image[]" class="required fromedit" onChange="imagePreview(this);" />
                </div>
              </div>
              <div class="sescmads_create_field_row sesbasic_clearfix">
                <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('Heading'); ?></label></div>
                <div class="sescmads_create_field_element">
                  <input type="hidden" value="<?php echo $attachment->getIdentity(); ?>" name="attachmentId[]">
                  <input type="hidden" value="<?php echo $attachment->file_id; ?>" name="attachmentFileId[]">
                  <input type="text" value="<?php echo $attachment->title; ?>" name="heading1[]" class="sescommunity_content_text required _preview_title">                
               </div>
              </div>
              <div class="sescmads_create_field_row sesbasic_clearfix">
                <div class="sescmads_create_field_label"><label><?php echo $this->translate('Description'); ?></label></div>
                <div class="sescmads_create_field_element">
                  <input type="text" name="description1[]" value="<?php echo $attachment->description; ?>" class="sescommunity_content_text _preview_des">                </div>
              </div>
              <div class="sescmads_create_field_row sesbasic_clearfix">
                <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('Destination URL'); ?></label></div>
                <div class="sescmads_create_field_element">
                  <input type="text" name="destinationurl1[]" value="<?php echo $attachment->destination_url; ?>" class="sescommunity_content_text required url"><span class="sescmads_create_hint sesbasic_text_light"><?php echo $this->translate('eg. http://www.example.com'); ?></span></div>
              </div>
            </div>
            <?php 
              $counter++;
              } ?>
          <?php } ?>   
      </div>
      <?php if($this->callToAction){ ?>
        <div class="sescmads_create_field_row sesbasic_clearfix">
          <div class="sescmads_create_field_label"><label><?php echo $this->translate('Call to Action'); ?></label></div>
            <div class="sescmads_create_field_element">
              <select class="form-control sesbasic_button sescomm_call_to_action" name="call_to_action"><?php echo $this->callToAction; ?></select>
              <div id="sescomm_calltoaction_url" style="display:none;">
                <input type="text" name="calltoaction_url" class="url">
              </div>
            </div>
        </div>
      <?php } ?>
      <div class="sescmads_create_field_row sesbasic_clearfix">
        <div class="sescmads_create_field_element">
          <input type="checkbox" id="add_card" name="add_card" <?php echo !empty($this->ad) && $this->ad->more_image ? 'checked' : ''; ?> class="add_comm_card" />
          <label for="add_card"><?php echo $this->translate("Add a card at the end."); ?></label>
        </div>
      </div>
      <?php $displayAddCard =  !empty($this->ad) && $this->ad->more_image ? 'block' : 'none'; ?>
       <div class="sescmads_create_field_row sesbasic_clearfix checkbox_val" style="display:<?php echo $displayAddCard; ?>;">
        <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('SESCOMMImage'); ?></label></div>
        <div class="sescmads_create_field_element"><input type="file" name="more_image" class="required more_image <?php echo !empty($this->ad) && $this->ad->more_image ? 'fromedit' : ''; ?>" onChange="imagePreview(this);" /></div>
      </div>
      <div class="sescmads_create_field_row sesbasic_clearfix checkbox_val" style="display:<?php echo $displayAddCard; ?>;">
        <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('See More URL'); ?></label></div>
        <div class="sescmads_create_field_element"><input type="text" value="<?php echo !empty($this->ad) && $this->ad->more_image ? $this->ad->see_more_url : ''; ?>" class="sescommunity_content_text required url" name="see_more_url" id="see_more_url"/><span class="sescmads_create_hint sesbasic_text_light"><?php echo $this->translate('eg. http://www.example.com'); ?></span></div>
      </div>
      <div class="sescmads_create_field_row sesbasic_clearfix checkbox_val"  style="display:<?php echo $displayAddCard; ?>;">
        <div class="sescmads_create_field_label"><label class="required"><?php  echo $this->translate('See More Display Link'); ?></label></div>
        <div class="sescmads_create_field_element"><input value="<?php echo !empty($this->ad) && $this->ad->more_image ? $this->ad->see_more_display_link : ''; ?>" type="text" name="see_more_display_link" id="see_more_display_link" class="sescommunity_content_text required more_text" /></div>
      </div>
      <?php $overlay = array(''=>'No Overlay','cash_on_delivery'=>'Cash on Delivery','home_delivery'=>'Home Delivery','bank_transfer_available'=>'Bank Transfer Available'); ?>
      <div class="sescmads_create_field_row sesbasic_clearfix">
        <div class="sescmads_create_field_label"><label><?php echo $this->translate('Overlay'); ?></label></div>
        <div class="sescmads_create_field_element">
         <select class="form-control sesbasic_button sescomm_call_to_action_overlay" name="call_to_action_overlay">
            <?php 
              foreach($overlay as $key=>$val){ 
                $select = "";
                if(!empty($this->ad) && $key == $this->ad->call_to_action_overlay)
                  $select = "selected";
              ?>
                <option value="<?php echo $key; ?>" <?php echo $select; ?>><?php echo $this->translate($val); ?></option>
              <?php } ?>
          </select>
        </div>
      </div>
    </div>
  </div>
  </form>
  <?php } ?>
   <form name="image_form" id="image_form" method="post">
    <div class="image_div hideall sescmads_create_step" style="display:none">
     <div class="sescmads_create_step_header"><?php echo $this->translate('Single Image'); ?></div>
      <div class="sescmads_create_fields">
            <div class="sescommunityads_post_descbox" style="display:none;">
              <label><?php echo $this->translate('SESCOMMText'); ?></label>
              <textarea class="form-control required sescommunityads_carousel_title_text sescommunity_content_text" name="main_heading_title"></textarea>
            </div>
            <div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('SESCOMMImage'); ?></label></div>
              <div class="sescmads_create_field_element"><input type="file" name="image" class="required _image_image <?php echo !empty($this->ad) ? 'fromedit' : '' ?> ?>" onChange="imagePreview(this);" /></div>
            </div>          
            <?php if(!empty($this->ad) && engine_count($this->attachment)){ ?>
            <input type="hidden" value="<?php echo $this->attachment[0]->getIdentity(); ?>" name="attachmentId[]">
            <input type="hidden" value="<?php echo $this->attachment[0]->file_id; ?>" name="attachmentFileId[]">
            <?php } ?>
            <div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label class="required"> <?php echo $this->translate('Heading'); ?> </label></div>
              <div class="sescmads_create_field_element"><input type="text" value="<?php echo !empty($this->attachment[0]->title) ? $this->attachment[0]->title : ''; ?>" name="heading1[]" class="sescommunity_content_text required _preview_title" /></div>
            </div>
             <div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label><?php echo $this->translate('Description'); ?></label></div>
              <div class="sescmads_create_field_element"><input type="text" value="<?php echo !empty($this->attachment[0]->description) ? $this->attachment[0]->description : ''; ?>" name="description1[]" class="sescommunity_content_text _preview_des" /></div>
            </div>
            <div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate("Destination URL"); ?></label></div>
              <div class="sescmads_create_field_element"><input type="text" value="<?php echo !empty($this->attachment[0]->destination_url) ? $this->attachment[0]->destination_url : ''; ?>" name="destinationurl1[]" class="sescommunity_content_text required url" /><span class="sescmads_create_hint sesbasic_text_light"><?php echo $this->translate('eg. http://www.example.com'); ?></span></div>
            </div>
            
            <?php if($this->callToAction){ ?>
            <div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label><?php echo $this->translate('Call to Action'); ?></label></div>
              <div class="sescmads_create_field_element">
                <select class="form-control sesbasic_button sescomm_call_to_action" name="call_to_action">
                  <?php echo $this->callToAction; ?>
                </select>
              </div>
               <div id="sescomm_calltoaction_url" style="display:none;">
                <input type="text" name="calltoaction_url" class="url">
              </div>
            </div>
            <?php } ?>
      </div>
    </div>
  </form>
  
   <?php // Banner Work ?>
   <?php if(isset($package->banner) && !empty($package->banner)){ ?>
   <form name="banner_form" id="banner_form" method="post">
    <div class="banner_div hideall sescmads_create_step" style="display:none">
     <div class="sescmads_create_step_header"><?php echo $this->translate('Banner Image'); ?></div>
      <div class="sescmads_create_fields">
            <div class="sescommunityads_post_descbox" style="display:none;">
              <label><?php echo $this->translate('SESCOMMText'); ?></label>
              <textarea class="form-control required sescommunityads_carousel_title_text sescommunity_content_text" name="main_heading_title"></textarea>
            </div>
						<div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('Ad Title'); ?> </label></div>
              <div class="sescmads_create_field_element"><input type="text" value="<?php echo !empty($this->ad->title) ? $this->ad->title : ''; ?>" name="banner_title" class="sescommunity_content_text required _preview_title" /></div>
            </div>
						<div class="sescmads_create_field_row sesbasic_clearfix">
							<div class="sescmads_create_field_label">
								<label><?php echo $this->translate("Banner Type"); ?></label>
							</div>
							<div class="sescmads_create_field_element">
                <ul class="_multiplefields">
  								<li>
  									<input onclick="showBannerAds(this.value);" type="radio" name="banner_type" value="1" id="banner_ad" <?php echo empty($this->ad) || ((!empty($this->ad)) && $this->ad->banner_type == 1) ? "checked=checked" : ""; ?>> <label for="banner_ad"><?php echo $this->translate("Upload Banner Image"); ?></label>
  								</li>
  								<li>
  									<input onclick="showBannerAds(this.value);" type="radio" name="banner_type" value="0" id="banerexternal_ad" <?php echo (!empty($this->ad)) && $this->ad->banner_type == 0 ? "checked=checked" : ""; ?>> <label for="banerexternal_ad"><?php echo $this->translate("Insert Banner HTML"); ?></label>
  								</li>
                </ul>
							</div>  
						</div>
            
            <?php if($this->getBannerSizes) { ?>
							<div id="banner_size" class="sescmads_create_field_row sesbasic_clearfix">
								<div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('Banner Sizes'); ?></label></div>
								<div class="sescmads_create_field_element">
									<select class="form-control sesbasic_button sescomm_banner_size" onchange="changeHeightWidth(this.value);" name="banner_id">
										<?php echo $this->getBannerSizes; ?>
									</select>
								</div>
							</div>
            <?php } ?>
            <?php if(!empty($this->widgetid)) { ?>
              <input style="display:none;" type="text" name="widgetid" id="widgetid" value="<?php echo (!empty($this->ad)) ? $this->ad->widgetid : $this->widgetid; ?>">
            <?php } ?>
            
            <div id="banner_image" class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('SESCOMMImage'); ?></label></div>
              <div class="sescmads_create_field_element"><input id="image-banner" type="file" name="image-banner" class="required _image_image <?php echo !empty($this->ad) ? 'fromedit' : '' ?> ?>" onChange="imagePreview(this);" /></div>
            </div>          
            <?php if(!empty($this->ad) && engine_count($this->attachment)){ ?>
            <input type="hidden" value="<?php echo $this->attachment[0]->getIdentity(); ?>" name="attachmentId[]">
            <input type="hidden" value="<?php echo $this->attachment[0]->file_id; ?>" name="attachmentFileId[]">
            <?php } ?>
<!--            <div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label class="required"> <?php //echo $this->translate('Heading'); ?> </label></div>
              <div class="sescmads_create_field_element"><input type="text" value="<?php //echo !empty($this->attachment[0]->title) ? $this->attachment[0]->title : ''; ?>" name="heading1[]" class="sescommunity_content_text required _preview_title" /></div>
            </div>-->
<!--             <div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label><?php //echo $this->translate('Description'); ?></label></div>
              <div class="sescmads_create_field_element"><input type="text" value="<?php //echo !empty($this->attachment[0]->description) ? $this->attachment[0]->description : ''; ?>" name="description1[]" class="sescommunity_content_text _preview_des" /></div>
            </div>-->
            <div id="banner_url" class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate("Destination URL"); ?></label></div>
              <div class="sescmads_create_field_element"><input id="destination_url" type="text" value="<?php echo !empty($this->attachment[0]->destination_url) ? $this->attachment[0]->destination_url : ''; ?>" name="destinationurl1[]" class="sescommunity_content_text required url" /><span class="sescmads_create_hint sesbasic_text_light"><?php echo $this->translate('eg. http://www.example.com'); ?></span></div>
            </div>
            
						<div id="banner_html_code" class="sescommunityads_post_descbox" style="display:none;">
							<div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('Html Code'); ?></label></div>
              <div class="sescmads_create_field_element">
  							<textarea class="form-control required sescommunityads_carousel_title_text" name="html_code"><?php echo !empty($this->ad->html_code) ? $this->ad->html_code : ''; ?></textarea>
              </div>
            </div>
      </div>
    </div>
  </form>
  <?php } ?>
  
  <?php if($package->video){ ?>
  <form name="video_form" id="video_form" method="post">
  <div class="video_div hideall" style="display:none">
    <div class="sescmads_create_step">
        <div class="sescmads_create_step_header"><?php echo $this->translate('Single Video'); ?></div>
        <div class="sescmads_create_fields">
        
          <div class="sescommunityads_post_descbox" style="display:none;">
            <label><?php echo $this->translate('SESCOMMText'); ?></label>
            <textarea class="form-control required sescommunityads_carousel_title_text" name="main_heading_title"></textarea>
          </div>
          
          <div class="sescmads_create_field_row sesbasic_clearfix">
            <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('Video (mp4 only)'); ?></label></div>
            <div class="sescmads_create_field_element"><input type="file" id="choose-video" class="required _image_image <?php echo !empty($this->ad) ? 'fromedit' : '' ?> ?>"  onChange="uploadVideoSescomm(this)" accept="video/*" name="image"/></div>
          </div>
            <div class="sescmads_create_field_row sesbasic_clearfix">
                <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('Image'); ?></label></div>
                <div class="sescmads_create_field_element"><input type="file" id="choose-video-image" class="required video_image _image_image <?php echo !empty($this->ad) ? 'fromedit' : '' ?> ?>"  onChange="imagePreview(this);" name="image-video"/></div>
            </div>
          <div class="sescmads_create_field_row sesbasic_clearfix">
            <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('Heading'); ?></label></div>
            <div class="sescmads_create_field_element"><input type="text" value="<?php echo !empty($this->attachment[0]->title) ? $this->attachment[0]->title : ''; ?>" name="heading1[]" class="sescommunity_content_text required _preview_title"></div>
          </div>
          <?php if(!empty($this->ad) && engine_count($this->attachment)){ ?>
            <input type="hidden" value="<?php echo $this->attachment[0]->getIdentity(); ?>" name="attachmentId[]">
            <input type="hidden" value="<?php echo $this->attachment[0]->file_id; ?>" name="attachmentFileId[]">
            <?php } ?>
          <div class="sescmads_create_field_row sesbasic_clearfix">
            <div class="sescmads_create_field_label"><label><?php echo $this->translate('Description'); ?></label></div>
            <div class="sescmads_create_field_element"><input type="text" value="<?php echo !empty($this->attachment[0]->description) ? $this->attachment[0]->description : ''; ?>"  name="description1[]" class="sescommunity_content_text _preview_des"></div>
          </div>
          
          <div class="sescmads_create_field_row sesbasic_clearfix">
            <div class="sescmads_create_field_label"><label class="required"><?php echo $this->translate('Destination URL'); ?></label></div>
            <div class="sescmads_create_field_element"><input type="text" name="destinationurl1[]" value="<?php echo !empty($this->attachment[0]->destination_url) ? $this->attachment[0]->destination_url : ''; ?>" class="sescommunity_content_text required url"><span class="sescmads_create_hint sesbasic_text_light"><?php echo $this->translate('eg. http://www.example.com'); ?></span></div>
          </div>
          <?php if($this->callToAction){ ?>
            <div class="sescmads_create_field_row sesbasic_clearfix">
              <div class="sescmads_create_field_label"><label><?php echo $this->translate('Call to Action'); ?></label></div>
              <div class="sescmads_create_field_element">
                <select class="form-control sesbasic_button sescomm_call_to_action" name="call_to_action">
                  <?php echo $this->callToAction; ?>
                </select>
              </div>
               <div id="sescomm_calltoaction_url" style="display:none;">
                <input type="text" name="calltoaction_url" class="url">
              </div>
            </div>
            <?php } ?>    
        </div>
    </div>
  </div>
  </form>
  <?php } ?>
