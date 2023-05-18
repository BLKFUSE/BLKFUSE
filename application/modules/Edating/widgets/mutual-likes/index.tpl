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
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js'); ?>

<h3><?php echo $this->translate("Mutual Likes"); ?></h3>
  <div class="edating_users_notification">
    <span class="tip">
      <?php echo $this->translate("Your and profile which you like rated 'yes'. This is good chance to meet!"); ?>
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
                <div class="edating_users_cover_bg" style="background-image:url(<?php echo $memberCover; ?>);"></div>
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
                  <i class="fa fa-heart" style='color:red;'></i>
                  <p><?php echo $this->translate("Good chance to write message or add to friends!"); ?></p>
                </div>
                <div class='edating_users_listing_btns'>
                  <div>
                    <?php if( $row == NULL ): ?>
                      <?php if($this->viewer()->getIdentity()): ?>
                        <?php echo $this->userFriendship($item); ?>
                      <?php endif; ?>
                    <?php endif; ?>
                    </div>
                    <div>
                    <?php if (Engine_Api::_()->sesbasic()->hasCheckMessage($item)): ?>
                      <a class="buttonlink sesbasic_link_btn icon_message" href="<?php echo $this->baseUrl(). '/messages/compose/to/'.$item->user_id ?>"><?php echo $this->translate("Message"); ?></a>
                    <?php endif; ?>
                  </div>
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
      <?php echo $this->translate("No one likes your profile."); ?>
    </span>
  </div>
<?php endif; ?>
<script>
  scriptJquery('.edating_users_listing_btns .buttonlink').addClass('sesbasic_link_btn');
</script>
