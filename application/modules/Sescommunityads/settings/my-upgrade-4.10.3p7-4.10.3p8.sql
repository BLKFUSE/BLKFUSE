ALTER TABLE `engine4_sescommunityads_ads` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `engine4_sescommunityads_attachments` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

DELETE FROM `engine4_activity_notificationtypes` WHERE `engine4_activity_notificationtypes`.`module` = 'sescommunityads';
DELETE FROM `engine4_activity_notifications` WHERE `engine4_activity_notifications`.`type` LIKE '%sescommunityads%';

INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`, `default`) VALUES
("sescommunityads_adsapprove", "sescommunityads", '{var:$adsLink} advertisement has been approved.', "0", "", "1"),
("sescommunityads_adsdisapprove", "sescommunityads", '{var:$adsLink} advertisement has been disapproved.', "0", "", "1"),
("sescommunityads_pmtmadeadmin", "sescommunityads", 'Payment for an advertisement {var:$adsLink} is made.', "0", "", "1"),
("sescommunityads_adsactivated", "sescommunityads", 'Your {var:$adsLink} advertisement has been activated.', "0", "", "1"),
("sescommunityads_paymentpending", "sescommunityads", 'Payment for your {var:$adsLink} advertisement is pending.', "0", "", "1"),
("sescommunityads_paymentrefunded", "sescommunityads", 'Payment for your {var:$adsLink} advertisement is refunded.', "0", "", "1"),
("sescommunityads_paymentcancel", "sescommunityads", 'Payment for your {var:$adsLink} advertisement is canceled.', "0", "", "1"),
("sescommunityads_adsexpired", "sescommunityads", '{var:$adsLink} advertisement has expired.', "0", "", "1"),
("sescommunityads_adsoverdue", "sescommunityads", 'Payment for {var:$adsLink} advertisement is overdue.', "0", "", "1"),

("sescommunityads_adminapproval", "sescommunityads", 'A new {var:$adsLink} advertisement is created & waiting approval.', "0", "", "1"),
("sescommunityads_newadscradmin", "sescommunityads", 'A new {var:$adsLink} advertisement is created.', "0", "", "1"),
("sescommunityads_paymentsussfull", "sescommunityads", 'Payment for your {var:$adsLink} advertisement is successful.', "0", "", "1");
