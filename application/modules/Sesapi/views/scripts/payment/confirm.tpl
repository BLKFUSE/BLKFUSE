<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesapi
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: confirm.tpl 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>

<form method="post" action="<?php echo $this->escape($this->url()) ?>?package_id=<?php echo $this->package->package_id ?>"
      class="global_form" enctype="application/x-www-form-urlencoded">
  <div>
    <div>
      <h3>
        <?php echo $this->translate('Confirm Subscription') ?>
      </h3>
      <p class="form-description">
        <?php echo $this->translate('You are about to subscribe to the plan: ' .
            '%1$s', '<strong>' .
            $this->translate($this->package->title) . '</strong>') ?>
        <br />
        <?php echo $this->translate('Are you sure you want to do this? You ' .
            'will be charged: %1$s',
            '<strong>' . $this->package->getPackageDescription()
            . '</strong>') ?>
      </p>
      <p style="padding-top: 15px; padding-bottom: 15px;">
        <?php echo $this->translate('If yes, click the button below and you ' .
            'will be taken to a payment page. When you have completed your ' .
            'payment, please remember to click the button that takes you back ' .
            'to our site.') ?>
      </p>
      <p style="padding-top: 15px; padding-bottom: 15px;">
        <?php echo $this->translate('Please note that no refund will be ' .
            'provided for any unused portion of your current plan.') ?>
      </p>
      <div class="form-elements">
        <div class="form-wrapper" id="execute-wrapper">
          <div class="form-element" id="execute-element">
            <button type="submit" id="execute" name="execute"><?php echo $this->translate('Subscribe') ?></button>
            <?php echo $this->translate(' or ') ?>
            <?php echo $this->htmlLink(array('action' => 'index',
              'package_id' => null), $this->translate('cancel')) ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="gateway_id" id="gateway_id" value="" />
</form>
