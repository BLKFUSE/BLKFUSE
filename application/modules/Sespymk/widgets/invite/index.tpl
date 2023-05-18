<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sespymk
 * @package    Sespymk
 * @copyright  Copyright 2016-2017 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2017-03-03 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sespymk/externals/styles/styles.css'); ?>

<div id="invite_form" class="sesbasic_bxs sespymk_invite_form prelative">
  <?php echo $this->form->render($this) ?>
  <div class="sesbasic_loading_cont_overlay" id="sespymk_loading_cont_overlay"></div>
</div>

<script type="application/javascript">

  function validateEmail(email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( email );
  }

  scriptJquery(document).ready(function(){
    scriptJquery('#sespymk_invite').submit(function(e){
      e.preventDefault();
      
      var subscribe_email = scriptJquery('#recipients').val();

      if(subscribe_email.length <= 0) {
        alert('Please enter valid email address.');
        return;
      }
      var str_array = subscribe_email.split(',');

      for(var i = 0; i < str_array.length; i++) {
        // Trim the excess whitespace.
        str_array[i] = str_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
        // Add additional code here, such as:
        if(validateEmail(str_array[i])) {
      
        } else {
          alert('Please enter valid email address.');
          return;
        }
      }
			document.getElementById('sespymk_loading_cont_overlay').style.display='block';
      invitepeoplesespymk = scriptJquery.ajax({
        dataType: 'html',
        method: 'post',
        'url': en4.core.baseUrl + 'sespymk/index/invite/',
        'data': {
          format: 'html',    
          params : scriptJquery(this).serialize(), 
          is_ajax : 1,
        },
        success: function(responseHTML) {
          var response = scriptJquery.parseJSON( responseHTML );
          if(response.emails_sent) {
						document.getElementById('sespymk_loading_cont_overlay').style.display='none';
            scriptJquery('#sespymk_invite').fadeOut("slow", function(){
              scriptJquery('#sespymk_invite').remove();
            });
            document.getElementById('invite_form').innerHTML = "<div class='sespymk_invite_success_message'><span>Invitations Sent.</span></div>";
          } else {
						document.getElementById('sespymk_loading_cont_overlay').style.display='none';
            scriptJquery('#sespymk_invite').fadeOut("slow", function(){
              scriptJquery('#sespymk_invite').remove();
            });
            document.getElementById('invite_form').innerHTML = "<div class='sespymk_invite_success_message'><span>Invitations Sent.</span></div>";
          }
        }
      });
      return false;
    });
  });
</script>  
