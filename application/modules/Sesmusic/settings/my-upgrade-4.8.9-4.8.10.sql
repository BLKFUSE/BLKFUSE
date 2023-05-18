ALTER TABLE `engine4_sesmusic_albums` ADD `store_link` VARCHAR( 255 ) NULL;
ALTER TABLE `engine4_sesmusic_albumsongs` ADD `store_link` VARCHAR( 255 ) NULL;
UPDATE  `engine4_core_menuitems` SET  `params` =  '{"route":"sesmusic_general_home","action":"home"}' WHERE  `engine4_core_menuitems`.`name` ='core_main_sesmusic';