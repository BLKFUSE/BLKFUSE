<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: edit.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<style>
.tag img{
	float:left;
	height:25px;
	width:25px;
}
</style>
<?php 
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . "externals/selectize/css/normalize.css");
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/selectize/js/selectize.js'); 
?>
 <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/moment.js'); ?>
    <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/moment-timezone.js'); ?>
     <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/moment-timezone-with-data.js'); ?>


<?php if(!$this->is_ajax){ ?>
<?php
echo $this->partial('dashboard/left-bar.tpl', 'sescontest', array(
	'contest' => $this->contest,
      ));	
?>
	<div class="sesbasic_dashboard_content sesbm sesbasic_clearfix">
<?php } 
		echo $this->partial('dashboard/contest_expire.tpl', 'sescontest', array(
		'contest' => $this->contest,
		));	
		?>
    <div class="sesbasic_dashboard_form">
			<?php echo $this->form->render() ?>
    </div>
		<?php if(!$this->is_ajax){ ?>
	</div>
</div>
</div>
<?php  } ?>
<script type="text/javascript">
  //trim last -
  function removeLastMinus (myUrl){
    if (myUrl.substring(myUrl.length-1) == "-"){
      myUrl = myUrl.substring(0, myUrl.length-1);
    }
    return myUrl;
  }
  //function ckeck url availability
  scriptJquery("#custom_url").blur(function(){
    validUrl = false;
    scriptJquery('#check_custom_url_availability').trigger('click');
  });
  scriptJquery('#check_custom_url_availability').click(function(){
    var custom_url_value = scriptJquery('#custom_url').val();
    if(!custom_url_value)return;
    scriptJquery.post('<?php echo $this->url(array('controller' => 'ajax','module'=>'sescontest', 'action' => 'custom-url-check'), 'default', true) ?>',{value:custom_url_value,contest_id:<?php echo $this->contest->contest_id ?>},function(response){
      response = scriptJquery.parseJSON(response);
      if(response.error){
         scriptJquery('#custom_url').css('border-color','red');
      }else{
         scriptJquery('#custom_url').css('border-color','green');
      }
   });
  });
//tags

  en4.core.runonce.add(function()
  {
    var lastTwoDigit = sesBasicAutoScroll('#sescontest_start_time').val().slice('-2');
    var sesContestStartDate =  new Date(sesBasicAutoScroll('#sescontest_start_date').val()+' '+sesBasicAutoScroll('#sescontest_start_time').val().replace(lastTwoDigit,'')+':00 '+lastTwoDigit);
    var format = 'YYYY/MM/DD HH:mm:ss';
    var currentTime =  new Date();
    currentTime = moment(currentTime, format).tz(scriptJquery('#contest_timezone_jq').val()).format(format);
    currentTime =  new Date(currentTime);    
    if((sesContestStartDate.valueOf()) < currentTime.valueOf()) {
      scriptJquery("#timezone_setting_contest").off('click');
      scriptJquery('#timezone_setting_contest').addClass("sesbasic_linkinherit");
      scriptJquery(document).on('mouseover','#timezone_setting_contest',function(event) {
        en4.core.showError("<?php echo $this->translate('You can not edit the Timezone of contest after its starting.');?>");
      });
    }
    if(scriptJquery('#editor_type') && scriptJquery('#contest_type option:selected').val() == '1')
    scriptJquery('#editor_type-wrapper').show();
    else
    scriptJquery('#editor_type-wrapper').hide();

    if(scriptJquery('#sescontest_announcement_date') && scriptJquery('#vote_type').val() == '1')
    scriptJquery('#sescontest_announcement_date').show();
    else
    scriptJquery('#sescontest_announcement_date').hide();
    
    scriptJquery('#tags').selectize({
      maxItems: 10,
      valueField: 'label',
      labelField: 'label',
      searchField: 'label',
      create: true,
      load: function(query, callback) {
        if (!query.length) return callback();
        scriptJquery.ajax({
          url: '<?php echo $this->url(array('controller' => 'tag', 'action' => 'suggest'), 'default', true) ?>',
          data: { value: query },
          success: function (transformed) {
            callback(transformed);
          },
          error: function () {
              callback([]);
          }
        });
      }
    });
  });
//custom term and condition
function customTermAndCondition(){
	if(scriptJquery("#is_custom_term_condition").is(':checked'))
    scriptJquery("#custom_term_condition-wrapper").show();  // checked
	else
    scriptJquery("#custom_term_condition-wrapper").hide();  // unchecked
}
scriptJquery('#is_custom_term_condition').bind('change', function () {
	customTermAndCondition();
});
customTermAndCondition();

scriptJquery(document).ready(function(){
	scriptJquery('#subcat_id-wrapper').css('display' , 'none');
	scriptJquery('#subsubcat_id-wrapper').css('display' , 'none');
	//map
mapLoad_contest = false;
if(scriptJquery('#lat-wrapper').length > 0){
	scriptJquery('#lat-wrapper').css('display' , 'none');
	scriptJquery('#lng-wrapper').css('display' , 'none');
	initializeSesContestMapList();
}
});
</script>

<?php 
$defaultProfileFieldId = "0_0_$this->defaultProfileId";
$profile_type = 2;
?>
<?php echo $this->partial('_customFields.tpl', 'sesbasic', array()); ?>
<script type="text/javascript">

  var defaultProfileFieldId = '<?php echo $defaultProfileFieldId ?>';
  var profile_type = '<?php echo $profile_type ?>';
  var previous_mapped_level = 0;
  function showFields(cat_value, cat_level,typed,isLoad) {
	if(isLoad == 'custom'){
		var type = typed;
	}else{
		var categoryId = getProfileType(document.getElementById('category_id').value);
		var subcatId = getProfileType(document.getElementById('subcat_id').value);
		var subsubcatId = getProfileType(document.getElementById('subsubcat_id').value);
		var type = categoryId+','+subcatId+','+subsubcatId;
	}
    if (cat_level == 1 || (previous_mapped_level >= cat_level && previous_mapped_level != 1) || (profile_type == null || profile_type == '' || profile_type == 0)) {
      profile_type = getProfileType(cat_value);
      if (profile_type == 0) {
        profile_type = '';
      } else {
        previous_mapped_level = cat_level;
      }
      document.getElementById(defaultProfileFieldId).value = profile_type;
      changeFields(document.getElementById(defaultProfileFieldId),null,isLoad,type);
    }
  }
  var getProfileType = function(category_id) {
    var mapping = <?php echo Zend_Json_Encoder::encode(Engine_Api::_()->getDbTable('categories', 'sescontest')->getMapping(array('category_id', 'profile_type'))); ?>;
		  for (i = 0; i < mapping.length; i++) {	
      	if (mapping[i].category_id == category_id)
        return mapping[i].profile_type;
    	}
    return 0;
  }
  en4.core.runonce.add(function() {
    var defaultProfileId = '<?php echo '0_0_' . $this->defaultProfileId ?>' + '-wrapper';
            if ($type(document.getElementById(defaultProfileId)) && typeof document.getElementById(defaultProfileId) != 'undefined') {
      scriptJquery('#'+defaultProfileId).css('display', 'none');
    }
  });
  function showSubCategory(cat_id,selectedId,isLoad) {
		var selected;
		if(selectedId != ''){
			var selected = selectedId;
		}
    var url = en4.core.baseUrl + 'sescontest/ajax/subcategory/category_id/' + cat_id;
    scriptJquery.ajax({
			method:'post',
      url: url,
      data: {
				'selected':selected
      },
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
			if(isLoad != 'yes')
				showFields(cat_id,1);
      }
    }); 
  }
	function showSubSubCategory(cat_id,selectedId,isLoad) {
		var categoryId = getProfileType(document.getElementById('category_id').value);
		if(cat_id == 0){
			if (document.getElementById('subsubcat_id-wrapper')) {
				document.getElementById('subsubcat_id-wrapper').style.display = "none";
				document.getElementById('subsubcat_id').innerHTML = '';
				document.getElementsByName("0_0_1")[0].value=categoryId;				
      }
		if(isLoad != 'yes')
			showFields(cat_id,1,categoryId);
			return false;
		}
	if(isLoad != 'yes')
		showFields(cat_id,1,categoryId);
		var selected;
		if(selectedId != ''){
			var selected = selectedId;
		}
    var url = en4.core.baseUrl + 'sescontest/ajax/subsubcategory/subcategory_id/' + cat_id;
    (scriptJquery.ajax({
			method:'post',
      url: url,
      data: {
				'selected':selected
      },
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
	function showCustom(value,isLoad){
		var categoryId = getProfileType(document.getElementById('category_id').value);
		var subcatId = getProfileType(document.getElementById('subcat_id').value);
		var id = categoryId+','+subcatId;
		if(isLoad != 'yes')
			showFields(value,1,id,isLoad);
		if(value == 0)
			document.getElementsByName("0_0_1")[0].value=subcatId;	
			return false;
	}
	
	function showCustomOnLoad(value,isLoad){
	 <?php if(isset($this->category_id) && $this->category_id != 0){ ?>
		var categoryId = getProfileType(<?php echo $this->category_id; ?>)+',';
		<?php }else{ ?>
		var categoryId = '0';
		<?php } ?>
		<?php if(isset($this->subcat_id) && $this->subcat_id != 0){ ?>
		var subcatId = getProfileType(<?php echo $this->subcat_id; ?>)+',';
		<?php  }else{ ?>
		var subcatId = '0';
		<?php } ?>
		<?php if(isset($this->subsubcat_id) && $this->subsubcat_id != 0){ ?>
		var subsubcat_id = getProfileType(<?php echo $this->subsubcat_id; ?>)+',';
		<?php  }else{ ?>
		var subsubcat_id = '0';
		<?php } ?>
		var id = (categoryId+subcatId+subsubcat_id).replace(/,+$/g,"");;
			showFields(value,1,id,'custom');
		if(value == 0)
			document.getElementsByName("0_0_1")[0].value=subcatId;	
			return false;
		
	}
  scriptJquery(document).ready(function() {
	scriptJquery('#host-element').find('select').val(0);
	var sesdevelopment = 1;
	<?php if(isset($this->category_id) && $this->category_id != 0){ ?>
			<?php if(isset($this->subcat_id)){$catId = $this->subcat_id;}else $catId = ''; ?>
      showSubCategory('<?php echo $this->category_id; ?>','<?php echo $catId; ?>','yes');
   <?php  }else{ ?>
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
	 		showCustomOnLoad('','no');
  });
//validate form
//Ajax error show before form submit
var error = false;
var objectError ;
var counter = 0;
function validateForm(){
		var errorPresent = false;
		scriptJquery('#sescontest_create_form input, #sescontest_create_form select,#sescontest_create_form checkbox,#sescontest_create_form textarea,#sescontest_create_form radio').each(
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
								if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
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
							}else{
								
							}
							if(error)
								errorPresent = true;
							error = false;
						}
				}
			);
				
			return errorPresent ;
}
scriptJquery('#sescontest_create_form').submit(function(e){
					var validationFm = validateForm();
					if(validationFm)
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
                      <?php if(strtotime($this->contest->starttime) > time()):?>
                        var showErrorMessage = checkAllDateFields();
                        if(showErrorMessage != ''){
                          scriptJquery('#contest_error_time-wrapper').show();
                          scriptJquery('#contest_error_time-element').text(showErrorMessage);
                          var errorFirstObject = scriptJquery('.sescontest_choose_date');
                          scriptJquery('html, body').animate({scrollTop: errorFirstObject.offset().top}, 2000);
                          return false;
                        }else{
                          scriptJquery('#contest_error_time-wrapper').hide();
                        }
                      <?php endif;?>
						scriptJquery('#submit').attr('disabled',true);
						scriptJquery('#submit').html('<?php echo $this->translate("Saving Form ...") ; ?>');
						return true;
					}			
	});
      function showEditorOption(value) {
    if(value == '1')
    scriptJquery('#editor_type-wrapper').show();
    else
    scriptJquery('#editor_type-wrapper').hide();
  }
  function showResultDate(value) {
    if(value == '1')
    scriptJquery('#sescontest_announcement_date').show();
    else
    scriptJquery('#sescontest_announcement_date').hide();
  }
  function showPreview(value) {
    if(value == 1)
    en4.core.showError('<a class="icon_close" onclick="parent.Smoothbox.close();"><i class="fa fa-times"></i></a> <p class="popup_design_title">'+en4.core.language.translate("Design 1")+'</p><img class="popup_img" src="./application/modules/Sescontest/externals/images/layout_1.jpg" alt="" />');
    else if(value == 2)
    en4.core.showError('<a class="icon_close" onclick="parent.Smoothbox.close();"><i class="fa fa-times"></i></a> <p class="popup_design_title">'+en4.core.language.translate("Design 2")+'</p><img src="./application/modules/Sescontest/externals/images/layout_2.jpg" alt="" />');
    else if(value == 3)
    en4.core.showError('<a class="icon_close" onclick="parent.Smoothbox.close();"><i class="fa fa-times"></i></a> <p class="popup_design_title">'+en4.core.language.translate("Design 3")+'</p><img src="./application/modules/Sescontest/externals/images/layout_3.jpg" alt="" />');
    else if(value == 4)
    en4.core.showError('<a class="icon_close" onclick="parent.Smoothbox.close();"><i class="fa fa-times"></i></a> <p class="popup_design_title">'+en4.core.language.translate("Design 4")+'</p><img src="./application/modules/Sescontest/externals/images/layout_4.jpg" alt="" />');
    return;
  }
</script>
