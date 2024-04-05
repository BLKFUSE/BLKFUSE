<?php ?>
<?php include APPLICATION_PATH .  '/application/modules/Sesalbum/views/scripts/dismiss_message.tpl';?>
<h2 class="page_heading">
  <?php echo $this->translate("Advanced Photos & Albums Plugin") ?>
</h2>
<div class="sesbasic_nav_btns">
  <a href="<?php echo $this->url(array('module' => 'sesalbum', 'controller' => 'settings', 'action' => 'help'),'admin_default',true); ?>" class="request-btn">Help</a>
</div>
<?php if(is_countable($this->navigation) && engine_count($this->navigation) ): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>

<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesalbum', 'controller' => 'integrateothersmodules', 'action' => 'index'), $this->translate("Back to Integrate and Manage Other Plugins"), array('class'=>'sesbasic_icon_back buttonlink')) ?>
<br style="clear:both;" /><br />
<div class='clear'>
  <div class='settings sesbasic_admin_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<script type="text/javascript">
 function changemodule(modulename) {
   var type = '<?php echo $this->type ?>';
   window.location.href="<?php echo $this->url(array('module'=>'sesalbum','controller'=>'integrateothersmodules', 'action'=>'addmodule'),'admin_default',true)?>/module_name/"+modulename;
 }
</script>
<style type="text/css">
.sesbasic_back_icon{
  background-image: url(<?php echo $this->layout()->staticBaseUrl ?>application/modules/Core/externals/images/back.png);
}
</style>
