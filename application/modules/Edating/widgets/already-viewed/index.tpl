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

<h3><?php echo $this->translate("Already viewed"); ?></h3>
<div class="edating_users_notification">
  <span class="tip">
    <?php echo $this->translate("You rated these profiles 'NO'."); ?>
  </span>
</div>
<?php $photoTable = Engine_Api::_()->getDbTable('photos', 'edating'); ?>
<?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
      <div class="edating_users_listing row">
      <?php foreach ($this->paginator as $item): ?>
        <?php $sender = Engine_Api::_()->getItem('user', $item->owner_id); ?>
        <?php $receiver = Engine_Api::_()->getItem('user', $item->user_id); ?>
          <div class="col-lg-3 col-md-6 col-sm-12 edating_users_listing_item">
            <article>
              <?php if (isset($receiver->coverphoto) && !empty($receiver->coverphoto)) {
                $memberCover =	Engine_Api::_()->storage()->get($receiver->coverphoto, ''); 
                if($memberCover)
                  $memberCover = $memberCover->map();
                 ?>
                <div class="edating_users_cover_bg"><img src="<?php echo $memberCover; ?>" /></div>
              <?php } else { ?>
                <div class="edating_users_cover_bg"></div>
              <?php } ?>
              <div class='edating_users_photo'>
                <?php $getMainDatingPhoto = $photoTable->getMainDatingPhoto($receiver->getIdentity()); ?>
                    <?php if (!empty($getMainDatingPhoto)) { ?>
                      <?php $photo = Engine_Api::_()->getItem('edating_photo', $getMainDatingPhoto->photo_id); ?>
                      <!-- <img src='<?php // echo $photo->getPhotoUrl("thumb.normal"); ?>' style="max-width:100%;" /> -->
                      <?php echo $this->itemBackgroundPhoto($photo, 'thumb.profile', $photo->getTitle()) ?>
                    <?php } else { ?>
                      <?php echo $this->itemBackgroundPhoto($receiver, 'thumb.profile', $receiver->getTitle()) ?>
                <?php  } ?>
              </div>
              <div class='edating_users_listing_content'>
                <div class='edating_username'>
                  <?php echo $this->htmlLink($receiver->getHref(), $receiver->getTitle()) ?> 
                </div>
                <div class="edating_users_date sesbasic_font_small">
                  <p><?php echo $this->translate("Your sent reject: "); ?> <?php echo date("d M, Y",$user->time_stamp); ?></p>
                  <p><?php echo $this->translate("Status: Reject "); ?></p>
                </div>
                <!-- <div class='edating_users_listing_btns'>
                  <div>
                    <a href="javascript:void(0);" class="sesbasic_link_btn" onclick="cancel(<?php echo $item->getIdentity();?>)"><?php echo $this->translate('Cancel');?></a>
                  </div>
                </div> -->
              </div>
            </article>
          </div>
      <?php endforeach; ?>
    </div>
  <?php echo $this->paginationControl($this->paginator, null, null, array('pageAsQuery' => true)); ?>
<?php else : ?>
	<div class="tip">
    <span>
      <?php echo $this->translate("You are not rejected any profiles."); ?>
    </span>
  </div>
<?php endif; ?>
