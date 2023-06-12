<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: faq.tpl 2021-01-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php  ?>

<?php include APPLICATION_PATH .  '/application/modules/Tickvideo/views/scripts/dismiss_message.tpl'; ?>

<div>
  <div class="sesbasic_search_reasult"><?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'tickvideo', 'controller' => 'music', 'action' => 'categories'), $this->translate("Back to Manage Categories"), array('class'=>'sesbasic_icon_back buttonlink')); ?></div>
  
  <h3>Music Import Instructions</h3>
  <p>With this feature, you can import music in bulk from your system to the website. To allow site admin to upload music in bulk, we have provided a template folder which you need to download & follow the below instructions to know how to use it:</p>
  <div class="tickvideo_import_faq">
    <ol>
      <li>Unzip the downloaded template folder so that you may get all the files packed in it.</li>
      <li>You will get the following after unzip:
        <ul>
          <li><b>Images Folder:</b> This folder contain all the images which have to be used for the songs which are going to be uploaded in bulk.</li>
          <li><b>Music Folder:</b>  This folder contain all the Mp3 Music files for songs.</li>
          <li><b>Details CSV File:</b> This file contain the values which you need to fill in CSV format as per the file format pre-given in it.</li>
          <li><b>Readme Text Document:</b> This text document will contain all the columns title for the details which you have to fill in the Details CSV File.</li>
        </ul>
      </li>
      <li>For uploading the music in bulk, you need to add Images, mp3 files, CSV details (as per the pre given template) in the folder accordingly.</li>
      <li>After adding the details in the folder, Zip it again & upload via "Import New Music" link given below.</li>
    </ol>
  </div>
  <?php echo $this->htmlLink('javascript:void(0);', $this->translate("Import New Music"), array('class'=>'buttonlink tickvideo_icon_import','onclick'=>'uploadFile()')); ?>
  <div class="base-uploader" style="display: none;">
      <input class="file-input" id="upload_file" type="file" multiple="multiple" data-url="<?php echo $this->url(array('action' => 'upload-music','id'=>$this->id))?>" data-form-id="#form-upload" name='Filedata' accept=".zip">
  </div>
  <ul class="upload-error-list" id="upload_error_list">
  </ul>
  <div id="upload-status" class="tickvideo_progress">
    <div class="upload-status-current" id="upload-status-current" style="display: none">
      <div class="current-title"></div>
      <img class="progress current-progress" alt="" src="externals/fancyupload/assets/progress-bar/bar.gif" />
    </div>
    <div class="current-text"></div>
  </div>
</div>

<script type="text/javascript">
  scriptJquery("audio").on("play",function(){
    var audio = scriptJquery(this);
    scriptJquery("audio").each(function(){
        if(scriptJquery("audio").index(this) != scriptJquery("audio").index(audio)){
            scriptJquery(this)[0].pause();
        }
    });
  });
  var post_max_size = "<?php echo (int)(ini_get('post_max_size')); ?>";
  var uploadedFiles = [];
  var pendingExtraction = [];
  var currentlyUploading = false;
  var currentlyExtracting = false;
  var uploadError = false;
  var uploadFile = function() {
    document.getElementById('upload_file').click();
  }
  var BaseFileUpload = {
    uploadFile: function (obj, file, iteration, total) {
      //Check upload file size
      var FileSize = obj.files[0].size / 1024 / 1024;
      uploadError = false;
      var url = obj.get('data-url');
      var xhr = new XMLHttpRequest();
      var fd = new FormData();
      uploadedFiles.push(file.name);
      xhr.open("POST", url, true);
      scriptJquery('#upload-status-current').css('display', '');

      // progress bar
      xhr.upload.addEventListener("progress", function(e) {
        var currentFileProgress = -400 + ((e.loaded/e.total) * 100 * 2.5);
        scriptJquery('div#upload-status-current img')[0].css('background-position', currentFileProgress + 'px 0px');
      }, false);

      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          try {
            var res = JSON.parse(xhr.responseText);
            if(res.status){
              if (iteration === total) {
                window.location.href = "<?php echo $this->url(array('module' => 'tickvideo', 'controller' => 'music', 'action' => 'manage','id'=>$this->id),'admin_default')?>";
              }
            } else {
                BaseFileUpload.processUploadError(res.message);
            }
          } catch (err) {
            BaseFileUpload.processUploadError('An error occurred.');
            return false;
          }
          scriptJquery('#upload-status-current').css('display', 'none');
        }
      };
      fd.append('ajax-upload', 'true');
      fd.append(obj.get('name'), file);
      fd.append('format', 'json');
      xhr.send(fd);
    },
    processUploadError: function (errorMessage) {
      new Element('li', {
        'class': 'file-upload-error',
        href: 'javascript:void(0);',
        html: errorMessage,
        events: {
          click: function() {
            this.destroy();
          }
        }
      }).inject(document.getElementById('upload_error_list'), 'top');
    }
  };
  scriptJquery(document).ready(function() {
    scriptJquery('.file-input').each(function (el) {
      scriptJquery(document).on('change',function(obj) {
        currentlyUploading = true;
        var files = this.files;
        var total = files.length;
        var iteration = 0;
        var valid = true;
        for(var i = 0; i < files.length; i++) {
          var FileSize = files[i].size / 1024 / 1024;
          if(FileSize <= post_max_size) {
            iteration++;
            BaseFileUpload.uploadFile($(el), this.files[i], iteration, total);
          }else{
            valid = false;
          }
        }
        if(!valid){
            alert("The size of the file exceeds the limits set on the server.");
            return false;
        }
      });
      scriptJquery(document).on('click',function() {
        this.value = '';
      });
    });
  });
</script>
<style type="text/css">
.tickvideo_manage_form_head > div,
.tickvideo_manage_form_list li > div{
  box-sizing:border-box;
}
</style>
