<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: delete.tpl 10003 2013-03-26 22:48:26Z john $
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
			<?php if( $this->isSuperAdmin ):?>
				<div class="tip">
					<span>
						<?php echo $this->translate('Super Admins can\'t be deleted.'); ?>
					</span>
				</div>
			<?php return; endif; ?>
			<div class="user_setting_global_form user_delete_page">
				<?php echo $this->form->setAttrib('id', 'user_form_settings_delete')->render($this) ?>
			</div>
    </div>
  </div>



</div>


<script type="text/javascript">
	scriptJquery("#send").click(function(e){
		e.preventDefault();
		let url = new URL(window.location.href);
		url.searchParams.set('code', 1);
		window.location.href = url.toString();
	});
</script>