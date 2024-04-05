<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Create.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_Review_Create extends Engine_Form {
  protected $_defaultProfileId;
  public function getDefaultProfileId() {
    return $this->_defaultProfileId;
  }
  public function setDefaultProfileId($default_profile_id) {
    $this->_defaultProfileId = $default_profile_id;
    return $this;
  }
  public function init() {
		$this->setAttrib('id', 'sesnews_review_form');

		$newsId = Zend_Controller_Front::getInstance()->getRequest()->getParam('news_id');
    if ($newsId) {
			$item = Engine_Api::_()->getItem('sesnews_news', $newsId);
    }else if(Engine_Api::_()->core()->getSubject()){
			$subject = Engine_Api::_()->core()->getSubject();
			$item =  Engine_Api::_()->getItem('sesnews_news',$subject->news_id);
		}

    $this->addElement('Dummy', 'review_star', array(
      'label' => 'Review',
      'decorators' => array(array('ViewScript', array(
      'viewScript' => '/application/modules/Sesnews/views/scripts/review-rating.tpl',

      'class' => 'form element')))
    ));

		$this->addElement('Dummy', 'review_parameters', array(
      'label' => 'Review',
      'decorators' => array(array('ViewScript', array(
			'item'=>$item,
      'viewScript' => '/application/modules/Sesnews/views/scripts/review-parameters.tpl',
      'class' => 'form element')))
    ));
    $this->addElement('Hidden', 'rate_value',array( 'order' => 878));
		$this->addElement('Hidden', 'category_id',array( 'order' => 879,'value'=>$item->category_id));
		$this->addElement('Hidden', 'subcat_id',array( 'order' => 880,'value'=>$item->subcat_id));
		$this->addElement('Hidden', 'subsubcat_id',array( 'order' => 881,'value'=>$item->subsubcat_id));
		$orderC = 999;
		if(isset($subject)){
			$reviewParameters = Engine_Api::_()->getDbtable('parametervalues', 'sesnews')->getParameters(array('content_id'=>$subject->getIdentity(),'user_id'=>$subject->owner_id));
			foreach($reviewParameters as $val){
				$this->addElement('Hidden', 'review_parameter_value_'.$val['parameter_id'],array( 'order' => $orderC++,'value'=>$val['rating'],'class'=>"sesnews_review_values"));
			}
		}
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.review.title', 1)) {
      $this->addElement('Text', 'title', array(
          'label' => 'Review Title',
          'allowEmpty' => false,
          'required' => true,
          'maxlength' => "255",
      ));
    }
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.show.pros', 1)) {
      $this->addElement('Text', 'pros', array(
          'label' => 'Pros',
          'allowEmpty' => false,
          'required' => true,
          'maxlength' => "255",
      ));
    }
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.show.cons', 1)) {
      $this->addElement('Text', 'cons', array(
          'label' => 'Cons',
          'allowEmpty' => false,
          'required' => true,
          'maxlength' => "255",
      ));
    }
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.review.summary', 1)) {
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.show.tinymce', 1)) {
        $user = Engine_Api::_()->user()->getViewer();
        $user_level = Engine_Api::_()->user()->getViewer()->level_id;
        //UPLOAD PHOTO URL
        $editorOptions = array(
          'uploadUrl' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'core', 'controller' => 'index', 'action' => 'upload-photo'), 'default', true),
        );
        $this->addElement('TinyMce', 'description', array(
            'label' => 'Description',
           // 'disableLoadDefaultDecorators' => true,
            'required' => true,
            'allowEmpty' => false,
            //'decorators' => array(
              //  'ViewHelper'
            //),
            'editorOptions' => $editorOptions,
        ));
      } else {
        $this->addElement('Textarea', 'description', array(
            'label' => 'Description',
            'allowEmpty' => false,
            'required' => true,
            'maxlength' => "300",
        ));
      }
    }
    $defaultProfileId = "0_0_" . $this->getDefaultProfileId();
    $customFields = new Sesnews_Form_Review_Custom_Fields(array(
        'item' => isset($subject) ? $subject : 'sesnews_review',
        'decorators' => array(
            'FormElements'
    )));
    $customFields->removeElement('submit');
    if ($customFields->getElement($defaultProfileId)) {
      $customFields->getElement($defaultProfileId)
              ->clearValidators()
              ->setRequired(false)
              ->setAllowEmpty(true);
    }
    $this->addSubForms(array(
        'fields' => $customFields
    ));
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.show.recommended', 1)) {
      $this->addElement('Radio', 'recommended', array(
          'label' => 'Recommend News',
          'description' => 'Do you recommend this news to other users?',
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
		$tabId =  Engine_Api::_()->sesbasic()->pageTabIdOnPage('sesnews.news-reviews','sesnews_profile_index','widget');
		$tabData = '';
		if($tabId){
			$tabData = '/tab/'.$tabId->content_id;
		}
    $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'link' => true,
				'href' =>  $item->getHref().$tabData,
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
