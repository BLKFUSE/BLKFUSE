<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Award.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Form_Dashboard_Award extends Engine_Form {

  public function init() {
    $this->setTitle('Change Contest Award')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
            ->setMethod('POST');

    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)) {
      if (Engine_Api::_()->core()->hasSubject('contest'))
        $contest = Engine_Api::_()->core()->getSubject();
      $package = Engine_Api::_()->getItem('sescontestpackage_package', $contest->package_id);
      $params = json_decode($package->params, true);
      $awardCount = $params['award_count'];
    } else {
      $awardCount = Engine_Api::_()->authorization()->getPermission(Engine_Api::_()->user()->getViewer()->level_id, 'contest', 'award_count');
    }
    if ($awardCount == 6)
      $awardCount = 5;
    $upload_url = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sesbasic', 'controller' => 'index', 'action' => "upload-image"), 'default', true);
    $allowed_html = 'strong, b, em, i, u, strike, sub, sup, p, div, pre, address, h1, h2, h3, h4, h5, h6, span, ol, li, ul, a, img, embed, br, hr';

    $editorOptions = array(
        'upload_url' => $upload_url,
        'html' => (bool) $allowed_html,
    );

    if (!empty($upload_url)) {
      $editorOptions['plugins'] = array(
          'table', 'fullscreen', 'media', 'preview', 'paste',
          'code', 'image', 'textcolor', 'jbimages', 'link'
      );

      $editorOptions['toolbar1'] = array(
          'undo', 'redo', 'removeformat', 'pastetext', '|', 'code',
          'media', 'image', 'jbimages', 'link', 'fullscreen',
          'preview'
      );
    }

    // 1st Prize
    $this->addElement('TinyMce', 'award', array(
        'label' => '1st Prize Award',
        'description' => '',
        'editorOptions' => $editorOptions,
    ));
    $this->addElement('TinyMce', 'award1_message', array(
        'label' => 'Congratulations Message',
        'description' => '',
        'editorOptions' => $editorOptions,
    ));
    $this->addDisplayGroup(array('award', 'award1_message'), 'award_1', array('disableLoadDefaultDecorators' => true, 'legend' => '1st'));
    $this->setDisplayGroupDecorators(array(
        'FormElements',
        'Fieldset'
    ));
    $restapi=Zend_Controller_Front::getInstance()->getRequest()->getParam( 'restApi', null );
    if ($awardCount > 1 && $restapi != 'Sesapi') {
      // 2nd Prize
      $this->addElement('TinyMce', 'award2', array(
          'label' => '2nd Prize Award',
          'description' => '',
          'editorOptions' => $editorOptions,
      ));

      $this->addElement('TinyMce', 'award2_message', array(
          'label' => 'Congratulations Message',
          'description' => '',
          'editorOptions' => $editorOptions,
      ));
      $this->addDisplayGroup(array('award2', 'award2_message'), 'award_2', array('disableLoadDefaultDecorators' => true, 'legend' => '2nd'));
      $this->setDisplayGroupDecorators(array(
          'FormElements',
          'Fieldset'
      ));
    }elseif ($awardCount > 1 && $restapi == 'Sesapi'){
      // 2nd Prize
      $this->addElement('TinyMce', 'award2', array(
        'label' => '2nd Prize Award',
        'description' => '',
        'editorOptions' => $editorOptions,
      ));

      $this->addElement('TinyMce', 'award2_message', array(
        'label' => 'Congratulations Message',
        'description' => '',
        'editorOptions' => $editorOptions,
      ));
      $this->addDisplayGroup(array('award2', 'award2_message'), 'award_2', array('disableLoadDefaultDecorators' => true, 'legend' => '2nd'));
      $this->setDisplayGroupDecorators(array(
        'FormElements',
        'Fieldset'
      ));
    }

    if ($awardCount > 2 && $restapi != 'Sesapi') {
      // 3rd Prize
      $this->addElement('TinyMce', 'award3', array(
          'label' => '3rd Prize Award',
          'description' => '',
          'editorOptions' => $editorOptions,
      ));

      $this->addElement('TinyMce', 'award3_message', array(
          'label' => 'Congratulations Message',
          'description' => '',
          'editorOptions' => $editorOptions,
      ));
      $this->addDisplayGroup(array('award3', 'award3_message'), 'award_3', array('disableLoadDefaultDecorators' => true, 'legend' => '3rd'));
      $this->setDisplayGroupDecorators(array(
          'FormElements',
          'Fieldset'
      ));
    }else if($awardCount > 2 && $restapi == 'Sesapi'){
      // 3rd Prize
      $this->addElement('TinyMce', 'award3', array(
        'label' => '3rd Prize Award',
        'description' => '',
        'editorOptions' => $editorOptions,
      ));

      $this->addElement('TinyMce', 'award3_message', array(
        'label' => 'Congratulations Message',
        'description' => '',
        'editorOptions' => $editorOptions,
      ));
      $this->addDisplayGroup(array('award3', 'award3_message'), 'award_3', array('disableLoadDefaultDecorators' => true, 'legend' => '3rd'));
      $this->setDisplayGroupDecorators(array(
        'FormElements',
        'Fieldset'
      ));
    }

    if ($awardCount > 3 && $restapi != 'Sesapi') {
      // 4th Prize
      $this->addElement('TinyMce', 'award4', array(
          'label' => '4th Prize Award',
          'description' => '',
          'editorOptions' => $editorOptions,
      ));
      $this->addElement('TinyMce', 'award4_message', array(
          'label' => 'Congratulations Message',
          'description' => '',
          'editorOptions' => $editorOptions,
      ));
      $this->addDisplayGroup(array('award4', 'award4_message'), 'award_4', array('disableLoadDefaultDecorators' => true, 'legend' => '4th'));
      $this->setDisplayGroupDecorators(array(
          'FormElements',
          'Fieldset'
      ));
    }elseif ($awardCount > 3 && $restapi == 'Sesapi'){
      $this->addElement('TinyMce', 'award4', array(
        'label' => '4th Prize Award',
        'description' => '',
        'editorOptions' => $editorOptions,
      ));
      $this->addElement('TinyMce', 'award4_message', array(
        'label' => 'Congratulations Message',
        'description' => '',
        'editorOptions' => $editorOptions,
      ));
      $this->addDisplayGroup(array('award4', 'award4_message'), 'award_4', array('disableLoadDefaultDecorators' => true, 'legend' => '4th'));
      $this->setDisplayGroupDecorators(array(
        'FormElements',
        'Fieldset'
      ));
    }
    if ($awardCount > 4 && $restapi != 'Sesapi') {
      // 5th Prize
      $this->addElement('TinyMce', 'award5', array(
          'label' => '5th Prize Award',
          'description' => '',
          'editorOptions' => $editorOptions,
      ));
      $this->addElement('TinyMce', 'award5_message', array(
          'label' => 'Congratulations Message',
          'description' => '',
          'editorOptions' => $editorOptions,
      ));
      $this->addDisplayGroup(array('award5', 'award5_message'), 'award_5', array('disableLoadDefaultDecorators' => true, 'legend' => '5th'));
      $this->setDisplayGroupDecorators(array(
          'FormElements',
          'Fieldset'
      ));
    }elseif ($awardCount > 4 && $restapi == 'Sesapi'){
      $this->addElement('TinyMce', 'award5', array(
        'label' => '5th Prize Award',
        'description' => '',
        'editorOptions' => $editorOptions,
      ));
      $this->addElement('TinyMce', 'award5_message', array(
        'label' => 'Congratulations Message',
        'description' => '',
        'editorOptions' => $editorOptions,
      ));
      $this->addDisplayGroup(array('award5', 'award5_message'), 'award_5', array('disableLoadDefaultDecorators' => true, 'legend' => '5th'));
      $this->setDisplayGroupDecorators(array(
        'FormElements',
        'Fieldset'
      ));
    }

    // Buttons
    $this->addElement('Button', 'submit', array(
        'label' => 'Save',
        'type' => 'submit',
        'ignore' => true,
    ));
  }

}
