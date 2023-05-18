UPDATE `engine4_activity_actiontypes` SET `type` = 'sesmusic_album_favourite' WHERE `engine4_activity_actiontypes`.`type` = 'sesmusic_favouritealbum';

UPDATE `engine4_activity_actions` SET `type` = 'sesmusic_album_favourite' WHERE `engine4_activity_actions`.`type` = 'sesmusic_favouritealbum';

UPDATE `engine4_activity_actiontypes` SET `type` = 'sesmusic_artist_favourite' WHERE `engine4_activity_actiontypes`.`type` = 'sesmusic_favouriteartist';

UPDATE `engine4_activity_actions` SET `type` = 'sesmusic_artist_favourite' WHERE `engine4_activity_actions`.`type` = 'sesmusic_favouriteartist';

UPDATE `engine4_activity_actiontypes` SET `type` = 'sesmusic_playlist_favourite' WHERE `engine4_activity_actiontypes`.`type` = 'sesmusic_favouriteplaylist';

UPDATE `engine4_activity_actions` SET `type` = 'sesmusic_playlist_favourite' WHERE `engine4_activity_actions`.`type` = 'sesmusic_favouriteplaylist';

UPDATE `engine4_activity_actiontypes` SET `type` = 'sesmusic_album_rating' WHERE `engine4_activity_actiontypes`.`type` = 'sesmusic_albumrating';

UPDATE `engine4_activity_actions` SET `type` = 'sesmusic_album_rating' WHERE `engine4_activity_actions`.`type` = 'sesmusic_albumrating';

UPDATE `engine4_activity_actiontypes` SET `type` = 'sesmusic_albumsong_rating' WHERE `engine4_activity_actiontypes`.`type` = 'sesmusic_songrating';

UPDATE `engine4_activity_actions` SET `type` = 'sesmusic_albumsong_rating' WHERE `engine4_activity_actions`.`type` = 'sesmusic_songrating';

UPDATE `engine4_activity_actiontypes` SET `type` = 'sesmusic_artist_rating' WHERE `engine4_activity_actiontypes`.`type` = 'sesmusic_artistrating';

UPDATE `engine4_activity_actions` SET `type` = 'sesmusic_artist_rating' WHERE `engine4_activity_actions`.`type` = 'sesmusic_artistrating';

UPDATE `engine4_activity_actiontypes` SET `type` = 'sesmusic_albumsong_favourite' WHERE `engine4_activity_actiontypes`.`type` = 'sesmusic_favouritealbumsong';

UPDATE `engine4_activity_actions` SET `type` = 'sesmusic_albumsong_favourite' WHERE `engine4_activity_actions`.`type` = 'sesmusic_favouritealbumsong';



UPDATE `engine4_activity_notificationtypes` SET `type` = 'sesmusic_album_favourite' WHERE `engine4_activity_notificationtypes`.`type` = 'sesmusic_favourite_musicalbum';
UPDATE `engine4_activity_notifications` SET `type` = 'sesmusic_album_favourite' WHERE `engine4_activity_notifications`.`type` = 'sesmusic_favourite_musicalbum';

UPDATE `engine4_activity_notificationtypes` SET `type` = 'sesmusic_albumsong_favourite' WHERE `engine4_activity_notificationtypes`.`type` = 'sesmusic_favourite_song';
UPDATE `engine4_activity_notifications` SET `type` = 'sesmusic_albumsong_favourite' WHERE `engine4_activity_notifications`.`type` = 'sesmusic_favourite_song';

UPDATE `engine4_activity_notificationtypes` SET `type` = 'sesmusic_playlist_favourite' WHERE `engine4_activity_notificationtypes`.`type` = 'sesmusic_favourite_playlist';
UPDATE `engine4_activity_notifications` SET `type` = 'sesmusic_playlist_favourite' WHERE `engine4_activity_notifications`.`type` = 'sesmusic_favourite_playlist';

UPDATE `engine4_activity_notificationtypes` SET `type` = 'sesmusic_album_rating' WHERE `engine4_activity_notificationtypes`.`type` = 'sesmusic_rated_musicalbum';
UPDATE `engine4_activity_notifications` SET `type` = 'sesmusic_album_rating' WHERE `engine4_activity_notifications`.`type` = 'sesmusic_rated_musicalbum';

UPDATE `engine4_activity_notificationtypes` SET `type` = 'sesmusic_albumsong_rating' WHERE `engine4_activity_notificationtypes`.`type` = 'sesmusic_rated_song';
UPDATE `engine4_activity_notifications` SET `type` = 'sesmusic_albumsong_rating' WHERE `engine4_activity_notifications`.`type` = 'sesmusic_rated_song';