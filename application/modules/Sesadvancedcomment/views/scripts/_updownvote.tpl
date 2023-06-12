<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _updownvote.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php  if(!empty($_SESSION['fromActivityFeed'])){ return; } ?>
<?php $isPageSubject = empty($this->isPageSubject) ? $this->viewer() : $this->isPageSubject; 
      $item = $this->item;
      $isVote = Engine_Api::_()->getDbTable('voteupdowns','sesadvancedcomment')->isVote(array('resource_id'=>$item->getIdentity(),'resource_type'=>$item->getType(),'user_id'=>$isPageSubject->getIdentity(),'user_type'=>$isPageSubject->getType()));
      
      if($item->getType() == 'activity_action' && $item->getIdentity()) {
        $detail_id = Engine_Api::_()->getDbTable('details', 'sesadvancedactivity')->isRowExists($item->getIdentity());
        if($detail_id) {
            $detailAction = Engine_Api::_()->getItem('sesadvancedactivity_detail',$detail_id);
        }
      } else {
        if($item->getType() == 'activity_comment') {
          $detailAction = Engine_Api::_()->getDbTable('activitycomments', 'sesadvancedactivity')->rowExists($item->getIdentity());
        } else if($item->getType() == 'core_comment') {
          $detailAction = Engine_Api::_()->getDbTable('corecomments', 'sesadvancedactivity')->rowExists($item->getIdentity());
        }
        //$detailAction = $item;
      }
?>
<?php if($this->viewer()->getIdentity()) { ?>
	<li class="advcomnt_feed_votebtn">
		<span class="upvote">
			<a href="javascript:;" data-itemguid="<?php echo $item->getGuid(); ?>" data-userguid="<?php echo $isPageSubject->getGuid(); ?>" title="<?php echo $this->translate('Up Vote'); ?>" class="<?php echo !empty($isVote) && $isVote->type == "upvote" ? '_disabled ' : ""; ?> sesadv_upvote_btn">
				<i class="fa fa-angle-up"></i>
				<span><?php echo $detailAction->vote_up_count; ?></span>
			</a>
		</span>  
		<span>|</span>
		<span class="downvote">
			<a href="javascript:;" data-itemguid="<?php echo $item->getGuid(); ?>" data-userguid="<?php echo $isPageSubject->getGuid(); ?>" title="<?php echo $this->translate('Down Vote'); ?>" class="<?php echo !empty($isVote) && $isVote->type == "downvote" ? '_disabled ' : ""; ?> sesadv_downvote_btn">
				<i class="fa fa-angle-down"></i>
				<span><?php echo $detailAction->vote_down_count; ?></span>
			</a>
		</span>
	</li>
<?php } ?>
