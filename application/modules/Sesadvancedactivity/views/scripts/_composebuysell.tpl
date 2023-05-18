<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _composebuysell.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php 
$request = Zend_Controller_Front::getInstance()->getRequest();
$requestParams = $request->getParams();

if((($requestParams['action'] == 'home' || $requestParams['action'] == 'index') && $requestParams['module'] == 'user' && ($requestParams['controller'] == 'index' || $requestParams['controller'] == 'profile')) || ($this->subject() && ($this->subject()->getType() == "sesgroup_group"  || $this->subject()->getType() == "sespage_page" || $this->subject()->getType() == "businesses"))) { ?>
<?php
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/scripts/composer_buysell.js');
 
$this->headTranslate(array('What are you selling?', 'Add price', 'Add location (optional)', 'Describe your item (optional)', 'Choose a file to upload', 'Upload smaller file.', 'Where to Buy (URL Optional)'));     ?>
<script type="text/javascript">
<?php $fullySupportedCurrencies = Engine_Api::_()->sesadvancedactivity()->getSupportedCurrency();
      $currentCurrency = Engine_Api::_()->sesadvancedactivity()->getCurrencySymbol();
      if(Engine_Api::_()->sesadvancedactivity()->multiCurrencyActive()){
        $currencyData = '<select name ="buysell-currency">';
        foreach ($fullySupportedCurrencies as $key => $values) {
          if($currentCurrency == $key)
            $active ='selected';
          else
            $active ='';
          $currencyData .= '<option val="'.$key.'" '.$active.' >'.$key.'</option>';
        }
          $currencyData .= "</select>";
      }else{
          $currencyData = Engine_Api::_()->sesadvancedactivity()->getCurrencySymbol();
      }

  ?>
  en4.core.runonce.add(function() {
    composeInstance.addPlugin(new Composer.Plugin.Buysell({
      title: '<?php echo $this->string()->escapeJavascript($this->translate('Sell Something')) ?>',
      currency: '<?php echo $currencyData; ?>',
      photoUpload: <?php echo (int) (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesalbum') || Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('album')) ?>,
      lang : {
        'cancel' : '<?php echo $this->string()->escapeJavascript($this->translate('cancel')) ?>',
      },
    }));
  });
  scriptJquery(document).on('input propertychange','#buysell-title, #buysell-title-edit',function(){validateMaxLength(this);});
function validateMaxLength(obj)
{
  var text = scriptJquery(obj).val();
  var maxlength = 100;
  if(text.length > maxlength)  
  {
    scriptJquery(obj).val(text.substr(0, maxlength)); 
  }
  if(!scriptJquery('#buysell-title-count-edit').length)
    scriptJquery('#buysell-title-count').html(100 - scriptJquery(obj).val().length);
  else
    scriptJquery('#buysell-title-count-edit').html(100 - scriptJquery(obj).val().length);
}
scriptJquery(document).on('input propertychange', '#buysell-price, #buysell-price-edit', function(e){
  var val = scriptJquery(this).val();
  val = val.replace(/[^0-9\.]/g,'');
 if(val.split('.').length>2) 
     val =val.replace(/\.+$/,"");
  scriptJquery(this).val(val);
});

scriptJquery(document).on('click','.buysellloc_remove_act, .buysellloc_remove_act_edit',function(){
  if(scriptJquery(this).hasClass('buysellloc_remove_act_edit')){
    var edit = '-edit';
  }else
    var edit = '';
  scriptJquery('#locValuesbuysell-element'+edit).html('');
  scriptJquery('#locValuesbuysell-element'+edit).hide();
  scriptJquery('#buyselllocal'+edit).show();
  scriptJquery('#buysell-location'+edit).val('');
  document.getElementById('activitybuyselllng'+edit).value = '';
  document.getElementById('activitybuyselllat'+edit).value = '';
});
</script>

<script  type="text/javascript">
scriptJquery (document).ready(function()
{
var obj = scriptJquery('#dragandrophandlersesbuysell');
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
   handleFileUploadsesbuysell(files,obj);
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
scriptJquery(document).on('click','div[id^="abortPhotobusell_"]',function(){
		var id = scriptJquery(this).attr('id').match(/\d+/)[0];
		if(typeof jqXHRbuysell[id] != 'undefined'){
				jqXHRbuysell[id].abort();
				delete filesArray[id];	
				execute = true;
				scriptJquery(this).parent().remove();
				executeuploadsesbuysell();
		}else{
				delete filesArray[id];	
				scriptJquery(this).parent().remove();
		}
});
function createStatusbarbuysell(obj,file)
{
     rowCount++;
     var row="odd";
     if(rowCount %2 ==0) row ="even";
		  var checkedId = scriptJquery("input[name=cover]:checked");
			this.objectInsert = scriptJquery('<div class="advact_compose_photo_item sesbm '+row+'"></div>');
			this.overlay = scriptJquery("<div class='overlay advact_compose_photo_item_overlay'></div>").appendTo(this.objectInsert);
			this.abort = scriptJquery('<div class="abort sesalbum_upload_item_abort" id="abortPhotobusell_'+countUploadSes+'"><span><?php echo $this->translate("Cancel Uploading"); ?></span></div>').appendTo(this.objectInsert);
			this.progressBar = scriptJquery('<div class="overlay_image progressBar"><div></div></div>').appendTo(this.objectInsert);
			this.imageContainer = scriptJquery('<div class="advact_compose_photo_item_photo"></div>').appendTo(this.objectInsert);
			this.src = scriptJquery('<img src="'+en4.core.baseUrl+'application/modules/Sesalbum/externals/images/blank-img.gif">').appendTo(this.imageContainer);
			this.infoContainer = scriptJquery('<div class=advact_compose_photo_item_info sesbasic_clearfix"></div>').appendTo(this.objectInsert);
			 this.size = scriptJquery('<span class="sesalbum_upload_item_size sesbasic_text_light"></span>').appendTo(this.infoContainer);
			 this.filename = scriptJquery('<span class="sesalbum_upload_item_name"></span>').appendTo(this.infoContainer);
			this.option = scriptJquery('<div class="sesalbum_upload_item_options clear sesbasic_clearfix"><span class="sesalbum_upload_item_radio"></span><a class="edit_image_upload_buysell" href="javascript:void(0);"><i class="fa fa-edit"></i></a><a class="delete_image_upload_buysell" href="javascript:void(0);"><i class="fas fa-times"></i></a></div>').appendTo(this.objectInsert);
		  var objectAdd = scriptJquery(this.objectInsert).appendTo('#show_photo');
			//scriptJquery(".sesbasic_custom_horizontal_scroll").mCustomScrollbar("scrollTo",scriptJquery('.sesbasic_custom_horizontal_scroll').find('.mCSB_container').find('#advact_compose_photo_container_inner').find('#dragandrophandlersesbuysell'));
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
    this.setAbort = function(jqXHRbuysell)
    {
        var sb = this.objectInsert;
				
        this.abort.click(function()
        {
            jqXHRbuysell.abort();
            sb.hide();
						executeuploadsesbuysell();
        });
    }
}

var selectedFileLength = 0;
var statusArray =new Array();
var filesArray = [];
var countUploadSes = 0;
var fdSes = new Array();
function handleFileUploadsesbuysell(files,obj)
{
	 selectedFileLength = files.length;
   for (var i = 0; i < files.length; i++) 
   {
			var url = files[i].name;
    	var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
			if((ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG' || ext == 'gif' || ext == 'GIF')){
				var status = new createStatusbarbuysell(obj,files[i]); //Using this we can set progress.
				status.setFileNameSize(files[i].name,files[i].size);
				statusArray[countUploadSes] =status;
				filesArray[countUploadSes] = files[i];
				countUploadSes++;
			}
   }
	 executeuploadsesbuysell();
}
var execute = true;
function executeuploadsesbuysell(){
	if(Object.keys(filesArray).length == 0 && scriptJquery('#show_photo').html() != ''){
		scriptJquery('#compose-menu').show();
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
var jqXHRbuysell = new Array();
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
    var type = 'wall';
    if (composeInstance.options.type) type = composeInstance.options.type;
		scriptJquery('#show_photo_container').addClass('iscontent');
		var url = '&isURL='+urlIs;
    var uploadURL =en4.core.baseUrl + 'sesadvancedactivity/album/compose-upload/isactivity/true/type/'+type; //Upload URL
    var extraData ={}; //Extra Data.
    jqXHRbuysell[i]=scriptJquery.ajax({
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
							fileids.value = fileids.value + response.photo_id + " ";
							status.src.attr('src',response.url);
							status.option.attr('data-src',response.photo_id);
							status.overlay.css('display','none');
							status.setProgress(100);
							status.abort.remove();
              composeInstance.signalPluginReady(true);
 					}else
							status.abort.html('<span>Error In Uploading File</span>');
					executeuploadsesbuysell();
       }
    }); 
}
function readImageUrlbuysell(input) {
	handleFileUploadsesbuysell(input.files,scriptJquery('#dragandrophandlersesbuysell'));
}
scriptJquery(document).on('click','#dragandrophandlersesbuysell',function(){
	document.getElementById('file_multi').click();	
});
var isUploadUrl = false;
scriptJquery(document).on('click','.edit_image_upload_buysell',function(e){
	e.preventDefault();
	var photo_id = scriptJquery(this).closest('.sesalbum_upload_item_options').attr('data-src');
	if(photo_id){
		editImage(photo_id);
	}else
		return false;
});
scriptJquery(document).on('click','.delete_image_upload_buysell',function(e){
	e.preventDefault();
	scriptJquery(this).parent().parent().find('.sesalbum_upload_item_overlay').css('display','block');
	var sesthat = this;
	var photo_id = scriptJquery(this).closest('.sesalbum_upload_item_options').attr('data-src');
	if(photo_id){
		request = scriptJquery.ajax({
    'format' : 'json',
    'url' : '<?php echo $this->url(Array('module' => 'sesadvancedactivity', 'controller' => 'album', 'action' => 'remove'), 'default') ?>',
    'data': {
      'photo_id' : photo_id
    },
   'success' : function(responseJSON) {
			scriptJquery(sesthat).parent().parent().remove();
			var fileids = document.getElementById('fancyalbumuploadfileids');
			scriptJquery('#fancyalbumuploadfileids').val(fileids.value.replace(photo_id + " ",''));
			if(scriptJquery('#show_photo').html() == ''){
				scriptJquery('#show_photo_container').removeClass('iscontent');
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
    var url = '<?php echo $this->url(Array('module' => 'sesadvancedactivity', 'controller' => 'album', 'action' => 'edit-photo'), 'default') ?>' + '/photo_id/'+ photo_id;
    Smoothbox.open(url);
  }
</script>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery.min.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>

<?php } ?>
