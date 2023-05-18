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

<?php $rating1 = $rating2 = $rating3 = $rating4 = $rating5 = 0; ?>
<?php if(engine_count($this->ratingStats)){ 
	foreach($this->ratingStats as $starsRating){
  	${"rating".$starsRating['rating']} = $starsRating['total'];
 	}	
} 
	$totalRatings = $rating1 + $rating2 + $rating3 + $rating4 + $rating5;
  
?>
<div class="sesbasic_sidebar_block sesmember_rating_disc_block sesbasic_bxs">
<p><b>Rating Distribution</b></p>
<div class="sesmember_rating_list_block sesmember_rating_list_first">
	<div class="rating_list_left floatL"><?php echo $this->translate('5 stars');?></div>
  <div class="rating_list_right">
  	<div class="rating_list_right_inner" style="width:<?php echo ( $rating5 / $totalRatings ) * 100 ?>%;"></div>
  	<span class="rating_list_contant"><?php echo $rating5 ?></span>
  </div>
</div>
<div class="sesmember_rating_list_block sesmember_rating_list_second">
	<div class="rating_list_left floatL"><?php echo $this->translate('4 stars');?></div>
  <div class="rating_list_right">
  	<div class="rating_list_right_inner" style="width:<?php echo ( $rating4 / $totalRatings ) * 100 ?>%;"></div>
    <span class="rating_list_contant"><?php echo $rating4 ?></span>
  </div>
  
</div>
<div class="sesmember_rating_list_block sesmember_rating_list_three">
	<div class="rating_list_left floatL"><?php echo $this->translate('3 stars');?></div>
  <div class="rating_list_right">
  	<div class="rating_list_right_inner" style="width:<?php echo ( $rating3 / $totalRatings ) * 100 ?>%;"></div>
    <span class="rating_list_contant"><?php echo $rating3 ?></span>
  </div>
  
</div>
<div class="sesmember_rating_list_block sesmember_rating_list_four">
	<div class="rating_list_left floatL"><?php echo $this->translate('2 stars');?></div>
  <div class="rating_list_right">
  	<div class="rating_list_right_inner" style="width:<?php echo ( $rating2 / $totalRatings ) * 100 ?>%;"></div>
    <span class="rating_list_contant"> <?php echo $rating2 ?></span>
  </div>
  
</div>
<div class="sesmember_rating_list_block sesmember_rating_list_five">
	<div class="rating_list_left floatL"><?php echo $this->translate('1 stars');?></div>
  <div class="rating_list_right">
  	<div class="rating_list_right_inner" style="width:<?php echo ( $rating1 / $totalRatings ) * 100 ?>%;"></div>
    <span class="rating_list_contant"><?php echo $rating1 ?></span>
  </div>
  
</div>
<a href="<?php echo $this->url(array('action'=>'review-stats','user_id'=>$this->subject->getIdentity()), 'sesmember_general'); ?>" class="sessmoothbox">View more graph...</a>
</div>
