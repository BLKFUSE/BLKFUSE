<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Browse.php 9826 2012-11-21 02:56:50Z richard $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Group_Form_Filter_Browse extends Engine_Form
{
  public function init()
  {
    $this->clearDecorators()
      ->addDecorators(array(
        'FormElements',
        array('HtmlTag', array('tag' => 'dl')),
        'Form',
      ))
      ->setMethod('get')
      ->setAttrib('class', 'filters')
      //->setAttrib('onchange', 'this.submit()')
      ;
    
    $this->addElement('Text', 'search_text', array(
      'label' => 'Search Groups:',
    ));
    
    $categories = Engine_Api::_()->getDbtable('categories', 'group')->getCategoriesAssoc();
    if (engine_count($categories) > 0) {
      $categories = array('0' => 'All Categories') + $categories;
      $this->addElement('Select', 'category_id', array(
        'label' => 'Category:',
        'multiOptions' => $categories,
        'onchange' => "showSubCategory(this.value);",
      ));
      $this->addElement('Select', 'subcat_id', array(
        'label' => "2nd-level Category:",
        'allowEmpty' => true,
        'required' => false,
        'multiOptions' => array('0' => ''),
        'registerInArrayValidator' => false,
        'onchange' => "showSubSubCategory(this.value);"
      ));
      $this->addElement('Select', 'subsubcat_id', array(
        'label' => "3rd-level Category:",
        'allowEmpty' => true,
        'registerInArrayValidator' => false,
        'required' => false,
        'multiOptions' => array('0' => '')
      ));
    }

    $this->addElement('Select', 'view', array(
      'label' => 'View:',
      'multiOptions' => array(
        '' => 'Everyone\'s Groups',
        '1' => 'Only My Friends\' Groups',
      ),
      'decorators' => array(
        'ViewHelper',
        array('HtmlTag', array('tag' => 'dd')),
        array('Label', array('tag' => 'dt', 'placement' => 'PREPEND'))
      ),
      'onchange' => 'this.form.submit();',
    ));

		$orderby = array(
			'creation_date' => 'Most Recent',
			'modified_date' => 'Recently Updated',
			'view_count' => 'Most Viewed',
			'like_count' => 'Most Liked',
			'comment_count' => 'Most Commented',
			'member_count' => 'Most Popular',
			'atoz' => 'A to Z',
			'ztoa' => 'Z to A',
		);
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('group.enable.rating', 1)) {
      $orderby['rating'] = 'Highest Rated';
    }
    $this->addElement('Select', 'order', array(
      'label' => 'List By:',
      'multiOptions' => $orderby,
      'decorators' => array(
        'ViewHelper',
        array('HtmlTag', array('tag' => 'dd')),
        array('Label', array('tag' => 'dt', 'placement' => 'PREPEND'))
      ),
      'value' => 'creation_date',
      'onchange' => 'this.form.submit();',
    ));

    $this->addElement('Button', 'find', array(
      'type' => 'submit',
      'label' => 'Search',
      'ignore' => true,
      'order' => 10000001,
    ));
  }
}
