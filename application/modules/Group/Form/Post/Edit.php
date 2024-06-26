<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Edit.php 9802 2012-10-20 16:56:13Z pamela $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Group_Form_Post_Edit extends Engine_Form
{
  public function init()
  {
    //$this->setTitle('Edit Post');
    $viewer = Engine_Api::_()->user()->getViewer();
    $settings = Engine_Api::_()->getApi('settings', 'core');
    
    $allowHtml = (bool) $settings->getSetting('group_html', 0);
    $allowBbcode = (bool) $settings->getSetting('group_bbcode', 0);
    
    if( !$allowHtml ) {
      $filter = new Engine_Filter_HtmlSpecialChars();
    } else {
      $filter = new Engine_Filter_Html();
      $filter->setForbiddenTags();
      $allowedTags = array_map('trim', explode(',', Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'group', 'commentHtml')));
      $filter->setAllowedTags($allowedTags);
    }
    
    if( $allowHtml || $allowBbcode ) {
      $uploadUrl = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'core', 'controller' => 'index', 'action' => 'upload-photo'), 'default', true);
      $editorOptions = array(
        'uploadUrl' => $uploadUrl
      );
      if( $allowHtml ) {
        $editorOptions = array_merge($editorOptions, array('html' => 1, 'bbcode' => 1));
      } else {
        $editorOptions = array_merge($editorOptions, array('html' => 0, 'bbcode' => 1));
      }
      $this->addElement('TinyMce', 'body', array(
				'label' => 'Body',
        'disableLoadDefaultDecorators' => true,
        'required' => true,
        'editorOptions' => $editorOptions,
        'allowEmpty' => false,
        'decorators' => array('ViewHelper'),
        'filters' => array(
          $filter,
          new Engine_Filter_Censor(),
        ),
      ));
    } else {
      $this->addElement('textarea', 'body', array(
				'label' => 'Body',
        'required' => true,
        'attribs' => array(
          'rows' => 24,
          'cols' => 80,
          'style' => 'width:553px; max-width:553px; height:158px;'
        ),
        'allowEmpty' => false,
        'filters' => array(
          $filter,
          new Engine_Filter_Censor(),
        ),
      ));
    }
    
    // Buttons
    $this->addElement('Button', 'submit', array(
      'label' => 'Edit Post',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array('ViewHelper')
    ));

    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'link' => true,
      'prependText' => ' or ',
      'decorators' => array(
        'ViewHelper'
      )
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
    $buttonGroup = $this->getDisplayGroup('buttons');
    $buttonGroup->addDecorator('DivDivDivWrapper');
  }
}
