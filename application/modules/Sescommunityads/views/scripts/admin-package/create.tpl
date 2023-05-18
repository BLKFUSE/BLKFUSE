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
 
 ?>

<?php include APPLICATION_PATH .  '/application/modules/Sescommunityads/views/scripts/dismiss_message.tpl';?>
<div class="sesbasic_search_reasult">
	<a href="admin/sescommunityads/package/manage" class="sesbasic_icon_back buttonlink">Back to Manage Packages</a></div>
<div class="settings sesbasic_admin_form">
  <?php echo $this->form->render($this) ?>
</div>

<script type="application/javascript">
scriptJquery('#click_type').change(function(e){
    var value = scriptJquery(this).val();
   scriptJquery('#click_limit-label').find('.optional').html(scriptJquery('#click_type').attr('data-title-'+value)); 
   scriptJquery('#click_limit-element').find('.description').html(scriptJquery('#click_type').attr('data-description-'+value));  
   if(scriptJquery('#package_type').val() == 'recurring' && value == "perday"){
      scriptJquery('#click_limit-wrapper').hide();
   }else
    scriptJquery('#click_limit-wrapper').show(); 
});
scriptJquery('#click_type').trigger('change');
scriptJquery('#is_renew_link').change(function(e){
  var value = scriptJquery(this).val();
  if(value == 1)
    scriptJquery('#renew_link_days-wrapper').show();
  else
      scriptJquery('#renew_link_days-wrapper').hide();
});
scriptJquery('#is_renew_link').trigger('change');

scriptJquery('#package_type').change(function(e){
  var value = scriptJquery(this).val();
  if(value == "recurring"){
    scriptJquery('#is_renew_link-wrapper').hide();
    scriptJquery('#renew_link_days-wrapper').hide();
    scriptJquery('#duration-wrapper').show();
    scriptJquery('#recurrence-wrapper').show();
  }else{
    scriptJquery('#is_renew_link-wrapper').show();
    if(scriptJquery('#is_renew_link').val() == 1)
    scriptJquery('#renew_link_days-wrapper').show();
    scriptJquery('#duration-wrapper').hide();
    scriptJquery('#recurrence-wrapper').hide();
  }  
  if(scriptJquery('#package_type').val() == 'recurring' && scriptJquery('#click_type').val() == "perday"){
      scriptJquery('#click_limit-wrapper').hide();
   }else
    scriptJquery('#click_limit-wrapper').show();   
})
scriptJquery('#package_type').trigger('change');

function sponsored(){
  var isSponsored = scriptJquery('#sponsored').is(':checked');
  if(isSponsored){
    scriptJquery('#sponsored_days-wrapper').show();
  }else{
    scriptJquery('#sponsored_days-wrapper').hide(); 
  }  
}
sponsored();
scriptJquery('#sponsored').click(function(e){
  sponsored();
});

function featred(){
  var isfeatured = scriptJquery('#featured').is(':checked');
  if(isfeatured){
    scriptJquery('#featured_days-wrapper').show();
  }else{
    scriptJquery('#featured_days-wrapper').hide(); 
  }  
}
featred();
scriptJquery('#featured').click(function(e){
  featred();
});
scriptJquery('.global_form').submit(function(e){
  if(scriptJquery('#package_type').val() == "nonRecurring"){
    scriptJquery('#duration-select').val('forever');
    scriptJquery('#recurrence-text').val('1');  
  }  
  return true;
})
</script>