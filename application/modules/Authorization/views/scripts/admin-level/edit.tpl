<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Authorization
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: edit.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>
<?php echo $this->partial('_admin_breadcrumb.tpl', 'core', array('parentMenu' => "core_admin_main_manage", 'parentMenuItemName' => 'authorization_admin_main_manage', 'childMenuItemName' => 'authorization_admin_main_level')); ?>

<?php $permissionTable = $this->permissionTable; ?>

<script type="text/javascript">
  var fetchLevelSettings = function(level_id) {
    window.location.href = en4.core.baseUrl + 'admin/authorization/level/edit/id/' + level_id;
    //alert(level_id);
  }

</script>

<h2 class="page_heading">
  <?php echo $this->translate("Member Levels") ?>
</h2>

<?php if( engine_count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
      echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>

<div class='clear'>
  <div class='settings'>
    <?php echo $this->form->render($this) ?>
  </div>
</div>
<script type="text/javascript">

  function showProfileMemberLevel(value) { 
    if(value == 1) {
      scriptJquery('#editprotylevel-wrapper').show();
    } else {
      scriptJquery('#editprotylevel-wrapper').hide();
    } 
  }
  
  function showPreview() {
    Smoothbox.open(scriptJquery('#show_default_preview'));
  }

  scriptJquery("ready").ready(function() {
    showProfileMemberLevel('<?php echo $permissionTable->getAllowed('user', $this->level_id, 'editprofiletype'); ?>');
    
    showHideSettings('lastLoginShow', '<?php echo $permissionTable->getAllowed('user', $this->level_id, 'lastLoginShow'); ?>');
    
    showHideSettings('lastUpdateShow', '<?php echo $permissionTable->getAllowed('user', $this->level_id, 'lastUpdateShow'); ?>');
    
    showHideSettings('inviteeShow', '<?php echo $permissionTable->getAllowed('user', $this->level_id, 'inviteeShow'); ?>');
    
    showHideSettings('profileTypeShow', '<?php echo $permissionTable->getAllowed('user', $this->level_id, 'profileTypeShow'); ?>');
    
    showHideSettings('memberLevelShow', '<?php echo $permissionTable->getAllowed('user', $this->level_id, 'memberLevelShow'); ?>');
    
    showHideSettings('profileViewsShow', '<?php echo $permissionTable->getAllowed('user', $this->level_id, 'profileViewsShow'); ?>');
    
    showHideSettings('joinedDateShow', '<?php echo $permissionTable->getAllowed('user', $this->level_id, 'joinedDateShow'); ?>');
    
    showHideSettings('friendsCountShow', '<?php echo $permissionTable->getAllowed('user', $this->level_id, 'friendsCountShow'); ?>');
    
    showHideSettings('changeemail', '<?php echo $permissionTable->getAllowed('user', $this->level_id, 'changeemail'); ?>');
    
    priceVerified('<?php echo $permissionTable->getAllowed('user', $this->level_id, 'paid_verified'); ?>');
  });
  
  function showHideSettings(settingName, value) {
    if(value == 1) {
      if(settingName == 'lastLoginShow') {
        scriptJquery('#showLastLogin-wrapper').hide();
        scriptJquery('#lastLoginDate-wrapper').show();
      }
      if(settingName == 'lastUpdateShow') {
        scriptJquery('#showLastUpdate-wrapper').hide();
        scriptJquery('#lastUpdateDate-wrapper').show();
      }
      if(settingName == 'inviteeShow') {
        scriptJquery('#showInvitee-wrapper').hide();
        scriptJquery('#inviteeName-wrapper').show();
      }
      if(settingName == 'profileTypeShow') {
        scriptJquery('#showProfileType-wrapper').hide();
        scriptJquery('#profileType-wrapper').show();
      }
      if(settingName == 'memberLevelShow') {
        scriptJquery('#showMemberLevel-wrapper').hide();
        scriptJquery('#memberLevel-wrapper').show();
      }
      if(settingName == 'profileViewsShow') {
        scriptJquery('#showProfileViews-wrapper').hide();
        scriptJquery('#profileViews-wrapper').show();
      }
      if(settingName == 'joinedDateShow') {
        scriptJquery('#showJoinedDate-wrapper').hide();
        scriptJquery('#joinedDate-wrapper').show();
      }
      if(settingName == 'friendsCountShow') {
        scriptJquery('#showFriendsCount-wrapper').hide();
        scriptJquery('#friendsCount-wrapper').show();
      }
      
      if(settingName == 'changeemail') {
        scriptJquery('#emailverify-wrapper').show();
      }
      
    } else {
      if(settingName == 'lastLoginShow') {
        scriptJquery('#showLastLogin-wrapper').show();
        scriptJquery('#lastLoginDate-wrapper').hide();
      }
      if(settingName == 'lastUpdateShow') {
        scriptJquery('#showLastUpdate-wrapper').show();
        scriptJquery('#lastUpdateDate-wrapper').hide();
      }
      if(settingName == 'inviteeShow') {
        scriptJquery('#showInvitee-wrapper').show();
        scriptJquery('#inviteeName-wrapper').hide();
      }
      if(settingName == 'profileTypeShow') {
        scriptJquery('#showProfileType-wrapper').show();
        scriptJquery('#profileType-wrapper').hide();
      }
      if(settingName == 'memberLevelShow') {
        scriptJquery('#showMemberLevel-wrapper').show();
        scriptJquery('#memberLevel-wrapper').hide();
      }
      if(settingName == 'profileViewsShow') {
        scriptJquery('#showProfileViews-wrapper').show();
        scriptJquery('#profileViews-wrapper').hide();
      }
      if(settingName == 'joinedDateShow') {
        scriptJquery('#showJoinedDate-wrapper').show();
        scriptJquery('#joinedDate-wrapper').hide();
      }
      if(settingName == 'friendsCountShow') {
        scriptJquery('#showFriendsCount-wrapper').show();
        scriptJquery('#friendsCount-wrapper').hide();
      }
      
      if(settingName == 'changeemail') {
        scriptJquery('#emailverify-wrapper').hide();
      }
    }
  }
  
  function priceVerified(value) {
    if(value == 1) {
      scriptJquery('#price_verified-wrapper').show();
      scriptJquery('#recurrence-wrapper').show();
    } else { 
      scriptJquery('#price_verified-wrapper').hide();
      scriptJquery('#recurrence-wrapper').hide();
    }
  }
</script>

<style type="text/css">
  .is_hidden {
    display: none;
  }
</style>
<script type="application/javascript">
  scriptJquery('.core_admin_main_manage').parent().addClass('active');
  scriptJquery('.core_admin_main_manage_levels').addClass('active');
</script>
