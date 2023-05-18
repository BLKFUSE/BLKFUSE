<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Filter.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Edating_Form_Admin_Manage_Filter extends Engine_Form {

	protected $_isFeatured;
	protected $_isSponsored;
	protected $_creationDate;
	protected $_startDate;
	protected $_endDate;
	protected $_albumTitle;
	protected $_offtheDay;
	public function setAlbumTitle($title) {
  $this->_albumTitle = $title;
    return $this;
  }
  public function getAlbumTitle() {
    return $this->_albumTitle;
  }
	public function setOfftheDay($title) {
  $this->_offtheDay = $title;
    return $this;
  }
  public function getOfftheDay() {
    return $this->_offtheDay;
  }
	public function setStartDate($title) {
  $this->_startDate = $title;
    return $this;
  }
  public function getStartDate() {
    return $this->_startDate;
  }
	public function setEndDate($title) {
  $this->_endDate = $title;
    return $this;
  }
  public function getEndDate() {
    return $this->_endDate;
  }
	public function setCreationDate($title) {
	  $this->_creationDate = $title;
    return $this;
  }

  public function getCreationDate() {
    return $this->_creationDate;
  }
	
	public function setIsSponsored($title) {
    $this->_isSponsored = $title;
    return $this;
  }

  public function getIsSponsored() {
    return $this->_isSponsored;
  }
	public function setIsFeatured($title) {
    $this->_isFeatured = $title;
    return $this;
  }

  public function getIsFeatured() {
    return $this->_isFeatured;
  }

  public function init() {
  
		parent::init();
		
    $this->clearDecorators()
      ->addDecorator('FormElements')
      ->addDecorator('Form')
      ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search'))
      ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clear'));

    $this->setAttribs(array(
        'id' => 'filter_form',
        'class' => 'global_form_box',
      ))->setMethod('GET');
	
		$owner_name = new Zend_Form_Element_Text('owner_name');
    $owner_name
      ->setLabel('Owner Name')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));

    $submit = new Zend_Form_Element_Button('search', array('type' => 'submit'));
    $submit
      ->setLabel('Search')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'buttons'))
      ->addDecorator('HtmlTag2', array('tag' => 'div'));
		
		$arrayItem = array();
		$arrayItem = !empty($owner_name) ?	array_merge($arrayItem,array($owner_name)) : $arrayItem;
		$arrayItem = !empty($submit)?	array_merge($arrayItem,array($submit)) : '';
    $this->addElements($arrayItem);
  }
}
