<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesadvpmnt
 * @package    Sesadvpmnt
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-04-25 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php if($this->gateway->getIdentity() != 1) { ?>
<?php if($this->error): ?>
	<p><?php echo $this->message; ?></p>
<?php else: ?>
	<script src="https://js.stripe.com/v3/"></script>
	<script>
	  var stripe = Stripe("<?php echo $this->publishKey; ?>");
	  stripe.redirectToCheckout({
	    sessionId: '<?php echo $this->session->id; ?>'
	  }).then(function (result) {
	    console.log(result);
	  });
	</script>
<?php endif; } else  { ?>

<script type="text/javascript">
  function jsonToQueryString(json) {
    return '?' + 
      Object.keys(json).map(function(key) {
          return encodeURIComponent(key) + '=' +
              encodeURIComponent(json[key]);
      }).join('&');
  }

  scriptJquery( window ).load(function() {
    var url = '<?php echo $this->transactionUrl ?>';
    var data = <?php echo Zend_Json::encode($this->transactionData) ?>;

    window.location.href= url +jsonToQueryString(data);
  });
</script>
<?php } ?>
