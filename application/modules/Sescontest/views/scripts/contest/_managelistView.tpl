<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _listView.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
  <?php if(strlen($contest->getTitle()) > $this->params['list_title_truncation']):?>
    <?php $title = mb_substr($contest->getTitle(),0,$this->params['list_title_truncation']).'...';?>
  <?php else: ?>
    <?php $title = $contest->getTitle();?>
  <?php endif; ?>
  <?php $owner = $contest->getOwner();?>
<div class="sescontest_manage_list_item">
  <article class="sesbasic_clearfix">
    <div class="sescontest_manage_list_item_thumb sescontest_list_thumb">
      <a href="<?php echo $contest->getHref();?>" class="sescontest_manage_list_item_img"><span style="background-image:url(<?php echo $contest->getPhotoUrl('thumb.profile'); ?>);"></span></a>
      <div class="sescontest_list_labels sesbasic_animation">
        <?php // include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataLabel.tpl';?>
      </div>
      <div class="sescontest_list_thumb_over">
        <a href="<?php echo $contest->getHref();?>"></a>
        <div class="sescontest_list_btns">
          <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataSharing.tpl';?>
        </div>
      </div>
      <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_mediaType.tpl';?>
  	</div>
    <div class="sescontest_manage_list_item_cont">
    	<div class="sescontest_manage_list_item_title">
      	<?php if(isset($this->titleActive)):?>
          	<a href="<?php echo $contest->getHref();?>"><?php echo $title;?></a> <?php if(isset($this->verifiedLabelActive)&& $contest->verified):?><i class="sescontest_label_verified fa fa-check-circle" title='<?php echo $this->translate("Verified");?>'></i><?php endif;?>
        <?php endif;?>
      </div>
      <div class="sescontest_manage_list_cont_inner">
      	<div class="sescontest_manage_list_item_info">
          <div class="sescontest_manage_list_item_owner sesbasic_clearfix sesbasic_text_light">
            <?php if(isset($this->byActive)):?>
              <span><?php echo $this->translate('by');?>&nbsp;<?php echo $this->htmlLink($owner->getHref(), $owner->getTitle());?></span>
            <?php endif;?>
            <?php if(isset($category) && isset($this->categoryActive)):?>
              <span><?php echo $this->translate('in');?>&nbsp;<a href="<?php echo $category->getHref(); ?>"><?php echo $this->translate($category->category_name) ?></a></span>
            <?php endif;?>
          </div>
          <?php if(isset($this->startenddateActive)):?>
          <div class="sescontest_manage_list_item_date">
            <i class="fa fa-calendar sesbasic_text_light"></i>
            <?php echo $this->contestStartEndDates($contest, $dateinfoParams);?>
          </div>
          <?php endif;?>
          <div class="sescontest_manage_list_item_stats sesbasic_text_light">
            <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataStatics.tpl';?>
          </div>
    
          <div class="sescontest_manage_list_item_total">
            <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataBar.tpl';?>
          </div>
      	</div>
        <div class="sescontest_manage_list_item_options">
          <?php if(Engine_Api::_()->sescontest()->contestPrivacy($contest, 'edit')):?>
            <a href="<?php echo $this->url(array('action' => 'edit', 'contest_id' => $contest->custom_url), 'sescontest_dashboard', 'true');?>"><i class="fas fa-pencil-alt"></i><span><?php echo $this->translate("Edit Contest")?></span></a>
            <?php if($contest->authorization()->isAllowed($this->viewer(), 'delete')){ ?>
              <a href="<?php echo $this->url(array('contest_id' => $contest->contest_id,'action'=>'delete'), 'sescontest_general', true); ?>" class="smoothbox"><i class="fa fa-trash"></i><span><?php echo $this->translate("Delete Contest")?></span></a>
            <?php } ?>
          <?php endif;?>
          <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)):?>
            <?php $package = Engine_Api::_()->getItem('sescontestpackage_package',$contest->package_id);?>
            <?php if(!$package->isFree()): ?>
              <?php $transaction = Engine_Api::_()->getDbTable('transactions', 'sescontestpackage')->getItemTransaction(array('order_package_id' => $contest->orderspackage_id, 'contest' => $contest));?>
              <?php if($transaction): ?>
                <?php if($package->isOneTime()):?>
                  <?php if($package->is_renew_link):?>
                    <?php if(!empty($transaction->expiration_date) && $transaction->expiration_date != '3000-00-00 00:00:00'):?>
                      <?php $datediff = strtotime($transaction->expiration_date) - time();?>
                      <?php $daysLeft =  floor($datediff/(60*60*24));?>
                      <?php if($daysLeft <= $renew_link_days || strtotime($transaction->expiration_date) <= time()): ?>
                        <div class="sesbasic_clearfix"><a href="<?php echo $this->url(array('contest_id' => $item->contest_id,'action'=>'index'), 'sescontestpackage_payment', true); ?>" class="sescontest_payment_btn sesbasic_animation"><i class="far fa-money-bill-alt"></i><span><?php echo $this->translate("Reniew Contest Payment"); ?></span></a></div>
                      <?php endif;?>
                    <?php else: ?>
                      <div class="sesbasic_clearfix _paymnt_status"><span>Payment Status:</span> <span><?php echo ucwords($transaction->state); ?></span></div>
                    <?php endif;?>
                  <?php endif;?>
                <?php endif;?>
              <?php else:?>
                <a href="<?php echo $this->url(array('contest_id' => $contest->contest_id,'action'=>'index'), 'sescontestpackage_payment', true); ?>"><i class="far fa-money-bill-alt"></i><span><?php echo $this->translate("Make Payment")?></span></a>
              <?php endif;?>
            <?php endif;?>
          <?php endif;?>
          <?php $currentTime = time();?>
          <?php if(strtotime($contest->starttime) > $currentTime):?>
            <?php $status = 'notStarted';?>
          <?php elseif(strtotime($contest->endtime) < $currentTime):?>
            <?php $status = 'expire';?>
          <?php else:?>
            <?php $status = 'onGoing';?>	
          <?php endif;?>
          <?php if($status == 'notStarted'){ ?>
            <span class="sescontest_manage_list_item_status _comingsoon"><?php echo $this->translate('Coming Soon');?></span>
          <?php }else if($status == 'expire'){ ?>
            <span class="sescontest_manage_list_item_status _ended"><?php echo $this->translate('Contest Expired');?></span>
          <?php }else{ ?>
            <span class="sescontest_manage_list_item_status _active"><?php echo $this->translate('Contest ongoing');?></span>
          <?php } ?>
        </div>
      </div>
    </div>
  </article>
</div>