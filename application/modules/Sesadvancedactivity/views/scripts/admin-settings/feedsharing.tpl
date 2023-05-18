<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: feedsharing.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesadvancedactivity/views/scripts/dismiss_message.tpl';

?>
<div class="settings sesbasic_admin_form sesact_global_setting">
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<script>
scriptJquery(document).ready(function() {
//enablesessocialshare(<?php //echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.enablesessocialshare', 0); ?>);
});
function enableShare(value) {

  if(value == 1) {
    var enableShareval = scriptJquery('input[name=sesadvancedactivity_enablesocialshare]:checked').val();
    var enablesessocialshareval = scriptJquery('input[name=sesadvancedactivity_enablesessocialshare]:checked').val();
    scriptJquery('input[name="sesadvancedactivity_enablesessocialshare"]').prop('checked',true);
  }
}

function enablesessocialshare(value) {

if(value == 1) {
  var enableShareval = scriptJquery('input[name=sesadvancedactivity_enablesocialshare]:checked').val();
  var enablesessocialshareval = scriptJquery('input[name=sesadvancedactivity_enablesessocialshare]:checked').val();
  scriptJquery('input[name="sesadvancedactivity_enablesocialshare"]').prop('checked',true);
  document.getElementById('sesadvancedactivity_enableplusicon-wrapper').style.display = 'block';
  document.getElementById('sesadvancedactivity_iconlimit-wrapper').style.display = 'block';
} else {
  document.getElementById('sesadvancedactivity_enableplusicon-wrapper').style.display = 'none';
  document.getElementById('sesadvancedactivity_iconlimit-wrapper').style.display = 'none';
}

}

</script>
