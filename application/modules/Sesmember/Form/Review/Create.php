<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Create.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Form_Review_Create extends Engine_Form {

  protected $_userId;

  public function getUserId() {
    return $this->_userId;
  }

  public function setUserId($userId) {
    $this->_userId = $userId;
    return $this;
  }

  protected $_reviewId;

  public function getReviewId() {
    return $this->_reviewId;
  }

  public function setReviewId($reviewId) {
    $this->_reviewId = $reviewId;
    return $this;
  }

  protected $_profileId;

  public function getProfileId() {
    return $this->_profileId;
  }

  public function setProfileId($profileId) {
    $this->_profileId = $profileId;
    return $this;
  }

  public function init() {

    $this->setAttrib('id', 'sesmember_review_form');

    if ($this->getProfileId()) {
      $objectId = $this->getProfileId();
    } else {
      $id = Zend_Controller_Front::getInstance()->getRequest()->getParam('id');
      $objectId = Engine_Api::_()->user()->getUser($id)->user_id;
    }

    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sesmember', 'controller' => 'review', 'action' => 'create', 'object_id' => $objectId), 'default', true));
    $reviewId = $this->getReviewId();
    if (($this->getUserId()))
      $item = Engine_Api::_()->getItem('user', $this->getUserId());
    else
      $item = Engine_Api::_()->core()->getSubject();
    if ($reviewId) {
      $subject = Engine_Api::_()->getItem('sesmember_review', $reviewId);
    }

    $this->addElement('Dummy', 'review_star', array(
        'label' => 'Review',
        'decorators' => array(array('ViewScript', array(
                    'item' => $item,
                    'viewScript' => '/application/modules/Sesmember/views/scripts/review-rating.tpl',
                    'object_id' => $this->getProfileId(),
                    'class' => 'form element')))
    ));

    $this->addElement('Dummy', 'review_parameters', array(
        'label' => 'Review',
        'decorators' => array(array('ViewScript', array(
                    'userObject' => $item,
                    'viewScript' => '/application/modules/Sesmember/views/scripts/review-parameters.tpl',
                    'class' => 'form element')))
    ));

    $this->addElement('Hidden', 'rate_value', array('order' => 878));
    $orderC = 881;
    if (isset($subject)) {
      $reviewParameters = Engine_Api::_()->getDbtable('parametervalues', 'sesmember')->getParameters(array('content_id' => $subject->getIdentity(), 'user_id' => $subject->owner_id));
      foreach ($reviewParameters as $val) {
        $this->addElement('Hidden', 'review_parameter_value_' . $val['parameter_id'], array('order' => $orderC++, 'value' => $val['rating'], 'class' => "sesmember_review_values"));
      }
    }
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.title', 1)) {
      $this->addElement('Text', 'title', array(
          'label' => 'Review Title',
          'allowEmpty' => false,
          'required' => true,
          'maxlength' => "255",
      ));
    }
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.show.pros', 1)) {
      $this->addElement('Text', 'pros', array(
          'label' => 'Pros',
          'allowEmpty' => false,
          'required' => true,
          'maxlength' => "255",
      ));
    }
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.show.cons', 1)) {
      $this->addElement('Text', 'cons', array(
          'label' => 'Cons',
          'allowEmpty' => false,
          'required' => true,
          'maxlength' => "255",
      ));
    }
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.summary', 1)) {
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.show.tinymce', 1)) {
        $upload_url = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sesbasic', 'controller' => 'index', 'action' => "upload-image"), 'default', true);

    $allowed_html = 'strong, b, em, i, u, strike, sub, sup, p, div, pre, address, h1, h2, h3, h4, h5, h6, span, ol, li, ul, a, img, embed, br, hr';

    $editorOptions = array(
      'upload_url' => $upload_url,
      'html' => (bool) $allowed_html,
    );

    if (!empty($upload_url)) {
      $editorOptions['editor_selector'] = 'tinymce';
      $editorOptions['mode'] = 'specific_textareas';
      $editorOptions['plugins'] = array(
          'table', 'fullscreen', 'preview', 'paste',
          'code', 'textcolor', 'jbimages', 'link'
      );

      $editorOptions['toolbar1'] = array(
          'undo', 'redo', 'removeformat', 'pastetext', '|', 'code',
          'media', 'image', 'jbimages', 'link', 'fullscreen',
          'preview'
      );
    }

    $this->addElement('TinyMce', 'description', array(
        'label' => 'Description',
        'editorOptions' => $editorOptions,
        'class' => 'tinymce',
        'allowEmpty' => false,
        'required' => true,
        'maxlength' => "300",
    ));
       }
      else{
        $this->addElement('Textarea', 'description', array(
          'label' => 'Description',
          'allowEmpty' => false,
          'required' => true,
          'class' => 'sesmember_review_tinymce',
          'maxlength' => "300",
      ));
      
      }
    }
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.show.recommended', 1)) {
      $this->addElement('Radio', 'recommended', array(
          'label' => 'Recommended',
          'description' => 'Do you recommend this review to user?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => 1,
      ));
    }
    //Buttons
    $this->addElement('Button', 'submit', array(
        'label' => 'Submit',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array(
            'ViewHelper',
        ),
    ));
    $tabId = Engine_Api::_()->sesbasic()->pageTabIdOnPage('sesmember.member-reviews', 'user_profile_index', 'widget');
    $tabData = '';
    if ($tabId) {
      $tabData = '/tab/' . $tabId->content_id;
    }
    $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'link' => true,
        'href' => 'javascript:void(0);',
        'onclick' => 'closeReviewForm();',
        'prependText' => ' or ',
        'decorators' => array(
            'ViewHelper',
        ),
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
        'decorators' => array(
            'FormElements',
            'DivDivDivWrapper',
        ),
    ));
  }

}