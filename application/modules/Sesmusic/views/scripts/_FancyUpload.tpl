<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _FancyUpload.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>

<?php 
  $uploadCheck = Zend_Controller_Front::getInstance()->getRequest()->getParam('upload', null);

  $this->headScript()
    ->appendFile($this->layout()->staticBaseUrl . 'externals/fancyupload/Swiff.Uploader.js')
    ->appendFile($this->layout()->staticBaseUrl . 'externals/fancyupload/Fx.ProgressBar.js')
    ->appendFile($this->layout()->staticBaseUrl . 'externals/fancyupload/FancyUpload2.js');
    
  $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'externals/fancyupload/fancyupload.css');
  
  $this->headTranslate(array(
    'Overall Progress ({total})', 'File Progress', 'Uploading "{name}"',
    'Upload: {bytesLoaded} with {rate}, {timeRemaining} remaining.', '{name}',
    'Remove', 'Remove this song', 'Upload failed',
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

<script type="text/javascript">

  <?php if($uploadCheck == 'song') { ?>
    var uploadMultiple = false; 
  <?php } else { ?>
    var uploadMultiple = true; 
  <?php } ?>
  
  en4.core.runonce.add(function() {
    var init = function() {
      var uploadCount = 0;
      var uploaderSwf = '<?php echo $this->layout()->staticBaseUrl . 'externals/fancyupload/Swiff.Uploader.swf' ?>'
      var up = new FancyUpload2($('demo-status'), $('demo-list'), { // options object
        // we console.log infos, remove that in production!!
        verbose: <?php echo ( APPLICATION_ENV == 'development' ? 'true' : 'false') ?>,
        appendCookieData: true,
        timeLimit: 0,
        multiple:uploadMultiple,
        // set cross-domain policy file
        policyFile : '<?php echo (_ENGINE_SSL ? 'https://' : 'http://') 
            . $_SERVER['HTTP_HOST'] . $this->url(array(
              'controller' => 'cross-domain'), 
              'default', true) ?>',

        // url is read from the form, so you just have to change one place
        url: $('form-upload-music').action + '?ul=1',

        // path to the SWF file
        path: uploaderSwf,

        // remove that line to select all files, or edit it, add more items
        typeFilter: {
          'Music (*.mp3,*.m4a,*.aac,*.mp4)': '*.mp3; *.m4a; *.aac; *.mp4'
        },

        // this is our browse button, *target* is overlayed with the Flash movie
        target: 'demo-browse',

        // graceful degradation, onLoad is only called if all went well with Flash
        onLoad: function() {
          $('demo-status').removeClass('hide'); // we show the actual UI
          scriptJquery('#demo-fallback').remove(); // ... and hide the plain form

          // We relay the interactions with the overlayed flash to the link
          this.target.addEvents({
            click: function() {
              return false;
            },
            mouseenter: function() {
              this.addClass('hover');
            },
            mouseleave: function() {
              this.removeClass('hover');
              this.blur();
            },
            mousedown: function() {
              this.focus();
            }
          });

          // Interactions for the 2 other buttons
          if ($('submit-wrapper'))
            $('submit-wrapper').hide();
          $('demo-clear').addEvent('click', function() {
            up.remove(); // remove all files
            if ($('fancyuploadfileids'))
              $('fancyuploadfileids').value = '';
            return false;
          });

        },

        /**
         * Is called when files were not added, "files" is an array of invalid File classes.
         *
         * This example creates a list of error elements directly in the file list, which
         * hide on click.
         */
        onSelectFail: function(files) {
          files.each(function(file) {
            new Element('li', {
              'class': 'validation-error',
              html: file.validationErrorMessage || file.validationError,
              title: MooTools.lang.get('FancyUpload', 'removeTitle'),
              events: {
                click: function() {
                  this.destroy();
                }
              }
            }).inject(this.list, 'top');
          }, this);
        },

        onComplete: function hideProgress() {
          var demostatuscurrent = document.getElementById("demo-status-current");
          var demostatusoverall = document.getElementById("demo-status-overall");
          var demosubmit = document.getElementById("submit-wrapper");

          demostatuscurrent.style.display = "none";
          demostatusoverall.style.display = "none";
          if (demosubmit)
            demosubmit.style.display = "block";
        },

        onFileStart: function() {
          uploadCount += 1;
        },
        onFileRemove: function(file) {
          uploadCount -= 1;
          file_id = file.song_id;
          request = scriptJquery.ajax({
            'format' : 'json',
            'url' : '<?php echo $this->url(array('action' => 'remove-song'), 'sesmusic_general') ?>',
            'data': {
              'format': 'json',
              'albumsong_id' : file_id
            },
            success : function(responseJSON) {
              return false;
            }
          });
          
          var fileids = $('fancyuploadfileids');

          if ($("demo-list").getChildren('li').length == 0)
          {
            var democlear  = document.getElementById("demo-clear");
            var demolist   = document.getElementById("demo-list");
            var demosubmit = document.getElementById("submit-wrapper");
            democlear.style.display  = "none";
            demolist.style.display   = "none";
            demosubmit.style.display = "none";
          }
          if (fileids)
            fileids.value = fileids.value.replace(file_id, "");
        },
        onSelectSuccess: function(file) {
          $('demo-list').style.display = 'block';
          var democlear = document.getElementById("demo-clear");
          var demostatuscurrent = document.getElementById("demo-status-current");
          var demostatusoverall = document.getElementById("demo-status-overall");

          democlear.style.display = "inline";
          demostatuscurrent.style.display = "block";
          demostatusoverall.style.display = "block";
          up.start();
        },
        /**
         * This one was directly in FancyUpload2 before, the event makes it
         * easier for you, to add your own response handling (you probably want
         * to send something else than JSON or different items).
         */
        onFileSuccess: function(file, response) { 
          var json = new Hash(JSON.decode(response, true) || { });

          if (json.get('status') == '1') {
            file.element.addClass('file-success');
            file.info.set('html', '<span>' + '<?php echo $this->string()->escapeJavascript($this->translate('Upload complete.')) ?>' + '</span>');
            file.song_id   = json.get('albumsong_id');
            var fileids = $('fancyuploadfileids');
            if (fileids) {
              if (fileids.value.length)
                fileids.value += ' ';
              fileids.value += json.get('albumsong_id');
            }
          } else {
            file.element.addClass('file-failed');
            file.info.set('html', '<span><?php echo $this->string()->escapeJavascript($this->translate('An error occurred:')) ?></span> ' + (json.get('error') ? (json.get('error')) : response));
          }
        },

        /**
         * onFail is called when the Flash movie got bashed by some browser plugin
         * like Adblock or Flashblock.
         */
        onFail: function(error) {
          switch (error) {
            case 'hidden': // works after enabling the movie and clicking refresh
              alert('<?php echo $this->string()->escapeJavascript($this->translate("To enable the embedded uploader, unblock it in your browser and refresh (see Adblock).")) ?>');
              break;
            case 'blocked': // This no *full* fail, it works after the user clicks the button
              alert('<?php echo $this->string()->escapeJavascript($this->translate("To enable the embedded uploader, enable the blocked Flash movie (see Flashblock).")) ?>');
              break;
            case 'empty': // Oh oh, wrong path
              alert('<?php echo $this->string()->escapeJavascript($this->translate("A required file was not found, please be patient and we'll fix this.")) ?>');
              break;
            case 'flash': // no flash 9+
              alert('<?php echo $this->string()->escapeJavascript($this->translate("To enable the embedded uploader, install the latest Adobe Flash plugin.")) ?>');
              break;
          }
        }
      });
    }
    
    if( 'FancyUpload2' in window ) {
      init();
    } else {
      var count = 0;
      var callback = function() {
        count++;
        if( count >= 3 ) {
          init();
        }
      }
      Asset.css('<?php echo $this->layout()->staticBaseUrl . 'externals/fancyupload/fancyupload.css' ?>');
      Asset.javascript('<?php echo $this->layout()->staticBaseUrl . 'externals/fancyupload/Swiff.Uploader.js' ?>', {onLoad : callback});
      Asset.javascript('<?php echo $this->layout()->staticBaseUrl . 'externals/fancyupload/Fx.ProgressBar.js' ?>', {onLoad : callback});
      Asset.javascript('<?php echo $this->layout()->staticBaseUrl . 'externals/fancyupload/FancyUpload2.js' ?>', {onLoad : callback});
    }
  });
</script>

<fieldset id="demo-fallback">
  <legend><?php echo $this->translate("File Upload") ?></legend>
  <p>
    <?php echo $this->translate('Click "Browse..." to select the MP3 file you would like to upload.') ?>
  </p>
  <label for="demo-musiclabel">
    <?php echo $this->translate('Upload Songs:') ?>
    <input id="<?php echo $this->element->getName() ?>"
           type="file"
           name="<?php echo $this->element->getName() ?>"
           value="<?php echo $this->element->getValue() ?>"
           accept="audio/*" />
  </label>
</fieldset>
<?php $uploadoption = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.uploadoption', 'myComputer'); ?>
<?php if ($uploadoption == 'both' || $uploadoption == 'myComputer'): ?>
<div id="demo-status" class="hide">
<?php else: ?>
<div id="demo-status" class="hide" style="display:none;">
<?php endif; ?> 
  <div>
    <?php if($uploadCheck == 'song') { ?>
      <?php echo $this->translate('Select song from your computer.') ?>
    <?php } else { ?>
      <?php echo $this->translate('Select songs from your computer.') ?>
    <?php } ?>
  </div>
  <div>
    <a class="buttonlink icon_sesmusic_new" href="javascript:void(0);" id="demo-browse"><?php echo $this->translate('Select Songs') ?></a>
    <a class="buttonlink icon_clearlist" href="javascript:void(0);" id="demo-clear"><?php echo $this->translate('Clear List') ?></a>
  </div>

  <div class="demo-status-overall" id="demo-status-overall" style="display:none">
    <div class="overall-title"></div>
    <img src="<?php echo $this->baseUrl() . '/externals/fancyupload/assets/progress-bar/bar.gif'; ?>" class="progress overall-progress" alt="" />
  </div>
  <div class="demo-status-current" id="demo-status-current" style="display:none">
    <div class="current-title"></div>
    <img src="<?php echo $this->baseUrl() . '/externals/fancyupload/assets/progress-bar/bar.gif'; ?>" class="progress current-progress" alt="" />
  </div>
  <div class="current-text"></div>
</div>
<ul id="demo-list"></ul>
