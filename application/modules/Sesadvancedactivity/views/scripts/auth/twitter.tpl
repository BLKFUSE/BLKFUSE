<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: twitter.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<style>
body{ display:none !important;}
</style>
<script type="application/javascript">
<?php if($this->success){ ?>
  scriptJquery(document).ready(function(){
    window.opener.scriptJquery('#compose-twitter-form-input').prop('checked', !window.opener.scriptJquery('#compose-twitter-form-input').is(':checked'));
    window.opener.scriptJquery('.composer_twitter_toggle').removeClass('openWindowTwitter');
    window.opener.scriptJquery('.composer_twitter_toggle').addClass('composer_twitter_toggle_active');});
    setTimeout(function(){
       window.close();
    }, 300);
<?php }else{ ?>
  window.close();
<?php } ?>
</script>