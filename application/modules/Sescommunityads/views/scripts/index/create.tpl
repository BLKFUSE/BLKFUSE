<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: create.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
$start = time();
$end = time();
$oldTz = date_default_timezone_get();
date_default_timezone_set($this->viewer()->timezone);
$start_date = date('m/d/Y',$start);
$start_time = date('H:ia',strtotime('+5 hours',$start));
$end_date = date('m/d/Y',strtotime('+5 Days' ,$end));
$end_time = $start_time;
date_default_timezone_set($oldTz);
 ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/scripts/moment.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/scripts/moment-timezone.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/scripts/moment-timezone-with-data.js'); ?>
<script type="application/javascript">
var selectedBoostPostId = "<?php echo !empty($this->action_id) ? $this->action_id : 0; ?>";
</script>
<div class="sescmads_create_container sesbasic_bxs">
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/styles/styles.css'); ?>
<div class="sescommunity_create_cnt sescomm_stp_1" rel="1" style="display:none;">
  <?php include('application/modules/Sescommunityads/views/scripts/_adType.tpl'); ?>
</div>
<div class="sescommunity_create_cnt" rel="2" style="display:none;">
  <?php include('application/modules/Sescommunityads/views/scripts/_campaign.tpl'); ?>
</div>
<input type="hidden" name="package_id" id="package_id" value="<?php echo $this->package_id; ?>">
<input type="hidden" name="existingpackage" id="existingpackage" value="<?php echo !empty($this->existingpackage) ? $this->existingpackage->getIdentity() : 0; ?>">
<div class="sescommunity_create_cnt ads_preview_page" rel="3" style="display:none">
  <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')){ ?>
  <div class="boost_post_cnt hideall" style="display:none">
    <?php include('application/modules/Sescommunityads/views/scripts/_boostPost.tpl'); ?>
  </div>
  <?php } ?>
  <?php 
    $count = 5;
    if((!$this->package->targetting || engine_count($this->formField) < 2) && (!$this->package->networking || !array_key_exists('network_enable',$this->targetFields) || ($this->package->networking && !engine_count($this->networks)))){
      $count = 4;
    }
  ?>   
  <div class="sescmads_create_step sescomm_promote_cnt" style="display:none;">
        <div class="sescmads_create_step_header"><?php echo $this->translate("SELECT FORMAT"); ?></div>
        <div class="sescmads_create_step_content" id="choose_link">
     <?php $package = Engine_Api::_()->getItem('sescommunityads_packages',$this->package_id); ?>
      <div class="sescmads_choose_format">
        <ul>
          <?php if($package->carousel && empty($this->widgetid)){ ?>
          <?php 
            $baseURL = $this->layout()->staticBaseUrl;
            $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
            $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js'); 
          ?>
          <li class='<?php echo !empty($this->ad) && $this->ad->subtype == "carousel" ? 'active' : ""; ?>'>
            <article>
              <span class="_tick fa fa-check"></span>
              <input type="radio" class="form-control" <?php echo !empty($this->ad) && $this->ad->subtype == "carousel" ? 'checked' : ""; ?> rel="carousel_div" name="formate_type" id="format-1">
              <label for="format-1"> <span class="format_img"><img src="application/modules/Sescommunityads/externals/images/carousel.png"></span> <span class="format_name"><?php echo $this->translate('Carousel'); ?></span> </label>              
            </article>
          </li>
          <?php } ?>
          <?php if(empty($this->widgetid)) { ?>
          <li class='<?php echo !empty($this->ad) && $this->ad->subtype == "image" ? 'active' : (empty($this->ad) ? "active" : "" ); ?>'>
            <article>
              <span class="_tick fa fa-check"></span>
              <input type="radio" class="form-control" <?php echo !empty($this->ad) && $this->ad->subtype == "image" ? 'checked' : (empty($this->ad) ? "checked" : "" ); ?> rel="image_div" name="formate_type" id="format-2">
              <label for="format-2"><span class="format_img"><img src="application/modules/Sescommunityads/externals/images/single_image.png"></span> <span class="format_name"><?php echo $this->translate('Single Image'); ?></span> </label>
            </article>
          </li>
          <?php } ?>
          <?php if($package->video && empty($this->widgetid)){ ?>
          <li class="<?php echo !empty($this->ad) && $this->ad->subtype == "video" ? 'active' : ""; ?>">
            <article>
              <span class="_tick fa fa-check"></span>
              <input type="radio" class="form-control" rel="video_div" <?php echo !empty($this->ad) && $this->ad->subtype == "video" ? 'checked' : ""; ?> name="formate_type" id="format-3">
              <label for="format-3"><span class="format_img"><img src="application/modules/Sescommunityads/externals/images/single_video.png"></span> <span class="format_name"><?php echo $this->translate('Single Video'); ?></span></label>
            </article>
          </li>
          <?php } ?>
          <?php //Banner Ads ?>
          <?php if($package->banner){ ?>
          <?php if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sescomadbanr')) { ?>
            <?php if(!empty($this->widgetid)) { ?>
            <li class='<?php echo !empty($this->ad) && $this->ad->subtype == "banner" ? 'active' : (empty($this->ad) ? "active" : "" ); ?>'>
            <?php } else { ?>
						<li id="banner_select_option" class="<?php echo !empty($this->ad) && $this->ad->subtype == "banner" ? 'active' : ""; ?>">
						<?php } ?>
							<article>
								<span class="_tick fa fa-check"></span>
								<?php if(!empty($this->widgetid)) { ?>
                  <input type="radio" class="form-control" rel="banner_div" <?php echo !empty($this->ad) && $this->ad->subtype == "banner" ? 'checked' : (empty($this->ad) ? "checked" : "" ); ?> name="formate_type" id="format-4">
								<?php } else { ?>
                  <input type="radio" class="form-control" rel="banner_div" <?php echo !empty($this->ad) && $this->ad->subtype == "banner" ? 'checked' : ""; ?> name="formate_type" id="format-4">
								<?php } ?>
								<label for="format-4"><span class="format_img"><img src="application/modules/Sescommunityads/externals/images/single_image.png"></span> <span class="format_name"><?php echo $this->translate('Banner Ads'); ?></span></label>
							</article>
						</li>
          <?php } ?>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="sescmads_create_main">
      <div class="sescmads_create_main_left" style="display:none">
      <div class="sescmads_create_step select_sescomm_content" style="display:none;">
        <div class="sescmads_create_step_header"><?php echo $this->translate("SELECT CONTENT"); ?></div>
        <div class="sescmads_create_step_content">
          <div class="sescmads_create_campaign">	
              <div class="sescmads_create_campaign_field sesbasic_clearfix">
                <div class="sescmads_create_campaign_label">
                  <label class="required"><?php echo $this->translate("Select Content Type"); ?></label>
                </div>
                <?php $modules = Engine_Api::_()->getDbTable('modules','sescommunityads')->getEnabledModuleNames(array('enabled'=>1)); ?>
                <div class="sescmads_create_campaign_element" style="margin-top: 10px;">
                  <select class="form-control sescommunity_content_text" name="resource_type" id="sescomm_resource_type">
                    <option value=""><?php echo $this->translate('Select Module Name'); ?></option>
                    <?php foreach($modules as $module){
                      $select = "";
                      if(!empty($this->ad) &&  $module["content_type"] == $this->ad->resources_type && ($this->ad->type == "promote_content_cnt"))
                        $select = "selected";
                     ?>
                      <option value="<?php echo $module["content_type"]; ?>" <?php echo $select; ?>><?php echo $module["title"]; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="sescmads_create_campaign_field sesbasic_clearfix">
                <div class="sescmads_create_campaign_label">
                  <label class="required"><?php echo $this->translate('Select Content'); ?></label>
                </div>
                <div class="sescmads_create_campaign_element" style="margin-top: 10px;">
                  <select class="form-control sescommunity_content_text" name="resource_id" id="sescomm_resource_id">
                    
                  </select>
                </div>
              </div>
          </div>
        </div>
      </div>
      <div class="promote_website_cnt hideall" style="display:none">
        <?php include('application/modules/Sescommunityads/views/scripts/_promoteWebsite.tpl'); ?>
      </div>
      <div class="promote_content_cnt hideall" style="display:none">
        <?php include('application/modules/Sescommunityads/views/scripts/_promoteContent.tpl'); ?>
      </div> 
      
      <?php  if($count != 4){ ?>
        <div class="ad_targetting sescmads_create_step">
          <div class="sescmads_create_step_header">
            <button class="tablinks" data-rel="choose_targetting"><?php echo $this->translate('Targeting'); ?></button>
          </div>
          <div class ="tabcontent sescmads_create_targetting" id="choose_targetting">
            <div class="target_inner">
              <div class="sescmads_create_targetting_tabs">
                <ul>
                  <?php if($this->package->targetting  && engine_count($this->formField) > 1){ ?>
                  <?php 
                     $counter = 0;
                     foreach($this->profileTypes as $profileType){ ?>
                  <li class="<?php echo $counter == 0 ? 'sescustom_active' : ''; ?>"> <a href="javascript:;" rel="<?php echo $profileType->getIdentity(); ?>" class="sescustom_field_a"><?php echo $profileType->label; ?></a> </li>
                  <?php 
                     $counter++;
                     } 
                  ?>
                  <?php } ?>
                  <?php if($this->package->networking && engine_count($this->networks) && array_key_exists('network_enable',$this->targetFields)){ ?>
                  <li class="<?php echo !$this->package->targetting ? 'sescustom_active' : ''; ?>"> <a href="javascript:;" rel="sescommunity_network_targetting" class="sescustom_field_a"><?php echo $this->translate('Network Targeting'); ?></a> </li>
                  <?php } ?>
                </ul>
              </div>
              <?php //display all the profile fields here; ?>
              <div class="sescommunityads_packages sesbasic_bg">
                <?php if($this->package->targetting && engine_count($this->formField) > 1){ ?>
                <?php echo $this->formField->render($this); ?>
                <?php } ?>
                <?php if($this->package->networking && engine_count($this->networks) && array_key_exists('network_enable',$this->targetFields)){ ?>
                <div class="sescommunity_network_targetting sesbasic_clearfix">
                  <select multiple name="network[]" id="networks">
                    <?php foreach($this->networks as $network){   ?>
                    <option value="<?php echo $network->getIdentity(); ?>" <?php echo !empty($this->targetData) && engine_in_array($network->getIdentity(),$this->targetData) ? "selected" : "";  ?>><?php echo $network->getTitle(); ?></option>
                    <?php } ?>
                  </select>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
       <?php } ?>
      
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1) && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmember') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads_enable_location',1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)){ ?>
        <div class="ad_scheduling sescmads_create_step">
          <div class="sescmads_create_step_header">
            <button class="tablinks" data-rel="choose_locationTargetting"><?php echo $this->translate('Location Targeting'); ?></button>
          </div>
          <div class="sescmads_create_location" id="choose_locationTargetting">
            <form name="schedulling" id="location_targetting">
            	<div class="sescmads_create_fields sesbasic_clearfix">
                <div class="sescmads_create_field_row sesbasic_clearfix">
                  <div class="sescmads_create_field_label">
                  	<label><?php echo $this->translate("Location Type"); ?></label>
                  </div>
                  <div class="sescmads_create_field_element">
                    <div>
                      <input type="radio" name="location_type" value="3956" id="location_miles" <?php echo empty($this->ad) || ((!empty($this->ad)) && $this->ad->location_type == "3956") ? "checked=checked" : ""; ?>> <label for="location_miles"><?php echo $this->translate("Miles"); ?></label>
                    </div>
                    <div>
                      <input type="radio" name="location_type" value="6371" id="location_km" <?php echo (!empty($this->ad)) && $this->ad->location_type == "6371" ? "checked=checked" : ""; ?>> <label for="location_km"><?php echo $this->translate("Killometer"); ?></label>
                    </div>
                  </div>  
                </div>
                <div class="sescmads_create_field_row sesbasic_clearfix">
                	<div class="sescmads_create_field_label">
                  	<label><?php echo $this->translate("Location Distance"); ?></label>
                  </div>
                  <div class="sescmads_create_field_element">
                  	<input type="text" name="location_distance" id="location_distance" value="<?php echo (!empty($this->ad)) && $this->ad->location_distance ? $this->ad->location_distance : ''; ?>" placeholder="<?php echo $this->translate("Location Distance"); ?>">
                  </div>
                </div>
                <div class="sescmads_create_field_row sesbasic_clearfix">
                	<div class="sescmads_create_field_label">
                  	<label><?php echo $this->translate("Location"); ?></label>
                  </div>
                  <div class="sescmads_create_field_element">
                    <input type="text" name="location" id="location_sescomm" value="<?php echo (!empty($this->ad)) && $this->ad->location ? $this->ad->location : ''; ?>" placeholder="<?php echo $this->translate("Location"); ?>">
                    <input type="hidden" value="" id="sescomm_lat" name="lat">
                    <input type="hidden" value="" id="sescomm_lng" name="lat">
                  </div>
                </div>
            	</div>
            </form>
          </div>
          <script type="application/javascript">
          sescommMapList();
          </script>
        </div>
        
        <?php //Reverse Location Targeting ?>
        <div class="ad_scheduling sescmads_create_step">
          <div class="sescmads_create_step_header">
            <button class="tablinks" data-rel="choose_reverselocationTargetting"><?php echo $this->translate('Reverse Location Targeting'); ?></button>
          </div>
          <div class="sescmads_create_reverselocation" id="choose_reverselocationTargetting">
            <form name="schedulling" id="location_reversetargetting">
            	<div class="sescmads_create_fields sesbasic_clearfix">
                <div class="sescmads_create_field_row sesbasic_clearfix">
                  <div class="sescmads_create_field_label">
                  	<label><?php echo $this->translate("Location Type"); ?></label>
                  </div>
                  <div class="sescmads_create_field_element">
                    <div>
                      <input type="radio" name="revselocation_type" value="3956" id="revselocation_miles" <?php echo empty($this->ad) || ((!empty($this->ad)) && $this->ad->revselocation_type == "3956") ? "checked=checked" : ""; ?>> <label for="revselocation_miles"><?php echo $this->translate("Miles"); ?></label>
                    </div>
                    <div>
                      <input type="radio" name="revselocation_type" value="6371" id="revselocation_km" <?php echo (!empty($this->ad)) && $this->ad->revselocation_type == "6371" ? "checked=checked" : ""; ?>> <label for="revselocation_km"><?php echo $this->translate("Killometer"); ?></label>
                    </div>
                  </div>  
                </div>
                <div class="sescmads_create_field_row sesbasic_clearfix">
                	<div class="sescmads_create_field_label">
                  	<label><?php echo $this->translate("Location Distance"); ?></label>
                  </div>
                  <div class="sescmads_create_field_element">
                  	<input type="text" name="revselocation_distance" id="revselocation_distance" value="<?php echo (!empty($this->ad)) && $this->ad->revselocation_distance ? $this->ad->revselocation_distance : ''; ?>" placeholder="<?php echo $this->translate("Location Distance"); ?>">
                  </div>
                </div>
                <div class="sescmads_create_field_row sesbasic_clearfix">
                	<div class="sescmads_create_field_label">
                  	<label><?php echo $this->translate("Location"); ?></label>
                  </div>
                  <div class="sescmads_create_field_element">
                    <input type="text" name="revselocation" id="revselocation_sescomm" value="<?php echo (!empty($this->ad)) && $this->ad->revselocation ? $this->ad->revselocation : ''; ?>" placeholder="<?php echo $this->translate("Location"); ?>">
                    <input type="hidden" value="" id="revsesescomm_lat" name="revselat">
                    <input type="hidden" value="" id="revsesescomm_lng" name="revselng">
                  </div>
                </div>
            	</div>
            </form>
          </div>
          <script type="application/javascript">
           sescommRevseMapList();
          </script>
        </div>
       <?php } ?>
       
       <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesinterest') && !empty($this->package->interests)) { ?>
          <?php $getResults = Engine_Api::_()->getDbTable('interests', 'sesinterest')->getResults(array('approved' => 1, 'column_name' => '*')); ?>
          <?php if(engine_count($getResults) > 0) { ?>
						<div class="ad_scheduling sescmads_create_step">
							<div class="sescmads_create_step_header">
								<button class="tablinks" data-rel="choose_interestTargetting"><?php echo $this->translate('Interests Targeting'); ?></button>
							</div>
							<div class="sescmads_create_interest" id="choose_interestTargetting">
								<form name="schedulling" id="interest_targetting">
									<div class="sescmads_create_fields sesbasic_clearfix">
										<div class="sescmads_create_field_row sesbasic_clearfix">
											<div class="sescmads_create_field_label">
												<label><?php echo $this->translate("Choose Interest"); ?></label>
											</div>
											<div class="sescmads_create_field_element">
												<ul class="_multiplefields">
													<?php foreach($getResults as $getResult) { ?>
                            <li>
  														<input type="checkbox" id="intrest_<?php echo $getResult->interest_id; ?>" name="interest_enable" value="<?php echo $getResult->interest_id; ?>" <?php echo !empty($this->interestTargetData) && engine_in_array($getResult->interest_id,$this->interestTargetData) ? "checked" : "";  ?>><label for="intrest_<?php echo $getResult->interest_id; ?>"><?php echo $getResult->interest_name; ?></label>
                            </li>
													<?php } ?>
												</ul>
											</div>  
										</div>
									</div>
								</form>
							</div>
						</div>
					<?php } ?>
       <?php } ?>
       
        <div class="ad_scheduling sescmads_create_step">
          <div class="sescmads_create_step_header">
            <button class="tablinks" data-rel="choose_scheduling"><?php echo $this->translate('Scheduling'); ?></button>
          </div>
          <div class="sescmads_create_scheduling" id="choose_scheduling">
            <form name="schedulling" id="schedulling">
            <div class="sescommunityads_left_text sesbasic_clearfix">
              <div class="sescmads_format_inner">
                <div class="sescommunityads_select_content">
                  <label><?php echo $this->translate('Start Date & Time'); ?></label>
                  <input type="text" class="displayF" name="start_date" id="sescomm_start_date" value="<?php echo isset($this->ad->startdate) ? date('m/d/Y',strtotime($this->ad->startdate)) : $start_date  ?>">
                  <input type="text" name="start_time" id="sescomm_start_time" value="<?php echo isset($this->ad->startdate) ? date('H:ia',strtotime($this->ad->startdate)) : $start_time  ?>" class="ui-timepicker-input" autocomplete="off">
                </div>
                <div class="sescommunityads_select_content sescomm_end_date_div" style="display:<?php echo isset($this->ad->enddate) && $this->ad->enddate != "0000-00-00 00:00:00" ? 'block' : (empty($this->ad) ? 'block' : "none"); ?>">
                  <label><?php echo $this->translate('End Date & Time'); ?></label>
                  <input type="text" class="displayF" name="end_date" id="sescomm_end_date" value="<?php echo isset($this->ad->enddate) && $this->ad->enddate != "0000-00-00 00:00:00" ? date('m/d/Y',strtotime($this->ad->enddate))  : (empty($this->ad) ? $end_date : "");  ?>">
                  <input type="text" name="end_time" id="sescomm_end_time" value="<?php echo isset($this->ad->enddate) && $this->ad->enddate != "0000-00-00 00:00:00" ? date('H:ia',strtotime($this->ad->enddate)) : (empty($this->ad) ?  $end_time : "" )  ?>" class="ui-timepicker-input" autocomplete="off">
                </div>
                <div class="shedulling_error" style="display:none;">
                  <span></span>
                </div>
                <div class="_run_this">
                  <input type="checkbox" value="1" <?php echo isset($this->ad->enddate) && $this->ad->enddate != "0000-00-00 00:00:00" ? '' : (empty($this->ad) ? '' : "checked"); ?> name="ad_end_date" id="ad_end_date" class="form-control ad_end_date" />
                  <label for="ad_end_date"><?php echo $this->translate('Run this ad till it expires'); ?></label>
                </div>
              </div>
            </div>
            </form>
          </div>
        </div>
      </div>      
      <div class="sescmads_create_main_right sescommunityads_right_preview">
        <div class="sescmads_create_step sesbasic_clearfix">
          <div class="sescmads_create_step_header">
            <span><?php echo $this->translate('Ad Preview'); ?></span>
          </div>
          <?php include('application/modules/Sescommunityads/views/scripts/_videoPreview.tpl'); ?>
          <?php include('application/modules/Sescommunityads/views/scripts/_boostPostPreview.tpl'); ?>
          <?php include('application/modules/Sescommunityads/views/scripts/_imagePreview.tpl'); ?>
          <?php include('application/modules/Sescommunityads/views/scripts/_bannerPreview.tpl'); ?>
          <?php include('application/modules/Sescommunityads/views/scripts/_carouselContent.tpl'); ?>
        </div>
      </div>
      <div class="sescmads_create_step_footer sesbasic_clearfix sescomm_footer_cnt">
        <div class="floatL">
          <a href="javascript:;" class="sesbasic_button sesbasic_animation" onclick="sescomm_back_btn(2);"><?php echo $this->translate('Back'); ?></a>
          
          <?php if(empty($this->ad)){
              $adSubmit = 'Create Ad';
          }else{
              $adSubmit = "Edit Ad";
          } ?>
          
          <a href="javascript:;" class="sesbasic_animation _btnh" onclick="submitsescommunitycreate();"><?php echo $this->translate($adSubmit); ?></a>
        </div>
        <div class="sesbasic_loading_cont_overlay" id="sesbasic_loading_cont_overlay_submit"></div>
      </div>
    </div>
</div>
<input type="hidden" name="sescommunityad_id" id="sescommunityad_id" value="<?php echo (!empty($this->ad)) ? $this->ad->getIdentity() : 0; ?>">
<script type="application/javascript">
var sescommunityIsEditForm = "<?php echo $this->editName; ?>";
var selectedType = "";
var typeValue = "";
var subtypeValue = "";

scriptJquery(document).ready(function(){
  <?php if(!empty($this->ad)){ ?>
    typeValue = "<?php echo $this->ad->type; ?>";
    subtypeValue = "<?php echo $this->ad->subtype; ?>";
    scriptJquery('a[rel='+typeValue+']').trigger('click');
    scriptJquery('.sescomm_back_btn').remove();
    scriptJquery('#communityAds_campaign').trigger('change');
    selectedType = "<?php echo $this->ad->type == 'promote_content_cnt' ? $this->ad->resources_id : ''; ?>";
    <?php if($this->ad->type == 'promote_content_cnt' && $this->ad->resources_type == 'sespage_page'){ ?>
        var valueElem = scriptJquery('#sescomm_resource_type').find('option[value=sespage_page]');
        scriptJquery('#sescomm_resource_type').closest('.sescmads_create_campaign_field').hide();
        promotePageContent = true;
        scriptJquery('#sescomm_resource_type').val('sespage_page');
        scriptJquery('#sescomm_resource_type').trigger('change');
    <?php } ?>
    if(selectedType)
      scriptJquery('#sescomm_resource_type').trigger('change');
  <?php }else if(!empty($this->action_id)){ ?>
     typeValue = "<?php echo 'boost_post_cnt' ?>";
     subtypeValue = "";
     selectedType = "<?php echo $this->action_id ?>";
     scriptJquery('a[rel='+typeValue+']').trigger('click');
     scriptJquery('.sescomm_back_btn').remove();
  <?php }elseif(!empty($this->widgetid)){ ?>
    scriptJquery('a[rel='+'promote_website_cnt'+']').trigger('click');
    scriptJquery('.sescomm_back_btn').remove();
  <?php }else{ ?>
    scriptJquery('.sescomm_stp_1').show();
  <?php } ?>
});
  defaultRunSescommunityads();
</script>
<script>

scriptJquery(document).on('click','.tablinks',function(){
   var totalTabs = scriptJquery('.tablinks');
   for(i=0;i<totalTabs.length;i++){
     document.getElementById(scriptJquery(totalTabs[i]).data('rel')).style.display = "none"; 
   }
   scriptJquery('.tablinks').removeClass('active');
   document.getElementById(scriptJquery(this).data('rel')).style.display = "block";
   scriptJquery(this).addClass('active');
});
</script>
<script type="application/javascript">
  //default values of ads
  var contentImageValue = "application/modules/Sescommunityads/externals/images/transprant-bg.png";
  var contentTitleValue = "";
  var blankImage = "application/modules/Sescommunityads/externals/images/transprant-bg.png";
  
  <?php if(empty($this->widgetid)) { ?>
  scriptJquery(document).ready(function(){
    createSesadvCarousel();  
  });
  <?php } ?>
  var sescommStartPastDate = "<?php echo $this->translate('Start date is in the past. Please enter an start date greater than or equal to today\'s date.')?>";
  var sescommEndBeforeDate = "<?php echo $this->translate('Ad cannot end before or same date as it starts. Please choose an ad end date and time that is later than the start date and time.')?>";
<?php if($this->subject()){ ?>
  var sescommstartCalanderDate = new Date('<?php echo date("m/d/Y",strtotime($this->subject->creation_date));  ?>');
<?php }else{ ?>
  var sescommstartCalanderDate = new Date('<?php echo date("m/d/Y"); ?>');
<?php } ?>
</script>
</div>
<script type="application/javascript">
var scrollTopPosition;
var leftElementOrgHeight;
scriptJquery(function(){
  scriptJquery(window).scroll(function(){
    return;
    var sescmads_create_preview_video = scriptJquery('.sescmads_create_preview_video');
    var sescmads_ad_preview_boost_post = scriptJquery('.sescmads_ad_preview_boost_post');
    var sescmads_create_preview_image = scriptJquery('.sescmads_create_preview_image');
    var sescmads_create_preview_carousel = scriptJquery('.sescmads_create_preview_carousel');
    var rightElement = scriptJquery('.sescmads_create_main_right').find('.sescmads_create_step').eq(0);    
    if(sescmads_create_preview_video.css('display') != "none" || sescmads_ad_preview_boost_post.css('display') != "none" || sescmads_create_preview_image.css('display') != "none" || sescmads_create_preview_carousel.css('display') != "none"){
      if(typeof leftElementOrgHeight == "undefined")
        leftElementOrgHeight = scriptJquery('.sescmads_create_main_left').height();
      scrollTopPosition = scriptJquery(this).scrollTop() + 20;
      var getElementTopPosition = (rightElement.offset().top - scriptJquery(document).scrollTop()) + rightElement.height();     
      var fixedHeader = scriptJquery('.layout_page_header').css('position');
      if(fixedHeader != "fixed"){
          fixedHeader = scriptJquery('.global_header').css('position');
      }
      var height = '0';
      if(fixedHeader == "fixed"){
          height = scriptJquery('.layout_page_header').height();
      }
      rightElement.css('left',rightElement.offset().left+"px"); 
      rightElement.css('width',scriptJquery('.sescommunityads_right_preview').width()+"px");     
      var leftHeightElem = scrollTopPosition - rightElement.height() + height; 
      if(scrollTopPosition > getElementTopPosition ){
        if(leftHeightElem < (leftElementOrgHeight - rightElement.height()))
          rightElement.css('margin-top',(leftHeightElem)+"px");
      }else{
        rightElement.css('margin-top',"");  
      }
    }
  })
});

</script>
