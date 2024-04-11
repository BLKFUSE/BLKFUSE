<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: general.tpl 9874 2013-02-13 00:48:05Z shaun $
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
        <div class="global_form">
          <?php if ($this->form->saveSuccessful): ?>
            <h3><?php echo $this->translate('Settings were successfully saved.');?></h3>
          <?php endif; ?>
          <?php echo $this->form->render($this) ?>
        </div>
      </div>
      <?php if( Zend_Controller_Front::getInstance()->getRequest()->getParam('format') == 'html' ): ?>
        <script type="text/javascript">
          en4.core.runonce.add(function(){
            var req = new Form.Request($$('.global_form')[0], $('global_content'), {
              requestOptions : {
                url : '<?php echo $this->url(array()) ?>'
              },
              extraData : {
                format : 'html'
              }
            });
          });
        </script>{"route":"user_extended","module":"user","controller":"settings","action":"general"}
      <?php endif; ?>
      <?php $url = $this->url(array('module' => 'user', 'controller' => 'settings','action' => 'edit-email', 'param' => 1), 'user_extended', true); ?>
      <script type="text/javascript">
        scriptJquery(document).ready(function(){
          var editEmail = '<a href="<?php echo $url; ?>"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?php echo $this->translate("Edit Email Address"); ?>" class="smoothbox edit_email_setting"  ><?php echo $this->translate('<i class="fa fa-pencil-alt"></i>'); ?></a>';
          scriptJquery('#email-element').after(editEmail);
        });
      </script>
    </div>
  </div>
</div>
