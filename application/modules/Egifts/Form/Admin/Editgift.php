<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Editgift.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_Form_Admin_Editgift extends Egifts_Form_Admin_Creategift {
    public function init() {
    	parent::init();
        $settings = Engine_Api::_()->getApi('settings', 'core');

	    $this->setTitle('Edit Gift')
		    ->setDescription('Here you can edit gifts.')
	      ->setAttrib('class', 'global_form_popup');
    }
}

?>