<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: add-new-user.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     Jung
 */
?>
<?php echo $this->partial('_admin_breadcrumb.tpl', 'core', array('parentMenu' => "core_admin_main_manage", 'parentMenuItemName' => 'core_admin_main_manage_members', 'lastMenuItemName' => 'Add New User')); ?>

<?php echo $this->partial('_jsSwitch.tpl', 'fields', array()); ?>
<div class='clear'>
  <div class='settings admin_user_edit_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<style>
#signup_account_form #name-wrapper{
  display: none;
}
</style>

<script type="text/javascript">

  scriptJquery(document).on('click','.copy_password',function (e) {
    if(scriptJquery('#signup_password').val().length) {
      scriptJquery("<textarea/>").appendTo("body").val(scriptJquery('#signup_password').val()).select().each(function () {
        document.execCommand('copy');
      }).remove();
      showSuccessTooltip(10,10,'<i class="fas fa-check-circle"></i><span>'+('<?php echo $this->translate("Password copied successfully."); ?>')+'</span>', 'password_copied');
    }
  });
    
  function showSuccessTooltip(x, y, contents, className) {
    if(scriptJquery('.core_notification').length > 0)
      scriptJquery('.core_notification').hide();
      scriptJquery('<div class="core_success_notification '+className+'">' + contents + '</div>').css( {
      display: 'block',
    }).appendTo("body").fadeOut(5000,'',function(){
      scriptJquery(this).remove();
    });
  }

  function passwordRoutine(value) {
    var pswd = value;
    // valid length
    if ( pswd.length < 6) {
      scriptJquery('#passwordroutine_length').removeClass('valid').addClass('invalid');
    } else {
      scriptJquery('#passwordroutine_length').removeClass('invalid').addClass('valid');
    }

    //validate special character
    if ( pswd.match(/[#?!@$%^&*-]/) ) {
        if ( pswd.match(/[\\\\:\/]/) ) {
            scriptJquery('#passwordroutine_specialcharacters').removeClass('valid').addClass('invalid');
        } else {
            scriptJquery('#passwordroutine_specialcharacters').removeClass('invalid').addClass('valid');
        }
    } else {
        scriptJquery('#passwordroutine_specialcharacters').removeClass('valid').addClass('invalid');
    }

    //validate capital letter
    if ( pswd.match(/[A-Z]/) ) {
        scriptJquery('#passwordroutine_capital').removeClass('invalid').addClass('valid');
    } else {
        scriptJquery('#passwordroutine_capital').removeClass('valid').addClass('invalid');
    }

    //validate small letter
    if ( pswd.match(/[a-z]/) ) {
        scriptJquery('#passwordroutine_lowerLetter').removeClass('invalid').addClass('valid');
    } else {
        scriptJquery('#passwordroutine_lowerLetter').removeClass('valid').addClass('invalid');
    }

    //validate number
    if ( pswd.match(/\d{1}/) ) {
        scriptJquery('#passwordroutine_number').removeClass('invalid').addClass('valid');
    } else {
        scriptJquery('#passwordroutine_number').removeClass('valid').addClass('invalid');
    }
    
    if(scriptJquery('#signup_password').val().length) {
      scriptJquery('.copy_password').show();
      scriptJquery('#fieldset-password_settings_group').removeClass('password_hide');
    } else {
      scriptJquery('.copy_password').hide();
      scriptJquery('#fieldset-password_settings_group').addClass('password_hide');
    }
  }

  scriptJquery('.core_admin_main_manage').parent().addClass('active');
  scriptJquery('.core_admin_main_manage_members').addClass('active');
</script>
