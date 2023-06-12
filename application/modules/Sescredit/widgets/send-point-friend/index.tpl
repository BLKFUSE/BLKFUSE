<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescredit
 * @package    Sescredit
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-01-18 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl') . 'application/modules/Sescredit/externals/scripts/core.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescredit/externals/styles/styles.css'); ?>
<div class="sescredit_send_points_form sesbasic_bxs">
	<?php echo $this->form->render($this); ?>
</div>	
<div class="friend_send_point_success_message sescredit_success_message sesbasic_bxs" style="display:none;"><span><?php echo $this->translate("You have successfully sent point.");?></span></div>
<script type="text/javascript">

  en4.core.runonce.add(function() { 
    formObj = scriptJquery('#filter_form').find('div').find('div').find('div');
    AutocompleterRequestJSON('friend_name_search', "<?php echo $this->url(array('module' =>'sescredit','controller' => 'ajax', 'action' => 'get-friend'),'default',true); ?>", function(selecteditem) {
      scriptJquery('#friend_user_id').val(selecteditem.id);
    })
  });
  
  scriptJquery('#sescredit_send_point_friend').submit(function(e) {
      e.preventDefault();
      var friend = scriptJquery('#friend_user_id').val();
      var pointValue = scriptJquery('#send_credit_value').val();
      var message = scriptJquery('#friend_message').val();
      scriptJquery('.sescredit_error_message').remove();
      var isValid = true;
      if(!friend) {
        scriptJquery('#friend_name_search').parent().append('<span class="sescredit_error_message"><span>'+en4.core.language.translate("Please enter your friend name.")+'</span></span>');
        isValid = false;
      }
      if(!pointValue || parseInt(pointValue) <= 0) {
        scriptJquery('#send_credit_value').parent().append('<span class="sescredit_error_message"><span>'+en4.core.language.translate("Enter points greater than 1.")+'</span></span>');
        isValid = false;
      }
      if(isValid == false)
        return false;
      scriptJquery.post(en4.core.baseUrl + "widget/index/mod/sescredit/id/<?php echo $this->identity; ?>/name/send-point-friend",{send_credit_value:pointValue,friend_user_id:friend,message:message},function(response) {
        response = scriptJquery.parseJSON(response);
        if(response.status == 0) {
          scriptJquery('#sescredit_send_point_friend').find('div').find('div').find('.form-elements').prepend('<ul class="form-errors"><ul class="errors"><li>'+response.message+'Please complete this field - it is required.</li></ul></ul>');
        }
        else {
          scriptJquery('.friend_send_point_success_message').show();
          scriptJquery('#sescredit_send_point_friend').hide();
          setTimeout(function(){
            scriptJquery('.friend_send_point_success_message').hide();
            scriptJquery('#sescredit_send_point_friend').show();
            document.getElementById('sescredit_send_point_friend').reset();
          }, 4000)
        }
      });
  }); 
</script>
