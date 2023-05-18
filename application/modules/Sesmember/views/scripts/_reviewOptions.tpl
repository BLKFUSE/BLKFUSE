<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _reviewOptions.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $item = $this->subject; $viewer = $this->viewer;?>
<div class="sesmember_review_listing_footer clear sesbasic_clearfix">
	<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.votes', 1)){ ?>
    <p><b><?php echo $this->translate("Was this Review...?"); ?></b></p>
    <div class="sesmember_review_listing_btn_left floatL">
    	<?php $isGivenVoteTypeone = Engine_Api::_()->getDbTable('reviewvotes','sesmember')->isReviewVote(array('review_id'=>$item->getIdentity(),'user_id'=>$viewer->getIdentity(),'type'=>1)); ?>
      <a class="sesbasic_button <?php if($viewer->getIdentity()){ ?> sesmember_review_useful <?php } ?> sesbasic_animation <?php echo $isGivenVoteTypeone ? 'active' : '' ?>" href="javascript:;" data-href="<?php echo $item->getIdentity(); ?>" data-type="1"><i></i><span class="title"><?php echo $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.first', 'Useful')); ?></span> <span><?php echo $item->useful_count ?></span></a>
      <?php $isGivenVoteTypetwo = Engine_Api::_()->getDbTable('reviewvotes','sesmember')->isReviewVote(array('review_id'=>$item->getIdentity(),'user_id'=>$viewer->getIdentity(),'type'=>2)); ?>
      <a class="sesbasic_button <?php if($viewer->getIdentity()){ ?>sesmember_review_funny<?php } ?> sesbasic_animation <?php echo $isGivenVoteTypetwo ? 'active' : '' ?>" href="javascript:;" data-href="<?php echo $item->getIdentity(); ?>" data-type="2"><i></i><span class="title"><?php echo $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.second', 'Funny')); ?></span> <span><?php echo $item->funny_count ?></span></a>
      <?php $isGivenVoteTypethree = Engine_Api::_()->getDbTable('reviewvotes','sesmember')->isReviewVote(array('review_id'=>$item->getIdentity(),'user_id'=>$viewer->getIdentity(),'type'=>3)); ?>
      <a class="sesbasic_button <?php if($viewer->getIdentity()){ ?>sesmember_review_cool<?php } ?> sesbasic_animation <?php echo $isGivenVoteTypethree ? 'active' : '' ?>" href="javascript:;" data-href="<?php echo $item->getIdentity(); ?>" data-type="3"><i></i><span class="title"><?php echo $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.third', 'Cool')); ?></span> <span><?php echo $item->cool_count ?></span></a>
    </div>
  <?php } ?>
  <?php $ownerSelf = $viewer->getIdentity() == $item->owner_id ? true : false; ?>
	<div class="sesmember_review_listing_btn_right floatR">
		<?php if($item->authorization()->isAllowed($viewer, 'edit')) { ?>     
			<a class="sesbasic_icon_edit sesbasic_button sesbasic_button_icon <?php if($ownerSelf) { echo 'sesmember_own_update_review'; } ?>" href="<?php echo $this->url(array('route' => 'sesmember_review_view', 'action' => 'edit-review', 'review_id' => $item->review_id,'format' => 'smoothbox'),'sesmember_review_view',true); ?>" <?php //if(!$ownerSelf) { ?> onclick='return opensmoothboxurl(this.href);' <?php  //} ?> ><span><i class="fa fa-caret-down"></i><?php echo $this->translate('Edit Review'); ?></span></a>
		<?php } ?>
		<?php if($item->authorization()->isAllowed($viewer, 'delete')) { ?>     
		<a class="sesbasic_icon_delete sesbasic_button sesbasic_button_icon" href="<?php echo $this->url(array('route' => 'sesmember_review_view', 'action' => 'delete', 'review_id' => $item->review_id,'format' => 'smoothbox'),'sesmember_review_view',true); ?>" onclick='return opensmoothboxurl(this.href);'><span><i class="fa fa-caret-down"></i><?php echo $this->translate('Delete Review'); ?></span></a>
		<?php } ?>
		<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.show.report', 1) && $viewer->getIdentity() && engine_in_array('report', $this->stats)): ?>
		<a class="sesbasic_icon_report sesbasic_button sesbasic_button_icon" href="<?php echo $this->url(array('route' => 'default', 'module' => 'core', 'controller' => 'report', 'action' => 'create', 'subject' => $item->getGuid(), 'format' => 'smoothbox',),'default',true); ?>" onclick='return opensmoothboxurl(this.href);'><span><i class="fa fa-caret-down"></i><?php echo $this->translate('Report');?></span></a>
		<?php endif; ?>
		<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.allow.share', 1) && $viewer->getIdentity() && engine_in_array('share', $this->stats)): ?>
		<a class="sesbasic_icon_share sesbasic_button sesbasic_button_icon" href="<?php echo $this->url(array('route' => 'default', 'module' => 'activity', 'controller' => 'index', 'action' => 'share', 'type' => $item->getType(), 'id' => $item->getIdentity(), 'format' => 'smoothbox'),'default',true); ?>" onclick='return opensmoothboxurl(this.href);'><span><i class="fa fa-caret-down"></i><?php echo $this->translate('Share Review');?></span></a> 
		<?php endif; ?>
		</div>
</div>
