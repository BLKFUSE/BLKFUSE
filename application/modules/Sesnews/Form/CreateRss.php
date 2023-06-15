<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: CreateRss.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_CreateRss extends Engine_Form
{
  public $_error = array();
  protected $_fromApi;
  
  public function getFromApi() {
    return $this->_fromApi;
  }
  public function setFromApi($fromApi) {
    $this->_fromApi = $fromApi;
    return $this;
  }
  
  public function init()
  {
    $this->setTitle('Add New RSS')
      ->setDescription('Add new rss link by filling below details, then click "Add Rss" to publish the entry.')
      ->setAttrib('name', 'sesnews_create_rss');

    $user = Engine_Api::_()->user()->getViewer();
    $userLevel = Engine_Api::_()->user()->getViewer()->level_id;

    $this->addElement('Text', 'rss_link', array(
      'label' => 'RSS URL',
      'allowEmpty' => false,
      'required' => true,
      'onblur' => "checkURLValid(this.value);",
    ));
    
    if(!$this->getFromApi()){
    $this->addElement('Dummy', 'custom_url_news', array(
	'content' => '<span class="sesnews_check_availability_btn"><button id="check_custom_url_availability" type="button" name="check_availability" ><i class="fa fa-check" id="sesnews_custom_url_correct"></i><i class="fa fa-times" id="sesnews_custom_url_wrong" style="display:none;"></i><img src="application/modules/Core/externals/images/loading.gif" id="sesnews_custom_url_loading" alt="Loading" style="display:none;" /><samp class="availability_tip">Check Availability</samp></button></span>',
    ));
    }

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
//     $viewer = Engine_Api::_()->user()->getViewer();
// 			if(empty($_POST)){
// 				$startDate = date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s') . ' + 2 minutes'));
// 				$start_date = date('m/d/Y',strtotime($startDate));
// 				$start_time = date('g:ia',strtotime($startDate));
//
// 				if($viewer->timezone){
// 					$start =  strtotime(date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s') . ' + 2 minutes')));
// 					$selectedTime = "00:02:00";
// 					$startTime = time()+strtotime($selectedTime);
// 					$oldTz = date_default_timezone_get();
// 					date_default_timezone_set($viewer->timezone);
// 					$start_date = date('m/d/Y',($start));
// 					$start_time = date('g:ia',$startTime);
// 					date_default_timezone_set($oldTz);
// 				}
// 			}else{
// 				$start_date = date('m/d/Y',strtotime($_POST['start_date']));
// 				$start_time = date('g:ia',strtotime($_POST['start_time']));
// 			}
// 			$this->addElement('dummy', 'news_custom_datetimes', array(
// 				'decorators' => array(array('ViewScript', array(
// 										'viewScript' => 'application/modules/Sesnews/views/scripts/_customdates.tpl',
// 										'class' => 'form element',
// 										'start_date'=>$start_date,
// 										'start_time'=>$start_time,
// 										'start_time_check'=>1,
// 										'subject'=>isset($news) ? $news : '',
// 								)))
// 			));
//     //}


    // prepare categories
    $categories = Engine_Api::_()->getDbtable('categories', 'sesnews')->getCategoriesAssoc(array('member_levels' => 1));
    if( engine_count($categories) > 0 ) {
        $required = false;
        $allowEmpty = true;
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

//     $this->addElement('Checkbox', 'search', array(
//       'label' => 'Show this rss entry in search results',
//       'value' => 1,
//     ));

    // Element: submit
    $this->addElement('Button', 'submit', array(
      'label' => 'Add Rss',
      'type' => 'submit',
    ));
  }
}
