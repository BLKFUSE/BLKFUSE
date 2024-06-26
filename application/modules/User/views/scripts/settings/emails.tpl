<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: emails.tpl 9871 2013-02-12 22:47:33Z shaun $
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
      <div class="user_setting_global_form user_setting_notification_form">
        <?php echo $this->form->render($this) ?>
      </div>
      <script type="application/javascript">
        <?php if(!empty($this->user->disable_email)) { ?>
          scriptJquery(document).ready(function() {
          scriptJquery('.email_settings').attr('disabled', true);
          });
        <?php } ?>
        function disableEmail(value) {
          if(value.checked == true) {
              scriptJquery('.email_settings').attr('disabled', true);
          } else {
              scriptJquery('.email_settings').attr('disabled', false);
          }
        }
      </script>
    </div>
  </div>
</div>
