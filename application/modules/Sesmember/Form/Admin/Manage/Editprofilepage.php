<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Editprofilepage.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Form_Admin_Manage_Editprofilepage extends Sesmember_Form_Admin_Manage_Createprofilepage {

  public function init() {
    parent::init();

    $this->setTitle('Edit This Page')->setDescription('Below, edit this Page’s content and other parameters.');
    $this->submit->setLabel('Save Changes');
  }

}
