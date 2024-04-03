<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontestpackage/externals/styles/styles.css'); ?>
<?php 
$information = array('description' => 'Package Description', 'featured' => 'Featured', 'sponsored' => 'Sponsored', 'verified' => 'Verified', 'hot' => 'Hot', 'custom_fields' => 'Custom Fields');
$showinfo = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.package.info', array_keys($information)); ?>
<?php $currentCurrency =  Engine_Api::_()->sescontestpackage()->getCurrentCurrency(); ?>
<?php if(is_countable($this->existingleftpackages) && engine_count($this->existingleftpackages)): ?>
	<div class="sescontest_packages_main sesbasic_clearfix sesbasic_bxs">
  	<div class="sescontest_packages_main_header">
      <h2><?php echo $this->translate("My Package(s)")?></h2>
    </div>
    <div class="sescontest_packages_transactions_btn">
      <a href="<?php echo $this->url(array('action' => 'transactions'),'sescontest_general',true);?>" class="sescontest_packages_create_btn sesbasic_animation"><?php echo $this->translate("See All Transactions");?></a>
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
<?php else: ?>
  <div class="sesbasic_tip clearfix">
    <img src="application/modules/Sescontestpackage/externals/images/package-icon.png" alt="">
    <span class="sesbasic_text_light"><?php echo $this->translate("You have not subscribed to any package yet!")?></span>
  </div>
<?php endif;?>
<script type="application/javascript">
var elem = scriptJquery('.package_catogery_listing');
for(i=0;i<elem.length;i++){
	var widthTotal = scriptJquery(elem[i]).children().length * 265;
	scriptJquery(elem[i]).css('width',widthTotal+'px');
}
</script>
