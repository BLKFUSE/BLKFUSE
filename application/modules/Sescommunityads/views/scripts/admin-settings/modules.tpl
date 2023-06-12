<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: modules.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php include APPLICATION_PATH .  '/application/modules/Sescommunityads/views/scripts/dismiss_message.tpl';?>

<h3>Manage Modules</h3>
<p>You can add modules(plugin) from this section for 'Promote your Content Type' Ad which you want to create on your website. By adding the plugin, you can select any of the below added modules at the time of Ad creation.Simply click on "Add New Plugin" for adding new plugin for the Advertisements.</p>
<br />

<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescommunityads', 'controller' => 'settings', 'action' => 'addmodule'), $this->translate("Add New Plugin"), array('class'=>'buttonlink sesbasic_icon_add'));
?>
<br /><br />

<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
<form id='multidelete_form'>
  <table class='admin_table' width="100%">
    <thead>
      <tr>
        
        <th align="left">
          <?php echo $this->translate("Plugin Title"); ?>
        </th>
        
        <th align="left">
          <?php echo $this->translate("Plugin Name"); ?>
        </th>
        <th align="left">
          <?php echo $this->translate("Item Type"); ?>
        </th>
        <th class="admin_table_centered">
          <?php echo $this->translate("Enabled"); ?>
        </th>
        <th align="left">
          <?php echo $this->translate("Options"); ?>
        </th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($this->paginator as $item): ?>
      <?php 
      $module_name = $item->module_name; 
      $enabledModules = $this->enabledModules;
      ?>
      <?php if(engine_in_array($module_name, $enabledModules )): ?>
      <tr>
        <td><?php if( !empty($item->title) ){ echo $item->title; }else { echo '-'; } ?></td>
        <td><?php if( !empty($item->module_name) ){ echo $item->module_name; }else { echo '-'; } ?></td>
        <td><?php if( !empty($item->content_type) ){ echo $item->content_type; }else { echo '-'; } ?></td>
        <td class="admin_table_centered"><?php echo ( $item->enabled ? $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescommunityads', 'controller' => 'settings', 'action' => 'enabled', 'module_id' => $item->getIdentity() ), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title' => $this->translate('Disable'))), array())  : $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescommunityads', 'controller' => 'settings', 'action' => 'enabled', 'module_id' => $item->getIdentity() ), $this->htmlImage('application/modules/Sesbasic/externals/images/icons/error.png', '', array('title' => $this->translate('Enable')))) ) ?></td >
        <td>
        <!--<a href="<?php echo $this->url(array('action' => 'addmodule', 'module_id' => $item->getIdentity())) ?>" class=""><?php echo $this->translate("Edit") ?>
          </a>-->
          <?php if(empty($item->default)):?>
          
          <a href="<?php echo $this->url(array('action' => 'delete', 'module_id' => $item->getIdentity())) ?>" class="smoothbox"><?php echo $this->translate("Delete") ?>
          </a>
          <?php endif; ?>
        </td>
      </tr>
      <?php endif; ?>
      <?php  endforeach; ?>
    </tbody>
  </table>
</form>
<br />
<div>
  <?php echo $this->paginationControl($this->paginator); ?>
</div>
<?php else: ?>
<div class="tip">
  <span>
    <?php echo $this->translate("No plugins are integrated yet.") ?>
  </span>
</div>
<?php endif; ?>


     
