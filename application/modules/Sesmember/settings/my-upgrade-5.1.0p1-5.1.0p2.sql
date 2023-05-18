 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my-upgrade-5.1.0p1-5.1.0p2.sql 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` = "sesmember_admin_main_birthday";
DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` = "sesmember_admin_main_adminpicks";
DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` = "sesmember_main_editormembers";
DELETE FROM `engine4_core_mailtemplates` WHERE `engine4_core_mailtemplates`.`type` = "sesmember_birthday_email";
DELETE FROM `engine4_core_tasks` WHERE `engine4_core_tasks`.`plugin` = "Sesmember_Plugin_Task_Jobs";
DROP TABLE IF EXISTS `engine4_sesmember_birthdayemailsends`;

DROP TABLE IF EXISTS `engine4_sesmember_compliments`;
DROP TABLE IF EXISTS `engine4_sesmember_usercompliments`;
DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` = "sesmember_admin_main_complements";
DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` = "sesmember_main_membercompliments";
DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` = "sesmember_compliments";
DELETE FROM `engine4_core_pages` WHERE `engine4_core_pages`.`name` = "sesmember_index_member-compliments";

DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "sesmember.popular-compliment-members";
DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "sesmember.profile-user-compliments";
DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`name` = "sesmember.profile-compliments";

ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`follow_count`);
ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`location`);
ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`rating`);
ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`user_verified`);
ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`cool_count`);
ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`funny_count`);
ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`useful_count`);
ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`featured`);
ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`sponsored`);
ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`vip`);
ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`offtheday`);
ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`starttime`);
ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`endtime`);
ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`adminpicks`);
ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`order`);

ALTER TABLE `engine4_sesmember_featuredphotos` ADD INDEX(`user_id`);
ALTER TABLE `engine4_sesmember_featuredphotos` ADD INDEX(`photo_id`);

ALTER TABLE `engine4_sesmember_follows` ADD INDEX(`resource_approved`);
ALTER TABLE `engine4_sesmember_follows` ADD INDEX(`user_approved`);

ALTER TABLE `engine4_sesmember_homepages` ADD INDEX(`type`);
ALTER TABLE `engine4_sesmember_homepages` ADD INDEX(`page_id`);
ALTER TABLE `engine4_sesmember_homepages` ADD INDEX(`member_levels`);

ALTER TABLE `engine4_sesmember_profilephotos` ADD INDEX(`profiletype_id`);
ALTER TABLE `engine4_sesmember_profilephotos` ADD INDEX(`photo_id`);

ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`owner_id`);
ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`user_id`);
ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`recommended`);
ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`like_count`);
ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`comment_count`);
ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`view_count`);
ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`rating`);
ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`featured`);
ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`verified`);
ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`oftheday`);
ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`starttime`);
ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`endtime`);
ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`creation_date`);
ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`useful_count`);
ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`funny_count`);
ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`cool_count`);
