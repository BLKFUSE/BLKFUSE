<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating	
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php include APPLICATION_PATH .  '/application/modules/Sesdating/views/scripts/dismiss_message.tpl';?>
<div class='tabs'>
  <ul class="navigation">
    <li>
      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesdating', 'controller' => 'manage', 'action' => 'header-settings'), $this->translate('Header Settings')) ?>
    </li>
    <li  class="active">
      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesdating', 'controller' => 'manage', 'action' => 'index'), $this->translate('Main Menu Icons')) ?>
    </li>
  </ul>
</div>
<h3><?php echo "Manage Main Menu Icons"; ?></h3>
<p><?php echo "Here, you can add icons for the Main Navigation Menu Items of your website. You can also edit and delete the icons."; ?> </p>
<br />

<table class='admin_table dating_manangemenu_table'>
  <thead>
    <tr>
      <th><?php echo $this->translate("Menu Item") ?></th>
      <th><?php echo $this->translate("Icon") ?></th>
      <th><?php echo $this->translate("Options") ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($this->paginator as $item): ?>
      <tr id="<?php echo $item->id; ?>">
        <td><?php echo $item->label ?></td>
        
        <?php $getRow = Engine_Api::_()->getDbTable('menusicons','sesbasic')->getRow($item->id); ?>
        
        <td><?php if(($getRow)):
          $photo = $this->storage->get($getRow->icon_id, '');
        ?>
          <?php $label = 'Edit';?>
          <img class="dating_manangemenu_icon" alt="" src="<?php echo $photo ? $photo->getPhotoUrl() : ""; ?>" />
									<?php else:?>
          <?php $label = 'Add';?>
              -
									<?php endif;?></td>
        <td>
          <?php echo $this->htmlLink(
                array('route' => 'default', 'module' => 'sesdating', 'controller' => 'admin-manage', 'action' => 'upload-icon', 'id' => $item->id,'type' => 'main'),
                $label,
                array('class' => 'smoothbox')) ?>
          <?php if(($getRow)):?>
          | 
          <?php echo $this->htmlLink(
            array('route' => 'default', 'module' => 'sesdating', 'controller' => 'admin-manage', 'action' => 'delete-menu-icon', 'id' => $item->id, 'file_id' => $getRow->icon_id),
            $this->translate("Delete"),
            array('class' => 'smoothbox')) ?>
          <?php endif;?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
