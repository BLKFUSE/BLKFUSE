<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontestpackage/externals/styles/styles.css'); ?>
<?php 
$information = array('description' => 'Package Description', 'featured' => 'Featured', 'sponsored' => 'Sponsored', 'verified' => 'Verified', 'hot' => 'Hot', 'custom_fields' => 'Custom Fields');
$showinfo = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.package.info', array_keys($information)); ?>
<?php $currentCurrency =  Engine_Api::_()->sescontestpackage()->getCurrentCurrency(); ?>
<?php if(engine_count($this->existingleftpackages)){ ?>
	<div class="sescontest_packages_main sesbasic_clearfix sesbasic_bxs">
  	<div class="sescontest_packages_main_header">
      <h2><?php echo $this->translate("Existing Package(s)")?></h2>
    </div>
    <div class="sescontest_packages_table_container">
      <ul class="sescontest_packages_list">
        <?php $existing = 1;?>
      <?php foreach($this->existingleftpackages as $packageleft)	{
            $package = Engine_Api::_()->getItem('sescontestpackage_package',$packageleft->package_id);
            $enableModules = json_decode($package->params,true);
       ?>
        <?php include APPLICATION_PATH .  '/application/modules/Sescontestpackage/views/scripts/_packagesHorizontal.tpl';?>      
      <?php } ?>
      </ul>
    </div>
  </div>
<?php } ?>
<?php if(engine_count($this->package)){ ?>
	<div class="sescontest_packages_main sesbasic_clearfix sesbasic_bxs">
  	<div class="sescontest_packages_main_header">
      <h2><?php echo $this->translate("Choose A Package")?></h2>
      <p><?php echo $this->translate('Select a package that suits you most to start creating contests on this website.');?></p>
    </div>
    <div class="sescontest_packages_table_container">
      <ul class="sescontest_packages_table">
        <?php $existing = 0;?>
      	<?php foreach($this->package as $package)	{
              $enableModules = json_decode($package->params,true);
       	?>
         <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.package.style', 1)):?>
           <?php include APPLICATION_PATH .  '/application/modules/Sescontestpackage/views/scripts/_packages.tpl';?> 
         <?php else:?>
           <?php include APPLICATION_PATH .  '/application/modules/Sescontestpackage/views/scripts/_packagesHorizontal.tpl';?>
         <?php endif;?>
      	<?php } ?>
      </ul>
		</div>
  </div>  
<?php } ?>
  
<script type="application/javascript">
var elem = scriptJquery('.package_catogery_listing');
for(i=0;i<elem.length;i++){
	var widthTotal = scriptJquery(elem[i]).children().length * 265;
	scriptJquery(elem[i]).css('width',widthTotal+'px');
}
</script>
