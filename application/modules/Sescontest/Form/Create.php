<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Create.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Form_Create extends Engine_Form {

  protected $_defaultProfileId;
  protected $_smoothboxType;

  public function getDefaultProfileId() {
    return $this->_defaultProfileId;
  }

  public function setDefaultProfileId($default_profile_id) {
    $this->_defaultProfileId = $default_profile_id;
    return $this;
  }

  public function getSmoothboxType() {
    return $this->_smoothboxType;
  }

  public function setSmoothboxType($smoothboxType) {
    $this->_smoothboxType = $smoothboxType;
    return $this;
  }

  public function init() {

    $setting = Engine_Api::_()->getApi('settings', 'core');
    $translate = Zend_Registry::get('Zend_Translate');
    if (Engine_Api::_()->core()->hasSubject('contest'))
      $contest = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
	$restapi=Zend_Controller_Front::getInstance()->getRequest()->getParam( 'restApi', null );
    //get current logged in user
    $this->setTitle('Create New Contest')
            ->setAttrib('id', 'sescontest_create_form')
            ->setAttrib('enctype', 'multipart/form-data')
            ->setMethod("POST");
    // ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
    if ($this->getSmoothboxType())
      $this->setAttrib('class', 'global_form sesevent_smoothbox_create');
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $moduleName = $request->getModuleName();
    $controllerName = $request->getControllerName();
    $actionName = $request->getActionName();
    $hideClass = '';

    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)) {
      if (isset($contest)) {
        $package = Engine_Api::_()->getItem('sescontestpackage_package', $contest->package_id);
      } else {
        if ($request->getParam('package_id', 0)) {
          $package = Engine_Api::_()->getItem('sescontestpackage_package', $request->getParam('package_id', 0));
        } elseif ($request->getParam('existing_package_id', 0)) {
          $packageId = Engine_Api::_()->getItem('sescontestpackage_orderspackage', $request->getParam('existing_package_id', 0))->package_id;
          $package = Engine_Api::_()->getItem('sescontestpackage_package', $packageId);
        }
      }
      if (!isset($package)) {
        $packageId = Engine_Api::_()->getDbTable('packages', 'sescontestpackage')->getDefaultPackage();
        $package = Engine_Api::_()->getItem('sescontestpackage_package', $packageId);
      }
      $params = json_decode($package->params, true);
    }

    // Title
    //UPLOAD PHOTO URL
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
          'table', 'fullscreen', 'media', 'preview', 'paste',
          'code', 'image', 'textcolor', 'jbimages', 'link'
      );

      $editorOptions['toolbar1'] = array(
          'undo', 'redo', 'removeformat', 'pastetext', '|', 'code',
          'media', 'image', 'jbimages', 'link', 'fullscreen',
          'preview'
      );
    }
    $this->addElement('Text', 'title', array(
        'label' => 'Contest Title',
        'autocomplete' => 'off',
        'allowEmpty' => false,
        'required' => true,
        'validators' => array(
            array('NotEmpty', true),
            array('StringLength', false, array(1, 255)),
        ),
        'filters' => array(
            'StripTags',
            new Engine_Filter_Censor(),
        ),
    ));
    $custom_url_value = isset($contest->custom_url) ? $contest->custom_url : (isset($_POST["custom_url"]) ? $_POST["custom_url"] : "");
    if ($actionName == 'create' || ($actionName == 'edit' && $settings->getSetting('sescontest.edit.url', 0))) {
      // Custom Url
      $this->addElement('Dummy', 'custom_url_contest', array(
          'label' => 'Custom URL',
          'content' => '<input type="text" name="custom_url" id="custom_url" value="' . $custom_url_value . '"><span class="sescontest_check_availability_btn"><button id="check_custom_url_availability" type="button" name="check_availability" ><i class="fa fa-check" id="sescontest_custom_url_correct" style=""></i><i class="fa fa-times" id="sescontest_custom_url_wrong" style="display:none;"></i><img src="application/modules/Core/externals/images/loading.gif" id="sescontest_custom_url_loading" alt="Loading" style="display:none;" /><samp class="availability_tip">Check Availability</samp></button></span>',
      ));
    }
    if ($settings->getSetting('sescontest.contesttags', 1)) {
      if ($actionName == 'create') {
        if ((isset($_GET['typesmoothbox']) && $_GET['typesmoothbox'] == 'sessmoothbox' && $settings->getSetting('sescontest.show.tagpopup', 1)) || (!isset($_GET['typesmoothbox']) && $settings->getSetting('sescontest.show.tagcreate', 1)))
          $contestcretag = true;
        else
          $contestcretag = false;
      }elseif ($actionName == 'edit') {
        $contestcretag = true;
      }
      if ($contestcretag) {
        //Tags
        $this->addElement('Text', 'tags', array(
            'label' => 'Tags (Keywords)',
            'autocomplete' => 'off',
            'description' => 'Separate tags with commas.',
            'filters' => array(
                new Engine_Filter_Censor(),
            ),
        ));
        $this->tags->getDecorator("Description")->setOption("placement", "append");
      }
    }

    $mediaTypes = array();
    $contestTypes = Engine_Api::_()->getDbtable('medias', 'sescontest')->getMediaTypes();
    if (engine_count($contestTypes) > 1) {
      foreach ($contestTypes as $contestType) {
        $mediaTypes[$contestType['media_id']] = $contestType['title'];
      }
      $contestTypes = array('' => 'Choose Media Type') + $mediaTypes;
      $this->addElement('Select', 'contest_type', array(
          'label' => 'Media Type',
          'multiOptions' => $contestTypes,
          'allowEmpty' => false,
          'required' => true,
          'onchange' => "showEditorOption(this.value);",
      ));
    } else {
      $this->addElement('hidden', 'contest_type', array('order' => 99998, 'value' => $contestTypes[0]['media_id']));
    }

    if ($settings->getSetting('sescontest.editor.media.type', 1)) {
      $this->addElement('Radio', 'editor_type', array(
          'label' => 'Editor Type',
          'description' => 'Choose the Editor type for entering the text in the entries of this contest.',
          'multiOptions' => array(
              '1' => 'Rich WYSIWYG Editor',
              '2' => 'Plain Editor',
          ),
          'value' => 1
      ));
    } else {
      if ($settings->getSetting('sescontest.default.editor', 1))
        $value = 1;
      else
        $value = 2;
      $this->addElement('hidden', 'editor_type', array('order' => 9998, 'value' => $value));
    }

    //Category
    $categories = Engine_Api::_()->getDbtable('categories', 'sescontest')->getCategoriesAssoc();
    if (engine_count($categories) > 0) {

      $categorieEnable = $setting->getSetting('sescontest.category.required', '1');
      if ($categorieEnable == 1) {
        $required = true;
        $allowEmpty = false;
      } else {
        $required = false;
        $allowEmpty = true;
      }
      $categories = array('' => 'Choose Category') + $categories;
      $this->addElement('Select', 'category_id', array(
          'label' => 'Category',
          'multiOptions' => $categories,
          'allowEmpty' => $allowEmpty,
          'required' => $required,
          'onchange' => "showSubCategory(this.value);showFields(this.value,1,this.class,this.class,'resets');",
      ));
      //Add Element: 2nd-level Category
      $this->addElement('Select', 'subcat_id', array(
          'label' => "2nd-level Category",
          'allowEmpty' => true,
          'required' => false,
          'multiOptions' => array('0' => ''),
          'registerInArrayValidator' => false,
          'onchange' => "showSubSubCategory(this.value);showFields(this.value,1,this.class,this.class,'resets');"
      ));
      //Add Element: Sub Sub Category
      $this->addElement('Select', 'subsubcat_id', array(
          'label' => "3rd-level Category",
          'allowEmpty' => true,
          'registerInArrayValidator' => false,
          'required' => false,
          'multiOptions' => array('0' => ''),
          'onchange' => 'showFields(this.value,1);showFields(this.value,1,this.class,this.class,"resets");'
      ));

      if ((isset($package) && $package->custom_fields) || (!isset($package))) {
        $defaultProfileId = "0_0_" . $this->getDefaultProfileId();
        $customFields = new Sesbasic_Form_Custom_Fields(array(
            'packageId' => isset($package) ? $package->package_id : '',
            'resourceType' => 'sescontestpackage_package',
            'item' => isset($contest) ? $contest : 'contest',
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
      }
    }
    $enableDescription = $settings->getSetting('sescontest.enable.description', '1');
    if ($enableDescription) {
      if ($actionName == 'create') {
        if ((isset($_GET['typesmoothbox']) && $_GET['typesmoothbox'] == 'sessmoothbox' && $settings->getSetting('sescontest.show.descriptionpopup', 1)) || (!isset($_GET['typesmoothbox']) && $settings->getSetting('sescontest.show.descriptioncreate', 1)))
          $contestcredescription = true;
        else
          $contestcredescription = false;
      }elseif ($actionName == 'edit') {
        $contestcredescription = true;
      }
      $descriptionMandatory = $settings->getSetting('sescontest.description.required', '1');
      if ($descriptionMandatory == 1) {
        $required = true;
        $allowEmpty = false;
      } else {
        $required = false;
        $allowEmpty = true;
      }
      if ($contestcredescription) {
        $this->addElement('TinyMce', 'description', array(
            'label' => 'Contest Description',
            'allowEmpty' => $allowEmpty,
            'required' => $required,
            'class' => 'tinymce',
            'editorOptions' => $editorOptions,
        ));
      }
    }
    if (isset($package)) {
        if($restapi == 'Sesapi'){
            $this->addElement('Radio', 'conteststyle', array(
                'label' => 'Contest Profile Page Layout',
                'description' => 'Set Your Contest Template',
                'multiOptions' => array(
                    '1'=>'Design 1',
                    '2'=>'Design 2',
                    '3'=>'Design 3',
                    '4'=>'Design 4',
                ),
                'escape' => false,
                'value' => '1',
            ));
        }else{
            $params = json_decode($package->params, true);
            if ($params['contest_choose_style']) {
                $chooselayoutVal = $params['contest_chooselayout'];
                $designoptions = array();
                if (engine_in_array(1, $chooselayoutVal)) {
                    $designoptions[1] = '<span><img src="./application/modules/Sescontest/externals/images/layout_1.jpg" alt="" /></span>' . $translate->translate("Design 1");
                }
                if (engine_in_array(2, $chooselayoutVal)) {
                    $designoptions[2] = '<span><img src="./application/modules/Sescontest/externals/images/layout_2.jpg" alt="" /></span>' . $translate->translate("Design 2");
                }
                if (engine_in_array(3, $chooselayoutVal)) {
                    $designoptions[3] = '<span><img src="./application/modules/Sescontest/externals/images/layout_3.jpg" alt="" /></span>' . $translate->translate("Design 3");
                }
                if (engine_in_array(4, $chooselayoutVal)) {
                    $designoptions[4] = '<span><img src="./application/modules/Sescontest/externals/images/layout_4.jpg" alt="" /></span>' . $translate->translate("Design 4");
                }

                $this->addElement('Radio', 'conteststyle', array(
                    'label' => 'Contest Profile Page Layout',
                    'description' => 'Set Your Contest Template',
                    'multiOptions' => $designoptions,
                    'escape' => false,
                    'value' => $chooselayoutVal,
                ));
            } else {
                if (isset($params['contest_style_type']))
                    $value = $params['contest_style_type'];
                $value = 1;
                $this->addElement('Hidden', 'conteststyle', array(
                    'value' => $value,
                    'order' => 9999,
                ));
            }
        }
    }
	else {
        if($restapi == 'Sesapi') {
            $this->addElement('Radio', 'conteststyle', array(
                'label' => 'Contest Profile Page Layout',
                'description' => 'Set Your Contest Template',
                'multiOptions' => array(
                    '1' => 'Design 1',
                    '2' => 'Design 2',
                    '3' => 'Design 3',
                    '4' => 'Design 4',
                ),
                'escape' => false,
                'value' => '1',
            ));
        }else{
            if (Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'auth_contstyle')) {

                $chooselayoutVal = json_decode(Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'contest', 'chooselayout'));

                $designoptions = array();
                if (engine_in_array(1, $chooselayoutVal)) {
                    $designoptions[1] = '<span><img src="./application/modules/Sescontest/externals/images/layout_1.jpg" alt="" /></span> ' . $translate->translate("Design 1");
                }
                if (engine_in_array(2, $chooselayoutVal)) {
                    $designoptions[2] = '<span><img src="./application/modules/Sescontest/externals/images/layout_2.jpg" alt="" /></span> ' . $translate->translate("Design 2");
                }
                if (engine_in_array(3, $chooselayoutVal)) {
                    $designoptions[3] = '<span><img src="./application/modules/Sescontest/externals/images/layout_3.jpg" alt="" /></span> ' . $translate->translate("Design 3");
                }
                if (engine_in_array(4, $chooselayoutVal)) {
                    $designoptions[4] = '<span><img src="./application/modules/Sescontest/externals/images/layout_4.jpg" alt="" /></span> ' . $translate->translate("Design 4");
                }
                $this->addElement('Radio', 'conteststyle', array(
                    'label' => 'Contest Profile Page Layout',
                    'description' => 'Set Your Contest Template',
                    'multiOptions' => $designoptions,
                    'escape' => false,
                    'value' => $chooselayoutVal,
                ));
            } else {
                $value = Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'style');
                $this->addElement('Hidden', 'conteststyle', array(
                    'value' => $value,
                    'order' => 9999,
                ));
            }
        }

    }
    if (isset($contest) && empty($_POST)) {
      // Convert and re-populate times
      $start = strtotime($contest->starttime);
      $end = strtotime($contest->endtime);
      $joinStarttime = strtotime($contest->joinstarttime);
      $joinEndtime = strtotime($contest->joinendtime);
      $votingStarttime = strtotime($contest->votingstarttime);
      $votingEndtime = strtotime($contest->votingendtime);
      $resultDatetime = strtotime($contest->resulttime);
      $oldTz = date_default_timezone_get();
      date_default_timezone_set($contest->timezone);
      $start_date = date('m/d/Y', ($start));
      $start_time = date('g:ia', ($start));

      $join_start_date = date('m/d/Y', ($joinStarttime));
      $join_start_time = date('g:ia', ($joinStarttime));

      $voting_start_date = date('m/d/Y', ($votingStarttime));
      $voting_start_time = date('g:ia', ($votingStarttime));

      $result_date = date('m/d/Y', ($resultDatetime));
      $result_time = date('g:ia', ($resultDatetime));

      $endDate = date('Y-m-d H:i:s', ($end));
      $end_date = date('m/d/Y', strtotime($endDate));
      $end_time = date('g:ia', strtotime($endDate));

      $joinEndDate = date('Y-m-d H:i:s', ($joinEndtime));
      $join_end_date = date('m/d/Y', strtotime($joinEndDate));
      $join_end_time = date('g:ia', strtotime($joinEndDate));

      $votingEndDate = date('Y-m-d H:i:s', ($votingEndtime));
      $voting_end_date = date('m/d/Y', strtotime($votingEndDate));
      $voting_end_time = date('g:ia', strtotime($votingEndDate));

      date_default_timezone_set($oldTz);
    } else if (empty($_POST)) {
      $oldTz = date_default_timezone_get();
      date_default_timezone_set($viewer->timezone);
      $resultDate = date('Y-m-d h:i:s a', time() + 25200);
      $result_date = date('m/d/Y', strtotime($resultDate));
      $result_time = date('g:ia', strtotime($resultDate));

      $startDate = date('Y-m-d h:i:s a', time() + 3600);
      $start_date = date('m/d/Y', strtotime($startDate));
      $start_time = date('g:ia', strtotime($startDate));
      $endDate = date('Y-m-d h:i:s a', time() + 21600);
      $end_date = date('m/d/Y', strtotime($endDate));
      $end_time = date('g:ia', strtotime($endDate));

      $joiningStartDate = date('Y-m-d h:i:s a', time() + 7200);
      $join_start_date = date('m/d/Y', strtotime($joiningStartDate));
      $join_start_time = date('g:ia', strtotime($joiningStartDate));
      $joinEndDate = date('Y-m-d h:i:s a', time() + 10800);
      $join_end_date = date('m/d/Y', strtotime($joinEndDate));
      $join_end_time = date('g:ia', strtotime($joinEndDate));

      $votingStartDate = date('Y-m-d h:i:s a', time() + 14400);
      $voting_start_date = date('m/d/Y', strtotime($votingStartDate));
      $voting_start_time = date('g:ia', strtotime($votingStartDate));
      $votingEndDate = date('Y-m-d h:i:s a', time() + 18000);
      $voting_end_date = date('m/d/Y', strtotime($votingEndDate));
      $voting_end_time = date('g:ia', strtotime($votingEndDate));
      date_default_timezone_set($oldTz);
    } else {
      $oldTz = date_default_timezone_get();
      date_default_timezone_set($viewer->timezone);
      $start_date = date('m/d/Y', strtotime($_POST['start_date']));
      $start_time = date('g:ia', strtotime($_POST['start_time']));
      $end_date = date('m/d/Y', strtotime($_POST['end_date']));
      $end_time = date('g:ia', strtotime($_POST['end_time']));
      $join_start_date = date('m/d/Y', strtotime($_POST['join_start_date']));
      $join_start_time = date('g:ia', strtotime($_POST['join_start_time']));
      $join_end_date = date('m/d/Y', strtotime($_POST['join_end_date']));
      $join_end_time = date('g:ia', strtotime($_POST['join_end_time']));
      $voting_start_date = date('m/d/Y', strtotime($_POST['voting_start_date']));
      $voting_start_time = date('g:ia', strtotime($_POST['voting_start_time']));
      $voting_end_date = date('m/d/Y', strtotime($_POST['voting_end_date']));
      $voting_end_time = date('g:ia', strtotime($_POST['voting_end_time']));
      $result_date = date('m/d/Y', strtotime($_POST['result_date']));
      $result_time = date('g:ia', strtotime($_POST['result_time']));
      date_default_timezone_set($oldTz);
    }
	  if ($restapi == 'Sesapi'){
			//contest Start date
            $startdate = new Engine_Form_Element_Date('start_date');
            $startdate->setLabel("Start Date for Contest");
            $startdate->setAllowEmpty(false);
            $startdate->setRequired(true);
            $this->addElement($startdate);
			//contest Start time
            $starttime = new Engine_Form_Element_Date('start_time');
            $starttime->setLabel("Start Time for Contest");
            $starttime->setAllowEmpty(false);
            $starttime->setRequired(true);
            $this->addElement($starttime);
			 // contest end date
            $enddate = new Engine_Form_Element_Date('end_date');
            $enddate->setLabel("End Date for Contest");
            $enddate->setAllowEmpty(false);
            $enddate->setRequired(true);
            $this->addElement($enddate);
			// contest end time
            $endtime = new Engine_Form_Element_Date('end_time');
            $endtime->setLabel("End Time for Contest");
            $endtime->setAllowEmpty(false);
            $endtime->setRequired(true);
            $this->addElement($endtime);

			//join Start date
			$joinstartdate = new Engine_Form_Element_Date('join_start_date');
            $joinstartdate->setLabel("Start Date for Entry Submission");
            $joinstartdate->setAllowEmpty(false);
            $joinstartdate->setRequired(true);
            $this->addElement($joinstartdate);
			//join Start time
			$joinstarttime = new Engine_Form_Element_Date('join_start_time');
            $joinstarttime->setLabel("Start Time for Entry Submission");
            $joinstarttime->setAllowEmpty(false);
            $joinstarttime->setRequired(true);
            $this->addElement($joinstarttime);
			// join end date
			$joinenddate = new Engine_Form_Element_Date('join_end_date');
            $joinenddate->setLabel("End Date for Entry Submission");
            $joinenddate->setAllowEmpty(false);
            $joinenddate->setRequired(true);
            $this->addElement($joinenddate);
			// join end time
			$joinendtime = new Engine_Form_Element_Date('join_end_time');
            $joinendtime->setLabel("End Time for Entry Submission");
            $joinendtime->setAllowEmpty(false);
            $joinendtime->setRequired(true);
            $this->addElement($joinendtime);

			// Voting Start Date
			$votingstartdate = new Engine_Form_Element_Date('voting_start_date');
            $votingstartdate->setLabel("Start Date for Voting");
            $votingstartdate->setAllowEmpty(false);
            $votingstartdate->setRequired(true);
            $this->addElement($votingstartdate);
			//voting start time
			$votingstarttime = new Engine_Form_Element_Date('voting_start_time');
            $votingstarttime->setLabel("Start Time for Voting");
            $votingstarttime->setAllowEmpty(false);
            $votingstarttime->setRequired(true);
            $this->addElement($votingstarttime);
			// Voting end date
			$votingenddate = new Engine_Form_Element_Date('voting_end_date');
            $votingenddate->setLabel("End Date for Voting");
            $votingenddate->setAllowEmpty(false);
            $votingenddate->setRequired(true);
            $this->addElement($votingenddate);
			// Voting end time
			$votingendtime = new Engine_Form_Element_Date('voting_end_time');
            $votingendtime->setLabel("End Time for Voting");
            $votingendtime->setAllowEmpty(false);
            $votingendtime->setRequired(true);
            $this->addElement($votingendtime);


	  }
    $this->addElement('dummy', 'contest_custom_datetimes', array(
        'decorators' => array(array('ViewScript', array(
                    'viewScript' => 'application/modules/Sescontest/views/scripts/_customdates.tpl',
                    'class' => 'form element',
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'join_start_date' => $join_start_date,
                    'join_end_date' => $join_end_date,
                    'join_start_time' => $join_start_time,
                    'join_end_time' => $join_end_time,
                    'voting_start_date' => $voting_start_date,
                    'voting_end_date' => $voting_end_date,
                    'voting_start_time' => $voting_start_time,
                    'voting_end_time' => $voting_end_time,
                    'start_time_check' => isset($contest) ? 0 : 1,
                    'subject' => isset($contest) ? $contest : '',
                )))
    ));
    if ($settings->getSetting('sescontest.enable.timezone', 1))
      $contesttimezone = true;
    else
      $contesttimezone = false;
    if ($contesttimezone) {
      $timezoneArray = array(
          'US/Pacific' => '(UTC-8) Pacific Time (US & Canada)',
          'US/Mountain' => '(UTC-7) Mountain Time (US & Canada)',
          'US/Central' => '(UTC-6) Central Time (US & Canada)',
          'US/Eastern' => '(UTC-5) Eastern Time (US & Canada)',
          'America/Halifax' => '(UTC-4)  Atlantic Time (Canada)',
          'America/Anchorage' => '(UTC-9)  Alaska (US & Canada)',
          'Pacific/Honolulu' => '(UTC-10) Hawaii (US)',
          'Pacific/Samoa' => '(UTC-11) Midway Island, Samoa',
          'Etc/GMT-12' => '(UTC-12) Eniwetok, Kwajalein',
          'Canada/Newfoundland' => '(UTC-3:30) Canada/Newfoundland',
          'America/Buenos_Aires' => '(UTC-3) Brasilia, Buenos Aires, Georgetown',
          'Atlantic/South_Georgia' => '(UTC-2) Mid-Atlantic',
          'Atlantic/Azores' => '(UTC-1) Azores, Cape Verde Is.',
          'Europe/London' => 'Greenwich Mean Time (Lisbon, London)',
          'Europe/Berlin' => '(UTC+1) Amsterdam, Berlin, Paris, Rome, Madrid',
          'Europe/Athens' => '(UTC+2) Athens, Helsinki, Istanbul, Cairo, E. Europe',
          'Europe/Moscow' => '(UTC+3) Baghdad, Kuwait, Nairobi, Moscow',
          'Iran' => '(UTC+3:30) Tehran',
          'Asia/Dubai' => '(UTC+4) Abu Dhabi, Kazan, Muscat',
          'Asia/Kabul' => '(UTC+4:30) Kabul',
          'Asia/Yekaterinburg' => '(UTC+5) Islamabad, Karachi, Tashkent',
          'Asia/Calcutta' => '(UTC+5:30) Bombay, Calcutta, New Delhi',
          'Asia/Katmandu' => '(UTC+5:45) Nepal',
          'Asia/Omsk' => '(UTC+6) Almaty, Dhaka',
          'Indian/Cocos' => '(UTC+6:30) Cocos Islands, Yangon',
          'Asia/Krasnoyarsk' => '(UTC+7) Bangkok, Jakarta, Hanoi',
          'Asia/Hong_Kong' => '(UTC+8) Beijing, Hong Kong, Singapore, Taipei',
          'Asia/Tokyo' => '(UTC+9) Tokyo, Osaka, Sapporto, Seoul, Yakutsk',
          'Australia/Adelaide' => '(UTC+9:30) Adelaide, Darwin',
          'Australia/Sydney' => '(UTC+10) Brisbane, Melbourne, Sydney, Guam',
          'Asia/Magadan' => '(UTC+11) Magadan, Solomon Is., New Caledonia',
          'Pacific/Auckland' => '(UTC+12) Fiji, Kamchatka, Marshall Is., Wellington',
      );
      $this->addElement('dummy', 'contest_timezone_popup', array(
          'decorators' => array(array('ViewScript', array(
                      'viewScript' => 'application/modules/Sescontest/views/scripts/_timezone.tpl',
                      'class' => 'form element',
                      'timezone' => $timezoneArray,
                      'contest' => isset($contest) ? $contest : '',
                      'viewer' => $viewer,
                  )))
      ));
    } else {
      $this->addElement('dummy', 'contest_timezone_popup_hidden', array(
          'decorators' => array(array('ViewScript', array(
                      'viewScript' => 'application/modules/Sescontest/views/scripts/_timezonehidden.tpl',
                      'class' => 'form element',
                      'contest' => isset($contest) ? $contest : '',
                      'viewer' => $viewer,
                  )))
      ));
    }

     if ($restapi == 'Sesapi'){
		 if ($contesttimezone) {
		 $timezoneArray = array(
          'US/Pacific' => '(UTC-8) Pacific Time (US & Canada)',
          'US/Mountain' => '(UTC-7) Mountain Time (US & Canada)',
          'US/Central' => '(UTC-6) Central Time (US & Canada)',
          'US/Eastern' => '(UTC-5) Eastern Time (US & Canada)',
          'America/Halifax' => '(UTC-4)  Atlantic Time (Canada)',
          'America/Anchorage' => '(UTC-9)  Alaska (US & Canada)',
          'Pacific/Honolulu' => '(UTC-10) Hawaii (US)',
          'Pacific/Samoa' => '(UTC-11) Midway Island, Samoa',
          'Etc/GMT-12' => '(UTC-12) Eniwetok, Kwajalein',
          'Canada/Newfoundland' => '(UTC-3:30) Canada/Newfoundland',
          'America/Buenos_Aires' => '(UTC-3) Brasilia, Buenos Aires, Georgetown',
          'Atlantic/South_Georgia' => '(UTC-2) Mid-Atlantic',
          'Atlantic/Azores' => '(UTC-1) Azores, Cape Verde Is.',
          'Europe/London' => 'Greenwich Mean Time (Lisbon, London)',
          'Europe/Berlin' => '(UTC+1) Amsterdam, Berlin, Paris, Rome, Madrid',
          'Europe/Athens' => '(UTC+2) Athens, Helsinki, Istanbul, Cairo, E. Europe',
          'Europe/Moscow' => '(UTC+3) Baghdad, Kuwait, Nairobi, Moscow',
          'Iran' => '(UTC+3:30) Tehran',
          'Asia/Dubai' => '(UTC+4) Abu Dhabi, Kazan, Muscat',
          'Asia/Kabul' => '(UTC+4:30) Kabul',
          'Asia/Yekaterinburg' => '(UTC+5) Islamabad, Karachi, Tashkent',
          'Asia/Calcutta' => '(UTC+5:30) Bombay, Calcutta, New Delhi',
          'Asia/Katmandu' => '(UTC+5:45) Nepal',
          'Asia/Omsk' => '(UTC+6) Almaty, Dhaka',
          'Indian/Cocos' => '(UTC+6:30) Cocos Islands, Yangon',
          'Asia/Krasnoyarsk' => '(UTC+7) Bangkok, Jakarta, Hanoi',
          'Asia/Hong_Kong' => '(UTC+8) Beijing, Hong Kong, Singapore, Taipei',
          'Asia/Tokyo' => '(UTC+9) Tokyo, Osaka, Sapporto, Seoul, Yakutsk',
          'Australia/Adelaide' => '(UTC+9:30) Adelaide, Darwin',
          'Australia/Sydney' => '(UTC+10) Brisbane, Melbourne, Sydney, Guam',
          'Asia/Magadan' => '(UTC+11) Magadan, Solomon Is., New Caledonia',
          'Pacific/Auckland' => '(UTC+12) Fiji, Kamchatka, Marshall Is., Wellington',
      );
	  $setTimezone = date_default_timezone_set($viewer->timezone);
	  $viewerTimezone = date_default_timezone_get();
	  $this->addElement('Select', 'timezone', array(
            'label' => 'Select Your Time Zone',
            'multiOptions' => $timezoneArray,
            'value' => $viewerTimezone,
        ));
	 }
	 }
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestjurymember')) {
      $canChooseAudience = 0;
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)) {
        if ($params['can_add_jury'])
          $canChooseAudience = 1;
      } elseif (Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'can_add_jury')) {
        $canChooseAudience = 1;
      }
      if ($canChooseAudience) {
        $this->addElement('Radio', 'audience_type', array(
            'label' => 'Voting Audience',
            'description' => ' Choose the voting audience for your contest. (If you choose jury members to vote in your contests, then you will be able to add jury members from the Edit Dashboard of this contest.)',
            'multiOptions' => array(
                '0' => 'Jury Members Only',
                '1' => 'Site Users Only',
                '2' => 'Both Jury Members & Site Users',
            ),
            'value' => 1,
        ));
      }
    }


    if (($restapi != 'Sesapi'  &&  isset($_GET['typesmoothbox']) && $_GET['typesmoothbox'] == 'sessmoothbox' && $settings->getSetting('sescontest.show.votepopup', 1)) || ( $restapi != 'Sesapi'  && !isset($_GET['typesmoothbox']) && $settings->getSetting('sescontest.show.votecreate', 1))) {
      $this->addElement('Radio', 'vote_type', array(
          'label' => 'Show Votes',
          'description' => 'Do you want to show number of votes during voting or after voting ends?',
          'multiOptions' => array(
              '0' => 'Show votes during Voting',
              '1' => 'Show votes after Voting ends',
          ),
          'value' => 0,
          'onclick' => "showResultDate(this.value);",
      ));
      $this->addElement('dummy', 'contest_result_datetimes', array(
          'decorators' => array(array('ViewScript', array(
                      'viewScript' => 'application/modules/Sescontest/views/scripts/_resultcustomdates.tpl',
                      'class' => 'form element',
                      'result_date' => $result_date,
                      'result_time' => $result_time,
                      'start_time_check' => isset($contest) ? 0 : 1,
                      'subject' => isset($contest) ? $contest : '',
                  )))
      ));
    }
	if($restapi == 'Sesapi'){
		if($settings->getSetting('sescontest_show_votecreate', 1)){
			$dataVote = array(
		'0' => 'Show votes during Voting',
		'1' => 'Show votes after Voting ends',
		);

	  $this->addElement('Radio', 'vote_type', array(
            'label' => 'Show Votes',
            'description' => 'Do you want to show number of votes during voting or after voting ends?',
            'allowEmpty' => true,
            'required' => false,
            'multiOptions' => $dataVote,
            'value' => '0',
        ));
		}
		// result date
			$resultdate = new Engine_Form_Element_Date('result_date');
            $resultdate->setLabel("Result Announcement Date");
            $resultdate->setAllowEmpty(true);
            $resultdate->setRequired(false);
            $this->addElement($resultdate);
			// result time
			$resultime = new Engine_Form_Element_Date('result_time');
            $resultime->setLabel("Result Announcement Time");
            $resultime->setAllowEmpty(true);
            $resultime->setRequired(false);
            $this->addElement($resultime);


	}


    if ($actionName == 'create') {
      if ((((isset($_GET['typesmoothbox']) && $_GET['typesmoothbox'] == 'sessmoothbox' && $settings->getSetting('sescontest.show.photopopup', 1))) || (!isset($_GET['typesmoothbox']) && $settings->getSetting('sescontest.show.photocreate', 1))))
        $contestMainPhoto = true;
      else
        $contestMainPhoto = false;
    } elseif ($actionName == 'edit') {
      $contestMainPhoto = false;
    }


    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)) {
      if ($request->getParam('package_id', 0))
        $package = Engine_Api::_()->getItem('sescontestpackage_package', $request->getParam('package_id', 0));
      elseif ($request->getParam('existing_package_id', 0)) {
        $packageId = Engine_Api::_()->getItem('sescontestpackage_orderspackage', $request->getParam('existing_package_id', 0)->package_id);
        $package = Engine_Api::_()->getItem('sescontestpackage_package', $packageId);
      }
      if (!isset($package)) {
        $packageId = Engine_Api::_()->getDbTable('packages', 'sescontestpackage')->getDefaultPackage();
        $package = Engine_Api::_()->getItem('sescontestpackage_package', $packageId);
      }
      $params = json_decode($package->params, true);
      if ($params['upload_mainphoto'])
        $contestMainPhoto = true;
      else
        $contestMainPhoto = false;
    }
    elseif (!Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'upload_mainphoto'))
      $contestMainPhoto = false;
    if (!isset($contest) && $contestMainPhoto) {
      $photoMandatory = $setting->getSetting('sescontest.contestmainphoto', '1');
      if ($photoMandatory == 1) {
        $required = true;
        $allowEmpty = false;
      } else {
        $required = false;
        $allowEmpty = true;
      }
      $requiredClass = $required ? ' requiredClass' : '';
      $translate = Zend_Registry::get('Zend_Translate');
      //Main Photo
      $this->addElement('File', 'photo', array(
          'label' => 'Main Photo',
          'onclick' => 'javascript:scriptJquery("#photo").val("")',
          'onchange' => 'handleFileBackgroundUpload(this,contest_main_photo_preview)',
      ));
      $this->photo->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');
      $this->addElement('Dummy', 'photo-uploader', array(
          'label' => 'Main Photo',
          'content' => '<div id="dragandrophandlerbackground" class="sescontest_upload_dragdrop_content sesbasic_bxs' . $requiredClass . '"><div class="sescontest_upload_dragdrop_content_inner"><i class="fa fa-camera"></i><span class="sescontest_upload_dragdrop_content_txt">' . $translate->translate('Add photo for your contest') . '</span></div></div>'
      ));
      $this->addElement('Image', 'contest_main_photo_preview', array(
          'width' => 300,
          'height' => 200,
          'value' => '1',
          'disable' => true,
      ));
      $this->addElement('Dummy', 'removeimage', array(
          'content' => '<a class="icon_cancel form-link" id="removeimage1" style="display:none; "href="javascript:void(0);" onclick="removeImage();"><i class="fas fa-trash-alt"></i>' . $translate->translate('Remove') . '</a>',
      ));
      $this->addElement('Hidden', 'removeimage2', array(
          'value' => 1,
          'order' => 10000000012,
      ));
    }
    if ($actionName == 'create') {
      if ((isset($_GET['typesmoothbox']) && $_GET['typesmoothbox'] == 'sessmoothbox' && $settings->getSetting('sescontest.show.1stprizepopup', 1)) || (!isset($_GET['typesmoothbox']) && $settings->getSetting('sescontest.show.1stprizecreate', 1)))
        $contestIstAward = true;
      else
        $contestIstAward = false;
    } elseif ($actionName == 'edit') {
      $contestIstAward = false;
    }
    if ($contestIstAward) {
      $this->addElement('TinyMce', 'award', array(
          'label' => '1st Prize Award',
          'description' => '',
          'class' => 'tinymce',
          'editorOptions' => $editorOptions,
      ));
    }
    if ($actionName == 'create') {
      if ((isset($_GET['typesmoothbox']) && $_GET['typesmoothbox'] == 'sessmoothbox' && $settings->getSetting('sescontest.show.rulespopup', 1)) || (!isset($_GET['typesmoothbox']) && $settings->getSetting('sescontest.show.rulescreate', 1))) {
        $contestRules = true;
        if ($settings->getSetting('sescontest.rules.required', 1)) {
          $required = true;
          $allowEmpty = false;
        } else {
          $required = false;
          $allowEmpty = true;
        }
      } else
        $contestRules = false;
    } elseif ($actionName == 'edit') {
      $contestRules = false;
    }
    if ($contestRules) {
      if ($settings->getSetting('sescontest.rules.editor', 1)) {
        $this->addElement('TinyMce', 'rules', array(
            'label' => 'Rules',
            'allowEmpty' => $allowEmpty,
            'required' => $required,
            'class' => 'tinymce',
            'editorOptions' => $editorOptions,
        ));
      } else {
        $this->addElement('Textarea', 'rules', array(
            'label' => 'Rules',
            'allowEmpty' => $allowEmpty,
            'required' => $required
        ));
      }
    }
    // Privacy
    $viewOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('contest', $viewer, 'auth_view');
    $commentOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('contest', $viewer, 'auth_comment');

    $availableLabels = array(
        'everyone' => 'Everyone',
        'registered' => 'All Registered Members',
        'owner_network' => 'Friends and Networks',
        'owner_member_member' => 'Friends of Friends',
        'owner_member' => 'Friends Only',
        'member' => 'Contest Guests Only',
        'owner' => 'Just Me'
    );
    $viewOptions = array_intersect_key($availableLabels, array_flip($viewOptions));
    $commentOptions = array_intersect_key($availableLabels, array_flip($commentOptions));
    // View
    if (!empty($viewOptions) && engine_count($viewOptions) >= 1 && ((isset($_GET['typesmoothbox']) && $_GET['typesmoothbox'] == 'sessmoothbox' && $settings->getSetting('sescontest.show.viewprivacypopup', 1)) || (!isset($_GET['typesmoothbox']) && $settings->getSetting('sescontest.show.viewprivacycreate', 1)))) {
      // Make a hidden field
      if (engine_count($viewOptions) == 1) {
        $this->addElement('hidden', 'auth_view', array('order' => 100001,'value' => key($viewOptions)));
        // Make select box
      } else {
        $this->addElement('Select', 'auth_view', array(
            'label' => 'View Privacy',
            'description' => 'Who may see this contest?',
            'class' => $hideClass,
            'multiOptions' => $viewOptions,
            'value' => key($viewOptions)));
        $this->auth_view->getDecorator('Description')->setOption('placement', 'append');
      }
    } else {
      if (isset($_GET['typesmoothbox']) && $_GET['typesmoothbox'] == 'sessmoothbox')
        $this->addElement('hidden', 'auth_view', array('order' => 100002,'value' => key($settings->getSetting('sescontest.default.viewprivacypopup'))));
      else
        $this->addElement('hidden', 'auth_view', array('order' => 100003,'value' => ($settings->getSetting('sescontest.default.viewprivacy'))));
    }
    // Comment
    if (!empty($commentOptions) && engine_count($commentOptions) >= 1 && ((isset($_GET['typesmoothbox']) && $_GET['typesmoothbox'] == 'sessmoothbox' && $settings->getSetting('sescontest.show.commentprivacypopup', 1)) || (!isset($_GET['typesmoothbox']) && $settings->getSetting('sescontest.show.commentprivacycreate', 1)))) {
      // Make a hidden field
      if (engine_count($commentOptions) == 1) {
        $this->addElement('hidden', 'auth_comment', array('order' => 100004, 'value' => key($commentOptions)));
        // Make select box
      } else {
        $this->addElement('Select', 'auth_comment', array(
            'label' => 'Comment Privacy', 'description' => 'Who may post comments on this contest?', 'class' => $hideClass,
            'multiOptions' => $commentOptions,
            'value' => key($commentOptions),
        ));
        $this->auth_comment->getDecorator('Description')->setOption('placement', 'append');
      }
    } else {
      if (isset($_GET['typesmoothbox']) && $_GET['typesmoothbox'] == 'sessmoothbox')
        $this->addElement('hidden', 'auth_comment', array('order' => 100005, 'value' => key($settings->getSetting('sescontest.default.commentprivacypopup'))));
      else
        $this->addElement('hidden', 'auth_comment', array('order' => 100006, 'value' => ($settings->getSetting('sescontest.default.commentprivacy'))));
    }

    if ($actionName == 'create') {
      if ((isset($_GET['typesmoothbox']) && $_GET['typesmoothbox'] == 'sessmoothbox' && $settings->getSetting('sescontest.show.statuspopup', 1)) || (!isset($_GET['typesmoothbox']) && $settings->getSetting('sescontest.show.statuscreate', 1)))
        $draft = true;
      else
        $draft = false;
    } elseif ($actionName == 'edit') {
      $draft = true;
    }

    if ($draft) {
      $this->addElement('Select', 'draft', array(
          'label' => 'Status',
          'class' => $hideClass,
          'description' => 'If this entry is published, it cannot be switched back to draft mode.', 'multiOptions' => array('0' => 'Saved As Draft', '1' => 'Published'),
          'value' => 1,
      ));
      $this->draft->getDecorator('Description')->setOption('placement', 'append');
    }

    if ($settings->getSetting('sescontest.search', 1)) {
      // Search
      $this->addElement('Checkbox', 'search', array('label'
          => 'People can search for this contest',
          'class' => $hideClass, 'value' => True
      ));
    }
    else {
      $this->addElement('Hidden', 'search', array(
        'value' => 1,
      ));
    }

    // Buttons
    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array(
            'ViewHelper',
        ),
    ));

    if (!$this->getSmoothboxType()) {
      $this->addElement('Cancel', 'cancel', array(
          'label' => 'cancel',
          'link' => true,
          'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'manage'), 'sescontest_general', true),
          'prependText' => ' or ',
          'decorators' => array(
              'ViewHelper',
          ),
      ));
    } else {
      $this->addElement('Cancel', 'cancel', array(
          'label' => 'cancel',
          'link' => true,
          'href' => '',
          'prependText' => ' or ',
          'onclick' => 'sessmoothboxclose();',
          'decorators' => array(
              'ViewHelper'
          )
      ));
    }
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
        'decorators' => array(
            'FormElements',
            'DivDivDivWrapper',
        ),
    ));
  }

}
