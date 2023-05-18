<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: account-details.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Epaidcontent/externals/styles/style.css'); ?>

<div class="epaidcontent_account_details sesbm sesbasic_clearfix">
  <ul class="epaidcontent_account_details_tabs">
    <li class="<?php echo $this->gateway_type == 'paypal' ? '_active' : ''; ?> active"><a href="<?php echo $this->url(array('action'=>'account-details'), 'epaidcontent_general', true).'?gateway_type=paypal'; ?>" class="sesbasic_dashboard_nopropagate_content"><i class="fab fa-cc-paypal"></i><span><?php echo $this->translate('Paypal Details'); ?></span></a></li>
    <?php  if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvpmnt') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvpmnt.enable.package', 1)){ ?>
      <li class="<?php echo $this->gateway_type == 'stripe' ? '_active' : ''; ?> "><a href="<?php echo $this->url(array('action'=>'account-details'), 'epaidcontent_general', true).'?gateway_type=stripe'; ?>" class="sesbasic_dashboard_nopropagate_content"><i class="fab fa-cc-stripe"></i><span><?php echo $this->translate('Stripe Details'); ?></span></a></li>
    <?php  } ?>
    <?php  if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('epaytm')){ ?>
      <li class="<?php echo $this->gateway_type == 'paytm' ? '_active' : ''; ?>"><a href="<?php echo $this->url(array('action'=>'account-details'), 'epaidcontent_general', true).'?gateway_type=paytm'; ?>" class="sesbasic_dashboard_nopropagate_content"><i class="fab fa-cc-paytm"></i><span><?php echo $this->translate('Paytm Details'); ?></span></a></li>
    <?php } ?>
  </ul>
  <div class="epaidcontent_account_details_form">
    <?php echo $this->form->render() ?>
  </div>
</div>
