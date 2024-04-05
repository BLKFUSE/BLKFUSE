<?php



/**

 * SocialEngineSolutions

 *

 * @category   Application_Sescommunityads

 * @package    Sescommunityads

 * @copyright  Copyright 2018-2019 SocialEngineSolutions

 * @license    http://www.socialenginesolutions.com/license/

 * @version    $Id: package.tpl  2018-10-09 00:00:00 SocialEngineSolutions $

 * @author     SocialEngineSolutions

 */

 

 ?>


<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/scripts/core.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/styles/styles.css'); ?>

<?php 

/*$information = array('price'=>'Price','click_limit'=>'Click Limit','boos_post'=>'Boost A Posts','promote_page'=>'Promote Your Page','promote_content'=>'Promote Your Content','website_visitor'=>'Get More Website Visitor','carosel'=>'Carousel View','video'=>'Single Video','featured'=>'Featured','sponsored'=>'Sponsored','targetting'=>'Targeting','description'=>'Description');*/

$showinfo = Engine_Api::_()->sescommunityads()->allowedTypes(); 

?>

<?php $currentCurrency =  Engine_Api::_()->payment()->getCurrentCurrency(); ?>

<?php if(engine_count($this->existingleftpackages)){ ?>

	<div class="sescmads_packages_main sesbasic_clearfix sesbasic_bxs">

  	<div class="_header">

      <h2><?php echo $this->translate("Existing Package(s)")?></h2>

    </div>

    <div class="sescommunityads_packages_table_container">

      <ul class="sescmads_packages_list">

        <?php $existing = 1;?>

      <?php foreach($this->existingleftpackages as $packageleft)	{

            $package = Engine_Api::_()->getItem('sescommunityads_package',$packageleft->package_id);
            if($package) {
            if($package->rentpackage == 1) continue;
       ?>

        <?php //if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads.package.style', 0)):?>

           <?php //include APPLICATION_PATH .  '/application/modules/Sescommunityads/views/scripts/_packages.tpl';?> 

         <?php //else:?>

           <?php include APPLICATION_PATH .  '/application/modules/Sescommunityads/views/scripts/_packagesHorizontal.tpl';?>

         <?php //endif;?>

      <?php } } ?>

      </ul>

    </div>

  </div>

<?php } ?>

<?php if(engine_count($this->package)){ ?>

	<div class="sescmads_packages_main sesbasic_clearfix sesbasic_bxs">

  	<div class="_header">

      <h2><?php echo $this->translate("Choose A Package")?></h2>

      <p><?php echo $this->translate('Select a package that suits you most to start creating ads on this website.');?></p>

    </div>

    <div class="sescommunityads_packages_table_container">

      <ul class="sescommunityads_packages_table">

        <?php $existing = 0;?>

      	<?php foreach($this->package as $package)	{

       	?>

         <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads.package.style', 0) == 0):?>

           <?php include APPLICATION_PATH .  '/application/modules/Sescommunityads/views/scripts/_packages.tpl';?> 

         <?php else:?>

           <?php include APPLICATION_PATH .  '/application/modules/Sescommunityads/views/scripts/_packagesHorizontal.tpl';?>

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
