<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _activityAlbum.tpl 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $album = $this->album; ?>
<?php $paginatorRecentAlbum = Engine_Api::_()->getItemTable('album_photo')->getPhotoPaginator(array('album' => $album));  ?>
<?php foreach($paginatorRecentAlbum as $photo) { ?>
	<span class="feed_attachment_<?php echo $photo->getType(); ?>">
		<div class="feed_attachment_photo <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesalbum',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($album)) { ?> paid_content <?php } ?> " style="width:<?php echo is_numeric($this->width) ? $this->width.'px' : $this->width ?>;">	

		<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesalbum',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($album)) { ?>
			
			<?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $album)); ?>
		<?php } ?>
			<a href="<?php echo $photo->getHref(); ?>">
				<span class="bg_item_photo bg_thumb_profile bg_item_photo_album_photo" style="background-image:url(<?php echo $photo->getPhotoUrl(); ?>);"></span>
			</a>
		</div>
	</span>
<?php } ?>
