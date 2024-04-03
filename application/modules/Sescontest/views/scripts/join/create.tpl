<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: create.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php 
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . "externals/selectize/css/normalize.css");
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/selectize/js/selectize.js'); 
?>
<?php
  $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl."externals/selectize/css/normalize.css");
  $headScript = new Zend_View_Helper_HeadScript();
  $headScript->appendFile($this->layout()->staticBaseUrl.'externals/selectize/js/selectize.js');
  $headScript->appendFile($this->layout()->staticBaseUrl.'application/modules/Core/externals/scripts/create_edit_category.js');
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>
<div class="sescontest_join_form sesbasic_bxs">
  <?php echo $this->form->render();?>
  <div class="sescontest_join_loading sescontest_join_overlay" style="display: none">
  	<div class="sescontest_join_overlay_cont">
    	<i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom"></i>
      <span class="_text"><?php echo $this->translate('Please wait your entry is submitting ...');?></span>
    </div>
  </div>
</div>
<div class="sescontest_link_content_popup_overlay" style="display:none;"></div>
<div class="sescontest_link_content_popup" style="display:none;">
	<div class="sescontest_link_content_popup_content">
  	<div class="sescontest_link_content_popup_content_inner">
      <div class="sescontest_link_content_popup_heading">
        <h2><?php echo $this->translate("Select Your Content"); ?></h2>
      </div>
      <input type="text" name="selectcontestcontent" id="selectcontestcontent" value="" placeholder="<?php echo $this->translate("Start typing ...") ?>" autocomplete="off" />
      <div class="sescontest_link_content_popup_elements">  
        <br />
	    <div class="sescontest_link_content_popup_buttons">
          <button id="saveContent" onclick=""><?php echo $this->translate("Save"); ?></button>
          <button id="cancelContent" onclick="" class="secondary_button"><?php echo $this->translate("Cancel"); ?></button>
      	</div>
      </div>
		</div>
	</div>
</div>
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
  
   var acceptRule = false;
   scriptJquery('#contest_join_form_tabs li a').click(function(e){
	 e.preventDefault();
        var className = scriptJquery(this).parent().attr('data-url');
//         if(scriptJquery('.first_step').hasClass('active') && className == 'first_third') {
//           alert('Please first fill complete the "Registration" form.');
//           return false;
//         }
        if(scriptJquery('.first_step').hasClass('active') && scriptJquery(this).attr('id') == 'save_second_1-click' && acceptRule == false) {
          alert('Please accept rules.');
          return false;
        }
        acceptRule = false;
		if(onLoad == 'loadedElem' && className != 'first_second' && className != 'first_step'){
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
			}
		}
        var liLength = scriptJquery('#contest_join_form_tabs li');
// 		for(i=0;i<liLength.length;i++)
// 			liLength[i].removeClass('active');
		onLoad = 'loadedElem';
		scriptJquery('#first_step-wrapper').hide();
		scriptJquery('#first_second-wrapper').hide();
		scriptJquery('#first_third-wrapper').hide();
		scriptJquery('#'+className+'-wrapper').show();
		scriptJquery(this).parent().addClass('active');
 });
  var onLoad = 'firstLoad';
  scriptJquery('#contest_join_form_tabs').children().eq(0).find('a').click();  
  scriptJquery(document).on('click','.next_elm',function(){
    var id = scriptJquery(this).attr('id');
    acceptRule = true;
    scriptJquery('#'+id+'-click').trigger('click');
  });
//Ajax error show before form submit
var error = false;
var recordedDataContest;
var objectError ;
var counter = 0;
function validateForm(){
  var errorPresent = false;
  scriptJquery('#form-upload input, #form-upload select,#form-upload checkbox,#form-upload textarea,#form-upload radio').each(
  function(index){
    var input = scriptJquery(this);
    if(scriptJquery(this).closest('div').parent().css('display') != 'none' && scriptJquery(this).closest('div').parent().find('.form-label').find('label').first().hasClass('required') && scriptJquery(this).prop('type') != 'hidden' && scriptJquery(this).closest('div').parent().attr('class') != 'form-elements'){	
      if(scriptJquery(this).prop('type') == 'select-multiple'){
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
        if(scriptJquery('.first_second').hasClass('active') && this.id == 'contest_description')
          error = false;
        else {
        if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
          error = true;
        else
          error = false;
       }
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
        if(scriptJquery('#entry_photo').length && scriptJquery('#entry_photo').val() === '' && scriptJquery('#photouploaderentry-label').find('label').hasClass('required')){
          objectError = scriptJquery('#entrydragandrophandlerbackground');
          error = true;
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
 
  //drag drop photo upload
 en4.core.runonce.add(function()
  {
	if(scriptJquery('#entrydragandrophandlerbackground').hasClass('requiredClass')){
		scriptJquery('#entrydragandrophandlerbackground').parent().parent().find('#photouploaderentry-label').find('label').addClass('required').removeClass('optional');	
	}
    if(scriptJquery('#dragandrophandlerbackground').hasClass('requiredClass')){
		scriptJquery('#dragandrophandlerbackground').parent().parent().find('#photouploader-label').find('label').addClass('required').removeClass('optional');	
	}
    if(document.getElementById('photouploader-wrapper'))
	document.getElementById('photouploader-wrapper').style.display = 'block';
    if(document.getElementById('contest_main_photo_preview-wrapper'))
	document.getElementById('contest_main_photo_preview-wrapper').style.display = 'none';
    if(document.getElementById('contest_link_photo_preview-wrapper'))
	document.getElementById('contest_link_photo_preview-wrapper').style.display = 'none';
    if(document.getElementById('contest_link_video_preview-wrapper'))
	document.getElementById('contest_link_video_preview-wrapper').style.display = 'none';
    if(document.getElementById('contest_link_audio_preview-wrapper')) {
    document.getElementById('contest_link_audio_preview-wrapper').style.display = 'none';
    }
    if(document.getElementById('fromurl-wrapper')) {
      document.getElementById('fromurl-wrapper').style.display = 'none';
      document.getElementById('remove_fromurl_image-wrapper').style.display = 'none';
      document.getElementById('contest_url_photo_preview-wrapper').style.display = 'none';  
    }
    if(document.getElementById('contest_link_audio_data-wrapper'))
    document.getElementById('contest_link_audio_data-wrapper').style.display = 'none';
    if(document.getElementById('photo-wrapper'))
	document.getElementById('photo-wrapper').style.display = 'none';
    scriptJquery('.contest-entry-video').hide();
    
 var obj = scriptJquery('#dragandrophandlerbackground');
obj.click(function(e){
	scriptJquery('#photo').val('');
	scriptJquery('#contest_main_photo_preview').attr('src','');
  scriptJquery('#photo').trigger('click');
});
    
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
     var files = e.originalEvent.dataTransfer;
     handleFileBackgroundUpload(files,'contest_main_photo_preview');
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
    scriptJquery('#form-upload').submit(function(e){
      e.preventDefault();
      var uploadType = scriptJquery('#uploaded_content_type').val();
      if(uploadType == '') {
        uploadType = 1;
      }
      if("<?php echo $this->contest->contest_type ;?>" == 4) {
        if(uploadType == 2 && typeof recordedDataContest == 'undefined') {
           alert('Please record the audio for uploading content.');
           return false;
        }
        else if(uploadType == 1 && scriptJquery('#sescontest_audio_file').val() == '') {
           alert('Please select the audio.');
           return false;
        }
        else if(uploadType == 3 && scriptJquery('#contest_link_audio_data-wrapper').find('audio').length <= 0) {
           alert('Please select the audio from popup.');
           return false;
        }
      }
      else if("<?php echo $this->contest->contest_type ;?>" == 3) { 
        if(uploadType == 2 && typeof recordedDataContest == 'undefined') {
           alert('Please record the video for uploading content.');
           return false;
        } else if(uploadType == 1 && scriptJquery('#sescontest_video_file').val() == '') {
           alert('Please select the video.');
           return false;
        }
        else if(uploadType == 3 && (scriptJquery('#contest_link_video_preview-wrapper').find('iframe').length <= 0 && scriptJquery('#contest_link_video_preview-wrapper').find('video').length <= 0)) {
           alert('Please seslect the video from popup.');
           return false;
        }
      }
      else if("<?php echo $this->contest->contest_type ;?>" == 2) {
        if(uploadType == 2 && typeof recordedDataContest == 'undefined') {
           alert('Please upload the photo for uploading content.');
           return false;
        }
        else if(uploadType == 1 && scriptJquery('#contest_main_photo_preview-wrapper').css('display') == 'none') {
           alert('Please seslect the photo.');
           return false;
        }
        else if(uploadType == 3 && scriptJquery('#contest_link_photo_preview').attr("src") == '') {
           alert('Please seslect the photo from existing album.');
           return false;
        }
         else if(uploadType == 4 && scriptJquery('#contest_url_photo_preview').attr("src") == '') {
           alert('Please Enter the Photo URL.');
           return false;
        }
      }
      submitForm(this);
    });
    

    //Need to remove
    //scriptJquery('#sescontest_content_link').click(function(e){
   // scriptJquery('.sescontest_link_content_popup_overlay').show();
    //scriptJquery('.sescontest_link_content_popup').show();
  //  return false;
  // });
   // Need to remove
 
    
});


  var rotation = {
    1: 'rotate(0deg)',
    3: 'rotate(180deg)',
    6: 'rotate(90deg)',
    8: 'rotate(270deg)'
  };

  function _arrayBufferToBase64(buffer) {
    var binary = ''
    var bytes = new Uint8Array(buffer)
    var len = bytes.byteLength;
    for (var i = 0; i < len; i++) {
      binary += String.fromCharCode(bytes[i])
    }
    return window.btoa(binary);
  }

  function orientation(file, callback) {

    var fileReader = new FileReader();
    fileReader.onloadend = function() {
      var base64img = "data:" + file.type + ";base64," + _arrayBufferToBase64(fileReader.result);
      var scanner = new DataView(fileReader.result);
      var idx = 0;
      var value = 1; // Non-rotated is the default
      if (fileReader.result.length < 2 || scanner.getUint16(idx) != 0xFFD8) {
        // Not a JPEG
        if (callback) {
          callback(base64img, value);
        }
        return;
      }
      idx += 2;
      var maxBytes = scanner.byteLength;
      while (idx < maxBytes - 2) {
        var uint16 = scanner.getUint16(idx);
        idx += 2;
        switch (uint16) {
          case 0xFFE1: // Start of EXIF
            var exifLength = scanner.getUint16(idx);
            maxBytes = exifLength - idx;
            idx += 2;
            break;
          case 0x0112: // Orientation tag
            // Read the value, its 6 bytes further out
            // See page 102 at the following URL
            // http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf
            value = scanner.getUint16(idx + 6, false);
            maxBytes = 0; // Stop scanning
            break;
        }
      }
      if (callback) {
        callback(base64img, value);
      }
    }
    fileReader.readAsArrayBuffer(file);
  };

var sesbasicidparam = "";
function handleFileBackgroundUpload(input,id) {
  var files = scriptJquery(input)[0].files[0];
  var url = input.value;
  if(typeof url == 'undefined')
  url = input.files[0]['name'];
  var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
  if (input.files && input.files[0] && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG' || ext == 'webp')){
    sesbasicidparam = id;
    if (files) {
      orientation(files, function(base64img, value) {
        //$(id+'-wrapper').attr('src', base64img);
        scriptJquery(sesbasicidparam).closest('.form-wrapper').show();;
        var rotated = scriptJquery(sesbasicidparam).attr('src', base64img);
        if (value) {
          scriptJquery(sesbasicidparam).css('transform', rotation[value]);
        }
      });
    }
    
    document.getElementById('photouploader-element').style.display = 'none';
    document.getElementById('removeimage-wrapper').style.display = 'block';
    document.getElementById('removeimage1').style.display = 'inline-block';
    document.getElementById('contest_main_photo_preview').style.display = 'block';
    document.getElementById('contest_main_photo_preview-wrapper').style.display = 'block';
    
    recordedDataContest = input.files[0];
    resetPhotoData();
    removeLinkImage();
    removeFromurlImage(0);
  }
}

function removeImage() {
	document.getElementById('photouploader-element').style.display = 'block';
	document.getElementById('removeimage-wrapper').style.display = 'none';
	document.getElementById('removeimage1').style.display = 'none';
	document.getElementById('contest_main_photo_preview').style.display = 'none';
	document.getElementById('contest_main_photo_preview-wrapper').style.display = 'none';
	document.getElementById('contest_main_photo_preview').src = '';
	document.getElementById('MAX_FILE_SIZE').value = '';
	document.getElementById('removeimage2').value = '';
	document.getElementById('photo').value = '';
}
  function removeLinkImage() {
    if(document.getElementById('remove_link_image-wrapper'))
    document.getElementById('remove_link_image-wrapper').style.display = 'none';
    if(document.getElementById('contest_link_photo_preview'))
    document.getElementById('contest_link_photo_preview').src = '';
    if(document.getElementById('sescontest_link_id'))
    scriptJquery('#sescontest_link_id').val('');
    document.getElementById('MAX_FILE_SIZE').value = '';
  }
function removeFromurlImage(value) {
  document.getElementById('remove_fromurl_image-wrapper').style.display = 'none';
  document.getElementById('contest_url_photo_preview').src = '';
  document.getElementById('contest_url_photo_preview-wrapper').style.display = 'none';
  if(value)
  document.getElementById('fromurl-wrapper').style.display = 'block';
  scriptJquery('#sescontest_url_id').val('');
  document.getElementById('MAX_FILE_SIZE').value = '';
}
function submitForm(obj) {
  var blob = recordedDataContest
  if(recordedDataContest instanceof Blob || typeof recordedDataContest == "object"  || typeof recordedDataContest == "string") {
    blob = recordedDataContest
  }else if (recordedDataContest){ 
    blob =  recordedDataContest.blob  
  }
  var form_elem_name = 'webcam';
  var image_fmt = '';
  var form = new FormData(obj);
  if(scriptJquery('#uploaded_content_type').val() == 2 && "<?php echo $this->contest->contest_type ;?>" != 2 &&"<?php echo $this->contest->contest_type ;?>" != 1){
    form.append( form_elem_name, blob, form_elem_name+".webm" );
  }else if("<?php echo $this->contest->contest_type ;?>" == 2)
  form.append('record_photo', blob);
  else if("<?php echo $this->contest->contest_type ;?>" == 1) {
    if("<?php echo $this->contest->editor_type ;?>" == 1) {
      var editorContent = tinyMCE.get('contest_description').getContent();
    }
    else {
      var editorContent = scriptJquery('#contest_description').val();
    }
    if(editorContent == '') {
      alert('Please fill the content.');
      return false;
    }
    else
      form.append('contest_description', editorContent);
  }
  scriptJquery('.sescontest_join_loading').show();
  scriptJquery('.sescontest_join_contest_form').addClass('_success');
  scriptJquery.ajax({
     xhr:  function() {
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
             }, false);
     }
     return xhrobj;
     },
 url:  en4.core.baseUrl+"<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.contest.manifest', 'contest');?>"+'/create/'+"<?php echo $this->contest->contest_id ;?>",
 type: "POST",
 contentType:false,
 processData: false,
     cache: false,
     data: form,
     success: function(response){
         var response = jQuery.parseJSON(response);
         if(response.status) {
           scriptJquery('.sescontest_join_loading').html('<div class="sescontest_join_success" style="display: block"><div class="sescontest_join_overlay_cont"><i><img src="application/modules/Sescontest/externals/images/success.png" alt="" /></i><span class="_text">'+en4.core.language.translate("Thanks for Participation !")+'</span></div></div>');
           window.location.href = response.href;
         }
     }
 });
}
</script>
