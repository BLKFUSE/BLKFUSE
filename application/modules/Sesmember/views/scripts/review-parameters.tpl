<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: review-parameters.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $profileType = Engine_Api::_()->sesmember()->getProfileType($this->userObject, 'option_id');?>
<?php $reviewParameters = Engine_Api::_()->getDbtable('parameters', 'sesmember')->getParameterResult(array('profile_type'=>$profileType));?>
<?php if(engine_count($reviewParameters)):?>
  <?php foreach($reviewParameters as $value):?>
    <div class="form-wrapper sesmember_form_review_star">
      <div class="form-label"><label><?php echo $this->translate($value['title']); ?></label></div>
      <div id="sesmember_review_rating" class="sesbasic_rating_parameter sesmember_rating_star_element" onmouseout="rating_out_review(<?php echo $value['parameter_id'] ?>);">
	<span id="rate_1_<?php echo $value['parameter_id'] ?>" class="sesbasic-rating-parameter-unit sesbasic-rating-parameter-unit-disable" onclick="rate_review(1,<?php echo $value['parameter_id'] ?>);" onmouseover="rating_over_review(1,<?php echo $value['parameter_id'] ?>);"></span>
	<span id="rate_2_<?php echo $value['parameter_id'] ?>" class="sesbasic-rating-parameter-unit sesbasic-rating-parameter-unit-disable" onclick="rate_review(2,<?php echo $value['parameter_id'] ?>);" onmouseover="rating_over_review(2,<?php echo $value['parameter_id'] ?>);"></span>
	<span id="rate_3_<?php echo $value['parameter_id'] ?>" class="sesbasic-rating-parameter-unit sesbasic-rating-parameter-unit-disable" onclick="rate_review(3,<?php echo $value['parameter_id'] ?>);" onmouseover="rating_over_review(3,<?php echo $value['parameter_id'] ?>);"></span>
	<span id="rate_4_<?php echo $value['parameter_id'] ?>" class="sesbasic-rating-parameter-unit sesbasic-rating-parameter-unit-disable" onclick="rate_review(4,<?php echo $value['parameter_id'] ?>);" onmouseover="rating_over_review(4,<?php echo $value['parameter_id'] ?>);"></span>
	<span id="rate_5_<?php echo $value['parameter_id'] ?>" class="sesbasic-rating-parameter-unit sesbasic-rating-parameter-unit-disable" onclick="rate_review(5,<?php echo $value['parameter_id'] ?>);" onmouseover="rating_over_review(5,<?php echo $value['parameter_id'] ?>);"></span>
	<span id="rating_text_<?php echo $value['parameter_id'] ?>" class="sesbasic_rating_text"><?php echo $this->translate('click to rate');?></span>
      </div>
    </div>
    <input type="hidden" name="review_parameter_<?php echo $value['parameter_id'] ?>" id="review_parameter_<?php echo $value['parameter_id'] ?>" />
  <?php endforeach;?>
<?php endif;?>


<script type="text/javascript">
  function ratingTextReview(rating){
    var text = '';
    if(rating == 1)
    text = "<?php echo $this->translate('terrible');?>";
    else if(rating == 2)
    text = "<?php echo $this->translate('poor');?>";
    else if(rating == 3)
    text = "<?php echo $this->translate('average');?>";
    else if(rating == 4)
    text = "<?php echo $this->translate('very good');?>";
    else if(rating == 5)
    text = "<?php echo $this->translate('excellent');?>";
    else 
    text = "<?php echo $this->translate('click to rate');?>";
    return text;
  }
  var rating_over_review = window.rating_over_review = function(rating,id) {
    document.getElementById('rating_text_'+id).innerHTML = ratingTextReview(rating);
    for(var x=1; x<=5; x++) {
      if(x <= rating)
      scriptJquery('#rate_'+x+'_'+id).addClass('sesbasic-rating-parameter-unit');
      else 
      scriptJquery('#rate_'+x+'_'+id).addClass('sesbasic-rating-parameter-unit sesbasic-rating-parameter-unit-disable');
    }
  }
  var rating_out_review = window.rating_out_review = function(id) {
    var star_value = document.getElementById('review_parameter_'+id).value;
    document.getElementById('rating_text_'+id).innerHTML = ratingTextReview(star_value);
    if(star_value != '') {
      set_rating_review(star_value,id);
    }
    else {
      for(var x=1; x<=5; x++) {	
        scriptJquery('#rate_'+x+'_'+id).addClass('sesbasic-rating-parameter-unit sesbasic-rating-parameter-unit-disable');
      }
    }
  }
  var rate_review = window.rate_review = function(rating,id) {
    document.getElementById('review_parameter_'+id).value = rating;
    document.getElementById('rating_text_'+id).innerHTML = ratingTextReview(rating);
    set_rating_review(rating,id);
  }
  var set_rating_review = window.set_rating_review = function(rating,id) {
    for(var x=1; x<=parseInt(rating); x++) {
      scriptJquery('#rate_'+x+'_'+id).addClass('sesbasic-rating-parameter-unit');
    }
    for(var x=parseInt(rating)+1; x<=5; x++) {
      scriptJquery('#rate_'+x+'_'+id).addClass('sesbasic-rating-parameter-unit sesbasic-rating-parameter-unit-disable');
    }
    document.getElementById('rating_text_'+id).innerHTML = ratingTextReview(rating);
  }
  
  scriptJquery(document).ready(function() {
    var countExistsParam = scriptJquery('.sesmember_review_values');
    for(var i=0;i<countExistsParam.length;i++){
      var valueEx = scriptJquery(countExistsParam[i]).val();	
      var id = scriptJquery(countExistsParam[i]).attr('id');	
      id = id.replace('review_parameter_value_','');
      scriptJquery('#review_parameter_'+id).val(valueEx);
      set_rating_review(valueEx,id);
    }
  });
</script>
