<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _expiry.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php 
  $item=$this->ad; 
  $package = $item->getPackage();
?>
<?php if($package->price < 1){?>
  <?php if($item->ad_type == "perclick"){ ?>
  <?php if($item->ad_limit == -1){
          $description = 'Never Expire';
        }else if($item->click_count >= $item->ad_limit){
          $description = "Expired";
        }else{
          $description = "Expire After ".$item->ad_limit.' Clicks';
        }
     ?>
  <?php }elseif($item->ad_type == "perday"){ 
        if($item->ad_limit == -1){
          $description = 'Never Expire';
        }else if(time() >= strtotime($item->ad_expiration_date)){
          $description = "Expired";
        }else{
          $description = "Expire After ".$item->ad_limit.' Days';
        }
  ?>
  <?php }else{ 
        if($item->ad_limit == -1){
          $description = 'Never Expire';
        }else if($item->views_count >= $item->ad_limit){
          $description = "Expired";
        }else{
          $description = "Expire After ".$item->ad_limit.' Views';
        }
   } ?>
  <?php echo $description;?>
<?php }else{?> 
  <?php $transaction = $item->getTransaction();?>
  <?php if(!$transaction){ ?>
    <?php if($item->orderspackage_id){?>
        <?php $table = Engine_Api::_()->getDbTable('transactions','sescommunityads');?>
        <?php $tableName = $table->info('name');?>
        <?php $select = $table->select()->from($tableName)->where('orderspackage_id =?',$item->orderspackage_id)->where('gateway_profile_id !=?','')->where('state = "pending" || state = "complete" || state = "okay" || state = "active"')->limit(1);?>
        <?php $transaction =  $table->fetchRow($select);?>
        <?php if($transaction){ ?>
          <?php echo date('Y-m-d H:i:s',strtotime($transaction->expiration_date));?>
        <?php }else{ ?>
          <?php echo "Never Expire";?>
        <?php } ?>
    <?php }else{ ?>
     <?php echo "Never Expire";?>	
    <?php } ?>
  <?php }
  if($transaction){ ?>
    <?php if($item->ad_type == "perclick"){ ?>
    <?php if($item->ad_limit == -1){
            //$description = 'Never Expire';
          }else if($item->click_count >= $item->ad_limit){
            $description = "Expired";
          }else{
            $description = "Expire After ".$item->ad_limit.' Clicks';
          }
       ?>
    <?php }elseif($item->ad_type == "perday"){ 
          if(!$item->ad_limit == -1){
            //$description = 'Never Expire';
          }else if(time() >= strtotime($item->ad_expiration_date)){
            $description = "Expired";
          }else{
            $description = "Expire After ".$item->ad_limit.' Days';
          }
    ?>
    <?php }else{ 
          if($item->ad_limit == -1){
            //$description = 'Never Expire';
          }else if($item->views_count >= $item->ad_limit){
            $description = "Expired";
          }else{
            $description = "Expire After ".$item->ad_limit.' Views';
          }
     } ?>
    <?php if($description == "Expired"){ ?>
    <?php echo $description;?>
    <?php }else{ ?>
      <?php echo "Expire After ".date('Y-m-d H:i:s',strtotime($transaction->expiration_date)).($description ? ' OR '.$description : "") ; ?>
    <?php } ?>
  <?php } ?>
<?php }?>