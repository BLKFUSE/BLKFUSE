<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Styling.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_Form_Admin_Styling extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $sesdatingApi = Engine_Api::_()->sesdating();
    $this->setTitle('Manage Color Schemes')
            ->setDescription('Here, you can manage the color schemes of your website.');
    
    $sesdating_landingpage = Zend_Registry::isRegistered('sesdating_landingpage') ? Zend_Registry::get('sesdating_landingpage') : null;
    if($sesdating_landingpage) {
    $this->addElement('Radio', 'theme_color', array(
        'label' => 'Color Schemes',
        'multiOptions' => array(
            1 => '<img src="./application/modules/Sesdating/externals/images/color-scheme/1.jpg" alt="" />',
            2 => '<img src="./application/modules/Sesdating/externals/images/color-scheme/2.jpg" alt="" />',
            3 => '<img src="./application/modules/Sesdating/externals/images/color-scheme/3.jpg" alt="" />',
            4 => '<img src="./application/modules/Sesdating/externals/images/color-scheme/4.jpg" alt="" />',
						6 => '<img src="./application/modules/Sesdating/externals/images/color-scheme/5.jpg" alt="" />',
						7 => '<img src="./application/modules/Sesdating/externals/images/color-scheme/6.jpg" alt="" />',
						8 => '<img src="./application/modules/Sesdating/externals/images/color-scheme/7.jpg" alt="" />',
						9 => '<img src="./application/modules/Sesdating/externals/images/color-scheme/8.jpg" alt="" />',
						10 => '<img src="./application/modules/Sesdating/externals/images/color-scheme/9.jpg" alt="" />',
            11 => '<img src="./application/modules/Sesdating/externals/images/color-scheme/10.jpg" alt="" />',
            12 => '<img src="./application/modules/Sesdating/externals/images/color-scheme/11.jpg" alt="" />',
            13 => '<img src="./application/modules/Sesdating/externals/images/color-scheme/12.jpg" alt="" />',
						5 => '<img src="./application/modules/Sesdating/externals/images/color-scheme/custom.jpg" alt="" />',
        ),
        'onclick' => 'changeThemeColor(this.value, "")',
        'escape' => false,
        'value' => $sesdatingApi->getContantValueXML('theme_color'),
    ));
    
    $activatedTheme = $sesdatingApi->getContantValueXML('custom_theme_color');
    
    $customtheme_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('customtheme_id', 0);
    if($customtheme_id) {
      $customtheme_value = $customtheme_id;
    } else {
      $customtheme_value = $sesdatingApi->getContantValueXML('custom_theme_color');
    }
    
    $sestheme = array(
      //5 => 'New Custom',
      1 => 'Theme - 1',
      2 => 'Theme - 2',
      3 => 'Theme - 3',
      4 => 'Theme - 4',
      6 => 'Theme - 5',
      7 => 'Theme - 6',
      8 => 'Theme - 7',
      9 => 'Theme - 8',
      10 => 'Theme - 9',
      11 => 'Theme - 10',
      12 => 'Theme - 11',
      13 => 'Theme - 12'
    );
    
    $getCustomThemes = Engine_Api::_()->getDbTable('customthemes', 'sesdating')->getCustomThemes();
    foreach($getCustomThemes as $getCustomTheme){
      $sestheme[$getCustomTheme['customtheme_id']] = $getCustomTheme['name'];
    }

    $this->addElement('Select', 'custom_theme_color', array(
        'label' => 'Custom Theme Color',
        'multiOptions' => $sestheme,
        'onclick' => 'changeCustomThemeColor(this.value)',
        'escape' => false,
        'value' => $customtheme_value, //$sesdatingApi->getContantValueXML('custom_theme_color'),
    ));

    $this->addElement('dummy', 'custom_themes', array(
      'decorators' => array(array('ViewScript', array(
        'viewScript' => 'application/modules/Sesdating/views/scripts/custom_themes.tpl',
        'class' => 'form element',
        'customtheme_id' => $customtheme_id,
        'activatedTheme' => $activatedTheme,
      )))
    ));
    
    
    $theme_color = $sesdatingApi->getContantValueXML('theme_color');
    if($theme_color == '5') {
    	$sesdating_header_background_color = $settings->getSetting('sesdating.header.background.color');
			$sesdating_menu_logo_font_color = $settings->getSetting('sesdating.menu.logo.font.color');
			$sesdating_mainmenu_background_color = $settings->getSetting('sesdating.mainmenu.background.color');
			$sesdating_mainmenu_links_color = $settings->getSetting('sesdating.mainmenu.links.color');
			$sesdating_mainmenu_links_hover_color = $settings->getSetting('sesdating.mainmenu.links.hover.color');
			$sesdating_minimenu_links_color = $settings->getSetting('sesdating.minimenu.links.color');
			$sesdating_minimenu_links_hover_color = $settings->getSetting('sesdating.minimenu.links.hover.color');
			$sesdating_minimenu_icon_background_color = $settings->getSetting('sesdating.minimenu.icon.background.color');
			$sesdating_minimenu_icon_background_active_color = $settings->getSetting('sesdating.minimenu.icon.background.active.color');
			$sesdating_minimenu_icon_color = $settings->getSetting('sesdating.minimenu.icon.color');
			$sesdating_minimenu_icon_active_color = $settings->getSetting('sesdating.minimenu.icon.active.color');
			$sesdating_header_searchbox_background_color = $settings->getSetting('sesdating.header.searchbox.background.color');
			$sesdating_header_searchbox_text_color = $settings->getSetting('sesdating.header.searchbox.text.color');
			$sesdating_login_popup_header_font_color = $settings->getSetting('sesdating.login.popup.header.font.color');
			$sesdating_footer_background_color = $settings->getSetting('sesdating.footer.background.color');
			$sesdating_footer_heading_color = $settings->getSetting('sesdating.footer.heading.color');
			$sesdating_footer_links_color = $settings->getSetting('sesdating.footer.links.color');
			$sesdating_footer_links_hover_color = $settings->getSetting('sesdating.footer.links.hover.color');
			$sesdating_footer_border_color = $settings->getSetting('sesdating.footer.border.color');
			$sesdating_theme_color = $settings->getSetting('sesdating.theme.color');
			$sesdating_body_background_color = $settings->getSetting('sesdating.body.background.color');
			$sesdating_font_color = $settings->getSetting('sesdating.fontcolor');
			$sesdating_font_color_light = $settings->getSetting('sesdating.font.color.light');
			$sesdating_heading_color = $settings->getSetting('sesdating.heading.color');
			$sesdating_links_color = $settings->getSetting('sesdating.links.color');
			$sesdating_links_hover_color = $settings->getSetting('sesdating.links.hover.color');
			$sesdating_content_header_background_color = $settings->getSetting('sesdating.content.header.background.color');
			$sesdating_content_header_font_color = $settings->getSetting('sesdating.content.header.font.color');
			$sesdating_content_background_color = $settings->getSetting('sesdating.content.background.color');
			$sesdating_content_border_color = $settings->getSetting('sesdating.content.border.color');
			$sesdating_form_label_color = $settings->getSetting('sesdating.form.label.color');
			$sesdating_input_background_color = $settings->getSetting('sesdating.input.background.color');
			$sesdating_input_font_color = $settings->getSetting('sesdating.input.font.color');
			$sesdating_input_border_color = $settings->getSetting('sesdating.input.border.color');
			$sesdating_button_background_color = $settings->getSetting('sesdating.button.backgroundcolor');
			$sesdating_button_background_color_hover = $settings->getSetting('sesdating.button.background.color.hover');
			$sesdating_button_font_color = $settings->getSetting('sesdating.button.font.color');
			$sesdating_button_font_hover_color = $settings->getSetting('sesdating.button.font.hover.color');
			$sesdating_comment_background_color = $settings->getSetting('sesdating.comment.background.color');

    } else {
	    $sesdating_header_background_color = $sesdatingApi->getContantValueXML('sesdating_header_background_color');
			$sesdating_menu_logo_font_color = $sesdatingApi->getContantValueXML('sesdating_menu_logo_font_color');
			$sesdating_mainmenu_background_color = $sesdatingApi->getContantValueXML('sesdating_mainmenu_background_color');
			$sesdating_mainmenu_links_color = $sesdatingApi->getContantValueXML('sesdating_mainmenu_links_color');
			$sesdating_mainmenu_links_hover_color = $sesdatingApi->getContantValueXML('sesdating_mainmenu_links_hover_color');
			$sesdating_minimenu_links_color = $sesdatingApi->getContantValueXML('sesdating_minimenu_links_color');
			$sesdating_minimenu_links_hover_color = $sesdatingApi->getContantValueXML('sesdating_minimenu_links_hover_color');
			$sesdating_minimenu_icon_background_color = $settings->getSetting('sesdating_minimenu_icon_background_color');
			$sesdating_minimenu_icon_background_active_color = $settings->getSetting('sesdating_minimenu_icon_background_active_color');
			$sesdating_minimenu_icon_color = $sesdatingApi->getContantValueXML('sesdating_minimenu_icon_color');
			$sesdating_minimenu_icon_active_color = $sesdatingApi->getContantValueXML('sesdating_minimenu_icon_active_color');
			$sesdating_header_searchbox_background_color = $sesdatingApi->getContantValueXML('sesdating_header_searchbox_background_color');
			$sesdating_header_searchbox_text_color = $sesdatingApi->getContantValueXML('sesdating_header_searchbox_text_color');
			
			$sesdating_toppanel_userinfo_background_color = $settings->getSetting('sesdating_toppanel_userinfo_background_color');
			$sesdating_toppanel_userinfo_font_color = $settings->getSetting('sesdating_toppanel_userinfo_font_color');
			
			$sesdating_login_popup_header_background_color = $settings->getSetting('sesdating_login_popup_header_background_color');
			$sesdating_login_popup_header_font_color = $settings->getSetting('sesdating_login_popup_header_font_color');
			$sesdating_footer_background_color = $sesdatingApi->getContantValueXML('sesdating_footer_background_color');
			$sesdating_footer_heading_color = $sesdatingApi->getContantValueXML('sesdating_footer_heading_color');
			$sesdating_footer_links_color = $sesdatingApi->getContantValueXML('sesdating_footer_links_color');
			$sesdating_footer_links_hover_color = $sesdatingApi->getContantValueXML('sesdating_footer_links_hover_color');
			$sesdating_footer_border_color = $sesdatingApi->getContantValueXML('sesdating_footer_border_color');
			$sesdating_theme_color = $sesdatingApi->getContantValueXML('sesdating_theme_color');
			$sesdating_body_background_color = $sesdatingApi->getContantValueXML('sesdating_body_background_color');
			$sesdating_font_color = $sesdatingApi->getContantValueXML('sesdating_font_color');
			$sesdating_font_color_light = $sesdatingApi->getContantValueXML('sesdating_font_color_light');
			$sesdating_heading_color = $sesdatingApi->getContantValueXML('sesdating_heading_color');
			$sesdating_links_color = $sesdatingApi->getContantValueXML('sesdating_links_color');
			$sesdating_links_hover_color = $sesdatingApi->getContantValueXML('sesdating_links_hover_color');
			$sesdating_content_header_background_color = $sesdatingApi->getContantValueXML('sesdating_content_header_background_color');
			$sesdating_content_header_font_color = $sesdatingApi->getContantValueXML('sesdating_content_header_font_color');
			$sesdating_content_background_color = $sesdatingApi->getContantValueXML('sesdating_content_background_color');
			$sesdating_content_border_color = $sesdatingApi->getContantValueXML('sesdating_content_border_color');
			$sesdating_form_label_color = $sesdatingApi->getContantValueXML('sesdating_form_label_color');
			$sesdating_input_background_color = $sesdatingApi->getContantValueXML('sesdating_input_background_color');
			$sesdating_input_font_color = $sesdatingApi->getContantValueXML('sesdating_input_font_color');
			$sesdating_input_border_color = $sesdatingApi->getContantValueXML('sesdating_input_border_color');
			$sesdating_button_background_color = $sesdatingApi->getContantValueXML('sesdating_button_background_color');
			$sesdating_button_background_color_hover = $sesdatingApi->getContantValueXML('sesdating_button_background_color_hover');
			$sesdating_button_font_color = $sesdatingApi->getContantValueXML('sesdating_button_font_color');
			$sesdating_button_font_hover_color = $sesdatingApi->getContantValueXML('sesdating_button_font_hover_color');
			$sesdating_comment_background_color = $sesdatingApi->getContantValueXML('sesdating_comment_background_color');
    }

    //Start Header Styling
    $this->addElement('Dummy', 'header_settings', array(
        'label' => 'Header Styling Settings',
    ));
    $this->addElement('Text', "sesdating_header_background_color", array(
        'label' => 'Header Background Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_header_background_color,
    ));
		
    $this->addElement('Text', "sesdating_menu_logo_font_color", array(
        'label' => 'Menu Logo Font Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_menu_logo_font_color,
    ));

    $this->addElement('Text', "sesdating_mainmenu_background_color", array(
        'label' => 'Main Menu Background Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_mainmenu_background_color,
    ));
		
    $this->addElement('Text', "sesdating_mainmenu_links_color", array(
        'label' => 'Main Menu Link Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_mainmenu_links_color,
    ));

    $this->addElement('Text', "sesdating_mainmenu_links_hover_color", array(
        'label' => 'Main Menu Link Hover Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_mainmenu_links_hover_color,
    ));

    $this->addElement('Text', "sesdating_minimenu_icon_background_color", array(
        'label' => 'Mini Menu Icon Background Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_minimenu_icon_background_color,
    ));
    $this->addElement('Text', "sesdating_minimenu_icon_background_active_color", array(
        'label' => 'Mini Menu Icon Hover Background Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_minimenu_icon_background_active_color,
    ));
		
    $this->addElement('Text', "sesdating_minimenu_icon_color", array(
        'label' => 'Mini Menu Icon Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_minimenu_icon_color,
    ));
    $this->addElement('Text', "sesdating_minimenu_icon_active_color", array(
        'label' => 'Mini Menu Icon Hover Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_minimenu_icon_active_color,
    ));

    $this->addElement('Text', "sesdating_header_searchbox_background_color", array(
        'label' => 'Header Searchbox Border Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_header_searchbox_background_color,
    ));

    $this->addElement('Text', "sesdating_header_searchbox_text_color", array(
        'label' => 'Header Searchbox Text Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_header_searchbox_text_color,
    ));
    
    //Top Panel Color
    $this->addElement('Text', "sesdating_toppanel_userinfo_background_color", array(
        'label' => 'Background Color for User section in Main Menu',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_toppanel_userinfo_background_color,
    ));
    $this->addElement('Text', "sesdating_toppanel_userinfo_font_color", array(
        'label' => 'Font Color for User Section in Main Menu',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_toppanel_userinfo_font_color,
    ));
    //Top Panel Color
    
		//Login Popup Styling
    $this->addElement('Text', "sesdating_login_popup_header_background_color", array(
        'label' => 'Login Popup Header Background Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_login_popup_header_background_color,
    ));
    $this->addElement('Text', "sesdating_login_popup_header_font_color", array(
        'label' => 'Login Popup Header Font Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_login_popup_header_font_color,
    ));

    $this->addDisplayGroup(array('sesdating_header_background_color', 'sesdating_menu_logo_font_color', 'sesdating_mainmenu_background_color', 'sesdating_mainmenu_links_color', 'sesdating_mainmenu_links_hover_color', 'sesdating_minimenu_links_color', 'sesdating_minimenu_links_hover_color', 'sesdating_minimenu_icon_background_color', 'sesdating_minimenu_icon_background_active_color', 'sesdating_minimenu_icon_color', 'sesdating_minimenu_icon_active_color',  'sesdating_header_searchbox_background_color', 'sesdating_header_searchbox_text_color','sesdating_toppanel_userinfo_background_color','sesdating_toppanel_userinfo_font_color', 'sesdating_login_popup_header_background_color', 'sesdating_login_popup_header_font_color'), 'header_settings_group', array('disableLoadDefaultDecorators' => true));
    $header_settings_group = $this->getDisplayGroup('header_settings_group');
    $header_settings_group->setDecorators(array('FormElements', 'Fieldset', array('HtmlTag', array('tag' => 'div', 'id' => 'header_settings_group'))));
    //End Header Styling
    //Start Footer Styling
    $this->addElement('Dummy', 'footer_settings', array(
        'label' => 'Footer Styling Settings',
    ));
    $this->addElement('Text', "sesdating_footer_background_color", array(
        'label' => 'Footer Background Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_footer_background_color,
    ));

//     $this->addElement('Text', "sesdating_footer_heading_color", array(
//         'label' => 'Footer Heading Color',
//         'allowEmpty' => false,
//         'required' => true,
//         'class' => 'SEScolor',
//         'value' => $sesdating_footer_heading_color,
//     ));

    $this->addElement('Text', "sesdating_footer_links_color", array(
        'label' => 'Footer Link Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_footer_links_color,
    ));

    $this->addElement('Text', "sesdating_footer_links_hover_color", array(
        'label' => 'Footer Link Hover Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_footer_links_hover_color,
    ));
    $this->addElement('Text', "sesdating_footer_border_color", array(
        'label' => 'Footer Border Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_footer_border_color,
    ));
    $this->addDisplayGroup(array('sesdating_footer_background_color', 'sesdating_footer_heading_color', 'sesdating_footer_links_color', 'sesdating_footer_links_hover_color', 'sesdating_footer_border_color'), 'footer_settings_group', array('disableLoadDefaultDecorators' => true));
    $footer_settings_group = $this->getDisplayGroup('footer_settings_group');
    $footer_settings_group->setDecorators(array('FormElements', 'Fieldset', array('HtmlTag', array('tag' => 'div', 'id' => 'footer_settings_group'))));
    //End Footer Styling
    //Start Body Styling
    $this->addElement('Dummy', 'body_settings', array(
        'label' => 'Body Styling Settings',
    ));
    $this->addElement('Text', "sesdating_theme_color", array(
        'label' => 'Theme Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_theme_color,
    ));
    
    
    $this->addElement('Text', "sesdating_body_background_color", array(
        'label' => 'Body Background Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_body_background_color,
    ));

    $this->addElement('Text', "sesdating_font_color", array(
        'label' => 'Font Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_font_color,
    ));

    $this->addElement('Text', "sesdating_font_color_light", array(
        'label' => 'Font Light Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_font_color_light,
    ));

    $this->addElement('Text', "sesdating_heading_color", array(
        'label' => 'Heading Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_heading_color,
    ));

    $this->addElement('Text', "sesdating_links_color", array(
        'label' => 'Link Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_links_color,
    ));

    $this->addElement('Text', "sesdating_links_hover_color", array(
        'label' => 'Link Hover Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_links_hover_color,
    ));

    $this->addElement('Text', "sesdating_content_header_background_color", array(
        'label' => 'Content Header Background Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_content_header_background_color,
    ));
    $this->addElement('Text', "sesdating_content_header_font_color", array(
        'label' => 'Content Header Font Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_content_header_font_color,
    ));
		
    $this->addElement('Text', "sesdating_content_background_color", array(
        'label' => 'Content Background Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_content_background_color,
    ));

    $this->addElement('Text', "sesdating_content_border_color", array(
        'label' => 'Content Border Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_content_border_color,
    ));

    $this->addElement('Text', "sesdating_form_label_color", array(
        'label' => 'Form Label Font Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_form_label_color,
    ));

    $this->addElement('Text', "sesdating_input_background_color", array(
        'label' => 'Input Background Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_input_background_color,
    ));

    $this->addElement('Text', "sesdating_input_font_color", array(
        'label' => 'Input Font Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_input_font_color,
    ));

    $this->addElement('Text', "sesdating_input_border_color", array(
        'label' => 'Input Border Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_input_border_color,
    ));

    $this->addElement('Text', "sesdating_button_background_color", array(
        'label' => 'Button Background Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_button_background_color,
    ));
    $this->addElement('Text', "sesdating_button_background_color_hover", array(
        'label' => 'Button Background Hovor Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_button_background_color_hover,
    ));

    $this->addElement('Text', "sesdating_button_font_color", array(
        'label' => 'Button Font Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_button_font_color,
    ));
    $this->addElement('Text', "sesdating_button_font_hover_color", array(
        'label' => 'Button Font Hover Color',
        'allowEmpty' => false,
        'required' => true,
        'class' => 'SEScolor',
        'value' => $sesdating_button_font_hover_color,
    ));


    $this->addDisplayGroup(array('sesdating_theme_color','sesdating_body_background_color', 'sesdating_font_color', 'sesdating_font_color_light', 'sesdating_heading_color', 'sesdating_links_color', 'sesdating_links_hover_color', 'sesdating_content_header_background_color', 'sesdating_content_header_font_color', 'sesdating_content_background_color', 'sesdating_content_border_color', 'sesdating_form_label_color', 'sesdating_input_background_color', 'sesdating_input_font_color', 'sesdating_input_border_color', 'sesdating_button_background_color', 'sesdating_button_background_color_hover', 'sesdating_button_font_color', 'sesdating_button_font_hover_color'), 'body_settings_group', array('disableLoadDefaultDecorators' => true));
    $body_settings_group = $this->getDisplayGroup('body_settings_group');
    $body_settings_group->setDecorators(array('FormElements', 'Fieldset', array('HtmlTag', array('tag' => 'div', 'id' => 'body_settings_group'))));
    //End Body Styling
    
    //Add submit button
    $this->addElement('Button', 'save', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper')
    ));
    $this->addElement('Button', 'submit', array(
        'label' => 'Save as Draft',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper')
    ));
    $this->addDisplayGroup(array('save', 'submit'), 'buttons');
    }
  }
}
