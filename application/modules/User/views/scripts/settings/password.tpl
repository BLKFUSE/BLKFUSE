<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: password.tpl 9869 2013-02-12 22:37:42Z shaun $
 * @author     Steve
 */
?>
<div class="generic_layout_container layout_top">
  <div class="generic_layout_container layout_middle">
    <?php echo $this->content()->renderWidget('user.user-setting-cover-photo'); ?>
  </div>
</div>
<div class="generic_layout_container layout_main user_setting_main_page_main">
  <div class="generic_layout_container layout_left">
    <div class="theiaStickySidebar">
      <?php echo $this->content()->renderWidget('user.settings-menu'); ?>
    </div>
  </div>
  <div class="generic_layout_container layout_middle user_setting_main_middle">
    <div class="theiaStickySidebar">
      <div class="user_setting_global_form">
        <?php if(!empty($_SESSION['requirepassword'] )){ ?>
          <div class="require_password">
            <?php echo $this->content()->renderWidget('core.menu-logo',array('disableLink'=>true)); ?>
            <?php echo $this->form->render($this) ?>
          </div>
        <?php }else{ ?>
        <?php echo $this->form->render($this) ?>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<script>
  function passwordRoutine(value){
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
  }
</script>
