<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: backgroundphoto.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php if(!$this->is_ajax){ 
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
  <div class="sesbasic_dashboard_form sescontest_dashboard_photo_form">
    <?php echo $this->form->render() ?>
  </div>
<?php if(!$this->is_ajax){ ?>
  </div>
</div>
</div>
<?php  } ?>
<script type="application/javascript">
  scriptJquery  (document).ready(function() {
	var removehtml=scriptJquery('#removeimage-wrapper').html();
	scriptJquery('#removeimage-wrapper').remove();
	scriptJquery('#contest_main_photo_preview-element').append('<div id="removeimage-wrapper">'+removehtml+'</div>');
	
    var obj = scriptJquery('#dragandrophandlerbackground');
    obj.click(function(e){
      scriptJquery('#background').trigger('click');
    });
    obj.on('dragenter', function (e) {
      e.stopPropagation();
      e.preventDefault();
      scriptJquery (this).addClass("sesbd");
    });
    obj.on('dragover', function (e) {
      e.stopPropagation();
      e.preventDefault();
    });
    obj.on('drop', function (e) {
      scriptJquery (this).removeClass("sesbd");
      scriptJquery (this).addClass("sesbm");
      e.preventDefault();
      var files = e.originalContest.dataTransfer;
      handleFileBackgroundUpload(files,'contest_main_photo_preview');
    });
    scriptJquery (document).on('dragenter', function (e) {
      e.stopPropagation();
      e.preventDefault();
    });
    scriptJquery (document).on('dragover', function (e) {
      e.stopPropagation();
      e.preventDefault();
    });
	scriptJquery (document).on('drop', function (e) {
      e.stopPropagation();
      e.preventDefault();
	});
  });
<?php
if ($this->contest->background_photo_id !== null && $this->contest->background_photo_id){ 
 $backgroundImage =	Engine_Api::_()->storage()->get($this->contest->background_photo_id, '')->getPhotoUrl();?>
 ShowhandleFileBackgroundUpload('<?php echo $backgroundImage ?>','contest_main_photo_preview');
<?php }else{ ?>
scriptJquery  (document).ready(function()
{
	document.getElementById('dragdropbackground-wrapper').style.display = 'block';
	document.getElementById('contest_main_photo_preview-wrapper').style.display = 'none';
	document.getElementById('background-wrapper').style.display = 'none';
});
<?php } ?>
function ShowhandleFileBackgroundUpload(input,id) {
  var url = input; 
		document.getElementById('background-wrapper').style.display = 'none';
    document.getElementById('dragdropbackground-element').style.display = 'none';
    document.getElementById('removeimage-wrapper').style.display = 'block';
    document.getElementById('removeimage1').style.display = 'inline-block';
    document.getElementById('contest_main_photo_preview').style.display = 'block';
    document.getElementById('contest_main_photo_preview-wrapper').style.display = 'block';
  }

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
    
    document.getElementById('dragdropbackground-element').style.display = 'none';
    document.getElementById('removeimage-wrapper').style.display = 'block';
    document.getElementById('removeimage1').style.display = 'inline-block';
    document.getElementById('contest_main_photo_preview').style.display = 'block';
    document.getElementById('contest_main_photo_preview-wrapper').style.display = 'block';
  }
}


function removeImage() {
	document.getElementById('dragdropbackground-element').style.display = 'block';
	document.getElementById('removeimage-wrapper').style.display = 'none';
	document.getElementById('removeimage1').style.display = 'none';
	document.getElementById('contest_main_photo_preview').style.display = 'none';
	document.getElementById('contest_main_photo_preview-wrapper').style.display = 'none';
	document.getElementById('contest_main_photo_preview').src = '';
	document.getElementById('MAX_FILE_SIZE').value = '';
	document.getElementById('removeimage2').value = '';
}
function uploadBackgroundPhoto(){
	document.getElementById("EditPhoto").submit();
}
function removePhotoContest(url) {
		window.location.href = url;
}
</script>
<?php if($this->is_ajax) die; ?>
