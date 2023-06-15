<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: media.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/dismiss_message.tpl';?>
<h3><?php echo $this->translate("Manage Media Types") ?></h3>
<p><?php echo $this->translate('This page shows all the 4 media types supported for the contests on your website. You can enable / disable any media type and users on your website can choose to create contests in the enabled media types only.<br />You can also add Banner images to media types which will appear in the "Media Type Banner" widget placed on the Media Type View page of this plugin.<br /><br /><div class="tip"><span>Note: Please keep at least one media type enabled, as if you disable all the media types then contests will get created without any media type and the participated entries will be blank without any content.</span></div>
'); ?></p>
<br />
<div class="sescontest_manage_media">
  <ul class='admin_table'>
    <?php foreach ($this->mediaTypes as $mediaType): ?>
      <li>
      	<div class="sescontest_manage_media_item">
          <div class="sescontest_manage_media_title"><?php echo $mediaType->title; ?></div>
          <div class="sescontest_manage_media_options">
            <?php if($mediaType->enabled == 1):?>
              <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'enabled', 'id' => $mediaType->media_id), $this->translate('Disable')) ?>
            <?php else: ?>
              <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'enabled', 'id' => $mediaType->media_id), $this->translate('Enable')) ?>
            <?php endif; ?>
            &nbsp;|&nbsp;
            <?php if($mediaType->banner):?>
              <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'upload-banner', 'id' => $mediaType->media_id), $this->translate("Change Banner"), array('class' => 'smoothbox')) ?>
            <?php else:?>
              <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'upload-banner', 'id' => $mediaType->media_id), $this->translate("Add Banner"), array('class' => 'smoothbox')) ?>
            <?php endif;?>
          </div>
          <div class="sescontest_manage_media_options_img">
            <?php if($mediaType->banner):?>
              <?php $banner = Engine_Api::_()->storage()->get($mediaType->banner);
              if($banner) {
              ?>
              <img class="banner_img" src="<?php echo $banner->getPhotoUrl('thumb.normal'); ?>" alt="" />
            <?php } endif;?>
          </div>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
