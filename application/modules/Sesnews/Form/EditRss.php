<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: EditRss.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_EditRss extends Engine_Form
{
  public $_error = array();

  public function init()
  {

    if (Engine_Api::_()->core()->hasSubject('sesnews_rss'))
        $rss = Engine_Api::_()->core()->getSubject();

    $this->setTitle('Edit RSS')
      ->setDescription('Edit rss information below, then click "Add RSS" to publish the entry.')
      ->setAttrib('name', 'sesnews_rssedit');

    $user = Engine_Api::_()->user()->getViewer();
    $userLevel = Engine_Api::_()->user()->getViewer()->level_id;

    $this->addElement('Text', 'rss_link', array(
      'label' => 'RSS URL',
      //'allowEmpty' => false,
     // 'required' => true,
      'disable' => true,
      'onblur' => "checkURLValid(this.value);",
    ));

    $this->addElement('Text', 'title', array(
      'label' => 'Title',
      'allowEmpty' => false,
      'required' => true,
    ));

//     //if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.start.date', 1))  {
//
//     $this->addElement('Radio', 'show_start_time', array(
//         'label' => 'Start Date',
//         'description' => '',
//         'multiOptions' => array(
//         "" => 'Choose Start Date',
//         "1" => 'Publish Now',
//       ),
//       'value' => 1,
//       'onclick' => "showStartDate(this.value);",
//     ));
//
// 			if(isset($rss)){
// 				$start = strtotime($rss->publish_date);
// 				$start_date = date('m/d/Y',($start));
// 				$start_time = date('g:ia',($start));
// 				$viewer = Engine_Api::_()->user()->getViewer();
// 				$publishDate = $start_date.' '.$start_time;
// 				if($viewer->timezone){
// 					$start = strtotime($rss->publish_date);
// 					$oldTz = date_default_timezone_get();
// 					date_default_timezone_set($viewer->timezone);
// 					$start_date = date('m/d/Y',($start));
//           $start_date_y = date('Y',strtotime($start_date));
//           $start_date_m = date('m',strtotime($start_date));
//           $start_date_d = date('d',strtotime($start_date));
// 					$start_time = date('g:ia',($start));
// 					date_default_timezone_set($oldTz);
// 				}
// 			}
// 			if(isset($rss) && $rss->publish_date != '' && strtotime($publishDate) > time()){
// 				$this->addElement('dummy', 'news_custom_datetimes', array(
// 						'decorators' => array(array('ViewScript', array(
// 						'viewScript' => 'application/modules/Sesnews/views/scripts/_customdates.tpl',
// 						'class' => 'form element',
// 						'start_date'=>$start_date,
// 						'start_time'=>$start_time,
// 						'start_time_check'=>1,
// 						'subject'=> '',
// 				  )))
// 				));
//             }
//

    // prepare categories
    $categories = Engine_Api::_()->getDbtable('categories', 'sesnews')->getCategoriesAssoc(array('member_levels' => 1));
    if( engine_count($categories) > 0 ) {
		$categorieEnable = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.category.enable', '1');
		if ($categorieEnable == 1) {
			$required = true;
			$allowEmpty = false;
		} else {
			$required = false;
			$allowEmpty = true;
		}
        $categories = array('' => '') + $categories;
        // category field
        $this->addElement('Select', 'category_id', array(
            'label' => 'Category',
            'multiOptions' => $categories,
            'allowEmpty' => $allowEmpty,
            'required' => $required,
            'onchange' => "showSubCategory(this.value);",
        ));
        //Add Element: 2nd-level Category
        $this->addElement('Select', 'subcat_id', array(
            'label' => "2nd-level Category",
            'allowEmpty' => true,
            'required' => false,
            'multiOptions' => array('0' => ''),
            'registerInArrayValidator' => false,
            'onchange' => "showSubSubCategory(this.value);"
        ));
        //Add Element: Sub Sub Category
        $this->addElement('Select', 'subsubcat_id', array(
            'label' => "3rd-level Category",
            'allowEmpty' => true,
            'registerInArrayValidator' => false,
            'required' => false,
            'multiOptions' => array('0' => ''),
        ));
	}

    $this->addElement('Textarea', 'body', array(
      'label' => 'Description',
      'description' => 'Enter description.',
      'allowEmpty' => false,
      'required' => true,
    ));

    $this->addElement('File', 'photo', array(
      'label' => 'RSS Logo',
    ));
    $this->photo->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');


    $availableLabels = array(
      'everyone'            => 'Everyone',
      'registered'          => 'All Registered Members',
      'owner_network'       => 'Friends and Networks',
      'owner_member_member' => 'Friends of Friends',
      'owner_member'        => 'Friends Only',
      'owner'               => 'Just Me'
    );

    // Element: auth_view
    $viewOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('sesnews_rss', $user, 'auth_view');
    $viewOptions = array_intersect_key($availableLabels, array_flip($viewOptions));

    if( !empty($viewOptions) && engine_count($viewOptions) >= 1 ) {
      // Make a hidden field
      if( engine_count($viewOptions) == 1 ) {
        $this->addElement('hidden', 'auth_view', array( 'order' => 101, 'value' => key($viewOptions)));
      // Make select box
      } else {
        $this->addElement('Select', 'auth_view', array(
            'label' => 'Privacy',
            'description' => 'Who may see this rss news?',
            'multiOptions' => $viewOptions,
            'value' => key($viewOptions),
        ));
        $this->auth_view->getDecorator('Description')->setOption('placement', 'append');
      }
    }

    // Element: auth_comment
    $commentOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('sesnews_rss', $user, 'auth_comment');
    $commentOptions = array_intersect_key($availableLabels, array_flip($commentOptions));

    if( !empty($commentOptions) && engine_count($commentOptions) >= 1 ) {
      // Make a hidden field
      if( engine_count($commentOptions) == 1 ) {
        $this->addElement('hidden', 'auth_comment', array('order' => 102, 'value' => key($commentOptions)));
      // Make select box
      } else {
        $this->addElement('Select', 'auth_comment', array(
            'label' => 'Comment Privacy',
            'description' => 'Who may post comments on this rss news?',
            'multiOptions' => $commentOptions,
            'value' => key($commentOptions),
        ));
        $this->auth_comment->getDecorator('Description')->setOption('placement', 'append');
      }
    }


    $this->addElement('Select', 'draft', array(
      'label' => 'Status',
      'multiOptions' => array("0"=>"Published", "1"=>"Saved As Draft"),
      'description' => 'If this entry is published, it cannot be switched back to draft mode.'
    ));
    $this->draft->getDecorator('Description')->setOption('placement', 'append');

    $this->addElement('Checkbox', 'search', array(
      'label' => 'Show this rss entry in search results',
      'value' => 1,
    ));
    $this->addElement('Button', 'submit', array(
      'label' => 'Save Changes',
      'type' => 'submit',
    ));
  }
}
