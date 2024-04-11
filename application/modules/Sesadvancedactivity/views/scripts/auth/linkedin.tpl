<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: linkedin.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<style>
body{ display:none !important;}
</style>
<script type="application/javascript">
<?php if($this->success){ ?>
  scriptJquery(document).ready(function(){
    window.opener.scriptJquery('#compose-linkedin-form-input').prop('checked', !window.opener.scriptJquery('#compose-linkedin-form-input').is(':checked'));
    window.opener.scriptJquery('.composer_linkedin_toggle').removeClass('openWindowLinkedin');
    window.opener.scriptJquery('.composer_linkedin_toggle').addClass('composer_linkedin_toggle_active');});
    setTimeout(function(){
       window.close();
    }, 300);
<?php }else{ ?>
  window.close();
<?php } ?>
</script>