<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescredit
 * @package    Sescredit
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: process.tpl  2019-01-18 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl') . 'application/modules/Sescredit/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescredit/externals/styles/styles.css'); ?>
<div style="margin: 50px 0" class="sesbasic_bxs">
 <div class="sescredit_lds_ring"><div></div><div></div><div></div><div></div></div>
 <div class="sescredit_lds_msg"><?php echo $this->translate("Please do not close tab while we are processing."); ?></div>
</div>
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
