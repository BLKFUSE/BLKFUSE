ALTER TABLE `engine4_sesthought_thoughts` ADD `thoughttitle` VARCHAR(255) NULL;

ALTER TABLE `engine4_sesthought_thoughts` ADD `mediatype` TINYINT(1) NOT NULL DEFAULT "1";

ALTER TABLE `engine4_sesthought_thoughts` ADD `code` TEXT NOT NULL;