<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesapi
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: IndexController.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */


class Sesmultiplecurrency_IndexController extends Sesapi_Controller_Action_Standard
{
    public function supportedCurrenciesAction()
    {

        $fullySupportedCurrencies = Engine_Api::_()->sesmultiplecurrency()->getSupportedCurrency();
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $currencies = array();
        $defaultCurrency = Engine_Api::_()->payment()->defaultCurrency();
        foreach ($fullySupportedCurrencies as $key => $values) {
            if(!$settings->getSetting('sesmultiplecurrency.'.$key.'active','0') && $key != $defaultCurrency)
                continue;
            $currencies[$key] = $values;
        }
        $result['enabled_currencies'] = $currencies;
        $result['default_currency']  = Engine_Api::_()->payment()->getCurrentCurrency();
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => "", 'result' => $result));
    } 
    public function changeCurrencyAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        $_SESSION['sesmultiplecurrency_currencyId'] = $this->_getParam('currency',"USD");
        $settings = Engine_Api::_()->getApi('settings', 'core');
        if($viewer->getIdentity() && $settings->hasSetting("sesmultiplecurrency_user".$viewer->getIdentity())){
            $settings->removeSetting("sesmultiplecurrency_user".$viewer->getIdentity());
        }
        if($viewer->getIdentity()){
            $settings->setSetting("sesmultiplecurrency_user".$viewer->getIdentity(),$_SESSION['sesmultiplecurrency_currencyId']);
        }
        setcookie('sesmultiplecurrency_currencyId', $_SESSION['sesmultiplecurrency_currencyId'], time() + (86400*365), '/');
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => "",'result'=>array('default_currency'=>$_SESSION['sesmultiplecurrency_currencyId'])));
        
    }
}
