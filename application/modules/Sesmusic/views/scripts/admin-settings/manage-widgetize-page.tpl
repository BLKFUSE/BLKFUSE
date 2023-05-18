<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manage-widgetize-page.tpl 2015-06-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php include APPLICATION_PATH .  '/application/modules/Sesmusic/views/scripts/dismiss_message.tpl';?>
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
    </tr>
  </thead>
  <tbody>
    <?php foreach ($this->pagesArray as $item):
    if(!$item) continue;
    $corePages = Engine_Api::_()->sesbasic()->getwidgetizePage(array('name' => $item));
    $page = explode("_",$corePages->name);
    $executed = false;
    ?>
    <tr>
		
		 <?php $results = ''; ?>
      <td><?php echo $corePages->displayname ?></td>
      <td>
        <?php $url = $this->url(array('module' => 'core', 'controller' => 'content', 'action' => 'index'), 'admin_default').'?page='.$corePages->page_id;?>
        <a href="<?php echo $url;?>"  target="_blank"><?php echo "Get Widgetize Page";?></a>
        |
				<?php if($corePages->name !=  'sesmusic_album_edit' && $corePages->name !='sesmusic_album_view' && $corePages->name != 'sesmusic_song_view' && $corePages->name != 'sesmusic_playlist_view' && $corePages->name != 'sesmusic_artist_view' && $corePages->name != 'sesmusic_album_edit' && $corePages->name != 'sesmusic_index_create' && $corePages->name != 'sesmusic_album_view' && $corePages->name != 'sesmusic_song_view' && $corePages->name != 'sesmusic_playlist_view' && $corePages->name != 'sesmusic_artist_view'): ?>
					<?php if($results): ?>
					<a href="<?php echo $results->getHref(); ?>" target="_blank"><?php echo $this->translate("View Page") ?></a>
					<?php elseif($executed):?>
						<?php echo $this->translate("---") ?>
					<?php else: ?>
					<?php $viewPageUrl = $this->url(array('module' => $page[0], 'controller' => $page[1], 'action' => $page[2]), 'default');?>
					<a href="<?php echo $viewPageUrl; ?>" target="_blank"><?php echo $this->translate("View Page") ?></a>
					<?php endif; ?>
				<?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
