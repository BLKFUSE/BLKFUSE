INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES

('sesbasic_admin_managephotolightbox', 'sesbasic', 'Manage Photo Lightbox', '', '{"route":"admin_default","module":"sesbasic","controller":"photolightbox","action":"photo"}', 'sesbasic_admin_main', '', 4),

('sesbasic_admin_photolightboxphotolightbox', 'sesbasic', 'Photo Lightbox Settings', '', '{"route":"admin_default","module":"sesbasic","controller":"photolightbox","action":"photo"}', 'sesbasic_admin_managephotolightbox', '', 1),

('sesbasic_admin_memberlevelphotolightbox', 'sesbasic', 'Member Level Setting', '', '{"route":"admin_default","module":"sesbasic","controller":"photolightbox","action":"index"}', 'sesbasic_admin_managephotolightbox', '', 2);