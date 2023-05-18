<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: edit.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css');?>
<?php 
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . "externals/selectize/css/normalize.css");
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/selectize/js/selectize.js'); 
?>
<?php 
$defaultProfileFieldId = "0_0_$this->defaultProfileId";
$profile_type = 2;
?>
<?php echo $this->partial('_customFields.tpl', 'sesbasic', array()); ?>
	<div class="sesvideo_video_form sesbasic_bxs"> 
		<?php echo $this->form->render(); ?>
	</div>
<script type="application/javascript">
  en4.core.runonce.add(function() {
    var tagsUrl = '<?php echo $this->url(array('controller' => 'tag', 'action' => 'suggest'), 'default', true) ?>';
    scriptJquery('#tags').selectize({
      maxItems: 10,
      valueField: 'label',
      labelField: 'label',
      searchField: 'label',
      create: true,
      load: function(query, callback) {
        if (!query.length) return callback();
        scriptJquery.ajax({
          url: tagsUrl,
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
		
<?php 
$optionsenableglotion = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('optionsenableglotion',''));
if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo_enable_location', 1)){ ?>
  scriptJquery(document).ready(function(){
    <?php if(!empty($optionsenableglotion) && !engine_in_array('lat', $optionsenableglotion)) { ?>
      scriptJquery('#lat-wrapper').css('display' , 'none');
    <?php } ?>
    <?php if(!empty($optionsenableglotion) && !engine_in_array('lng', $optionsenableglotion)) { ?>
      scriptJquery('#lng-wrapper').css('display' , 'none');
    <?php } ?>
    <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
      scriptJquery('#lat-wrapper').css('display' , 'none');
      scriptJquery('#lng-wrapper').css('display' , 'none');
      scriptJquery('#mapcanvas-element').attr('id','map-canvas');
      scriptJquery('#map-canvas').css('height','200px');
      scriptJquery('#map-canvas').css('width','500px');
      scriptJquery('#mapcanvas-wrapper').hide();
      scriptJquery('#ses_location-label').attr('id','ses_location_data_list');
      scriptJquery('#ses_location_data_list').html("<?php echo isset($_POST['location']) ? $_POST['location'] : '' ; ?>");
      scriptJquery('#ses_location-wrapper').css('display','none');
      initializeSesVideoMap();
    <?php } else { ?>
      scriptJquery('#mapcanvas-wrapper').hide();
      scriptJquery('#ses_location-wrapper').css('display','none');
    <?php } ?>
  });
  scriptJquery( window ).load(function() {
    editMarkerOnMapVideoEdit();
	});
<?php } ?>

function enablePasswordFiled(value){
	if(value == 0){
		document.getElementById('password-wrapper').style.display = 'none';	
    scriptJquery('#password').val('');
	}else{
		document.getElementById('password-wrapper').style.display = 'block';		
	}
}

if(document.getElementById('password-wrapper') && scriptJquery('#is_locked').val() == 0){
	document.getElementById('password-wrapper').style.display = 'none';	
  scriptJquery('#password').val('');
}
else if(document.getElementById('password-wrapper')){
	document.getElementById('password-wrapper').style.display = 'block';	
	scriptJquery('#password').val('<?php echo $this->video->password; ?>');
}
</script>
<script type="application/javascript">
  var defaultProfileFieldId = '<?php echo $defaultProfileFieldId ?>';
  var profile_type = '<?php echo $profile_type ?>';
  var previous_mapped_level = 0;
   function showFields(cat_value, cat_level,type,isLoad,resets) {
    if(typeof type == 'undefined'){
       type = getProfileType(scriptJquery('#category_id').val())+','+getProfileType(scriptJquery('#subcat_id').val())+','+getProfileType(scriptJquery('#subsubcat_id').val());
    }
    if (cat_level == 1 || (previous_mapped_level >= cat_level && previous_mapped_level != 1) || (profile_type == null || profile_type == '' || profile_type == 0)) {
      profile_type = getProfileType(cat_value);
      if (profile_type == 0) {
        profile_type = '';
      } else {
        previous_mapped_level = cat_level;
      }
      document.getElementById(defaultProfileFieldId).value = profile_type;
      changeFields(document.getElementById(defaultProfileFieldId),null,isLoad,type,resets);
    }
  }
  var getProfileType = function(category_id) {
    var mapping = <?php echo Zend_Json_Encoder::encode(Engine_Api::_()->getDbTable('categories', 'sesvideo')->getMapping(array('category_id', 'profile_type'))); ?>;
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
  function showSubCategory(cat_id,selectedId) {
		var selected;
		if(selectedId != ''){
			var selected = selectedId;
		}
    var url = en4.core.baseUrl + 'sesvideo/index/subcategory/category_id/' + cat_id;
    scriptJquery.ajax({
    method: 'post',
      dataType: 'html',
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
            document.getElementById('subcat_id').innerHTML = '';
          }
					 if (document.getElementById('subsubcat_id-wrapper')) {
            document.getElementById('subsubcat_id-wrapper').style.display = "none";
            document.getElementById('subsubcat_id').innerHTML = '';
          }
        }
      }
    }); 
  }
	function showSubSubCategory(cat_id,selectedId,isLoad) {
		var categoryId = getProfileType(document.getElementById('category_id').value);
		if(cat_id == 0){
			showFields(categoryId,1,categoryId);
			if (document.getElementById('subsubcat_id-wrapper')) {
				document.getElementById('subsubcat_id-wrapper').style.display = "none";
				document.getElementById('subsubcat_id').innerHTML = '';
				document.getElementsByName("0_0_1")[0].value=categoryId;				
      }	
			return false;
		}
		var selected;
		if(selectedId != ''){
			var selected = selectedId;
		}
    var url = en4.core.baseUrl + 'sesvideo/index/subsubcategory/subcategory_id/' + cat_id;
    (scriptJquery.ajax({
    method: 'post',
      dataType: 'html',
      url: url,
      data: {
				'selected':selected
      },
      success: function(responseHTML) {
        if (document.getElementById('subsubcat_id') && responseHTML) {
          if (document.getElementById('subsubcat_id-wrapper')) {
            document.getElementById('subsubcat_id-wrapper').style.display = "block";
          }
          document.getElementById('subsubcat_id').innerHTML = responseHTML;
					// get category id value 
					if(isLoad == 'no')
						showFields(cat_id,1,categoryId,isLoad);
        } else {
          if (document.getElementById('subsubcat_id-wrapper')) {
            document.getElementById('subsubcat_id-wrapper').style.display = "none";
            document.getElementById('subsubcat_id').innerHTML = '';
          }
        }
      }
    }));
  }
	function showCustom(value,isLoad){
		var categoryId = getProfileType(document.getElementById('category_id').value);
		var subcatId = getProfileType(document.getElementById('subcat_id').value);
		var id = categoryId+','+subcatId;
			showFields(value,1,id,isLoad);
		if(value == 0)
			document.getElementsByName("0_0_1")[0].value=subcatId;	
			return false;
	}
		function showCustomOnLoad(value,isLoad){
	 <?php if(isset($this->category_id) && $this->category_id != 0){ ?>
		var categoryId = getProfileType(<?php echo $this->category_id; ?>)+',';
		<?php if(isset($this->subcat_id) && $this->subcat_id != 0){ ?>
		var subcatId = getProfileType(<?php echo $this->subcat_id; ?>)+',';
		<?php  }else{ ?>
		var subcatId = '';
		<?php } ?>
		<?php if(isset($this->subsubcat_id) && $this->subsubcat_id != 0){ ?>
		var subsubcat_id = getProfileType(<?php echo $this->subsubcat_id; ?>)+',';
		<?php  }else{ ?>
		var subsubcat_id = '';
		<?php } ?>
		var id = (categoryId+subcatId+subsubcat_id).replace(/,+$/g,"");;
			showFields(value,1,id,isLoad);
		if(value == 0)
			document.getElementsByName("0_0_1")[0].value=subcatId;	
			return false;
		<?php } ?>
	}
  scriptJquery(document).ready(function(e){
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
		scriptJquery('#form-upload input, #form-upload select,#form-upload checkbox,#form-upload textarea,#form-upload radio').each(
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
scriptJquery('#form-upload').submit(function(e){
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
						scriptJquery('#upload').attr('disabled',true);
						scriptJquery('#upload').html('<?php echo $this->translate("Submitting Form ...") ; ?>');
						return true;
					}			
	});
</script>
