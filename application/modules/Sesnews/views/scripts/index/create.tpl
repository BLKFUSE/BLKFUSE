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
<?php if(!$this->typesmoothbox){ ?>
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'application/modules/Sesnews/externals/scripts/core.js'); ?>
  <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); ?>
  
	<?php 
		$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . "externals/selectize/css/normalize.css");
		$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/selectize/js/selectize.js'); 
	?>

<?php } else { ?>
  <script type="application/javascript">
    Sessmoothbox.javascript.push("<?php echo $this->layout()->staticBaseUrl .'application/modules/Sesnews/externals/scripts/core.js'; ?>");
    Sessmoothbox.css.push("<?php echo $this->layout()->staticBaseUrl . 'externals/selectize/css/normalize.css'; ?>");
    Sessmoothbox.javascript.push("<?php echo $this->layout()->staticBaseUrl .'externals/selectize/js/selectize.js'; ?>");
    Sessmoothbox.css.push("<?php echo $this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'; ?>");
    Sessmoothbox.javascript.push("<?php echo $this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery.min.js'; ?>");
    
    Sessmoothbox.javascript.push("<?php echo $this->layout()->staticBaseUrl . 'externals/tinymce/tinymce.min.js'; ?>");

  </script>

<?php } ?>


<?php 		
$mainPhotoEnable = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.photo.mandatory', '1');
if ($mainPhotoEnable == 1) {
	$required = true; ?>
  <style type='text/css'>
  .sesnews_create #tabs_form_newscreate-label label:after{content: '*';color: #F00;}
  </style>
<?php } else {
	$required = false;
}
?>

<script type="text/javascript">

  function removeLastMinus (myUrl) {
    if (myUrl.substring(myUrl.length-1) == "-") {
      myUrl = myUrl.substring(0, myUrl.length-1);
    }
    return myUrl;
  }
  var changeTitle = true;

  en4.core.runonce.add(function() {
		
    //auto fill custom url value
    scriptJquery("#title").keyup(function(){
      var Text = scriptJquery(this).val();
      if(!changeTitle)
      return;
      Text = Text.toLowerCase();
      Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
      Text = removeLastMinus(Text);
      scriptJquery("#custom_url").val(Text);        
    });
    scriptJquery("#title").blur(function(){
      if(scriptJquery(this).val()){
        changeTitle = false;
      }
    });
  });
  
  function checkAvailsbility(submitform) {
    var custom_url_value = scriptJquery('#custom_url').val();
    if(!custom_url_value && typeof submitform == 'undefined')
    return;
    scriptJquery('#sesnews_custom_url_wrong').hide();
    scriptJquery('#sesnews_custom_url_correct').hide();
    scriptJquery('#sesnews_custom_url_loading').css('display','inline-block');
    scriptJquery.post('<?php echo $this->url(array('controller' => 'index','module'=>'sesnews', 'action' => 'custom-url-check'), 'default', true) ?>',{value:custom_url_value},function(response){
      scriptJquery('#sesnews_custom_url_loading').hide();
      response = scriptJquery.parseJSON(response);
      if(response.error){
        scriptJquery('#sesnews_custom_url_correct').hide();
        scriptJquery('#sesnews_custom_url_wrong').css('display','inline-block');
        if(typeof submitform != 'undefined') {
          alert('<?php echo $this->string()->escapeJavascript("Custom Url is not available. Please select another URL."); ?>');
          var errorFirstObject = scriptJquery('#custom_url').parent().parent();
          scriptJquery('html, body').animate({
          scrollTop: errorFirstObject.offset().top
          }, 2000);
        }
      } else{
        scriptJquery('#custom_url').val(response.value);
        scriptJquery('#sesnews_custom_url_wrong').hide();
        scriptJquery('#sesnews_custom_url_correct').css('display','inline-block');
        if(typeof submitform != 'undefined') {
          scriptJquery('#upload').attr('disabled',true);
          scriptJquery('#upload').html('<?php echo $this->translate("Submitting Form ...") ; ?>');
          scriptJquery('#submit_check').trigger('click');
        }
      }
    });
  }

  en4.core.runonce.add(function() {
  
    if(scriptJquery('#show_start_time') && scriptJquery('input[name="show_start_time"]:checked').val() == '1')
    scriptJquery('#event_start_time-wrapper').hide();
    
    scriptJquery('#submit_check-wrapper').hide();
    
    //function ckeck url availability
    scriptJquery('#check_custom_url_availability').click(function(){
      checkAvailsbility();
    });
    
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
  
  <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews_enable_location', 1)){ ?>
    en4.core.runonce.add(function() {
      scriptJquery('#lat-wrapper').css('display' , 'none');
      scriptJquery('#lng-wrapper').css('display' , 'none');
      scriptJquery('#mapcanvas-element').attr('id','map-canvas');
      scriptJquery('#map-canvas').css('height','200px');
      scriptJquery('#mapcanvas-wrapper').hide();
      scriptJquery('#map-canvas').css('width','500px');
      scriptJquery('#ses_location-label').attr('id','ses_location_data_list');
      scriptJquery('#ses_location_data_list').html("<?php echo isset($_POST['location']) ? $_POST['location'] : '' ; ?>");
      scriptJquery('#ses_location-wrapper').css('display','none');
      initializeSesNewsMap();	
    });
  <?php } ?>
</script>

<?php if (($this->current_count >= $this->quota) && !empty($this->quota)):?>
  <div class="tip">
    <span>
      <?php echo $this->translate('You have already uploaded the maximum number of entries allowed.');?>
      <?php echo $this->translate('If you would like to upload a new entry, please <a href="%1$s">delete</a> an old one first.', $this->url(array('action' => 'manage'), 'sesnews_general'));?>
    </span>
  </div>
  <br/>
<?php else:?>
<div class="sesnews_create sesbasic_bxs">
  <?php echo $this->form->render($this);?></div>
<?php endif; ?>

<script type="text/javascript">
  scriptJquery('.core_main_sesnews').parent().addClass('active');
</script>

<?php 
$defaultProfileFieldId = "0_0_$this->defaultProfileId";
$profile_type = 2;
?>

<?php echo $this->partial('_customFields.tpl', 'sesbasic', array()); ?>

<script type="application/javascript">
scriptJquery('#rotation-wrapper').hide();
scriptJquery('#embedUrl-wrapper').hide();
function enablePasswordFiled(value) {
  if(value == 0)
  scriptJquery('#password-wrapper').hide();
  else
  scriptJquery('#password-wrapper').show();		
}
scriptJquery('#password-wrapper').hide();	
</script>

<script type="text/javascript">

  var defaultProfileFieldId = '<?php echo $defaultProfileFieldId ?>';
  var profile_type = '<?php echo $profile_type ?>';
  var previous_mapped_level = 0;
  function showFields(cat_value, cat_level,typed,isLoad) {
		if(scriptJquery('#custom_fields_enable').length > 0)
			return;
    var categoryId = getProfileType(document.getElementById('category_id').value);
    var subcatId = getProfileType(document.getElementById('subcat_id').value);
    var subsubcatId = getProfileType(document.getElementById('subsubcat_id').value);
    var type = categoryId+','+subcatId+','+subsubcatId;
    if (cat_level == 1 || (previous_mapped_level >= cat_level && previous_mapped_level != 1) || (profile_type == null || profile_type == '' || profile_type == 0)) {
      profile_type = getProfileType(cat_value);
      if (profile_type == 0)
      profile_type = '';
      else
      previous_mapped_level = cat_level;
      document.getElementById(defaultProfileFieldId).value = profile_type;
      changeFields(document.getElementById(defaultProfileFieldId),null,isLoad,type);
    }
  }
  
  var getProfileType = function(category_id) {
    var mapping = <?php echo Zend_Json_Encoder::encode(Engine_Api::_()->getDbTable('categories', 'sesnews')->getMapping(array('category_id', 'profile_type'))); ?>;
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
        if(typeof document.getElementsByName("0_0_1")[0] != 'undefined')
        document.getElementsByName("0_0_1")[0].value=categoryId;				
      }
      
      showFields(cat_id,1,categoryId);
      return false;
    }
    
    showFields(cat_id,1,categoryId);
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
          // get category id value 
          if(isLoad == 'no')
          showFields(cat_id,1,categoryId,isLoad);
        } else {
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
    showFields(value,1,id,isLoad);
    if(value == 0 && typeof document.getElementsByName("0_0_1")[0] != 'undefined')
    document.getElementsByName("0_0_1")[0].value=subcatId;	
    return false;
  }

  function showCustomOnLoad(value,isLoad) {
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
    if(value == 0 && typeof document.getElementsByName("0_0_1")[0] != 'undefined')
    document.getElementsByName("0_0_1")[0].value=subcatId;	
    return false;
    <?php } ?>
  }
  
  en4.core.runonce.add(function() {
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
        showCustomOnLoad('','no');
  });
  
  //prevent form submit on enter
  scriptJquery("#sesnews_create").bind("keypress", function (e) {
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
    scriptJquery('#sesnews_create input, #sesnews_create select,#sesnews_create checkbox,#sesnews_create textarea,#sesnews_create radio').each(
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
					objectError = scriptJquery('.sesnews_create_form_tabs');
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
  scriptJquery('#sesnews_create').submit(function(e) {
    var validationFm = validateForm();
    if(!validationFm) {
      if(scriptJquery('#sesnews_schedule_time')) {
			var lastTwoDigitStart = scriptJquery('#sesnews_schedule_time').val().slice('-2');
			var startDate = new Date(scriptJquery('#sesnews_schedule_date').val()+' '+scriptJquery('#sesnews_schedule_time').val().replace(lastTwoDigitStart,'')+':00 '+lastTwoDigitStart);
			var error = checkDateTime(startDate);
			if(error != ''){
				scriptJquery('#event_error_time-wrapper').show();
				scriptJquery('#sesnews_schedule_error_time-element').text(error);
			 var errorFirstObject = scriptJquery('#event_start_time-label').parent().parent();
			 scriptJquery('html, body').animate({
				scrollTop: errorFirstObject.offset().top
			 }, 2000);
				return false;
			}else{
				scriptJquery('#event_error_time-wrapper').hide();
			}	
			}
		}
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

<script type="text/javascript">

en4.core.runonce.add(function() {
  scriptJquery('#dragdrop-wrapper').show();
  scriptJquery('#fromurl-wrapper').hide();
  scriptJquery('#file_multi-wrapper').hide();
});

en4.core.runonce.add(function() {
  var sesnews_create_form_tabsSesnews = scriptJquery('#sesnews_create_form_tabs li a');
  sesnews_create_form_tabsSesnews.click(function() {
    scriptJquery('#dragdrop-wrapper').hide();
    scriptJquery('#fromurl-wrapper').hide();
    scriptJquery('#file_multi-wrapper').hide();
    if(scriptJquery(this).hasClass('drag_drop'))
      scriptJquery('#dragdrop-wrapper').show();
    else if(scriptJquery(this).hasClass('multi_upload')){
      document.getElementById('file_multi').click();			
    }
    else if(scriptJquery(this).hasClass('from_url')){
      document.getElementById('fromurl-wrapper').style.display = 'block'		
    }
  });
});

en4.core.runonce.add(function()
{
var obj = scriptJquery('#dragandrophandler');
obj.on('dragenter', function (e) 
{
    e.stopPropagation();
    e.preventDefault();
    scriptJquery (this).addClass("sesbd");
});
obj.on('dragover', function (e) 
{
     e.stopPropagation();
     e.preventDefault();
});
obj.on('drop', function (e) 
{
 
         scriptJquery (this).removeClass("sesbd");
         scriptJquery (this).addClass("sesbm");
     e.preventDefault();
     var files = e.originalEvent.dataTransfer.files;
     //We need to send dropped files to Server
     handleFileUpload(files,obj);
});
scriptJquery (document).on('dragenter', function (e) 
{
    e.stopPropagation();
    e.preventDefault();
});
scriptJquery (document).on('dragover', function (e) 
{
  e.stopPropagation();
  e.preventDefault();
});
	scriptJquery (document).on('drop', function (e) 
	{
			e.stopPropagation();
			e.preventDefault();
	});
});
var rowCount=0;
scriptJquery(document).on('click','div[id^="abortPhoto_"]',function(){
		var id = scriptJquery(this).attr('id').match(/\d+/)[0];
		if(typeof jqXHR[id] != 'undefined'){
				jqXHR[id].abort();
				delete filesArray[id];	
				execute = true;
				scriptJquery(this).parent().remove();
				executeupload();
		}else{
				delete filesArray[id];	
				scriptJquery(this).parent().remove();
		}
});
function createStatusbar(obj,file)
{
     rowCount++;
     var row="odd";
     if(rowCount %2 ==0) row ="even";
		  var checkedId = scriptJquery("input[name=cover]:checked");
			this.objectInsert = scriptJquery('<div class="sesnews_upload_item sesbm '+row+'"></div>');
			this.overlay = scriptJquery("<div class='overlay sesnews_upload_item_overlay'></div>").appendTo(this.objectInsert);
			this.abort = scriptJquery('<div class="abort sesnews_upload_item_abort" id="abortPhoto_'+countUploadSes+'"><span><?php echo $this->translate("Cancel Uploading"); ?></span></div>').appendTo(this.objectInsert);
			this.progressBar = scriptJquery('<div class="overlay_image progressBar"><div></div></div>').appendTo(this.objectInsert);
			this.imageContainer = scriptJquery('<div class="sesnews_upload_item_photo"></div>').appendTo(this.objectInsert);
			this.src = scriptJquery('<img src="'+en4.core.baseUrl+'application/modules/Sesnews/externals/images/blank-img.gif">').appendTo(this.imageContainer);
			this.infoContainer = scriptJquery('<div class=sesnews_upload_photo_info sesbasic_clearfix"></div>').appendTo(this.objectInsert);
			this.size = scriptJquery('<span class="sesnews_upload_item_size sesbasic_text_light"></span>').appendTo(this.infoContainer);
			this.filename = scriptJquery('<span class="sesnews_upload_item_name"></span>').appendTo(this.infoContainer);
			this.option = scriptJquery('<div class="sesnews_upload_item_options clear sesbasic_clearfix"><span class="sesnews_upload_item_radio"><input type="radio" id="main_photo_id'+rowCount+'" name="cover"><label for="main_photo_id'+rowCount+'"><?php echo $this->translate("Main Photo"); ?></label></span><a class="edit_image_upload" href="javascript:void(0);"><i class="fa fa-edit"></i></a><a class="delete_image_upload" href="javascript:void(0);"><i class="fa fa-trash"></i></a></div>').appendTo(this.objectInsert);
		  var objectAdd = scriptJquery(this.objectInsert).appendTo('#show_photo');
			scriptJquery(this.objectInsert).css('width', widthSetImageContainer+'px');
		if (1) {
			if(scriptJquery('#show_photo').children('div').length == 1) {
				var idPhoto = scriptJquery('#show_photo').eq(0).find('.sesnews_upload_item_radio').find('input').attr('id');
				scriptJquery('#'+idPhoto).prop('checked', true);
			}else{
				scriptJquery(checkedId).prop('checked', true);
			}
		}
    this.setFileNameSize = function(name,size)
    {
				if(typeof size != 'undefined'){
					var sizeStr="";
					var sizeKB = size/1024;
					if(parseInt(sizeKB) > 1024)
					{
							var sizeMB = sizeKB/1024;
							sizeStr = sizeMB.toFixed(2)+" MB";
					}
					else
					{
							sizeStr = sizeKB.toFixed(2)+" KB";
					}
					this.size.html(sizeStr);
				}
					this.filename.html(name);
    }
    this.setProgress = function(progress)
    {       
        var progressBarWidth =progress*this.progressBar.width()/ 100;  
        this.progressBar.find('div').animate({ width: progressBarWidth }, 10).html(progress + "% ");
        if(parseInt(progress) >= 100)
        {
						scriptJquery(this.progressBar).remove();
        }
    }
    this.setAbort = function(jqxhr)
    {
        var sb = this.objectInsert;
				
        this.abort.click(function()
        {
            jqxhr.abort();
            sb.hide();
						executeupload();
        });
    }
}
var widthSetImageContainer = 180;
scriptJquery(document).ready(function(){
calculateWidthOfImageContainer();
});
function calculateWidthOfImageContainer(){
	var widthOfContainer = scriptJquery('#uploadFileContainer-element').width();
	if(widthOfContainer>=740){
		widthSetImageContainer = 	(widthOfContainer/4)-12;
	}else if(widthOfContainer>=570){
			widthSetImageContainer = (widthOfContainer/3)-12;
	}else if(widthOfContainer>=380){
			widthSetImageContainer = (widthOfContainer/2)-12;
	}else {
			widthSetImageContainer = (widthOfContainer/1)-12;
	}
}
var selectedFileLength = 0;
var statusArray =new Array();
var filesArray = [];
var countUploadSes = 0;
var fdSes = new Array();
var checkUploadPhoto = false;
var myuploadphotocounter = 0;
function handleFileUpload(files,obj)
{
	 if(checkUploadPhoto)
	 	return;
	 var check = false;
	 if(scriptJquery('#photo_count').length && scriptJquery('#photo_count').val() == 0){
		 	checkUploadPhoto = true;
			return false; 
	 }
	 if(scriptJquery('#photo_count').length){
			 check = true;
			 var  count = scriptJquery('#photo_count').val();
		}
	 selectedFileLength = files.length;
   for (var i = 0; i < files.length; i++) 
   {
			var url = files[i].name;
    	var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
			if((ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG' || ext == 'gif'  || ext == 'GIF')){
				var status = new createStatusbar(obj,files[i]); //Using this we can set progress.
				status.setFileNameSize(files[i].name,files[i].size);
				statusArray[countUploadSes] =status;
				filesArray[countUploadSes] = files[i];
				countUploadSes++;
				myuploadphotocounter++;
				if(check && parseInt(count) <= (myuploadphotocounter)){
					checkUploadPhoto = true;
					break;
				}
			}
   }
	 executeupload();
}
var execute = true;
function executeupload(){
	if(Object.keys(filesArray).length == 0 && scriptJquery('#show_photo').html() != ''){
		scriptJquery('#submit-wrapper').show();
	}
	if(execute == true){
	 for (var i in filesArray) {
		if (filesArray.hasOwnProperty(i))
    {
     	sendFileToServer(filesArray[i],statusArray[i],filesArray[i],'upload',i);
			break;
    }			
	 }
	}
}
var jqXHR = new Array();
function sendFileToServer(formData,status,file,isURL,i)
{
		execute = false;
		var formData = new FormData();
		formData.append('Filedata', file);
		if(isURL == 'upload'){
			var reader = new FileReader();
			reader.onload = function (e) {
				status.src.attr('src', e.target.result);
			}
			reader.readAsDataURL(file);
			var urlIs = '';
		}else{
			status.src.attr('src', file);
			var urlIs = true;
		}
		scriptJquery('#show_photo_container').addClass('iscontent');
		var url = '&isURL='+urlIs;
    var uploadURL = en4.core.baseUrl + 'sesnews/photo/upload' + '?ul=1'+url; //Upload URL
    var extraData ={}; //Extra Data.
    jqXHR[i]=scriptJquery.ajax({
		xhr: function() {
		var xhrobj = scriptJquery.ajaxSettings.xhr();
		if (xhrobj.upload) {
				xhrobj.upload.addEventListener('progress', function(event) {
						var percent = 0;
						var position = event.loaded || event.position;
						var total = event.total;
						if (event.lengthComputable) {
								percent = Math.ceil(position / total * 100);
						}
						//Set progress
						status.setProgress(percent);
				}, false);
		}
		return xhrobj;
		},
    url: uploadURL,
    type: "POST",
    contentType:false,
    processData: false,
		cache: false,
		data: formData,
		success: function(response){
		                        response = scriptJquery.parseJSON(response);
     
					execute = true;
					delete filesArray[i];
					//scriptJquery('#submit-wrapper').show();
					if (response.status) {
							var fileids = document.getElementById('fancyuploadfileids');
							fileids.value = fileids.value + response.photo_id + " ";
							status.option.find('.sesnews_upload_item_radio').find('input').attr('value',response.photo_id);
							status.src.attr('src',response.url);
							status.option.attr('data-src',response.photo_id);
							status.overlay.css('display','none');
							status.setProgress(100);
							status.abort.remove();
					}else
							status.abort.html('<span>Error In Uploading File</span>');
					executeupload();
       }
    }); 
}
function readImageUrl(input) {
	handleFileUpload(input.files,scriptJquery('#dragandrophandler'));
}

var dragandrophandlerSesnews = scriptJquery('#dragandrophandler');
dragandrophandlerSesnews.click(function(){
	document.getElementById('file_multi').click();	
});

var isUploadUrl = false;

var upload_from_url = scriptJquery('#upload_from_url');
upload_from_url.click(function(e) {
	e.preventDefault();
	
	if(checkUploadPhoto || (parseInt(scriptJquery('#show_photo').children().length) <= (myuploadphotocounter) && myuploadphotocounter) || (scriptJquery('#photo_count').length && scriptJquery('#photo_count').val() == 0)){
		myuploadphotocounter++;
		checkUploadPhoto = true;
		return false;
  }
	var url = scriptJquery('#from_url_upload').val();

	var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
	var name = url.split('/').pop();
	name = name.substr(0, name.lastIndexOf('.'));

		if((ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG' || ext == 'gif'  || ext == 'GIF')){
			var status = new createStatusbar(scriptJquery('#dragandrophandler'),url,'url'); //Using this we can set progress.
			var fd = new FormData();
			fd.append('Filedata', url);
			status.setFileNameSize(name);
			isUploadUrl = true;
			scriptJquery('#loading_image').html('uploading ...');
			sendFileToServer(fd,status,url,'url');
			isUploadUrl = false;
			
			scriptJquery('#loading_image').html('');
			scriptJquery('#from_url_upload').val('');
   }
	 return false;
});

scriptJquery(document).on('click','.edit_image_upload',function(e){
  e.preventDefault();
  var photo_id = scriptJquery(this).closest('.sesnews_upload_item_options').attr('data-src');
  if(photo_id){
    editImage(photo_id);
  }else
    return false;
});

scriptJquery(document).on('click','.delete_image_upload',function(e){
	e.preventDefault();
	scriptJquery(this).parent().parent().find('.sesnews_upload_item_overlay').css('display','block');
	var sesthat = this;
	var isCover = scriptJquery(this).closest('.sesnews_upload_item_options').find('.sesnews_upload_item_radio').find('input').prop('checked');
	var photo_id = scriptJquery(this).closest('.sesnews_upload_item_options').attr('data-src');
	if(photo_id){
		request = scriptJquery.ajax({
    dataType: 'json',
    'url' : '<?php echo $this->url(Array('module' => 'sesnews', 'controller' => 'index', 'action' => 'remove'), 'default') ?>',
    'data': {
      'photo_id' : photo_id
    },
   success:  function(responseJSON) {
			scriptJquery(sesthat).parent().parent().remove();
			var fileids = document.getElementById('fancyuploadfileids');
			scriptJquery('#fancyuploadfileids').val(fileids.value.replace(photo_id + " ",''));
		//if (document.getElementById('album').get('value') == 0) {
			if(isCover){
				var idPhoto = scriptJquery('#show_photo').eq(0).find('.sesnews_upload_item_radio').find('input').attr('id');
				scriptJquery('#'+idPhoto).prop('checked', true);	
			}
		//}
			if(scriptJquery('#show_photo').html() == ''){
				scriptJquery('#submit-wrapper').hide();
				scriptJquery('#show_photo_container').removeClass('iscontent');
			}
			checkUploadPhoto = false;
			myuploadphotocounter--;
     return false;
    }
    });
    
	}else
		return false;
});

<?php if(isset($_POST['file']) && $_POST['file'] != ''){ ?>
		scriptJquery('#fancyuploadfileids').val("<?php echo $_POST['file'] ?>");    	
<?php } ?>
  function editImage(photo_id) {
    var url = '<?php echo $this->url(Array('module' => 'sesnews', 'controller' => 'index', 'action' => 'edit-photo'), 'default') ?>' + '/photo_id/'+ photo_id;
    Smoothbox.open(url);
  }
  
  function showPreview(value) {
    if(value == 1)
    en4.core.showError('<a class="icon_close"><i class="fa fa-times"></i></a> <p class="popup_design_title">'+en4.core.language.translate("Design 1")+'</p><img class="popup_img" src="./application/modules/Sesnews/externals/images/layout_1.jpg" alt="" />');
    else if(value == 2)
    en4.core.showError('<a class="icon_close"><i class="fa fa-times"></i></a> <p class="popup_design_title">'+en4.core.language.translate("Design 2")+'</p><img src="./application/modules/Sesnews/externals/images/layout_2.jpg" alt="" />');
    else if(value == 3)
    en4.core.showError('<a class="icon_close"><i class="fa fa-times"></i></a> <p class="popup_design_title">'+en4.core.language.translate("Design 3")+'</p><img src="./application/modules/Sesnews/externals/images/layout_3.jpg" alt="" />');
    else if(value == 4)
    en4.core.showError('<a class="icon_close"><i class="fa fa-times"></i></a> <p class="popup_design_title">'+en4.core.language.translate("Design 4")+'</p><img src="./application/modules/Sesnews/externals/images/layout_4.jpg" alt="" />');
    return;
  }
  scriptJquery(document).on('click','.icon_close',function(){
    Smoothbox.close();
  });
  
  function showStartDate(value) {
    if(value == '1')
    scriptJquery('#event_start_time-wrapper').hide();
    else
    scriptJquery('#event_start_time-wrapper').show();
  }
  
</script>


<script type="text/javascript">
scriptJquery('body').click(function(event) {
  if(event.target.id == 'custom_url') {
    scriptJquery('#suggestion_tooltip').show();
  }
  else {
    scriptJquery('#suggestion_tooltip').hide();
  }
});
</script>


<?php if($this->typesmoothbox) { ?>
	<script type="application/javascript">
	executetimesmoothboxTimeinterval = 200;
	executetimesmoothbox = true;
	function showHideOptionsSesnews(display){
		var elem = scriptJquery('.sesnews_hideelement_smoothbox');
		for(var i = 0 ; i < elem.length ; i++){
				scriptJquery(elem[i]).parent().parent().css('display',display);
		}
	}
	function checkSettingSesnews(first){
		var hideShowOption = scriptJquery('#advanced_sesnewsoptions').hasClass('active');
			if(hideShowOption){
					showHideOptionsSesnews('none');
					if(typeof first == 'undefined'){
						scriptJquery('#advanced_sesnewsoptions').html("<i class='fa fa-plus-circle'></i><?php echo $this->translate('Show Advanced Settings') ?>");
					}
					scriptJquery('#advanced_sesnewsoptions').removeClass('active');
			}else{
					showHideOptionsSesnews('block');
					scriptJquery('#advanced_sesnewsoptions').html("<i class='fa fa-minus-circle'></i><?php echo $this->translate('Hide Advanced Settings') ?>");
						scriptJquery('#advanced_sesnewsoptions').addClass('active');
			}	
	}
	en4.core.runonce.add(function()
  {
		scriptJquery('#advanced_sesnewsoptions').click(function(e){
			checkSettingSesnews();
		});
		scriptJquery('#advanced_sesnewsoptions').html("<i class='fa fa-plus-circle'></i><?php echo $this->translate('Show Advanced Settings') ?>");
		checkSettingSesnews('true');
		
		tinymce.init({
			mode: "specific_textareas",
			plugins: "table,fullscreen,media,preview,paste,code,image,textcolor,jbimages,link",
			theme: "modern",
			menubar: false,
			statusbar: false,
			toolbar1:  "undo,redo,removeformat,pastetext,|,code,media,image,jbimages,link,fullscreen,preview",
			toolbar2: "fontselect,fontsizeselect,bold,italic,underline,strikethrough,forecolor,backcolor,|,alignleft,aligncenter,alignright,alignjustify,|,bullist,numlist,|,outdent,indent,blockquote",
			toolbar3: "",
			element_format: "html",
			height: "225px",
      content_css: "bbcode.css",
      entity_encoding: "raw",
      add_unload_trigger: "0",
      remove_linebreaks: false,
			convert_urls: false,
			language: "<?php echo $this->language; ?>",
			directionality: "<?php echo $this->direction; ?>",
			upload_url: "<?php echo $this->url(array('module' => 'sesbasic', 'controller' => 'index', 'action' => 'upload-image'), 'default', true); ?>",
			editor_selector: "tinymce"
		});
	});
  </script>	
<?php	die; 	} ?>
