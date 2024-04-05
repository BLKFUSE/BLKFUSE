<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _entryPhoto.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<script type="text/javascript">
//drag drop photo upload
 en4.core.runonce.add(function()
  {
	if(scriptJquery('#entrydragandrophandlerbackground').hasClass('requiredClass')){
		scriptJquery('#entrydragandrophandlerbackground').parent().parent().find('#photouploaderentry-label').find('label').addClass('required').removeClass('optional');	
	}
    if(document.getElementById('photouploaderentry-wrapper'))
	document.getElementById('photouploaderentry-wrapper').style.display = 'flex';
	document.getElementById('contest_entry_main_photo_preview-wrapper').style.display = 'none';
   
	document.getElementById('entry_photo-wrapper').style.display = 'none';

var obj = scriptJquery('#entrydragandrophandlerbackground');
obj.click(function(e){
	scriptJquery('#entry_photo').val('');
	scriptJquery('#contest_entry_main_photo_preview').attr('src','');
  scriptJquery('#entry_photo').trigger('click');
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
     entryhandleFileBackgroundUpload(files,'contest_entry_main_photo_preview');
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
function entryhandleFileBackgroundUpload(input,id) {
  var url = input.value; 
  if(typeof url == 'undefined')
    url = input.files[0]['name'];
  var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
  if (input.files && input.files[0] && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG' || ext == 'webp')){
    var reader = new FileReader();
    reader.onload = function (e) {
     // document.getElementById(id+'-wrapper').style.display = 'flex';
      scriptJquery(id).attr('src', e.target.result);
    }
    if(document.getElementById('photouploaderentry-element'))
    document.getElementById('photouploaderentry-element').style.display = 'none';
    document.getElementById('removeEntryImage-wrapper').style.display = 'flex';
    document.getElementById('removeentryimage1').style.display = 'inline-block';
    document.getElementById('contest_entry_main_photo_preview').style.display = 'flex';
    document.getElementById('contest_entry_main_photo_preview-wrapper').style.display = 'flex';
    reader.readAsDataURL(input.files[0]);
  }
}
function removeEntryImage() {
    if(document.getElementById('photouploaderentry-element'))
	document.getElementById('photouploaderentry-element').style.display = 'flex';
	document.getElementById('removeEntryImage-wrapper').style.display = 'none';
	document.getElementById('removeentryimage1').style.display = 'none';
	document.getElementById('contest_entry_main_photo_preview').style.display = 'none';
	document.getElementById('contest_entry_main_photo_preview-wrapper').style.display = 'none';
	document.getElementById('contest_entry_main_photo_preview').src = '';
	document.getElementById('MAX_FILE_SIZE').value = '';
	document.getElementById('removeentryimage2').value = '';
	document.getElementById('entry_photo').value = '';
}
</script>
