ALTER TABLE `engine4_video_videos` CHANGE `owner_type` `owner_type` VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `engine4_video_videos` CHANGE `rating` `rating` FLOAT NOT NULL DEFAULT '0';
ALTER TABLE `engine4_video_videos` CHANGE `file_id` `file_id` INT(11) UNSIGNED NOT NULL DEFAULT '0';
