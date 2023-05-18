<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _FancyUpload_sample.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>

<?php
$this->headScript()
    ->appendFile($this->layout()->staticBaseUrl . 'externals/fancyupload/Swiff.Uploader.js')
    ->appendFile($this->layout()->staticBaseUrl . 'externals/fancyupload/Fx.ProgressBar.js')
    ->appendFile($this->layout()->staticBaseUrl . 'externals/fancyupload/FancyUpload2.js');
  $this->headLink()
    ->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/uploader.css');
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

<script type="text/javascript">
  en4.core.runonce.add(function() {
    window.uploadCount = 0;
    window.sampleup = null;
    
	// our uploader instance
	sampleup = new FancyUpload2($('sample-demo-status'), $('sample-demo-list'), { // options object
      // we console.log infos, remove that in production!!
      verbose: false,
      multiple: false,
      appendCookieData: true,
      timeLimit: 0,
      // set cross-domain policy file
      policyFile : '<?php echo (_ENGINE_SSL ? 'https://' : 'http://') 
          . $_SERVER['HTTP_HOST'] . $this->url(array(
            'controller' => 'cross-domain'), 
            'default', true) ?>',
      
      // url is read from the form, so you just have to change one place
      url: "<?php  echo $this->url(array('module' => 'sesvideo', 'controller' => 'index', 'action' => 'create'), 'default', true); ?>?ul_sample=1",
      
      // path to the SWF file
      path: '<?php echo $this->layout()->staticBaseUrl . 'externals/fancyupload/Swiff.Uploader.swf'; ?>',
      
      // remove that line to select all files, or edit it, add more items
      // 'Videos (*.4xm, *.IFF, *.MTV, *.RoQ, *.aac, *.ac3, *.aiff, *.alaw, *.amr, *.apc, *.ape, *.asf, *.asf_stream, *.au, *.avi, *.avs, *.bfi, *.c93, *.daud, *.dirac, *.dsicin, *.dts, *.dv, *.dv1394, *.dxa, *.ea, *.ea_cdata, *.eac3, *.f32be, *.f32le, *.f64be, *.f64le, *.ffm, *.film_cpk, *.flac, *.flic, *.flv, *.gsm, *.gxf, *.h261, *.h263, *.h264, *.idcin, *.image2, *.image2pipe, *.ingenient, *.ipmovie, *.lmlm4, *.m4v, *.matroska, *.mjpeg, *.mlp, *.mm, *.mmf, *.mov, *.mp4, *.m4a, *.3gp, *.3g2, *.mj2, *.mp3, *.mpc, *.mpc8, *.mpeg, *.mpegts, *.mpegtsraw, *.mpegvideo, *.msnwctcp, *.mulaw, *.mvi, *.mxf, *.nsv, *.nut, *.nuv, *.ogg, *.oma, *.oss, *.psxstr, *.pva, *.rawvideo, *.redir, *.rl2, *.rm, *.rpl, *.rtsp, *.s16be, *.s16le, *.s24be, *.s24le, *.s32be, *.s32le, *.s8, *.sdp, *.shn, *.siff, *.smk, *.sol, *.swf, *.thp, *.tiertexseq, *.tta, *.txd, *.u16be, *.u16le, *.u24be, *.u24le, *.u32be, *.u32le, *.u8, *.vc1, *.vc1test, *.video4linux, *.video4linux2, *.vmd, *.voc, *.wav, *.wc3movie, *.wsaud, *.wsvqa, *.wv, *.xa, *.yuv4mpegpipe)': '*.4xm; *.IFF; *.MTV; *.RoQ; *.aac; *.ac3; *.aiff; *.alaw; *.amr; *.apc; *.ape; *.asf; *.asf_stream; *.au; *.avi; *.avs; *.bfi; *.c93; *.daud; *.dirac; *.dsicin; *.dts; *.dv; *.dv1394; *.dxa; *.ea; *.ea_cdata; *.eac3; *.f32be; *.f32le; *.f64be; *.f64le; *.ffm; *.film_cpk; *.flac; *.flic; *.flv; *.gsm; *.gxf; *.h261; *.h263; *.h264; *.idcin; *.image2; *.image2pipe; *.ingenient; *.ipmovie; *.lmlm4; *.m4v; *.matroska; *.mjpeg; *.mlp; *.mm; *.mmf; *.mov; *.mp4; *.m4a; *.3gp; *.3g2; *.mj2; *.mp3; *.mpc; *.mpc8; *.mpeg; *.mpegts; *.mpegtsraw; *.mpegvideo; *.msnwctcp; *.mulaw; *.mvi; *.mxf; *.nsv; *.nut; *.nuv; *.ogg; *.oma; *.oss; *.psxstr; *.pva; *.rawvideo; *.redir; *.rl2; *.rm; *.rpl; *.rtsp; *.s16be; *.s16le; *.s24be; *.s24le; *.s32be; *.s32le; *.s8; *.sdp; *.shn; *.siff; *.smk; *.sol; *.swf; *.thp; *.tiertexseq; *.tta; *.txd; *.u16be; *.u16le; *.u24be; *.u24le; *.u32be; *.u32le; *.u8; *.vc1; *.vc1test; *.video4linux; *.video4linux2; *.vmd; *.voc; *.wav; *.wc3movie; *.wsaud; *.wsvqa; *.wv; *.xa; *.yuv4mpegpipe'
      
      typeFilter: {
        //'Videos (*.4xm, *.IFF, *.MTV, *.RoQ, *.aac, *.ac3, *.aiff, *.alaw, *.amr, *.apc, *.ape, *.asf, *.asf_stream, *.au, *.avi, *.avs, *.bfi, *.c93, *.daud, *.dirac, *.dsicin, *.dts, *.dv, *.dv1394, *.dxa, *.ea, *.ea_cdata, *.eac3, *.f32be, *.f32le, *.f64be, *.f64le, *.ffm, *.film_cpk, *.flac, *.flic, *.flv, *.gsm, *.gxf, *.h261, *.h263, *.h264, *.idcin, *.image2, *.image2pipe, *.ingenient, *.ipmovie, *.lmlm4, *.m4v, *.matroska, *.mjpeg, *.mlp, *.mm, *.mmf, *.mov, *.mp4, *.m4a, *.3gp, *.3g2, *.mj2, *.mp3, *.mpc, *.mpc8, *.mpeg, *.mpegts, *.mpegtsraw, *.mpegvideo, *.msnwctcp, *.mulaw, *.mvi, *.mxf, *.nsv, *.nut, *.nuv, *.ogg, *.oma, *.oss, *.psxstr, *.pva, *.rawvideo, *.redir, *.rl2, *.rm, *.rpl, *.rtsp, *.s16be, *.s16le, *.s24be, *.s24le, *.s32be, *.s32le, *.s8, *.sdp, *.shn, *.siff, *.smk, *.sol, *.swf, *.thp, *.tiertexseq, *.tta, *.txd, *.u16be, *.u16le, *.u24be, *.u24le, *.u32be, *.u32le, *.u8, *.vc1, *.vc1test, *.video4linux, *.video4linux2, *.vmd, *.voc, *.wav, *.wc3movie, *.wsaud, *.wsvqa, *.wv, *.xa, *.yuv4mpegpipe)': '*.4xm; *.IFF; *.MTV; *.RoQ; *.aac; *.ac3; *.aiff; *.alaw; *.amr; *.apc; *.ape; *.asf; *.asf_stream; *.au; *.avi; *.avs; *.bfi; *.c93; *.daud; *.dirac; *.dsicin; *.dts; *.dv; *.dv1394; *.dxa; *.ea; *.ea_cdata; *.eac3; *.f32be; *.f32le; *.f64be; *.f64le; *.ffm; *.film_cpk; *.flac; *.flic; *.flv; *.gsm; *.gxf; *.h261; *.h263; *.h264; *.idcin; *.image2; *.image2pipe; *.ingenient; *.ipmovie; *.lmlm4; *.m4v; *.matroska; *.mjpeg; *.mlp; *.mm; *.mmf; *.mov; *.mp4; *.m4a; *.3gp; *.3g2; *.mj2; *.mp3; *.mpc; *.mpc8; *.mpeg; *.mpegts; *.mpegtsraw; *.mpegvideo; *.msnwctcp; *.mulaw; *.mvi; *.mxf; *.nsv; *.nut; *.nuv; *.ogg; *.oma; *.oss; *.psxstr; *.pva; *.rawvideo; *.redir; *.rl2; *.rm; *.rpl; *.rtsp; *.s16be; *.s16le; *.s24be; *.s24le; *.s32be; *.s32le; *.s8; *.sdp; *.shn; *.siff; *.smk; *.sol; *.swf; *.thp; *.tiertexseq; *.tta; *.txd; *.u16be; *.u16le; *.u24be; *.u24le; *.u32be; *.u32le; *.u8; *.vc1; *.vc1test; *.video4linux; *.video4linux2; *.vmd; *.voc; *.wav; *.wc3movie; *.wsaud; *.wsvqa; *.wv; *.xa; *.yuv4mpegpipe'
      },
      
      // this is our browse button, *target* is overlayed with the Flash movie
      target: 'sample-demo-browse',
      
      // graceful degradation, onLoad is only called if all went well with Flash
      onLoad: function() {
        scriptJquery('#sample-demo-status').removeClass('hide'); // we show the actual UI
        scriptJquery('#sample-demo-fallback').remove(); // ... and hide the plain form
        
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
        
      },
      
      // Edit the following lines, it is your custom event handling
      
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
        var demostatuscurrent = document.getElementById("sample-demo-status-current");
        var demostatusoverall = document.getElementById("sample-demo-status-overall");
        var demosubmit = document.getElementById("upload-wrapper");
        
        //demostatuscurrent.style.display = "none";
        demostatusoverall.style.display = "none";
        //demosubmit.style.display = "block";
      },
      
      onFileStart: function() {
        uploadCount += 1;
      },
      onFileRemove: function(file) {
        uploadCount -= 1;
        file_id = file.photo_id;
        var fileids = document.getElementById('fancyuploadfileids');
        
        var demobrowse = document.getElementById("sample-demo-browse");
        var demoupload = document.getElementById("sample-demo-upload");
        var demolist = document.getElementById("sample-demo-list");
        var demosubmit = document.getElementById("upload-wrapper");
        
        demolist.style.display = "none";
        demosubmit.style.display = "none";
        demobrowse.style.display = "block"
        demoupload.style.display = "none";
        
        //clear out the progress bar
        var demostatusoverall = document.getElementById("sample-demo-status-overall");
        demostatusoverall.style.display = "none";
        
        fileids.value = fileids.value.replace(file_id, "");
      },
      onSelectSuccess: function(file) {
        $('sample-demo-list').style.display = 'block';
        
        var demoupload = document.getElementById("sample-demo-upload");
        var demobrowse = document.getElementById("sample-demo-browse");
        
        var demostatuscurrent = document.getElementById("sample-demo-status-current");
        var demostatusoverall = document.getElementById("sample-demo-status-overall");
        
        demoupload.style.display = "inline";
        demobrowse.style.display = "none";
        
        //demostatuscurrent.style.display = "block";
        demostatusoverall.style.display = "block";
        //up.start();
      } ,
      /**
       * This one was directly in FancyUpload2 before, the event makes it
       * easier for you, to add your own response handling (you probably want
       * to send something else than JSON or different items).
       */
      onFileSuccess: function(file, response) {
        var json = new Hash(JSON.decode(response, true) || {});
        
        if (json.get('status') == '1') {
          file.element.addClass('file-success');
          file.info.set('html', '<span>Upload complete.</span>');
          //var fileids = document.getElementById('fancyuploadfileids');
          //fileids.value = fileids.value + json.get('photo_id') + " ";
          //file.photo_id = json.get('photo_id');
          $('code').value=json.get('code');
          $('samplevideo_id').value=json.get('samplevideo_id');
          //$('form-upload').submit();
        } else {
          file.element.addClass('file-failed');
          file.info.set('html', '<br/><b>Upload has failed: </b> The video you tried to upload exceeds the maximum file size. <br/>' + (json.get('error') ? (json.get('error')) : response));
          //file.info.set('html', '<span>An error occurred:</span> ' + (json.get('error') ? (json.get('error') + ' #' + json.get('code')) : response));
        }
      },
      
    });

    var startUploadsample = window.startUploadsample = function() {
      $('type-wrapper').style.display = "none";
      $('sample-demo-upload').style.display = "none";
      sampleup.start();
    }
  });

  function showSaveButtonSample(){
     if(!$('input1_sample').value){
       $('upload-wrapper').hide();
     }
     else {
       $('upload-wrapper').show();
     }
  }
</script>

<input type="hidden" name="<?php echo $this->name;?>" id="fancyuploadfileids" value ="" />
<fieldset id="sample-demo-fallback">
  <legend><?php echo $this->translate('File Upload');?></legend>
  <label for="demo-photoupload">
    <?php echo $this->translate('Upload a Video:');?>
    <input type="file" id="input1_sample" name="Filedata_sample" accept="video/*" />
  </label>
</fieldset>

<div id="sample-demo-status" class="sesbasic_uploader_status hide">
  <div>
    <?php echo $this->translate('Click "Choose Sample Video" link to select a sample video from your computer. After you have selected the video, click on “Upload Sample Video” link at the bottom to begin uploading the file. Please wait while your sample video is being uploaded. When your upload is finished, your sample video will be processed - you will be notified when it is ready to be viewed. [maximum allowed size for video is 20 mb]');?>
		<!--<p><?php //echo $this->translate('[maximum allowed size for video is %s mb]',str_replace('M','',ini_get('upload_max_filesize'))) ?></p>-->
  </div>
  <div>
    <a class="buttonlink sesvideo_icon_video_add" href="javascript:void(0);" id="sample-demo-browse"><?php echo $this->translate('Choose Sample Video');?></a>
  </div>
  <div class="demo-status-overall" id="sample-demo-status-overall" style="display:none">
    <div class="overall-title"></div>
    <img src="<?php echo $this->baseUrl() . '/externals/fancyupload/assets/progress-bar/bar.gif';?>" class="progress overall-progress" />
  </div>
  <div class="demo-status-current" id="sample-demo-status-current" style="display:none">
    <div class="current-title"></div>
    <img src="<?php echo $this->baseUrl() . '/externals/fancyupload/assets/progress-bar/bar.gif';?>" class="progress current-progress" />
  </div>
  <div class="current-text"></div>
</div>
<ul id="sample-demo-list" class="sesbasic_uploader_list"></ul>

<div><br/>
  <a class="buttonlink sesvideo_icon_video_add" href="javascript:startUploadsample();" id="sample-demo-upload" style='display:none;'><?php echo $this->translate('Upload Sample Video');?></a>
</div>
