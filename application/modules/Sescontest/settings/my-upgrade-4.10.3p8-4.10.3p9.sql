ALTER TABLE `engine4_sescontest_categories` ADD `member_levels` VARCHAR(255) NULL DEFAULT NULL;
UPDATE `engine4_sescontest_categories` SET `member_levels` = '1,2,3,4' WHERE `engine4_sescontest_categories`.`subcat_id` = 0 and  `engine4_sescontest_categories`.`subsubcat_id` = 0;
