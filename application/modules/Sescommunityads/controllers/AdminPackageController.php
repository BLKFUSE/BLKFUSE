<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminPackageController.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_AdminPackageController extends Core_Controller_Action_Admin {

  public function manageAction() {
    $db = Engine_Db_Table::getDefaultAdapter();

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_package');

    // Test curl support
    if( !function_exists('curl_version') ||
        !($info = curl_version()) ) {
      $this->view->error = $this->view->translate('The PHP extension cURL ' .
          'does not appear to be installed, which is required ' .
          'for interaction with payment gateways. Please contact your ' .
          'hosting provider.');
    }
    // Test curl ssl support
    else if( !($info['features'] & CURL_VERSION_SSL) ||
        !engine_in_array('https', $info['protocols']) ) {
      $this->view->error = $this->view->translate('The installed version of ' .
          'the cURL PHP extension does not support HTTPS, which is required ' .
          'for interaction with payment gateways. Please contact your ' .
          'hosting provider.');
    }
    // Check for enabled payment gateways
    else if( Engine_Api::_()->getDbtable('gateways', 'payment')->getEnabledGatewayCount() <= 0 ) {
      $this->view->error = $this->view->translate('There are currently no ' .
          'enabled payment gateways. You must %1$sadd one%2$s before this ' .
          'page is available.', '<a href="' .
          $this->view->escape($this->view->url(array('controller' => 'gateway','module'=>'payment','action'=>'index'))) .
          '">', '</a>');
    }

    // Make form
    $this->view->formFilter = $formFilter = new Sescommunityads_Form_Admin_Package_Filter();

    // Process form
    if( $formFilter->isValid($this->_getAllParams()) ) {
      $filterValues = $formFilter->getValues();
    } else {
      $filterValues = array(
        'enabled' => 1,
      );
      $formFilter->populate(array('enabled' => 1));
    }
    if( empty($filterValues['order']) ) {
      $filterValues['order'] = 'package_id';
    }
    if( empty($filterValues['direction']) ) {
      $filterValues['direction'] = 'DESC';
    }
    $this->view->filterValues = $filterValues;
    $this->view->order = $filterValues['order'];
    $this->view->direction = $filterValues['direction'];

    // Initialize select
    $table = Engine_Api::_()->getDbtable('packages', 'sescommunityads');
    $select = $table->select();

    // Add filter values
    if( !empty($filterValues['query']) ) {
      $select->where('title LIKE ?', '%' . $filterValues['query'] . '%');
    }
    if( !empty($filterValues['level_id']) ) {
      $select->where('concat(",",REPLACE(REPLACE(level_id,"[",""),"]",""),",") LIKE \'%,"'.$filterValues['level_id'].'",%\'');
    }
    if( isset($filterValues['enabled']) && '' != $filterValues['enabled'] ) {
      $select->where('enabled = ?', $filterValues['enabled']);
    }
    if( !empty($filterValues['order']) ) {
      if( empty($filterValues['direction']) ) {
        $filterValues['direction'] = 'ASC';
      }
      $select->order($filterValues['order'] . ' ' . $filterValues['direction']);
    }

    // Make paginator
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));

    // Get member totals for each plan
    $memberCounts = array();
    foreach( $paginator as $item ) {
      if($item->default == 1){
        $memberCounts[$item->package_id] = Engine_Api::_()->getDbTable('sescommunityads', 'sescommunityads')
        ->select()
        ->from('engine4_sescommunityads_ads', new Zend_Db_Expr('COUNT(*)'))
        ->where('package_id = ?', $item->package_id)
        ->query()
        ->fetchColumn();
      }else{
      $memberCounts[$item->package_id] = Engine_Api::_()->getDbtable('transactions', 'sescommunityads')
        ->select()
        ->from('engine4_sescommunityads_transactions', new Zend_Db_Expr('COUNT(*)'))
        ->where('package_id = ?', $item->package_id)
        ->where('state = ?', 'active')
        ->query()
        ->fetchColumn();
      }
    }
    $this->view->adsCounts = $memberCounts;
  }
  public function createAction(){
  $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_package');
    $package_id = $this->_getParam('package_id',0);

    // Make form
    $this->view->form = $form = new Sescommunityads_Form_Admin_Package_Create();
    $locale = $this->view->locale()->getLocaleDefault();
    $defaultVal = $this->view->locale()->toNumber('0.00', array('default_locale' => true));
    $form->price->setValue($defaultVal)
      ->addValidator('float', true, array('locale' => $locale));

    // Get supported billing cycles
    $gateways = array();
    $supportedBillingCycles = array();
    $partiallySupportedBillingCycles = array();
    $fullySupportedBillingCycles = array();
    $gatewaysTable = Engine_Api::_()->getDbtable('gateways', 'payment');
    foreach( $gatewaysTable->fetchAll(array('enabled = ?' => 1)) as $gateway ) {
      $gateways[$gateway->gateway_id] = $gateway;
      $supportedBillingCycles[$gateway->gateway_id] = $gateway->getGateway()->getSupportedBillingCycles();
      $partiallySupportedBillingCycles = array_merge($partiallySupportedBillingCycles, $supportedBillingCycles[$gateway->gateway_id]);
      if( null === $fullySupportedBillingCycles ) {
        $fullySupportedBillingCycles = $supportedBillingCycles[$gateway->gateway_id];
      } else {
        $fullySupportedBillingCycles = array_intersect($fullySupportedBillingCycles, $supportedBillingCycles[$gateway->gateway_id]);
      }
    }
    $partiallySupportedBillingCycles = array_diff($partiallySupportedBillingCycles, $fullySupportedBillingCycles);

    $multiOptions = /* array(
      'Fully Supported' =>*/ array_combine(array_map('strtolower', $fullySupportedBillingCycles), $fullySupportedBillingCycles)/*,
      'Partially Supported' => array_combine(array_map('strtolower', $partiallySupportedBillingCycles), $partiallySupportedBillingCycles),
    )*/;

  	if($multiOptions){
    $form->getElement('recurrence')
      ->setMultiOptions($multiOptions);
      //->setDescription('-')
      ;

  //  $form->getElement('recurrence')->options/*['Fully Supported']*/['forever'] = 'One-time';

    $form->getElement('duration')
      ->setMultiOptions($multiOptions)
      //->setDescription('-')
      ;
		}
    $form->getElement('duration')->options/*['Fully Supported']*/['forever'] = 'Forever';

    if($package_id){
      $package = Engine_Api::_()->getItem('sescommunityads_packages',$package_id);
      $values = $package->toArray();
      $values['level_id'] = (json_decode($package->level_id,true));
      $values['modules'] = (json_decode($package->modules,true));

      $values['recurrence'] = array($values['recurrence'], $values['recurrence_type']);
      $values['duration'] = array($values['duration'], $values['duration_type']);

      unset($values['recurrence_type']);
      unset($values['duration_type']);

      $otherValues = array(
        'price' => $values['price'],
        'recurrence' => $values['recurrence'],
        'duration' => $values['duration'],
      );

      $values['price'] = $this->view->locale()->toNumber($values['price'], array('default_locale' => true));
      $form->populate($values);
    }

    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    // Process
    $values = $form->getValues();
    $tmp = $values['recurrence'];
    unset($values['recurrence']);
    if( empty($tmp) || !is_array($tmp) ) {
      $tmp = array(null, null);
    }
    $values['recurrence'] = (int) $tmp[0];
    $values['recurrence_type'] = $tmp[1];

    $tmp = $values['duration'];
    unset($values['duration']);
    if( empty($tmp) || !is_array($tmp) ) {
      $tmp = array(null, null);
    }
    $values['duration'] = (int) $tmp[0];
    $values['duration_type'] = $tmp[1];

    if( !empty($values['default']) && (float) $values['price'] > 0 ) {
      return $form->addError('Only a free plan may be the default plan.');
    }

    $packageTable = Engine_Api::_()->getDbtable('packages', 'sescommunityads');
    $db = $packageTable->getAdapter();
    $db->beginTransaction();

    try {

      // Update default
      if( !empty($values['default']) ) {
        $packageTable->update(array(
          'default' => 0,
        ), array(
          '`default` = ?' => 1,
        ));
      }

      $values['price'] = Zend_Locale_Format::getNumber($values['price'], array('locale' => $locale));

      if(!$package_id)
        $package = $packageTable->createRow();

      $values['level_id'] = json_encode($values['level_id']);
      $values['modules'] = json_encode($values['modules']);
      if($values['package_type'] == "nonRecurring"){
        unset($values['duration']);
        unset($values['duration_type']);
        unset($values['recurrence']);
        unset($values['recurrence_type']);
      }
      if($values['package_type'] == "recurring" && $values['click_type'] == "perday")
        unset($values['click_limit']);
      $package->setFromArray($values);
      $package->save();

      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }
    // Redirect
    return $this->_helper->redirector->gotoRoute(array('action' => 'manage','controller'=>'package','module'=>'sescommunityads'),'admin_default',true);

  }
   public function enabledAction(){
      $id = $this->_getParam('id',0);
      $package = Engine_Api::_()->getItem('sescommunityads_packages',$id);
     $package->enabled = !$package->enabled;
     $package->save();
     header("Location:".$_SERVER['HTTP_REFERER']);
      exit();
   }
  public function defaultAction(){
      $id = $this->_getParam('id',0);
      $package = Engine_Api::_()->getItem('sescommunityads_packages',$id);
      // Update default
      $packageTable = Engine_Api::_()->getItemTable('sescommunityads_packages');
        $packageTable->update(array(
          'default' => 0,
        ), array(
          '`default` = ?' => 1,
        ));

      $package->default = 1;
      $package->save();
      header("Location:".$_SERVER['HTTP_REFERER']);
      exit();

  }
}
