<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _campaign.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<div class="sescmads_create_container sesbasic_bxs"> 
  <!--Select Campaign-->
  <div class="sescmads_create_step">
    <div class="sescmads_create_step_header"><?php echo $this->translate('Select Campaign'); ?></div>
    <div class="sescmads_create_step_content">
      <div class="sescmads_create_campaign">
        <form  id="campaign_frm">
          <div class="sescmads_create_campaign_field sesbasic_clearfix">
            <div class="sescmads_create_campaign_label">
              <label><?php echo $this->translate('Select Campaign'); ?></label>
            </div>
            <div class="sescmads_create_campaign_element">
              <select name="campaign" id="communityAds_campaign">
                <option value="0"><?php echo $this->translate('Create New Campaign'); ?></option>
                <?php foreach($this->campaign as $campaign){ ?>
                <option value="<?php echo $campaign->getIdentity(); ?>" <?php echo !empty($this->ad) && $this->ad->campaign_id == $campaign->getIdentity() ? 'selected' : ''; ?>><?php echo $campaign->getTitle(); ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="sescmads_create_campaign_field sesbasic_clearfix sescommunityads_select_content_title">
            <div class="sescmads_create_campaign_label">
              <label><?php echo $this->translate('Campaign Name'); ?></label>
            </div>
            <div class="sescmads_create_campaign_element">
              <input type="text" id="campaign_name" name="campaign_name" style="" />
            </div>
          </div>
        </form>
      </div>
    </div>
    
    <?php $settings = Engine_Api::_()->getApi('settings', 'core'); 
          $enableCat = $settings->getSetting('sescommunityads_category_enable',1);
          $categories = Engine_Api::_()->getDbTable('categories','sescommunityads')->getCategory();
          if($enableCat && engine_count($categories)){
          $manCat = $settings->getSetting('sescommunityads_category_mandatory',1);
    ?>
    <div class="sescmads_create_step_header"><?php echo $this->translate('Select Category'); ?></div>
    <div class="sescmads_create_step_content">
      <div class="sescmads_create_campaign">
          <div class="sescmads_create_campaign_field sesbasic_clearfix">
            <div class="sescmads_create_campaign_label">
              <label class="<?php echo $manCat ? 'required' : "" ?>"><?php echo $this->translate('Category'); ?></label>
            </div>
            <div class="sescmads_create_campaign_element">
              <select class="cat cat <?php echo $manCat ? 'mandatory' : "" ?>" name="category_id" id="category_id">
                <option value="0"><?php echo $this->translate('Select category'); ?></option>
                <?php foreach($categories as $category){ ?>
                <option value="<?php echo $category->getIdentity(); ?>" <?php echo !empty($this->ad) && $this->ad->category_id == $category->getIdentity() ? 'selected' : ''; ?>><?php echo $category->category_name; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          
          <?php $subcat = array();
                if(!empty($this->ad) && $this->ad->category_id){
                  $subcat = Engine_Api::_()->getDbTable('categories','sescommunityads')->getModuleSubcategory(array('category_id'=>$this->ad->category_id,'column_name'=>'*'));
                  
                }
            
           ?>
          
          <div class="sescmads_create_campaign_field sesbasic_clearfix subcat_wrapper" style="display:<?php echo engine_count($subcat) ? 'block' : 'none'; ?>;">
            <div class="sescmads_create_campaign_label">
              <label><?php echo $this->translate('Sub Category'); ?></label>
            </div>
            <div class="sescmads_create_campaign_element">
              <select class="cat" name="subcat_id" id="subcat_id">
                <option></option>
                <?php foreach($subcat as $category){ ?>
                <option value="<?php echo $category->getIdentity(); ?>" <?php echo !empty($this->ad) && $this->ad->subcat_id == $category->getIdentity() ? 'selected' : ''; ?>><?php echo $category->category_name; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <?php $subsubcat = array();
                if(!empty($this->ad) && $this->ad->subcat_id){
                  $subsubcat = Engine_Api::_()->getDbTable('categories','sescommunityads')->getModuleSubsubcategory(array('category_id'=>$this->ad->subcat_id,'column_name'=>'*'));
                }
           ?>
          <div class="sescmads_create_campaign_field sesbasic_clearfix subsubcat_wrapper" style="display:<?php echo engine_count($subsubcat) ? 'block' : 'none'; ?>;">
            <div class="sescmads_create_campaign_label">
              <label><?php echo $this->translate('Sub Sub Category'); ?></label>
            </div>
            <div class="sescmads_create_campaign_element">
              <select name="subsubcat_id" id="subsubcat_id">
                 <option></option>
                <?php foreach($subsubcat as $category){ ?>
                <option value="<?php echo $category->getIdentity(); ?>" <?php echo !empty($this->ad) && $this->ad->subsubcat_id == $category->getIdentity() ? 'selected' : ''; ?>><?php echo $category->category_name; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          
      </div>
    </div>
    <?php } ?>
    
    <script type="application/javascript">
    scriptJquery(document).on('change','#category_id',function(){
      var value = scriptJquery(this).val();
      scriptJquery(this).css('border','');
      if(!value || value == 0){
        scriptJquery(this).css('border','1px solid red');
        scriptJquery('#subcat_id').html('');
        scriptJquery('#subsubcat_id').html('');
        scriptJquery('.subsubcat_wrapper').hide();
        scriptJquery('.subcat_wrapper').hide();
        return;  
      }  
      scriptJquery('#subsubcat_id').html('');
      scriptJquery('.subsubcat_wrapper').hide();
      scriptJquery.post('sescommunityads/index/subcategory',{category_id:value},function(response){
         if(response != ""){
            scriptJquery('#subcat_id').html(response);
            scriptJquery('.subcat_wrapper').show();
         }else{
            scriptJquery('.subcat_wrapper').hide();
         }
      });
    });
    scriptJquery(document).on('change','#subcat_id',function(){
      var value = scriptJquery(this).val();
      if(!value || value == 0){
        scriptJquery('#subsubcat_id').html('');
        scriptJquery('.subsubcat_wrapper').hide();
        return;  
      }  
      scriptJquery('#subsubcat_id').html('');
      scriptJquery('.subsubcat_wrapper').hide();
      scriptJquery.post('sescommunityads/index/subsubcategory',{subcategory_id:value},function(response){
         if(response != ""){
            scriptJquery('#subsubcat_id').html(response);
            scriptJquery('.subsubcat_wrapper').show();
         }
      });
    })
    </script>
    
    <div class="sescmads_create_step_footer sesbasic_clearfix">
      <div class="floatL"> <a href="javascript:;" class="sesbasic_button sesbasic_animation sescomm_back_btn" onclick="sescomm_back_btn(1);"><?php echo $this->translate('Back'); ?></a> <a href="javascript:;" class="sesbasic_animation _btnh" onclick="sescomm_back_btn(3);"><?php echo $this->translate('Next'); ?></a> </div>
    </div>
  </div>
</div>
