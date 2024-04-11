<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: upgrade.tpl 2016-07-23 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontestpackage/externals/styles/styles.css'); ?>
<?php if(!$this->is_ajax) {
  echo $this->partial('dashboard/left-bar.tpl', 'sescontest', array('contest' => $this->contest));	
?>
	<div class="sesbasic_dashboard_content sesbm sesbasic_clearfix">
<?php }  ?>
	<div class="sesbasic_dashboard_form">
		
<?php 
$information = array('description' => 'Package Description', 'featured' => 'Featured', 'sponsored' => 'Sponsored', 'verified' => 'Verified', 'hot' => 'Hot', 'custom_fields' => 'Custom Fields');
$showinfo = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.package.info', array_keys($information)); ?>
<?php $currentCurrency =  Engine_Api::_()->payment()->getCurrentCurrency(); ?>
<div class="sescontest_packages_main sesbasic_clearfix sesbasic_bxs sescontest_packages_upgrade">
  <?php if($this->currentPackage){ 
    $package = Engine_Api::_()->getItem('sescontestpackage_package',$this->currentPackage->package_id);
    if($package){
  ?>
  	<div class="sescontest_packages_main_header">
    	<h2><?php echo $this->translate("Existing Package")?></h2>
    </div>
    <div class="sescontest_packages_table_container">
      <ul class="sescontest_packages_list">
         <?php $enableModules = json_decode($package->params,true);?>
         <li class="sescontest_packages_list_item <?php echo ($package->highlight) ? 'active' : '' ?>">
          <section>
           <div class="_top sesbasic_clearfix">
              <div class="_title"><h5><?php echo $this->translate($package->title); ?></h5></div>
              <div class="_price">
                <?php if(!$package->isFree() && $package->recurrence_type != 'forever'){ ?>
                  <span><?php echo Engine_Api::_()->payment()->getCurrencyPrice($package->price); ?></span>
                  <small>
                    <?php if($package->recurrence_type == 'day'):?>
                       <?php echo $this->translate('Daily');?>
                     <?php elseif($package->price && $package->recurrence_type != 'forever'):?>
                       <?php echo $this->translate(ucfirst($package->recurrence_type).'ly');?>
                     <?php elseif($package->recurrence_type == 'forever'): ?>
                       <?php echo sprintf($this->translate('One-time fee of %1$s'), Engine_Api::_()->payment()->getCurrencyPrice($package->price)); ?>
                     <?php else:?>
                       <?php echo $this->translate('Free');?>
                     <?php endif;?>
                  </small>
                <?php }elseif($package->recurrence_type == 'forever'){ ?>
                  <span><?php echo sprintf($this->translate('One-time fee of %1$s'), Engine_Api::_()->payment()->getCurrencyPrice($package->price)); ?></span>
                <?php }else{ ?>
                  <span><?php echo $this->translate("FREE"); ?></span>
                <?php } ?>
              </div>
            </div>
            <div class="_cont sesbasic_clearfix">
              <div class="package_capabilities _features">
                <div class="sesbasic_clearfix">
                	<div>
                    <span class="_label"><?php echo $this->translate('Billing Duration');?></span>
                    <span class="_value">
                      <?php if($package->duration_type == 'forever'):?>
                        <?php echo $this->translate('Forever');?>
                      <?php else:?>
                        <?php if($package->duration > 1):?>
                          <?php echo $package->duration . ' ' . ucfirst($package->duration_type).'s';?>
                        <?php else:?>
                          <?php echo $package->duration . ' ' . ucfirst($package->duration_type);?>
                        <?php endif;?>
                      <?php endif;?>
                    </span>
                	</div>    
                </div>	
                <div class="sesbasic_clearfix">
                	<div>
                    <span class="_label"><?php echo $this->translate('Contests Count');?></span>
                    <span class="_value"><?php echo (!$package->item_count) ? $this->translate("Unlimited") : $package->item_count.' ( '.$this->currentPackage->item_count.' Left )' ?></span>
                  </div>
                </div>
                <div class="sesbasic_clearfix">
                	<div>
                    <span class="_label"><?php echo $this->translate('Auto Approved Contests');?></span>
                    <span class="_value"><i class="_icon _<?php echo ($enableModules['contest_approve']) ? 'yes' : 'no';?>"></i></span>
                	</div>
                </div>
                <div class="sesbasic_clearfix">
                	<div>
                    <span class="_label"><?php echo $this->translate('Number of Awards');?></span>
                    <span class="_value"><?php echo $enableModules['award_count'];?></span>
                	</div>
                </div>
                <?php if(is_array($showinfo) && engine_in_array('featured',$showinfo)){ ?>	
                  <div class="sesbasic_clearfix <?php echo ($enableModules['contest_featured']) ? 'yes' : 'no'; ?>">
                  	<div>
                      <span class="_label">Featured</span>
                      <span class="_value"><i class="_icon <?php echo ($enableModules['contest_featured']) ? '_yes' : '_no'; ?>"></i></span>
                  	</div>
                  </div>
                <?php } ?>
                <?php if(is_array($showinfo) && engine_in_array('sponsored',$showinfo)){ ?>  
                  <div class="sesbasic_clearfix <?php echo ($enableModules['contest_sponsored']) ? 'yes' : 'no'; ?>">
                  	<div>
                      <span class="_label">Sponsored</span>
                      <span class="_value"><i class="_icon <?php echo ($enableModules['contest_sponsored']) ? '_yes' : '_no'; ?>"></i></span>
                  	</div>
                  </div>
                <?php } ?>
                <?php if(is_array($showinfo) && engine_in_array('verified',$showinfo)){ ?>  
                  <div class="sesbasic_clearfix <?php echo ($enableModules['contest_verified']) ? 'yes' : 'no'; ?>">
                  	<div>
                      <span class="_label">Verified</span>
                      <span class="_value"><i class="_icon <?php echo ($enableModules['contest_verified']) ? '_yes' : '_no'; ?>"></i></span>
                  	</div>
                  </div>
                <?php } ?>
                <?php if(engine_in_array('hot',$showinfo)){ ?>  
                  <div class="sesbasic_clearfix <?php echo ($enableModules['contest_hot']) ? 'yes' : 'no'; ?>">
                  	<div>
                      <span class="_label">Hot</span>
                      <span class="_value"><i class="_icon <?php echo ($enableModules['contest_hot']) ? '_yes' : '_no'; ?>"></i></span>
                  	</div>
                  </div>
                <?php } ?>
                <div class="sesbasic_clearfix">
                	<div>
                    <span class="_label"><?php echo $this->translate('Background Photo');?></span>
                    <span class="_value"><i class="_icon <?php echo ($enableModules['contest_bgphoto']) ? '_yes' : '_no'; ?>"></i></span>
                	</div>
                </div>
                <div class="sesbasic_clearfix">
                	<div>
                    <span class="_label"><?php echo $this->translate('Main Photo');?></span>
                    <span class="_value"><i class="_icon <?php echo ($enableModules['upload_mainphoto']) ? '_yes' : '_no'; ?>"></i></span>
                	</div>
                </div>
                <div class="sesbasic_clearfix">
                	<div>
                    <span class="_label"><?php echo $this->translate('Cover Photo');?></span>
                    <span class="_value"><i class="_icon <?php echo ($enableModules['upload_cover']) ? '_yes' : '_no'; ?>"></i></span>
                	</div>
                </div>
                <div class="sesbasic_clearfix">
                	<div>
                    <span class="_label"><?php echo $this->translate('Select Design Layout');?></span>
                    <span class="_value"><i class="_icon <?php echo ($enableModules['contest_choose_style']) ? '_yes' : '_no'; ?>"></i></span>
                	</div>
                </div>
              </div>
              <?php if(is_array($showinfo) && engine_in_array('description',$showinfo)){ ?> 
                <div class="_des _colm">
                  <p class="package_des"><?php echo $package->description; ?> </p>
                </div>  
              <?php } ?>
            </div>
          </section>
         </li>      
      </ul>
    </div>
  <?php 
    }
  } ?>
  
  <?php if(engine_count($this->upgradepackage)){ ?>
  	<div class="sescontest_packages_main_header">
    	<h2><?php echo $this->translate("Upgrade Your Package")?></h2>
        <p><?php echo $this->translate('Choose a higher package to create contests on this website and get benefited with advance features and functionalities.');?></p>
    </div>
    <div class="sescontest_packages_table_container">
      <ul class="sescontest_packages_list">
        <?php foreach($this->upgradepackage as $package){ ?>  
           <?php $enableModules = json_decode($package->params,true);?>
           <li class="sescontest_packages_list_item <?php echo ($package->highlight) ? 'active' : '' ?>">
            <section>
             <div class="_top sesbasic_clearfix">
                <div class="_title"><h5><?php echo $this->translate($package->title); ?></h5></div>
                <div class="_price">
                  <?php if(!$package->isFree()){ ?>
                    <span><?php echo Engine_Api::_()->payment()->getCurrencyPrice($package->price); ?></span>
                    <small>
                      <?php if($package->recurrence_type == 'day'):?>
                        <?php echo $this->translate('Daily');?>
                      <?php elseif($package->price && $package->recurrence_type != 'forever'):?>
                        <?php echo $this->translate(ucfirst($package->recurrence_type).'ly');?>
                      <?php elseif($package->recurrence_type == 'forever'): ?>
                        <?php echo sprintf($this->translate('One-time fee of %1$s'), Engine_Api::_()->payment()->getCurrencyPrice($package->price)); ?>
                      <?php else:?>
                        <?php echo $this->translate('Free');?>
                      <?php endif;?>
                    </small>
                  <?php }else{ ?>
                    <span><?php echo $this->translate("FREE"); ?></span>
                  <?php } ?>
                </div>
              </div>
              <div class="_cont sesbasic_clearfix">
                <div class="package_capabilities _colm">
                  <div class="sesbasic_clearfix">
                    <span class="_label"><?php echo $this->translate('Billing Duration');?></span>
                    <span class="_value">
                      <?php if($package->duration_type == 'forever'):?>
                        <?php echo $this->translate('Forever');?>
                      <?php else:?>
                        <?php if($package->duration > 1):?>
                          <?php echo $package->duration . ' ' . ucfirst($package->duration_type).'s';?>
                        <?php else:?>
                          <?php echo $package->duration . ' ' . ucfirst($package->duration_type);?>
                        <?php endif;?>
                      <?php endif;?>
                    </span>
                  </div>	
                  <div class="sesbasic_clearfix">
                    <span class="_label"><?php echo $this->translate('Contests Count');?></span>
                    <span class="_value"><?php echo (!$package->item_count) ? $this->translate("Unlimited") : $package->item_count; ?></span>
                  </div>
                  <div class="sesbasic_clearfix">
                    <span class="_label"><?php echo $this->translate('Auto Approved Contests');?></span>
                    <span class="_value"><i class="_icon _<?php echo ($enableModules['contest_approve']) ? 'yes' : 'no';?>"></i></span>
                  </div>
                  <div class="sesbasic_clearfix">
                    <span class="_label"><?php echo $this->translate('Number of Awards');?></span>
                    <span class="_value"><?php echo $enableModules['award_count'];?></span>
                  </div>
                </div>
                <div class="package_capabilities _colm">
                  <?php if(is_array($showinfo) && engine_in_array('featured',$showinfo)){ ?>	
                    <div class="sesbasic_clearfix <?php echo ($enableModules['contest_featured']) ? 'yes' : 'no'; ?>">
                      <span class="_label">Featured</span>
                      <span class="_value"><i class="_icon <?php echo ($enableModules['contest_featured']) ? '_yes' : '_no'; ?>"></i></span>
                    </div>
                  <?php } ?>
                  <?php if(is_array($showinfo) && engine_in_array('sponsored',$showinfo)){ ?>  
                    <div class="sesbasic_clearfix <?php echo ($enableModules['contest_sponsored']) ? 'yes' : 'no'; ?>">
                      <span class="_label">Sponsored</span>
                      <span class="_value"><i class="_icon <?php echo ($enableModules['contest_sponsored']) ? '_yes' : '_no'; ?>"></i></span>
                    </div>
                  <?php } ?>
                  <?php if(is_array($showinfo) && engine_in_array('verified',$showinfo)){ ?>  
                    <div class="sesbasic_clearfix <?php echo ($enableModules['contest_verified']) ? 'yes' : 'no'; ?>">
                      <span class="_label">Verified</span>
                      <span class="_value"><i class="_icon <?php echo ($enableModules['contest_verified']) ? '_yes' : '_no'; ?>"></i></span>
                    </div>
                  <?php } ?>
                  <?php if(engine_in_array('hot',$showinfo)){ ?>  
                    <div class="sesbasic_clearfix <?php echo ($enableModules['contest_hot']) ? 'yes' : 'no'; ?>">
                      <span class="_label">Hot</span>
                      <span class="_value"><i class="_icon <?php echo ($enableModules['contest_hot']) ? '_yes' : '_no'; ?>"></i></span>
                    </div>
                  <?php } ?>
                </div>
                <div class="package_capabilities _colm">
                  <div class="sesbasic_clearfix">
                    <span class="_label"><?php echo $this->translate('Background Photo');?></span>
                    <span class="_value"><i class="_icon <?php echo ($enableModules['contest_bgphoto']) ? '_yes' : '_no'; ?>"></i></span>
                  </div>
                  <div class="sesbasic_clearfix">
                    <span class="_label"><?php echo $this->translate('Main Photo');?></span>
                    <span class="_value"><i class="_icon <?php echo ($enableModules['upload_mainphoto']) ? '_yes' : '_no'; ?>"></i></span>
                  </div>
                  <div class="sesbasic_clearfix">
                    <span class="_label"><?php echo $this->translate('Cover Photo');?></span>
                    <span class="_value"><i class="_icon <?php echo ($enableModules['upload_cover']) ? '_yes' : '_no'; ?>"></i></span>
                  </div>
                  <div class="sesbasic_clearfix">
                    <span class="_label"><?php echo $this->translate('Select Design Layout');?></span>
                    <span class="_value"><i class="_icon <?php echo ($enableModules['contest_choose_style']) ? '_yes' : '_no'; ?>"></i></span>
                  </div>
                </div>
                <?php if(is_array($showinfo) && engine_in_array('description',$showinfo)){ ?> 
                  <div class="_des _colm">
                    <p class="package_des"><?php echo $this->translate($package->description); ?> </p>
                  </div>  
                <?php } ?>
              </div>
              <div class="_btns">
                <a href="<?php echo $this->url(array('contest_id' => $this->contest->contest_id,'action'=>'confirm-upgrade','package_id'=>$package->package_id), 'sescontestpackage_general', true); ?>" class="smoothbox sescontest_packages_create_btn sesbasic_animation"><?php echo $this->translate('Upgrade Package');?></a>
              </div>
            </section>
           </li> 
      	<?php } ?>     
      </ul>
    </div>
  <?php } ?>
</div>
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
