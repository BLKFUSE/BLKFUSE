<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manage-widgetize-page.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesmember/views/scripts/dismiss_message.tpl';?>
<?php $sespwaEnable = Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sespwa'); ?>
<h3><?php echo $this->translate("Manage Widgetize Pages") ?></h3>
<p>
	<?php echo $this->translate('This page lists all of the Widgetize Page in this plugin. From here you can easily go to particular page in "Layout Editor" by clicking on "Get Widgetize Page" and also you can view directly user side page by click on "View Page" link.'); ?>
</p>
<br />
<table class='admin_table'>
  <thead>
    <tr>
      <th><?php echo $this->translate("Page Name") ?></th>
      <th><?php echo $this->translate("Get Widgetize Page For") ?></th>
      <th><?php echo $this->translate("Demo Links For") ?></th>
      <th><?php echo $this->translate("Reset (Website)") ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($this->pagesArray as $item):
    $corePages = Engine_Api::_()->sesmember()->getwidgetizePage(array('name' => $item, 'modulename' => 'core'));
    $page = explode("_",$corePages->name);
    $executed = false;
    if($sespwaEnable) {
      $pwaPages = Engine_Api::_()->sesmember()->getwidgetizePage(array('name' => $item, 'modulename' => 'sespwa'));
      $pwapage = explode("_",$corePages->name);
    }
    ?>
    <tr>
      <td><?php echo $corePages->displayname ?></td>
      <td>
        <?php $url = $this->url(array('module' => 'core', 'controller' => 'content', 'action' => 'index'), 'admin_default').'?page='.$corePages->page_id;?>
        <a href="<?php echo $url;?>"  target="_blank"><?php echo "Website";?></a>
        <?php if($sespwaEnable) { ?>
          |
          <?php $pwaurl = $this->url(array('module' => 'sespwa', 'controller' => 'content', 'action' => 'index'), 'admin_default').'?page='.$pwaPages->page_id;?>
          <a href="<?php echo $pwaurl;?>"  target="_blank"><?php echo "PWA";?></a>
        <?php } ?>
      </td>
      <td>
        <?php if($corePages->name !=  'sesmember_review_view'): ?>
          <?php $viewPageUrl = $this->url(array('module' => $page[0], 'controller' => $page[1], 'action' => $page[2]), 'default');?>
          <?php if($sespwaEnable) { ?>
            <a href="<?php echo $viewPageUrl.'?pwa=0'; ?>" target="_blank"><?php echo $this->translate("Website") ?></a>
            |
            <?php $pwaviewPageUrl = $this->url(array('module' => $pwapage[0], 'controller' => $pwapage[1], 'action' => $pwapage[2]), 'default');?>
            <a href="<?php echo $pwaviewPageUrl.'?pwa=1'; ?>" target="_blank"><?php echo $this->translate("PWA") ?></a>
          <?php } else { ?>
            <a href="<?php echo $viewPageUrl; ?>" target="_blank"><?php echo $this->translate("Website") ?></a>
          <?php } ?>
        <?php endif; ?>
      </td>
      <td>
        <a title="<?php echo $this->translate('Reset Page'); ?>" href="<?php echo $this->url(array('module'=> 'sesmember', 'controller' => 'settings', 'action' => 'reset-page-settings', 'page_id' => $corePages->page_id, 'page_name' => $corePages->name,'format' => 'smoothbox'),'admin_default',true); ?>" class=" smoothbox"><?php echo $this->translate('Reset Page'); ?></a>
      </td>
    </tr>
    <?php $results = ''; ?>
    <?php endforeach; ?>
  </tbody>
</table>
