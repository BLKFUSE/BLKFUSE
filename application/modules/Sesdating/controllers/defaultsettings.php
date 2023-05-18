<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: defaultsettings.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


$db = Zend_Db_Table_Abstract::getDefaultAdapter();

$default_constants = array(
	'sesdating_responsive_layout'=> '1',
	'sesdating_body_background_image' => 'public/admin/blank.png',
	'sesdating_user_photo_round' => '1',
	'sesdating_feed_style' => '2',
	'sesdating_left_columns_width' => '240px',
	'sesdating_right_columns_width' => '240px',
	'sesdating_header_design' => '2',
	'sesdating_sidepanel_effect' => '2',
  'sesdating_footer_background_image' => 'public/admin/blank.png',
  'sesdating_header_background_color' => '#1D2632',
  'sesdating_menu_logo_font_color' => '#FFFFFF',
	'sesdating_mainmenu_background_color' => '#1D2632',
	'sesdating_mainmenu_links_color' => '#FFFFFF',
	'sesdating_mainmenu_links_hover_color' => '#FF5722',
	'sesdating_minimenu_links_color' => '#FF5722',
	'sesdating_minimenu_links_hover_color' => '#FFFFFF',
  'sesdating_minimenu_icon_background_color' => '#FFFFFF',
	'sesdating_minimenu_icon_background_active_color' => '#FFFFFF',
	'sesdating_minimenu_icon_color' => '#FF5722',
	'sesdating_minimenu_icon_active_color' => '#FF5722',
	'sesdating_header_searchbox_background_color' => '#ECEFF1',
	'sesdating_header_searchbox_text_color' => '#FFFFFF',
  'sesdating_toppanel_userinfo_background_color' => '#FF5722',
  'sesdating_toppanel_userinfo_font_color' => '#FFFFFF',
	'sesdating_login_popup_header_background_color' => '#FF5722',
	'sesdating_login_popup_header_font_color' => '#FFFFFF',
	'sesdating_footer_background_color' => '#26323F',
	'sesdating_footer_links_color' => '#B3B3B3',
	'sesdating_footer_links_hover_color' => '#FF5722',
	'sesdating_footer_border_color' => '#B3B3B3',
  'sesdating_theme_color' => '#FF5722',
	'sesdating_body_background_color' => '#101419',
	'sesdating_font_color' => '#CCCCCC',
	'sesdating_font_color_light' => '#FFFFFF',
	'sesdating_heading_color' => '#b1b1b1',
	'sesdating_links_color' => '#FFFFFF',
	'sesdating_links_hover_color' => '#FF5722',
	'sesdating_content_header_background_color' => '#1D2632',
	'sesdating_content_header_font_color' => '#b1b1b1',
	'sesdating_content_background_color' => '#1D2632',
	'sesdating_content_border_color' => '#334354',
	'sesdating_form_label_color' => '#CCCCCC',
	'sesdating_input_background_color' => '#CCCCCC',
	'sesdating_input_font_color' => '#243238',
	'sesdating_input_border_color' => '#CACACA',
	'sesdating_button_background_color' => '#FF5722',
	'sesdating_button_background_color_hover' => '#FFFFFF',
	'sesdating_button_font_color' => '#FFFFFF',
	'sesdating_button_font_hover_color' => '#243238',
	'sesdating_comment_background_color' => '#1D2632',
  'custom_theme_color' => '7',

  'sesdating_body_fontfamily' => 'Poppins',
	'sesdating_body_fontsize'  =>  '13px',
	'sesdating_heading_fontfamily' =>  'Poppins',
	'sesdating_heading_fontsize' =>  '17px',
	'sesdating_mainmenu_fontfamily' =>  'Poppins',
	'sesdating_mainmenu_fontsize' =>  '13px',
	'sesdating_tab_fontfamily' =>  'Poppins',
	'sesdating_tab_fontsize' =>  '13px',
);
Engine_Api::_()->sesdating()->readWriteXML('', '', $default_constants);

//Quick links footer
$menuitem_id = $db->select()
  ->from('engine4_core_menuitems', 'id')
  ->limit(1)
  ->order('id DESC')
  ->query()
  ->fetchColumn();

$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
("custom_'.$menuitem_id++.'", "core", "Albums", "", \'{"uri":"albums","icon":""}\', "sesdating_quicklinks_footer", "", 1, 1, 1),
("custom_'.$menuitem_id++.'", "core", "Blogs", "", \'{"uri":"blogs","icon":""}\', "sesdating_quicklinks_footer", "", 1, 1, 2),
("custom_'.$menuitem_id++.'", "core", "Events", "", \'{"uri":"events"}\', "sesdating_quicklinks_footer", "", 1, 1, 3),
("custom_'.$menuitem_id++.'", "core", "Videos", "", \'{"uri":"videos","icon":"","target":"","enabled":"1"}\', "sesdating_quicklinks_footer", "", 1, 1, 4),
("custom_'.$menuitem_id++.'", "core", "Music", "", \'{"uri":"music/album/home","icon":"","target":"","enabled":"1"}\', "sesdating_quicklinks_footer", "", 1, 1, 5);');

//Header Default Work
$content_id = $this->widgetCheck(array('widget_name' => 'sesdating.header', 'page_id' => '1'));

$minimenu = $this->widgetCheck(array('widget_name' => 'core.menu-mini', 'page_id' => '1'));
$menulogo = $this->widgetCheck(array('widget_name' => 'core.menu-logo', 'page_id' => '1'));
$mainmenu = $this->widgetCheck(array('widget_name' => 'core.menu-main', 'page_id' => '1'));
$search = $this->widgetCheck(array('widget_name' => 'core.search-mini', 'page_id' => '1'));

$parent_content_id = $db->select()
        ->from('engine4_core_content', 'content_id')
        ->where('type = ?', 'container')
        ->where('page_id = ?', '1')
        ->where('name = ?', 'main')
        ->limit(1)
        ->query()
        ->fetchColumn();
if (empty($content_id)) {
  if($minimenu)
    $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`content_id` = "'.$minimenu.'";');
  if($menulogo)
    $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`content_id` = "'.$menulogo.'";');
  if($mainmenu)
    $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`content_id` = "'.$mainmenu.'";');
  if($search)
    $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`content_id` = "'.$search.'";');
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesdating.header',
        'page_id' => 1,
        'parent_content_id' => $parent_content_id,
        'order' => 20,
    ));
}

//Footer Default Work
$footerContent_id = $this->widgetCheck(array('widget_name' => 'sesdating.menu-footer', 'page_id' => '2'));
$footerMenu = $this->widgetCheck(array('widget_name' => 'core.menu-footer', 'page_id' => '2'));
$parent_content_id = $db->select()
        ->from('engine4_core_content', 'content_id')
        ->where('type = ?', 'container')
        ->where('page_id = ?', '2')
        ->where('name = ?', 'main')
        ->limit(1)
        ->query()
        ->fetchColumn();
if (empty($footerContent_id)) {
  if($footerMenu)
    $db->query('DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`content_id` = "'.$footerMenu.'";');

    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesdating.menu-footer',
        'page_id' => 2,
        'parent_content_id' => $parent_content_id,
        'order' => 10,
    ));
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesbasic.scroll-bottom-top',
        'page_id' => 2,
        'parent_content_id' => $parent_content_id,
        'order' => 21,
    ));

}

$page_table = Engine_Api::_()->getDbtable('pages', 'core');
$page_table_name = $page_table->info('name');

$content_table = Engine_Api::_()->getDbtable('content', 'core');
$content_table_name = $content_table->info('name');

//Login Page
$select = new Zend_Db_Select($db);
$page_login_id = $select
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'user_auth_login')
        ->query()
        ->fetchColumn();
if ($page_login_id) {
  $db->query("UPDATE  `engine4_core_content` SET  `name` =  'sesdating.login' WHERE  `engine4_core_content`.`name` ='core.content' AND `engine4_core_content`.`page_id` ='".$page_login_id."' LIMIT 1");
}

//Member Home Page widget
$select = new Zend_Db_Select($db);
$page_id = $select
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'user_index_home')
        ->query()
        ->fetchColumn();
if ($page_id) {
  $top_contanier_id = $content_table->select()
          ->from($content_table_name, 'content_id')
          ->where('page_id =?', $page_id)
          ->where('name =?', 'top')
          ->query()
          ->fetchColumn();
  //Check top container id
  if (empty($top_contanier_id)) {
    $db->insert('engine4_core_content', array(
        'type' => 'container',
        'parent_content_id' => NULL,
        'name' => 'top',
        'page_id' => $page_id,
        'order' => 1,
    ));
    //get Last insert Id of top container
    $content_id = $db->lastInsertId('engine4_core_content');
    $middle_contanier_id = $content_table->select()
            ->from($content_table_name, 'content_id')
            ->where('page_id =?', $page_id)
            ->where('parent_content_id =?', $content_id)
            ->where('name =?', 'middle')
            ->query()
            ->fetchColumn();
    if (empty($middle_contanier_id)) {
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $content_id,
          'order' => 2,
      ));
      $content_id = $db->lastInsertId('engine4_core_content');
      if (!empty($content_id)) {
        $usercoverphotowidgetcontent_id = $content_table->select()
                ->from($content_table_name, array('content_id'))
                ->where('page_id =?', $page_id)
                ->where('name =?', 'sesdating.banner-slideshow')
                ->query()
                ->fetchColumn();
        if (empty($usercoverphotowidgetcontent_id)) {
          $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'sesdating.banner-slideshow',
            'page_id' => $page_id,
            'parent_content_id' => $content_id,
            'order' => 2,
            'params' => '{"banner_id":"1","full_width":"1","height":"250","title":"","nomobile":"0","name":"sesdating.banner-slideshow"}',
          ));
        }
      }
    }
  }
}
$db->query('ALTER TABLE `engine4_sesdating_customthemes` ADD `default` TINYINT(1) NOT NULL DEFAULT "1";');
//Default theme color for custom theme
$db->query('INSERT IGNORE INTO `engine4_sesdating_customthemes` (`customtheme_id`, `name`, `description`, `default`) VALUES
(1, "Theme - 1", \'a:44:{s:11:"theme_color";s:1:"5";s:18:"custom_theme_color";s:2:"18";s:13:"custom_themes";N;s:33:"sesdating_header_background_color";s:7:"#FFFFFF";s:30:"sesdating_menu_logo_font_color";s:7:"#005C99";s:35:"sesdating_mainmenu_background_color";s:7:"#FFFFFF";s:30:"sesdating_mainmenu_links_color";s:7:"#243238";s:36:"sesdating_mainmenu_links_hover_color";s:7:"#005C99";s:30:"sesdating_minimenu_links_color";s:7:"#243238";s:36:"sesdating_minimenu_links_hover_color";s:7:"#005C99";s:40:"sesdating_minimenu_icon_background_color";s:7:"#ECEFF1";s:47:"sesdating_minimenu_icon_background_active_color";s:7:"#005C99";s:29:"sesdating_minimenu_icon_color";s:7:"#243238";s:36:"sesdating_minimenu_icon_active_color";s:7:"#FFFFFF";s:43:"sesdating_header_searchbox_background_color";s:7:"#ECEFF1";s:37:"sesdating_header_searchbox_text_color";s:7:"#8DA1AB";s:44:"sesdating_toppanel_userinfo_background_color";s:7:"#005C99";s:38:"sesdating_toppanel_userinfo_font_color";s:7:"#FFFFFF";s:45:"sesdating_login_popup_header_background_color";s:7:"#005C99";s:39:"sesdating_login_popup_header_font_color";s:7:"#FFFFFF";s:33:"sesdating_footer_background_color";s:7:"#FFFFFF";s:28:"sesdating_footer_links_color";s:7:"#243238";s:34:"sesdating_footer_links_hover_color";s:7:"#005C99";s:29:"sesdating_footer_border_color";s:7:"#DDDDDD";s:21:"sesdating_theme_color";s:7:"#005C99";s:31:"sesdating_body_background_color";s:7:"#ECEFF1";s:20:"sesdating_font_color";s:7:"#243238";s:26:"sesdating_font_color_light";s:7:"#999999";s:23:"sesdating_heading_color";s:7:"#243238";s:21:"sesdating_links_color";s:7:"#243238";s:27:"sesdating_links_hover_color";s:7:"#005C99";s:41:"sesdating_content_header_background_color";s:7:"#FFFFFF";s:35:"sesdating_content_header_font_color";s:7:"#243238";s:34:"sesdating_content_background_color";s:7:"#FFFFFF";s:30:"sesdating_content_border_color";s:7:"#EBECEE";s:26:"sesdating_form_label_color";s:7:"#243238";s:32:"sesdating_input_background_color";s:7:"#FFFFFF";s:26:"sesdating_input_font_color";s:7:"#6D6D6D";s:28:"sesdating_input_border_color";s:7:"#CACACA";s:33:"sesdating_button_background_color";s:7:"#243238";s:39:"sesdating_button_background_color_hover";s:7:"#005C99";s:27:"sesdating_button_font_color";s:7:"#FFFFFF";s:33:"sesdating_button_font_hover_color";s:7:"#FFFFFF";s:34:"sesdating_comment_background_color";s:7:"#FDFDFD";}\', 0),
(2, "Theme - 2", \'a:44:{s:11:"theme_color";s:1:"5";s:18:"custom_theme_color";s:2:"19";s:13:"custom_themes";N;s:33:"sesdating_header_background_color";s:7:"#FFFFFF";s:30:"sesdating_menu_logo_font_color";s:7:"#FF5722";s:35:"sesdating_mainmenu_background_color";s:7:"#FFFFFF";s:30:"sesdating_mainmenu_links_color";s:7:"#243238";s:36:"sesdating_mainmenu_links_hover_color";s:7:"#FF5722";s:30:"sesdating_minimenu_links_color";s:7:"#243238";s:36:"sesdating_minimenu_links_hover_color";s:7:"#FF5722";s:40:"sesdating_minimenu_icon_background_color";s:7:"#ECEFF1";s:47:"sesdating_minimenu_icon_background_active_color";s:7:"#FF5722";s:29:"sesdating_minimenu_icon_color";s:7:"#243238";s:36:"sesdating_minimenu_icon_active_color";s:7:"#FFFFFF";s:43:"sesdating_header_searchbox_background_color";s:7:"#ECEFF1";s:37:"sesdating_header_searchbox_text_color";s:7:"#8DA1AB";s:44:"sesdating_toppanel_userinfo_background_color";s:7:"#FF5722";s:38:"sesdating_toppanel_userinfo_font_color";s:7:"#FFFFFF";s:45:"sesdating_login_popup_header_background_color";s:7:"#FF5722";s:39:"sesdating_login_popup_header_font_color";s:7:"#FFFFFF";s:33:"sesdating_footer_background_color";s:7:"#FFFFFF";s:28:"sesdating_footer_links_color";s:7:"#243238";s:34:"sesdating_footer_links_hover_color";s:7:"#FF5722";s:29:"sesdating_footer_border_color";s:7:"#DDDDDD";s:21:"sesdating_theme_color";s:7:"#FF5722";s:31:"sesdating_body_background_color";s:7:"#ECEFF1";s:20:"sesdating_font_color";s:7:"#243238";s:26:"sesdating_font_color_light";s:7:"#999999";s:23:"sesdating_heading_color";s:7:"#243238";s:21:"sesdating_links_color";s:7:"#243238";s:27:"sesdating_links_hover_color";s:7:"#FF5722";s:41:"sesdating_content_header_background_color";s:7:"#FFFFFF";s:35:"sesdating_content_header_font_color";s:7:"#243238";s:34:"sesdating_content_background_color";s:7:"#FFFFFF";s:30:"sesdating_content_border_color";s:7:"#EBECEE";s:26:"sesdating_form_label_color";s:7:"#243238";s:32:"sesdating_input_background_color";s:7:"#FFFFFF";s:26:"sesdating_input_font_color";s:7:"#6D6D6D";s:28:"sesdating_input_border_color";s:7:"#CACACA";s:33:"sesdating_button_background_color";s:7:"#243238";s:39:"sesdating_button_background_color_hover";s:7:"#FF5722";s:27:"sesdating_button_font_color";s:7:"#FFFFFF";s:33:"sesdating_button_font_hover_color";s:7:"#FFFFFF";s:34:"sesdating_comment_background_color";s:7:"#FDFDFD";}\', 0),
(3, "Theme - 3", \'a:44:{s:11:"theme_color";s:1:"5";s:18:"custom_theme_color";s:2:"20";s:13:"custom_themes";N;s:33:"sesdating_header_background_color";s:7:"#FFFFFF";s:30:"sesdating_menu_logo_font_color";s:7:"#D03E82";s:35:"sesdating_mainmenu_background_color";s:7:"#FFFFFF";s:30:"sesdating_mainmenu_links_color";s:7:"#243238";s:36:"sesdating_mainmenu_links_hover_color";s:7:"#D03E82";s:30:"sesdating_minimenu_links_color";s:7:"#243238";s:36:"sesdating_minimenu_links_hover_color";s:7:"#D03E82";s:40:"sesdating_minimenu_icon_background_color";s:7:"#ECEFF1";s:47:"sesdating_minimenu_icon_background_active_color";s:7:"#D03E82";s:29:"sesdating_minimenu_icon_color";s:7:"#243238";s:36:"sesdating_minimenu_icon_active_color";s:7:"#FFFFFF";s:43:"sesdating_header_searchbox_background_color";s:7:"#ECEFF1";s:37:"sesdating_header_searchbox_text_color";s:7:"#8DA1AB";s:44:"sesdating_toppanel_userinfo_background_color";s:7:"#D03E82";s:38:"sesdating_toppanel_userinfo_font_color";s:7:"#FFFFFF";s:45:"sesdating_login_popup_header_background_color";s:7:"#D03E82";s:39:"sesdating_login_popup_header_font_color";s:7:"#FFFFFF";s:33:"sesdating_footer_background_color";s:7:"#FFFFFF";s:28:"sesdating_footer_links_color";s:7:"#243238";s:34:"sesdating_footer_links_hover_color";s:7:"#D03E82";s:29:"sesdating_footer_border_color";s:7:"#DDDDDD";s:21:"sesdating_theme_color";s:7:"#D03E82";s:31:"sesdating_body_background_color";s:7:"#ECEFF1";s:20:"sesdating_font_color";s:7:"#243238";s:26:"sesdating_font_color_light";s:7:"#999999";s:23:"sesdating_heading_color";s:7:"#243238";s:21:"sesdating_links_color";s:7:"#243238";s:27:"sesdating_links_hover_color";s:7:"#D03E82";s:41:"sesdating_content_header_background_color";s:7:"#FFFFFF";s:35:"sesdating_content_header_font_color";s:7:"#243238";s:34:"sesdating_content_background_color";s:7:"#FFFFFF";s:30:"sesdating_content_border_color";s:7:"#EBECEE";s:26:"sesdating_form_label_color";s:7:"#243238";s:32:"sesdating_input_background_color";s:7:"#FFFFFF";s:26:"sesdating_input_font_color";s:7:"#6D6D6D";s:28:"sesdating_input_border_color";s:7:"#CACACA";s:33:"sesdating_button_background_color";s:7:"#243238";s:39:"sesdating_button_background_color_hover";s:7:"#D03E82";s:27:"sesdating_button_font_color";s:7:"#FFFFFF";s:33:"sesdating_button_font_hover_color";s:7:"#FFFFFF";s:34:"sesdating_comment_background_color";s:7:"#FDFDFD";}\', 0),
(4, "Theme - 4", \'a:44:{s:11:"theme_color";s:1:"5";s:18:"custom_theme_color";s:2:"21";s:13:"custom_themes";N;s:33:"sesdating_header_background_color";s:7:"#FFFFFF";s:30:"sesdating_menu_logo_font_color";s:7:"#354C17";s:35:"sesdating_mainmenu_background_color";s:7:"#FFFFFF";s:30:"sesdating_mainmenu_links_color";s:7:"#243238";s:36:"sesdating_mainmenu_links_hover_color";s:7:"#354C17";s:30:"sesdating_minimenu_links_color";s:7:"#243238";s:36:"sesdating_minimenu_links_hover_color";s:7:"#354C17";s:40:"sesdating_minimenu_icon_background_color";s:7:"#ECEFF1";s:47:"sesdating_minimenu_icon_background_active_color";s:7:"#354C17";s:29:"sesdating_minimenu_icon_color";s:7:"#243238";s:36:"sesdating_minimenu_icon_active_color";s:7:"#FFFFFF";s:43:"sesdating_header_searchbox_background_color";s:7:"#ECEFF1";s:37:"sesdating_header_searchbox_text_color";s:7:"#8DA1AB";s:44:"sesdating_toppanel_userinfo_background_color";s:7:"#354C17";s:38:"sesdating_toppanel_userinfo_font_color";s:7:"#FFFFFF";s:45:"sesdating_login_popup_header_background_color";s:7:"#354C17";s:39:"sesdating_login_popup_header_font_color";s:7:"#FFFFFF";s:33:"sesdating_footer_background_color";s:7:"#FFFFFF";s:28:"sesdating_footer_links_color";s:7:"#243238";s:34:"sesdating_footer_links_hover_color";s:7:"#354C17";s:29:"sesdating_footer_border_color";s:7:"#DDDDDD";s:21:"sesdating_theme_color";s:7:"#354C17";s:31:"sesdating_body_background_color";s:7:"#ECEFF1";s:20:"sesdating_font_color";s:7:"#243238";s:26:"sesdating_font_color_light";s:7:"#999999";s:23:"sesdating_heading_color";s:7:"#243238";s:21:"sesdating_links_color";s:7:"#243238";s:27:"sesdating_links_hover_color";s:7:"#354C17";s:41:"sesdating_content_header_background_color";s:7:"#FFFFFF";s:35:"sesdating_content_header_font_color";s:7:"#243238";s:34:"sesdating_content_background_color";s:7:"#FFFFFF";s:30:"sesdating_content_border_color";s:7:"#EBECEE";s:26:"sesdating_form_label_color";s:7:"#243238";s:32:"sesdating_input_background_color";s:7:"#FFFFFF";s:26:"sesdating_input_font_color";s:7:"#6D6D6D";s:28:"sesdating_input_border_color";s:7:"#CACACA";s:33:"sesdating_button_background_color";s:7:"#243238";s:39:"sesdating_button_background_color_hover";s:7:"#354C17";s:27:"sesdating_button_font_color";s:7:"#FFFFFF";s:33:"sesdating_button_font_hover_color";s:7:"#FFFFFF";s:34:"sesdating_comment_background_color";s:7:"#FDFDFD";}\', 0),
(5, "Theme - 5", \'a:44:{s:11:"theme_color";s:1:"5";s:18:"custom_theme_color";s:2:"22";s:13:"custom_themes";N;s:33:"sesdating_header_background_color";s:7:"#FFFFFF";s:30:"sesdating_menu_logo_font_color";s:7:"#2C6C73";s:35:"sesdating_mainmenu_background_color";s:7:"#FFFFFF";s:30:"sesdating_mainmenu_links_color";s:7:"#243238";s:36:"sesdating_mainmenu_links_hover_color";s:7:"#2C6C73";s:30:"sesdating_minimenu_links_color";s:7:"#243238";s:36:"sesdating_minimenu_links_hover_color";s:7:"#2C6C73";s:40:"sesdating_minimenu_icon_background_color";s:7:"#ECEFF1";s:47:"sesdating_minimenu_icon_background_active_color";s:7:"#2C6C73";s:29:"sesdating_minimenu_icon_color";s:7:"#243238";s:36:"sesdating_minimenu_icon_active_color";s:7:"#FFFFFF";s:43:"sesdating_header_searchbox_background_color";s:7:"#ECEFF1";s:37:"sesdating_header_searchbox_text_color";s:7:"#8DA1AB";s:44:"sesdating_toppanel_userinfo_background_color";s:7:"#2C6C73";s:38:"sesdating_toppanel_userinfo_font_color";s:7:"#FFFFFF";s:45:"sesdating_login_popup_header_background_color";s:7:"#2C6C73";s:39:"sesdating_login_popup_header_font_color";s:7:"#FFFFFF";s:33:"sesdating_footer_background_color";s:7:"#FFFFFF";s:28:"sesdating_footer_links_color";s:7:"#243238";s:34:"sesdating_footer_links_hover_color";s:7:"#2C6C73";s:29:"sesdating_footer_border_color";s:7:"#DDDDDD";s:21:"sesdating_theme_color";s:7:"#2C6C73";s:31:"sesdating_body_background_color";s:7:"#ECEFF1";s:20:"sesdating_font_color";s:7:"#243238";s:26:"sesdating_font_color_light";s:7:"#999999";s:23:"sesdating_heading_color";s:7:"#243238";s:21:"sesdating_links_color";s:7:"#243238";s:27:"sesdating_links_hover_color";s:7:"#2C6C73";s:41:"sesdating_content_header_background_color";s:7:"#FFFFFF";s:35:"sesdating_content_header_font_color";s:7:"#243238";s:34:"sesdating_content_background_color";s:7:"#FFFFFF";s:30:"sesdating_content_border_color";s:7:"#EBECEE";s:26:"sesdating_form_label_color";s:7:"#243238";s:32:"sesdating_input_background_color";s:7:"#FFFFFF";s:26:"sesdating_input_font_color";s:7:"#6D6D6D";s:28:"sesdating_input_border_color";s:7:"#CACACA";s:33:"sesdating_button_background_color";s:7:"#243238";s:39:"sesdating_button_background_color_hover";s:7:"#2C6C73";s:27:"sesdating_button_font_color";s:7:"#FFFFFF";s:33:"sesdating_button_font_hover_color";s:7:"#FFFFFF";s:34:"sesdating_comment_background_color";s:7:"#FDFDFD";}\', 0),
(6, "Theme - 6", \'a:44:{s:11:"theme_color";s:1:"5";s:18:"custom_theme_color";s:2:"23";s:13:"custom_themes";N;s:33:"sesdating_header_background_color";s:7:"#FFFFFF";s:30:"sesdating_menu_logo_font_color";s:7:"#BF3F34";s:35:"sesdating_mainmenu_background_color";s:7:"#FFFFFF";s:30:"sesdating_mainmenu_links_color";s:7:"#243238";s:36:"sesdating_mainmenu_links_hover_color";s:7:"#BF3F34";s:30:"sesdating_minimenu_links_color";s:7:"#243238";s:36:"sesdating_minimenu_links_hover_color";s:7:"#BF3F34";s:40:"sesdating_minimenu_icon_background_color";s:7:"#ECEFF1";s:47:"sesdating_minimenu_icon_background_active_color";s:7:"#BF3F34";s:29:"sesdating_minimenu_icon_color";s:7:"#243238";s:36:"sesdating_minimenu_icon_active_color";s:7:"#FFFFFF";s:43:"sesdating_header_searchbox_background_color";s:7:"#ECEFF1";s:37:"sesdating_header_searchbox_text_color";s:7:"#8DA1AB";s:44:"sesdating_toppanel_userinfo_background_color";s:7:"#BF3F34";s:38:"sesdating_toppanel_userinfo_font_color";s:7:"#FFFFFF";s:45:"sesdating_login_popup_header_background_color";s:7:"#BF3F34";s:39:"sesdating_login_popup_header_font_color";s:7:"#FFFFFF";s:33:"sesdating_footer_background_color";s:7:"#FFFFFF";s:28:"sesdating_footer_links_color";s:7:"#243238";s:34:"sesdating_footer_links_hover_color";s:7:"#BF3F34";s:29:"sesdating_footer_border_color";s:7:"#DDDDDD";s:21:"sesdating_theme_color";s:7:"#BF3F34";s:31:"sesdating_body_background_color";s:7:"#ECEFF1";s:20:"sesdating_font_color";s:7:"#243238";s:26:"sesdating_font_color_light";s:7:"#999999";s:23:"sesdating_heading_color";s:7:"#243238";s:21:"sesdating_links_color";s:7:"#243238";s:27:"sesdating_links_hover_color";s:7:"#BF3F34";s:41:"sesdating_content_header_background_color";s:7:"#FFFFFF";s:35:"sesdating_content_header_font_color";s:7:"#243238";s:34:"sesdating_content_background_color";s:7:"#FFFFFF";s:30:"sesdating_content_border_color";s:7:"#EBECEE";s:26:"sesdating_form_label_color";s:7:"#243238";s:32:"sesdating_input_background_color";s:7:"#FFFFFF";s:26:"sesdating_input_font_color";s:7:"#6D6D6D";s:28:"sesdating_input_border_color";s:7:"#CACACA";s:33:"sesdating_button_background_color";s:7:"#243238";s:39:"sesdating_button_background_color_hover";s:7:"#BF3F34";s:27:"sesdating_button_font_color";s:7:"#FFFFFF";s:33:"sesdating_button_font_hover_color";s:7:"#FFFFFF";s:34:"sesdating_comment_background_color";s:7:"#FDFDFD";}\', 0),
(7, "Theme - 7", \'a:44:{s:11:"theme_color";s:1:"5";s:18:"custom_theme_color";s:2:"24";s:13:"custom_themes";N;s:33:"sesdating_header_background_color";s:7:"#FFFFFF";s:30:"sesdating_menu_logo_font_color";s:7:"#B09800";s:35:"sesdating_mainmenu_background_color";s:7:"#FFFFFF";s:30:"sesdating_mainmenu_links_color";s:7:"#243238";s:36:"sesdating_mainmenu_links_hover_color";s:7:"#B09800";s:30:"sesdating_minimenu_links_color";s:7:"#243238";s:36:"sesdating_minimenu_links_hover_color";s:7:"#B09800";s:40:"sesdating_minimenu_icon_background_color";s:7:"#ECEFF1";s:47:"sesdating_minimenu_icon_background_active_color";s:7:"#B09800";s:29:"sesdating_minimenu_icon_color";s:7:"#243238";s:36:"sesdating_minimenu_icon_active_color";s:7:"#FFFFFF";s:43:"sesdating_header_searchbox_background_color";s:7:"#ECEFF1";s:37:"sesdating_header_searchbox_text_color";s:7:"#8DA1AB";s:44:"sesdating_toppanel_userinfo_background_color";s:7:"#B09800";s:38:"sesdating_toppanel_userinfo_font_color";s:7:"#FFFFFF";s:45:"sesdating_login_popup_header_background_color";s:7:"#B09800";s:39:"sesdating_login_popup_header_font_color";s:7:"#FFFFFF";s:33:"sesdating_footer_background_color";s:7:"#FFFFFF";s:28:"sesdating_footer_links_color";s:7:"#243238";s:34:"sesdating_footer_links_hover_color";s:7:"#B09800";s:29:"sesdating_footer_border_color";s:7:"#DDDDDD";s:21:"sesdating_theme_color";s:7:"#B09800";s:31:"sesdating_body_background_color";s:7:"#ECEFF1";s:20:"sesdating_font_color";s:7:"#243238";s:26:"sesdating_font_color_light";s:7:"#999999";s:23:"sesdating_heading_color";s:7:"#243238";s:21:"sesdating_links_color";s:7:"#243238";s:27:"sesdating_links_hover_color";s:7:"#B09800";s:41:"sesdating_content_header_background_color";s:7:"#FFFFFF";s:35:"sesdating_content_header_font_color";s:7:"#243238";s:34:"sesdating_content_background_color";s:7:"#FFFFFF";s:30:"sesdating_content_border_color";s:7:"#EBECEE";s:26:"sesdating_form_label_color";s:7:"#243238";s:32:"sesdating_input_background_color";s:7:"#FFFFFF";s:26:"sesdating_input_font_color";s:7:"#6D6D6D";s:28:"sesdating_input_border_color";s:7:"#CACACA";s:33:"sesdating_button_background_color";s:7:"#243238";s:39:"sesdating_button_background_color_hover";s:7:"#B09800";s:27:"sesdating_button_font_color";s:7:"#FFFFFF";s:33:"sesdating_button_font_hover_color";s:7:"#FFFFFF";s:34:"sesdating_comment_background_color";s:7:"#FDFDFD";}\', 0),
(8, "Theme - 8", \'a:44:{s:11:"theme_color";s:1:"5";s:18:"custom_theme_color";s:2:"25";s:13:"custom_themes";N;s:33:"sesdating_header_background_color";s:7:"#FFFFFF";s:30:"sesdating_menu_logo_font_color";s:7:"#83431B";s:35:"sesdating_mainmenu_background_color";s:7:"#FFFFFF";s:30:"sesdating_mainmenu_links_color";s:7:"#243238";s:36:"sesdating_mainmenu_links_hover_color";s:7:"#000000";s:30:"sesdating_minimenu_links_color";s:7:"#243238";s:36:"sesdating_minimenu_links_hover_color";s:7:"#83431B";s:40:"sesdating_minimenu_icon_background_color";s:7:"#ECEFF1";s:47:"sesdating_minimenu_icon_background_active_color";s:7:"#83431B";s:29:"sesdating_minimenu_icon_color";s:7:"#243238";s:36:"sesdating_minimenu_icon_active_color";s:7:"#FFFFFF";s:43:"sesdating_header_searchbox_background_color";s:7:"#ECEFF1";s:37:"sesdating_header_searchbox_text_color";s:7:"#8DA1AB";s:44:"sesdating_toppanel_userinfo_background_color";s:7:"#83431B";s:38:"sesdating_toppanel_userinfo_font_color";s:7:"#FFFFFF";s:45:"sesdating_login_popup_header_background_color";s:7:"#83431B";s:39:"sesdating_login_popup_header_font_color";s:7:"#FFFFFF";s:33:"sesdating_footer_background_color";s:7:"#FFFFFF";s:28:"sesdating_footer_links_color";s:7:"#243238";s:34:"sesdating_footer_links_hover_color";s:7:"#83431B";s:29:"sesdating_footer_border_color";s:7:"#DDDDDD";s:21:"sesdating_theme_color";s:7:"#83431B";s:31:"sesdating_body_background_color";s:7:"#ECEFF1";s:20:"sesdating_font_color";s:7:"#243238";s:26:"sesdating_font_color_light";s:7:"#999999";s:23:"sesdating_heading_color";s:7:"#243238";s:21:"sesdating_links_color";s:7:"#243238";s:27:"sesdating_links_hover_color";s:7:"#83431B";s:41:"sesdating_content_header_background_color";s:7:"#FFFFFF";s:35:"sesdating_content_header_font_color";s:7:"#243238";s:34:"sesdating_content_background_color";s:7:"#FFFFFF";s:30:"sesdating_content_border_color";s:7:"#EBECEE";s:26:"sesdating_form_label_color";s:7:"#243238";s:32:"sesdating_input_background_color";s:7:"#FFFFFF";s:26:"sesdating_input_font_color";s:7:"#6D6D6D";s:28:"sesdating_input_border_color";s:7:"#CACACA";s:33:"sesdating_button_background_color";s:7:"#243238";s:39:"sesdating_button_background_color_hover";s:7:"#83431B";s:27:"sesdating_button_font_color";s:7:"#FFFFFF";s:33:"sesdating_button_font_hover_color";s:7:"#FFFFFF";s:34:"sesdating_comment_background_color";s:7:"#FDFDFD";}\', 0),
(9, "Theme - 9", \'a:44:{s:11:"theme_color";s:1:"5";s:18:"custom_theme_color";s:2:"26";s:13:"custom_themes";N;s:33:"sesdating_header_background_color";s:7:"#222428";s:30:"sesdating_menu_logo_font_color";s:7:"#FF1D23";s:35:"sesdating_mainmenu_background_color";s:7:"#222428";s:30:"sesdating_mainmenu_links_color";s:7:"#FFFFFF";s:36:"sesdating_mainmenu_links_hover_color";s:7:"#FF1D23";s:30:"sesdating_minimenu_links_color";s:7:"#FFFFFF";s:36:"sesdating_minimenu_links_hover_color";s:7:"#FF1D23";s:40:"sesdating_minimenu_icon_background_color";s:7:"#36383D";s:47:"sesdating_minimenu_icon_background_active_color";s:7:"#FF1D23";s:29:"sesdating_minimenu_icon_color";s:7:"#FFFFFF";s:36:"sesdating_minimenu_icon_active_color";s:7:"#FFFFFF";s:43:"sesdating_header_searchbox_background_color";s:7:"#36383D";s:37:"sesdating_header_searchbox_text_color";s:7:"#FFFFFF";s:44:"sesdating_toppanel_userinfo_background_color";s:7:"#FF1D23";s:38:"sesdating_toppanel_userinfo_font_color";s:7:"#FFFFFF";s:45:"sesdating_login_popup_header_background_color";s:7:"#FF1D23";s:39:"sesdating_login_popup_header_font_color";s:7:"#FFFFFF";s:33:"sesdating_footer_background_color";s:7:"#222222";s:28:"sesdating_footer_links_color";s:7:"#B3B3B3";s:34:"sesdating_footer_links_hover_color";s:7:"#FF1D23";s:29:"sesdating_footer_border_color";s:7:"#FF1D23";s:21:"sesdating_theme_color";s:7:"#FF1D23";s:31:"sesdating_body_background_color";s:7:"#111418";s:20:"sesdating_font_color";s:7:"#F1F1F1";s:26:"sesdating_font_color_light";s:7:"#DDDDDD";s:23:"sesdating_heading_color";s:7:"#FFFFFF";s:21:"sesdating_links_color";s:7:"#FFFFFF";s:27:"sesdating_links_hover_color";s:7:"#FF1D23";s:41:"sesdating_content_header_background_color";s:7:"#222428";s:35:"sesdating_content_header_font_color";s:7:"#FFFFFF";s:34:"sesdating_content_background_color";s:7:"#222428";s:30:"sesdating_content_border_color";s:7:"#36383D";s:26:"sesdating_form_label_color";s:7:"#FFFFFF";s:32:"sesdating_input_background_color";s:7:"#222428";s:26:"sesdating_input_font_color";s:7:"#FFFFFF";s:28:"sesdating_input_border_color";s:7:"#36383D";s:33:"sesdating_button_background_color";s:7:"#FF1D23";s:39:"sesdating_button_background_color_hover";s:7:"#FF5252";s:27:"sesdating_button_font_color";s:7:"#FFFFFF";s:33:"sesdating_button_font_hover_color";s:7:"#FFFFFF";s:34:"sesdating_comment_background_color";s:7:"#1E1F23";}\', 0),
(10, "Theme - 10", \'a:44:{s:11:"theme_color";s:1:"5";s:18:"custom_theme_color";s:2:"27";s:13:"custom_themes";N;s:33:"sesdating_header_background_color";s:7:"#ED54A4";s:30:"sesdating_menu_logo_font_color";s:7:"#FFFFFF";s:35:"sesdating_mainmenu_background_color";s:7:"#FFFFFF";s:30:"sesdating_mainmenu_links_color";s:7:"#36383D";s:36:"sesdating_mainmenu_links_hover_color";s:7:"#4682B4";s:30:"sesdating_minimenu_links_color";s:7:"#FFFFFF";s:36:"sesdating_minimenu_links_hover_color";s:7:"#F1F1F1";s:40:"sesdating_minimenu_icon_background_color";s:7:"#F07AB1";s:47:"sesdating_minimenu_icon_background_active_color";s:7:"#FFFFFF";s:29:"sesdating_minimenu_icon_color";s:7:"#FFFFFF";s:36:"sesdating_minimenu_icon_active_color";s:7:"#ED54A4";s:43:"sesdating_header_searchbox_background_color";s:7:"#C7C7C7";s:37:"sesdating_header_searchbox_text_color";s:7:"#FFFFFF";s:44:"sesdating_toppanel_userinfo_background_color";s:7:"#ED54A4";s:38:"sesdating_toppanel_userinfo_font_color";s:7:"#FFFFFF";s:45:"sesdating_login_popup_header_background_color";s:7:"#ED54A4";s:39:"sesdating_login_popup_header_font_color";s:7:"#FFFFFF";s:33:"sesdating_footer_background_color";s:7:"#FFFFFF";s:28:"sesdating_footer_links_color";s:7:"#4682B4";s:34:"sesdating_footer_links_hover_color";s:7:"#ED54A4";s:29:"sesdating_footer_border_color";s:7:"#DDDDDD";s:21:"sesdating_theme_color";s:7:"#ED54A4";s:31:"sesdating_body_background_color";s:7:"#F5F5F5";s:20:"sesdating_font_color";s:7:"#243238";s:26:"sesdating_font_color_light";s:7:"#707070";s:23:"sesdating_heading_color";s:7:"#243238";s:21:"sesdating_links_color";s:7:"#243238";s:27:"sesdating_links_hover_color";s:7:"#4682B4";s:41:"sesdating_content_header_background_color";s:7:"#FFFFFF";s:35:"sesdating_content_header_font_color";s:7:"#243238";s:34:"sesdating_content_background_color";s:7:"#FFFFFF";s:30:"sesdating_content_border_color";s:7:"#EBECEE";s:26:"sesdating_form_label_color";s:7:"#243238";s:32:"sesdating_input_background_color";s:7:"#FFFFFF";s:26:"sesdating_input_font_color";s:7:"#6D6D6D";s:28:"sesdating_input_border_color";s:7:"#CACACA";s:33:"sesdating_button_background_color";s:7:"#4682B4";s:39:"sesdating_button_background_color_hover";s:7:"#E8288D";s:27:"sesdating_button_font_color";s:7:"#FFFFFF";s:33:"sesdating_button_font_hover_color";s:7:"#FFFFFF";s:34:"sesdating_comment_background_color";s:7:"#FDFDFD";}\', 0);');




$db->query('INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES
("sesdating.banner.bannerupimage", "public/admin/banner-img.png"),
("sesdating.banner.bgimage", "public/admin/theme-banner.jpg"),
("sesdating.banner.content", "Share your Photos and let them talk for you.,Music & Videos in the Community.,Create your events and sell tickets online.,Write new Blogs and share your thoughts."),
("sesdating.body.background.color", "#ECEFF1"),
("sesdating.body.background.image", "public/admin/blank.png"),
("sesdating.body.fontfamily", "Georgia, serif"),
("sesdating.body.fontsize", "13px"),
("sesdating.button.background.color.hover", "#243238"),
("sesdating.button.backgroundcolor", "#BF3F34"),
("sesdating.button.font.color", "#FFFFFF"),
("sesdating.button.font.hover.color", "#FFFFFF"),
("sesdating.comment.background.color", "#FDFDFD"),
("sesdating.content.background.color", "#FFFFFF"),
("sesdating.content.border.color", "#EBECEE"),
("sesdating.content.header.background.color", "#FFFFFF"),
("sesdating.content.header.font.color", "#243238"),
("sesdating.facebookurl", "http://www.facebook.com/"),
("sesdating.feature.bgimage", "public/admin/online-community.jpg"),
("sesdating.feature.caption", "Create your own events, book tickets online, share photos, videos, music, etc. Meet new people, join new groups, write your own blogs and more!"),
("sesdating.feature.content.0.caption", "Explore Popular Blogs"),
("sesdating.feature.content.0.description", "Write new blogs and share your ideas and stories with the World. Meet our bloggers here."),
("sesdating.feature.content.0.iconimage", "public/admin/blog-icon.png"),
("sesdating.feature.content.0.url", "blogs"),
("sesdating.feature.content.1.caption", "Events & Online Booking"),
("sesdating.feature.content.1.description", "Create, promote, manage, and host your meetings, conferences & special events, etc."),
("sesdating.feature.content.1.iconimage", "public/admin/event-icon.png"),
("sesdating.feature.content.1.url", "events"),
("sesdating.feature.content.2.caption", "Music Albums & Songs"),
("sesdating.feature.content.2.description", "What do you want to listen? Choose from awesome collection in our Community."),
("sesdating.feature.content.2.iconimage", "public/admin/music-icon.png"),
("sesdating.feature.content.2.url", "music/album/home"),
("sesdating.feature.content.3.caption", "Discover New Groups"),
("sesdating.feature.content.3.description", "Join groups based on your interest and get connetced with the world."),
("sesdating.feature.content.3.iconimage", "public/admin/group-icon.png"),
("sesdating.feature.content.3.url", "groups"),
("sesdating.feature.content.4.caption", "Videos, Channels & Playlists"),
("sesdating.feature.content.4.description", "We make Videos worth watching. Share videos with friends, family, & with the whole world."),
("sesdating.feature.content.4.iconimage", "public/admin/video-icon.png"),
("sesdating.feature.content.4.url", "videos"),
("sesdating.feature.content.5.caption", "Photos & Albums"),
("sesdating.feature.content.5.description", "Share your Stories with Photos! Let your photos do the talking for you."),
("sesdating.feature.content.5.iconimage", "public/admin/photo-icon.png"),
("sesdating.feature.content.5.url", "albums"),
("sesdating.feature.heading", "What is Inside Our Community?"),
("sesdating.feed.style", "1"),
("sesdating.font.color.light", "#999999"),
("sesdating.fontcolor", "#243238"),
("sesdating.footer.background.color", "#FFFFFF"),
("sesdating.footer.border.color", "#DDDDDD"),
("sesdating.footer.links.color", "#243238"),
("sesdating.footer.links.hover.color", "#BF3F34"),
("sesdating.form.label.color", "#243238"),
("sesdating.googlebody.fontfamily", "\"Poppins\""),
("sesdating.googlebody.fontsize", "13px"),
("sesdating.googlefonts", "1"),
("sesdating.googleheading.fontfamily", "\"Poppins\""),
("sesdating.googleheading.fontsize", "17px"),
("sesdating.googlemainmenu.fontfamily", "\"Poppins\""),
("sesdating.googlemainmenu.fontsize", "13px"),
("sesdating.googleplusurl", "http://plus.google.com/"),
("sesdating.googletab.fontfamily", "\"Poppins\""),
("sesdating.googletab.fontsize", "13px"),
("sesdating.header.background.color", "#BF3F34"),
("sesdating.header.design", "2"),
("sesdating.header.loggedin.options", "a:4:{i:0;s:6:"search";i:1;s:8:"miniMenu";i:2;s:8:"mainMenu";i:3;s:4:"logo";}"),
("sesdating.header.nonloggedin.options", "a:4:{i:0;s:6:"search";i:1;s:8:"miniMenu";i:2;s:8:"mainMenu";i:3;s:4:"logo";}"),
("sesdating.header.searchbox.background.color", "#ECEFF1"),
("sesdating.header.searchbox.text.color", "#FFFFFF"),
("sesdating.heading.color", "#243238"),
("sesdating.heading.fontfamily", "Georgia, serif"),
("sesdating.heading.fontsize", "17px"),
("sesdating.helpenable", "1"),
("sesdating.input.background.color", "#FFFFFF"),
("sesdating.input.border.color", "#CACACA"),
("sesdating.input.font.color", "#6D6D6D"),
("sesdating.left.columns.width", "240px"),
("sesdating.limit", "6"),
("sesdating.links.color", "#243238"),
("sesdating.links.hover.color", "#BF3F34"),
("sesdating.login.popup.header.background.color", "#BF3F34"),
("sesdating.login.popup.header.font.color", "#FFFFFF"),
("sesdating.loginsignupbgimage", "public/admin/blank.png"),
("sesdating.loginsignuplogo", "0"),
("sesdating.mainmenu.background.color", "#FFFFFF"),
("sesdating.mainmenu.fontfamily", "Georgia, serif"),
("sesdating.mainmenu.fontsize", "13px"),
("sesdating.mainmenu.links.color", "#243238"),
("sesdating.mainmenu.links.hover.color", "#BF3F34"),
("sesdating.member.link", "1"),
("sesdating.memeber.caption", "Meet our members and grow your network."),
("sesdating.memeber.heading", "Meet Our Members!"),
("sesdating.memeber.height", "100"),
("sesdating.memeber.width", "100"),
("sesdating.menu.img", "public/admin/blank.png"),
("sesdating.menu.logo.font.color", "#FFFFFF"),
("sesdating.menu.logo.top.space", "0px"),
("sesdating.menuinformation.img", "public/admin/info-bg2.png"),
("sesdating.minimenu.icon.active.color", "#243238"),
("sesdating.minimenu.icon.background.active.color", "#ECEFF1"),
("sesdating.minimenu.icon.background.color", "#ECEFF1"),
("sesdating.minimenu.icon.color", "#BF3F34"),
("sesdating.minimenu.links.color", "#BF3F34"),
("sesdating.minimenu.links.hover.color", "#243238"),
("sesdating.miniuserphotoround", "1"),
("sesdating.moretext", "More"),
("sesdating.pinteresturl", "https://www.pinterest.com/"),
("sesdating.popup.day", "5"),
("sesdating.popup.enable", "1"),
("sesdating.popupfixed", "0"),
("sesdating.popupsign", "1"),
("sesdating.quicklinksenable", "1"),
("sesdating.responsive.layout", "1"),
("sesdating.right.columns.width", "240px"),
("sesdating.sidepanel.effect", "2"),
("sesdating.sidepanel.showhide", "1"),
("sesdating.socialenable", "1"),
("sesdating.staticcontent", "Connect With the World!"),
("sesdating.submenu", "1"),
("sesdating.tab.fontfamily", "Georgia, serif"),
("sesdating.tab.fontsize", "15px"),
("sesdating.theme.color", "#BF3F34"),
("sesdating.toppanel.userinfo.background.color", "#BF3F34"),
("sesdating.toppanel.userinfo.font.color", "#FFFFFF"),
("sesdating.twitterurl", "https://www.twitter.com/"),
("sesdating.user.photo.round", "1");');
