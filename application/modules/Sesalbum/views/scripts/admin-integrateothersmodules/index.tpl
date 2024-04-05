<?php ?>
<?php include APPLICATION_PATH .  '/application/modules/Sesalbum/views/scripts/dismiss_message.tpl';?>
<h2 class="page_heading">
  <?php echo $this->translate("Advanced Photos & Albums Plugin") ?>
</h2>
<div class="sesbasic_nav_btns">
  <a href="<?php echo $this->url(array('module' => 'sesalbum', 'controller' => 'settings', 'action' => 'help'),'admin_default',true); ?>" class="request-btn">Help</a>
</div>
<?php if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesbasic'))
  {
    include APPLICATION_PATH .  '/application/modules/Sesbasic/views/scripts/_mapKeyTip.tpl'; 
  } else { ?>
     <div class="tip"><span><?php echo $this->translate("This plugin requires \"<a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>SocialNetworking.Solutions (SNS) Basic Required Plugin </a>\" to be installed and enabled on your website for Location and various other featrures to work. Please get the plugin from <a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>here</a> to install and enable on your site."); ?></span></div>
  <?php } ?>
<?php if(is_countable($this->navigation) && engine_count($this->navigation) ): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>
<h3 style="margin-bottom:6px;"><?php echo $this->translate("Integrate and Manage Other Plugins"); ?></h3>
<p>In this page, you can enable the creation of Albums in other plugins. Below you can integrate other plugins with this plugin using “Add New Plugin” button.<br />
This process is very easy, but still if you face any difficulty, then please contact our support team for Free integration of other plugins from here: <a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">https://socialnetworking.solutions/support/create-new-ticket/</a> .</p>
<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesalbum', 'controller' => 'integrateothersmodules', 'action' => 'addmodule'), $this->translate("Add New Plugin"), array('class'=>'buttonlink sesbasic_icon_add sesbasic_btn_space'));
?>
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
<form id='multidelete_form'>
  <table class='admin_table'>
    <thead>
      <tr>
        <th align="left">
          <?php echo $this->translate("Plugin Name"); ?>
        </th>
        <th align="left">
          <?php echo $this->translate("Item Type"); ?>
        </th>
        <th align="left">
          <?php echo $this->translate("Item Type URL"); ?>
        </th>
        <th class="left">
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
        <td><?php if( !empty($item->module_name) ){ echo $item->module_name; }else { echo '-'; } ?></td>
        <td><?php if( !empty($item->content_type) ){ echo $item->content_type; }else { echo '-'; } ?></td>
        <td><?php if( !empty($item->content_url) ){ echo $item->content_url; }else { echo '-'; } ?></td>
        <td><?php echo ( $item->enabled ? $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesalbum', 'controller' => 'integrateothersmodules', 'action' => 'enabled', 'integrateothersmodule_id' => $item->integrateothersmodule_id ), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title' => $this->translate('Disable'))), array())  : $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesalbum', 'controller' => 'integrateothersmodules', 'action' => 'enabled', 'integrateothersmodule_id' => $item->integrateothersmodule_id ), $this->htmlImage('application/modules/Sesbasic/externals/images/icons/error.png', '', array('title' => $this->translate('Enable')))) ) ?></td >
        <td>
          <?php if(empty($item->default)):?>
          <a href="<?php echo $this->url(array('action' => 'delete', 'integrateothersmodule_id' => $item->integrateothersmodule_id)) ?>" class="smoothbox"><?php echo $this->translate("Delete") ?>
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
