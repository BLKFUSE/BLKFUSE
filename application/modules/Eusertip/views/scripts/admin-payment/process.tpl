<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: process.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<div style="position:relative;height:200px;">
 <div class="sesbasic_loading_cont_overlay" style="display:block;"></div>
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
