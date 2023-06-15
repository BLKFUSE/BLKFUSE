<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: upgrade.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php if(!$this->is_ajax) {
  echo $this->partial('dashboard/left-bar.tpl', 'sesnews', array('news' => $this->news));	
?>
	<div class="sesnews_dashboard_content sesbm sesbasic_clearfix">
<?php }  ?>
	<div class="sesbasic_dashboard_form">
		
<?php 
$information = array('description'=>'Package Description','featured'=>'Featured','sponsored'=>'Sponsored','verified'=>'Verified','location'=>'Location','modules'=>'Modules','editor'=>'Rich Editor','custom_fields'=>'Custom Fields','newscount'=>'News Count');
$showinfo = array('description','featured','sponsored','verified','location','modules','editor','custom_fields','newscount');//Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnewspackage.package.information', array_keys($information));
 ?>
<?php $currentCurrency =  Engine_Api::_()->sesnews()->getCurrentCurrency(); ?>
<?php if($this->currentPackage){ 
	$package = Engine_Api::_()->getItem('sesnewspackage_package',$this->currentPackage->package_id);
  if($package){
?>
<div class="sesnewspackage_news_list sesbasic_clearfix sesbasic_bxs">
	<div class="sesnewspackage_slect_plan_news">
  <div class="heading_news">
  	<p class="headding"><?php echo $this->translate("Current Package"); ?></p>
    </div>
    <div class="select">
    	<ul>
      <?php if(engine_in_array('newscount',$showinfo)){ ?>
        <li><?php echo $this->translate("News Count"); ?></li>
     	<?php }else{ ?>
      	<li><?php echo $this->translate("News Left"); ?></li>
      <?php } ?>
      <?php if(is_array($showinfo) && engine_in_array('featured',$showinfo)){ ?>
      	<li><?php echo $this->translate("Featured"); ?></li>
      <?php } ?>
      <?php if(is_array($showinfo) && engine_in_array('sponsored',$showinfo)){ ?>
        <li><?php echo $this->translate("Sponsored"); ?></li>
       <?php } ?>  
      <?php if(is_array($showinfo) && engine_in_array('verified',$showinfo)){ ?>
        <li><?php echo $this->translate("Verified"); ?></li>
        <?php } ?> 
      <?php if(engine_in_array('location',$showinfo)){ ?>
        <li><?php echo $this->translate("Location"); ?></li>
       <?php } ?>  
      <?php if(engine_in_array('editor',$showinfo)){ ?>
        <li><?php echo $this->translate("Rich Editor"); ?></li>
       <?php } ?>  
      <?php if(engine_in_array('custom_fields',$showinfo)){ ?>
        <li><?php echo $this->translate("Custom Fields"); ?></li>
        <?php } ?> 
      <?php if(engine_in_array('modules',$showinfo)){ ?>
        <li><?php echo $this->translate("Modules"); ?></li>
     <?php } ?>
      </ul>
    </div>
  </div>
  <div class="package_catogery_list">
  	<ul class="package_catogery_listing">
    <?php 
            $enableModules = json_decode($package->params,true);
            $curruncySymbol = str_replace('.','',preg_replace('/\d+/u', '', Engine_Api::_()->sesnews()->getCurrencyPrice($package->price)));
     ?>
    	<li class="<?php ($package->highlight) ? 'active' : '' ?>">
      	<div class="package-ditals_news">
        	<div class="headding_news">
          	<div class="title">
            	<p><?php echo $this->translate($package->title); ?></p>
              <small></small>
            </div>
            <div class="amount">
            	<?php if($package->price > 0){ ?>
            		<span><?php echo $curruncySymbol; ?></span><?php echo Engine_Api::_()->sesnews()->getCurrencyPrice($package->price,'','',true); ?><br />
                <small><?php echo $package->getPackageDescription(); ?></small>
              <?php }else{ ?>
              	<span><?php echo $this->translate("FREE"); ?></span><br />
              <?php } ?>
            </div>
          </div>
          <div class="select">
          	<ul>
            <?php if(engine_in_array('newscount',$showinfo)){ ?>	
            	<li><span><?php echo (!$package->item_count) ? $this->translate("Unlimited") : $package->item_count.' ( '.$this->currentPackage->item_count.' Left )' ?></span></li>
            <?php }else{ ?>
            	<li><span><?php echo $packageleft->item_count ; ?></span></li>
            <?php } ?>
            <?php if(is_array($showinfo) && engine_in_array('featured',$showinfo)){ ?>	
            	<li class="<?php echo ($enableModules['is_featured']) ? 'yes' : 'no'; ?>"><i class="fa fa-<?php echo ($enableModules['is_featured']) ? 'check' : 'times'; ?>"></i></li>
            <?php } ?>
            <?php if(is_array($showinfo) && engine_in_array('sponsored',$showinfo)){ ?>  
              <li class="<?php echo ($enableModules['is_sponsored']) ? 'yes' : 'no'; ?>"><i class="fa fa-<?php echo ($enableModules['is_sponsored']) ? 'check' : 'times'; ?>"></i></li>
            <?php } ?>
            <?php if(is_array($showinfo) && engine_in_array('verified',$showinfo)){ ?>  
              <li class="<?php echo ($enableModules['is_verified']) ? 'yes' : 'no'; ?>"><i class="fa fa-<?php echo ($enableModules['is_verified']) ? 'check' : 'times'; ?>"></i></li>
            <?php } ?>
            <?php if(engine_in_array('location',$showinfo)){ ?>  
              <li class="<?php echo ($enableModules['enable_location']) ? 'yes' : 'no'; ?>"><i class="fa fa-<?php echo ($enableModules['enable_location']) ? 'check' : 'times'; ?>"></i></li>
            <?php } ?>
            <?php if(engine_in_array('editor',$showinfo)){ ?>
             <li class="<?php echo ($enableModules['enable_tinymce']) ? 'yes' : 'no'; ?>"><i class="fa fa-<?php echo ($enableModules['enable_tinymce']) ? 'check' : 'times'; ?>"></i></li>
           <?php } ?>
           <?php if(engine_in_array('custom_fields',$showinfo)){ ?>
              <li class="<?php echo (@$enableModules['custom_fields']) ? 'yes' : 'no'; ?>"><i class="fa fa-<?php echo (@$enableModules['custom_fields']) ? 'check' : 'times'; ?>"></i></li>
          <?php } ?>
           <?php if(engine_in_array('modules',$showinfo)){ ?>
              <li class="<?php echo (engine_count($enableModules['modules'])) ? 'yes' : 'no'; ?>"><i class="fa fa-<?php echo (engine_count($enableModules['modules'])) ? 'check' : 'times'; ?>"></i>
              	<?php if(engine_count($enableModules['modules'])){ 
                		echo "<br>";
                    $counter = 1;
                		foreach($enableModules['modules'] as $module){
                    	$countmodulelimit = @$enableModules[$module.'_count'] ? $enableModules[$module.'_count'] : $this->translate('Unlimited');
                    	echo ucfirst($module).'('.$countmodulelimit.')';
                      if(engine_count($enableModules['modules']) != $counter)
                      	echo ' ,';
                       $counter++;
                    }
                 } ?>
              </li>
           <?php } ?>
            </ul>
          <?php if(engine_in_array('description',$showinfo) && $package->description){ ?> 
            <p class="footer_discprition"><?php echo $this->translate($package->description); ?> </p>
          <?php } ?>
          </div>
        </div>
      </li> 
    </ul>
  </div>
</div>
<?php 
	}
} ?>

<?php if(engine_count($this->upgradepackage)){ ?>
<div class="sesnewspackage_news_list sesbasic_clearfix sesbasic_bxs">
	<div class="sesnewspackage_slect_plan_news">
  <div class="heading_news">
  	<p class="headding"><?php echo $this->translate("New Package(s)"); ?></p>
    </div>
    <div class="select">
    	<ul>
      <?php if(engine_in_array('newscount',$showinfo)){ ?>
        <li><?php echo $this->translate("News Count"); ?></li>
     	<?php }else{ ?>
      	<li><?php echo $this->translate("News Left"); ?></li>
      <?php } ?>
      <?php if(is_array($showinfo) && engine_in_array('featured',$showinfo)){ ?>
      	<li><?php echo $this->translate("Featured"); ?></li>
      <?php } ?>
      <?php if(is_array($showinfo) && engine_in_array('sponsored',$showinfo)){ ?>
        <li><?php echo $this->translate("Sponsored"); ?></li>
       <?php } ?>  
      <?php if(is_array($showinfo) && engine_in_array('verified',$showinfo)){ ?>
        <li><?php echo $this->translate("Verified"); ?></li>
        <?php } ?> 
      <?php if(engine_in_array('location',$showinfo)){ ?>
        <li><?php echo $this->translate("Location"); ?></li>
       <?php } ?>  
      <?php if(engine_in_array('editor',$showinfo)){ ?>
        <li><?php echo $this->translate("Rich Editor"); ?></li>
       <?php } ?>  
      <?php if(engine_in_array('custom_fields',$showinfo)){ ?>
        <li><?php echo $this->translate("Custom Fields"); ?></li>
        <?php } ?> 
      <?php if(engine_in_array('modules',$showinfo)){ ?>
        <li><?php echo $this->translate("Modules"); ?></li>
     <?php } ?>
      </ul>
    </div>
  </div>
  <div class="package_catogery_list">
  	<ul class="package_catogery_listing">
    <?php foreach($this->upgradepackage as $package)	{
            $enableModules = json_decode($package->params,true);
            $curruncySymbol = str_replace('.','',preg_replace('/\d+/u', '', Engine_Api::_()->sesnews()->getCurrencyPrice($package->price)));
     ?>
    	<li class="<?php echo ($package->highlight) ? 'active' : '' ?>">
      	<div class="package-ditals_news">
        	<div class="headding_news">
          	<div class="title">
            	<p><?php echo $this->translate($package->title); ?></p>
              <small></small>
            </div>
            <div class="amount">
            	<?php if(!$package->isFree()){ ?>
            		<span><?php echo $curruncySymbol; ?></span><?php echo Engine_Api::_()->sesnews()->getCurrencyPrice($package->price,'','',true); ?><br />
                <small><?php echo $package->getPackageDescription(); ?></small>
              <?php }else{ ?>
              	<span><?php echo $this->translate("FREE"); ?></span><br />
              <?php } ?>
            </div>
          </div>
          <div class="select">
          	<ul>
            <?php if(engine_in_array('newscount',$showinfo)){ ?>	
            	<li><span><?php echo (!$package->item_count) ? $this->translate("Unlimited") : $package->item_count; ?></span></li>
            <?php }else{ ?>
            	<li><span><?php echo $packageleft->item_count ; ?></span></li>
            <?php } ?>
            <?php if(is_array($showinfo) && engine_in_array('featured',$showinfo)){ ?>	
            	<li class="<?php echo ($enableModules['is_featured']) ? 'yes' : 'no'; ?>"><i class="fa fa-<?php echo ($enableModules['is_featured']) ? 'check' : 'times'; ?>"></i></li>
            <?php } ?>
            <?php if(is_array($showinfo) && engine_in_array('sponsored',$showinfo)){ ?>  
              <li class="<?php echo ($enableModules['is_sponsored']) ? 'yes' : 'no'; ?>"><i class="fa fa-<?php echo ($enableModules['is_sponsored']) ? 'check' : 'times'; ?>"></i></li>
            <?php } ?>
            <?php if(is_array($showinfo) && engine_in_array('verified',$showinfo)){ ?>  
              <li class="<?php echo ($enableModules['is_verified']) ? 'yes' : 'no'; ?>"><i class="fa fa-<?php echo ($enableModules['is_verified']) ? 'check' : 'times'; ?>"></i></li>
            <?php } ?>
            <?php if(engine_in_array('location',$showinfo)){ ?>  
              <li class="<?php echo ($enableModules['enable_location']) ? 'yes' : 'no'; ?>"><i class="fa fa-<?php echo ($enableModules['enable_location']) ? 'check' : 'times'; ?>"></i></li>
            <?php } ?>
            <?php if(engine_in_array('editor',$showinfo)){ ?>
             <li class="<?php echo ($enableModules['enable_tinymce']) ? 'yes' : 'no'; ?>"><i class="fa fa-<?php echo ($enableModules['enable_tinymce']) ? 'check' : 'times'; ?>"></i></li>
           <?php } ?>
           <?php if(engine_in_array('custom_fields',$showinfo)){ ?>
              <li class="<?php echo (@$enableModules['custom_fields']) ? 'yes' : 'no'; ?>"><i class="fa fa-<?php echo (@$enableModules['custom_fields']) ? 'check' : 'times'; ?>"></i></li>
          <?php } ?>
           <?php if(engine_in_array('modules',$showinfo)){ ?>
              <li class="<?php echo (engine_count($enableModules['modules'])) ? 'yes' : 'no'; ?>"><i class="fa fa-<?php echo (engine_count($enableModules['modules'])) ? 'check' : 'times'; ?>"></i>
              	<?php if(engine_count($enableModules['modules'])){ 
                		echo "<br>";
                    $counter = 1;
                		foreach($enableModules['modules'] as $module){
                    	$countmodulelimit = @$enableModules[$module.'_count'] ? $enableModules[$module.'_count'] : $this->translate('Unlimited');
                    	echo ucfirst($module).'('.$countmodulelimit.')';
                      if(engine_count($enableModules['modules']) != $counter)
                      	echo ' ,';
                       $counter++;
                    }
                 } ?>
              </li>
           <?php } ?>
            </ul>
          <?php if(is_array($showinfo) && engine_in_array('description',$showinfo)){ ?> 
            <p class="footer_discprition"><?php echo $this->translate($package->description); ?> </p>
          <?php } ?>
          <a href="<?php echo $this->url(array('news_id' => $this->news->news_id,'action'=>'confirm-upgrade','package_id'=>$package->package_id), 'sesnewspackage_general', true); ?>" class="smoothbox">Upgrade Package</a>
          </div>
        </div>
      </li>      
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
	</div>
<?php if(!$this->is_ajax) { ?>
	</div>
  </div>
<?php } ?>
