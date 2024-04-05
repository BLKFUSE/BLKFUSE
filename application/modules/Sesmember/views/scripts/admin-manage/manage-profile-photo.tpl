<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manage-profile-photo.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesmember/views/scripts/dismiss_message.tpl';?>

<h3><?php echo "Manage Default Profile Photo";?></h3>

<p><?php echo "This page lists default profile photos according to profile type. You can also delete and edit defalut  photo from here." ?></p>
<div>
  <?php echo $this->htmlLink(array('action' => 'create-profile-photo', 'reset' => false), $this->translate("Upload New Profile Photo"),array('class' => 'smoothbox buttonlink sesbasic_icon_add sesbasic_btn_space')) ?>
</div>
<?php if( engine_count($this->pages) ): ?>
<form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
  <table class='admin_table' style="width:100%;">
    <thead>
      <tr>
        <th>ID</th>
        <th><?php echo $this->translate("Profile Type") ?></th>
        <th><?php echo $this->translate("Thumbnail") ?></th>
        <th><?php echo $this->translate("Options") ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($this->pages as $item): ?>
        <tr>
          <td><?php echo $item->profilephoto_id ?></td>
          <?php $profiletypeValue = Engine_Api::_()->sesmember()->getProfileTypeValue(array('option_id' => $item->profiletype_id)); ?>
          <td><?php echo $profiletypeValue ?></td>
          <td>
            <?php if($item->photo_id): ?>
              <img height="100px;" width="100px;" alt="" src="<?php echo Engine_Api::_()->storage()->get($item->photo_id, '')->getPhotoUrl(); ?>" />
            <?php else: ?>
              <?php echo "---"; ?>
            <?php endif; ?>
          </td>
          <td>
            <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesmember', 'controller' => 'admin-manage', 'action' => 'edit-profile-photo', 'id' => $item->profilephoto_id),$this->translate("edit"), array('class' => 'smoothbox')) ?>
            |
            <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesmember', 'controller' => 'admin-manage', 'action' => 'delete-profile-photo', 'id' => $item->profilephoto_id), $this->translate("delete"), array('class' => 'smoothbox')) ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</form>
<?php else: ?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are no default profile photo.") ?>
    </span>
  </div>
<?php endif; ?>
