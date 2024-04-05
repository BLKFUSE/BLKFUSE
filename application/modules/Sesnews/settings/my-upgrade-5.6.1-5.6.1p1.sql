UPDATE `engine4_activity_actiontypes` SET `body` = '{item:$subject} added a news {item:$object}:' WHERE `engine4_activity_actiontypes`.`type` = 'sesnews_new';
