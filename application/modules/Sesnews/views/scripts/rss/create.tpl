<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: create.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
  <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); ?>
  
  <?php  ?>
  
<?php if (($this->current_count >= $this->quota) && !empty($this->quota)):?>
  <div class="tip">
    <span>
      <?php echo $this->translate('You have already created the maximum number of entries allowed.');?>
      <?php echo $this->translate('If you would like to create a new rss, please <a href="%1$s">delete</a> an old one first.', $this->url(array('action' => 'manage'), 'sesnews_generalrss'));?>
    </span>
  </div>
  <br/>
<?php else:?>
  <?php echo $this->form->render($this);?>
<?php endif; ?>
<script type="text/javascript">
  scriptJquery('.core_main_sesnews').parent().addClass('active');
</script>
<?php $required = false; ?>
<script type="text/javascript">
  
  function checkURLValid(urlsubmit) { 
    
    if(urlsubmit == '') 
      return;
    scriptJquery('#sesnews_custom_url_wrong').hide();
    scriptJquery('#sesnews_custom_url_correct').hide();
    scriptJquery('#custom_url_news-wrapper').show();
    scriptJquery('#sesnews_custom_url_loading').css('display','inline-block');
    var url = en4.core.baseUrl + 'sesnews/rss/checkurl/';
    scriptJquery.ajax({
      dataType: 'json',
      format : 'json',
      url: url,
      data: {'urlsubmit':urlsubmit},
      success: function(responseJSON) {
        if(responseJSON.status == 'true') {
          scriptJquery('#sesnews_custom_url_loading').hide();
          scriptJquery('#sesnews_custom_url_wrong').hide();
          scriptJquery('#sesnews_custom_url_correct').css('display','inline-block');
          scriptJquery('#title').val(responseJSON.title);
          scriptJquery('#body').val(responseJSON.description);
          scriptJquery('#submit-wrapper').show();
        } else {
          scriptJquery('#sesnews_custom_url_loading').hide();
          scriptJquery('#sesnews_custom_url_wrong').css('display','inline-block')
          scriptJquery('#sesnews_custom_url_correct').hide();
          scriptJquery('#submit-wrapper').hide();
        }
      }
    }); 
    
  }
  
  function showSubCategory(cat_id,selectedId) {
    var selected;
    if(selectedId != '')
    var selected = selectedId;
    var url = en4.core.baseUrl + 'sesnews/index/subcategory/category_id/' + cat_id;
    scriptJquery.ajax({
    method: 'post',
      dataType: 'html',
      url: url,
      data: {'selected':selected},
      success: function(responseHTML) {

	  if (document.getElementById('subcat_id') && responseHTML) {
	    if (document.getElementById('subcat_id-wrapper')) {
	      document.getElementById('subcat_id-wrapper').style.display = "block";
	    }
	    document.getElementById('subcat_id').innerHTML = responseHTML;
	  } else {
	    if (document.getElementById('subcat_id-wrapper')) {
	      document.getElementById('subcat_id-wrapper').style.display = "none";
	      document.getElementById('subcat_id').innerHTML = '<option value="0"></option>';
	    }
	  }
	  if (document.getElementById('subsubcat_id-wrapper')) {
	    document.getElementById('subsubcat_id-wrapper').style.display = "none";
	    document.getElementById('subsubcat_id').innerHTML = '<option value="0"></option>';
	  }
      }
    }); 
  }
  
  function showSubSubCategory(cat_id,selectedId,isLoad) {
    if(cat_id == 0){
      if (document.getElementById('subsubcat_id-wrapper')) {
        document.getElementById('subsubcat_id-wrapper').style.display = "none";
        document.getElementById('subsubcat_id').innerHTML = '';				
      }
      return false;
    }

    var selected;
    if(selectedId != '')
    var selected = selectedId;
    var url = en4.core.baseUrl + 'sesnews/index/subsubcategory/subcategory_id/' + cat_id;
    (scriptJquery.ajax({
    method: 'post',
      dataType: 'html',
      url: url,
      data: {'selected':selected},
      success: function(responseHTML) {

        if (document.getElementById('subsubcat_id') && responseHTML) {
          if (document.getElementById('subsubcat_id-wrapper')) {
            document.getElementById('subsubcat_id-wrapper').style.display = "block";
          }
          document.getElementById('subsubcat_id').innerHTML = responseHTML;
        } else {
          if (document.getElementById('subsubcat_id-wrapper')) {
            document.getElementById('subsubcat_id-wrapper').style.display = "none";
            document.getElementById('subsubcat_id').innerHTML = '<option value="0"></option>';
          }
        }
      }
    }));  
  }
    
  function showStartDate(value) {
    if(value == '1')
    scriptJquery('#event_start_time-wrapper').hide();
    else
    scriptJquery('#event_start_time-wrapper').show();
  }
  
  en4.core.runonce.add(function() {
    
    scriptJquery('#custom_url_news-wrapper').hide();
    
    if(scriptJquery('#show_start_time') && scriptJquery('input[name="show_start_time"]:checked').val() == '1')
    scriptJquery('#event_start_time-wrapper').hide();
    
    scriptJquery('#submit_check-wrapper').hide();
    
    var sesdevelopment = 1;
    <?php if(isset($this->category_id) && $this->category_id != 0){ ?>
        <?php if(isset($this->subcat_id)){$catId = $this->subcat_id;}else $catId = ''; ?>
        showSubCategory('<?php echo $this->category_id; ?>','<?php echo $catId; ?>','yes');
     <?php  }else{ ?>
      if(document.getElementById('subcat_id-wrapper'))
        document.getElementById('subcat_id-wrapper').style.display = "none";
     <?php } ?>
   
     <?php if(isset($this->subsubcat_id)){ ?>
      if (<?php echo isset($this->subcat_id) && intval($this->subcat_id)>0 ? $this->subcat_id : 'sesdevelopment' ?> == 0) {
       document.getElementById('subsubcat_id-wrapper').style.display = "none";
      } else {
        <?php if(isset($this->subsubcat_id)){$subsubcat_id = $this->subsubcat_id;}else $subsubcat_id = ''; ?>
        showSubSubCategory('<?php echo $this->subcat_id; ?>','<?php echo $this->subsubcat_id; ?>','yes');
      }
     <?php }else{ ?>
         document.getElementById('subsubcat_id-wrapper').style.display = "none";
     <?php } ?>
  });
  
  //prevent form submit on enter
  scriptJquery("#sesnews_create_rss").bind("keypress", function (e) {
    if (e.keyCode == 13 && scriptJquery('#'+e.target.id).prop('tagName') != 'TEXTAREA') {
      e.preventDefault();
    }else{
      return true;	
    }
  });
  
  //Ajax error show before form submit
  var error = false;
  var objectError ;
  var counter = 0;
  function validateForm() {
    var errorPresent = false; 
    scriptJquery('#sesnews_create_rss input, #sesnews_create_rss select,#sesnews_create_rss checkbox,#sesnews_create_rss textarea,#sesnews_create_rss radio').each(
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
	  }
	  else if(scriptJquery(this).prop('type') == 'select-multiple'){
	    if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
	    error = true;
	    else
	    error = false;
	  }
	  else if(scriptJquery(this).prop('type') == 'select-one' || scriptJquery(this).prop('type') == 'select' ){
	    if(scriptJquery(this).val() === '')
	    error = true;
	    else
	    error = false;
	  }
	  else if(scriptJquery(this).prop('type') == 'radio'){
	    if(scriptJquery("input[name='"+scriptJquery(this).attr('name').replace('[]','')+"']:checked").val() === '')
	    error = true;
	    else
	    error = false;
	  }
	  else if(scriptJquery(this).prop('type') == 'textarea' && scriptJquery(this).prop('id') == 'body'){
	   
	  }
	  else if(scriptJquery(this).prop('type') == 'textarea') {
	    if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
	    error = true;
	    else
	    error = false;
	  }
	  else{
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
	  else{
			if(scriptJquery('#tabs_form_newscreate-wrapper').length && scriptJquery('.sesnews_upload_item_photo').length == 0){
				<?php if($required):?>
					objectError = scriptJquery('.sesnews_create_rss_form_tabs');
					error = true;
				<?php endif;?>
			}		
	  }
	  if(error)
			errorPresent = true;
			error = false;
		}
      }
    );
    return errorPresent ;
  }
  
en4.core.runonce.add(function() {
  scriptJquery('#sesnews_create_rss').submit(function(e) {
    var validationFm = validateForm();
//     if(!validationFm) {
// 			var lastTwoDigitStart = scriptJquery('#sesnews_schedule_time').val().slice('-2');
// 			var startDate = new Date(scriptJquery('#sesnews_schedule_date').val()+' '+scriptJquery('#sesnews_schedule_time').val().replace(lastTwoDigitStart,'')+':00 '+lastTwoDigitStart);
// 			var error = checkDateTime(startDate);
// 			if(error != ''){
// 				scriptJquery('#event_error_time-wrapper').show();
// 				scriptJquery('#sesnews_schedule_error_time-element').text(error);
// 			 var errorFirstObject = scriptJquery('#event_start_time-label').parent().parent();
// 			 scriptJquery('html, body').animate({
// 				scrollTop: errorFirstObject.offset().top
// 			 }, 2000);
// 				return false;
// 			}else{
// 				scriptJquery('#event_error_time-wrapper').hide();
// 			}	
// 			
// 		}
    if(validationFm) {
      alert('<?php echo $this->string()->escapeJavascript("Please fill the red mark fields"); ?>');
      if(typeof objectError != 'undefined'){
				var errorFirstObject = scriptJquery(objectError).parent().parent();
				scriptJquery('html, body').animate({
				scrollTop: errorFirstObject.offset().top
				}, 2000);
      }
      return false;	
    }else if(scriptJquery('.sesnews_upload_item_abort').length){
				alert('<?php echo $this->string()->escapeJavascript("Please wait till all photos uploaded."); ?>');
				var errorFirstObject = scriptJquery('#uploadFileContainer-wrapper');
				scriptJquery('html, body').animate({
					scrollTop: errorFirstObject.offset().top
				}, 2000);
				return false;
		}
//     else{
//       var avacheckAvailsbility = checkAvailsbility('true');
//       return false;
//     }
  });
});
</script>
