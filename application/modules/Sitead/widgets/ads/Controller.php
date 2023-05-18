<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Widget_AdsController extends Engine_Content_Widget_Abstract {

    public function indexAction() {
        $session = new Zend_Session_Namespace('Sitead_Ads_Renders');
        $front = Zend_Controller_Front::getInstance();
        $module = $front->getRequest()->getModuleName();
        $action = $front->getRequest()->getActionName();
        $controller = $front->getRequest()->getControllerName();
        $this->view->adboardPage = $this->_getParam('isAdboardPage', false);
        if (($controller == 'display' && $action == 'adboard' && $module == 'sitead')) {
            $this->view->adboardPage = true;
            if (!$this->_getParam('showOnAdboard', true)) {
                return $this->setNoRender();
            }
        }
        if ($this->_getParam('loaded_by_ajax', false)) {
            $this->view->loaded_by_ajax = true;
            if ($this->_getParam('is_ajax_load', false)) {
                $this->view->is_ajax_load = true;
                $this->view->loaded_by_ajax = false;
                if (!$this->_getParam('onloadAdd', false))
                    $this->getElement()->removeDecorator('Title');
                $this->getElement()->removeDecorator('Container');

                $this->view->showContent = true;
            }
            $this->view->widgetId = $this->_getParam('widgetId', false);
            if ($this->view->widgetId)
                $this->view->params = $this->_getAllParams();
        } else {
            $this->view->showContent = true;
        }
        $this->view->showHeader = $this->_getParam('showHeader', true);
        if ($this->view->showContent) {

            $this->view->viewer_object = $viewer_object = Engine_Api::_()->user()->getViewer();
            $this->view->user_id = $viewer_object->getIdentity();
            $params = array();
            $this->view->limit = $params['lim'] = $this->_getParam('itemCount', 3);
            $packageIds = $this->_getParam('packageIds', array());
            if ($packageIds) {
                $packages = Engine_Api::_()->getItemtable('package')->getEnabledPackageList('default');
                $packageIds = array_intersect($packageIds, array_keys($packages));
                if ($packageIds) {
                    $params['packageIds'] = $packageIds;
                }
            }
            //packageIds
            $this->view->showType = $this->_getParam('show_type', 'all');
            if ($this->view->showType != 'all')
                $params[$this->view->showType] = 1;

            $params['excludeAdIds'] = Engine_Api::_()->sitead()->getAlreadyRenderAdIds();
            $fetch_site_ads = Engine_Api::_()->sitead()->getAdvertisement($params);
            $siteadinfo_table = Engine_Api::_()->getItemTable('sitead_adsinfo');
            $fetch_site_adsinfo = $siteadinfo_table->fetchAll();
            // Check if ads to be displayed are not empty
            if (!empty($fetch_site_ads) && !empty($fetch_site_adsinfo)) {
                $this->view->siteads_array = $fetch_site_ads;
                $this->view->siteadsinfo_array = $fetch_site_adsinfo;
                $this->view->hideCustomUrl = Engine_Api::_()->sitead()->hideCustomUrl();
                $adIds = array();
                foreach ($fetch_site_ads as $row) {
                    $adIds [] = $row->userad_id;
                }
                Engine_Api::_()->sitead()->setAlreadyRenderAdIds($adIds);
            } else {
                return $this->setNoRender();
            }
        }
    }

}

?>