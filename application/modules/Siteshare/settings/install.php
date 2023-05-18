<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteshare_Installer extends Engine_Package_Installer_Module {

    function onInstall() {
        $db = $this->getDb();
        $db->query("UPDATE `engine4_core_menuitems` SET `label` = 'Social Sites Share Stats' WHERE `engine4_core_menuitems`.`name` = 'siteshare_admin_main_social_services_status';");
        $db->query("UPDATE `engine4_core_menuitems` SET `label` = 'Manage Sharing Outside Community' WHERE `engine4_core_menuitems`.`name` = 'siteshare_admin_main_socialsites';");
        $db->query("UPDATE `engine4_core_menuitems` SET `label` = 'Manage Sharing Within Community' WHERE `engine4_core_menuitems`.`name` = 'siteshare_admin_main_manage';");
        if ($this->_databaseOperationType == 'upgrade') {
            $db->query('INSERT IGNORE INTO `engine4_core_menuitems` ( `name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
                ("siteshare_social_link_skype", "siteshare", "Skype", "Siteshare_Plugin_Menus::setMenu", \'{"class":"fa-skype","uri":"https://web.skype.com/share?url=CONTENT_URI&lang=en","target":"_blank"}\', "siteshare_social_link", "", 1, 0, 14),
                ("siteshare_social_link_mail", "siteshare", "Email", "Siteshare_Plugin_Menus::setMenu", \'{"class":"fa-envelope","uri":"/share/send-email?link=CONTENT_URI&subject=CONTENT_GUID", "target":"_blank"}\', "siteshare_social_link", "", 1, 0, 14),
                ("siteshare_social_link_flipboard", "siteshare", "Flipboard", "Siteshare_Plugin_Menus::setMenu", \'{"class":"fa-flipboard","uri":"https://share.flipboard.com/bookmarklet/popout?v=2&url=CONTENT_URI&title=CONTENT_TITLE","target":"_blank"}\', "siteshare_social_link", "", 1, 0, 15),
                ("siteshare_social_link_bookmark", "siteshare", "Google Bookmark", "Siteshare_Plugin_Menus::setMenu", \'{"class":"fa-bookmark","uri":"https://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk=CONTENT_URI&title=CONTENT_TITLE","target":"_blank"}\', "siteshare_social_link", "", 1, 0, 16),
                ("siteshare_social_link_mailgoogle", "siteshare", "Gmail", "Siteshare_Plugin_Menus::setMenu", \'{"class":"fa-envelope","uri":"https://mail.google.com/mail/u/1/?view=cm&fs=1&body=CONTENT_URI","target":"_blank"}\', "siteshare_social_link", "", 1, 0, 17),
                ("siteshare_social_link_rediff", "siteshare", "Rediff", "Siteshare_Plugin_Menus::setMenu", \'{"class":"fa-rediff","uri":"http://share.rediff.com/bookmark/addbookmark?bookmarkurl=CONTENT_URI&title=CONTENT_TITLE","target":"_blank"}\', "siteshare_social_link", "", 0, 0, 18),
                ("siteshare_social_link_vk", "siteshare", "VK", "Siteshare_Plugin_Menus::setMenu", \'{"class":"fa-vk","uri":"https://vk.com/share.php?url=CONTENT_URI&title=CONTENT_TITLE","target":"_blank"}\', "siteshare_social_link", "", 1, 0, 19),
                ("siteshare_social_link_yahoomail", "siteshare", "Yahoo!", "Siteshare_Plugin_Menus::setMenu", \'{"class":"fa-yahoo","uri":"http://compose.mail.yahoo.com/?body=CONTENT_URI&title=CONTENT_TITLE","target":"_blank"}\', "siteshare_social_link", "", 1, 0, 20),
                ("siteshare_social_link_whatsapp", "siteshare", "Whatsapp", "Siteshare_Plugin_Menus::setMenu", \'{"class":"fa-whatsapp","uri":"whatsapp://send?text=CONTENT_URI","target":"_blank"}\', "siteshare_social_link", "", 1, 0, 21),
                ("siteshare_social_link_sms", "siteshare", "Message", "Siteshare_Plugin_Menus::setMenu", \'{"class":"fa-commenting","uri":"sms://send?body=CONTENT_URI","target":"_blank"}\', "siteshare_social_link", "", 1, 0, 22)'
              );
        }
        parent::onInstall();
    }
}

