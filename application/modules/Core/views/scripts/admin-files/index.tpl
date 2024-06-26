<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 10247 2014-05-30 21:34:25Z andres $
 * @author     John
 */
?>
<?php echo $this->partial('_admin_breadcrumb.tpl', 'core', array('parentMenu' => "core_admin_main_layout", 'childMenuItemName' => 'core_admin_main_layout_files')); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . '/externals/uploader/uploader.css'); ?>

<script type="text/javascript">

  var fileCopyUrl = function(url) {
    Smoothbox.open('<div><input type=\'text\' style=\'width:400px\' /><br /><br /><button onclick="Smoothbox.close();">Close</button></div>', {autoResize : true});
    Smoothbox.instance.content.find('input').val(url).focus();
    Smoothbox.instance.content.find('input').select();
    Smoothbox.instance.doAutoResize();
  }

  var uploadFile = function()
  {
    scriptJquery('#upload_file').trigger("click");
  }

  window.addEventListener('load', function() {
    scriptJquery('.admin_file_name').on('onclick onmouseout onmouseover',function(event){
      previewFile(event);
    });
    scriptJquery('.admin_file_preview').click(function(event){
      previewFile(event);
    });
  });

var BaseFileUpload = {
  uploadFile: function (obj, file, iteration, total) {
    var url = obj.attr('data-url');
    var xhr = new XMLHttpRequest();
    var fd = new FormData();
    xhr.open("POST", url, true);
    scriptJquery('#files-status-overall').css('display', 'block');

    // progress bar
    xhr.upload.addEventListener('progress', function (e) {
      var per = (total <= 1 ? e.loaded/e.total : iteration/total) * 100;
      var overAllFileProgress = -400 + ((per) * 2.5);
      scriptJquery('div#files-status-overall img').css('background-position', overAllFileProgress + 'px 0px');
      scriptJquery('div#files-status-overall span').html(per + '%');
    }, false);

    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
        try {
          var res = JSON.parse(xhr.responseText);
        } catch (err) {
          BaseFileUpload.processUploadError('An error occurred.');
          return false;
        }

        if (res['error'] !== undefined) {
          BaseFileUpload.processUploadError(res['error']);
          return false;
        }
        BaseFileUpload.processUploadSuccess(res);

        if (iteration === total) {
          location.reload();
          //BaseFileUpload.showRefresh();
        }
      }
    };
    fd.append('ajax-upload', 'true');
    fd.append(obj.attr('name'), file);
    xhr.send(fd);
  },

  showRefresh: function () {
    scriptJquery('#files-status-overall').css('display', 'none');
    scriptJquery('#upload-complete-message').css('display', '');
  },

  processUploadSuccess: function(response) {
    var uploadedFileList = document.getElementById("uploaded-file-list");
    var uploadedFile = scriptJquery.crtEle('li', {
      'class': 'file file-success',
    }).html('<span class="file-name">' + response['fileName'] + '</span>').appendTo(uploadedFileList);
    if (uploadedFile.offsetParent === null) {
      uploadedFileList.style.display = "block";
    }
  },
  processUploadError: function(errorMessage) {
    var uploadedFileList = document.getElementById("uploaded-file-list");
    var uploadedFile = scriptJquery.crtEle('li', {
      'class': 'file file-error',
      events: {
        click: function() {
          this.destroy();
        }
      }
    }).html('<span class="validation-error" title="Click to remove this entry.">' + errorMessage + '</span>').prependTo(uploadedFileList, 'top');
    // If hidden show upload list
    if (uploadedFile.offsetParent === null) {
      uploadedFileList.style.display = "block";
    }
    scriptJquery('#files-status-overall').css('display', 'none');
    return false;
  },
};

en4.core.runonce.add(function () {
  scriptJquery('#upload-complete-message').css('display', 'none');
  scriptJquery('.file-input').on('change', function () {
    var files = this.files;
    var total = files.length;
    var iteration = 0;
    for(var i = 0; i < files.length; i++) {
      iteration++;
      BaseFileUpload.uploadFile(scriptJquery(this), this.files[i], iteration, total);
    }
  });
  scriptJquery('.file-input').on('click', function () {
    this.value = '';
  });
});

function multiDelete() {
  return confirm("<?php echo $this->translate("Are you sure you want to delete the selected files ?") ?>");
}
function selectAll(obj)
{
  scriptJquery('.checkbox').each(function(){
    scriptJquery(this).prop("checked",scriptJquery(obj).prop("checked"))
  });
}
</script>
<div class="admin_common_top_section">
  <h2 class="page_heading"><?php echo $this->translate("File & Media Manager") ?></h2>
  <p><?php echo $this->translate('You may want to quickly upload images, icons, or other media for use in your layout, announcements, blog entries, etc. You can upload and manage these files here. Move your mouse over a filename to preview an image.') ?></p>
  <?php
  $settings = Engine_Api::_()->getApi('settings', 'core');
  if( $settings->getSetting('user.support.links', 0) == 1 ) {
    echo 'More info: <a href="https://community.socialengine.com/blogs/597/64/file-media-manager" target="_blank">See KB article</a>';
  } 
  ?>	
</div>  
<div>
  <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Upload New Files'), array('id' => 'demo-browse', 'class' => 'admin_link_btn admin_files_upload', 'onclick' => 'uploadFile();')) ?>
</div>
<div id="file-status">
  <div id="files-status-overall" style="display: none;">
    <div class="overall-title">Overall Progress</div>
    <img src='<?php echo $this->layout()->staticBaseUrl . "/externals/fancyupload/assets/progress-bar/bar.gif" ?>' class="progress overall-progress">
    <span>0%</span>
  </div>

  <ul id="uploaded-file-list"></ul>
  <div id="base-uploader-progress"></div>
  <div class="base-uploader">
    <input class="file-input" id="upload_file" type="file" multiple="multiple" data-url="<?php echo $this->url(array('action' => 'upload'))?>" data-form-id="#form-upload" name='Filedata'>
  </div>

  <div id="upload-complete-message" style="display:none;">
    <?php echo $this->htmlLink(array('reset' => false), 'Refresh the page to display new files') ?>
  </div>
</div>
<div class='admin_search'>
  <?php echo $this->formFilter->render($this) ?>
</div>
<?php if( engine_count($this->paginator) ): ?>
  <div>
    <?php echo $this->translate(array('%s file found.', '%s files found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
  </div>
  <?php if($this->existingFiles > 0) { ?>
    <a href="<?php echo $this->url(array('action' => 'sink')) ?>" class="smoothbox sink_fmm_files"><?php echo $this->translate('Sink Existing Files') ?></a>
  <?php } ?>
  <br />
  <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
  <div class="select_fmm_all"><input onclick='selectAll(this);' type='checkbox' class='checkbox' />Select All</div>
  <ul class="fmm_media_list">
      <?php foreach ($this->paginator as $item): ?>
        <?php $storage = Engine_Api::_()->getItem('storage_file', $item->storage_file_id);
        if($storage) {
        $path = $storage->map();
        $copyPath = ($storage->service_id == 2) ?  $path : (_ENGINE_SSL ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $path; 
        ?>
        <li class="fmm_media_file">
         <div class="fmm_media_item">
           <div class="fmm_file_checkbox"><input type='checkbox' class='checkbox' name='delete_<?php echo $item->file_id;?>' value='<?php echo $item->file_id ?>' /></div>
          <div class="fmm_file_options">
            <a href="<?php echo $path; ?>" target="_blank" class="fa fmm_icon_preview" title="<?php echo $this->translate('Preview') ?>"></a>
            <a href="<?php echo $this->url(array('action' => 'rename', 'file_id' => $item->file_id)) ?>" class="smoothbox fa fmm_icon_rename" title="<?php echo $this->translate('Rename') ?>"></a>
            <a href="<?php echo $this->url(array('action' => 'delete', 'file_id' => $item->file_id)) ?>" class="smoothbox fa fmm_icon_delete" title="<?php echo $this->translate('Delete') ?>"></a>
            <a href="<?php echo $this->url(array('action' => 'download', 'file_id' => $item->file_id)) ?>" class="fa fmm_icon_download" title="<?php echo $this->translate('Download') ?>"></a>
            <a href="javascript:void(0);" onclick="fileCopyUrl('<?php echo $copyPath; ?>');" class="fa fmm_icon_url" title="<?php echo $this->translate('Copy URL') ?>"></a>
          </div>
          <div class="fmm_file_preview fmm_file_preview_image">
            <?php if($item->extension == 'pdf'): ?>
                <img src="application/modules/Core/externals/images/admin/file-icons/pdf.png">
              <?php elseif(engine_in_array($item->extension, array('text', 'txt'))): ?>
                <img src="application/modules/Core/externals/images/admin/file-icons/text.png">
              <?php elseif(engine_in_array($item->extension, array('mpeg', 'x-realaudio', 'wav', 'amr', 'mp3', 'ogg','midi','x-ms-wma', 'x-ms-wax', 'x-matroska'))): ?>
                <img src="application/modules/Core/externals/images/admin/file-icons/audio.png">
              <?php elseif(engine_in_array($item->extension, array('mp4', 'flv'))): ?>
                <img src="application/modules/Core/externals/images/admin/file-icons/video.png">
              <?php elseif(engine_in_array($item->extension, array('zip', 'tar'))): ?>
                <img src="application/modules/Core/externals/images/admin/file-icons/zip.png">
              <?php elseif(engine_in_array($item->extension, array('jpeg', 'jpg', 'gif', 'png', 'tiff','webp'))): ?>
                <img src="<?php echo $path; ?>" />
              <?php else: ?>
                <img src="application/modules/Core/externals/images/admin/file-icons/default.png">
               <?php endif; ?>
          </div>
          <div class="fmm_file_name"><?php echo $item->name . '.'.$item->extension; ?></div>
          </div>
        </li>
      <?php } endforeach; ?>
      </ul>
  <div class='buttons'>
    <button type='submit'><?php echo $this->translate("Delete Selected") ?></button>
  </div>
  </form>
  <div>
    <?php echo $this->paginationControl($this->paginator); ?>
  </div>
<?php else: ?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are no files uploaded by you yet.") ?>
    </span>
  </div>
<?php endif; ?> 
<script type="application/javascript">
  scriptJquery('.core_admin_main_layout').parent().addClass('active');
  scriptJquery('.core_admin_main_layout_files').addClass('active');
</script>
