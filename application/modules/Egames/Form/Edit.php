<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Edit.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egames_Form_Edit extends Egames_Form_Create
{
  
  public function init()
  {
    parent::init();
    $this->setTitle('Edit Game')
    ->setDescription('');
    $this->submit->setLabel('Save Changes');
  }
}
