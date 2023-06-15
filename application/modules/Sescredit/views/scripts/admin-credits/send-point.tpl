<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescredit
 * @package    Sescredit
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: send-point.tpl  2019-01-18 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php include APPLICATION_PATH .  '/application/modules/Sescredit/views/scripts/dismiss_message.tpl';?>
<?php $base_url = $this->layout()->staticBaseUrl;?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>
<?php $this->headLink()->appendStylesheet($base_url . 'application/modules/Sescredit/externals/styles/styles.css'); ?>    
<div>
  <?php echo $this->htmlLink(array('action' => 'send-points', 'reset' => false), $this->translate("Back to Manage Offers"),array('class' => 'buttonlink sesbasic_icon_back')) ?>
</div>
<br />
<div class='clear sesbasic_admin_form'>
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<script type='text/javascript'>
  scriptJquery(document).on('change','input[type=radio][name=member_type]',function(){
    if (this.value == 0) {
      scriptJquery('#sescredit_specific_member-wrapper').hide();
      scriptJquery('#member_level-wrapper').hide();
    }else if (this.value == 1){
      scriptJquery('#sescredit_specific_member-wrapper').show();
      scriptJquery('#member_level-wrapper').hide();
    }
    else if (this.value == 2){
      scriptJquery('#sescredit_specific_member-wrapper').hide();
      scriptJquery('#member_level-wrapper').show();
    }
  });
  scriptJquery(document).on('change','input[type=radio][name=send_email]',function(){
    if (this.value == 0) {
      scriptJquery('#email_message-wrapper').hide();
    }else{
      scriptJquery('#email_message-wrapper').show();
    }
    
  });
  scriptJquery(document).ready(function() {
    var valueStyle = scriptJquery('input[name=member_type]:checked').val();
    if (valueStyle == 0) {
      scriptJquery('#sescredit_specific_member-wrapper').hide();
      scriptJquery('#member_level-wrapper').hide();
    }else if (valueStyle == 1){
      scriptJquery('#sescredit_specific_member-wrapper').show();
      scriptJquery('#member_level-wrapper').hide();
    }
    else if (valueStyle == 2){
      scriptJquery('#sescredit_specific_member-wrapper').hide();
      scriptJquery('#member_level-wrapper').show();
    }
    var valueStyle = scriptJquery('input[name=send_email]:checked').val();
    if(valueStyle == 0) {
      scriptJquery('#email_message-wrapper').hide();
    }
    else {
      scriptJquery('#email_message-wrapper').show();
    }
  });
  
  en4.core.runonce.add(function() {
    formObj = scriptJquery('#filter_form').find('div').find('div').find('div');
    AutocompleterRequestJSON('sescredit_specific_member', "<?php echo $this->url(array('module' =>'sescredit','controller' => 'credits', 'action' => 'get-all-members'),'admin_default',true); ?>", function(selecteditem) {
      scriptJquery('#sescredit_user_id').val(selecteditem.id);
    })
  });
</script>
