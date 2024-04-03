<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manage-widgetize-page.tpl  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php include APPLICATION_PATH .  '/application/modules/Sestutorial/views/scripts/dismiss_message.tpl';?>
<h3><?php echo $this->translate("Links to Widgetized Pages") ?></h3>
<p>
	<?php echo $this->translate('This page lists all the Widgetized Pages of this plugin. From here, you can easily go to particular widgetized page in "Layout Editor" by clicking on "Widgetized Page" link. The user side link of the page can be viewed by clicking on "User Page" link'); ?>
</p>
<br />
<table class='admin_table'>
  <thead>
    <tr>
      <th><?php echo $this->translate("Page Name") ?></th>
      <th><?php echo $this->translate("Options") ?></th>
      <th><?php echo $this->translate("Demo Links") ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($this->pagesArray as $item):
    $corePages = Engine_Api::_()->sestutorial()->getwidgetizePage(array('name' => $item));
    $page = explode("_",$corePages->name);
    ?>
    <tr>
      <td><?php echo $corePages->displayname ?></td>
      <td>
        <?php $url = $this->url(array('module' => 'core', 'controller' => 'content', 'action' => 'index'), 'admin_default').'?page='.$corePages->page_id;?>
        <a href="<?php echo $url;?>"  target="_blank"><?php echo "Widgetized Page";?></a>
        <?php if($item != 'sestutorial_index_view') { ?>
          |
          <?php $viewPageUrl = $this->url(array('module' => $page[0], 'controller' => $page[1], 'action' => $page[2]), 'default');?>
          <?php if($item == 'sestutorial_index_home'): ?>
            <a href="<?php echo $this->url(array('action' => 'home'), 'sestutorial_general'); ?>" target="_blank"><?php echo $this->translate("User Page") ?></a>
          <?php elseif($item == 'sestutorial_index_browse'): ?>
            <a href="<?php echo $this->url(array('action' => 'browse'), 'sestutorial_general'); ?>" target="_blank"><?php echo $this->translate("User Page") ?></a>
          <?php elseif($item == 'sestutorial_index_tags'): ?>
            <a href="<?php echo $this->url(array('action' => 'tags'), 'sestutorial_general'); ?>" target="_blank"><?php echo $this->translate("User Page") ?></a>
          <?php elseif($item == 'sestutorial_category_browse'): ?>
            <a href="<?php echo $this->url(array('action' => 'categories'), 'sestutorial_general'); ?>" target="_blank"><?php echo $this->translate("User Page") ?></a>
          <?php else: ?>
            <a href="<?php echo $viewPageUrl; ?>" target="_blank"><?php echo $this->translate("User Page") ?></a>
          <?php endif; ?>
        <?php } ?>
      </td>
      <td>
      </td>
    </tr>
    <?php $results = ''; ?>
    <?php endforeach; ?>
  </tbody>
</table>
