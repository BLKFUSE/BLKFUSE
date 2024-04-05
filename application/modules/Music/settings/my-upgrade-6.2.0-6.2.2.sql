ALTER TABLE `engine4_music_playlists` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `engine4_music_playlist_songs` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
