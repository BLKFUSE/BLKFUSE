<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manage-browsepage.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesmember/views/scripts/dismiss_message.tpl';?>

<h3><?php echo "Manage Browse Page for Profile Types";?></h3>

<p><?php echo "This page lists all of the widgetized pages created by you using this plugin for browsing members based on selected Profile Types. Below, you can create a new page by clicking on “Create Browse Page for Profile Type” link. You can manage below pages by using various links for them in the “Options” section below." ?></p>
<br />	
<div>
  <?php 
  $profileTypeFields = Engine_Api::_()->fields()->getFieldsObjectsByAlias('user', 'profile_type');
      if (engine_count($profileTypeFields) !== 1 || !isset($profileTypeFields['profile_type']))
        return;
      $profileTypeField = $profileTypeFields['profile_type'];
      $options = $profileTypeField->getOptions();
      foreach ($options as $option) {
        $multiOptions[$option->option_id] = $option->label;
      }
  foreach ($multiOptions as $key =>  $level) {
          $homepage_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('id', 0);

          $checkLevelId = Engine_Api::_()->getDbtable('homepages', 'sesmember')->checkLevelId($key, $homepage_id, 'browse');
          if ($checkLevelId)
            continue;
          $levelOptions[$key] = $level;
          $levelValues[] = $key;
        }
    if($levelOptions){ 
        echo $this->htmlLink(array('action' => 'create-browse', 'reset' => false), $this->translate("Create New Browse Member Page"),array('class' => 'buttonlink sesbasic_icon_add'));
     } else { ?>
      <div class="tip">
    <span>
      <?php echo $this->translate("There are no profile type remaining to create browse member pages.(NOTE: If you want to create a new one then you have to delete older one)") ?>
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
                  array('route' => 'default', 'module' => 'sesmember', 'controller' => 'admin-manage', 'action' => 'edit-browse', 'id' => $item->homepage_id),
                  $this->translate("edit")) ?>
            |
            <?php echo $this->htmlLink(
                  array('route' => 'default', 'module' => 'sesmember', 'controller' => 'admin-manage', 'action' => 'delete-browse', 'id' => $item->homepage_id),
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
      <?php echo $this->translate("There are no browse member pages created using this plugin.") ?>
    </span>
  </div>
<?php endif; ?>
