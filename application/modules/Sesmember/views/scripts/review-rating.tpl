<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: review-rating.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $id = Zend_Controller_Front::getInstance()->getRequest()->getParam('id');?>
<?php $editPrivacy = Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'edit');?>

<div class="form-wrapper sesmember_form_rating_star">
  <div class="form-label"><label><?php echo $this->translate("Overall Rating"); ?></label></div>
  <div id="sesmember_review_rating" class="sesbasic_rating_star sesmember_rating_star_element" onmouseout="rating_out();">
    <span id="rate_1" class="sesmember_rating_star sesmember_rating_star_disable" onclick="rate(1);" onmouseout="rating_out()" onmouseover="rating_over(1);"></span>
    <span id="rate_2" class="sesmember_rating_star sesmember_rating_star_disable" onclick="rate(2);" onmouseout="rating_out()" onmouseover="rating_over(2);"></span>
    <span id="rate_3" class="sesmember_rating_star sesmember_rating_star_disable" onclick="rate(3);" onmouseout="rating_out()" onmouseover="rating_over(3);"></span>
    <span id="rate_4" class="sesmember_rating_star sesmember_rating_star_disable" onclick="rate(4);" onmouseout="rating_out()" onmouseover="rating_over(4);"></span>
    <span id="rate_5" class="sesmember_rating_star sesmember_rating_star_disable" onclick="rate(5);" onmouseout="rating_out()" onmouseover="rating_over(5);"></span>
    <span id="rating_text" class="sesbasic_rating_text"><?php echo $this->translate('click to rate');?></span>
  </div>
</div>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/tinymce/tinymce.min.js'); ?>
<script type="text/javascript">

en4.core.runonce.add(function() {

  tinymce.init({
    mode: "specific_textareas",
    editor_selector: "sesmember_review_tinymce",
    plugins: "table,fullscreen,media,preview,paste,code,image,textcolor",
    theme: "modern",
    menubar: false,
    statusbar: false,
    toolbar1: "",
    toolbar2: "",
    toolbar3: "",
    element_format: "html",
    height: "225px",
    convert_urls: false,
    language: "en",
    directionality: "ltr"
  });  

  function ratingText(rating){
    var text = '';
    if(rating == 1)
    text = "<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember_rating_stars_one',$this->translate('terrible')); ?>";
    else if(rating == 2)
    text = "<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember_rating_stars_two',$this->translate('poor')); ?>";
    else if(rating == 3)
    text = "<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember_rating_stars_three',$this->translate('average')); ?>";
    else if(rating == 4)
    text = "<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember_rating_stars_four',$this->translate('very good')); ?>";
    else if(rating == 5)
    text = "<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember_rating_stars_five',$this->translate('excellent')); ?>";
    else 
    text = "<?php echo $this->translate('click to rate');?>";
    return text;
  }
  var rating_over = window.rating_over = function(rating) {
    document.getElementById('rating_text').innerHTML = ratingText(rating);
    for(var x=1; x<=5; x++) {
      if(x <= rating) 
      scriptJquery('#rate_'+x).addClass('sesmember_rating_star');
      else 
      scriptJquery('#rate_'+x).addClass('sesmember_rating_star sesmember_rating_star_disable');
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
        scriptJquery('#rate_'+x).addClass('sesmember_rating_star sesmember_rating_star_disable');
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
      scriptJquery('#rate_'+x).addClass('sesmember_rating_star').removeClass('sesmember_rating_star_disable');
    }
    for(var x=parseInt(rating)+1; x<=5; x++) {
      scriptJquery('#rate_'+x).addClass('sesmember_rating_star sesmember_rating_star_disable');
    }
    document.getElementById('rating_text').innerHTML = ratingText(rating);
  }

  scriptJquery(document).ready(function() {
    var ratingCount = $('rate_value').value;
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
    scriptJquery('#sesmember_review_form input, #sesmember_review_form select,#sesmember_review_form checkbox,#sesmember_review_form textarea,#sesmember_review_form radio').each(
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
      <?php if(!empty($id)):?>
    scriptJquery(document).on('submit','#sesmember_review_form',function(e){
      var validationFm = validateForm();
      if(!scriptJquery('#rate_value').val()){
	alert('<?php echo $this->string()->escapeJavascript("Please fill the red mark fields"); ?>');
	var errorFirstObject = scriptJquery('#sesmember_review_rating').parent();
	scriptJquery('html, body').animate({
	scrollTop: errorFirstObject.offset().top
	}, 2000);
	return false;
      }
      else if(validationFm) {
	alert('<?php echo $this->string()->escapeJavascript("Please fill the red mark fields"); ?>');
	if(typeof objectError != 'undefined'){
	var errorFirstObject = scriptJquery(objectError).parent().parent();
	scriptJquery('html, body').animate({
	scrollTop: errorFirstObject.offset().top
	}, 2000);
	}
	return false;	
      }else{
	sendDataToServer(this);
	return false;	
      }			
    });
    <?php endif;?>
  });


  function sendDataToServer(object){
    //submit form
    scriptJquery('.sesbasic_loading_cont_overlay').show();
    var formData = new FormData(object);
    formData.append('is_ajax', 1);
    formData.append('user_id', '<?php echo $this->viewer_id;?>');
    var form = scriptJquery(object);
    var url = scriptJquery('#sesmember_review_form').attr('action');
      var d = new Date();
      d.setMonth(d.getMonth()+1);
      document.cookie="data="+JSON.stringify(scriptJquery('#sesmember_review_form').serialize())+"; expires="+d+"; path=/";
    scriptJquery.ajax({
      type:'POST',
      dataType:'html',
      url: url,
      data:formData,
      cache:false,
      contentType: false,
      processData: false,
      success:function(response){
        scriptJquery('body').append('<div id="sesmember_review_content" style="display:none;"></div>');
        scriptJquery('#sesmember_review_content').html(response);
        var reviewHtml = scriptJquery('#sesmember_review_content').find('.sesmember_reviews').html();
        //update cover rating
				if(scriptJquery('.sesmember_cover_rating').length){
					pre_rate_cover = scriptJquery('#sesmember_review_content').find('.rating_params').find('#total_rating_average').val();
					var ratingtext = scriptJquery('#sesmember_review_content').find('.rating_params').find('#rating_text').val();
					window.set_rating_cover(ratingtext);
				}

        if(scriptJquery('.sesmember_owner_review').length > 0) {
          var updatedHtmlQuery = scriptJquery('ul.sesmember_review_listing li.sesmember_owner_review').index();
          scriptJquery('.sesmember_review_listing').children().eq(updatedHtmlQuery).html(reviewHtml);
        }
        else if(!scriptJquery('#sesmember_review_rate').length){
	 			 scriptJquery('.sesmember_review_listing').prepend('<li class="sesbasic_clearfix sesmember_owner_review">'+reviewHtml+'</li>');
        }
        var editPrivacy = '<?php echo $editPrivacy;?>';
        if(editPrivacy == 1) {
					var editFormHtml = scriptJquery('#sesmember_review_content').find('#sesmember_review_create_form').html();
					scriptJquery('#sesmember_review_create_form').first().html(editFormHtml);
					scriptJquery('#sesmember_create_button').hide();
					scriptJquery('#sesmember_edit_button').show();
        }
        else
        	scriptJquery('#sesmember_create_button').hide();

        scriptJquery('#sesmember_review_content').remove();
        scriptJquery('#sesmember_review_create_form').hide();
				scriptJquery('.sesmember_review_listing ').show();
				scriptJquery('.sesmember_review_listing').parent().find('.tip').hide();
				scriptJquery('.sesbasic_loading_cont_overlay').hide();
				var openObject = scriptJquery('.sesmember_review_profile_btn');
				scriptJquery('html, body').animate({
					scrollTop: openObject.offset().top
				}, 2000);
				en4.core.runonce.trigger();
      },
      error: function(data){
      //silence
      }
    });
  }
</script>
