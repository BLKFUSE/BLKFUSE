UPDATE `engine4_core_pages` SET `displayname` = replace(displayname, 'SES', 'SNS');
UPDATE `engine4_core_menuitems` SET `label` = replace(label, 'SES', 'SNS');
UPDATE `engine4_core_menus` SET `title` = replace(title, 'SES', 'SNS');