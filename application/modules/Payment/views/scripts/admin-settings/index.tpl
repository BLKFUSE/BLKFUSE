<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Payment
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John Boehr <j@webligo.com>
 */
?>
<?php echo $this->partial('_admin_breadcrumb.tpl', 'core', array('parentMenu' => "core_admin_main_monetization", 'parentMenuItemName' => 'core_admin_main_payment', 'childMenuItemName' => 'core_admin_main_payment_settings')); ?>

<script type="text/javascript">
  var supportedCurrencyIndex;
  var gateways;
  var displayCurrencyGateways = function() {
    var currency = scriptJquery('#currency').val();
    var has = [], hasNot = [];
    Object.entries(gateways).forEach(function([id,title]) {
      if( !supportedCurrencyIndex.has(title) ) {
        hasNot.push(title);
      } else if($type(supportedCurrencyIndex.get(title)) && !Object.values(supportedCurrencyIndex.get(title)).includes(currency)) {
        hasNot.push(title);
      } else {
        has.push(title);
      }
      var supportString = '';
      if( has.length > 0 ) {
        supportString += '<span class="currency-gateway-supported">'
            + 'Supported Gateways: ' + has + '</span>';
      }
      if( has.length > 0 && hasNot.length > 0 ) {
        supportString += '<br />';
      }
      if( hasNot.length > 0 ) {
        supportString += '<span class="currency-gateway-unsupported">'
            + 'Unsupported Gateways: ' + hasNot + '</span>';
      }
      scriptJquery('#currency-element .description').html(supportString);
    });

  }
  window.addEventListener('load', function() {
    supportedCurrencyIndex = new Hash(<?php echo Zend_Json::encode($this->supportedCurrencyIndex) ?>);
    gateways = new Hash(<?php echo Zend_Json::encode($this->gateways) ?>);
    scriptJquery('#currency').on('change', displayCurrencyGateways);
    displayCurrencyGateways();
  });

  scriptJquery('.core_admin_main_monetization').parent().addClass('active');
  scriptJquery('.core_admin_main_payment').addClass('active');
  
  scriptJquery(document).ready(function() {
    autoUpdateCurrency('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting("payment.autoupdate",0); ?>');
  });
  
  function autoUpdateCurrency(value) { 
    if(value == 1) { 
      scriptJquery('#currencyapikey-wrapper').show();
    } else {
      scriptJquery('#currencyapikey-wrapper').hide();
    }
  }
</script>
<h2 class="page_heading">
  <?php echo $this->translate("Billing Settings") ?>
</h2>	
<?php if( count($this->navigation) ): ?>
<div class='tabs'>
    <?php
    // Render the menu
    //->setUlClass()
    echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
</div>
<?php endif; ?>
<div class="settings">
  <?php echo $this->form->render($this) ?>
</div>

