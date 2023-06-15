UPDATE `engine4_activity_stream` SET `object_type` = 'contest' WHERE `engine4_activity_stream`.`object_type` = 'sescontest_contest';
UPDATE `engine4_activity_attachments` SET `type` = 'contest' WHERE `engine4_activity_attachments`.`type` = 'sescontest_contest'; 
UPDATE `engine4_activity_actions` SET `object_type` = 'contest' WHERE `engine4_activity_actions`.`object_type` = 'sescontest_contest'; 
UPDATE `engine4_activity_actions` SET `object_type` = 'participant' WHERE `engine4_activity_actions`.`object_type` = 'sescontest_participant'; 
UPDATE `engine4_activity_notifications` SET `object_type` = 'contest' WHERE `engine4_activity_notifications`.`object_type` = 'sescontest_contest'; 
UPDATE `engine4_activity_notifications` SET `object_type` = 'participant' WHERE `engine4_activity_notifications`.`object_type` = 'sescontest_participant'; 
UPDATE `engine4_core_likes` SET `resource_type` = 'contest' WHERE `engine4_core_likes`.`resource_type` = 'sescontest_contest'; 
UPDATE `engine4_core_likes` SET `resource_type` = 'participant' WHERE `engine4_core_likes`.`resource_type` = 'sescontest_participant';
UPDATE `engine4_core_comments` SET `resource_type` = 'contest' WHERE `engine4_core_comments`.`resource_type` = 'sescontest_contest'; 
UPDATE `engine4_core_comments` SET `resource_type` = 'participant' WHERE `engine4_core_comments`.`resource_type` = 'sescontest_participant';
UPDATE `engine4_sescontest_favourites` SET `resource_type` = 'contest' WHERE `engine4_sescontest_favourites`.`resource_type` = 'sescontest_contest'; 
UPDATE `engine4_sescontest_favourites` SET `resource_type` = 'participant' WHERE `engine4_sescontest_favourites`.`resource_type` = 'sescontest_participant';
UPDATE `engine4_sescontest_followers` SET `resource_type` = 'contest' WHERE `engine4_sescontest_followers`.`resource_type` = 'sescontest_contest'; 
UPDATE `engine4_sescontest_saves` SET `resource_type` = 'contest' WHERE `engine4_sescontest_saves`.`resource_type` = 'sescontest_contest'; 
UPDATE `engine4_authorization_allow` SET `resource_type` = 'contest' WHERE `engine4_authorization_allow`.`resource_type` = 'sescontest_contest';
UPDATE `engine4_authorization_permissions` SET `type` = 'contest' WHERE `engine4_authorization_permissions`.`type` = 'sescontest_contest';
UPDATE `engine4_core_search` SET `type` = 'contest' WHERE `engine4_core_search`.`type` = 'sescontest_contest';
UPDATE `engine4_core_tagmaps` SET `resource_type` = 'contest' WHERE `engine4_core_tagmaps`.`resource_type` = 'sescontest_contest';
UPDATE `engine4_storage_files` SET `parent_type` = 'contest' WHERE `engine4_storage_files`.`parent_type` = 'sescontest_contest';
UPDATE `engine4_sescontest_recentlyviewitems` SET `resource_type` = 'contest' WHERE `engine4_sescontest_recentlyviewitems`.`resource_type` = 'sescontest_contest'; 
UPDATE `engine4_authorization_permissions` SET `type` = 'participant' WHERE `engine4_authorization_permissions`.`type` = 'sescontest_participant'; 
UPDATE `engine4_authorization_permissions` SET `name` = 'auth_participant' WHERE `engine4_authorization_permissions`.`name` = 'allow_participant';  

UPDATE `engine4_authorization_permissions` SET `name` = 'textEntryPhoto' WHERE `engine4_authorization_permissions`.`name` = 'sescontest_contest_text_entryphoto';  

UPDATE `engine4_authorization_permissions` SET `name` = 'photoEntryPhoto' WHERE `engine4_authorization_permissions`.`name` = 'sescontest_contest_photo_entryphoto';
  
UPDATE `engine4_authorization_permissions` SET `name` = 'musicEntryPhoto' WHERE `engine4_authorization_permissions`.`name` = 'sescontest_contest_music_entryphoto';  

UPDATE `engine4_authorization_permissions` SET `name` = 'videoEntryPhoto' WHERE `engine4_authorization_permissions`.`name` = 'sescontest_contest_video_entryphoto'; 

UPDATE `engine4_authorization_permissions` SET `name` = 'canEntryMultvote' WHERE `engine4_authorization_permissions`.`name` = 'allow_entry_multivote'; 
 
UPDATE `engine4_authorization_permissions` SET `name` = 'voteInterval' WHERE `engine4_authorization_permissions`.`name` = 'vote_entry_interval';  

UPDATE `engine4_authorization_permissions` SET `name` = 'juryVoteWeight' WHERE `engine4_authorization_permissions`.`name` = 'jury_votecount_weight'; 

UPDATE `engine4_authorization_permissions` SET `name` = 'auth_contstyle' WHERE `engine4_authorization_permissions`.`name` = 'contest_choose_style';  

UPDATE `engine4_authorization_permissions` SET `name` = 'chooselayout' WHERE `engine4_authorization_permissions`.`name` = 'contest_chooselayout';  

UPDATE `engine4_authorization_permissions` SET `name` = 'style' WHERE `engine4_authorization_permissions`.`name` = 'contest_style_type';

UPDATE `engine4_authorization_permissions` SET `name` = 'textContPhoto' WHERE `engine4_authorization_permissions`.`name` = 'sescontest_contest_text_photo';
  
UPDATE `engine4_authorization_permissions` SET `name` = 'photoContPhoto' WHERE `engine4_authorization_permissions`.`name` = 'sescontest_contest_photo_photo'; 
 
UPDATE `engine4_authorization_permissions` SET `name` = 'musicContPhoto' WHERE `engine4_authorization_permissions`.`name` = 'sescontest_contest_music_photo';  

UPDATE `engine4_authorization_permissions` SET `name` = 'videoContPhoto' WHERE `engine4_authorization_permissions`.`name` = 'sescontest_contest_video_photo';  

UPDATE `engine4_authorization_permissions` SET `name` = 'textContCPhoto' WHERE `engine4_authorization_permissions`.`name` = 'sescontest_contest_text_coverphoto';
  
UPDATE `engine4_authorization_permissions` SET `name` = 'photoContCPhoto' WHERE `engine4_authorization_permissions`.`name` = 'sescontest_contest_photo_coverphoto'; 
 
UPDATE `engine4_authorization_permissions` SET `name` = 'musicContCPhoto' WHERE `engine4_authorization_permissions`.`name` = 'sescontest_contest_music_coverphoto';  

UPDATE `engine4_authorization_permissions` SET `name` = 'videoContCPhoto' WHERE `engine4_authorization_permissions`.`name` = 'sescontest_contest_video_coverphoto';

UPDATE `engine4_authorization_permissions` SET `name` = 'autosponsored' WHERE `engine4_authorization_permissions`.`name` = 'contest_sponsored'; 
 
UPDATE `engine4_authorization_permissions` SET `name` = 'contactinfo' WHERE `engine4_authorization_permissions`.`name` = 'contest_contactinfo'; 
 
UPDATE `engine4_authorization_permissions` SET `name` = 'contparticipant' WHERE `engine4_authorization_permissions`.`name` = 'contest_enable_contactparticipant';
 
UPDATE `engine4_authorization_permissions` SET `name` = 'juryMemberCount' WHERE `engine4_authorization_permissions`.`name` = 'jury_member_count';

RENAME TABLE `engine4_sescontest_contest_fields_maps` TO `engine4_contest_fields_maps`;
RENAME TABLE `engine4_sescontest_contest_fields_meta` TO `engine4_contest_fields_meta`;
RENAME TABLE `engine4_sescontest_contest_fields_options` TO `engine4_contest_fields_options`;
RENAME TABLE `engine4_sescontest_contest_fields_search` TO `engine4_contest_fields_search`;
RENAME TABLE `engine4_sescontest_contest_fields_values` TO `engine4_contest_fields_values`;

UPDATE `engine4_payment_products` SET `extension_type` = 'contest' WHERE `engine4_payment_products`.`extension_type` = 'sescontest_contest';

UPDATE `engine4_payment_orders` SET `source_type` = 'contest' WHERE `engine4_payment_orders`.`source_type` = 'sescontest_contest';