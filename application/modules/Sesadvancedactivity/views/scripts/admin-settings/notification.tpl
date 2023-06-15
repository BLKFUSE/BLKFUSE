<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: notification.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesadvancedactivity/views/scripts/dismiss_message.tpl';?>
<div class="settings sesbasic_admin_form">
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<script type="application/javascript">

function showDays(value){
  if(value == 1){
    document.getElementById('sesadvancedactivity_notificationfriendsdays-wrapper').style.display = 'block';		
  }else{
    document.getElementById('sesadvancedactivity_notificationfriendsdays-wrapper').style.display = 'none';
  }
}
showDays(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.notificationfriends', 1); ?>);
function birthdayText(value){
  if(value == 1){
    document.getElementById('sesadvancedactivity_friendnotificationbirthdaytext-wrapper').style.display = 'block';		
  }else{
    document.getElementById('sesadvancedactivity_friendnotificationbirthdaytext-wrapper').style.display = 'none';
  } 
}
birthdayText(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.friendnotificationbirthday', 1); ?>);
function dayText(value){
  if(value == 1){
    document.getElementById('sesadvancedactivity_notificationdaytext-wrapper').style.display = 'block';		
  }else{
    document.getElementById('sesadvancedactivity_notificationdaytext-wrapper').style.display = 'none';
  } 
}
birthdayText(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.notificationday', 1); ?>);

function notificationbday(value){
  if(value == 1){
    document.getElementById('sesadvancedactivity_notificationbirthdaytext-wrapper').style.display = 'block';		
  }else{
    document.getElementById('sesadvancedactivity_notificationbirthdaytext-wrapper').style.display = 'none';
  } 
}
notificationbday(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.notificationbirthday', 1); ?>);
</script>