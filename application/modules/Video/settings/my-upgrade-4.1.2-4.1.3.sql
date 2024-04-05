ALTER TABLE `engine4_video_videos`
  ADD COLUMN `parent_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci default NULL,
  ADD COLUMN `parent_id` int(11) unsigned default NULL;

