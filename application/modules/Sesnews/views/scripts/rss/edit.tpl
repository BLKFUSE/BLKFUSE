<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: edit.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); ?>

<div class="layout_middle">
 <div class="generic_layout_container">
  <div class="headline">
   <h2>
    <?php echo $this->translate('News');?>
   </h2>
   <div class="tabs">
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render();?>
   </div>
  </div>
</div>
<div class="generic_layout_container">
   <div class="sesnews_news_form"> 
     <?php echo $this->form->render(); ?>
   </div>
  </div>
</div>

<script type="application/javascript">


  function showSubCategory(cat_id,selectedId, isLoad) {
 
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
      return false;
    }
    var selected;
    if(selectedId != ''){
      var selected = selectedId;
    }
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
	      document.getElementById('subsubcat_id').innerHTML = responseHTML;
	    }					
	  }else{
	    // get category id value 						
	    if (document.getElementById('subsubcat_id-wrapper')) {
	      document.getElementById('subsubcat_id-wrapper').style.display = "none";
	      document.getElementById('subsubcat_id').innerHTML = '<option value="0"></option>';
	    } 
	  }
	
      }
    }));
  }
  
  scriptJquery(document).ready(function(e){
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
    } 
  <?php }else{ ?>
    document.getElementById('subsubcat_id-wrapper').style.display = "none";
  <?php } ?>
  });

//prevent form submit on enter
  scriptJquery("#form-upload").bind("keypress", function (e) {		
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
  function validateForm(){
    var errorPresent = false; 
    scriptJquery('#sesnews_rssedit input, #sesnews_rssedit select,#sesnews_rssedit checkbox,#sesnews_rssedit textarea,#sesnews_rssedit radio').each(
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
	    if(tinyMCE.get('body').getContent() === '' || tinyMCE.get('body').getContent() == null)
	    error = true;
	    else
	    error = false;
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
	  }
	  if(error)
	  errorPresent = true;
	  error = false;
	}
      }
    );
    return errorPresent ;
  }
  scriptJquery('#sesnews_rssedit').submit(function(e){
    var validationFm = validateForm();
    if(validationFm) {
      alert('<?php echo $this->string()->escapeJavascript("Please fill the red mark fields"); ?>');
      if(typeof objectError != 'undefined'){
	var errorFirstObject = scriptJquery(objectError).parent().parent();
	scriptJquery('html, body').animate({
	scrollTop: errorFirstObject.offset().top
	}, 2000);
      }
      return false;	
    }
    else{
      scriptJquery('#upload').attr('disabled',true);
      scriptJquery('#upload').html('<?php echo $this->translate("Submitting Form ...") ; ?>');
      return true;
    }			
  });
  
  function showStartDate(value) {
    if(value == '1')
    scriptJquery('#event_start_time-wrapper').hide();
    else
    scriptJquery('#event_start_time-wrapper').show();
  }
</script>

<script type="text/javascript">
  scriptJquery('.core_main_sesnews').parent().addClass('active');
</script>
