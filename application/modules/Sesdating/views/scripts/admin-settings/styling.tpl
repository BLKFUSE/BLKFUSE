<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: styling.tpl 2016-11-22 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>

<?php $settings = Engine_Api::_()->getApi('settings', 'core');
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/ses-scripts/jscolor/jscolor.js');
?>

<script>
hashSign = '#';
</script>
<?php include APPLICATION_PATH .  '/application/modules/Sesdating/views/scripts/dismiss_message.tpl';?>
<div class='clear'>
  <div class='settings sescore_admin_form sesdating_themes_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<script>

  scriptJquery(document).ready(function() {
    changeThemeColor("<?php echo Engine_Api::_()->sesdating()->getContantValueXML('theme_color'); ?>", '');
  });
  
  function changeCustomThemeColor(value) {

    if(value > 13) {
      var URL = en4.core.staticBaseUrl+'sesdating/admin-settings/getcustomthemecolors/';
      (scriptJquery.ajax({
          method: 'post',
          url: URL ,
          dataType: 'html',
          'data': {
            format: 'html',
            customtheme_id: value,
          },
          success: function(responseHTML) {
          var customthevalyearray = scriptJquery.parseJSON(responseHTML);
          
          for(i=0;i<customthevalyearray.length;i++){
            var splitValue = customthevalyearray[i].split('||');
            scriptJquery('#'+splitValue[0]).val(splitValue[1]);
          }
        }
      }));
    }
    changeThemeColor(value, 'custom');
  }

	function changeThemeColor(value, custom) {

	  if(custom == '' && (value == 1 || value == 2 || value == 3 || value == 4 || value == 6 || value == 7 || value == 8 || value == 9 || value == 10 || value == 11 || value == 12 || value == 13)) {
	    if(document.getElementById('common_settings-wrapper'))
				document.getElementById('common_settings-wrapper').style.display = 'none';
		  if(document.getElementById('header_settings-wrapper'))
				document.getElementById('header_settings-wrapper').style.display = 'none';
	    if(document.getElementById('footer_settings-wrapper'))
				document.getElementById('footer_settings-wrapper').style.display = 'none';
		  if(document.getElementById('body_settings-wrapper'))
				document.getElementById('body_settings-wrapper').style.display = 'none';
		  if(document.getElementById('general_settings_group'))
			  document.getElementById('general_settings_group').style.display = 'none';
			if(document.getElementById('header_settings_group'))
			  document.getElementById('header_settings_group').style.display = 'none';
			if(document.getElementById('footer_settings_group'))
			  document.getElementById('footer_settings_group').style.display = 'none';
			if(document.getElementById('body_settings_group'))
			  document.getElementById('body_settings_group').style.display = 'none';
	    if(document.getElementById('custom_theme_color-wrapper'))
				document.getElementById('custom_theme_color-wrapper').style.display = 'none';
      if(document.getElementById('custom_themes'))
				document.getElementById('custom_themes').style.display = 'none';
      if(document.getElementById('edit_custom_themes'))
        document.getElementById('edit_custom_themes').style.display = 'none';
      if(document.getElementById('delete_custom_themes'))
        document.getElementById('delete_custom_themes').style.display = 'none';
      if(document.getElementById('deletedisabled_custom_themes'))
        document.getElementById('deletedisabled_custom_themes').style.display = 'none';
      if(document.getElementById('submit'))
        document.getElementById('submit').style.display = 'none';
	  } else if(custom == '' && value == 5) {
	    
	    if(document.getElementById('custom_theme_color-wrapper'))
				document.getElementById('custom_theme_color-wrapper').style.display = 'block';
      if(document.getElementById('custom_themes'))
				document.getElementById('custom_themes').style.display = 'block';
      <?php if($this->customtheme_id): ?>
        //value = '<?php echo $this->customtheme_id; ?>';
        changeCustomThemeColor('<?php echo $this->customtheme_id; ?>');
      <?php else: ?>
        changeCustomThemeColor(5);
      <?php endif; ?>
		 // changeCustomThemeColor(5);
	  } else if(custom == 'custom') {
		  if(document.getElementById('common_settings-wrapper'))
				document.getElementById('common_settings-wrapper').style.display = 'block';
		  if(document.getElementById('header_settings-wrapper'))
				document.getElementById('header_settings-wrapper').style.display = 'block';
	    if(document.getElementById('footer_settings-wrapper'))
				document.getElementById('footer_settings-wrapper').style.display = 'block';
			if(document.getElementById('body_settings-wrapper'))
				document.getElementById('body_settings-wrapper').style.display = 'block';
		  if(document.getElementById('general_settings_group'))
			  document.getElementById('general_settings_group').style.display = 'block';
			if(document.getElementById('header_settings_group'))
			  document.getElementById('header_settings_group').style.display = 'block';
			if(document.getElementById('footer_settings_group'))
			  document.getElementById('footer_settings_group').style.display = 'block';
			if(document.getElementById('body_settings_group'))
			  document.getElementById('body_settings_group').style.display = 'block';
			  
      if(document.getElementById('custom_theme_color').value > 13) {
        if(document.getElementById('submit'))
          document.getElementById('submit').style.display = 'inline-block';
        if(document.getElementById('edit_custom_themes'))
          document.getElementById('edit_custom_themes').style.display = 'block';
        if(document.getElementById('delete_custom_themes'))
          document.getElementById('delete_custom_themes').style.display = 'block';

        <?php if(empty($this->customtheme_id)): ?>
          history.pushState(null, null, 'admin/sesdating/settings/styling/customtheme_id/'+document.getElementById('custom_theme_color').value);
          scriptJquery('#edit_custom_themes').attr('href', 'sesdating/admin-settings/add-custom-theme/customtheme_id/'+document.getElementById('custom_theme_color').value);

          scriptJquery('#delete_custom_themes').attr('href', 'sesdating/admin-settings/delete-custom-theme/customtheme_id/'+document.getElementById('custom_theme_color').value);
          //window.location.href = 'admin/sesdating/settings/styling/customtheme_id/'+document.getElementById('custom_theme_color').value;
        <?php else: ?>
          scriptJquery('#edit_custom_themes').attr('href', 'sesdating/admin-settings/add-custom-theme/customtheme_id/'+document.getElementById('custom_theme_color').value);
          
          var activatedTheme = '<?php echo $this->activatedTheme; ?>';
          if(activatedTheme == document.getElementById('custom_theme_color').value) {
            document.getElementById('delete_custom_themes').style.display = 'none';
            document.getElementById('deletedisabled_custom_themes').style.display = 'block';
          } else {
            if(document.getElementById('deletedisabled_custom_themes'))
              document.getElementById('deletedisabled_custom_themes').style.display = 'none';
            scriptJquery('#delete_custom_themes').attr('href', 'sesdating/admin-settings/delete-custom-theme/customtheme_id/'+document.getElementById('custom_theme_color').value);
          }
        <?php endif; ?>
      } else {
        if(document.getElementById('edit_custom_themes'))
          document.getElementById('edit_custom_themes').style.display = 'none';
        if(document.getElementById('delete_custom_themes'))
          document.getElementById('delete_custom_themes').style.display = 'none';
        if(document.getElementById('deletedisabled_custom_themes'))
          document.getElementById('deletedisabled_custom_themes').style.display = 'none';
        if(document.getElementById('submit'))
          document.getElementById('submit').style.display = 'none';
      }
	  }

 if(value == 1) {
			//Theme Base Styling
			if(document.getElementById('sesdating_theme_color')) {
				document.getElementById('sesdating_theme_color').value = '#bf3f34';
			}
			//Theme Base Styling
			
			//Body Styling
			if(document.getElementById('sesdating_body_background_color')) {
				document.getElementById('sesdating_body_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_font_color')) {
				document.getElementById('sesdating_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_font_color_light')) {
				document.getElementById('sesdating_font_color_light').value = '#999';
			}
			
			if(document.getElementById('sesdating_heading_color')) {
				document.getElementById('sesdating_heading_color').value = '#243238';
			}
			if(document.getElementById('sesdating_links_color')) {
				document.getElementById('sesdating_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_links_hover_color')) {
				document.getElementById('sesdating_links_hover_color').value = '#bf3f34';
			}
			if(document.getElementById('sesdating_content_header_background_color')) {
				document.getElementById('sesdating_content_header_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_content_header_font_color')) {
				document.getElementById('sesdating_content_header_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_content_background_color')) {
				document.getElementById('sesdating_content_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_content_border_color')) {
				document.getElementById('sesdating_content_border_color').value = '#ebecee';
			}
			if(document.getElementById('sesdating_form_label_color')) {
				document.getElementById('sesdating_form_label_color').value = '#243238';
			}
			if(document.getElementById('sesdating_input_background_color')) {
				document.getElementById('sesdating_input_background_color').value = '#ffffff';
			}
			if(document.getElementById('sesdating_input_font_color')) {
				document.getElementById('sesdating_input_font_color').value = '#6D6D6D';
			}
			if(document.getElementById('sesdating_input_border_color')) {
				document.getElementById('sesdating_input_border_color').value = '#CACACA';
			}
			if(document.getElementById('sesdating_button_background_color')) {
				document.getElementById('sesdating_button_background_color').value = '#bf3f34';
			}
			if(document.getElementById('sesdating_button_background_color_hover')) {
				document.getElementById('sesdating_button_background_color_hover').value = '#243238';
			}
			if(document.getElementById('sesdating_button_font_color')) {
				document.getElementById('sesdating_button_font_color').value = '#ffffff';
			}
			if(document.getElementById('sesdating_button_font_hover_color')) {
				document.getElementById('sesdating_button_font_hover_color').value = '#fff';
			}
			if(document.getElementById('sesdating_comment_background_color')) {
				document.getElementById('sesdating_comment_background_color').value = '#fdfdfd';
			}
			//Body Styling
			
			//Header Styling
			if(document.getElementById('sesdating_header_background_color')) {
				document.getElementById('sesdating_header_background_color').value = '#bf3f34';
			}
			if(document.getElementById('sesdating_menu_logo_font_color')) {
				document.getElementById('sesdating_menu_logo_font_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_background_color')) {
				document.getElementById('sesdating_mainmenu_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_links_color')) {
				document.getElementById('sesdating_mainmenu_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_mainmenu_links_hover_color')) {
				document.getElementById('sesdating_mainmenu_links_hover_color').value = '#bf3f34';
			}
			if(document.getElementById('sesdating_minimenu_links_color')) {
				document.getElementById('sesdating_minimenu_links_color').value = '#bf3f34';
			}
			if(document.getElementById('sesdating_minimenu_links_hover_color')) {
				document.getElementById('sesdating_minimenu_links_hover_color').value = '#243238';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_color')) {
				document.getElementById('sesdating_minimenu_icon_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_active_color')) {
				document.getElementById('sesdating_minimenu_icon_background_active_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_minimenu_icon_color')) {
				document.getElementById('sesdating_minimenu_icon_color').value = '#bf3f34';
			}
			if(document.getElementById('sesdating_minimenu_icon_active_color')) {
				document.getElementById('sesdating_minimenu_icon_active_color').value = '#243238';
			}
			if(document.getElementById('sesdating_header_searchbox_background_color')) {
				document.getElementById('sesdating_header_searchbox_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_header_searchbox_text_color')) {
				document.getElementById('sesdating_header_searchbox_text_color').value = '#fff';
			}
			
			//Top Panel Color
			if(document.getElementById('sesdating_toppanel_userinfo_background_color')) {
				document.getElementById('sesdating_toppanel_userinfo_background_color').value = '#bf3f34';
			}
			if(document.getElementById('sesdating_toppanel_userinfo_font_color')) {
				document.getElementById('sesdating_toppanel_userinfo_font_color').value = '#FFFFFF';
			}
			//Top Panel Color
			
			//Login Popup Styling
			if(document.getElementById('sesdating_login_popup_header_background_color')) {
				document.getElementById('sesdating_login_popup_header_background_color').value = '#bf3f34';
			}
			if(document.getElementById('sesdating_login_popup_header_font_color')) {
				document.getElementById('sesdating_login_popup_header_font_color').value = '#fff';
			}
			//Login Pop up Styling
			//Header Styling
			
			//Footer Styling
			if(document.getElementById('sesdating_footer_background_color')) {
				document.getElementById('sesdating_footer_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_footer_heading_color')) {
				document.getElementById('sesdating_footer_heading_color').value = '#243238';
			}
			if(document.getElementById('sesdating_footer_links_color')) {
				document.getElementById('sesdating_footer_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_footer_links_hover_color')) {
				document.getElementById('sesdating_footer_links_hover_color').value = '#bf3f34';
			}
			if(document.getElementById('sesdating_footer_border_color')) {
				document.getElementById('sesdating_footer_border_color').value = '#ddd';
			}
			//Footer Styling
    }
		 else if(value == 2) {
			//Theme Base Styling
			if(document.getElementById('sesdating_theme_color')) {
				document.getElementById('sesdating_theme_color').value = '#0296C0';
			}
			//Theme Base Styling
			
			//Body Styling
			if(document.getElementById('sesdating_body_background_color')) {
				document.getElementById('sesdating_body_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_font_color')) {
				document.getElementById('sesdating_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_font_color_light')) {
				document.getElementById('sesdating_font_color_light').value = '#999';
			}
			
			if(document.getElementById('sesdating_heading_color')) {
				document.getElementById('sesdating_heading_color').value = '#243238';
			}
			if(document.getElementById('sesdating_links_color')) {
				document.getElementById('sesdating_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_links_hover_color')) {
				document.getElementById('sesdating_links_hover_color').value = '#0296C0';
			}
			if(document.getElementById('sesdating_content_header_background_color')) {
				document.getElementById('sesdating_content_header_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_content_header_font_color')) {
				document.getElementById('sesdating_content_header_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_content_background_color')) {
				document.getElementById('sesdating_content_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_content_border_color')) {
				document.getElementById('sesdating_content_border_color').value = '#ebecee';
			}
			if(document.getElementById('sesdating_form_label_color')) {
				document.getElementById('sesdating_form_label_color').value = '#243238';
			}
			if(document.getElementById('sesdating_input_background_color')) {
				document.getElementById('sesdating_input_background_color').value = '#ffffff';
			}
			if(document.getElementById('sesdating_input_font_color')) {
				document.getElementById('sesdating_input_font_color').value = '#6D6D6D';
			}
			if(document.getElementById('sesdating_input_border_color')) {
				document.getElementById('sesdating_input_border_color').value = '#CACACA';
			}
			if(document.getElementById('sesdating_button_background_color')) {
				document.getElementById('sesdating_button_background_color').value = '#ED0058';
			}
			if(document.getElementById('sesdating_button_background_color_hover')) {
				document.getElementById('sesdating_button_background_color_hover').value = '#0296C0';
			}
			if(document.getElementById('sesdating_button_font_color')) {
				document.getElementById('sesdating_button_font_color').value = '#ffffff';
			}
			if(document.getElementById('sesdating_button_font_hover_color')) {
				document.getElementById('sesdating_button_font_hover_color').value = '#fff';
			}
			if(document.getElementById('sesdating_comment_background_color')) {
				document.getElementById('sesdating_comment_background_color').value = '#fdfdfd';
			}
			//Body Styling
			
					//Header Styling
			if(document.getElementById('sesdating_header_background_color')) {
				document.getElementById('sesdating_header_background_color').value = '#0296C0';
			}
			if(document.getElementById('sesdating_menu_logo_font_color')) {
				document.getElementById('sesdating_menu_logo_font_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_background_color')) {
				document.getElementById('sesdating_mainmenu_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_links_color')) {
				document.getElementById('sesdating_mainmenu_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_mainmenu_links_hover_color')) {
				document.getElementById('sesdating_mainmenu_links_hover_color').value = '#0296C0';
			}
			if(document.getElementById('sesdating_minimenu_links_color')) {
				document.getElementById('sesdating_minimenu_links_color').value = '#0296C0';
			}
			if(document.getElementById('sesdating_minimenu_links_hover_color')) {
				document.getElementById('sesdating_minimenu_links_hover_color').value = '#243238';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_color')) {
				document.getElementById('sesdating_minimenu_icon_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_active_color')) {
				document.getElementById('sesdating_minimenu_icon_background_active_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_minimenu_icon_color')) {
				document.getElementById('sesdating_minimenu_icon_color').value = '#0296C0';
			}
			if(document.getElementById('sesdating_minimenu_icon_active_color')) {
				document.getElementById('sesdating_minimenu_icon_active_color').value = '#243238';
			}
			if(document.getElementById('sesdating_header_searchbox_background_color')) {
				document.getElementById('sesdating_header_searchbox_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_header_searchbox_text_color')) {
				document.getElementById('sesdating_header_searchbox_text_color').value = '#fff';
			}
			
			//Top Panel Color
			if(document.getElementById('sesdating_toppanel_userinfo_background_color')) {
				document.getElementById('sesdating_toppanel_userinfo_background_color').value = '#0296C0';
			}
			if(document.getElementById('sesdating_toppanel_userinfo_font_color')) {
				document.getElementById('sesdating_toppanel_userinfo_font_color').value = '#FFFFFF';
			}
			//Top Panel Color
			
			//Login Popup Styling
			if(document.getElementById('sesdating_login_popup_header_background_color')) {
				document.getElementById('sesdating_login_popup_header_background_color').value = '#0296C0';
			}
			if(document.getElementById('sesdating_login_popup_header_font_color')) {
				document.getElementById('sesdating_login_popup_header_font_color').value = '#fff';
			}
			//Login Pop up Styling
			//Header Styling
			
			//Footer Styling
			if(document.getElementById('sesdating_footer_background_color')) {
				document.getElementById('sesdating_footer_background_color').value = '#000627';
			}
			if(document.getElementById('sesdating_footer_heading_color')) {
				document.getElementById('sesdating_footer_heading_color').value = '#243238';
			}
			if(document.getElementById('sesdating_footer_links_color')) {
				document.getElementById('sesdating_footer_links_color').value = '#fff';
			}
			if(document.getElementById('sesdating_footer_links_hover_color')) {
				document.getElementById('sesdating_footer_links_hover_color').value = '#0296C0';
			}
			if(document.getElementById('sesdating_footer_border_color')) {
				document.getElementById('sesdating_footer_border_color').value = '#ddd';
			}
			//Footer Styling
		} 
		else if(value == 3) {
			//Theme Base Styling
			if(document.getElementById('sesdating_theme_color')) {
				document.getElementById('sesdating_theme_color').value = '#d03e82';
			}
			//Theme Base Styling
			
	//Body Styling
			if(document.getElementById('sesdating_body_background_color')) {
				document.getElementById('sesdating_body_background_color').value = '#101419';
			}
			if(document.getElementById('sesdating_font_color')) {
				document.getElementById('sesdating_font_color').value = '#CCCCCC';
			}
			if(document.getElementById('sesdating_font_color_light')) {
				document.getElementById('sesdating_font_color_light').value = '#fff';
			}
			
			if(document.getElementById('sesdating_heading_color')) {
				document.getElementById('sesdating_heading_color').value = '#b1b1b1';
			}
			if(document.getElementById('sesdating_links_color')) {
				document.getElementById('sesdating_links_color').value = '#fff';
			}
			if(document.getElementById('sesdating_links_hover_color')) {
				document.getElementById('sesdating_links_hover_color').value = '#d03e82';
			}
			if(document.getElementById('sesdating_content_header_background_color')) {
				document.getElementById('sesdating_content_header_background_color').value = '#1D2632';
			}
			if(document.getElementById('sesdating_content_header_font_color')) {
				document.getElementById('sesdating_content_header_font_color').value = '#b1b1b1';
			}
			if(document.getElementById('sesdating_content_background_color')) {
				document.getElementById('sesdating_content_background_color').value = '#1D2632';
			}
			if(document.getElementById('sesdating_content_border_color')) {
				document.getElementById('sesdating_content_border_color').value = '#334354';
			}
			if(document.getElementById('sesdating_form_label_color')) {
				document.getElementById('sesdating_form_label_color').value = '#CCCCCC';
			}
			if(document.getElementById('sesdating_input_background_color')) {
				document.getElementById('sesdating_input_background_color').value = '#CCCCCC';
			}
			if(document.getElementById('sesdating_input_font_color')) {
				document.getElementById('sesdating_input_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_input_border_color')) {
				document.getElementById('sesdating_input_border_color').value = '#CACACA';
			}
			if(document.getElementById('sesdating_button_background_color')) {
				document.getElementById('sesdating_button_background_color').value = '#d03e82';
			}
			if(document.getElementById('sesdating_button_background_color_hover')) {
				document.getElementById('sesdating_button_background_color_hover').value = '#fff';
			}
			if(document.getElementById('sesdating_button_font_color')) {
				document.getElementById('sesdating_button_font_color').value = '#fff';
			}
			if(document.getElementById('sesdating_button_font_hover_color')) {
				document.getElementById('sesdating_button_font_hover_color').value = '#243238';
			}
			if(document.getElementById('sesdating_comment_background_color')) {
				document.getElementById('sesdating_comment_background_color').value = '#1D2632';
			}
			
		//Header Styling
			if(document.getElementById('sesdating_header_background_color')) {
				document.getElementById('sesdating_header_background_color').value = '#1D2632';
			}
			if(document.getElementById('sesdating_menu_logo_font_color')) {
				document.getElementById('sesdating_menu_logo_font_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_background_color')) {
				document.getElementById('sesdating_mainmenu_background_color').value = '#1D2632';
			}
			if(document.getElementById('sesdating_mainmenu_links_color')) {
				document.getElementById('sesdating_mainmenu_links_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_links_hover_color')) {
				document.getElementById('sesdating_mainmenu_links_hover_color').value = '#d03e82';
			}
			if(document.getElementById('sesdating_minimenu_links_color')) {
				document.getElementById('sesdating_minimenu_links_color').value = '#d03e82';
			}
			if(document.getElementById('sesdating_minimenu_links_hover_color')) {
				document.getElementById('sesdating_minimenu_links_hover_color').value = '#fff';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_color')) {
				document.getElementById('sesdating_minimenu_icon_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_active_color')) {
				document.getElementById('sesdating_minimenu_icon_background_active_color').value = '#FFF';
			}
			if(document.getElementById('sesdating_minimenu_icon_color')) {
				document.getElementById('sesdating_minimenu_icon_color').value = '#d03e82';
			}
			if(document.getElementById('sesdating_minimenu_icon_active_color')) {
				document.getElementById('sesdating_minimenu_icon_active_color').value = '#d03e82';
			}
			if(document.getElementById('sesdating_header_searchbox_background_color')) {
				document.getElementById('sesdating_header_searchbox_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_header_searchbox_text_color')) {
				document.getElementById('sesdating_header_searchbox_text_color').value = '#fff';
			}
			//Top Panel Color
			if(document.getElementById('sesdating_toppanel_userinfo_background_color')) {
				document.getElementById('sesdating_toppanel_userinfo_background_color').value = '#d03e82';
			}
			if(document.getElementById('sesdating_toppanel_userinfo_font_color')) {
				document.getElementById('sesdating_toppanel_userinfo_font_color').value = '#FFFFFF';
			}
			//Top Panel Color
			
			//Login Popup Styling
			if(document.getElementById('sesdating_login_popup_header_background_color')) {
				document.getElementById('sesdating_login_popup_header_background_color').value = '#d03e82';
			}
			if(document.getElementById('sesdating_login_popup_header_font_color')) {
				document.getElementById('sesdating_login_popup_header_font_color').value = '#fff';
			}
			//Login Pop up Styling
			//Header Styling
			
			//Footer Styling
			if(document.getElementById('sesdating_footer_background_color')) {
				document.getElementById('sesdating_footer_background_color').value = '#26323F';
			}
			if(document.getElementById('sesdating_footer_heading_color')) {
				document.getElementById('sesdating_footer_heading_color').value = '#B3B3B3';
			}
			if(document.getElementById('sesdating_footer_links_color')) {
				document.getElementById('sesdating_footer_links_color').value = '#B3B3B3';
			}
			if(document.getElementById('sesdating_footer_links_hover_color')) {
				document.getElementById('sesdating_footer_links_hover_color').value = '#d03e82';
			}
			if(document.getElementById('sesdating_footer_border_color')) {
				document.getElementById('sesdating_footer_border_color').value = '#B3B3B3';
			}
			//Footer Styling

		}
		else if(value == 4) {
			//Theme Base Styling
			if(document.getElementById('sesdating_theme_color')) {
				document.getElementById('sesdating_theme_color').value = '#7155f9';
			}
			//Theme Base Styling
			
			//Body Styling
			if(document.getElementById('sesdating_body_background_color')) {
				document.getElementById('sesdating_body_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_font_color')) {
				document.getElementById('sesdating_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_font_color_light')) {
				document.getElementById('sesdating_font_color_light').value = '#999';
			}
			
			if(document.getElementById('sesdating_heading_color')) {
				document.getElementById('sesdating_heading_color').value = '#243238';
			}
			if(document.getElementById('sesdating_links_color')) {
				document.getElementById('sesdating_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_links_hover_color')) {
				document.getElementById('sesdating_links_hover_color').value = '#7155f9';
			}
			if(document.getElementById('sesdating_content_header_background_color')) {
				document.getElementById('sesdating_content_header_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_content_header_font_color')) {
				document.getElementById('sesdating_content_header_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_content_background_color')) {
				document.getElementById('sesdating_content_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_content_border_color')) {
				document.getElementById('sesdating_content_border_color').value = '#ebecee';
			}
			if(document.getElementById('sesdating_form_label_color')) {
				document.getElementById('sesdating_form_label_color').value = '#243238';
			}
			if(document.getElementById('sesdating_input_background_color')) {
				document.getElementById('sesdating_input_background_color').value = '#ffffff';
			}
			if(document.getElementById('sesdating_input_font_color')) {
				document.getElementById('sesdating_input_font_color').value = '#6D6D6D';
			}
			if(document.getElementById('sesdating_input_border_color')) {
				document.getElementById('sesdating_input_border_color').value = '#CACACA';
			}
			if(document.getElementById('sesdating_button_background_color')) {
				document.getElementById('sesdating_button_background_color').value = '#7155f9';
			}
			if(document.getElementById('sesdating_button_background_color_hover')) {
				document.getElementById('sesdating_button_background_color_hover').value = '#243238';
			}
			if(document.getElementById('sesdating_button_font_color')) {
				document.getElementById('sesdating_button_font_color').value = '#ffffff';
			}
			if(document.getElementById('sesdating_button_font_hover_color')) {
				document.getElementById('sesdating_button_font_hover_color').value = '#fff';
			}
			if(document.getElementById('sesdating_comment_background_color')) {
				document.getElementById('sesdating_comment_background_color').value = '#fdfdfd';
			}
			//Body Styling
			
								//Header Styling
			if(document.getElementById('sesdating_header_background_color')) {
				document.getElementById('sesdating_header_background_color').value = '#7155f9';
			}
			if(document.getElementById('sesdating_menu_logo_font_color')) {
				document.getElementById('sesdating_menu_logo_font_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_background_color')) {
				document.getElementById('sesdating_mainmenu_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_links_color')) {
				document.getElementById('sesdating_mainmenu_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_mainmenu_links_hover_color')) {
				document.getElementById('sesdating_mainmenu_links_hover_color').value = '#7155f9';
			}
			if(document.getElementById('sesdating_minimenu_links_color')) {
				document.getElementById('sesdating_minimenu_links_color').value = '#7155f9';
			}
			if(document.getElementById('sesdating_minimenu_links_hover_color')) {
				document.getElementById('sesdating_minimenu_links_hover_color').value = '#243238';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_color')) {
				document.getElementById('sesdating_minimenu_icon_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_active_color')) {
				document.getElementById('sesdating_minimenu_icon_background_active_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_minimenu_icon_color')) {
				document.getElementById('sesdating_minimenu_icon_color').value = '#7155f9';
			}
			if(document.getElementById('sesdating_minimenu_icon_active_color')) {
				document.getElementById('sesdating_minimenu_icon_active_color').value = '#243238';
			}
			if(document.getElementById('sesdating_header_searchbox_background_color')) {
				document.getElementById('sesdating_header_searchbox_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_header_searchbox_text_color')) {
				document.getElementById('sesdating_header_searchbox_text_color').value = '#fff';
			}
			
			//Top Panel Color
			if(document.getElementById('sesdating_toppanel_userinfo_background_color')) {
				document.getElementById('sesdating_toppanel_userinfo_background_color').value = '#7155f9';
			}
			if(document.getElementById('sesdating_toppanel_userinfo_font_color')) {
				document.getElementById('sesdating_toppanel_userinfo_font_color').value = '#FFFFFF';
			}
			//Top Panel Color
			
			//Login Popup Styling
			if(document.getElementById('sesdating_login_popup_header_background_color')) {
				document.getElementById('sesdating_login_popup_header_background_color').value = '#7155f9';
			}
			if(document.getElementById('sesdating_login_popup_header_font_color')) {
				document.getElementById('sesdating_login_popup_header_font_color').value = '#fff';
			}
			//Login Pop up Styling
			//Header Styling
			
			//Footer Styling
			if(document.getElementById('sesdating_footer_background_color')) {
				document.getElementById('sesdating_footer_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_footer_heading_color')) {
				document.getElementById('sesdating_footer_heading_color').value = '#243238';
			}
			if(document.getElementById('sesdating_footer_links_color')) {
				document.getElementById('sesdating_footer_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_footer_links_hover_color')) {
				document.getElementById('sesdating_footer_links_hover_color').value = '#7155f9';
			}
			if(document.getElementById('sesdating_footer_border_color')) {
				document.getElementById('sesdating_footer_border_color').value = '#ddd';
			}
			//Footer Styling
    }
 		else if(value == 6) {
			//Theme Base Styling
			if(document.getElementById('sesdating_theme_color')) {
				document.getElementById('sesdating_theme_color').value = '#03A9F4';
			}
			//Theme Base Styling
			
			//Body Styling
			if(document.getElementById('sesdating_body_background_color')) {
				document.getElementById('sesdating_body_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_font_color')) {
				document.getElementById('sesdating_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_font_color_light')) {
				document.getElementById('sesdating_font_color_light').value = '#999';
			}
			
			if(document.getElementById('sesdating_heading_color')) {
				document.getElementById('sesdating_heading_color').value = '#243238';
			}
			if(document.getElementById('sesdating_links_color')) {
				document.getElementById('sesdating_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_links_hover_color')) {
				document.getElementById('sesdating_links_hover_color').value = '#03A9F4';
			}
			if(document.getElementById('sesdating_content_header_background_color')) {
				document.getElementById('sesdating_content_header_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_content_header_font_color')) {
				document.getElementById('sesdating_content_header_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_content_background_color')) {
				document.getElementById('sesdating_content_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_content_border_color')) {
				document.getElementById('sesdating_content_border_color').value = '#ebecee';
			}
			if(document.getElementById('sesdating_form_label_color')) {
				document.getElementById('sesdating_form_label_color').value = '#243238';
			}
			if(document.getElementById('sesdating_input_background_color')) {
				document.getElementById('sesdating_input_background_color').value = '#ffffff';
			}
			if(document.getElementById('sesdating_input_font_color')) {
				document.getElementById('sesdating_input_font_color').value = '#6D6D6D';
			}
			if(document.getElementById('sesdating_input_border_color')) {
				document.getElementById('sesdating_input_border_color').value = '#CACACA';
			}
			if(document.getElementById('sesdating_button_background_color')) {
				document.getElementById('sesdating_button_background_color').value = '#03A9F4';
			}
			if(document.getElementById('sesdating_button_background_color_hover')) {
				document.getElementById('sesdating_button_background_color_hover').value = '#243238';
			}
			if(document.getElementById('sesdating_button_font_color')) {
				document.getElementById('sesdating_button_font_color').value = '#ffffff';
			}
			if(document.getElementById('sesdating_button_font_hover_color')) {
				document.getElementById('sesdating_button_font_hover_color').value = '#fff';
			}
			if(document.getElementById('sesdating_comment_background_color')) {
				document.getElementById('sesdating_comment_background_color').value = '#fdfdfd';
			}
			//Body Styling
			
			
			//Header Styling
			if(document.getElementById('sesdating_header_background_color')) {
				document.getElementById('sesdating_header_background_color').value = '#03A9F4';
			}
			if(document.getElementById('sesdating_menu_logo_font_color')) {
				document.getElementById('sesdating_menu_logo_font_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_background_color')) {
				document.getElementById('sesdating_mainmenu_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_links_color')) {
				document.getElementById('sesdating_mainmenu_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_mainmenu_links_hover_color')) {
				document.getElementById('sesdating_mainmenu_links_hover_color').value = '#03A9F4';
			}
			if(document.getElementById('sesdating_minimenu_links_color')) {
				document.getElementById('sesdating_minimenu_links_color').value = '#03A9F4';
			}
			if(document.getElementById('sesdating_minimenu_links_hover_color')) {
				document.getElementById('sesdating_minimenu_links_hover_color').value = '#243238';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_color')) {
				document.getElementById('sesdating_minimenu_icon_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_active_color')) {
				document.getElementById('sesdating_minimenu_icon_background_active_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_minimenu_icon_color')) {
				document.getElementById('sesdating_minimenu_icon_color').value = '#03A9F4';
			}
			if(document.getElementById('sesdating_minimenu_icon_active_color')) {
				document.getElementById('sesdating_minimenu_icon_active_color').value = '#243238';
			}
			if(document.getElementById('sesdating_header_searchbox_background_color')) {
				document.getElementById('sesdating_header_searchbox_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_header_searchbox_text_color')) {
				document.getElementById('sesdating_header_searchbox_text_color').value = '#fff';
			}
			
			//Top Panel Color
			if(document.getElementById('sesdating_toppanel_userinfo_background_color')) {
				document.getElementById('sesdating_toppanel_userinfo_background_color').value = '#03A9F4';
			}
			if(document.getElementById('sesdating_toppanel_userinfo_font_color')) {
				document.getElementById('sesdating_toppanel_userinfo_font_color').value = '#FFFFFF';
			}
			//Top Panel Color
			
			//Login Popup Styling
			if(document.getElementById('sesdating_login_popup_header_background_color')) {
				document.getElementById('sesdating_login_popup_header_background_color').value = '#03A9F4';
			}
			if(document.getElementById('sesdating_login_popup_header_font_color')) {
				document.getElementById('sesdating_login_popup_header_font_color').value = '#fff';
			}
			//Login Pop up Styling
			//Header Styling
			
			//Footer Styling
			if(document.getElementById('sesdating_footer_background_color')) {
				document.getElementById('sesdating_footer_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_footer_heading_color')) {
				document.getElementById('sesdating_footer_heading_color').value = '#243238';
			}
			if(document.getElementById('sesdating_footer_links_color')) {
				document.getElementById('sesdating_footer_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_footer_links_hover_color')) {
				document.getElementById('sesdating_footer_links_hover_color').value = '#03A9F4';
			}
			if(document.getElementById('sesdating_footer_border_color')) {
				document.getElementById('sesdating_footer_border_color').value = '#ddd';
			}
			//Footer Styling
    }
    else if(value == 7) {
			//Theme Base Styling
			if(document.getElementById('sesdating_theme_color')) {
				document.getElementById('sesdating_theme_color').value = '#FF5722';
			}
			//Theme Base Styling
			
			//Body Styling
			if(document.getElementById('sesdating_body_background_color')) {
				document.getElementById('sesdating_body_background_color').value = '#101419';
			}
			if(document.getElementById('sesdating_font_color')) {
				document.getElementById('sesdating_font_color').value = '#CCCCCC';
			}
			if(document.getElementById('sesdating_font_color_light')) {
				document.getElementById('sesdating_font_color_light').value = '#fff';
			}
			
			if(document.getElementById('sesdating_heading_color')) {
				document.getElementById('sesdating_heading_color').value = '#b1b1b1';
			}
			if(document.getElementById('sesdating_links_color')) {
				document.getElementById('sesdating_links_color').value = '#fff';
			}
			if(document.getElementById('sesdating_links_hover_color')) {
				document.getElementById('sesdating_links_hover_color').value = '#FF5722';
			}
			if(document.getElementById('sesdating_content_header_background_color')) {
				document.getElementById('sesdating_content_header_background_color').value = '#1D2632';
			}
			if(document.getElementById('sesdating_content_header_font_color')) {
				document.getElementById('sesdating_content_header_font_color').value = '#b1b1b1';
			}
			if(document.getElementById('sesdating_content_background_color')) {
				document.getElementById('sesdating_content_background_color').value = '#1D2632';
			}
			if(document.getElementById('sesdating_content_border_color')) {
				document.getElementById('sesdating_content_border_color').value = '#334354';
			}
			if(document.getElementById('sesdating_form_label_color')) {
				document.getElementById('sesdating_form_label_color').value = '#CCCCCC';
			}
			if(document.getElementById('sesdating_input_background_color')) {
				document.getElementById('sesdating_input_background_color').value = '#CCCCCC';
			}
			if(document.getElementById('sesdating_input_font_color')) {
				document.getElementById('sesdating_input_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_input_border_color')) {
				document.getElementById('sesdating_input_border_color').value = '#CACACA';
			}
			if(document.getElementById('sesdating_button_background_color')) {
				document.getElementById('sesdating_button_background_color').value = '#FF5722';
			}
			if(document.getElementById('sesdating_button_background_color_hover')) {
				document.getElementById('sesdating_button_background_color_hover').value = '#fff';
			}
			if(document.getElementById('sesdating_button_font_color')) {
				document.getElementById('sesdating_button_font_color').value = '#fff';
			}
			if(document.getElementById('sesdating_button_font_hover_color')) {
				document.getElementById('sesdating_button_font_hover_color').value = '#243238';
			}
			if(document.getElementById('sesdating_comment_background_color')) {
				document.getElementById('sesdating_comment_background_color').value = '#1D2632';
			}
			//Body Styling
			
				
								//Header Styling
			if(document.getElementById('sesdating_header_background_color')) {
				document.getElementById('sesdating_header_background_color').value = '#1D2632';
			}
			if(document.getElementById('sesdating_menu_logo_font_color')) {
				document.getElementById('sesdating_menu_logo_font_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_background_color')) {
				document.getElementById('sesdating_mainmenu_background_color').value = '#1D2632';
			}
			if(document.getElementById('sesdating_mainmenu_links_color')) {
				document.getElementById('sesdating_mainmenu_links_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_links_hover_color')) {
				document.getElementById('sesdating_mainmenu_links_hover_color').value = '#FF5722';
			}
			if(document.getElementById('sesdating_minimenu_links_color')) {
				document.getElementById('sesdating_minimenu_links_color').value = '#FF5722';
			}
			if(document.getElementById('sesdating_minimenu_links_hover_color')) {
				document.getElementById('sesdating_minimenu_links_hover_color').value = '#fff';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_color')) {
				document.getElementById('sesdating_minimenu_icon_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_active_color')) {
				document.getElementById('sesdating_minimenu_icon_background_active_color').value = '#FFF';
			}
			if(document.getElementById('sesdating_minimenu_icon_color')) {
				document.getElementById('sesdating_minimenu_icon_color').value = '#FF5722';
			}
			if(document.getElementById('sesdating_minimenu_icon_active_color')) {
				document.getElementById('sesdating_minimenu_icon_active_color').value = '#FF5722';
			}
			if(document.getElementById('sesdating_header_searchbox_background_color')) {
				document.getElementById('sesdating_header_searchbox_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_header_searchbox_text_color')) {
				document.getElementById('sesdating_header_searchbox_text_color').value = '#fff';
			}
			//Top Panel Color
			if(document.getElementById('sesdating_toppanel_userinfo_background_color')) {
				document.getElementById('sesdating_toppanel_userinfo_background_color').value = '#FF5722';
			}
			if(document.getElementById('sesdating_toppanel_userinfo_font_color')) {
				document.getElementById('sesdating_toppanel_userinfo_font_color').value = '#FFFFFF';
			}
			//Top Panel Color
			
			//Login Popup Styling
			if(document.getElementById('sesdating_login_popup_header_background_color')) {
				document.getElementById('sesdating_login_popup_header_background_color').value = '#FF5722';
			}
			if(document.getElementById('sesdating_login_popup_header_font_color')) {
				document.getElementById('sesdating_login_popup_header_font_color').value = '#fff';
			}
			//Login Pop up Styling
			//Header Styling
			
			//Footer Styling
			if(document.getElementById('sesdating_footer_background_color')) {
				document.getElementById('sesdating_footer_background_color').value = '#26323F';
			}
			if(document.getElementById('sesdating_footer_heading_color')) {
				document.getElementById('sesdating_footer_heading_color').value = '#B3B3B3';
			}
			if(document.getElementById('sesdating_footer_links_color')) {
				document.getElementById('sesdating_footer_links_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_footer_links_hover_color')) {
				document.getElementById('sesdating_footer_links_hover_color').value = '#FF5722';
			}
			if(document.getElementById('sesdating_footer_border_color')) {
				document.getElementById('sesdating_footer_border_color').value = '#B3B3B3';
			}
			//Footer Styling
    }
    else if(value == 8) {
			//Theme Base Styling
			if(document.getElementById('sesdating_theme_color')) {
				document.getElementById('sesdating_theme_color').value = '#673AB7';
			}
			//Theme Base Styling
			
			//Body Styling
			if(document.getElementById('sesdating_body_background_color')) {
				document.getElementById('sesdating_body_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_font_color')) {
				document.getElementById('sesdating_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_font_color_light')) {
				document.getElementById('sesdating_font_color_light').value = '#999';
			}
			
			if(document.getElementById('sesdating_heading_color')) {
				document.getElementById('sesdating_heading_color').value = '#243238';
			}
			if(document.getElementById('sesdating_links_color')) {
				document.getElementById('sesdating_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_links_hover_color')) {
				document.getElementById('sesdating_links_hover_color').value = '#673AB7';
			}
			if(document.getElementById('sesdating_content_header_background_color')) {
				document.getElementById('sesdating_content_header_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_content_header_font_color')) {
				document.getElementById('sesdating_content_header_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_content_background_color')) {
				document.getElementById('sesdating_content_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_content_border_color')) {
				document.getElementById('sesdating_content_border_color').value = '#ebecee';
			}
			if(document.getElementById('sesdating_form_label_color')) {
				document.getElementById('sesdating_form_label_color').value = '#243238';
			}
			if(document.getElementById('sesdating_input_background_color')) {
				document.getElementById('sesdating_input_background_color').value = '#ffffff';
			}
			if(document.getElementById('sesdating_input_font_color')) {
				document.getElementById('sesdating_input_font_color').value = '#6D6D6D';
			}
			if(document.getElementById('sesdating_input_border_color')) {
				document.getElementById('sesdating_input_border_color').value = '#CACACA';
			}
			if(document.getElementById('sesdating_button_background_color')) {
				document.getElementById('sesdating_button_background_color').value = '#673AB7';
			}
			if(document.getElementById('sesdating_button_background_color_hover')) {
				document.getElementById('sesdating_button_background_color_hover').value = '#243238';
			}
			if(document.getElementById('sesdating_button_font_color')) {
				document.getElementById('sesdating_button_font_color').value = '#ffffff';
			}
			if(document.getElementById('sesdating_button_font_hover_color')) {
				document.getElementById('sesdating_button_font_hover_color').value = '#fff';
			}
			if(document.getElementById('sesdating_comment_background_color')) {
				document.getElementById('sesdating_comment_background_color').value = '#fdfdfd';
			}
			//Body Styling
			
				//Header Styling
			if(document.getElementById('sesdating_header_background_color')) {
				document.getElementById('sesdating_header_background_color').value = '#673AB7';
			}
			if(document.getElementById('sesdating_menu_logo_font_color')) {
				document.getElementById('sesdating_menu_logo_font_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_background_color')) {
				document.getElementById('sesdating_mainmenu_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_links_color')) {
				document.getElementById('sesdating_mainmenu_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_mainmenu_links_hover_color')) {
				document.getElementById('sesdating_mainmenu_links_hover_color').value = '#673AB7';
			}
			if(document.getElementById('sesdating_minimenu_links_color')) {
				document.getElementById('sesdating_minimenu_links_color').value = '#673AB7';
			}
			if(document.getElementById('sesdating_minimenu_links_hover_color')) {
				document.getElementById('sesdating_minimenu_links_hover_color').value = '#243238';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_color')) {
				document.getElementById('sesdating_minimenu_icon_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_active_color')) {
				document.getElementById('sesdating_minimenu_icon_background_active_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_minimenu_icon_color')) {
				document.getElementById('sesdating_minimenu_icon_color').value = '#673AB7';
			}
			if(document.getElementById('sesdating_minimenu_icon_active_color')) {
				document.getElementById('sesdating_minimenu_icon_active_color').value = '#243238';
			}
			if(document.getElementById('sesdating_header_searchbox_background_color')) {
				document.getElementById('sesdating_header_searchbox_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_header_searchbox_text_color')) {
				document.getElementById('sesdating_header_searchbox_text_color').value = '#fff';
			}
			
			//Top Panel Color
			if(document.getElementById('sesdating_toppanel_userinfo_background_color')) {
				document.getElementById('sesdating_toppanel_userinfo_background_color').value = '#673AB7';
			}
			if(document.getElementById('sesdating_toppanel_userinfo_font_color')) {
				document.getElementById('sesdating_toppanel_userinfo_font_color').value = '#FFFFFF';
			}
			//Top Panel Color
			
			//Login Popup Styling
			if(document.getElementById('sesdating_login_popup_header_background_color')) {
				document.getElementById('sesdating_login_popup_header_background_color').value = '#673AB7';
			}
			if(document.getElementById('sesdating_login_popup_header_font_color')) {
				document.getElementById('sesdating_login_popup_header_font_color').value = '#fff';
			}
			//Login Pop up Styling
			//Header Styling
			
			//Footer Styling
			if(document.getElementById('sesdating_footer_background_color')) {
				document.getElementById('sesdating_footer_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_footer_heading_color')) {
				document.getElementById('sesdating_footer_heading_color').value = '#243238';
			}
			if(document.getElementById('sesdating_footer_links_color')) {
				document.getElementById('sesdating_footer_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_footer_links_hover_color')) {
				document.getElementById('sesdating_footer_links_hover_color').value = '#673AB7';
			}
			if(document.getElementById('sesdating_footer_border_color')) {
				document.getElementById('sesdating_footer_border_color').value = '#ddd';
			}
			//Footer Styling
    }
    else if(value == 9) {
		 //Theme Base Styling
			if(document.getElementById('sesdating_theme_color')) {
				document.getElementById('sesdating_theme_color').value = '#009f8b';
			}
			//Theme Base Styling
			
			//Body Styling
			if(document.getElementById('sesdating_body_background_color')) {
				document.getElementById('sesdating_body_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_font_color')) {
				document.getElementById('sesdating_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_font_color_light')) {
				document.getElementById('sesdating_font_color_light').value = '#999';
			}
			
			if(document.getElementById('sesdating_heading_color')) {
				document.getElementById('sesdating_heading_color').value = '#243238';
			}
			if(document.getElementById('sesdating_links_color')) {
				document.getElementById('sesdating_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_links_hover_color')) {
				document.getElementById('sesdating_links_hover_color').value = '#009f8b';
			}
			if(document.getElementById('sesdating_content_header_background_color')) {
				document.getElementById('sesdating_content_header_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_content_header_font_color')) {
				document.getElementById('sesdating_content_header_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_content_background_color')) {
				document.getElementById('sesdating_content_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_content_border_color')) {
				document.getElementById('sesdating_content_border_color').value = '#ebecee';
			}
			if(document.getElementById('sesdating_form_label_color')) {
				document.getElementById('sesdating_form_label_color').value = '#243238';
			}
			if(document.getElementById('sesdating_input_background_color')) {
				document.getElementById('sesdating_input_background_color').value = '#ffffff';
			}
			if(document.getElementById('sesdating_input_font_color')) {
				document.getElementById('sesdating_input_font_color').value = '#6D6D6D';
			}
			if(document.getElementById('sesdating_input_border_color')) {
				document.getElementById('sesdating_input_border_color').value = '#CACACA';
			}
			if(document.getElementById('sesdating_button_background_color')) {
				document.getElementById('sesdating_button_background_color').value = '#009f8b';
			}
			if(document.getElementById('sesdating_button_background_color_hover')) {
				document.getElementById('sesdating_button_background_color_hover').value = '#243238';
			}
			if(document.getElementById('sesdating_button_font_color')) {
				document.getElementById('sesdating_button_font_color').value = '#ffffff';
			}
			if(document.getElementById('sesdating_button_font_hover_color')) {
				document.getElementById('sesdating_button_font_hover_color').value = '#fff';
			}
			if(document.getElementById('sesdating_comment_background_color')) {
				document.getElementById('sesdating_comment_background_color').value = '#fdfdfd';
			}
			//Body Styling
			
			//Header Styling
			if(document.getElementById('sesdating_header_background_color')) {
				document.getElementById('sesdating_header_background_color').value = '#009f8b';
			}
			if(document.getElementById('sesdating_menu_logo_font_color')) {
				document.getElementById('sesdating_menu_logo_font_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_background_color')) {
				document.getElementById('sesdating_mainmenu_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_links_color')) {
				document.getElementById('sesdating_mainmenu_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_mainmenu_links_hover_color')) {
				document.getElementById('sesdating_mainmenu_links_hover_color').value = '#009f8b';
			}
			if(document.getElementById('sesdating_minimenu_links_color')) {
				document.getElementById('sesdating_minimenu_links_color').value = '#009f8b';
			}
			if(document.getElementById('sesdating_minimenu_links_hover_color')) {
				document.getElementById('sesdating_minimenu_links_hover_color').value = '#243238';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_color')) {
				document.getElementById('sesdating_minimenu_icon_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_active_color')) {
				document.getElementById('sesdating_minimenu_icon_background_active_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_minimenu_icon_color')) {
				document.getElementById('sesdating_minimenu_icon_color').value = '#009f8b';
			}
			if(document.getElementById('sesdating_minimenu_icon_active_color')) {
				document.getElementById('sesdating_minimenu_icon_active_color').value = '#243238';
			}
			if(document.getElementById('sesdating_header_searchbox_background_color')) {
				document.getElementById('sesdating_header_searchbox_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_header_searchbox_text_color')) {
				document.getElementById('sesdating_header_searchbox_text_color').value = '#fff';
			}
			
			//Top Panel Color
			if(document.getElementById('sesdating_toppanel_userinfo_background_color')) {
				document.getElementById('sesdating_toppanel_userinfo_background_color').value = '#009f8b';
			}
			if(document.getElementById('sesdating_toppanel_userinfo_font_color')) {
				document.getElementById('sesdating_toppanel_userinfo_font_color').value = '#FFFFFF';
			}
			//Top Panel Color
			
			//Login Popup Styling
			if(document.getElementById('sesdating_login_popup_header_background_color')) {
				document.getElementById('sesdating_login_popup_header_background_color').value = '#009f8b';
			}
			if(document.getElementById('sesdating_login_popup_header_font_color')) {
				document.getElementById('sesdating_login_popup_header_font_color').value = '#fff';
			}
			//Login Pop up Styling
			//Header Styling
			
			//Footer Styling
			if(document.getElementById('sesdating_footer_background_color')) {
				document.getElementById('sesdating_footer_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_footer_heading_color')) {
				document.getElementById('sesdating_footer_heading_color').value = '#243238';
			}
			if(document.getElementById('sesdating_footer_links_color')) {
				document.getElementById('sesdating_footer_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_footer_links_hover_color')) {
				document.getElementById('sesdating_footer_links_hover_color').value = '#009f8b';
			}
			if(document.getElementById('sesdating_footer_border_color')) {
				document.getElementById('sesdating_footer_border_color').value = '#ddd';
			}
			//Footer Styling
		}
    else if(value == 10) {
			//Theme Base Styling
			if(document.getElementById('sesdating_theme_color')) {
				document.getElementById('sesdating_theme_color').value = '#ff9800';
			}
			//Theme Base Styling
			
			//Body Styling
			if(document.getElementById('sesdating_body_background_color')) {
				document.getElementById('sesdating_body_background_color').value = '#101419';
			}
			if(document.getElementById('sesdating_font_color')) {
				document.getElementById('sesdating_font_color').value = '#CCCCCC';
			}
			if(document.getElementById('sesdating_font_color_light')) {
				document.getElementById('sesdating_font_color_light').value = '#fff';
			}
			
			if(document.getElementById('sesdating_heading_color')) {
				document.getElementById('sesdating_heading_color').value = '#b1b1b1';
			}
			if(document.getElementById('sesdating_links_color')) {
				document.getElementById('sesdating_links_color').value = '#fff';
			}
			if(document.getElementById('sesdating_links_hover_color')) {
				document.getElementById('sesdating_links_hover_color').value = '#ff9800';
			}
			if(document.getElementById('sesdating_content_header_background_color')) {
				document.getElementById('sesdating_content_header_background_color').value = '#1D2632';
			}
			if(document.getElementById('sesdating_content_header_font_color')) {
				document.getElementById('sesdating_content_header_font_color').value = '#b1b1b1';
			}
			if(document.getElementById('sesdating_content_background_color')) {
				document.getElementById('sesdating_content_background_color').value = '#1D2632';
			}
			if(document.getElementById('sesdating_content_border_color')) {
				document.getElementById('sesdating_content_border_color').value = '#334354';
			}
			if(document.getElementById('sesdating_form_label_color')) {
				document.getElementById('sesdating_form_label_color').value = '#CCCCCC';
			}
			if(document.getElementById('sesdating_input_background_color')) {
				document.getElementById('sesdating_input_background_color').value = '#CCCCCC';
			}
			if(document.getElementById('sesdating_input_font_color')) {
				document.getElementById('sesdating_input_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_input_border_color')) {
				document.getElementById('sesdating_input_border_color').value = '#CACACA';
			}
			if(document.getElementById('sesdating_button_background_color')) {
				document.getElementById('sesdating_button_background_color').value = '#ff9800';
			}
			if(document.getElementById('sesdating_button_background_color_hover')) {
				document.getElementById('sesdating_button_background_color_hover').value = '#fff';
			}
			if(document.getElementById('sesdating_button_font_color')) {
				document.getElementById('sesdating_button_font_color').value = '#fff';
			}
			if(document.getElementById('sesdating_button_font_hover_color')) {
				document.getElementById('sesdating_button_font_hover_color').value = '#243238';
			}
			if(document.getElementById('sesdating_comment_background_color')) {
				document.getElementById('sesdating_comment_background_color').value = '#1D2632';
			}
			//Body Styling
			
			//Header Styling
			if(document.getElementById('sesdating_header_background_color')) {
				document.getElementById('sesdating_header_background_color').value = '#1D2632';
			}
			if(document.getElementById('sesdating_menu_logo_font_color')) {
				document.getElementById('sesdating_menu_logo_font_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_background_color')) {
				document.getElementById('sesdating_mainmenu_background_color').value = '#1D2632';
			}
			if(document.getElementById('sesdating_mainmenu_links_color')) {
				document.getElementById('sesdating_mainmenu_links_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_links_hover_color')) {
				document.getElementById('sesdating_mainmenu_links_hover_color').value = '#ff9800';
			}
			if(document.getElementById('sesdating_minimenu_links_color')) {
				document.getElementById('sesdating_minimenu_links_color').value = '#ff9800';
			}
			if(document.getElementById('sesdating_minimenu_links_hover_color')) {
				document.getElementById('sesdating_minimenu_links_hover_color').value = '#fff';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_color')) {
				document.getElementById('sesdating_minimenu_icon_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_active_color')) {
				document.getElementById('sesdating_minimenu_icon_background_active_color').value = '#FFF';
			}
			if(document.getElementById('sesdating_minimenu_icon_color')) {
				document.getElementById('sesdating_minimenu_icon_color').value = '#ff9800';
			}
			if(document.getElementById('sesdating_minimenu_icon_active_color')) {
				document.getElementById('sesdating_minimenu_icon_active_color').value = '#ff9800';
			}
			if(document.getElementById('sesdating_header_searchbox_background_color')) {
				document.getElementById('sesdating_header_searchbox_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_header_searchbox_text_color')) {
				document.getElementById('sesdating_header_searchbox_text_color').value = '#fff';
			}
			//Top Panel Color
			if(document.getElementById('sesdating_toppanel_userinfo_background_color')) {
				document.getElementById('sesdating_toppanel_userinfo_background_color').value = '#ff9800';
			}
			if(document.getElementById('sesdating_toppanel_userinfo_font_color')) {
				document.getElementById('sesdating_toppanel_userinfo_font_color').value = '#FFFFFF';
			}
			//Top Panel Color
			
			//Login Popup Styling
			if(document.getElementById('sesdating_login_popup_header_background_color')) {
				document.getElementById('sesdating_login_popup_header_background_color').value = '#ff9800';
			}
			if(document.getElementById('sesdating_login_popup_header_font_color')) {
				document.getElementById('sesdating_login_popup_header_font_color').value = '#fff';
			}
			//Login Pop up Styling
			//Header Styling
			
			//Footer Styling
			if(document.getElementById('sesdating_footer_background_color')) {
				document.getElementById('sesdating_footer_background_color').value = '#26323F';
			}
			if(document.getElementById('sesdating_footer_heading_color')) {
				document.getElementById('sesdating_footer_heading_color').value = '#B3B3B3';
			}
			if(document.getElementById('sesdating_footer_links_color')) {
				document.getElementById('sesdating_footer_links_color').value = '#B3B3B3';
			}
			if(document.getElementById('sesdating_footer_links_hover_color')) {
				document.getElementById('sesdating_footer_links_hover_color').value = '#ff9800';
			}
			if(document.getElementById('sesdating_footer_border_color')) {
				document.getElementById('sesdating_footer_border_color').value = '#B3B3B3';
			}
			//Footer Styling
    }
		else if(value == 11) {
    
      //Theme Base Styling
			if(document.getElementById('sesdating_theme_color')) {
				document.getElementById('sesdating_theme_color').value = '#ed54a4';
			}
			//Theme Base Styling
			
			//Body Styling
			if(document.getElementById('sesdating_body_background_color')) {
				document.getElementById('sesdating_body_background_color').value = '#F5F5F5';
			}
			if(document.getElementById('sesdating_font_color')) {
				document.getElementById('sesdating_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_font_color_light')) {
				document.getElementById('sesdating_font_color_light').value = '#707070';
			}
			
			if(document.getElementById('sesdating_heading_color')) {
				document.getElementById('sesdating_heading_color').value = '#243238';
			}
			if(document.getElementById('sesdating_links_color')) {
				document.getElementById('sesdating_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_links_hover_color')) {
				document.getElementById('sesdating_links_hover_color').value = '#4682B4';
			}
			if(document.getElementById('sesdating_content_header_background_color')) {
				document.getElementById('sesdating_content_header_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_content_header_font_color')) {
				document.getElementById('sesdating_content_header_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_content_background_color')) {
				document.getElementById('sesdating_content_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_content_border_color')) {
				document.getElementById('sesdating_content_border_color').value = '#EBECEE';
			}
			if(document.getElementById('sesdating_form_label_color')) {
				document.getElementById('sesdating_form_label_color').value = '#243238';
			}
			if(document.getElementById('sesdating_input_background_color')) {
				document.getElementById('sesdating_input_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_input_font_color')) {
				document.getElementById('sesdating_input_font_color').value = '#6D6D6D';
			}
			if(document.getElementById('sesdating_input_border_color')) {
				document.getElementById('sesdating_input_border_color').value = '#CACACA';
			}
			if(document.getElementById('sesdating_button_background_color')) {
				document.getElementById('sesdating_button_background_color').value = '#E8288D';
			}
			if(document.getElementById('sesdating_button_background_color_hover')) {
				document.getElementById('sesdating_button_background_color_hover').value = '#4682B4';
			}
			if(document.getElementById('sesdating_button_font_color')) {
				document.getElementById('sesdating_button_font_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_button_font_hover_color')) {
				document.getElementById('sesdating_button_font_hover_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_comment_background_color')) {
				document.getElementById('sesdating_comment_background_color').value = '#FDFDFD';
			}
			//Body Styling
			
			//Header Styling
			if(document.getElementById('sesdating_header_background_color')) {
				document.getElementById('sesdating_header_background_color').value = '#E8288D';
			}
			if(document.getElementById('sesdating_menu_logo_font_color')) {
				document.getElementById('sesdating_menu_logo_font_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_background_color')) {
				document.getElementById('sesdating_mainmenu_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_links_color')) {
				document.getElementById('sesdating_mainmenu_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_mainmenu_links_hover_color')) {
				document.getElementById('sesdating_mainmenu_links_hover_color').value = '#E8288D';
			}
			if(document.getElementById('sesdating_minimenu_links_color')) {
				document.getElementById('sesdating_minimenu_links_color').value = '#E8288D';
			}
			if(document.getElementById('sesdating_minimenu_links_hover_color')) {
				document.getElementById('sesdating_minimenu_links_hover_color').value = '#243238';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_color')) {
				document.getElementById('sesdating_minimenu_icon_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_active_color')) {
				document.getElementById('sesdating_minimenu_icon_background_active_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_minimenu_icon_color')) {
				document.getElementById('sesdating_minimenu_icon_color').value = '#E8288D';
			}
			if(document.getElementById('sesdating_minimenu_icon_active_color')) {
				document.getElementById('sesdating_minimenu_icon_active_color').value = '#243238';
			}
			if(document.getElementById('sesdating_header_searchbox_background_color')) {
				document.getElementById('sesdating_header_searchbox_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_header_searchbox_text_color')) {
				document.getElementById('sesdating_header_searchbox_text_color').value = '#fff';
			}
			
			
			//Top Panel Color
			if(document.getElementById('sesdating_toppanel_userinfo_background_color')) {
				document.getElementById('sesdating_toppanel_userinfo_background_color').value = '#ED54A4';
			}
			if(document.getElementById('sesdating_toppanel_userinfo_font_color')) {
				document.getElementById('sesdating_toppanel_userinfo_font_color').value = '#FFFFFF';
			}
			//Top Panel Color
			
			//Login Popup Styling
			if(document.getElementById('sesdating_login_popup_header_background_color')) {
				document.getElementById('sesdating_login_popup_header_background_color').value = '#ED54A4';
			}
			if(document.getElementById('sesdating_login_popup_header_font_color')) {
				document.getElementById('sesdating_login_popup_header_font_color').value = '#FFFFFF';
			}
			//Login Pop up Styling
			//Header Styling
			
			//Footer Styling
			if(document.getElementById('sesdating_footer_background_color')) {
				document.getElementById('sesdating_footer_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_footer_heading_color')) {
				document.getElementById('sesdating_footer_heading_color').value = '#A4A4A4';
			}
			if(document.getElementById('sesdating_footer_links_color')) {
				document.getElementById('sesdating_footer_links_color').value = '#4682B4';
			}
			if(document.getElementById('sesdating_footer_links_hover_color')) {
				document.getElementById('sesdating_footer_links_hover_color').value = '#ED54A4';
			}
			if(document.getElementById('sesdating_footer_border_color')) {
				document.getElementById('sesdating_footer_border_color').value = '#ddd';
			}
			//Footer Styling
    } else if(value == 12) {
      //Theme Base Styling
			if(document.getElementById('sesdating_theme_color')) {
				document.getElementById('sesdating_theme_color').value = '#2E363F';
			}
			//Theme Base Styling
			
			//Body Styling
			if(document.getElementById('sesdating_body_background_color')) {
				document.getElementById('sesdating_body_background_color').value = '#F5F5F5';
			}
			if(document.getElementById('sesdating_font_color')) {
				document.getElementById('sesdating_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_font_color_light')) {
				document.getElementById('sesdating_font_color_light').value = '#707070';
			}
			
			if(document.getElementById('sesdating_heading_color')) {
				document.getElementById('sesdating_heading_color').value = '#243238';
			}
			if(document.getElementById('sesdating_links_color')) {
				document.getElementById('sesdating_links_color').value = '#49AFCD';
			}
			if(document.getElementById('sesdating_links_hover_color')) {
				document.getElementById('sesdating_links_hover_color').value = '#243238';
			}
			if(document.getElementById('sesdating_content_header_background_color')) {
				document.getElementById('sesdating_content_header_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_content_header_font_color')) {
				document.getElementById('sesdating_content_header_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_content_background_color')) {
				document.getElementById('sesdating_content_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_content_border_color')) {
				document.getElementById('sesdating_content_border_color').value = '#EBECEE';
			}
			if(document.getElementById('sesdating_form_label_color')) {
				document.getElementById('sesdating_form_label_color').value = '#243238';
			}
			if(document.getElementById('sesdating_input_background_color')) {
				document.getElementById('sesdating_input_background_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_input_font_color')) {
				document.getElementById('sesdating_input_font_color').value = '#6D6D6D';
			}
			if(document.getElementById('sesdating_input_border_color')) {
				document.getElementById('sesdating_input_border_color').value = '#CACACA';
			}
			if(document.getElementById('sesdating_button_background_color')) {
				document.getElementById('sesdating_button_background_color').value = '#2E363F';
			}
			if(document.getElementById('sesdating_button_background_color_hover')) {
				document.getElementById('sesdating_button_background_color_hover').value = '#49AFCD';
			}
			if(document.getElementById('sesdating_button_font_color')) {
				document.getElementById('sesdating_button_font_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_button_font_hover_color')) {
				document.getElementById('sesdating_button_font_hover_color').value = '#FFFFFF';
			}
			if(document.getElementById('sesdating_comment_background_color')) {
				document.getElementById('sesdating_comment_background_color').value = '#FDFDFD';
			}
			//Body Styling
			
			//Header Styling
			if(document.getElementById('sesdating_header_background_color')) {
				document.getElementById('sesdating_header_background_color').value = '#49AFCD';
			}
			if(document.getElementById('sesdating_menu_logo_font_color')) {
				document.getElementById('sesdating_menu_logo_font_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_background_color')) {
				document.getElementById('sesdating_mainmenu_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_links_color')) {
				document.getElementById('sesdating_mainmenu_links_color').value = '#243238';
			}
			if(document.getElementById('sesdating_mainmenu_links_hover_color')) {
				document.getElementById('sesdating_mainmenu_links_hover_color').value = '#49AFCD';
			}
			if(document.getElementById('sesdating_minimenu_links_color')) {
				document.getElementById('sesdating_minimenu_links_color').value = '#49AFCD';
			}
			if(document.getElementById('sesdating_minimenu_links_hover_color')) {
				document.getElementById('sesdating_minimenu_links_hover_color').value = '#243238';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_color')) {
				document.getElementById('sesdating_minimenu_icon_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_active_color')) {
				document.getElementById('sesdating_minimenu_icon_background_active_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_minimenu_icon_color')) {
				document.getElementById('sesdating_minimenu_icon_color').value = '#49AFCD';
			}
			if(document.getElementById('sesdating_minimenu_icon_active_color')) {
				document.getElementById('sesdating_minimenu_icon_active_color').value = '#243238';
			}
			if(document.getElementById('sesdating_header_searchbox_background_color')) {
				document.getElementById('sesdating_header_searchbox_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_header_searchbox_text_color')) {
				document.getElementById('sesdating_header_searchbox_text_color').value = '#fff';
			}
			
			//Top Panel Color
			if(document.getElementById('sesdating_toppanel_userinfo_background_color')) {
				document.getElementById('sesdating_toppanel_userinfo_background_color').value = '#49AFCD';
			}
			if(document.getElementById('sesdating_toppanel_userinfo_font_color')) {
				document.getElementById('sesdating_toppanel_userinfo_font_color').value = '#FFFFFF';
			}
			//Top Panel Color
			
			//Login Popup Styling
			if(document.getElementById('sesdating_login_popup_header_background_color')) {
				document.getElementById('sesdating_login_popup_header_background_color').value = '#2E363F';
			}
			if(document.getElementById('sesdating_login_popup_header_font_color')) {
				document.getElementById('sesdating_login_popup_header_font_color').value = '#FFFFFF';
			}
			//Login Pop up Styling
			//Header Styling
			
			//Footer Styling
			if(document.getElementById('sesdating_footer_background_color')) {
				document.getElementById('sesdating_footer_background_color').value = '#2E363F';
			}
			if(document.getElementById('sesdating_footer_heading_color')) {
				document.getElementById('sesdating_footer_heading_color').value = '#CBCBCB';
			}
			if(document.getElementById('sesdating_footer_links_color')) {
				document.getElementById('sesdating_footer_links_color').value = '#CBCBCB';
			}
			if(document.getElementById('sesdating_footer_links_hover_color')) {
				document.getElementById('sesdating_footer_links_hover_color').value = '#49AFCD';
			}
			if(document.getElementById('sesdating_footer_border_color')) {
				document.getElementById('sesdating_footer_border_color').value = '#4A5766';
			}
			//Footer Styling
    }    
		 else if(value == 13) {
			//Theme Base Styling
			if(document.getElementById('sesdating_theme_color')) {
				document.getElementById('sesdating_theme_color').value = '#9C27B0';
			}
			//Theme Base Styling
			
				//Body Styling
			if(document.getElementById('sesdating_body_background_color')) {
				document.getElementById('sesdating_body_background_color').value = '#101419';
			}
			if(document.getElementById('sesdating_font_color')) {
				document.getElementById('sesdating_font_color').value = '#CCCCCC';
			}
			if(document.getElementById('sesdating_font_color_light')) {
				document.getElementById('sesdating_font_color_light').value = '#fff';
			}
			
			if(document.getElementById('sesdating_heading_color')) {
				document.getElementById('sesdating_heading_color').value = '#b1b1b1';
			}
			if(document.getElementById('sesdating_links_color')) {
				document.getElementById('sesdating_links_color').value = '#fff';
			}
			if(document.getElementById('sesdating_links_hover_color')) {
				document.getElementById('sesdating_links_hover_color').value = '#9C27B0';
			}
			if(document.getElementById('sesdating_content_header_background_color')) {
				document.getElementById('sesdating_content_header_background_color').value = '#1D2632';
			}
			if(document.getElementById('sesdating_content_header_font_color')) {
				document.getElementById('sesdating_content_header_font_color').value = '#b1b1b1';
			}
			if(document.getElementById('sesdating_content_background_color')) {
				document.getElementById('sesdating_content_background_color').value = '#1D2632';
			}
			if(document.getElementById('sesdating_content_border_color')) {
				document.getElementById('sesdating_content_border_color').value = '#334354';
			}
			if(document.getElementById('sesdating_form_label_color')) {
				document.getElementById('sesdating_form_label_color').value = '#CCCCCC';
			}
			if(document.getElementById('sesdating_input_background_color')) {
				document.getElementById('sesdating_input_background_color').value = '#CCCCCC';
			}
			if(document.getElementById('sesdating_input_font_color')) {
				document.getElementById('sesdating_input_font_color').value = '#243238';
			}
			if(document.getElementById('sesdating_input_border_color')) {
				document.getElementById('sesdating_input_border_color').value = '#CACACA';
			}
			if(document.getElementById('sesdating_button_background_color')) {
				document.getElementById('sesdating_button_background_color').value = '#9C27B0';
			}
			if(document.getElementById('sesdating_button_background_color_hover')) {
				document.getElementById('sesdating_button_background_color_hover').value = '#fff';
			}
			if(document.getElementById('sesdating_button_font_color')) {
				document.getElementById('sesdating_button_font_color').value = '#fff';
			}
			if(document.getElementById('sesdating_button_font_hover_color')) {
				document.getElementById('sesdating_button_font_hover_color').value = '#243238';
			}
			if(document.getElementById('sesdating_comment_background_color')) {
				document.getElementById('sesdating_comment_background_color').value = '#1D2632';
			}
			//Body Styling
			
			//Header Styling
			if(document.getElementById('sesdating_header_background_color')) {
				document.getElementById('sesdating_header_background_color').value = '#1D2632';
			}
			if(document.getElementById('sesdating_menu_logo_font_color')) {
				document.getElementById('sesdating_menu_logo_font_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_background_color')) {
				document.getElementById('sesdating_mainmenu_background_color').value = '#1D2632';
			}
			if(document.getElementById('sesdating_mainmenu_links_color')) {
				document.getElementById('sesdating_mainmenu_links_color').value = '#fff';
			}
			if(document.getElementById('sesdating_mainmenu_links_hover_color')) {
				document.getElementById('sesdating_mainmenu_links_hover_color').value = '#9C27B0';
			}
			if(document.getElementById('sesdating_minimenu_links_color')) {
				document.getElementById('sesdating_minimenu_links_color').value = '#9C27B0';
			}
			if(document.getElementById('sesdating_minimenu_links_hover_color')) {
				document.getElementById('sesdating_minimenu_links_hover_color').value = '#fff';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_color')) {
				document.getElementById('sesdating_minimenu_icon_background_color').value = '#fff';
			}
			if(document.getElementById('sesdating_minimenu_icon_background_active_color')) {
				document.getElementById('sesdating_minimenu_icon_background_active_color').value = '#FFF';
			}
			if(document.getElementById('sesdating_minimenu_icon_color')) {
				document.getElementById('sesdating_minimenu_icon_color').value = '#9C27B0';
			}
			if(document.getElementById('sesdating_minimenu_icon_active_color')) {
				document.getElementById('sesdating_minimenu_icon_active_color').value = '#9C27B0';
			}
			if(document.getElementById('sesdating_header_searchbox_background_color')) {
				document.getElementById('sesdating_header_searchbox_background_color').value = '#ECEFF1';
			}
			if(document.getElementById('sesdating_header_searchbox_text_color')) {
				document.getElementById('sesdating_header_searchbox_text_color').value = '#fff';
			}
			//Top Panel Color
			if(document.getElementById('sesdating_toppanel_userinfo_background_color')) {
				document.getElementById('sesdating_toppanel_userinfo_background_color').value = '#9C27B0';
			}
			if(document.getElementById('sesdating_toppanel_userinfo_font_color')) {
				document.getElementById('sesdating_toppanel_userinfo_font_color').value = '#FFFFFF';
			}
			//Top Panel Color
			
			//Login Popup Styling
			if(document.getElementById('sesdating_login_popup_header_background_color')) {
				document.getElementById('sesdating_login_popup_header_background_color').value = '#9C27B0';
			}
			if(document.getElementById('sesdating_login_popup_header_font_color')) {
				document.getElementById('sesdating_login_popup_header_font_color').value = '#fff';
			}
			//Login Pop up Styling
			//Header Styling
			
			//Footer Styling
			if(document.getElementById('sesdating_footer_background_color')) {
				document.getElementById('sesdating_footer_background_color').value = '#26323F';
			}
			if(document.getElementById('sesdating_footer_heading_color')) {
				document.getElementById('sesdating_footer_heading_color').value = '#B3B3B3';
			}
			if(document.getElementById('sesdating_footer_links_color')) {
				document.getElementById('sesdating_footer_links_color').value = '#B3B3B3';
			}
			if(document.getElementById('sesdating_footer_links_hover_color')) {
				document.getElementById('sesdating_footer_links_hover_color').value = '#9C27B0';
			}
			if(document.getElementById('sesdating_footer_border_color')) {
				document.getElementById('sesdating_footer_border_color').value = '#B3B3B3';
			}
			//Footer Styling
    }
		 else if(value == 5) {
    
      //Theme Base Styling
      if(document.getElementById('sesdating_theme_color')) {
        document.getElementById('sesdating_theme_color').value = '<?php echo $settings->getSetting('sesdating.theme.color') ?>';
      }
      //Theme Base Styling
      //Body Styling
      if(document.getElementById('sesdating_body_background_color')) {
        document.getElementById('sesdating_body_background_color').value = '<?php echo $settings->getSetting('sesdating.body.background.color') ?>';
      }
      if(document.getElementById('sesdating_font_color')) {
        document.getElementById('sesdating_font_color').value = '<?php echo $settings->getSetting('sesdating.fontcolor') ?>';
      }
      if(document.getElementById('sesdating_font_color_light')) {
        document.getElementById('sesdating_font_color_light').value = '<?php echo $settings->getSetting('sesdating.font.color.light') ?>';
      }
      if(document.getElementById('sesdating_heading_color')) {
        document.getElementById('sesdating_heading_color').value = '<?php echo $settings->getSetting('sesdating.heading.color') ?>';
      }
      if(document.getElementById('sesdating_links_color')) {
        document.getElementById('sesdating_links_color').value = '<?php echo $settings->getSetting('sesdating.links.color') ?>';
      }
      if(document.getElementById('sesdating_links_hover_color')) {
        document.getElementById('sesdating_links_hover_color').value = '<?php echo $settings->getSetting('sesdating.links.hover.color') ?>';
      }
			if(document.getElementById('sesdating_content_header_background_color')) {
        document.getElementById('sesdating_content_header_background_color').value = '<?php echo $settings->getSetting('sesdating.content.header.background.color') ?>';
      }
			if(document.getElementById('sesdating_content_header_font_color')) {
        document.getElementById('sesdating_content_header_font_color').value = '<?php echo $settings->getSetting('sesdating.content.header.font.color') ?>';
      }
      if(document.getElementById('sesdating_content_background_color')) {
        document.getElementById('sesdating_content_background_color').value = '<?php echo $settings->getSetting('sesdating.content.background.color') ?>';
      }
      if(document.getElementById('sesdating_content_border_color')) {
        document.getElementById('sesdating_content_border_color').value = '<?php echo $settings->getSetting('sesdating.content.border.color') ?>';
      }
      if(document.getElementById('sesdating_form_label_color')) {
        document.getElementById('sesdating_input_font_color').value = '<?php echo $settings->getSetting('sesdating.form.label.color') ?>';
      }
      if(document.getElementById('sesdating_input_background_color')) {
        document.getElementById('sesdating_input_background_color').value = '<?php echo $settings->getSetting('sesdating.input.background.color') ?>';
      }
      if(document.getElementById('sesdating_input_font_color')) {
        document.getElementById('sesdating_input_font_color').value = '<?php echo $settings->getSetting('sesdating.input.font.color') ?>';
      }
      if(document.getElementById('sesdating_input_border_color')) {
        document.getElementById('sesdating_input_border_color').value = '<?php echo $settings->getSetting('sesdating.input.border.color') ?>';
      }
      if(document.getElementById('sesdating_button_background_color')) {
        document.getElementById('sesdating_button_background_color').value = '<?php echo $settings->getSetting('sesdating.button.backgroundcolor') ?>';
      }
      if(document.getElementById('sesdating_button_background_color_hover')) {
        document.getElementById('sesdating_button_background_color_hover').value = '<?php echo $settings->getSetting('sesdating.button.background.color.hover') ?>';
      }
      if(document.getElementById('sesdating_button_font_color')) {
        document.getElementById('sesdating_button_font_color').value = '<?php echo $settings->getSetting('sesdating.button.font.color') ?>';
      }
      if(document.getElementById('sesdating_button_font_hover_color')) {
        document.getElementById('sesdating_button_font_hover_color').value = '<?php echo $settings->getSetting('sesdating.button.font.hover.color') ?>';
      }
      if(document.getElementById('sesdating_comment_background_color')) {
        document.getElementById('sesdating_comment_background_color').value = '<?php echo $settings->getSetting('sesdating.comment.background.color') ?>';
      }
      //Body Styling
      //Header Styling
      if(document.getElementById('sesdating_header_background_color')) {
        document.getElementById('sesdating_header_background_color').value = '<?php echo $settings->getSetting('sesdating.header.background.color') ?>';
      }
			if(document.getElementById('sesdating_mainmenu_background_color')) {
        document.getElementById('sesdating_mainmenu_background_color').value = '<?php echo $settings->getSetting('sesdating.mainmenu.background.color') ?>';
      }
      if(document.getElementById('sesdating_mainmenu_links_color')) {
        document.getElementById('sesdating_mainmenu_links_color').value = '<?php echo $settings->getSetting('sesdating.mainmenu.links.color') ?>';
      }
      if(document.getElementById('sesdating_mainmenu_links_hover_color')) {
        document.getElementById('sesdating_mainmenu_links_hover_color').value = '<?php echo $settings->getSetting('sesdating.mainmenu.links.hover.color') ?>';
      }
      if(document.getElementById('sesdating_minimenu_links_color')) {
        document.getElementById('sesdating_minimenu_links_color').value = '<?php echo $settings->getSetting('sesdating.minimenu.links.color') ?>';
      }
      if(document.getElementById('sesdating_minimenu_links_hover_color')) {
        document.getElementById('sesdating_minimenu_links_hover_color').value = '<?php echo $settings->getSetting('sesdating.minimenu.links.hover.color') ?>';
      }
      if(document.getElementById('sesdating_minimenu_icon_background_color')) {
        document.getElementById('sesdating_minimenu_icon_background_color').value = '<?php echo $settings->getSetting('sesdating.minimenu.icon.background.color') ?>';
      }
      if(document.getElementById('sesdating_minimenu_icon_background_active_color')) {
        document.getElementById('sesdating_minimenu_icon_background_active_color').value = '<?php echo $settings->getSetting('sesdating.minimenu.icon.background.active.color') ?>';
      }
      if(document.getElementById('sesdating_minimenu_icon_color')) {
        document.getElementById('sesdating_minimenu_icon_color').value = '<?php echo $settings->getSetting('sesdating.minimenu.icon.color') ?>';
      }
      if(document.getElementById('sesdating_minimenu_icon_active_color')) {
        document.getElementById('sesdating_minimenu_icon_active_color').value = '<?php echo $settings->getSetting('sesdating.minimenu.icon.active.color') ?>';
      }
      if(document.getElementById('sesdating_header_searchbox_background_color')) {
        document.getElementById('sesdating_header_searchbox_background_color').value = '<?php echo $settings->getSetting('sesdating.header.searchbox.background.color') ?>';
      }
      if(document.getElementById('sesdating_header_searchbox_text_color')) {
        document.getElementById('sesdating_header_searchbox_text_color').value = '<?php echo $settings->getSetting('sesdating.header.searchbox.text.color') ?>';
      }
			
			//Top Panel Color
      if(document.getElementById('sesdating_toppanel_userinfo_background_color')) {
        document.getElementById('sesdating_toppanel_userinfo_background_color').value = '<?php echo $settings->getSetting('sesdating.toppanel.userinfo.background.color'); ?>';
      }
      
      if(document.getElementById('sesdating_toppanel_userinfo_font_color')) {
        document.getElementById('sesdating_toppanel_userinfo_font_color').value = '<?php echo $settings->getSetting('sesdating.toppanel.userinfo.font.color'); ?>';
      }
			//Top Panel Color
			
			//Login Popup Styling
      if(document.getElementById('sesdating_login_popup_header_font_color')) {
        document.getElementById('sesdating_login_popup_header_font_color').value = '<?php echo $settings->getSetting('sesdating.login.popup.header.font.color'); ?>';
      }
      if(document.getElementById('sesdating_login_popup_header_background_color')) {
        document.getElementById('sesdating_login_popup_header_background_color').value = '<?php echo $settings->getSetting('sesdating.login.popup.header.background.color'); ?>';
      }
			//Login Pop up Styling
      //Header Styling

      //Footer Styling
      if(document.getElementById('sesdating_footer_background_color')) {
        document.getElementById('sesdating_footer_background_color').value = '<?php echo $settings->getSetting('sesdating.footer.background.color') ?>';
      }
      if(document.getElementById('sesdating_footer_heading_color')) {
        document.getElementById('sesdating_footer_heading_color').value = '<?php echo $settings->getSetting('sesdating.footer.heading.color') ?>';
      }
      if(document.getElementById('sesdating_footer_links_color')) {
        document.getElementById('sesdating_footer_links_color').value = '<?php echo $settings->getSetting('sesdating.footer.links.color') ?>';
      }
      if(document.getElementById('sesdating_footer_links_hover_color')) {
        document.getElementById('sesdating_footer_links_hover_color').value = '<?php echo $settings->getSetting('sesdating.footer.links.hover.color') ?>';
      }
      if(document.getElementById('sesdating_footer_border_color')) {
        document.getElementById('sesdating_footer_border_color').value = '<?php echo $settings->getSetting('sesdating.footer.border.color') ?>';
      }
      //Footer Styling
    }
	}
</script>
