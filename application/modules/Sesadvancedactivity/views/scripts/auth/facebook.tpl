<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: facebook.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<style>
body{ display:none !important;}
</style>
<script type="application/javascript">
<?php if($this->success){ ?>
  scriptJquery(document).ready(function(){
    window.opener.scriptJquery('#compose-facebook-form-input').prop('checked', !window.opener.scriptJquery('#compose-facebook-form-input').is(':checked'));
    window.opener.scriptJquery('.composer_facebook_toggle').removeClass('openWindowFacebook');
    window.opener.scriptJquery('.composer_facebook_toggle').addClass('composer_facebook_toggle_active');});
    setTimeout(function(){
       window.close();
    }, 300);
<?php }else{ ?>
  window.close();
<?php } ?>
</script>