<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Edating/externals/styles/styles.css'); ?>

<h3><?php echo $this->translate("Who Likes Me"); ?></h3>
<div class="edating_users_notification">
  <span class="tip">
    <?php echo $this->translate("These users rated your profile 'yes' and awaiting your decision."); ?>
  </span>
</div>
<?php $photoTable = Engine_Api::_()->getDbTable('photos', 'edating'); ?>
<?php if( $this->paginator->getTotalItemCount() > 0 ): ?>

  <div class="edating_users_listing row">
      <?php foreach ($this->paginator as $item): ?>
        <?php $sender = Engine_Api::_()->getItem('user', $item->user_id); ?>
        <?php $receiver = Engine_Api::_()->getItem('user', $item->owner_id); ?>
          <div class="col-lg-3 col-md-6 col-sm-12 edating_users_listing_item">
            <article>
              <?php if (isset($sender->coverphoto) && !empty($receiver->coverphoto)) {
                $memberCover =	Engine_Api::_()->storage()->get($sender->coverphoto, ''); 
                if($memberCover)
                  $memberCover = $memberCover->map();
                 ?>
                <div class="edating_users_cover_bg"><img src="<?php echo $memberCover; ?>" /></div>
              <?php } else { ?>
                <div class="edating_users_cover_bg"></div>
              <?php } ?>
              <div class='edating_users_photo'>
                <?php $getMainDatingPhoto = $photoTable->getMainDatingPhoto($sender->getIdentity()); ?>
                  <?php if (!empty($getMainDatingPhoto)) { ?>
                    <?php $photo = Engine_Api::_()->getItem('edating_photo', $getMainDatingPhoto->photo_id); ?>
                    <!-- <img src='<?php // echo $photo->getPhotoUrl("thumb.normal"); ?>' style="max-width:100%;" /> -->
                    <?php echo $this->itemBackgroundPhoto($photo, 'thumb.profile', $photo->getTitle()) ?>
                    <?php } else { ?>
                    <?php echo $this->itemBackgroundPhoto($sender, 'thumb.profile', $sender->getTitle()) ?>
                    <?php  } ?>
              </div>
              <div class='edating_users_listing_content'>
                <div class='edating_username'>
                  <?php echo $this->htmlLink($sender->getHref(), $sender->getTitle()); ?>                
                </div>
                <div class="edating_users_date sesbasic_font_small">
                  <p><?php echo $this->translate("User sent like: "); ?> <?php echo date("d M, Y", $item->time_stamp); ?></p>
                  <p><?php echo $this->translate("Status: Awaiting your response "); ?></p>
                </div>
                <div class='edating_users_listing_btns'>
                  <div><a title="<?php echo $this->translate('Accept'); ?>" href="javascript:void(0)" class='dating_accept' onclick="accept(<?php echo $item->getIdentity();?>, 'liked')"><i class="fa fa-check"></i></a></div>
                  <div><a title="<?php echo $this->translate('Reject'); ?>" href="javascript:void(0)" class='dating_reject' onclick="reject(<?php echo $item->getIdentity();?>, 'reject')"><i class="fa fa-times"></i></a></div>
                </div>
              </div>
            </article>
          </div>
      <?php endforeach; ?>
    </div>
  <?php echo $this->paginationControl($this->paginator, null, null, array('pageAsQuery' => true)); ?>
<?php else : ?>
	<div class="tip">
    <span>
      <?php echo $this->translate("You are not make any likes"); ?>
    </span>
  </div>
<?php endif; ?>

<script type="text/javascript">
  function accept(user_id, reaction) {
    var URL = "<?php echo $this->url(array('action' => 'like'), 'edating_general', true); ?>";
    scriptJquery.ajax({
      method: 'post',
      url:  URL,
      'data': {
        format: 'json',
        user_id: user_id,
        reaction:reaction,
      },
      success: function(responseJson) {
        var response = jQuery.parseJSON(responseJson);
        if(response.status == true) {
          window.location.reload();
        }
      }
    });
  }

  function reject(user_id, reaction) {
    var URL = "<?php echo $this->url(array('action' => 'reject'), 'edating_general', true); ?>";
    scriptJquery.ajax({
      method: 'post',
      url:  URL,
      'data': {
        format: 'json',
        user_id: user_id,
        reaction: reaction,
      },
      success: function(responseJson) {
        var response = jQuery.parseJSON(responseJson);
        if(response.status == true) {
          if (reaction == "reject") {
            window.location.reload();
          }
        }
      }
    });
  }
</script>
