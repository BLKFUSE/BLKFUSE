<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eticktokclone/externals/styles/styles.css'); ?>
<div class="eticktokclone_suggested_members">
   <div class="eticktokclone_suggested_members_inner">
     <!-- <a href="members" class="view_all" title="<?php echo $this->translate('View All'); ?>"><i class="fa fa-angle-right"></i></a> -->
      <?php foreach($this->results as $item) { ?>
        <div class="eticktokclone_member_item">
          <div class="_img">
            <a href="<?php echo $item->getHref(); ?>"><?php echo $this->itemPhoto($item, 'thumb.icon'); ?></a>
          </div> 
          <div class="_cont">
            <span class="_name"><a href="<?php echo $item->getHref(); ?>"><?php echo $item->getTitle(); ?></a></span>
            <span class="_username sesbasic_text_light sesbasic_font_small"><?php echo $item->username; ?></span>
          </div>
          <?php if($this->viewer()->getIdentity() && $item->getIdentity() != $this->viewer()->getIdentity()){ ?>
            <div class="_btn">
              <?php $FollowUser = Engine_Api::_()->eticktokclone()->getFollowStatus($item->getIdentity());
              ?>
              <a href="javascript:void(0);" onclick="eticktokclone_follow_button(this)" data-url="<?php echo $item->getIdentity(); ?>" style="display:<?php echo !$FollowUser ? "" : "none" ?>;" class="eticktokclone_follow_button follow" data-bs-toggle="eticktokclone_tooltip" data-bs-title="<?php echo $this->translate("Follow"); ?>"><i class="fas fa-user-check"></i></a>
              <a href="javascript:void(0);" onclick="eticktokclone_follow_button(this)" data-url="<?php echo $item->getIdentity(); ?>" style="display:<?php echo $FollowUser ? "" : "none" ?>;" class="eticktokclone_follow_button unfollow active" data-bs-toggle="eticktokclone_tooltip" data-bs-title="<?php echo $this->translate("Un-Follow"); ?>"><i class="fas fa-user-check"></i></a>
            </div>
          <?php } ?>
        </div>
      <?php } ?>
   </div>
</div>

<script type="text/javascript">
// Tooltip
scriptJquery(document).ready(function(){
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="eticktokclone_tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  });
});
</script>