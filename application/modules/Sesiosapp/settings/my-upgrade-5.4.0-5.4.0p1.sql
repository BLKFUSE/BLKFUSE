 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesiosapp
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my-upgrade-4.10.3p9-4.10.3p10.sql 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

INSERT IGNORE INTO `engine4_sesapi_menus`(`label`, `module`, `type`, `status`, `order`, `file_id`,`class`, `device`, `is_delete`, `visibility`, `module_name`, `version`) VALUES 
('Members','user','1','1','1','0','core_main_members','1','0','0','user','0'),
('Groups','group','1','1','4','0','core_main_group','1','0','0','group','0'),
('Blogs','blog','1','1','5','0','core_main_blog','1','0','0','blog','0'),
('Classifieds','classified','1','1','6','0','core_main_classified','1','0','0','classified','0'),
('Events','event','1','1','7','0','core_main_event','1','0','0','event','0'),
('Music','music','1','1','8','0','core_main_music','1','0','0','music','0'),
('Polls','poll','1','1','9','0','core_main_poll','1','0','0','poll','0'),
('Forums','Forum','1','1','54','0','core_main_forum','1','0','0','forum','0');