<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: review-stats.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<?php $rating1 = $rating2 = $rating3 = $rating4 = $rating5 = 0; ?>
<?php if(engine_count($this->ratingStats)){ 
	foreach($this->ratingStats as $starsRating){
  	${"rating".$starsRating['rating']} = $starsRating['total'];
 	}	
} 
	$totalRatings = $rating1 + $rating2 + $rating3 + $rating4 + $rating5;
  
?>

<div class="sesmember_review_stats_popup">
	<div class="sesmember_review_stats_popup_header"><?php echo $this->translate("Rating Details"); ?></div>
  <div class="sesmember_review_stats_popup_cont">
    <div class="sesmemeber_review_stats sesbasic_bxs sesbasic_clearfix">
      <div class="sesmemeber_review_stats_row sesbasic_clearfix">
        <div class="sesmemeber_review_stat_label"><?php echo $this->translate('5 star');?></div>
        <div class="sesmemeber_review_stat_total"><?php echo $rating5 ?></div>
        <div class="sesmemeber_review_stat_gr">
        <?php if($rating5){ ?>
          <div class="sesmemeber_review_stat_users floatR">
          	<?php 
            	$start5 = Engine_Api::_()->getDbTable('reviews','sesmember')->getUserRatingWithStar(array('user_id'=>$this->subject->user_id,'rating'=>5)); 
            	$start5->setItemCountPerPage(2);
    					$start5->setCurrentPageNumber(1);
              $totalitem5 = $start5->getTotalItemCount();
            ?>
            <?php foreach($start5 as $user5){ ?>
            	<?php $user = Engine_Api::_()->getItem('user',$user5->owner_id); ?>
            
							<a href="<?php echo $user->getHref(); ?>">
                            
              	<img class="thumb_icon item_photo_user thumb_icon ses_tooltip" data-src="<?php echo $user->getGuid(); ?>" title="<?php echo $user->getTitle(); ?>"  alt="<?php echo $user->getTitle(); ?>" src="<?php echo $user->getPhotoUrl('thumb.icon'); ?>">
             </a>
            
            <?php } ?>
            <?php if($totalitem5 > 2){ ?>
            <a href="<?php echo 'sesmember/index/review-user-rating/rating_id/5/user_id/'.$this->subject->user_id ; ?>" onclick="return opensmoothboxurl(this.href);"><span>+ <?php $totalitem5 - 2 ?></span></a>
            <?php } ?>
          </div>
        <?php } ?>
        <?php if($totalRatings): ?>
          <div class="sesmemeber_review_stat_gr_line"><span style="width:<?php echo ( $rating5 / $totalRatings ) * 100 ?>%;"></span></div>
          </div>
        <?php endif; ?>
      </div>
      <div class="sesmemeber_review_stats_row sesbasic_clearfix">
        <div class="sesmemeber_review_stat_label"><?php echo $this->translate('4 star');?></div>
        <div class="sesmemeber_review_stat_total"><?php echo $rating4 ?></div>
        <div class="sesmemeber_review_stat_gr">
        <?php if($rating4){ ?>
          <div class="sesmemeber_review_stat_users floatR">
						<?php 
            	$start4 = Engine_Api::_()->getDbTable('reviews','sesmember')->getUserRatingWithStar(array('user_id'=>$this->subject->user_id,'rating'=>4)); 
            	$start4->setItemCountPerPage(2);
    					$start4->setCurrentPageNumber(1);
              $totalitem4 = $start4->getTotalItemCount();
            ?>
            <?php foreach($start4 as $user4){ ?>
            	<?php $user = Engine_Api::_()->getItem('user',$user4->owner_id); ?>
            
							<a href="<?php echo $user->getHref(); ?>">
              	<img class="thumb_icon item_photo_user thumb_icon ses_tooltip" data-src="<?php echo $user->getGuid(); ?>" title="<?php echo $user->getTitle(); ?>"  alt="<?php echo $user->getTitle(); ?>" src="<?php echo $user->getPhotoUrl('thumb.icon'); ?>">
             </a>
            
            <?php } ?>
            <?php if($totalitem4 > 2){ ?>
            <a href="<?php echo 'sesmember/index/review-user-rating/rating_id/4/user_id/'.$this->subject->user_id ; ?>" onclick="return opensmoothboxurl(this.href);"><span>+ <?php $totalitem4 - 2 ?></span></a>
            <?php } ?>
          </div>
       <?php } ?>
          <div class="sesmemeber_review_stat_gr_line"><span style="width:<?php echo ( $rating4 / $totalRatings ) * 100 ?>%;"></span></div>
        </div>
      </div>
      <div class="sesmemeber_review_stats_row sesbasic_clearfix">
        <div class="sesmemeber_review_stat_label"><?php echo $this->translate('3 star');?></div>
        <div class="sesmemeber_review_stat_total"><?php echo $rating3 ?></div>
        <div class="sesmemeber_review_stat_gr">
          <?php if($rating3){ ?>
          <div class="sesmemeber_review_stat_users floatR">
						<?php 
            	$start3 = Engine_Api::_()->getDbTable('reviews','sesmember')->getUserRatingWithStar(array('user_id'=>$this->subject->user_id,'rating'=>3)); 
            	$start3->setItemCountPerPage(2);
    					$start3->setCurrentPageNumber(1);
              $totalitem3 = $start3->getTotalItemCount();
            ?>
            <?php foreach($start3 as $user3){ ?>
            	<?php $user = Engine_Api::_()->getItem('user',$user3->owner_id); ?>
            
							<a href="<?php echo $user->getHref(); ?>">
              	<img class="thumb_icon item_photo_user thumb_icon ses_tooltip" data-src="<?php echo $user->getGuid(); ?>" title="<?php echo $user->getTitle(); ?>"  alt="<?php echo $user->getTitle(); ?>" src="<?php echo $user->getPhotoUrl('thumb.icon'); ?>">
             </a>
            
            <?php } ?>
            <?php if($totalitem3 > 2){ ?>
            <a href="<?php echo 'sesmember/index/review-user-rating/rating_id/3/user_id/'.$this->subject->user_id ; ?>" onclick="return opensmoothboxurl(this.href);"><span>+ <?php $totalitem3 - 2 ?></span></a>
            <?php } ?>
          </div>
       <?php } ?>
          <div class="sesmemeber_review_stat_gr_line"><span style="width:<?php echo ( $rating3 / $totalRatings ) * 100 ?>%;"></span></div>
        </div>
      </div>
      <div class="sesmemeber_review_stats_row sesbasic_clearfix">
        <div class="sesmemeber_review_stat_label"><?php echo $this->translate('2 star');?></div>
        <div class="sesmemeber_review_stat_total"><?php echo $rating2 ?></div>
       <div class="sesmemeber_review_stat_gr">
          <?php if($rating2){ ?>
          <div class="sesmemeber_review_stat_users floatR">
						<?php 
            	$start2 = Engine_Api::_()->getDbTable('reviews','sesmember')->getUserRatingWithStar(array('user_id'=>$this->subject->user_id,'rating'=>2)); 
            	$start2->setItemCountPerPage(2);
    					$start2->setCurrentPageNumber(1);
              $totalitem2 = $start2->getTotalItemCount();
            ?>
            <?php foreach($start2 as $user2){ ?>
            	<?php $user = Engine_Api::_()->getItem('user',$user2->owner_id); ?>
            
							<a href="<?php echo $user->getHref(); ?>">
              	<img class="thumb_icon item_photo_user thumb_icon ses_tooltip" data-src="<?php echo $user->getGuid(); ?>" title="<?php echo $user->getTitle(); ?>"  alt="<?php echo $user->getTitle(); ?>" src="<?php echo $user->getPhotoUrl('thumb.icon'); ?>">
             </a>
            
            <?php } ?>
            <?php if($totalitem2 > 2){ ?>
            <a href="<?php echo 'sesmember/index/review-user-rating/rating_id/2/user_id/'.$this->subject->user_id ; ?>" onclick="return opensmoothboxurl(this.href);"><span>+ <?php $totalitem2 - 2 ?></span></a>
            <?php } ?>
          </div>
       <?php } ?>
          <div class="sesmemeber_review_stat_gr_line"><span style="width:<?php echo ( $rating2 / $totalRatings ) * 100 ?>%;"></span></div>
        </div>
      </div>
      <div class="sesmemeber_review_stats_row sesbasic_clearfix">
        <div class="sesmemeber_review_stat_label"><?php echo $this->translate('1 star');?></div>
        <div class="sesmemeber_review_stat_total"><?php echo $rating1 ?></div>
        <div class="sesmemeber_review_stat_gr">
          <?php if($rating1){ ?>
          <div class="sesmemeber_review_stat_users floatR">
						<?php 
            	$start1 = Engine_Api::_()->getDbTable('reviews','sesmember')->getUserRatingWithStar(array('user_id'=>$this->subject->user_id,'rating'=>1)); 
            	$start1->setItemCountPerPage(2);
    					$start1->setCurrentPageNumber(1);
              $totalitem1 = $start1->getTotalItemCount();
            ?>
            <?php foreach($start1 as $user1){ ?>
            	<?php $user = Engine_Api::_()->getItem('user',$user1->owner_id); ?>
							<a href="<?php echo $user->getHref(); ?>">
              	<img class="thumb_icon item_photo_user thumb_icon ses_tooltip" data-src="<?php echo $user->getGuid(); ?>" title="<?php echo $user->getTitle(); ?>"  alt="<?php echo $user->getTitle(); ?>" src="<?php echo $user->getPhotoUrl('thumb.icon'); ?>">
             </a>
            <?php } ?>
            <?php if($totalitem1 > 2){ ?>
            <a href="<?php echo 'sesmember/index/review-user-rating/rating_id/1/user_id/'.$this->subject->user_id ; ?>" onclick="return opensmoothboxurl(this.href);"><span>+ <?php $totalitem1 - 2 ?></span></a>
            <?php } ?>
          </div>
       <?php } ?>
          <div class="sesmemeber_review_stat_gr_line"><span style="width:<?php echo ( $rating1 / $totalRatings ) * 100 ?>%;"></span></div>
        </div>
      </div>
    </div>
  </div>
</div>
