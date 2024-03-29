/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Serenity
 * @copyright  Copyright 2006-2022 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: my.sql 2022-06-20
 */

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("core_admin_main_serenity", "serenity", "Serenity Theme", "", '{"route":"admin_default","module":"serenity","controller":"settings"}', "core_admin_main", "", 999),
("serenity_admin_main_settings", "serenity", "Global Settings", "", '{"route":"admin_default","module":"serenity","controller":"settings"}', "serenity_admin_main", "", 1),
("serenity_admin_main_styling", "serenity", "Color Schemes", "", '{"route":"admin_default","module":"serenity","controller":"settings", "action":"styling"}', "serenity_admin_main", "", 2),
("serenity_admin_main_managefonts", "serenity", "Manage Fonts", "", '{"route":"admin_default","module":"serenity","controller":"settings", "action":"manage-fonts"}', "serenity_admin_main", "", 3);

DROP TABLE IF EXISTS `engine4_serenity_customthemes`;
CREATE TABLE IF NOT EXISTS `engine4_serenity_customthemes` (
  `customtheme_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `value` varchar(32) NOT NULL,
  `column_key` varchar(128) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `default` TINYINT(1) NOT NULL DEFAULT "0" ,
  PRIMARY KEY (`customtheme_id`),
  UNIQUE KEY `UNIQUEKEY` (`column_key`,`theme_id`,`default`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;


INSERT IGNORE INTO `engine4_serenity_customthemes` (`name`, `value`, `column_key`, `theme_id`, `default`) VALUES
("Theme - 1", "1", "theme_color", 1, 0),
("Theme - 1", "1", "custom_theme_color", 1, 0),
("Theme - 1", "#03598f", "serenity_header_background_color", 1, 0),
("Theme - 1", "#fff", "serenity_mainmenu_background_color", 1, 0),
("Theme - 1", "#909090", "serenity_mainmenu_links_color", 1, 0),
("Theme - 1", "#FFFFFF", "serenity_mainmenu_links_hover_color", 1, 0),
("Theme - 1", "#fe4497", "serenity_mainmenu_links_hover_background_color", 1, 0),
("Theme - 1", "#FFFFFF", "serenity_menu_tip_color", 1, 0),
("Theme - 1", "#FFFFFF", "serenity_minimenu_links_color", 1, 0),
("Theme - 1", "#fe4497", "serenity_minimenu_link_active_color", 1, 0),
("Theme - 1", "#FFFFFF", "serenity_footer_background_color", 1, 0),
("Theme - 1", "#676767", "serenity_footer_font_color", 1, 0),
("Theme - 1", "#676767", "serenity_footer_links_color", 1, 0),
("Theme - 1", "#676767", "serenity_footer_copyright_color", 1, 0),
("Theme - 1", "#e4e4e4", "serenity_footer_border_color", 1, 0),
("Theme - 1", "#03598f", "serenity_theme_color", 1, 0),
("Theme - 1", "#e6ecf0", "serenity_body_background_color", 1, 0),
("Theme - 1", "#5f727f", "serenity_font_color", 1, 0),
("Theme - 1", "#808D97", "serenity_font_color_light", 1, 0),
("Theme - 1", "#444f5d", "serenity_links_color", 1, 0),
("Theme - 1", "#03598f", "serenity_links_hover_color", 1, 0),
("Theme - 1", "#1c2735", "serenity_headline_color", 1, 0),
("Theme - 1", "#e2e4e6", "serenity_border_color", 1, 0),
("Theme - 1", "#FFFFFF", "serenity_box_background_color", 1, 0),
("Theme - 1", "#455B6B", "serenity_form_label_color", 1, 0),
("Theme - 1", "#fff", "serenity_input_background_color", 1, 0),
("Theme - 1", "#5f727f", "serenity_input_font_color", 1, 0),
("Theme - 1", "#d7d8da", "serenity_input_border_color", 1, 0),
("Theme - 1", "#03598f", "serenity_button_background_color", 1, 0),
("Theme - 1", "#fe4497", "serenity_button_background_color_hover", 1, 0),
("Theme - 1", "#FFFFFF", "serenity_button_font_color", 1, 0),
("Theme - 1", "#03598f", "serenity_button_border_color", 1, 0),
("Theme - 1", "#fff", "serenity_comments_background_color", 1, 0),
("Theme - 2", "2", "theme_color", 2, 0),
("Theme - 2", "2", "custom_theme_color", 2, 0),
("Theme - 2", "#0FB8AD", "serenity_header_background_color", 2, 0),
("Theme - 2", "#fff", "serenity_mainmenu_background_color", 2, 0),
("Theme - 2", "#909090", "serenity_mainmenu_links_color", 2, 0),
("Theme - 2", "#FFFFFF", "serenity_mainmenu_links_hover_color", 2, 0),
("Theme - 2", "#0fb8ad", "serenity_mainmenu_links_hover_background_color", 2, 0),
("Theme - 2", "#FFFFFF", "serenity_menu_tip_color", 2, 0),
("Theme - 2", "#FFFFFF", "serenity_minimenu_links_color", 2, 0),
("Theme - 2", "#0fb8ad", "serenity_minimenu_link_active_color", 2, 0),
("Theme - 2", "#FFFFFF", "serenity_footer_background_color", 2, 0),
("Theme - 2", "#676767", "serenity_footer_font_color", 2, 0),
("Theme - 2", "#676767", "serenity_footer_links_color", 2, 0),
("Theme - 2", "#676767", "serenity_footer_copyright_color", 2, 0),
("Theme - 2", "#e4e4e4", "serenity_footer_border_color", 2, 0),
("Theme - 2", "#0FB8AD", "serenity_theme_color", 2, 0),
("Theme - 2", "#e6ecf0", "serenity_body_background_color", 2, 0),
("Theme - 2", "#5f727f", "serenity_font_color", 2, 0),
("Theme - 2", "#808D97", "serenity_font_color_light", 2, 0),
("Theme - 2", "#444f5d", "serenity_links_color", 2, 0),
("Theme - 2", "#0FB8AD", "serenity_links_hover_color", 2, 0),
("Theme - 2", "#1c2735", "serenity_headline_color", 2, 0),
("Theme - 2", "#e2e4e6", "serenity_border_color", 2, 0),
("Theme - 2", "#FFFFFF", "serenity_box_background_color", 2, 0),
("Theme - 2", "#455B6B", "serenity_form_label_color", 2, 0),
("Theme - 2", "#fff", "serenity_input_background_color", 2, 0),
("Theme - 2", "#5f727f", "serenity_input_font_color", 2, 0),
("Theme - 2", "#d7d8da", "serenity_input_border_color", 2, 0),
("Theme - 2", "#0FB8AD", "serenity_button_background_color", 2, 0),
("Theme - 2", "#0fb8ad", "serenity_button_background_color_hover", 2, 0),
("Theme - 2", "#FFFFFF", "serenity_button_font_color", 2, 0),
("Theme - 2", "#1bc1d6", "serenity_button_border_color", 2, 0),
("Theme - 2", "#fff", "serenity_comments_background_color", 2, 0),
("Theme - 3", "3", "theme_color", 3, 0),
("Theme - 3", "3", "custom_theme_color", 3, 0),
("Theme - 3", "#E82F34", "serenity_header_background_color", 3, 0),
("Theme - 3", "#0D0D0D", "serenity_mainmenu_background_color", 3, 0),
("Theme - 3", "#FFFFFF", "serenity_mainmenu_links_color", 3, 0),
("Theme - 3", "#FFFFFF", "serenity_mainmenu_links_hover_color", 3, 0),
("Theme - 3", "#E82F34", "serenity_mainmenu_links_hover_background_color", 3, 0),
("Theme - 3", "#E82F34", "serenity_menu_tip_color", 3, 0),
("Theme - 3", "#DDDDDD", "serenity_minimenu_links_color", 3, 0),
("Theme - 3", "#E82F34", "serenity_minimenu_link_active_color", 3, 0),
("Theme - 3", "#151515", "serenity_footer_background_color", 3, 0),
("Theme - 3", "#FFFFFF", "serenity_footer_font_color", 3, 0),
("Theme - 3", "#999999", "serenity_footer_links_color", 3, 0),
("Theme - 3", "#999999", "serenity_footer_copyright_color", 3, 0),
("Theme - 3", "#151515", "serenity_footer_border_color", 3, 0),
("Theme - 3", "#E82F34", "serenity_theme_color", 3, 0),
("Theme - 3", "#222222", "serenity_body_background_color", 3, 0),
("Theme - 3", "#DDDDDD", "serenity_font_color", 3, 0),
("Theme - 3", "#CCCCCC", "serenity_font_color_light", 3, 0),
("Theme - 3", "#FFFFFF", "serenity_links_color", 3, 0),
("Theme - 3", "#E82F34", "serenity_links_hover_color", 3, 0),
("Theme - 3", "#DDDDDD", "serenity_headline_color", 3, 0),
("Theme - 3", "#383838", "serenity_border_color", 3, 0),
("Theme - 3", "#2F2F2F", "serenity_box_background_color", 3, 0),
("Theme - 3", "#DDDDDD", "serenity_form_label_color", 3, 0),
("Theme - 3", "#4C4C4C", "serenity_input_background_color", 3, 0),
("Theme - 3", "#DDDDDD", "serenity_input_font_color", 3, 0),
("Theme - 3", "#666666", "serenity_input_border_color", 3, 0),
("Theme - 3", "#E82F34", "serenity_button_background_color", 3, 0),
("Theme - 3", "#bb0005", "serenity_button_background_color_hover", 3, 0),
("Theme - 3", "#FFFFFF", "serenity_button_font_color", 3, 0),
("Theme - 3", "#E82F34", "serenity_button_border_color", 3, 0),
("Theme - 3", "#4C4C4C", "serenity_comments_background_color", 3, 0);
