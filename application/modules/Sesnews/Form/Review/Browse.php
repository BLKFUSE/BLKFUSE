<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Browse.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_Review_Browse extends Engine_Form {

  protected $_reviewTitle;
  protected $_reviewSearch;
  protected $_reviewStars;
  protected $_reviewRecommended;

  public function setReviewTitle($title) {
    $this->_reviewTitle = $title;
    return $this;
  }

  public function getReviewTitle() {
    return $this->_reviewTitle;
  }

  public function setReviewSearch($title) {
    $this->_reviewSearch = $title;
    return $this;
  }

  public function getReviewSearch() {
    return $this->_reviewSearch;
  }

  public function setReviewStars($title) {
    $this->_reviewStars = $title;
    return $this;
  }

  public function getReviewStars() {
    return $this->_reviewStars;
  }

  public function setReviewRecommended($title) {
    $this->_reviewRecommended = $title;
    return $this;
  }

  public function getReviewRecommended() {
    return $this->_reviewRecommended;
  }

  public function init() {

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $identity = $view->identity;

    $news_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('news_id', 0);
    $this->setAttribs(array('id' => 'filter_form_review', 'class' => 'global_form_box'))->setMethod('GET');
    if($news_id)
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array('news_id' => $news_id), 'sesnews_entry_view', true));
    else
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'sesnews_review', true));

    if ($this->getReviewTitle()) {
      $this->addElement('Text', 'search_text', array(
          'label' => 'Search Reviews:',
          'order' => -999999,
      ));
    }

    if ($this->getReviewSearch()) {
      $this->addElement('Select', 'order', array(
          'label' => 'Browse By:',
          'multiOptions' => array(),
          'order' => -999998,
      ));
    }

    if ($this->getReviewStars()) {
      //Add Element: rating stars
      $this->addElement('Select', 'review_stars', array(
          'label' => "Review Stars:",
          'registerInArrayValidator' => false,
          'required' => false,
          'multiOptions' => array('' => '', '5' => '5 Stars', '4' => '4 Stars', '3' => '3 Stars', '2' => '2 Stars', '1' => '1 Star'),
      ));
    }
    if ($this->getReviewRecommended()) {
      //Add Element: rating stars
      $this->addElement('Select', 'review_recommended', array(
          'label' => "Recommended Reviews Only:",
          'registerInArrayValidator' => false,
          'required' => false,
          'multiOptions' => array('' => 'All Reviews', '1' => 'Recommended Only'),
      ));
    }

    $this->addElement('Button', 'submit', array(
        'label' => 'Search',
        'type' => 'submit',
        'order' => '9999',
    ));
    $this->addElement('Dummy', 'loading-img-sesnews-review', array(
        'content' => '<img src="application/modules/Core/externals/images/loading.gif" alt="Loading" />',
        'order' => '10001',
    ));
    parent::init();
  }

}
