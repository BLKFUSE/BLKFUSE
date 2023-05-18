<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _paidContent.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>

<?php 
  $viewer = Engine_Api::_()->user()->getViewer();
  $viewer_id = $viewer->getIdentity();
  $item = $this->item; 
  if(isset($item->owner_id)) {
    $owner_id = $item->owner_id;
  } else if(isset($item->user_id)) {
    $owner_id = $item->user_id;
  }
  $package_id = $item->package_id;
  if(in_array($item->getType(), array('album', 'album_photo'))) {
		$enable = Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesalbum',1);
  } else if(in_array($item->getType(), array('video', 'sesvideo_video'))) {
		$enable = Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesvideo',1);
  } else if(in_array($item->getType(), array('sesmusic_album', 'sesmusic_albumsong'))) {
		$enable = Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1);
  }
?>
<?php if($enable && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && isset($package_id) && !empty($package_id) && $owner_id != $viewer_id) { ?>
  <?php $package = Engine_Api::_()->getItem('epaidcontent_package', $package_id); ?>
  <?php $getViewerOrder = Engine_Api::_()->getDbTable('orders','epaidcontent')->getViewerOrder(array('owner_id' => $viewer_id, 'package_owner_id' => $package->user_id, 'noCondition' => 1)); ?>
  <?php if((float) $getViewerOrder->total_amount < (float) $package->price) { ?>
    <div class="epaidcontent_attachment">
      <div class="epaidcontent_attachment_subscription_box">
         <div class="epaid_label">
           <span>Paid</span>
         </div>
        <div class="epaidcontent_attachment_subscription_box_lock"><img src="<?php echo $item->getPhotoUrl('thumb.icon'); ?>" alt="img" /></div>
        <div class="title" title="<?php echo $item->getTitle(); ?>"><a href="<?php echo $item->getHref(); ?>"><?php echo $item->getTitle(); ?></a></div>
				<div class="description">
					<?php echo $item->getDescription(); ?>
				</div>
        <a href="<?php echo $this->url(array('action' => 'showpackage', 'package_id' => $package_id), 'epaidcontent_general', true); ?>">
          <?php echo $this->translate('Subscribe Package '); ?><?php echo Engine_Api::_()->epaidcontent()->getCurrencyPrice($package->price, Engine_Api::_()->epaidcontent()->defaultCurrency()); ?>
        </a>
      </div>
    </div>
  <?php } ?>
<?php } ?>
<script>
	en4.core.runonce.add(function() {
		//Music Plugin
    scriptJquery('.paid_content').find('.sesmusic_play_button').remove();
    scriptJquery('.paid_content').find('.sesmusic_sidebar_list_play_btn').remove();
    scriptJquery('.paid_content').find('.sesmusic_artist_songslist_playbutton').remove();
    scriptJquery('.paid_content').find('.sesmusic_songslist_playbutton').remove();
    
    //Video Plugin
    scriptJquery('.paid_content').find('.sesvideo_play_btn').remove();
    scriptJquery('.paid_content').find('.sesvideo_lightbox_open').removeClass('sesvideo_lightbox_open');
  });
</script>
