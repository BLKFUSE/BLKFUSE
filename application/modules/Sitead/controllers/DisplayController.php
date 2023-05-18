<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: DisplayController.php 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_DisplayController extends Core_Controller_Action_Standard {

    protected $_navigation;
    protected $_viewer;
    protected $_viewer_id;
    protected $_session;
    protected $_noPhotos;

    public function init() {

        $this->_session = new Zend_Session_Namespace('Payment_Userads');
        $this->_viewer = Engine_Api::_()->user()->getViewer();
        $this->_viewer_id = $this->_viewer->getIdentity();
        if (!$this->_helper->requireAuth()->setAuthParams('sitead', $this->_viewer, 'view')->isValid()) {
            return;
        }
    }

     /**
     * For Cancel advertisment by viewer and  submit reason
     */
    public function adsaveAction() {
        // Received Parameter from JS file.
        $adCancelReasion = (string) $this->_getParam('adCancelReasion');
        $adsId = (string) $this->_getParam('adsId');
        // Decode a ad id
        $adsId = Engine_Api::_()->sitead()->getEncodeToDecode($adsId);
        $adDescription = (string) $this->_getParam('adDescription');
        //Insert entry in the data base.
        $viewerId = Engine_Api::_()->user()->getViewer()->getIdentity();
        $adcancelTable = Engine_Api::_()->getItemTable('sitead_adcancel');
        $adcancelList = $adcancelTable->createRow();
        $adcancelList->user_id = $viewerId;
        $adcancelList->report_type = $adCancelReasion;
        if (!empty($adDescription)) {
            $adcancelList->report_description = $adDescription;
        }
        $adcancelList->ad_id = $adsId;
        $adcancelList->save();
        $this->view->showMsg = 1;
    }

    /**
     * Display ads on adboard
     */
    public function adboardAction() {
        $this->view->headTitle($this->view->translate("Ad Board"), Zend_View_Helper_Placeholder_Container_Abstract::PREPEND);
        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitead_main');
        $limit = Engine_Api::_()->getApi('settings', 'core')->ad_board_limit;
        $this->view->hideCustomUrl = Engine_Api::_()->sitead()->hideCustomUrl();
        $this->view->viewer_object = $viewer = Engine_Api::_()->user()->getViewer();
        $this->view->user_id = $viewer->getIdentity();
        $this->view->baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $params = array();
        $params['lim'] = $limit;
        // FEATCH ADS
        $fetch_site_ads = Engine_Api::_()->sitead()->getAdvertisement($params);
        $siteadinfo_table = Engine_Api::_()->getItemTable('sitead_adsinfo');
        $fetch_site_adsinfo = $siteadinfo_table->fetchAll();
        if (!empty($fetch_site_ads) && !empty($fetch_site_adsinfo)) {
            $this->view->siteads_array = $fetch_site_ads;
            $this->view->siteadsinfo_array = $fetch_site_adsinfo;
        } else {
            $this->view->noResult = 1;
        }
        $this->_helper->content
                ->setEnabled();
    }

    /**
     * Function: When click on 'Help & Learn More' tab from user section.
     */
    public function helpAndLearnmoreAction() {

        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitead_main');
        $this->view->display_faq = $display_faq = $this->_getParam('display_faq');
        $this->view->page_id = $page_id = $this->_getParam('page_id', 0);
        if (empty($page_id)) {
            $helpInfoTable = Engine_Api::_()->getItemtable('sitead_infopage');
            $helpInfoTableName = $helpInfoTable->info('name');
            $select = $helpInfoTable->select()->from($helpInfoTableName)->where('status =?', 1);
            $fetchHelpTable = $select->query()->fetchAll();
            if (!empty($fetchHelpTable)) {
                $this->view->pageObject = $fetchHelpTable;
                $default_faq = $fetchHelpTable[0]['faq'];
                $default_contact = $fetchHelpTable[0]['contect_team'];
                $this->view->page_default = $fetchHelpTable[0]['page_default'];
            }
        } else {
            $helpInfoTable = Engine_Api::_()->getItemtable('sitead_infopage');
            $helpInfoTableName = $helpInfoTable->info('name');
            $select = $helpInfoTable->select()->from($helpInfoTableName, array('infopage_id', 'title', 'package', 'faq', 'contect_team'))->where('status =?', 1);
            $fetchHelpTable = $select->query()->fetchAll();
            if (!empty($fetchHelpTable)) {
                $this->view->pageObject = $fetchHelpTable;
                $page_info = Engine_Api::_()->getItem('sitead_infopage', $page_id);
                if (empty($page_info)) {
                    return $this->_forward('notfound', 'error', 'core');
                }
                $display_faq = $default_faq = $page_info->faq;
                $default_contact = $page_info->contect_team;
                $this->view->page_default = $page_info->page_default;
                if (empty($default_faq) && empty($default_contact)) {
                    $this->view->content_data = $page_info->description;
                    $this->view->content_title = $page_info->title;
                }
            }
        }
        if (empty($display_faq)) {
            $this->view->display_faq = $display_faq = $default_faq;
        }
        if (!empty($display_faq)) {
            $pageIdSelect = $helpInfoTable->select()->from($helpInfoTableName, array('*'))
                            ->where('faq =?', $display_faq)->where('status =?', 1)->limit(1);
            $result = $pageIdSelect->query()->fetchAll();
            $this->view->faqpage_id = $result[0]['infopage_id'];
            $siteadFaqTable = Engine_Api::_()->getItemTable('sitead_faq');
            $siteadFaqName = $siteadFaqTable->info('name');
            // fetch General or Design or Targeting FAQ according to the selected tab
            $siteadFaqSelect = $siteadFaqTable->select()->from($siteadFaqName, array('question', 'answer', 'type', 'faq_default'))
                    ->where('status =?', 1)
                    ->where('type =?', $display_faq)
                    ->order('faq_id DESC');
            $this->view->viewFaq = $siteadFaqSelect->query()->fetchAll();
        } else if (!empty($default_contact)) { // Condition: Fetch data for 'Contact us' type.
            $contactTeam['numbers'] = Engine_Api::_()->getApi('settings', 'core')->ad_saleteam_con;
            $contactTeam['emails'] = Engine_Api::_()->getApi('settings', 'core')->ad_saleteam_email;
            $this->view->contactTeam = $contactTeam;
        }
    }

    // Function: Email to conteact team members if members email address not available then email to siteadmin.
    // Call From: 'Help and Learn More' => 'Contact sales team' .
    public function sendMessagesAction() {
        $this->view->form = $form = new Sitead_Form_Contactus();
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
            $ownerEmail = Engine_Api::_()->getApi('settings', 'core')->ad_saleteam_email;
            if (!empty($email)) {
                // Condition: If there are no E-mail address available of sales team member then message will go to admin derfault id.
                if (!empty($ownerEmail)) {
                    $ownerEmailArray = explode(",", $ownerEmail);
                    foreach ($ownerEmailArray as $owner_email) {
                        Engine_Api::_()->getApi('mail', 'core')->sendSystem($owner_email, 'site_team_contact', array(
                            'sitead_name' => $values['name'],
                            'sitead_email' => $values['email'],
                            'sitead_messages' => $values['message'],
                            'email' => $email,
                            'queue' => true
                        ));
                    }
                } else {
                    Engine_Api::_()->getApi('mail', 'core')->sendSystem($email, 'site_team_contact', array(
                        'sitead_name' => $values['name'],
                        'sitead_email' => $values['email'],
                        'sitead_messages' => $values['message'],
                        'email' => $email,
                        'queue' => true
                    ));
                }
            }

            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array('Successsfully send messages.')
            ));
        }
    }

    /**
     * after click on ad redirect on the mention url
     */
    public function adRedirectAction() {
        $adId = $this->_getParam('adId');
        $adId = Engine_Api::_()->sitead()->getEncodeToDecode($adId);
        $redirect = Engine_Api::_()->sitead()->ad_clickcount($adId);
        if ($redirect == 'false') {
            return $this->_forward('notfound', 'error', 'core');
        }
    }

    // Function: Ajax based info return, when select any 'Module' then return all the content from that modules which are created by loggden user.
    // Return: Return selected module content array.
    // Call From: _formModtitle.tpl
    public function contenttypeAction() {
        $resource_type = $this->_getParam('resource_type');
        $calling_from = $this->_getParam('calling_from', null);
        $resource_id = $this->_getParam('resource_id', null);

        $resource_array = array();
        if (!empty($resource_type)) {
            $resource_array = Engine_Api::_()->sitead()->resource_content($resource_type, $calling_from, $resource_id);
            $getModType = Engine_Api::_()->getDbTable('modules', 'sitead')->getModuleInfo($resource_type);
        }

        $this->view->resource_string = $resource_array;
        $this->view->resource_type = $resource_type;
        if (!empty($getModType) && !empty($getModType['module_title'])) {
            $this->view->modTitle = $getModType['module_title'];
        }
    }

    // Function: Ajax based info return, when select any content from the drop down then return the information about that content.
    // Return: Return the all information about any content.
    // Call From: _formModtitle.tpl
    public function resourcecontentAction() {
        $resource_type = $this->_getParam('resource_type');
        $resource_id = $this->_getParam('resource_id');

        $is_document = 0;
        if ($resource_type == 'document') {
            $is_document = 1;
        }

        if (strstr($resource_type, "sitereview")) {
            // $resource_type = "sitereview";

            $sitereviewExplode = explode("_", $resource_type);
            $tempAdModId = $sitereviewExplode[1];
            $module_info = Engine_Api::_()->getItem("sitead_module", $tempAdModId);
            $tempModName = strtolower($module_info->module_title);
            $tempModName = ucfirst($module_info->module_title);

            $content_table = "sitereview_listing";
            $sub_title = "View" . " " . $tempModName;
            $content_data = Engine_Api::_()->getItem($content_table, $resource_id);
        } else {
            $field_info = Engine_Api::_()->getDbTable('modules', 'sitead')->getModuleInfo($resource_type);

            if (!empty($field_info)) {
                $content_data = Engine_Api::_()->getItem($field_info['table_name'], $resource_id);
            }
        }

        $base_url = Zend_Controller_Front::getInstance()->getBaseUrl();
        if (empty($sub_title)) {
            $sub_title = Engine_Api::_()->sitead()->viewType($resource_type);
        }
        $photo_id_filepath = 0;

        if (empty($is_document)) {
            $photo_id_filepath = $content_data->getPhotoUrl('thumb.normal');
        } else {
            $photo_id_filepath = $content_data->thumbnail;
        }

        if (strstr($photo_id_filepath, '?')) {
            $explode_array = explode("?", $photo_id_filepath);
            $photo_id_filepath = $explode_array[0];
        }

        $isCDN = Engine_Api::_()->seaocore()->isCdn();

        if (empty($isCDN)) {
            if (!empty($base_url)) {
                $photo_id_filepath = str_replace($base_url . '/', '', $photo_id_filepath);
            } else {
                $arrqay = explode('/', $photo_id_filepath);
                unset($arrqay[0]);
                $photo_id_filepath = implode('/', $arrqay);
            }
        }

        if (!empty($photo_id_filepath)) {
            if (strstr($photo_id_filepath, 'application/')) {
                $photo_id_filepath = 0;
            } else {
                $content_photo = $this->upload($photo_id_filepath, $is_document, $isCDN);
            }
        }
        // Set "Title width" acording to the module.
        $getStoryContentTitle = $title = $content_data->getTitle();
        $title_lenght = strlen($title);
        $tmpTitle = strip_tags($content_data->getTitle());
        $titleTruncationLimit = $title_truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.char.title', 25);
        if ($title_lenght > $title_truncation_limit) {
            $title_truncation_limit = $title_truncation_limit - 2;
            $title = Engine_String::strlen($tmpTitle) > $title_truncation_limit ? Engine_String::substr($tmpTitle, 0, $title_truncation_limit) : $tmpTitle;
            $title = $title . '..';
        }

        // Set "Body width" acording to the module.
        $body = $content_data->getDescription();
        $body_lenght = strlen($body);
        $tmpBody = strip_tags($content_data->getDescription());
        $body_truncation_limit = Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.char.body', 135);
        if ($body_lenght > $body_truncation_limit) {
            $body_truncation_limit = $body_truncation_limit - 2;
            $body = Engine_String::strlen($tmpBody) > $body_truncation_limit ? Engine_String::substr($tmpBody, 0, $body_truncation_limit) : $tmpBody;
            $body = $body . '..';
        }
        $preview_title = $title;

        $remaning_body_limit = $body_truncation_limit - strlen($body);
        if ($remaning_body_limit < 0) {
            $remaning_body_limit = 0;
        }
        $remaning_title_limit = $title_truncation_limit - strlen($title);
        if ($remaning_title_limit < 0) {
            $remaning_title_limit = 0;
        }

        // Set the default image if no image selected.
        if (empty($content_photo)) {
            $content_photo = $this->view->itemPhoto($content_data, 'thumb.icon');
        }
        if (empty($photo_id_filepath)) {
            $photo_id_filepath = $this->getNoPhoto($content_data, 'thumb.icon');
        }
        $viewerTruncatedTitle = Engine_Api::_()->sitead()->truncation($this->_viewer->getTitle(), $titleTruncationLimit);

        $title = Engine_Api::_()->sitead()->truncation($title, $titleTruncationLimit);

        $this->view->id = $content_data->getIdentity();
        $this->view->title = $title;
        $this->view->resource_type = $resource_type;
        $this->view->des = $body;
        $this->view->page_url = $content_data->getHref();
        $this->view->photo = $content_photo;
        $this->view->preview_title = $preview_title;
        $this->view->remaning_body_text = $remaning_body_limit;
        $this->view->remaning_title_text = $remaning_title_limit;
        $this->view->photo_id_filepath = $photo_id_filepath;
    }

    // This function is call from 'resourcecontentAction()' for make a image image in temporary folder.
    public function upload($uploaded_image_path, $is_document, $isCDN) {
        if (empty($isCDN)) {
            $file = APPLICATION_PATH . DIRECTORY_SEPARATOR . $uploaded_image_path;
        } else {
            $file = $uploaded_image_path;
        }

        if (empty($isCDN) && strstr($file, "//")) {
            if (strstr($uploaded_image_path, "/public")) {
                $tempExplode = explode("public", $uploaded_image_path);
                $tempPath = trim($tempExplode[1], "/");
                $uploaded_image_path = "public/" . $tempPath;
                $file = APPLICATION_PATH . DIRECTORY_SEPARATOR . $uploaded_image_path;
            }
        }

        if (!empty($isCDN) || @is_file($file) || !empty($is_document)) {
            @chmod($file, 0777);
            @unlink($this->_session->photoName_Temp_module);
        } else {
            if (isset($this->_session->photoName_Temp_module)) {
                if (is_file($this->_session->photoName_Temp_module)) {
                    @chmod($this->_session->photoName_Temp_module, 0777);
                    @unlink($this->_session->photoName_Temp_module);
                }
                unset($this->_session->photoName_Temp_module);
            }
            return;
        }

        $file1 = str_replace('/', '_', $uploaded_image_path);
        $name = $file1;
        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/sitead/temporary';
        $min = 60;
        $maxW = 640;
        $maxH = 480;

        // Recreate image  not delete this code
        $image = Engine_Image::factory();
        $image->open($file);

        $dstW = $image->width;
        $dstH = $image->height;

        $multiplier = min($maxW / $dstW, $maxH / $dstH);
        if ($multiplier > 1) {
            $dstH *= $multiplier;
            $dstW *= $multiplier;
        }
        if (($delta = $maxW / $dstW) < 1) {
            $dstH = round($dstH * $delta);
            $dstW = round($dstW * $delta);
        }
        if (($delta = $maxH / $dstH) < 1) {
            $dstH = round($dstH * $delta);
            $dstW = round($dstW * $delta);
        }

        $createHight = $dstH;
        $createWidth = $dstW;
        if ($createWidth < $min)
            $createWidth = $min;

        if ($createHight < $min)
            $createHight = $min;

        // Resize image (icon)
        $image = Engine_Image::factory();
        $image->open($file);
        $image->resample(0, 0, $image->width, $image->height, $createWidth, $createHight)
                ->write($path . '/' . $name)
                ->destroy();

        $photoName = $this->view->baseUrl() . '/public/sitead/temporary/' . $name;
        $currentIMagePath = $path . '/' . $name;
        if (isset($this->_session->photoName_Temp_module)) {
            if ($currentIMagePath !== $this->_session->photoName_Temp_module) {
                if (is_file($this->_session->photoName_Temp_module) || !empty($isCDN)) {
                    @chmod($this->_session->photoName_Temp_module, 0777);
                    @unlink($this->_session->photoName_Temp_module);
                }
            }
            unset($this->_session->photoName_Temp_module);
        }
        if (isset($this->_session->photoName_Temp)) {
            if (is_file($this->_session->photoName_Temp) || !empty($isCDN)) {
                @chmod($this->_session->photoName_Temp, 0777);
                @unlink($this->_session->photoName_Temp);
            }
            unset($this->_session->photoName_Temp);
        }
        $this->_session->photoName_Temp_module = $path . '/' . $name;
        return '<img  src="' . $photoName . '" alt="" />';
    }

    /**
     * Get default content photo if the content has not any image
     */
    public function getNoPhoto($item, $type) {
        $type = ( $type ? str_replace('.', '_', $type) : 'main' );

        if (($item instanceof Core_Model_Item_Abstract)) {
            $item = $item->getType();
        } else if (!is_string($item)) {
            return '';
        }

        if (!Engine_Api::_()->hasItemType($item)) {
            return '';
        }

        // Load from registry
        if (null === $this->_noPhotos) {
            // Process active themes
            $themesInfo = Zend_Registry::get('Themes');
            foreach ($themesInfo as $themeName => $themeInfo) {
                if (!empty($themeInfo['nophoto'])) {
                    foreach ((array) $themeInfo['nophoto'] as $itemType => $moreInfo) {
                        if (!is_array($moreInfo)) {
                            continue;
                        }
                        if (!empty($this->_noPhotos[$itemType])) {
                            $moreInfo = array_merge((array) $this->_noPhotos[$itemType], $moreInfo);
                        }
                        $this->_noPhotos[$itemType] = $moreInfo;
                    }
                }
            }
        }
        // Use default
        if (!isset($this->_noPhotos[$item][$type])) {
            $shortType = $item;
            if (strpos($shortType, '_') !== false) {
                list($null, $shortType) = explode('_', $shortType, 2);
            }
            $module = Engine_Api::_()->inflect(Engine_Api::_()->getItemModule($item));
            $this->_noPhotos[$item][$type] = 'application/modules/' .
                    $module .
                    '/externals/images/nophoto_' .
                    $shortType . '_'
                    . $type . '.png';
        }
        return $this->_noPhotos[$item][$type];
    }

}

?>
