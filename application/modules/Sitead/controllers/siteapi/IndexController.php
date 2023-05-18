<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteapi
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    indexController.php 2015-09-17 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_IndexController extends Siteapi_Controller_Action_Standard {
    public function init() {
        Engine_Api::_()->getApi('Core', 'siteapi')->setTranslate();
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();
    }
    public function indexAction() {

        $placementCount = $this->_getParam('placementCount', 5);
        $viewer = Engine_Api::_()->user()->getViewer();
        if (empty($placementCount))
            $this->respondWithError('no_record');
        //2 = Site Ad
        $adType = $this->getRequestParam('type', 1);
        $length = $this->getRequestParam('limit', Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.length', 15));
        $totalNoOfAdv = (int) $length / $placementCount;
        $finalArray = array();
        try {
             if ($adType == 2) {
                $finalArray = $this->getSiteAd($totalNoOfAdv);
            } 
            $response['advertisments'] = $finalArray;
            $response['totalItemCount'] = count($finalArray);
            $response['hideCustomUrl'] = Engine_Api::_()->sitead()->hideCustomUrl();
            $this->respondWithSuccess($response);
        } catch (Exception $e) {
           
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
    }
    public function getSiteAd($totalNoOfAdv) {
        $viewer = Engine_Api::_()->user()->getViewer();
        $user_id = $viewer->getIdentity();
        $getHost = Engine_Api::_()->getApi('Core', 'siteapi')->getHost();
        if(_ANDROID_VERSION < 4.0)  {
            $siteAdsSelected = Engine_Api::_()->sitead()->getSiteAddsMultioptions(1);

            //Remove Ads Cancelled By User
            $cancelledAdvs = Engine_Api::_()->advancedactivity()->getCancelAdvs();
            if (is_array($siteAdsSelected)) {
                if (is_array($cancelledAdvs)) {
                    $siteAdsSelected = array_diff($siteAdsSelected, $cancelledAdvs);
                }
            }
            $totalAdsAvailable = count($siteAdsSelected);

            //Set total ads per block to total ads if ads is less.
            if ($totalAdsAvailable < $totalNoOfAdv)
                $totalNoOfAdv = $totalAdsAvailable;

            $random_ads = array_rand($siteAdsSelected, $totalNoOfAdv);
            if (!is_array($random_ads)) {
                $random_ads = array($random_ads);
            }
        }
        else{
                $random_ads = Engine_Api::_()->sitead()->getAdvertisement($params);
        }

        foreach ($random_ads as $key => $random_ad) {

            if(_ANDROID_VERSION < 4.0)  {
                if (empty($siteAdsSelected[$random_ad]))
                    continue;
                $site_ad = Engine_Api::_()->getItem('userads', $siteAdsSelected[$random_ad]);
                Engine_Api::_()->sitead()->adViewCount($site_ad['userad_id']);
            }
            else{
                $site_ad = $random_ad;
                
                Engine_Api::_()->sitead()->adViewCount($site_ad->userad_id);
            }
            
            if ($site_ad) {
               
                $tempArray = $site_ad->toArray();
                if($site_ad->cmd_ad_type == 'content'){
                   $tempArray['web_url'] =  $getHost . $tempArray['web_url'];
                }
               
                $siteadTable = Engine_Api::_()->getDbtable('adsinfos', 'sitead');
                

                $siteadSelect = $siteadTable->select()->where('userad_id=?', $tempArray['userad_id']);
                $siteadInfo = $siteadTable->fetchRow($siteadSelect);
                if(!empty($siteadInfo))
                $adInfoArray = $siteadInfo->toArray();
            
                if (!empty($adInfoArray['cta_button']))
                {
                    $tempArray['cta_button'] = $adInfoArray['cta_button'];
                }
                else{
                    $tempArray['cta_button'] = "";
                }
        
                if (isset($adInfoArray['cads_url']) && !empty($adInfoArray['cads_url']))
                {
                  
                    $staticBaseUrl = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.static.baseurl', null);
                    
                    $serverHost = Engine_Api::_()->getApi('Core', 'siteapi')->getHost();
                    $baseParentUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
                    $baseParentUrl = @trim($baseParentUrl, "/");

                    $getDefaultStorageId = Engine_Api::_()->getDbtable('services', 'storage')->getDefaultServiceIdentity();
                    $getDefaultStorageType = Engine_Api::_()->getDbtable('services', 'storage')->getService($getDefaultStorageId)->getType();

                    $host = '';
                    if ($getDefaultStorageType == 'local')
                        $host = !empty($staticBaseUrl) ? $staticBaseUrl : $serverHost;

                    if (strstr($adInfoArray['cads_url'], 'http://') || strstr($adInfoArray['cads_url'], 'https://'))
                        $host = '';

                    $tempArray['cads_url'] = $host . $adInfoArray['cads_url'];
                    if (!strstr($tempArray['cads_url'], 'http'))
                        $tempArray['cads_url'] = $serverHost . $tempArray['cads_url'];
                }
                if (isset($adInfoArray['cads_title']) && !empty($adInfoArray['cads_title'])) {
                    $tempArray['cads_title'] = $host . $adInfoArray['cads_title'];
                }
                if (isset($adInfoArray['cads_body']) && !empty($adInfoArray['cads_body'])) {
                    $tempArray['cads_body'] = $host . $adInfoArray['cads_body'];
                }
                $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($site_ad);
                $tempArray['image_icon'] = $getContentImages['image_icon'];
                
                if ($tempArray['cmd_ad_format'] == 'video') {
                    
                    if (!empty($siteadInfo->file_id)) {
                        $storage_file = Engine_Api::_()->getItem('storage_file', $siteadInfo->file_id);
                        if ($storage_file) {
                            $video_location = $storage_file->map();
                            $video_location = strstr($video_location, 'http') ? $video_location : $getHost . $video_location;
                            $tempArray['videoUrl'] = $video_location;
                            $tempArray['image'] = $getContentImages['image'];
                        }
                    }
                }

                if ($tempArray['cmd_ad_format'] == 'image') {
                    if (!empty($siteadInfo->file_id) && !empty($siteadInfo->file_type)) {

                        $userImageUrl = Engine_Api::_()->storage()->get($siteadInfo->file_id,$siteadInfo->getType())->getHref();
                        $userImageUrl = strstr($userImageUrl, 'http') ? $userImageUrl : $getHost . $userImageUrl;
                        $tempArray['image'] = $userImageUrl;
                       
                    }
                }


                if($tempArray['cmd_ad_format'] == 'carousel'){

                    $siteadSelect = $siteadTable->select()->where('userad_id=?', $tempArray['userad_id']);
                    $siteadInfos = $siteadTable->fetchAll($siteadSelect);
                    $tempArray['image'] = $getContentImages['image'];

                    foreach($siteadInfos as $siteadInfo1){
                        $adInfoArray1 = $siteadInfo1->toArray();
                        if (!empty($adInfoArray1['cta_button']))
                            $adInfoArray1['cta_button'] = $adInfoArray1['cta_button'];
                        else
                            $adInfoArray1['cta_button'] = "";
                        if(!empty($adInfoArray1['file_type'])){
                            $userImageUrl = Engine_Api::_()->storage()->get($siteadInfo1->file_id,$siteadInfo1->getType())->getHref();
                            $userImageUrl = strstr($userImageUrl, 'http') ? $userImageUrl : $getHost . $userImageUrl;
                            $adInfoArray1['imageUrl'] = $userImageUrl;

                        }
                        else{
                            $storage_file = Engine_Api::_()->getItem('storage_file', $siteadInfo1->file_id);
                            if ($storage_file) {
                                $video_location = $storage_file->map();
                                $video_location = strstr($video_location, 'http') ? $video_location : $getHost . $video_location;
                                $adInfoArray1['videoUrl'] = $video_location;
                            }
                        }
                        $tempArray1[] = $adInfoArray1;
                    }
                    $tempArray['carousel'] = $tempArray1;

                }

            }
            $advArray[] = $tempArray;
        }
        
        return $advArray;
    }

    // For Cancel advertisment by viewer and for submit reasion
    public function removeAdAction() {
        $adsId = $this->_getParam('adsId');
        $type = $this->_getParam('type', 2);

        if (empty($adsId))
            $this->respondWithError('no_record');
        if ($this->getRequest()->isGet()) {
            $this->respondWithSuccess($this->getReportAdForm());
        } else if ($this->getRequest()->isPost()) {
            try {
                $adCancelReasion = (string) $this->_getParam('adCancelReason');
                $adDescription = (string) $this->_getParam('adDescription');
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
                $this->setRequestMethod();
                $this->_forward('index', 'index', 'sitead', array(
                    'limit' => 2,
                    'placementCount' => 2,
                    'type' => $type,
                ));
                return;
            } catch (Exception $e) {
                $db->rollBack();
                $this->respondWithValidationError('internal_server_error', $e->getMessage());
            }
        }
    }

    public function getReportAdForm() {
        $reportCategories = array(
            'Misleading' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Misleading'),
            'Offensive' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Offensive'),
            'Inappropriate' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Inappropriate'),
            'Licensed Material' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Licensed Material'),
            'Other' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Other'),
        );

        $reportForm['form'][] = array(
            'type' => 'Radio',
            'name' => 'adCancelReason',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Report'),
            'multiOptions' => $reportCategories,
            'hasValidator' => true
        );

        $reportForm['form'][] = array(
            'type' => 'Submit',
            'name' => 'submit',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Submit'),
        );

        $reportForm['fields']['Other'][] = array(
            'type' => 'other',
            'name' => 'adDescription',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Specify your reason here..'),
        );

        return $reportForm;
    }

    public function updateClickCountAction() {
        $adsId = $this->_getParam('userad_id');
        $userad = Engine_Api::_()->getItem('userads', $adsId);

        if (empty($userad))
            $this->respondWithError('no_record');
        try {
            Engine_Api::_()->sitead()->ad_clickcount($adsId, false);
            $this->successResponseNoContent('no_content', true);
        } catch (Exception $ex) {
            $this->respondWithValidationError('internal_server_error', $ex->getMessage());
        }
    }

    public function videoType($type) {
        switch ($type) {
            case 1:
            case 'youtube':
                return 1;
            case 2:
            case 'vimeo':
                return 2;
            case 3:
            case 'mydevice':
            case 'upload' :
                return 3;
            case 4:
            case 'dailymotion':
                return 4;
            case 5:
            case 'embedcode':
                return 5;
            case 6;
            case 'iframely':
                return 6;
            default : return $type;
        }
    }

}

