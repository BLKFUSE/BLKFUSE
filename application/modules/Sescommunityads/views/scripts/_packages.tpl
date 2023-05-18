<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _packages.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?> 

 <li class="sescmads_packages_table_item <?php echo !empty($package->highlight) ? 'active' : '' ?>">
  <section>
      <div class="_title sesbasic_clearfix">
      <h5><?php echo $this->translate($package->title); ?></h5>
      <?php if(engine_in_array('price',$showinfo)){ ?>
      <div class="_price">
        <?php if(!$package->isFree()){ ?>        
          <span><?php echo Engine_Api::_()->sescommunityads()->getCurrencyPrice($package->price,'','',true); ?></span>
          <small>
            <?php if($package->package_type == "nonRecurring"){
                      if($package->click_type == "perday"){
                        echo "for ".($package->click_limit ? $package->click_limit : "Unlimited")." Days";
                      }else if($package->click_type == "perclick"){
                        echo "for ".($package->click_limit ? $package->click_limit : "Unlimited")." Clicks";
                      }else if($package->click_type == "perview"){
                       echo "for ".($package->click_limit ? $package->click_limit : "Unlimited")." Views";
                      }
                   }else{
            ?>
            <?php if($package->recurrence_type == 'day'):?>
              <?php echo $this->translate('Daily');?>
            <?php elseif($package->price && $package->recurrence_type != 'forever'):?>
              <?php echo $this->translate(ucfirst($package->recurrence_type).'ly');?>
            <?php elseif($package->recurrence_type == 'forever'): ?>
              <?php echo sprintf($this->translate('One-time fee of %1$s'), Engine_Api::_()->sescommunityads()->getCurrencyPrice($package->price,'','',true)); ?>
            <?php else:?>
              <?php echo $this->translate('Free');?>
            <?php endif;?>
            <?php } ?>
          </small>
        <?php }else{ ?>
          <span><?php echo $this->translate("FREE"); ?></span>
        <?php } ?>
      </div>
      <?php } ?>
    </div>
    <div class="_cont">
      <ul class="package_capabilities">
        <?php if(engine_in_array('payment_type',$showinfo)){ ?>
       <li class="sesbasic_clearfix">
        	<div>
            <span class="_label"><?php echo $this->translate('Payment Type'); ?></span>
            <span class="_value">
              <?php if($package->package_type == "nonRecurring"){
                    echo $this->translate('One Time');        
                   }else{
                    echo $this->translate("Recurring");        
              } ?>
            </span>
          </div>
        </li>
      <?php } ?>
      
        <?php if(engine_in_array('payment_duration',$showinfo)){ ?>
        <li class="sesbasic_clearfix">
        	<div>
            <span class="_label"><?php echo $this->translate('Billing Duration');?></span>
            <span class="_value">
              <?php if($package->package_type == "nonRecurring"){
                      if($package->click_type == "perday"){
                        echo ($package->click_limit ? $package->click_limit : "Unlimited")." Days";
                      }else if($package->click_type == "perclick"){
                        echo ($package->click_limit ? $package->click_limit : "Unlimited")." Clicks";
                      }else if($package->click_type == "perview"){
                       echo ($package->click_limit ? $package->click_limit : "Unlimited")." Views";
                      }
                   }else{
            ?>
              <?php if($package->duration_type == 'forever'):?>
                <?php echo $this->translate('Forever');?>
              <?php else:?>
                <?php if($package->duration > 1):?>
                  <?php echo $package->duration . ' ' . ucfirst($package->duration_type).'s';?>
                <?php else:?>
                  <?php echo $package->duration . ' ' . ucfirst($package->duration_type);?>
                <?php endif;?>
              <?php endif;?>
              <?php } ?>
            </span>
          </div>
        </li>
        <?php } ?>
        
        
        
       <?php if(engine_in_array('ad_count',$showinfo)){ ?>
        <li class="sesbasic_clearfix">
        	<div>
          <span class="_label"><?php echo $this->translate('Ads Count');?></span>
            <?php if($existing):?>
              <span class="_value"><?php echo (!$package->item_count) ? $this->translate("Unlimited") : $package->item_count.' ( '.$packageleft->item_count.' Left )' ?></span>
            <?php else:?>
              <span class="_value"><?php echo !$package->item_count ? $this->translate("Unlimited") : $package->item_count; ; ?></span>
            <?php endif;?>
          </div>
        </li>
        <?php } ?>
        
        
        
        <?php if(engine_in_array('auto_approve',$showinfo)){ ?>	
        <li class="sesbasic_clearfix">
        	<div>
            <span class="_label"><?php echo $this->translate('Auto Approved Ads');?></span>
            <span class="_value"><i class="_icon _<?php echo ($package->auto_approve) ? 'yes' : 'no';?>"></i></span>
        	</div>
        </li>
        <?php } ?>
        <?php if(is_array($showinfo) && engine_in_array('featured',$showinfo)){ ?>	
          <li class="sesbasic_clearfix <?php echo ($package->featured) ? 'yes' : 'no'; ?>">
          	<div>
              <span class="_label"><?php echo $this->translate("Featured"); ?></span>
              <span class="_value"><i class="_icon <?php echo ($package->featured) ? '_yes' : '_no'; ?>"></i></span>
          	</div>
          </li>
        <?php } ?>
        <?php if(is_array($showinfo) && engine_in_array('sponsored',$showinfo)){ ?>  
          <li class="sesbasic_clearfix <?php echo ($package->sponsored) ? 'yes' : 'no'; ?>">
          	<div>
              <span class="_label"><?php echo $this->translate("Sponsored"); ?></span>
              <span class="_value"><i class="_icon <?php echo ($package->sponsored) ? '_yes' : '_no'; ?>"></i></span>
          	</div>
          </li>
        <?php } ?>
       <?php if(engine_in_array('targetting',$showinfo)){ ?>  
          <li class="sesbasic_clearfix">
          	<div>
              <span class="_label"><?php echo $this->translate("Targeting"); ?></span>
              <span class="_value"><i class="_icon <?php echo ($package->targetting) ? '_yes' : '_no'; ?>"></i></span>
          	</div>
          </li>
        <?php } ?>
        
        <li class="sesbasic_clearfix">
        	<div>
            <span class="_label"><?php echo $this->translate('Ads Format');?></span>
            <span class="_value">
              <?php echo $this->translate("SESCOMMImage"); ?>
              <?php if(engine_in_array('carosel',$showinfo)){ ?>
                ,<?php echo $this->translate("Carousel"); ?>
              <?php } ?>
              <?php if(engine_in_array('video',$showinfo)){ ?>
              ,<?php echo $this->translate("SESCOMMVideo"); ?>
              <?php } ?>
							<?php if(engine_in_array('banner',$showinfo)){ ?>
              ,<?php echo $this->translate("Banner"); ?>
              <?php } ?>
            </i></span>
        	</div>
        </li>
       <?php if(engine_in_array('advertise',$showinfo) && $package->modules){ ?>
      <li class="sesbasic_clearfix">
        	<div>
            <span class="_label"><?php echo $this->translate('You can advertise');?></span>
            <span class="_value">
              <?php 
                   $arrayModules = array();
                  $modules = json_decode($package->modules,true); 
                  foreach($modules as $module){
                  $moduleCan = Engine_Api::_()->getDbTable('modules','sescommunityads')->getEnabledModuleNames(array('content_type'=>$module,'enabled'=>1,'fetchRow'=>1));
                    if($moduleCan && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled($moduleCan->module_name)){
                      
                      if($moduleCan)
                      $arrayModules[] = $moduleCan['title'];
                    }
                  }
                  echo implode(',',$arrayModules);
              ?>
            </span>
        	</div>
        </li>
      <?php } ?>
      
      
      <li class="sesbasic_clearfix">
        	<div>
            <span class="_label"><?php echo $this->translate('Ads Type');?></span>
            <span class="_value">
            <?php $type = array(); ?>
              <?php if(engine_in_array('boos_post',$showinfo)){ ?>
                <?php $type[] =  $this->translate("Boost A Posts"); ?>
              <?php } ?>
              <?php if(engine_in_array('promote_page',$showinfo) && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sespage')){ ?>
              <?php $type[] = $this->translate("Promote Your Page"); ?>
              <?php } ?>
              <?php if(engine_in_array('promote_content',$showinfo)){ ?>
              <?php $type[] =  $this->translate("Promote Your Content"); ?>
              <?php } ?>
              <?php if(engine_in_array('website_visitor',$showinfo)){ ?>
                <?php $type[] = $this->translate("Get More Website Visitor"); ?>
              <?php } ?>
              <?php echo implode(',',$type); ?>
            </i></span>
        	</div>
        </li>    
      </ul>
      <?php if(is_array($showinfo) && engine_in_array('description',$showinfo)){ ?> 
        <p class="package_des"><?php echo $this->translate($package->description); ?> </p>
      <?php } ?>
    </div>
    <div class="_btn">
      <?php if($existing):?>
        <a class="sescmads_packages_create_btn sesbasic_animation" href="<?php echo $this->url(array('action' => 'create', 'existing_package_id' => $packageleft->getIdentity(),'action_id'=>$this->action_id),'sescommunityads_general',true); ?>"><?php echo $this->translate('Create an Ad');?></a>
      <?php else:?>
        <a class="sescmads_packages_create_btn sesbasic_animation" href="<?php echo $this->url(array('action' => 'create', 'package_id' => $package->package_id,'action_id'=>$this->action_id),'sescommunityads_general',true); ?>"><?php echo $this->translate('Create an Ad');?></a>
      <?php endif;?>
    </div>
  </section>
 </li>   
