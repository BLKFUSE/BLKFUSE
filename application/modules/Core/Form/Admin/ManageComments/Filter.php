<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Filter.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Core_Form_Admin_ManageComments_Filter extends Engine_Form {

  public function init() {
    
    $action = Zend_Controller_Front::getInstance()->getRequest()->getParam('action', null);

    $this->clearDecorators()
        ->addDecorator('FormElements')
        ->addDecorator('Form')
        ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search'))
        ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clear'));

    $this->setAttribs(array(
        'id' => 'filter_form',
        'class' => 'global_form_box',
      ))
      ->setMethod('GET');

    $this->addElement('Text', 'comment_id', array(
      'label' => 'ID',
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
        array('HtmlTag', array('tag' => 'div'))
      ),
    ));
    
    $this->addElement('Text', 'body', array(
      'label' => 'Comment',
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
        array('HtmlTag', array('tag' => 'div'))
      ),
    ));
    
    $this->addElement('Text', 'commented_by', array(
      'label' => 'Commented By',
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
        array('HtmlTag', array('tag' => 'div'))
      ),
    ));
    
		$subform = new Engine_Form(array(
			'description' => 'Comment Date',
			'elementsBelongTo'=> 'date',
			'decorators' => array(
				'FormElements',
				array('Description', array('placement' => 'PREPEND', 'tag' => 'label', 'class' => 'form-label')),
				array('HtmlTag', array('tag' => 'div', 'id' =>'integer-wrapper'))
			)
		));
		$subform->addElement('Text', 'date_from', array('placeholder'=>'from'));
    $subform->addElement('Text', 'date_to', array('placeholder'=>'to'));
		$this->addSubForm($subform, 'date');
    
//     if($action != 'activity') {
//       $modules = Engine_Api::_()->getDbTable('modules', 'core')->getAllModules();
//       if(is_countable($modules) && engine_count($modules) > 0) {
//         $this->addElement('Select', 'resource_type', array(
//           'label' => "Content Type",
//           'required' => true,
//           'multiOptions' => $modules,
//           'decorators' => array(
//               'ViewHelper',
//               array('Label', array('tag' => null, 'placement' => 'PREPEND')),
//               array('HtmlTag', array('tag' => 'div'))
//           ),
//         ));
//       }
//     }

    $this->addElement('Hidden', 'id', array(
      'order' => 10003,
    ));

    $this->addElement('Button', 'search', array(
        'label' => 'Search',
        'type' => 'submit',
        'ignore' => true,
    ));

    //Set default action without URL-specified params
    $params = array();
    foreach (array_keys($this->getValues()) as $key) {
      $params[$key] = null;
    }
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params));
  }
}
