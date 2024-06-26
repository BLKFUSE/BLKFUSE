<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: create.tpl 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
                 .'application/modules/Sesalbum/externals/scripts/core.js'); ?> 
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesalbum/externals/styles/styles.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>
<?php 
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . "externals/selectize/css/normalize.css");
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/selectize/js/selectize.js'); 
?>
<script type="text/javascript">
	function updateTextFields() {
		var fieldToggleGroup = ['#title-wrapper', '#category_id-wrapper', '#description-wrapper','#country-wrapper','#state-wrapper','#city-wrapper','#zip-wrapper','#latValue-wrapper','#lngValue-wrapper', '#search-wrapper','#auth_view-wrapper',  '#auth_comment-wrapper', '#auth_tag-wrapper','#location-wrapper','#mapcanvas-wrapper','#tags-wrapper','#art_cover-wrapper','#is_locked-wrapper','#adult-wrapper'];
    fieldToggleGroup = scriptJquery(fieldToggleGroup.join(','))
		if (document.getElementById('album').value == 0) {
			fieldToggleGroup.show();
			
      scriptJquery('#mapcanvas-wrapper').css('display' , 'none');
      scriptJquery('#mapcanvas-element').attr('id','map-canvas-list');
      
			if((scriptJquery('#subcat_id option').length > 1 && scriptJquery('#category_id').val() > 0) || (scriptJquery('#subcat_id').val() != 0 && scriptJquery('#subcat_id').val() != '' && scriptJquery('#subcat_id').val() != null ))
				scriptJquery('#subcat_id-wrapper').show();
			if((scriptJquery('#subsubcat_id option').length > 1  && scriptJquery('#subcat_id').val() > 0 ) || (scriptJquery('#subsubcat_id').val() != 0 && scriptJquery('#subsubcat_id').val() != '' && scriptJquery('#subsubcat_id').val() != null))
				scriptJquery('#subsubcat_id-wrapper').show();
				showFields(scriptJquery('#category_id').val(),1);
		} else {
			fieldToggleGroup.hide();
			scriptJquery('#subsubcat_id-wrapper').hide();
			scriptJquery('#subcat_id-wrapper').hide();
			scriptJquery('.field_container').parent().parent().hide();
		}
	}
	
	<?php if(!empty($this->album_id)) { ?>
    en4.core.runonce.add(function() {
      if (document.getElementById('album') && document.getElementById('album').value != 0) {
        updateTextFields();
      }
    });
  <?php } ?>
  
  var changeCatFieldsList = function(){
    if(scriptJquery('#sesact_popup_cat_fields').length){
        var customFieldHtml = scriptJquery('#sesact_popup_cat_fields').html();
        scriptJquery('#sesact_popup_cat_fields').html('');
        scriptJquery('#subcat_id-wrapper').after(customFieldHtml);
    }  
  }
  en4.core.runonce.add(changeCatFieldsList);
</script>
<script type="text/javascript">
  en4.core.runonce.add(function() {
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
</script>
<?php if (($this->current_count >= $this->quota) && !empty($this->quota)):?>
<div class="tip"> <span> <?php echo $this->translate('You have already uploaded the maximum number of albums allowed.');?> <?php echo $this->translate('If you would like to upload a new album, please <a href="%1$s">delete</a> an old one first.', $this->url(array('action' => 'manage'), 'sesalbum_general'));?> </span> </div>
<br/>
<?php else:?>
<?php if($this->anfalbumparams): ?>
<div class="sesalbum_create_album_popup sesbasic_bxs">
	<?php echo $this->form->render($this) ?> 
</div>
<?php else: ?>
<div class="sesalbum_album_form"> <?php echo $this->form->render($this) ?> </div>
<?php endif;?>


<?php endif; ?>

<script type="text/javascript">
en4.core.runonce.add(function() {
	scriptJquery('#dragdrop-wrapper').show();
	scriptJquery('#fromurl-wrapper').hide();
	scriptJquery('#file_multi_sesalbum-wrapper').hide();
	scriptJquery('#submit').hide();
	scriptJquery('#sesalbum_create_form_tabs li a').on('click',function(){
		scriptJquery('#dragdrop-wrapper').hide();
		scriptJquery('#fromurl-wrapper').hide();
		scriptJquery('#file_multi_sesalbum-wrapper').hide();
		if(scriptJquery(this).hasClass('drag_drop'))
			scriptJquery('#dragdrop-wrapper').show();
		else if(scriptJquery(this).hasClass('multi_upload')){
			document.getElementById('file_multi_sesalbum').click();			
		}else if(scriptJquery(this).hasClass('from_url')){
			document.getElementById('fromurl-wrapper').style.display="block";
		}
	});
});


<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum_enable_location', 1)){ ?>
en4.core.runonce.add(function() {
scriptJquery('#subcat_id-wrapper').css('display' , 'none');
scriptJquery('#subsubcat_id-wrapper').css('display' , 'none');
scriptJquery('#lat-wrapper').css('display' , 'none');
scriptJquery('#lng-wrapper').css('display' , 'none');
scriptJquery('#mapcanvas-wrapper').css('display' , 'none');
scriptJquery('#mapcanvas-element').attr('id','map-canvas-list');
//scriptJquery('#map-canvas-list').css('height','200px');
scriptJquery('#ses_location-label').attr('id','ses_location_data_list');
scriptJquery('#ses_location_data_list').html("<?php echo isset($_POST['location']) ? $_POST['location'] : '' ; ?>");
scriptJquery('#ses_location-wrapper').css('display','none');
initializeSesAlbumMapList();

});

<?php //ANF Album Condition
if(empty($this->anfalbumparams)): ?>
  scriptJquery( window ).load(function() {
    editSetMarkerOnMapList();
  });
<?php endif; ?>
<?php } ?>
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
		var categoryId = getProfileType(document.getElementById('category_id').value);
		var subcatId = getProfileType(document.getElementById('subcat_id').value);
		var subsubcatId = getProfileType(document.getElementById('subsubcat_id').value);
		var type = categoryId+','+subcatId+','+subsubcatId;
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
    var mapping = <?php echo Zend_Json_Encoder::encode(Engine_Api::_()->getDbTable('categories', 'sesalbum')->getMapping(array('category_id', 'profile_type'))); ?>;
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
    var url = en4.core.baseUrl + 'sesalbum/index/subcategory/category_id/' + cat_id;
    scriptJquery.ajax({
			method: 'post',
      dataType: 'html',
      url: url,
      data: {
				'selected':selected
      },
      success: function(responseHTML) {
        if (document.getElementById('subcat_id') && responseHTML) {
          if (document.getElementById('subcat_id-wrapper') && scriptJquery('#album').val() == 0) {
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
				//showFields(cat_id,1);
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
			showFields(cat_id,1,categoryId);
			return false;
		}
		showFields(cat_id,1,categoryId);
		var selected;
		if(selectedId != ''){
			var selected = selectedId;
		}
    var url = en4.core.baseUrl + 'sesalbum/index/subsubcategory/subcategory_id/' + cat_id;
    (scriptJquery.ajax({
			method: 'post',
      dataType: 'html',
      url: url,
      data: {
				'selected':selected
      },
      success: function(responseHTML) {
        if (document.getElementById('subsubcat_id') && responseHTML) {
          if (document.getElementById('subsubcat_id-wrapper')  && scriptJquery('#album').val() == 0) {
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
  en4.core.runonce.add(function() {
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
  <?php if(!$this->isOpenPopup || !$this->anfalbumparams): ?>
scriptJquery (document).ready(function()
{
var obj = scriptJquery('.dragandrophandler');
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
     handleFileUploadsesalbum(files,obj);
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
scriptJquery(document).on('click','div[id^="abortPhoto_"]',function(){
		var id = scriptJquery(this).attr('id').match(/\d+/)[0];
		if(typeof jqXHR[id] != 'undefined'){
				jqXHR[id].abort();
				delete filesArray[id];	
				execute = true;
				scriptJquery(this).parent().remove();
				executeuploadsesalbum();
		}else{
				delete filesArray[id];	
				scriptJquery(this).parent().remove();
		}
});
<?php endif; ?>
var rowCount=0;
function createStatusbarsesalbum(obj,file)
{
     rowCount++;
     var row="odd";
     if(rowCount %2 ==0) row ="even";
		  var checkedId = scriptJquery("input[name=cover]:checked");
			this.objectInsert = scriptJquery('<div class="sesalbum_upload_item sesbasic_bg sesbm '+row+'"></div>');
			this.overlay = scriptJquery("<div class='overlay sesalbum_upload_item_overlay'></div>").appendTo(this.objectInsert);
			this.abort = scriptJquery('<div class="abort sesalbum_upload_item_abort" id="abortPhoto_'+countUploadSes+'"><span><?php echo $this->translate("Cancel Uploading"); ?></span></div>').appendTo(this.objectInsert);
			this.progressBar = scriptJquery('<div class="overlay_image progressBar"><div></div></div>').appendTo(this.objectInsert);
			this.imageContainer = scriptJquery('<div class="sesalbum_upload_item_photo"></div>').appendTo(this.objectInsert);
			this.src = scriptJquery('<img src="'+en4.core.baseUrl+'application/modules/Sesalbum/externals/images/blank-img.gif">').appendTo(this.imageContainer);
			this.infoContainer = scriptJquery('<div class=sesalbum_upload_photo_info sesbasic_clearfix"></div>').appendTo(this.objectInsert);
			this.size = scriptJquery('<span class="sesalbum_upload_item_size sesbasic_text_light"></span>').appendTo(this.infoContainer);
			this.filename = scriptJquery('<span class="sesalbum_upload_item_name"></span>').appendTo(this.infoContainer);
			this.option = scriptJquery('<div class="sesalbum_upload_item_options clear sesbasic_clearfix"><span class="sesalbum_upload_item_radio"><input type="radio" id="main_photo_id'+rowCount+'" name="cover"><label for="main_photo_id'+rowCount+'"><?php echo $this->translate("Main Photo"); ?></label></span><a class="edit_image_upload_sesalbum fas fa-pencil-alt" href="javascript:void(0);"></a><a class="delete_image_upload delete_image_upload_sesalbum fa fa-trash" href="javascript:void(0);"></a></div>').appendTo(this.objectInsert);
			<?php if($this->anfalbumparams){ ?>
		  var objectAdd = scriptJquery(this.objectInsert).prependTo('#show_photo_sesalbum');
			<?php } else{ ?>
				 var objectAdd = scriptJquery(this.objectInsert).appendTo('#show_photo_sesalbum');
			<?php } ?>
			scriptJquery(this.objectInsert).css('width', widthSetImageContainer+'px');
		if (document.getElementById('album').value == 0) {
			if(scriptJquery('#show_photo_sesalbum').children('div').length == 1) {
				var idPhoto = scriptJquery('#show_photo_sesalbum').eq(0).find('.sesalbum_upload_item_radio').find('input').attr('id');
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
						executeuploadsesalbum();
        });
    }
}
var widthSetImageContainer = 180;
en4.core.runonce.add(function() {
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
function handleFileUploadsesalbum(files,obj)
{
	 selectedFileLength = files.length;
   for (var i = 0; i < files.length; i++) 
   {
			var url = files[i].name;
    	var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
			if((ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG' || ext == 'gif' || ext == 'GIF' || ext == "webp")){
				var status = new createStatusbarsesalbum(obj,files[i]); //Using this we can set progress.
				status.setFileNameSize(files[i].name,files[i].size);
				statusArray[countUploadSes] =status;
				filesArray[countUploadSes] = files[i];
				countUploadSes++;
			}
   }
	 executeuploadsesalbum();
}
var execute = true;
function executeuploadsesalbum(){
	if(Object.keys(filesArray).length == 0 && !scriptJquery('#show_photo_sesalbum').find('.sesalbum_upload_item').lenght){
		scriptJquery('#submit').show();
		scriptJquery('#orText').show();
	}
	if(execute == true){
	 for (var i in filesArray) {
		if (filesArray.hasOwnProperty(i))
    {
     	sendFileToServersesalbum(filesArray[i],statusArray[i],filesArray[i],'upload',i);
			break;
    }			
	 }
	}
}
var jqXHR = new Array();
function sendFileToServersesalbum(formData,status,file,isURL,i)
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
		scriptJquery('#show_photo_sesalbum_container').addClass('iscontent');
		var url = '&isURL='+urlIs;
    var uploadURL =document.getElementById('form-upload').action + '?ul=1'+url; //Upload URL
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
					execute = true;
					delete filesArray[i];
					//scriptJquery('#submit-wrapper').show();
					if (response.status) {
							var fileids = document.getElementById('fancyuploadfileids');
							fileids.value = fileids.value + response.photo_id + " ";
							status.option.find('.sesalbum_upload_item_radio').find('input').attr('value',response.photo_id);
							status.src.attr('src',response.url);
							status.option.attr('data-src',response.photo_id);
							status.overlay.css('display','none');
							status.setProgress(100);
							status.abort.remove();
					}else{
							status.abort.html('<span>Error In Uploading File</span>');
              var parseURL = scriptJquery.parseJSON(response);
              if(typeof parseURL.errorCode != 'undefined'){
                var code = parseURL.errorCode;
                if(code == "3999"){
                  alert(parseURL.message);  
                }  
              }   
          }
					executeuploadsesalbum();
       }
    }); 
}
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
 <?php if(!$this->isOpenPopup || !$this->anfalbumparams): ?>
scriptJquery(document).on('submit', '#form-upload',function(e) {
		var validation = validateForm();
		if(validation)
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
			scriptJquery('#file_multi_sesalbum-wrapper').remove();
			scriptJquery('#submit').attr('disabled',true);
			scriptJquery('#submit').html(en4.core.language.translate('Submitting Form ...'));
			return true;
		}
});
<?php endif; ?>
function readImageUrlsesalbum(input) {
	handleFileUploadsesalbum(input.files,scriptJquery('.dragandrophandler'));
}
var isUploadUrl = false;
 <?php if(!$this->isOpenPopup || !$this->anfalbumparams): ?>
scriptJquery('.dragandrophandler').click(function(){
	document.getElementById('file_multi_sesalbum').click();	
});


scriptJquery(document).on('click','#upload_from_url',function(e){
	e.preventDefault();
	var url = scriptJquery('#from_url_upload').val();
	var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
	var name = url.split('/').pop();
	name = name.substr(0, name.lastIndexOf('.'));
		if((ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG' || ext == 'gif' || ext == 'GIF' || ext == "webp")){
			var status = new createStatusbarsesalbum(scriptJquery('.dragandrophandler'),url,'url'); //Using this we can set progress.
			var fd = new FormData();
			fd.append('Filedata', url);
			status.setFileNameSize(name);
			isUploadUrl = true;
			scriptJquery('#loading_image').html('uploading ...');
			sendFileToServersesalbum(fd,status,url,'url');
			isUploadUrl = false;
			scriptJquery('#loading_image').html('');
			scriptJquery('#from_url_upload').val('');
   }
	 return false;
});
scriptJquery(document).on('click','.edit_image_upload_sesalbum',function(e){
	e.preventDefault();
	var photo_id = scriptJquery(this).closest('.sesalbum_upload_item_options').attr('data-src');
	if(photo_id){
		editImage(photo_id);
	}else
		return false;
});
scriptJquery(document).on('click','.multi_upload_sesact',function(e){
document.getElementById('file_multi_sesalbum').click();
});
scriptJquery(document).on('click','.delete_image_upload_sesalbum',function(e){
	e.preventDefault();
	scriptJquery(this).parent().parent().find('.sesalbum_upload_item_overlay').css('display','block');
	var sesthat = this;
	var isCover = scriptJquery(this).closest('.sesalbum_upload_item_options').find('.sesalbum_upload_item_radio').find('input').prop('checked');
	var photo_id = scriptJquery(this).closest('.sesalbum_upload_item_options').attr('data-src');
	if(photo_id){
		request = scriptJquery.ajax({
      dataType: 'json',
      'format' : 'json',
      
      'url' : '<?php echo $this->url(Array('module' => 'sesalbum', 'controller' => 'index', 'action' => 'remove'), 'default') ?>',
      'data': {
        'photo_id' : photo_id
      },
      success : function(responseJSON) {
        scriptJquery(sesthat).parent().parent().remove();
        var fileids = document.getElementById('fancyuploadfileids');
        scriptJquery('#fancyuploadfileids').val(fileids.value.replace(photo_id + " ",''));
        if (document.getElementById('album').value == 0) {
          if(isCover){
            var idPhoto = scriptJquery('#show_photo_sesalbum').eq(0).find('.sesalbum_upload_item_radio').find('input').attr('id');
            scriptJquery('#'+idPhoto).prop('checked', true);	
          }
        }
        if(scriptJquery('.sesalbum_upload_item').length == 0){
          scriptJquery('#submit').hide();
          scriptJquery('#show_photo_sesalbum_container').removeClass('iscontent');
        }
      return false;
      }
    });
    
	}else
		return false;
});
<?php if(isset($_POST['file']) && $_POST['file'] != ''){ ?>
		scriptJquery('#fancyuploadfileids').val("<?php echo $_POST['file'] ?>");    	
<?php } ?>
<?php endif; ?>
  function editImage(photo_id) {
    var url = '<?php echo $this->url(Array('module' => 'sesalbum', 'controller' => 'index', 'action' => 'edit-photo'), 'default') ?>' + '/photo_id/'+ photo_id;
    Smoothbox.open(url);
  }
	function enablePasswordFiled(value){
		if(value == 0){
			scriptJquery('#password-wrapper').hide();
		}else{
			scriptJquery('#password-wrapper').show();		
		}
	}
   en4.core.runonce.add(function() {
	  scriptJquery('#password-wrapper').hide();
   });
</script>

<?php if($this->anfalbumparams): ?>
<script>
  executetimesmoothboxTimeinterval = 200;	executetimesmoothbox = true;
  function sessmoothboxcallback() {
     isOpenPopup = 1;
     scriptJquery(".sessmoothbox_container").addClass('sesalbum_create_album_popup_main');
		 
		 	var heightPopup = scriptJquery(".sesalbum_create_album_popup_main").height();
			var heightDes = scriptJquery(".form-description").height();
			scriptJquery('#sesact_popup_fields').css('height',(heightPopup - heightDes - 51)+'px');
			scriptJquery('#show_photo_sesalbum_container').css('height',(heightPopup - 51)+'px');
  }
  
  
</script>
<?php die; ?>
 
<?php endif;?>
<?php if(isset($this->mediaimporter)){ ?>
  <script type="application/javascript">
   var type =  "<?php echo $this->mediaimporter; ?>";
   if(type == 'album'){
     en4.core.runonce.add(function() {
      scriptJquery('#album-wrapper').hide();
      scriptJquery('#title-wrapper').hide();
      scriptJquery('#description-wrapper').hide();
    });
   }
    en4.core.runonce.add(function() {
		scriptJquery('#tabs_form_albumcreate-wrapper').hide();
		scriptJquery('#dragdrop-wrapper').hide();
		scriptJquery('#submit').show();
    });
  </script>
<?php } ?>
