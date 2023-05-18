<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Bodyclass.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sitead_View_Filter_SiteadInjectdCoreFeed implements Zend_Filter_Interface {

    public function filter($string) {
        if (!Zend_Registry::isRegistered('Zend_View')) {
            return $string;
        }

        $view = Zend_Registry::get('Zend_View');
        if (!$view->settings('sitead.coreFeed.enable', 1)) {
            return $string;
        }
        if (strpos($string, '<ul class=\'feed\' id="activity-feed">') === FALSE || strpos($string, '<li class="feed_showcase_siteads">') !== FALSE) {
            return $string;
        }
        $find = '<li id="activity-item-';
        $adPosition = $view->settings('sitead.coreFeed.position') ?: ceil(substr_count($string, $find) / 2);
        if ($adPosition < 1) {
            return $string;
        }

        $pos = 0;
        for ($number = 0; $number <= $adPosition; $number++) {
            if ($number == 0) {
                $pos = strpos($string, $find);
            } else {
                $pos = strpos($string, $find, $pos + strlen($find)) ?: $pos;
            }
        }
        $adHTMl = $view->content()->renderWidget("sitead.ads", array('itemCount' => 1, 'showHeader' => false, 'widgetId' => 'core_activity_feed_' . time()));
        if (strlen($adHTMl) > 50) {
            $adHTML = '<li class="feed_showcase_siteads">' . $adHTMl . '</li>';
            $string = substr($string, 0, $pos) . $adHTML . substr($string, $pos);
        }

        return $string;
    }

}
