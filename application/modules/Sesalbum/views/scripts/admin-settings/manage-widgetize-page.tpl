<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manage-widgetize-page.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>

<h2>
    <?php echo $this->translate('Advanced Photos & Albums Plugin'); ?>
</h2>
<div class="sesbasic_nav_btns">
    <a href="<?php echo $this->url(array('module' => 'sesalbum', 'controller' => 'settings', 'action' => 'help'),'admin_default',true); ?>" class="request-btn">Help</a>
</div>
<?php include APPLICATION_PATH .  '/application/modules/Sesalbum/views/scripts/dismiss_message.tpl';?>
<?php if (engine_count($this->navigation)): ?>
<div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render(); ?>
</div>
<?php endif; ?>
<h3><?php echo $this->translate("Manage Widgetize Pages") ?></h3>
<p>
	<?php echo $this->translate('This page lists all of the Widgetize Page in this plugin. From here you can easily go to particular page in "Layout Editor" by clicking on "Get Widgetize Page" and also you can view directly user side page by click on "View Page" link.'); ?>
</p>
<br />
<table class='admin_table'>
  <thead>
    <tr>
      <th><?php echo $this->translate("Page Name") ?></th>
      <th><?php echo $this->translate("Options") ?></th>
      <th><?php echo $this->translate("Demo Links") ?></th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($this->pagesArray as $item):
    $corePages = Engine_Api::_()->sesalbum()->getwidgetizePage(array('name' => $item));
    $page = explode("_",$corePages->name);
    $executed = false;
    ?>
    <tr>
      <td><?php echo $corePages->displayname ?></td>
      <td>
        <?php $url = $this->url(array('module' => 'core', 'controller' => 'content', 'action' => 'index'), 'admin_default').'?page='.$corePages->page_id;?>
        <a href="<?php echo $url;?>"  target="_blank"><?php echo "Get Widgetize Page";?></a>

      </td>
      <td>
        <?php if($corePages->name != 'sesalbum_album_view' && $corePages->name != 'sesalbum_photo_view' && $corePages->name != 'sesalbum_category_index' ):?>
        <?php $viewPageUrl = $this->url(array('module' => $page[0], 'controller' => $page[1], 'action' => $page[2]), 'default');?>
        <a href="<?php echo $viewPageUrl; ?>" target="_blank"><?php echo $this->translate("User Page") ?></a>
        <?php endif;?>
      </td>
      <td>
       <a title="<?php echo $this->translate('Reset Page'); ?>" href="<?php echo $this->url(array('module'=> 'sesalbum', 'controller' => 'index', 'action' => 'reset-page-settings', 'page_id' => $corePages->page_id, 'page_name' => $corePages->name,'format' => 'smoothbox'),'sesalbum_extended',true); ?>" class=" smoothbox"><?php echo $this->translate('Reset Page'); ?></a>
      </td>
      <td>
        <?php if($corePages->name == 'sesalbum_index_welcome') :?>
        <a title="<?php echo $this->translate('Set as Landing Page'); ?>" href="<?php echo $this->url(array('module'=> 'sesalbum', 'controller' => 'index', 'action' => 'set-landing-page', 'page_id' => $corePages->page_id, 'page_name' => $corePages->name,'format' => 'smoothbox'),'sesalbum_extended',true); ?>" class=" smoothbox"><?php echo $this->translate('Set as Landing Page'); ?></a>
        <?php endif;?>
      </td>
    </tr>
    <?php $results = ''; ?>
    <?php endforeach; ?>
  </tbody>
</table>

