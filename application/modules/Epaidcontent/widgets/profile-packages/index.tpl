<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Epaidcontent/externals/styles/style.css'); ?>

<?php if(engine_count($this->enablePackage)){ ?>


  <div class="epaidcontent_packages_main sesbasic_clearfix sesbasic_bxs">
  	<div class="epaidcontent_packages_main_header">
      <h2><?php echo $this->translate("Choose A Package")?></h2>
      <p><?php echo $this->translate('Select a package that suits you most to start creating pages on this website.');?></p>
    </div>
    <div class="epaidcontent_packages_table_container">
      <ul class="epaidcontent_packages_table">
        <?php $existing = 0;?>
      	<?php foreach($this->enablePackage as $package)	{ ?>
          <li class="epaidcontent_packages_table_item">
            <section>
                <div class="title_price sesbasic_clearfix">
                <h5><?php echo $this->translate($package->title); ?></h5>
                <?php if(!$package->isFree()){ ?>
                  <span><?php echo Engine_Api::_()->epaidcontent()->getCurrencyPrice($package->price,'','',true); ?></span>
                <?php }else{ ?>
                  <span><?php echo $this->translate("FREE"); ?></span>
                <?php } ?>
              </div>
              <div class="_cont">
                <ul class="package_capabilities">
                  <li class="sesbasic_clearfix">
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
                  </li>
                  <li class="sesbasic_clearfix">
                    <span class="_label"><?php echo $this->translate('Billing Cycle');?></span>
                    <span class="_value">
                      <?php if($package->recurrence_type == 'day'):?>
                        <?php echo $this->translate('Daily');?>
                      <?php elseif($package->price && $package->recurrence_type != 'forever'):?>
                        <?php echo $this->translate(ucfirst($package->recurrence_type).'ly');?>
                      <?php elseif($package->recurrence_type == 'forever'): ?>
                        <?php echo sprintf($this->translate('One-time fee of %1$s'), Engine_Api::_()->epaidcontent()->getCurrencyPrice($package->price,'','',true)); ?>
                      <?php else:?>
                        <?php echo $this->translate('Free');?>
                      <?php endif;?>
                    </span>
                  </li>
                </ul>
                <p class="package_des"><?php echo $this->translate($package->description); ?> </p>
              </div>
              <div class="select_packeges_button">
                <a class="epaidcontent_packages_create_btn sesbasic_animation" href="<?php echo $this->url(array('action' => 'makepayment', 'package_id' => $package->package_id),'epaidcontent_general',true); ?>"><?php echo $this->translate('Select Package');?></a>
              </div>
            </section>
          </li>
      	<?php } ?>
      </ul>
		</div>
  </div>

<?php } ?>
