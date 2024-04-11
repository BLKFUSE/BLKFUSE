<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Fetchnews.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Plugin_Task_Fetchenews extends Core_Plugin_Task_Abstract {

  public function execute() {

    $db = Engine_Db_Table::getDefaultAdapter();

    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.rss.enable', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.mercurykey', '')) {
        $rssTable = Engine_Api::_()->getDbTable('rss', 'sesnews');
        $rssTableName = $rssTable->info('name');
        $select = $rssTable->select()
                        ->setIntegrityCheck(false)
                        ->from($rssTableName)
                        ->where('draft =?', 0)
                        ->where('is_approved =?', 1)
                        ->where('cron_enabled =?', 1);
        $rssResults = $rssTable->fetchAll($select);
        foreach($rssResults as $rssResult) {
            Engine_Api::_()->sesnews()->getRSSNews($rssResult);
        }
    }
  }
}
