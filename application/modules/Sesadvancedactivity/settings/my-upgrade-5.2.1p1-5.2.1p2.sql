UPDATE `engine4_core_settings` SET `value`=18 WHERE `name` = 'sesadvancedactivity.fonttextsize';
UPDATE `engine4_core_settings` SET `value`=5 WHERE `name` = 'sesadvancedactivity.visiblesearchfilter';
UPDATE `engine4_core_settings` SET `value`='000000' WHERE `name` = 'sesadvancedcomment.stickerstextcolor';

UPDATE `engine4_sesadvancedactivity_filterlists` SET `icon`= 'fas fa-sync' WHERE `filtertype` = 'all';
UPDATE `engine4_sesadvancedactivity_filterlists` SET `icon`= 'fas fa-network-wired' WHERE `filtertype` = 'my_networks';
UPDATE `engine4_sesadvancedactivity_filterlists` SET `icon`= 'fas fa-users' WHERE `filtertype` = 'my_friends';
UPDATE `engine4_sesadvancedactivity_filterlists` SET `icon`= 'fas fa-comment' WHERE `filtertype` = 'posts';
UPDATE `engine4_sesadvancedactivity_filterlists` SET `icon`= 'fas fa-save' WHERE `filtertype` = 'saved_feeds';
UPDATE `engine4_sesadvancedactivity_filterlists` SET `icon`= 'fas fa-shopping-cart' WHERE `filtertype` = 'post_self_buysell';
UPDATE `engine4_sesadvancedactivity_filterlists` SET `icon`= 'fas fa-clock' WHERE `filtertype` = 'scheduled_post';
UPDATE `engine4_sesadvancedactivity_filterlists` SET `icon`= 'fas fa-share-alt' WHERE `filtertype` = 'share';
UPDATE `engine4_sesadvancedactivity_filterlists` SET `icon`= 'fa fa-photo' WHERE `filtertype` = 'sesalbum' OR `filtertype` = 'album';
UPDATE `engine4_sesadvancedactivity_filterlists` SET `icon`= 'fa fa-video' WHERE `filtertype` = 'sesvideo' OR `filtertype` = 'video';
UPDATE `engine4_sesadvancedactivity_filterlists` SET `icon`= 'fa fa-music' WHERE `filtertype` = 'sesmusic' OR `filtertype` = 'music';
UPDATE `engine4_sesadvancedactivity_filterlists` SET `icon`= 'fa fa-file-alt' WHERE `filtertype` = 'post_self_file';
UPDATE `engine4_sesadvancedactivity_filterlists` SET `icon`= 'fa fa-comments' WHERE `filtertype` = 'sesblog' OR `filtertype` = 'blog';
