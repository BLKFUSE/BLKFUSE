<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/styles/styles.css'); ?>

<?php $transaction = $this->transaction; 
$item = $this->ad;
$package = $this->package;
$isrenew = $package->is_renew_link;
$renew_link_days = $package->renew_link_days;
?>
<div class="sescommunitads_details sesbasic_bxs">
  <?php if($package->isFree()){ ?>
    <div class="sesbasic_clearfix paymnt_b _paymnt_free"><span>Payment Status:</span> <span>FREE</span></div>
    <div class="sesbasic_clearfix paymnt_b _paymnt_date"><span><?php echo $this->partial('_expiry.tpl','sescommunityads',array('ad'=>$item)); ?></span></div>
  <?php }else{ ?>
    <?php if($transaction){ ?>
    <?php if(!(strtotime($transaction->expiration_date) <= time()) && $package->package_type != "nonRecurring"){ ?>
      <div class="sesbasic_clearfix paymnt_b _paymnt_status"><span><?php echo $this->translate("Payment Status:"); ?></span> <span><strong><?php echo ucwords($transaction->state); ?></strong></span></div>
      <div class="sesbasic_clearfix paymnt_b _paymnt_date"><span><?php echo $this->translate("Expire On:"); ?> </span> <span><?php echo date("M d,Y g:i A", strtotime($transaction->expiration_date)); ?></span></div>
    <?php } ?>
    <?php if($package->isOneTime()){
      $timePeriod = $package->renew_link_days;
      if($isrenew){
        if($item->ad_type == "perclick"){
          if($item->ad_limit != "-1" && $timePeriod <= $item->ad_limit - $item->click_count){ ?>
            <div class="sesbasic_clearfix"><a href="<?php echo $this->url(array('sescommunityad_id' => $item->sescommunityad_id,'action'=>'index'), 'sescomminityads_payment', true); ?>" class="sescontest_payment_btn sesbasic_animation"><i class="fab fa-paypal"></i><span><?php echo $this->translate("Reniew Advertisement Payment"); ?></span></a></div>
        <?php
          }
        }else if($item->ad_type == "perview"){
          if($item->ad_limit != "-1" && $timePeriod <= $item->ad_limit - $item->view_count){ ?>
            <div class="sesbasic_clearfix"><a href="<?php echo $this->url(array('sescommunityad_id' => $item->sescommunityad_id,'action'=>'index'), 'sescomminityads_payment', true); ?>" class="sescontest_payment_btn sesbasic_animation"><i class="fab fa-paypal"></i><span><?php echo $this->translate("Reniew Advertisement Payment"); ?></span></a></div>
        <?php
          }
        }else if($item->ad_type == "perday" && $item->ad_limit != "-1"){
          if(!empty($transaction->expiration_date) && $transaction->expiration_date != '3000-00-00 00:00:00'){
            $datediff = strtotime($transaction->expiration_date) - time();
            $daysLeft =  ceil($datediff/(60*60*24));
            if($daysLeft <= $renew_link_days || strtotime($transaction->expiration_date) <= time()){ ?>
            <div class="sesbasic_clearfix"><a href="<?php echo $this->url(array('sescommunityad_id' => $item->sescommunityad_id,'action'=>'index'), 'sescomminityads_payment', true); ?>" class="sescmads_payment_btn sesbasic_animation"><i class="fab fa-paypal"></i><span><?php echo $this->translate("Reniew Advertisement Payment"); ?></span></a></div>
           <?php 	
            }
          }
        }
        else{ ?>
          <div class="sesbasic_clearfix paymnt_b _paymnt_status"><span><?php echo $this->translate("Payment Status:"); ?></span> <span><?php echo ucwords($transaction->state); ?></span></div>
       <?php
        }
      }//else if($package){ ?>
        <?php if($package->click_type == "perclick"){ ?>
          <?php if(!$package->click_limit){
                  $description = 'Never';
                }else{
                  $description = $this->translate("After %s Clicks",$package->click_limit);
                }
             ?>
        <?php }elseif($package->click_type == "perday"){ 
                if(!$package->click_limit){
                  $description = 'Never';
                }else{
                  $description = $this->translate("After %s Days",$package->click_limit);
                }
          ?>
        <?php }else{ 
                if(!$package->click_limit){
                  $description = 'Never';
                }else{
                  $description = $this->translate("After %s Views",$package->click_limit);
                }
         } ?>
         
        <div class="sesbasic_clearfix paymnt_b _paymnt_date"><span><?php echo $this->translate("SESCOMMExpire"); ?> </span> <span><?php echo $description; ?></span></div>
      <?php 
        //} 
     ?>
    <?php } ?>
    <?php }else{  ?>
      <div>
        <a href="<?php echo $this->url(array('sescommunityad_id' => $item->sescommunityad_id,'action'=>'index'), 'sescomminityads_payment', true); ?>" class="sescmads_payment_btn sesbasic_animation"><i class="fa fa-money-bill"></i><span><?php echo $this->translate("Make Payment"); ?></span></a>
      </div>
    <?php } ?>
  <?php } ?>
</div>  
