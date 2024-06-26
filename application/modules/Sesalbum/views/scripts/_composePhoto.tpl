<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _composePhoto.tpl 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php
  $this->headLink()
    ->appendStylesheet($this->layout()->staticBaseUrl . 'externals/fancyupload/fancyupload.css')
    ->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesalbum/externals/styles/styles.css');
  $this->headTranslate(array(
    'Overall Progress ({total})', 'File Progress', 'Uploading "{name}"',
    'Upload: {bytesLoaded} with {rate}, {timeRemaining} remaining.', '{name}',
    'Remove', 'Click to remove this entry.', 'Upload failed',
    '{name} already added.',
    '{name} ({size}) is too small, the minimal file size is {fileSizeMin}.',
    '{name} ({size}) is too big, the maximal file size is {fileSizeMax}.',
    '{name} could not be added, amount of {fileListMax} files exceeded.',
    '{name} ({size}) is too big, overall filesize of {fileListSizeMax} exceeded.',
    'Server returned HTTP-Status <code>#{code}</code>',
    'Security error occurred ({text})',
    'Error caused a send or load operation to fail ({text})',
  ));
?>
<?php
  $this->headScript()
    ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesalbum/externals/scripts/composer_photo.js'); ?>
<style>
  #compose-photo-error{ display:none;}
</style>
<script type="text/javascript">
 if(window.location.href.indexOf("messages/compose") > -1 || window.location.href.indexOf("messages/view/id") > -1) {
  var isMessagePage = true;
 }else{
  var isMessagePage = false;
 }

<?php if(!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')){ ?>
  en4.core.runonce.add(function() {
        var type = 'wall';
        if (composeInstance.options.type) type = composeInstance.options.type;
        composeInstance.addPlugin(new Composer.Plugin.Photo({
          title : '<?php echo $this->string()->escapeJavascript($this->translate('Add Photo')) ?>',
          lang : {
            'Add Photo' : '<?php echo $this->string()->escapeJavascript($this->translate('Add Photo')) ?>',
            'Select File' : '<?php echo $this->string()->escapeJavascript($this->translate('Select File')) ?>',
            'cancel' : '<?php echo $this->string()->escapeJavascript($this->translate('cancel')) ?>',
            'Loading...' : '<?php echo $this->string()->escapeJavascript($this->translate('Loading...')) ?>',
            'Unable to upload photo. Please click cancel and try again': ''
          },
          requestOptions : {
            'url'  : en4.core.baseUrl + 'sesalbum/album/compose-upload/type/'+type
          },
          fancyUploadOptions : {
            'url'  : en4.core.baseUrl + 'sesalbum/album/compose-upload/format/json/type/'+type,
            'path' : en4.core.basePath + 'externals/fancyupload/Swiff.Uploader.swf'
          }
        }));
  });
</script>
<?php }else{ ?>  
<?php $this->headScript()
    ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesalbum/externals/scripts/composer_photo.js'); ?>
en4.core.runonce.add(function() {      
 composeInstance.addPlugin(new Composer.Plugin.Photo({
      title: '<?php echo $this->string()->escapeJavascript($this->translate('Add Photo')) ?>',
      lang : {
            'Add Photo' : '<?php echo $this->string()->escapeJavascript($this->translate('Add Photo')) ?>',
            'cancel' : '<?php echo $this->string()->escapeJavascript($this->translate('cancel')) ?>',
      }
    }));
 });
      
     
scriptJquery (document).ready(function()
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
   handleFileUploadSesalbum(files,obj);
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
				executeuploadSesalbum();
		}else{
				delete filesArray[id];	
				scriptJquery(this).parent().remove();
		}
    if(isMessagePage && (scriptJquery('#toValues-wrapper').length > 0 || scriptJquery('#submit-wrapper').length > 0)){
      scriptJquery('#dragandrophandler').show();
    }
	if(isMessagePage){
		scriptJquery('#dragandrophandler').show();
	}
});
function createStatusbar(obj,file)
{
     rowCount++;
     var row="odd";
     if(rowCount %2 ==0) row ="even";
		  var checkedId = scriptJquery("input[name=cover]:checked");
			this.objectInsert = scriptJquery('<div class="advact_compose_photo_item sesbm '+row+'"></div>');
			this.overlay = scriptJquery("<div class='overlay advact_compose_photo_item_overlay'></div>").appendTo(this.objectInsert);
			this.abort = scriptJquery('<div class="abort sesalbum_upload_item_abort" id="abortPhoto_'+countUploadSes+'"><span><?php echo $this->translate("Cancel Uploading"); ?></span></div>').appendTo(this.objectInsert);
			this.progressBar = scriptJquery('<div class="overlay_image progressBar"><div></div></div>').appendTo(this.objectInsert);
			this.imageContainer = scriptJquery('<div class="advact_compose_photo_item_photo"></div>').appendTo(this.objectInsert);
			this.src = scriptJquery('<img src="'+en4.core.baseUrl+'application/modules/Sesalbum/externals/images/blank-img.gif">').appendTo(this.imageContainer);
			this.infoContainer = scriptJquery('<div class=advact_compose_photo_item_info sesbasic_clearfix"></div>').appendTo(this.objectInsert);
			 this.size = scriptJquery('<span class="sesalbum_upload_item_size sesbasic_text_light"></span>').appendTo(this.infoContainer);
			 this.filename = scriptJquery('<span class="sesalbum_upload_item_name"></span>').appendTo(this.infoContainer);
			this.option = scriptJquery('<div class="sesalbum_upload_item_options clear sesbasic_clearfix"><span class="sesalbum_upload_item_radio"></span><a class="edit_image_upload" href="javascript:void(0);"><i class="fas fa-pencil-alt"></i></a><a class="delete_image_upload" href="javascript:void(0);"><i class="fas fa-times"></i></a></div>').appendTo(this.objectInsert);
		  var objectAdd = scriptJquery(this.objectInsert).appendTo('#show_photo');
			scriptJquery(".sesbasic_custom_horizontal_scroll").mCustomScrollbar("scrollTo",scriptJquery('.sesbasic_custom_horizontal_scroll').find('.mCSB_container').find('#advact_compose_photo_container_inner').find('#dragandrophandler'));
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
						executeuploadSesalbum();
        });
    }
}

var selectedFileLength = 0;
var statusArray =new Array();
var filesArray = [];
var countUploadSes = 0;
var fdSes = new Array();
function handleFileUploadSesalbum(files,obj)
{
	 selectedFileLength = files.length;
   for (var i = 0; i < files.length; i++) 
   {
			var url = files[i].name;
    	var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
			if((ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG' || ext == 'gif' || ext == 'GIF' || ext == "webp")){
				var status = new createStatusbar(obj,files[i]); //Using this we can set progress.
				status.setFileNameSize(files[i].name,files[i].size);
				statusArray[countUploadSes] =status;
				filesArray[countUploadSes] = files[i];
				countUploadSes++;
			}
   }
	 executeuploadSesalbum();
}
var execute = true;
function executeuploadSesalbum(){
	if(Object.keys(filesArray).length == 0 && scriptJquery('#show_photo').html() != ''){
   if(isMessagePage && (scriptJquery('#toValues-wrapper').length > 0 || scriptJquery('#submit-wrapper').length > 0)){}else{
		scriptJquery('#compose-menu').show();
   }
	}
	if(execute == true){
	 for (var i in filesArray) {
		if (filesArray.hasOwnProperty(i))
    {
     	sendFileToServerSesalbum(filesArray[i],statusArray[i],filesArray[i],'upload',i);
			break;
    }			
	 }
	}
}
var jqXHR = new Array();
function sendFileToServerSesalbum(formData,status,file,isURL,i)
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
    var type = 'wall';
    
    if(isMessagePage && (scriptJquery('#toValues-wrapper').length > 0 || scriptJquery('#submit-wrapper').length > 0)){
      scriptJquery('#dragandrophandler').hide();
    }
	if(isMessagePage){
		scriptJquery('#dragandrophandler').hide();
	}
    
    if (composeInstance.options.type) type = composeInstance.options.type;
		scriptJquery('#show_photo_container').addClass('iscontent');
		var url = '&isURL='+urlIs;
    var uploadURL =en4.core.baseUrl + 'sesalbum/album/compose-upload/isactivity/true/type/'+type; //Upload URL
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
          response = scriptJquery.parseJSON(response);
					if (response.status) {
							var fileids = document.getElementById('fancyalbumuploadfileids');
							if(fileids)
								fileids.value = fileids.value + response.photo_id + " ";
							status.src.attr('src',response.url);
							status.option.attr('data-src',response.photo_id);
							status.overlay.css('display','none');
							status.setProgress(100);
							status.abort.remove();
              if(isMessagePage && (scriptJquery('#toValues-wrapper').length > 0 || scriptJquery('#submit-wrapper').length > 0)){
                scriptJquery('#submit').show();
                scriptJquery('#dragandrophandler').hide();
              }
			  if(isMessagePage){
				if(!scriptJquery('#fancyalbumuploadfileids').length > 0){
					scriptJquery("#compose-tray").parent().find("form").append('<input type="hidden" name="attachment[photo_id]" value="'+response.photo_id+'" id="fancyalbumuploadfileids"><input type="hidden" id="messageAttachment" name="attachment[type]" value="photo">')
				}
				scriptJquery('#dragandrophandler').hide();
			  }
              composeInstance.signalPluginReady(true);
 					}else
						  status.abort.html('<span>Error In Uploading File</span>');
					    executeuploadSesalbum();
       }
    }); 
}
function readImageUrlSesalbum(input) {
	handleFileUploadSesalbum(input.files,scriptJquery('#dragandrophandler'));
}
scriptJquery(document).on('click','#dragandrophandler',function(){
  setTimeout(function(){ document.getElementById('file_multi').click();; }, 100);
});
var isUploadUrl = false;
scriptJquery(document).on('click','.edit_image_upload',function(e){
	e.preventDefault();
	var photo_id = scriptJquery(this).closest('.sesalbum_upload_item_options').attr('data-src');
	if(photo_id){
		editImage(photo_id);
	}else
		return false;
});
scriptJquery(document).on('click','.delete_image_upload',function(e){
	e.preventDefault();
	scriptJquery(this).parent().parent().find('.sesalbum_upload_item_overlay').css('display','block');
	var sesthat = this;
	var photo_id = scriptJquery(this).closest('.sesalbum_upload_item_options').attr('data-src');
	if(photo_id){
		request = scriptJquery.ajax({
    'format' : 'json',
    method: 'post',
    'url' : '<?php echo $this->url(Array('module' => 'sesalbum', 'controller' => 'index', 'action' => 'remove'), 'default') ?>',
    'data': {
      'photo_id' : photo_id
    },
   'success' : function(responseJSON) {
			scriptJquery(sesthat).parent().parent().remove();
			var fileids = document.getElementById('fancyalbumuploadfileids');
			if(fileids)
			scriptJquery('#fancyalbumuploadfileids').val(fileids.value.replace(photo_id + " ",''));
			if(scriptJquery('#show_photo').html() == ''){
				if(isMessagePage && (scriptJquery('#toValues-wrapper').length > 0 || scriptJquery('#submit-wrapper').length > 0)){
					scriptJquery('#submit').hide();
					scriptJquery('#dragandrophandler').show();
				}
				
				scriptJquery('#show_photo_container').removeClass('iscontent');
			}
			if(isMessagePage){
				scriptJquery("#fancyalbumuploadfileids").remove();
				scriptJquery("#messageAttachment").remove();
				scriptJquery('#dragandrophandler').show();
			}
     return false;
    }
    });
    
	}else
		return false;
});
<?php if(isset($_POST['file']) && $_POST['file'] != ''){ ?>
		scriptJquery('#fancyalbumuploadfileids').val("<?php echo $_POST['file'] ?>");    	
<?php } ?>
  function editImage(photo_id) {
    var url = '<?php echo $this->url(Array('module' => 'sesalbum', 'controller' => 'index', 'action' => 'edit-photo'), 'default') ?>' + '/photo_id/'+ photo_id;
    Smoothbox.open(url);
  }
</script>
<?php  } ?>
<?php if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesadvancedactivity')) { ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js');
 } ?>
