<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manage-page.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesmember/views/scripts/dismiss_message.tpl';?>

<h3><?php echo "Manage Member Home Pages";?></h3>

<p><?php echo "This page lists all of the Widgetized Pages created by you using this plugin. Below, you can create a new page by clicking on “Create New Member Home Page” link. You can manage below pages by using various links for them in the “Options” section below." ?></p>
<br />	
<div>
  <?php 
   foreach (Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level) {
        $checkLevelId = Engine_Api::_()->getDbtable('homepages', 'sesmember')->checkLevelId($level->level_id, '0', 'home');
        if ($checkLevelId || ($level->level_id == '5'))
          continue;
        $levelValues[] = $level->level_id;
      } ?>
      <?php if($levelValues){ 
         echo $this->htmlLink(array('action' => 'create', 'reset' => false), $this->translate("Create New Member Home Page"),array('class' => 'buttonlink sesbasic_icon_add'));
       }else{ ?>
        <div class="tip">
    <span>
      <?php echo $this->translate("There are no member level remaining to create homepage.
(NOTE: If you want to create a new one then you have to delete older one)") ?>
    </span>
  </div>
      <?php } ?>
</div>
<br />

<?php if( engine_count($this->pages) ): ?>
<form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
  <table class='admin_table' style="width:100%;">
    <thead>
      <tr>
        <th>ID</th>
        <th><?php echo $this->translate("Title") ?></th>
        <th><?php echo $this->translate("Options") ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($this->pages as $item): ?>
        <?php $pageId = Engine_Api::_()->sesmember()->getWidgetizePageId($item->homepage_id);?>
        <tr>
          <td><?php echo $item->homepage_id ?></td>
          <td><?php echo $item->title ?></td>
          <td>
            <?php echo $this->htmlLink(
                  array('route' => 'default', 'module' => 'sesmember', 'controller' => 'admin-manage', 'action' => 'edit', 'id' => $item->homepage_id),
                  $this->translate("edit")) ?>
            |
            <?php echo $this->htmlLink(
                  array('route' => 'default', 'module' => 'sesmember', 'controller' => 'admin-manage', 'action' => 'delete', 'id' => $item->homepage_id),
                  $this->translate("delete"),
                  array('class' => 'smoothbox')) ?>
            <?php if($pageId):?>
	      |
	      <?php $url = $this->url(array('module' => 'core', 'controller' => 'content', 'action' => 'index'), 'admin_default').'?page='.$pageId;?>
	      <a href="<?php echo $url;?>"  target="_blank"><?php echo "Go To Widgetize Page";?></a>
            <?php endif;?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</form>
<?php else: ?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are no member home pages created using this plugin.") ?>
    </span>
  </div>
<?php endif; ?>
