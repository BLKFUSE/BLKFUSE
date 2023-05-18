ALTER TABLE `engine4_sesvideo_categories` ADD `member_levels` VARCHAR(255) NULL DEFAULT NULL;
UPDATE `engine4_sesvideo_categories` SET `member_levels` = '1,2,3,4' WHERE `engine4_sesvideo_categories`.`subcat_id` = 0 and  `engine4_sesvideo_categories`.`subsubcat_id` = 0;
