<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Edit.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Epaidcontent_Form_Index_Edit extends Epaidcontent_Form_Index_Create {

  public function init() {
  
    parent::init();

    $this->setTitle('Edit Plan')
      ->setDescription('Please note that payment parameters (Price, Recurrence, Duration) cannot be edited after creation. If you wish to change these, you will have to create a new plan and disable the current one.');

    $this->getElement('price')
        ->setIgnore(true)
        ->setAttrib('disable', true)
        ->clearValidators()
        ->setRequired(false)
        ->setAllowEmpty(true);

    $this->getElement('recurrence')
        ->setIgnore(true)
        ->setAttrib('disable', true)
        ->clearValidators()
        ->setRequired(false)
        ->setAllowEmpty(true);

    $this->getElement('duration')
        ->setIgnore(true)
        ->setAttrib('disable', true)
        ->clearValidators()
        ->setRequired(false)
        ->setAllowEmpty(true);
    $this->getElement('execute')->setLabel('Edit Plan');
  }
}
