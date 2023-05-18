<?php



/**

 * SocialEngineSolutions

 *

 * @category   Application_Sessociallogin

 * @package    Sessociallogin

 * @copyright  Copyright 2015-2016 SocialEngineSolutions

 * @license    http://www.socialenginesolutions.com/license/

 * @version    $Id: Yahoo.php 2017-07-04 00:00:00 SocialEngineSolutions $

 * @author     SocialEngineSolutions

 */



class Sessociallogin_Form_Admin_Settings_Yahoo extends Engine_Form {
    public function init() {
        $this->setTitle('Yahoo Integration')
                ->setDescription('Due to new APIs of Yahoo for now we are disabling it as because of some technical issue it don\'t supports new APIs.');

    }
}

