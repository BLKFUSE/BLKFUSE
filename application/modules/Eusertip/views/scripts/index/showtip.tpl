<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: showtip.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eusertip/externals/styles/style.css'); ?>

<?php if(engine_count($this->enableTip)){ ?>

  <div class="generic_layout_container layout_main">
  <div class="generic_layout_container layout_middle">
  <div class="generic_layout_container layout_core_content">
     
  <div class="eusertip_tips_main sesbasic_clearfix sesbasic_bxs">
  	<div class="eusertip_tips_main_header">
      <h2><?php echo $this->translate("Choose A Tip")?></h2>
      <p><?php echo $this->translate('Select a tip that suits you most to start creating pages on this website.');?></p>
    </div>
    <div class="eusertip_tips_table_container">
      <ul class="eusertip_tips_table">
        <?php $existing = 0;?>
      	<?php foreach($this->enableTip as $tip)	{
              //$enableModules = json_decode($tip->params,true);
       	?>
          <li class="eusertip_tips_table_item">
            <section>
                <div class="title_price sesbasic_clearfix">
                <h5><?php echo $this->translate($tip->title); ?></h5>
                <?php if(!$tip->isFree()){ ?>
                  <span><?php echo Engine_Api::_()->eusertip()->getCurrencyPrice($tip->price,'','',true); ?></span>
                <?php }else{ ?>
                  <span><?php echo $this->translate("FREE"); ?></span>
                <?php } ?>
              </div>
              <div class="_cont">
                <ul class="tip_capabilities">
                  <li class="sesbasic_clearfix">
                    <span class="_label"><?php echo $this->translate('Billing Duration');?></span>
                    <span class="_value">
                      <?php if($tip->duration_type == 'forever'):?>
                        <?php echo $this->translate('Forever');?>
                      <?php else:?>
                        <?php if($tip->duration > 1):?>
                          <?php echo $tip->duration . ' ' . ucfirst($tip->duration_type).'s';?>
                        <?php else:?>
                          <?php echo $tip->duration . ' ' . ucfirst($tip->duration_type);?>
                        <?php endif;?>
                      <?php endif;?>
                    </span>
                  </li>
                  <li class="sesbasic_clearfix">
                    <span class="_label"><?php echo $this->translate('Billing Cycle');?></span>
                    <span class="_value">
                      <?php if($tip->recurrence_type == 'day'):?>
                        <?php echo $this->translate('Daily');?>
                      <?php elseif($tip->price && $tip->recurrence_type != 'forever'):?>
                        <?php echo $this->translate(ucfirst($tip->recurrence_type).'ly');?>
                      <?php elseif($tip->recurrence_type == 'forever'): ?>
                        <?php echo sprintf($this->translate('One-time fee of %1$s'), Engine_Api::_()->eusertip()->getCurrencyPrice($tip->price,'','',true)); ?>
                      <?php else:?>
                        <?php echo $this->translate('Free');?>
                      <?php endif;?>
                    </span>
                  </li>
                </ul>
                <p class="tip_des"><?php echo $this->translate($tip->description); ?> </p>
              </div>
              <div class="select_packeges_button">
                <a class="eusertip_tips_create_btn sesbasic_animation" href="<?php echo $this->url(array('action' => 'makepayment', 'tip_id' => $tip->tip_id),'eusertip_general',true); ?>"><?php echo $this->translate('Select Tip');?></a>
              </div>
            </section>
          </li>
      	<?php } ?>
      </ul>
		</div>
  </div>
  </div>
  </div>  
  </div>    
<?php } ?>
