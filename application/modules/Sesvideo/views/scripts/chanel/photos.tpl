<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: photos.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesalbum/externals/styles/styles.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>
<div class="layout_middle">
	<div class="layout_core_content generic_layout_container">
  	<div class="sesalbum_album_form"> <?php echo $this->form->render($this) ?> </div>
	</div>
</div>
<script type="text/javascript">
scriptJquery('#dragdrop-wrapper').show();
scriptJquery('#fromurl-wrapper').hide();
scriptJquery('#file_multi-wrapper').hide();
scriptJquery('#submit-wrapper').hide();
scriptJquery('#sesalbum_create_form_tabs li a').on('click',function(){
		scriptJquery('#dragdrop-wrapper').hide();
		scriptJquery('#fromurl-wrapper').hide();
		scriptJquery('#file_multi-wrapper').hide();
		if(scriptJquery(this).hasClass('from_url'))
			scriptJquery('#fromurl-wrapper').show();
		else if(scriptJquery(this).hasClass('drag_drop'))
			scriptJquery('#dragdrop-wrapper').show();
		else if(scriptJquery(this).hasClass('multi_upload')){
			document.getElementById('file_multi').click();			
		}
});
</script>
<script type="text/javascript">
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
			this.objectInsert = scriptJquery('<div class="sesalbum_upload_item sesbm sesbasic_bg '+row+'"></div>');
			this.overlay = scriptJquery("<div class='overlay sesalbum_upload_item_overlay'></div>").appendTo(this.objectInsert);
			this.abort = scriptJquery('<div class="abort sesalbum_upload_item_abort" id="abortPhoto_'+countUploadSes+'"><span>Cancel Uploading</span></div>').appendTo(this.objectInsert);
			this.progressBar = scriptJquery('<div class="overlay_image progressBar"><div></div></div>').appendTo(this.objectInsert);
			this.imageContainer = scriptJquery('<div class="sesalbum_upload_item_photo"></div>').appendTo(this.objectInsert);
			this.src = scriptJquery('<img src="'+en4.core.baseUrl+'application/modules/Sesalbum/externals/images/blank-img.gif">').appendTo(this.imageContainer);
			this.infoContainer = scriptJquery('<div class=sesalbum_upload_photo_info sesbasic_clearfix"></div>').appendTo(this.objectInsert);
			this.size = scriptJquery('<span class="sesalbum_upload_item_size sesbasic_text_light"></span>').appendTo(this.infoContainer);
			this.filename = scriptJquery('<span class="sesalbum_upload_item_name"></span>').appendTo(this.infoContainer);
			this.option = scriptJquery('<div class="sesalbum_upload_item_options clear sesbasic_clearfix"><a class="edit_image_upload" href="javascript:void(0);"><i class="fas fa-edit"></i></a><a class="delete_image_upload" href="javascript:void(0);"><i class="fa fa-trash"></i></a></div>').appendTo(this.objectInsert);
		  var objectAdd = scriptJquery(this.objectInsert).appendTo('#show_photo');
			scriptJquery(this.objectInsert).css('width', widthSetImageContainer+'px');
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
function handleFileUpload(files,obj)
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
					}else
							status.abort.html('<span>Error In Uploading File</span>');
					executeupload();
       }
    }); 
}
function readImageUrl(input) {
	handleFileUpload(input.files,scriptJquery('#dragandrophandler'));
}
scriptJquery('#dragandrophandler').click(function(){
	document.getElementById('file_multi').click();	
});
var isUploadUrl = false;
scriptJquery(document).on('click','#upload_from_url',function(e){
	e.preventDefault();
	var url = scriptJquery('#from_url_upload').val();
	var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
	var name = url.split('/').pop();
	name = name.substr(0, name.lastIndexOf('.'));
		if((ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG' || ext == 'gif' || ext == 'GIF' || ext == "webp")){
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
    dataType: 'json',
    'format' : 'json',
    'url' : '<?php echo $this->url(Array('module' => 'sesvideo', 'controller' => 'chanel', 'action' => 'remove'), 'default') ?>',
    'data': {
      'photo_id' : photo_id
    },
   success : function(responseJSON) {
			scriptJquery(sesthat).parent().parent().remove();
			var fileids = document.getElementById('fancyuploadfileids');
			scriptJquery('#fancyuploadfileids').val(fileids.value.replace(photo_id + " ",''));
			if(scriptJquery('#show_photo').html() == ''){
				scriptJquery('#submit-wrapper').hide();
				scriptJquery('#show_photo_container').removeClass('iscontent');
			}
     return false;
    }
    });
    request;
	}else
		return false;
});
<?php if(isset($_POST['file']) && $_POST['file'] != ''){ ?>
		scriptJquery('#fancyuploadfileids').val("<?php echo $_POST['file'] ?>");    	
<?php } ?>
  function editImage(photo_id) {
    var url = '<?php echo $this->url(Array('module' => 'sesvideo', 'controller' => 'chanel', 'action' => 'edit-photo'), 'default') ?>' + '/photo_id/'+ photo_id;
    Smoothbox.open(url);
  }
</script>
