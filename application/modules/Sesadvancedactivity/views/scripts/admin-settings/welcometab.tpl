<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: welcometab.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesadvancedactivity/views/scripts/dismiss_message.tpl';
?>
<div class="settings sesbasic_admin_form">
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<script>
  tabvisibility(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.tabvisibility', 0); ?>);
  
  friendrequest(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.friendrequest', 1); ?>);
  
  findfriendssearch(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.findfriends', 1); ?>);

  showwelcometab(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.showwelcometab', 1); ?>);
  
  //profilephotoupload(<?php //echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.profilephotoupload', 0); ?>);
  
  function friendrequest(value) {
    if(value == 1) {
      document.getElementById('sesadvancedactivity_countfriend-wrapper').style.display = 'flex';
    } else {
      document.getElementById('sesadvancedactivity_countfriend-wrapper').style.display = 'none';
    }
  }
  
  function findfriendssearch(value) {
    if(value == 1) {
      document.getElementById('sesadvancedactivity_searchnumfriend-wrapper').style.display = 'flex';
    } else {
      document.getElementById('sesadvancedactivity_searchnumfriend-wrapper').style.display = 'none';
    }
  }
  
  function showwelcometab(value) {

    if(value == 1) {
      document.getElementById('sesadvancedactivity_welcometabtext-wrapper').style.display = 'flex';
      document.getElementById('sesadvancedactivity_welcomeicon-wrapper').style.display = 'flex';
      document.getElementById('sesadvancedactivity_searchnumfriend-wrapper').style.display = 'flex';
      document.getElementById('sesadvancedactivity_tabvisibility-wrapper').style.display = 'flex';
      document.getElementById('sesadvancedactivity_makelandingtab-wrapper').style.display = 'flex';
      //document.getElementById('sesadvancedactivity_profilephotoupload-wrapper').style.display = 'flex';
      document.getElementById('sesadvancedactivity_friendrequest-wrapper').style.display = 'flex';
      document.getElementById('sesadvancedactivity_countfriend-wrapper').style.display = 'flex';
      document.getElementById('sesadvancedactivity_findfriends-wrapper').style.display = 'flex';
      document.getElementById('sesadvancedactivity_tabsettings-wrapper').style.display = 'flex';
    } else {
      document.getElementById('sesadvancedactivity_welcometabtext-wrapper').style.display = 'none';
      document.getElementById('sesadvancedactivity_welcomeicon-wrapper').style.display = 'none';
      document.getElementById('sesadvancedactivity_searchnumfriend-wrapper').style.display = 'none';
      document.getElementById('sesadvancedactivity_tabvisibility-wrapper').style.display = 'none';
      document.getElementById('sesadvancedactivity_makelandingtab-wrapper').style.display = 'none';
      //document.getElementById('sesadvancedactivity_profilephotoupload-wrapper').style.display = 'none';
      document.getElementById('sesadvancedactivity_friendrequest-wrapper').style.display = 'none';
      document.getElementById('sesadvancedactivity_countfriend-wrapper').style.display = 'none';
      document.getElementById('sesadvancedactivity_findfriends-wrapper').style.display = 'none';
      document.getElementById('sesadvancedactivity_tabsettings-wrapper').style.display = 'none';
    }
  }

  function tabvisibility(value) {
    if(value == 2) {
      document.getElementById('sesadvancedactivity_numberoffriends-wrapper').style.display = 'none';
      document.getElementById('sesadvancedactivity_numberofdays-wrapper').style.display = 'flex';
    } else if(value == 1) {
      document.getElementById('sesadvancedactivity_numberoffriends-wrapper').style.display = 'flex';
      document.getElementById('sesadvancedactivity_numberofdays-wrapper').style.display = 'none';
    } else if(value == 0) {
      document.getElementById('sesadvancedactivity_numberoffriends-wrapper').style.display = 'none';
      document.getElementById('sesadvancedactivity_numberofdays-wrapper').style.display = 'none';
    }
  }

  
  function profilephotoupload(value) {
    if(value == 1) {
      document.getElementById('sesadvancedactivity_canphotoshow-wrapper').style.display = 'flex';
    } else if(value == 0) {
      document.getElementById('sesadvancedactivity_canphotoshow-wrapper').style.display = 'none';
    }
  }
</script>
