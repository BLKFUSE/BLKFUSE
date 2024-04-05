ALTER TABLE `engine4_sesadvancedactivity_activitycomments` ADD INDEX(`file_id`);
ALTER TABLE `engine4_sesadvancedactivity_activitycomments` ADD INDEX(`parent_id`);
ALTER TABLE `engine4_sesadvancedactivity_activitycomments` ADD INDEX(`gif_id`);
ALTER TABLE `engine4_sesadvancedactivity_activitycomments` ADD INDEX(`emoji_id`);
ALTER TABLE `engine4_sesadvancedactivity_activitycomments` ADD INDEX(`reply_count`);
ALTER TABLE `engine4_sesadvancedactivity_activitycomments` ADD INDEX(`preview`);
ALTER TABLE `engine4_sesadvancedactivity_activitycomments` ADD INDEX(`showpreview`);
ALTER TABLE `engine4_sesadvancedactivity_activitycomments` ADD INDEX(`vote_up_count`);
ALTER TABLE `engine4_sesadvancedactivity_activitycomments` ADD INDEX(`vote_down_count`);

ALTER TABLE `engine4_sesadvancedactivity_activitylikes` ADD INDEX(`type`);

ALTER TABLE `engine4_sesadvancedactivity_buysells` ADD INDEX(`user_id`);
ALTER TABLE `engine4_sesadvancedactivity_buysells` ADD INDEX(`action_id`);
ALTER TABLE `engine4_sesadvancedactivity_buysells` ADD INDEX(`is_sold`);
ALTER TABLE `engine4_sesadvancedactivity_buysells` ADD INDEX(`buy`);

ALTER TABLE `engine4_sesadvancedactivity_corecomments` ADD INDEX(`file_id`);
ALTER TABLE `engine4_sesadvancedactivity_corecomments` ADD INDEX(`parent_id`);
ALTER TABLE `engine4_sesadvancedactivity_corecomments` ADD INDEX(`gif_id`);
ALTER TABLE `engine4_sesadvancedactivity_corecomments` ADD INDEX(`emoji_id`);
ALTER TABLE `engine4_sesadvancedactivity_corecomments` ADD INDEX(`reply_count`);
ALTER TABLE `engine4_sesadvancedactivity_corecomments` ADD INDEX(`preview`);
ALTER TABLE `engine4_sesadvancedactivity_corecomments` ADD INDEX(`showpreview`);
ALTER TABLE `engine4_sesadvancedactivity_corecomments` ADD INDEX(`vote_up_count`);
ALTER TABLE `engine4_sesadvancedactivity_corecomments` ADD INDEX(`vote_down_count`);

ALTER TABLE `engine4_sesadvancedactivity_corelikes` ADD INDEX(`type`);

ALTER TABLE `engine4_sesadvancedactivity_details` ADD INDEX(`commentable`);
ALTER TABLE `engine4_sesadvancedactivity_details` ADD INDEX(`schedule_time`);
ALTER TABLE `engine4_sesadvancedactivity_details` ADD INDEX(`sesapproved`);
ALTER TABLE `engine4_sesadvancedactivity_details` ADD INDEX(`reaction_id`);
ALTER TABLE `engine4_sesadvancedactivity_details` ADD INDEX(`sesresource_id`);
ALTER TABLE `engine4_sesadvancedactivity_details` ADD INDEX(`sesresource_type`);
ALTER TABLE `engine4_sesadvancedactivity_details` ADD INDEX(`is_community_ad`);
ALTER TABLE `engine4_sesadvancedactivity_details` ADD INDEX(`vote_up_count`);
ALTER TABLE `engine4_sesadvancedactivity_details` ADD INDEX(`vote_down_count`);
ALTER TABLE `engine4_sesadvancedactivity_details` ADD INDEX(`feedbg_id`);
ALTER TABLE `engine4_sesadvancedactivity_details` ADD INDEX(`image_id`);
ALTER TABLE `engine4_sesadvancedactivity_details` ADD INDEX(`view_count`);
ALTER TABLE `engine4_sesadvancedactivity_details` ADD INDEX(`share_count`);
ALTER TABLE `engine4_sesadvancedactivity_details` ADD INDEX(`posting_type`);

ALTER TABLE `engine4_sesadvancedactivity_events` ADD INDEX(`date`);
ALTER TABLE `engine4_sesadvancedactivity_events` ADD INDEX(`active`);
ALTER TABLE `engine4_sesadvancedactivity_events` ADD INDEX(`file_id`);
ALTER TABLE `engine4_sesadvancedactivity_events` ADD INDEX(`recurring`);
ALTER TABLE `engine4_sesadvancedactivity_events` ADD INDEX(`creation_date`);
ALTER TABLE `engine4_sesadvancedactivity_events` ADD INDEX(`visibility`);
ALTER TABLE `engine4_sesadvancedactivity_events` ADD INDEX(`starttime`);
ALTER TABLE `engine4_sesadvancedactivity_events` ADD INDEX(`endtime`);

ALTER TABLE `engine4_sesadvancedactivity_feelingposts` ADD INDEX(`feeling_id`);
ALTER TABLE `engine4_sesadvancedactivity_feelingposts` ADD INDEX(`feelingicon_id`);
ALTER TABLE `engine4_sesadvancedactivity_feelingposts` ADD INDEX(`resource_type`);
ALTER TABLE `engine4_sesadvancedactivity_feelingposts` ADD INDEX(`action_id`);
ALTER TABLE `engine4_sesadvancedactivity_feelingposts` ADD INDEX(`feeling_custom`);
ALTER TABLE `engine4_sesadvancedactivity_feelingposts` ADD INDEX(`feeling_customtext`);

ALTER TABLE `engine4_sesadvancedactivity_files` ADD INDEX(`user_id`);
ALTER TABLE `engine4_sesadvancedactivity_files` ADD INDEX(`item_id`);

ALTER TABLE `engine4_sesadvancedactivity_filterlists` ADD INDEX(`module`);
ALTER TABLE `engine4_sesadvancedactivity_filterlists` ADD INDEX(`active`);
ALTER TABLE `engine4_sesadvancedactivity_filterlists` ADD INDEX(`is_delete`);
ALTER TABLE `engine4_sesadvancedactivity_filterlists` ADD INDEX(`order`);
ALTER TABLE `engine4_sesadvancedactivity_filterlists` ADD INDEX(`file_id`);

ALTER TABLE `engine4_sesadvancedactivity_hashtags` ADD INDEX(`action_id`);
ALTER TABLE `engine4_sesadvancedactivity_hashtags` ADD INDEX(`title`);

ALTER TABLE `engine4_sesadvancedactivity_hides` ADD INDEX(`resource_id`);
ALTER TABLE `engine4_sesadvancedactivity_hides` ADD INDEX(`resource_type`);
ALTER TABLE `engine4_sesadvancedactivity_hides` ADD INDEX(`user_id`);
ALTER TABLE `engine4_sesadvancedactivity_hides` ADD INDEX(`subject_id`);

ALTER TABLE `engine4_sesadvancedactivity_links` ADD INDEX(`ses_aaf_gif`);

ALTER TABLE `engine4_sesadvancedactivity_pinposts` ADD INDEX(`action_id`);
ALTER TABLE `engine4_sesadvancedactivity_pinposts` ADD INDEX(`resource_id`);
ALTER TABLE `engine4_sesadvancedactivity_pinposts` ADD INDEX(`resource_type`);

ALTER TABLE `engine4_sesadvancedactivity_savefeeds` ADD INDEX(`action_id`);
ALTER TABLE `engine4_sesadvancedactivity_savefeeds` ADD INDEX(`user_id`);

ALTER TABLE `engine4_sesadvancedactivity_tagitems` ADD INDEX(`resource_id`);
ALTER TABLE `engine4_sesadvancedactivity_tagitems` ADD INDEX(`resource_type`);
ALTER TABLE `engine4_sesadvancedactivity_tagitems` ADD INDEX(`user_id`);
ALTER TABLE `engine4_sesadvancedactivity_tagitems` ADD INDEX(`action_id`);

ALTER TABLE `engine4_sesadvancedactivity_tagusers` ADD INDEX(`user_id`);
ALTER TABLE `engine4_sesadvancedactivity_tagusers` ADD INDEX(`action_id`);

ALTER TABLE `engine4_sesadvancedactivity_targetpost` ADD INDEX(`location_send`);
ALTER TABLE `engine4_sesadvancedactivity_targetpost` ADD INDEX(`country_name`);
ALTER TABLE `engine4_sesadvancedactivity_targetpost` ADD INDEX(`city_name`);
ALTER TABLE `engine4_sesadvancedactivity_targetpost` ADD INDEX(`location_city`);
ALTER TABLE `engine4_sesadvancedactivity_targetpost` ADD INDEX(`location_country`);
ALTER TABLE `engine4_sesadvancedactivity_targetpost` ADD INDEX(`gender_send`);
ALTER TABLE `engine4_sesadvancedactivity_targetpost` ADD INDEX(`age_min_send`);
ALTER TABLE `engine4_sesadvancedactivity_targetpost` ADD INDEX(`age_max_send`);
ALTER TABLE `engine4_sesadvancedactivity_targetpost` ADD INDEX(`lat`);
ALTER TABLE `engine4_sesadvancedactivity_targetpost` ADD INDEX(`lng`);
