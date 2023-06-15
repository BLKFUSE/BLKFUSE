<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: review-rating.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<div class="form-wrapper sesnews_form_rating_star">
  <div class="form-label"><label><?php echo $this->translate("Overall Rating"); ?></label></div>
  <div id="sesnews_review_rating" class="sesbasic_rating_star sesnews_rating_star_element" onmouseout="rating_out();">
    <span id="rate_1" class="far fa-star star-disable" onclick="rate(1);" onmouseover="rating_over(1);"></span>
    <span id="rate_2" class="far fa-star star-disable" onclick="rate(2);" onmouseover="rating_over(2);"></span>
    <span id="rate_3" class="far fa-star star-disable" onclick="rate(3);" onmouseover="rating_over(3);"></span>
    <span id="rate_4" class="far fa-star star-disable" onclick="rate(4);" onmouseover="rating_over(4);"></span>
    <span id="rate_5" class="far fa-star star-disable" onclick="rate(5);" onmouseover="rating_over(5);"></span>
    <span id="rating_text" class="sesbasic_rating_text"><?php echo $this->translate('click to rate');?></span>
  </div>
</div>

<script type="text/javascript">
function ratingText(rating){
	var text = '';
	if(rating == 1){
    text = "<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.rating.stars.one', 'terrible') ? Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.rating.stars.one', 'terrible') : $this->translate('terrible');?>";
	}else if(rating == 2){
			text = "<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.rating.stars.two', 'poor') ? Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.rating.stars.two', 'poor') : $this->translate('poor');?>";
	}else if(rating == 3){
    text = "<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.rating.stars.three', 'average') ? Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.rating.stars.three', 'average') : $this->translate('average');?>";
	}else if(rating == 4){
    text = "<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.rating.stars.four', 'very good') ? Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.rating.stars.four', 'very good') : $this->translate('very good');?>";
	}else if(rating == 5){
    text = "<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.rating.stars.five', 'excellent') ? Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.rating.stars.five', 'excellent') : $this->translate('excellent');?>";
	}else {
		text = "<?php echo $this->translate('click to rate');?>";
	}
	return text;
}
  var rating_over = window.rating_over = function(rating) {
    document.getElementById('rating_text').innerHTML = ratingText(rating);
    for(var x=1; x<=5; x++) {
      if(x <= rating) {
		scriptJquery('#rate_'+x).addClass('fa fa-star');
				} else {
		scriptJquery('#rate_'+x).addClass('far fa-star star-disable');
      }
    }
  }
  
  var rating_out = window.rating_out = function() {
    var star_value = document.getElementById('rate_value').value;
		document.getElementById('rating_text').innerHTML = ratingText(star_value);
    if(star_value != '') {
      set_rating(star_value);
    }
    else {
      for(var x=1; x<=5; x++) {	
	scriptJquery('#rate_'+x).addClass('far fa-star star-disable').removeClass('fa');;
      }
    }
  }
    
  var rate = window.rate = function(rating) {
    document.getElementById('rate_value').value = rating;
		document.getElementById('rating_text').innerHTML = ratingText(rating);
    set_rating(rating);
  }
    
  var set_rating = window.set_rating = function(rating) {
    for(var x=1; x<=parseInt(rating); x++) {
      scriptJquery('#rate_'+x).addClass('fa fa-star');
    }
    for(var x=parseInt(rating)+1; x<=5; x++) {
      scriptJquery('#rate_'+x).addClass('far fa-star star-disable').removeClass('fa');;
    }
		document.getElementById('rating_text').innerHTML = ratingText(rating);
  }
  
  scriptJquery(document).ready(function() {
		var ratingCount = document.getElementById('rate_value').value;
		if(ratingCount > 0)
			var val = ratingCount;
		else
			var val = 0;
    set_rating(ratingCount);
  });

//Ajax error show before form submit
var error = false;
var objectError ;
var counter = 0;
function validateForm(){
		var errorPresent = false;
		counter = 0;
		scriptJquery('#sesnews_review_form input, #sesnews_review_form select,#sesnews_review_form checkbox,#sesnews_review_form textarea,#sesnews_review_form radio').each(
				function(index){
						var input = scriptJquery(this);
						if(scriptJquery(this).closest('div').parent().css('display') != 'none' && scriptJquery(this).closest('div').parent().find('.form-label').find('label').first().hasClass('required') && scriptJquery(this).prop('type') != 'hidden' && scriptJquery(this).closest('div').parent().attr('class') != 'form-elements'){	
						  if(scriptJquery(this).prop('type') == 'checkbox'){
								value = '';
								if(scriptJquery('input[name="'+scriptJquery(this).attr('name')+'"]:checked').length > 0) { 
										value = 1;
								};
								if(value == '')
									error = true;
								else
									error = false;
							}else if(scriptJquery(this).prop('type') == 'select-multiple'){
								if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
									error = true;
								else
									error = false;
							}else if(scriptJquery(this).prop('type') == 'select-one' || scriptJquery(this).prop('type') == 'select' ){
								if(scriptJquery(this).val() === '')
									error = true;
								else
									error = false;
							}else if(scriptJquery(this).prop('type') == 'radio'){
								if(scriptJquery("input[name='"+scriptJquery(this).attr('name').replace('[]','')+"']:checked").val() === '')
									error = true;
								else
									error = false;
							}else if(scriptJquery(this).prop('type') == 'textarea'){
								if(scriptJquery(this).css('display') == 'none'){
								 var	content = tinymce.get(scriptJquery(this).attr('id')).getContent();
								 if(!content)
								 	error= true;
								 else
								 	error = false;
								}else	if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
									error = true;
								else
									error = false;
							}else{
								if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
									error = true;
								else
									error = false;
							}
							if(error){
							 if(counter == 0){
							 	objectError = this;
							 }
								counter++
							}
							if(error)
								errorPresent = true;
							error = false;
						}
				}
			);
			return errorPresent ;
}
			en4.core.runonce.add(function()
				{
			scriptJquery('#sesnews_review_form').submit(function(e){
					var validationFm = validateForm();
					if(!scriptJquery('#rate_value').val()){
						alert('<?php echo $this->string()->escapeJavascript("Please fill the red mark fields"); ?>');
						 var errorFirstObject = scriptJquery('#sesnews_review_rating').parent();
						 scriptJquery('html, body').animate({
							scrollTop: errorFirstObject.offset().top
						 }, 2000);
						 return false;
					}else	if(validationFm)
					{
						alert('<?php echo $this->string()->escapeJavascript("Please fill the red mark fields"); ?>');
						if(typeof objectError != 'undefined'){
						 var errorFirstObject = scriptJquery(objectError).parent().parent();
						 scriptJquery('html, body').animate({
							scrollTop: errorFirstObject.offset().top
						 }, 2000);
						}
						return false;	
					}else{
						scriptJquery('#submit').attr('disabled',true);
						scriptJquery('#submit').html('<?php echo $this->translate("Submitting Form ...") ; ?>');
						return true;
					}			
	});
});

</script>
