UPDATE `engine4_activity_actiontypes` SET `type` = 'sesvideo_video_favourite' WHERE `engine4_activity_actiontypes`.`type` = 'sesvideo_favourite_video';

UPDATE `engine4_activity_actions` SET `type` = 'sesvideo_video_favourite' WHERE `engine4_activity_actions`.`type` = 'sesvideo_favourite_video';

UPDATE `engine4_activity_actiontypes` SET `type` = 'sesvideo_chanel_favourite' WHERE `engine4_activity_actiontypes`.`type` = 'sesvideo_favourite_chanel';

UPDATE `engine4_activity_actions` SET `type` = 'sesvideo_chanel_favourite' WHERE `engine4_activity_actions`.`type` = 'sesvideo_favourite_chanel';

UPDATE `engine4_activity_actiontypes` SET `type` = 'sesvideo_playlist_favourite' WHERE `engine4_activity_actiontypes`.`type` = 'sesvideo_favourite_playlist';

UPDATE `engine4_activity_actions` SET `type` = 'sesvideo_playlist_favourite' WHERE `engine4_activity_actions`.`type` = 'sesvideo_favourite_playlist';


UPDATE `engine4_activity_notificationtypes` SET `type` = 'sesvideo_video_favourite' WHERE `engine4_activity_notificationtypes`.`type` = 'sesvideo_favourite_video';
UPDATE `engine4_activity_notifications` SET `type` = 'sesvideo_video_favourite' WHERE `engine4_activity_notifications`.`type` = 'sesvideo_favourite_video';

UPDATE `engine4_activity_notificationtypes` SET `type` = 'sesvideo_chanel_favourite' WHERE `engine4_activity_notificationtypes`.`type` = 'sesvideo_favourite_chanel';
UPDATE `engine4_activity_notifications` SET `type` = 'sesvideo_chanel_favourite' WHERE `engine4_activity_notifications`.`type` = 'sesvideo_favourite_chanel';

UPDATE `engine4_activity_notificationtypes` SET `type` = 'sesvideo_playlist_favourite' WHERE `engine4_activity_notificationtypes`.`type` = 'sesvideo_favourite_playlist';
UPDATE `engine4_activity_notifications` SET `type` = 'sesvideo_playlist_favourite' WHERE `engine4_activity_notifications`.`type` = 'sesvideo_favourite_playlist';

