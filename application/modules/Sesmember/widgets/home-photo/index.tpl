<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<?php $getUserInfoItem = Engine_Api::_()->sesmember()->getUserInfoItem($this->viewer()->getIdentity()); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmember/externals/styles/styles.css'); ?>
<?php if(isset($this->titleActive) || isset($this->verifiedLabelActive)):?>
	<h3>
		<?php if(isset($this->titleActive)):?><?php echo $this->translate('Hi %1$s!', $this->viewer()->getTitle()); ?><?php endif;?>
		<?php if(isset($this->verifiedLabelActive) && $getUserInfoItem->user_verified):?><i class="sesbasic_verified_icon" title="Verified"></i><?php endif;?>
	</h3>
<?php endif;?>
<div class="sesmember_home_photo_block sesbasic_bxs sesbasic_clearfix">
  <?php if($getUserInfoItem->featured):?>
    <div class="sesmember_labels clear"><p class="sesmember_label_featured"><?php echo $this->translate('FEATURED');?></p></div>
  <?php endif;?>
  <div class="sesmember_home_photo_block_photo sesbasic_clearfix">
    <?php echo $this->htmlLink($this->viewer()->getHref(), $this->itemPhoto($this->viewer(), 'thumb.main')) ?>
    <?php if(isset($this->vipLabelActive) && $getUserInfoItem->vip):?>
      <div class="sesmember_vip_label" title="VIP" style="background-image:url(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('member_vip_image', 'application/modules/Sesmember/externals/images/vip-label.png'); ?>)"></div>
    <?php endif;?>
  </div>
  <?php if($getUserInfoItem->sponsored):?>
    <div class="sesmember_labels clear"><p class="sesmember_label_sponsored"><?php echo $this->translate('SPONSORED');?></p></div>
  <?php endif;?>
</div>
