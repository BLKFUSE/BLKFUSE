<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: create.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/getVideoId.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>
<?php 
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . "externals/selectize/css/normalize.css");
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/selectize/js/selectize.js'); 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css');?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/uploader.css');?>

<script type="text/javascript">
  en4.core.runonce.add(function() {
    
    var tagsUrl = '<?php echo $this->url(array('controller' => 'tag', 'action' => 'suggest'), 'default', true) ?>';
    var validationUrl = '<?php echo $this->url(array('module' => 'sesvideo', 'controller' => 'index', 'action' => 'validation'), 'default', true) ?>';
    var validationErrorMessage = "<?php echo $this->translate("We could not find a video there - please check the URL and try again. If you are sure that the URL is valid, please click %s to continue.", "<a href='javascript:void(0);' onclick='javascript:ignoreValidation();'>".$this->translate("here")."</a>"); ?>";
    var checkingUrlMessage = '<?php echo $this->string()->escapeJavascript($this->translate('Checking URL...')) ?>';
    var current_code;
    var ignoreValidation = window.ignoreValidation = function() {
      document.getElementById('upload-wrapper').style.display = "inline-block";
      document.getElementById('buttons-wrapper').style.display = "inline-block";
      document.getElementById('validation').style.display = "none";
      document.getElementById('code').value = current_code;
      document.getElementById('ignore').value = true;
    }
     document.getElementById('upload-wrapper').style.display = "none";
     document.getElementById('buttons-wrapper').style.display = "none";
    var updateTextFields = window.updateTextFields = function() {
      var video_element = document.getElementById("type");
      var url_element = document.getElementById("url-wrapper");
      var file_element = document.getElementById("Filedata-wrapper");
      var sample_file_element = document.getElementById("sample_file-wrapper");
      var payment_type = document.getElementById("payment_type-wrapper");
      var submit_element = document.getElementById("upload-wrapper");
      scriptJquery('#orText').show();
      // clear url if input field on change
      //document.getElementById('code').value = "";
      
      document.getElementById('upload-wrapper').style.display = "none";
      document.getElementById('buttons-wrapper').style.display = "none";

      
      // If video source is empty
      if( video_element.value == 0 ) {
				//if(document.getElementById('photo_id-wrapper'))
					//document.getElementById('photo_id-wrapper').style.display = 'none';
        document.getElementById('url').value = "";
        file_element.style.display = "none";
        if(sample_file_element)
          sample_file_element.style.display = "none";
        if(payment_type)
          payment_type.style.display = 'none';
        url_element.style.display = "none";
				scriptJquery('#rotation-wrapper').hide();
				scriptJquery('#price-wrapper').hide();
				scriptJquery('#title-wrapper').show();
				scriptJquery('#description-wrapper').show();
        return;
      } else if( video_element.value == 'iframely' ) {
        // If video source is youtube or vimeo
        document.getElementById('url').value = '';
        document.getElementById('code').value = '';
        document.getElementById('id').value = '';
        scriptJquery('#rotation-wrapper').hide();
        file_element.style.display = "none";
        //rotation_element.style.display = "none";
        url_element.style.display = "block";
        if(payment_type)
          payment_type.style.display = 'block';
        return;
      }
      /*else if( document.getElementById('code').value && document.getElementById('url').value ) {
				//if(document.getElementById('photo_id-wrapper'))
					//document.getElementById('photo_id-wrapper').style.display = 'none';
        document.getElementById('type-wrapper').style.display = "none";
        file_element.style.display = "none";
        document.getElementById('upload-wrapper').style.display = "block";
        document.getElementById('buttons-wrapper').style.display = "block";
				if(video_element.value == 5){	
					scriptJquery('#title-wrapper').hide();
					scriptJquery('#description-wrapper').hide();
				}
        return;
      }*/ 
      else if( video_element.value == 1 || video_element.value == 2 || video_element.value == 4 || video_element.value == 5 || video_element.value == 16 || video_element.value == 17 || video_element.value == 107 || video_element.value  == 105 || video_element.value  == 106) {
				
				if(video_element.value == 5){
					if(document.getElementById('photo_id-wrapper'))
						document.getElementById('photo_id-wrapper').style.display = 'none';	
					scriptJquery('#title-wrapper').hide();
          document.getElementById('title').value = "0";
					scriptJquery('#description-wrapper').hide();
				}else{
					if(document.getElementById('photo_id-wrapper'))
						document.getElementById('photo_id-wrapper').style.display = 'block';	
					scriptJquery('#title-wrapper').show();
					scriptJquery('#description-wrapper').show();	
				}
				scriptJquery('#rotation-wrapper').hide();
				scriptJquery('#price-wrapper').hide();
				//if(document.getElementById('photo_id-wrapper'))
					//document.getElementById('photo_id-wrapper').style.display = 'none';
        // If video source is youtube or youtubePlaylist or vimeo or daily motion
        document.getElementById('url').value = "";
        document.getElementById('code').value = "";
        file_element.style.display = "none";
        if(sample_file_element)
          sample_file_element.style.display = "none";
        if(payment_type)
          payment_type.style.display = 'block';
        url_element.style.display = "block";
				if(video_element.value == 17 || video_element.value  == 105 || video_element.value  == 106 || video_element.value == 107){
					url_element.style.display = "none";
          if(document.getElementById('embedUrl-wrapper'))
					document.getElementById('embedUrl-wrapper').style.display = 'block';
				}else{
          if(document.getElementById('embedUrl-wrapper'))
					document.getElementById('embedUrl-wrapper').style.display = 'none';
				}
        return;
      } else if( video_element.value == 3 ) {
				<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.direct.video', 0)){ 
								$show = 'hide';
							}else{
								$show="show";
							}
				?>
				scriptJquery('#rotation-wrapper').<?php echo $show; ?>();
				scriptJquery('#price-wrapper').<?php echo $show; ?>();
				scriptJquery('#title-wrapper').show();
				scriptJquery('#description-wrapper').show();
				if(document.getElementById('photo_id-wrapper'))
					document.getElementById('photo_id-wrapper').style.display = 'block';
        // If video source is from computer
        document.getElementById('url').value = "";
        document.getElementById('code').value = "";
        file_element.style.display = "block";
        document.getElementById('upload_file').style.display = "block";
        if(sample_file_element)
          sample_file_element.style.display = "block";
        if(payment_type)
          payment_type.style.display = 'block';
        url_element.style.display = "none";
				scriptJquery('#embedUrl-wrapper').hide();
        document.getElementById('upload-wrapper').style.display = "";
        document.getElementById('buttons-wrapper').style.display = "inline-block";
        return;
      } else if( document.getElementById('id').value ) {
				if(video_element.value == 5){	
					scriptJquery('#title-wrapper').hide();
					scriptJquery('#description-wrapper').hide();
				}
				//if(document.getElementById('photo_id-wrapper'))
					//document.getElementById('photo_id-wrapper').style.display = 'none';
        // if there is video_id that means this form is returned from uploading 
        // because some other required field
        document.getElementById('type-wrapper').style.display = "none";
        file_element.style.display = "none";
        if(sample_file_element)
          sample_file_element.style.display = "none";
        if(payment_type)
          payment_type.style.display = 'block';
        document.getElementById('upload-wrapper').style.display = "inline-block";
        document.getElementById('buttons-wrapper').style.display = "inline-block";
        return;
      }
    }
    var video = window.video = {
      active : false,
      debug : false,
      currentUrl : null,
      currentTitle : null,
      currentDescription : null,
      currentImage : 0,
      currentImageSrc : null,
      imagesLoading : 0,
      images : [],
      maxAspect : (10 / 3), //(5 / 2), //3.1,
      minAspect : (3 / 10), //(2 / 5), //(1 / 3.1),
      minSize : 50,
      maxPixels : 500000,
      monitorInterval: null,
      monitorLastActivity : false,
      monitorDelay : 500,
      maxImageLoading : 5000,
      attach : function() {
        var bind = this;
        scriptJquery(document).on('keyup', '#url',function(event) {
          bind.monitorLastActivity = (new Date).valueOf();
        });
        scriptJquery("#url-element").append("<p style='display:none' class='description' id='validation'>test</p>")
        var lastBody = '';
        var lastMatch = '';
        var video_element = document.getElementById('type');
        setInterval(function() {
          var body = document.getElementById('url');
          // Ignore if no change or url matches
          if( body.value == lastBody || bind.currentUrl ) {
            return;
          }
          // Ignore if delay not met yet
          if( (new Date).valueOf() < bind.monitorLastActivity + bind.monitorDelay ) {
            return;
          }
         // Check for link
          var m = body.value.match(/https?:\/\/([-\w\.]+)+(:\d+)?(\/([-#:\w/_\.]*(\?\S+)?)?)?/);
          if( $type(m) && $type(m[0]) && lastMatch != m[0] ) {
            if (video_element.value == 1){
              video.youtube(body.value);
            } else if(video_element.value == 4){
							video.dailymotion(body.value);	
						}else if(video_element.value == 5){
							video.youtubePlaylist(body.value);	
						}else if(video_element.value == 16){
							video.fromURL(body.value);	
						}else if(video_element.value == 2) {
              video.vimeo(body.value);
            }else{
              video.iframely(body.value);
            }
          }
          lastBody = body.value;
        },250);
      },
      iframely : function(url) {
          (scriptJquery.ajax({
            'url' : validationUrl,
            'data' : {
              'format': 'json',
              'uri' : url,
            },
            'onRequest' : function() {
              document.getElementById('validation').style.display = "block";
              document.getElementById('validation').innerHTML = checkingUrlMessage;
              document.getElementById('upload-wrapper').style.display = "none";
              document.getElementById('buttons-wrapper').style.display = "none";
            },
            success : function(response1) {
              let response = JSON.parse(response1);
              if( response.valid ) {
                document.getElementById('upload-wrapper').style.display = "inline-block";
                document.getElementById('buttons-wrapper').style.display = "inline-block";
                document.getElementById('orText').style.display = "inline-block";
                document.getElementById('validation').style.display = "none";
                //document.getElementById('code').value = response.iframely.code;
                  if(!document.getElementById('title').value)
                document.getElementById('title').value = response.iframely.title;
                  if(!document.getElementById('description').value)
                document.getElementById('description').value = response.iframely.description;
                tinymce.get('description').setContent(response.iframely.description);
                document.getElementById('validation').value = "none";
              } else {
                document.getElementById('upload-wrapper').style.display = "none";
                document.getElementById('buttons-wrapper').style.display = "none";
                document.getElementById('validation').innerHTML = validationErrorMessage;
                document.getElementById('code').value = '';
              }
            }
          }));
      },
			fromURL:function(url,mUrl){
			
        if( url ) {
          (scriptJquery.ajax({
            'format': 'html',
            'url' : validationUrl,
            'data' : {
              'ajax' : true,
              'code' : url,
              'type' : 'fromurl',
            },
            'onRequest' : function() {
              document.getElementById('validation').style.display = "block";
              document.getElementById('validation').innerHTML = checkingUrlMessage;
              document.getElementById('upload-wrapper').style.display = "none";
              document.getElementById('buttons-wrapper').style.display = "none";
            },
            success: function(responseHTML) {
              if( valid ) {
                document.getElementById('upload-wrapper').style.display = "inline-block";
                document.getElementById('buttons-wrapper').style.display = "inline-block";
                document.getElementById('validation').style.display = "none";
                document.getElementById('code').value = url;
              } else {
                document.getElementById('upload-wrapper').style.display = "none";
                document.getElementById('buttons-wrapper').style.display = "none";
                current_code = url;
                document.getElementById('validation').innerHTML = validationErrorMessage;
              }
            }
          }));
        }
			},
			fromEmbedCode:function(url,mUrl){
          (scriptJquery.ajax({
            'format': 'html',
            'url' : validationUrl,
            'data' : {
              'ajax' : true,
              'code' : url,
              'type' : 'embedCode',
            },
            'onRequest' : function() {
              document.getElementById('validation').style.display = "block";
              document.getElementById('validation').innerHTML = checkingUrlMessage;
              document.getElementById('upload-wrapper').style.display = "none";
              document.getElementById('buttons-wrapper').style.display = "none";
            },
            success: function(responseHTML) {
              if( valid ) {
                document.getElementById('upload-wrapper').style.display = "inline-block";
                document.getElementById('buttons-wrapper').style.display = "inline-block";
                document.getElementById('validation').style.display = "none";
                document.getElementById('code').value = dailymotion_code;
              } else {
                document.getElementById('upload-wrapper').style.display = "none";
                document.getElementById('buttons-wrapper').style.display = "none";
                current_code = url;
                document.getElementById('validation').innerHTML = validationErrorMessage;
              }
            }
          }));      
			},
			dailymotion : function(url,mUrl) {
       let code = getVideoId(url);
        let dailymotion_code = code ? code.id : null;


        if( dailymotion_code.length > 0 ) {
          (scriptJquery.ajax({
            'format': 'html',
            'url' : validationUrl,
            'data' : {
              'ajax' : true,
              'code' : dailymotion_code,
              'type' : 'dailymotion',
            },
            'onRequest' : function() {
              document.getElementById('validation').style.display = "block";
              document.getElementById('validation').innerHTML = checkingUrlMessage;
              document.getElementById('upload-wrapper').style.display = "none";
              document.getElementById('buttons-wrapper').style.display = "none";
            },
            success : function(response1) {
              let response = JSON.parse(response1);
              if( response.valid ) {
                document.getElementById('upload-wrapper').style.display = "inline-block";
                document.getElementById('buttons-wrapper').style.display = "inline-block";
                document.getElementById('validation').style.display = "none";
                document.getElementById('code').value = dailymotion_code;
                
                //document.getElementById('code').value = response.iframely.code;
                if(!document.getElementById('title').value)
                  document.getElementById('title').value = response.iframely.title;

                if(!document.getElementById('description').value)
                  document.getElementById('description').value = response.iframely.description;
                if(document.getElementById('description'))
                  scriptJquery('#description').val(response.iframely.description);
               
                  tinymce.get('description').setContent(response.iframely.description);
                document.getElementById('validation').value = "none";
              } else {
                document.getElementById('upload-wrapper').style.display = "none";
                document.getElementById('buttons-wrapper').style.display = "none";
                current_code = dailymotion_code;
                document.getElementById('validation').innerHTML = validationErrorMessage;
              }
            }
          }));
        }
      },
      youtube : function(url) {
        // extract v from url
        var regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        var match = url.match(regExp);
        var youtube_code = "";
        if (match && match[2].length == 11) {
          youtube_code =   match[2];
        } else {
          return;
        }
        if (youtube_code){
          (scriptJquery.ajax({
            'format': 'html',
            'method' : "POST",
            'url' : validationUrl,
            'data' : {
              'ajax' : true,
              'code' : youtube_code,
              'type' : 'youtube',
              'uri' : url,
            },
            'onRequest' : function(){
              document.getElementById('validation').style.display = "block";
              document.getElementById('validation').innerHTML = checkingUrlMessage;
              document.getElementById('upload-wrapper').style.display = "none";
              document.getElementById('buttons-wrapper').style.display = "none";
            },
            success : function(response1) {
              let response = JSON.parse(response1);
              if( response.valid ) {
                document.getElementById('upload-wrapper').style.display = "inline-block";
                document.getElementById('buttons-wrapper').style.display = "inline-block";
                document.getElementById('orText').style.display = "inline-block";
                document.getElementById('validation').style.display = "none";
                //document.getElementById('code').value = response.iframely.code;
                if(!document.getElementById('title').value)
                  document.getElementById('title').value = response.iframely.title;
                if(document.getElementById('title'))
                  document.getElementById('title').value = response.iframely.title;

                if(!document.getElementById('description').value)
                  document.getElementById('description').value = response.iframely.description;
                if(document.getElementById('description'))
                  scriptJquery('#description').val(response.iframely.description);
               
                  tinymce.get('description').setContent(response.iframely.description);
                document.getElementById('validation').value = "none";
              } else {
                document.getElementById('upload-wrapper').style.display = "none";
                document.getElementById('buttons-wrapper').style.display = "none";
                document.getElementById('validation').innerHTML = validationErrorMessage;
                document.getElementById('code').value = '';
              }
              
//               if( valid ) {
//                 document.getElementById('upload-wrapper').style.display = "inline-block";
//                 document.getElementById('buttons-wrapper').style.display = "inline-block";
//                 document.getElementById('validation').style.display = "none";
//                 document.getElementById('code').value = youtube_code;
//               } else {
//                 document.getElementById('upload-wrapper').style.display = "none";
//                 document.getElementById('buttons-wrapper').style.display = "none";
//                 current_code = youtube_code;
//                 document.getElementById('validation').innerHTML = validationErrorMessage;
//               }
            }
          }));
        }
      },
      vimeo : function(url) {
        var vimeo_code = /(vimeo(pro)?\.com)\/(?:[^\d]+)?(\d+)\??(.*)?$/.exec(url)[3];

        if( vimeo_code.length > 0 ) {
          (scriptJquery.ajax({
            'format': 'html',
            'url' : validationUrl,
            'data' : {
              'ajax' : true,
              'code' : vimeo_code,
              'type' : 'vimeo'
            },
            'onRequest' : function() {
              document.getElementById('validation').style.display = "block";
              document.getElementById('validation').innerHTML = checkingUrlMessage;
              document.getElementById('upload-wrapper').style.display = "none";
              document.getElementById('buttons-wrapper').style.display = "none";
            },
            success : function(response1) {
              let response = JSON.parse(response1);
              if( response.valid ) {
                document.getElementById('upload-wrapper').style.display = "inline-block";
                document.getElementById('buttons-wrapper').style.display = "inline-block";
                document.getElementById('validation').style.display = "none";
                document.getElementById('code').value = vimeo_code;
                //document.getElementById('code').value = response.iframely.code;
                if(!document.getElementById('title').value)
                  document.getElementById('title').value = response.iframely.title;

                if(!document.getElementById('description').value)
                  document.getElementById('description').value = response.iframely.description;
                if(document.getElementById('description'))
                  scriptJquery('#description').val(response.iframely.description);
               
                  tinymce.get('description').setContent(response.iframely.description);
                document.getElementById('validation').value = "none";
              } else {
                document.getElementById('upload-wrapper').style.display = "none";
                document.getElementById('buttons-wrapper').style.display = "none";
                current_code = vimeo_code;
                document.getElementById('validation').innerHTML = validationErrorMessage;
              }
            }
          }));
        }
      },
			youtubePlaylist : function(url) {
        var youtubePlaylist_code = "";
        var reg = new RegExp("[&?]list=([a-z0-9_]+)","i");
        var match = reg.exec(url);

        if (match&&match[1].length>0){
            youtubePlaylist_code =  match[1];
        }else{
            return;
        }
        if( youtubePlaylist_code.length > 0 ) {
          (scriptJquery.ajax({
            'format': 'html',
            'url' : validationUrl,
            'data' : {
              'ajax' : true,
              'code' : youtubePlaylist_code,
              'type' : 'youtubePlaylist'
            },
            'onRequest' : function() {
              document.getElementById('validation').style.display = "block";
              document.getElementById('validation').innerHTML = checkingUrlMessage;
              document.getElementById('upload-wrapper').style.display = "none";
              document.getElementById('buttons-wrapper').style.display = "none";
            },
            success : function(response1) {
              let response = JSON.parse(response1);
              if( response.valid ) {
                document.getElementById('upload-wrapper').style.display = "inline-block";
                document.getElementById('buttons-wrapper').style.display = "inline-block";
                document.getElementById('validation').style.display = "none";
                document.getElementById('code').value = youtubePlaylist_code;
              } else {
                document.getElementById('upload-wrapper').style.display = "none";
                document.getElementById('buttons-wrapper').style.display = "none";
                current_code = youtubePlaylist_code;
                document.getElementById('validation').innerHTML = validationErrorMessage;
              }
            }
          }));
        }
      }
    }
    // Run stuff
    <?php if(!$this->youtubeVID) { ?>
      updateTextFields();
    <?php } ?>
    video.attach();
    
    scriptJquery('#tags').selectize({
      maxItems: 10,
      valueField: 'label',
      labelField: 'label',
      searchField: 'label',
      create: true,
      load: function(query, callback) {
        if (!query.length) return callback();
        scriptJquery.ajax({
          url: tagsUrl,
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

		var oldValEmbed = "";
var newEmbedRequest;
scriptJquery("#embedUrl").on("change keyup paste", function() {
	 	var currentVal = scriptJquery(this).val();
		if(scriptJquery('#type') != '17' && currentVal != ''){
				document.getElementById('upload-wrapper').style.display = "inline-block";
				document.getElementById('buttons-wrapper').style.display = "inline-block";
				document.getElementById('validation').style.display = "none";
				document.getElementById('code').value = currentVal;
				return;
		}
    if(currentVal == oldValEmbed || !currentVal) {
        return; //check to prevent multiple simultaneous triggers
    }

		newEmbedRequest= (scriptJquery.ajax({
      dataType: 'html',
			'format': 'html',
			'url' : validationUrl,
			'data' : {
				'ajax' : true,
				'code' : currentVal,
				'type' : 'embedCode'
			},
			'onRequest' : function() {
				document.getElementById('validation').style.display = "block";
				document.getElementById('validation').innerHTML = checkingUrlMessage;
				document.getElementById('upload-wrapper').style.display = "none";
				document.getElementById('buttons-wrapper').style.display = "none";
			},
			success : function(responseHTML) {
				if( valid ) {
					document.getElementById('upload-wrapper').style.display = "inline-block";
					document.getElementById('buttons-wrapper').style.display = "inline-block";
					document.getElementById('validation').style.display = "none";
					document.getElementById('code').value = currentVal;
				} else {
					document.getElementById('upload-wrapper').style.display = "none";
					document.getElementById('buttons-wrapper').style.display = "none";
					current_code = currentVal;
					document.getElementById('validation').innerHTML = validationErrorMessage;
				}
			}
		}));
    oldValEmbed = currentVal;
    //action to be performed on textarea changed
    
});

  });
  
<?php 
$optionsenableglotion = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('optionsenableglotion','a:6:{i:0;s:7:"country";i:1;s:5:"state";i:2;s:4:"city";i:3;s:3:"zip";i:4;s:3:"lat";i:5;s:3:"lng";}'));
if(!empty($optionsenableglotion) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo_enable_location', 1)){ ?>
  en4.core.runonce.add(function() {
    <?php if(!empty($optionsenableglotion) && !engine_in_array('lat', $optionsenableglotion)) { ?>
      scriptJquery('#lat-wrapper').css('display' , 'none');
    <?php } ?>
    <?php if(!empty($optionsenableglotion) && !engine_in_array('lng', $optionsenableglotion)) { ?>
      scriptJquery('#lng-wrapper').css('display' , 'none');
    <?php } ?>
    <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
      scriptJquery('#lat-wrapper').css('display' , 'none');
      scriptJquery('#lng-wrapper').css('display' , 'none');
      scriptJquery('#mapcanvas-element').attr('id','map-canvas');
      scriptJquery('#map-canvas').css('height','200px');
      scriptJquery('#map-canvas').css('width','500px');
      scriptJquery('#mapcanvas-wrapper').hide();
      scriptJquery('#ses_location-label').attr('id','ses_location_data_list');
      scriptJquery('#ses_location_data_list').html("<?php echo isset($_POST['location']) ? $_POST['location'] : '' ; ?>");
      scriptJquery('#ses_location-wrapper').css('display','none');
      initializeSesVideoMap();
    <?php } else { ?>
      scriptJquery('#mapcanvas-wrapper').hide();
      scriptJquery('#ses_location-wrapper').css('display','none');
    <?php } ?>
  });
<?php } ?>
</script>
<?php if (($this->current_count >= $this->quota) && !empty($this->quota)):?>
<div class="tip"> <span> <?php echo $this->translate('You have already uploaded the maximum number of videos allowed.');?> <?php echo $this->translate('If you would like to upload a new video, please <a href="%1$s">delete</a> an old one first.', $this->url(array('action' => 'manage'), 'sesvideo_general'));?> </span> </div>
<br/>
<?php else:?>
	<div class="sesvideo_video_form sesbasic_bxs">
		<?php echo $this->form->render($this);?>
  </div>
<?php endif; ?>
<?php 
$defaultProfileFieldId = "0_0_$this->defaultProfileId";
$profile_type = 2;
?>
<?php echo $this->partial('_customFields.tpl', 'sesbasic', array()); ?> 
<script type="application/javascript">
scriptJquery('#rotation-wrapper').hide();
scriptJquery('#price-wrapper').hide();
scriptJquery('#embedUrl-wrapper').hide();
function enablePasswordFiled(value){
	if(value == 0){
		scriptJquery('#password-wrapper').hide();
	}else{
		scriptJquery('#password-wrapper').show();		
	}
}
scriptJquery(document).ready(function() {

scriptJquery('#password-wrapper').hide();

});
	
</script>
<script type="text/javascript">
	//if(document.getElementById('type') && document.getElementById('type').value != 3 && document.getElementById('photo_id-wrapper'))
		//document.getElementById('photo_id-wrapper').style.display = 'none';
 var defaultProfileFieldId = '<?php echo $defaultProfileFieldId ?>';
  var profile_type = '<?php echo $profile_type ?>';
  var previous_mapped_level = 0;
  function showFields(cat_value, cat_level,typed,isLoad) {
		var categoryId = getProfileType(document.getElementById('category_id').value);
		var subcatId = getProfileType(document.getElementById('subcat_id').value);
		var subsubcatId = getProfileType(document.getElementById('subsubcat_id').value);
		var type = categoryId+','+subcatId+','+subsubcatId;
    if (cat_level == 1 || (previous_mapped_level >= cat_level && previous_mapped_level != 1) || (profile_type == null || profile_type == '' || profile_type == 0)) {
      profile_type = getProfileType(cat_value);
      if (profile_type == 0) {
        profile_type = '';
      } else {
        previous_mapped_level = cat_level;
      }
      document.getElementById(defaultProfileFieldId).value = profile_type;
      //changeFields(document.getElementById(defaultProfileFieldId),null,isLoad,type);
    }
  }
  var getProfileType = function(category_id) {
    var mapping = <?php echo Zend_Json_Encoder::encode(Engine_Api::_()->getDbTable('categories', 'sesvideo')->getMapping(array('category_id', 'profile_type'))); ?>;
		  for (i = 0; i < mapping.length; i++) {	
      	if (mapping[i].category_id == category_id)
        return mapping[i].profile_type;
    	}
    return 0;
  }
  en4.core.runonce.add(function() {
    var defaultProfileId = '<?php echo '0_0_' . $this->defaultProfileId ?>' + '-wrapper';
     if ($type(document.getElementById(defaultProfileId)) && typeof document.getElementById(defaultProfileId) != 'undefined') {
      scriptJquery('#'+defaultProfileId).css('display', 'none');
    }
  });
  function showSubCategory(cat_id,selectedId) {
		var selected;
		if(selectedId != ''){
			var selected = selectedId;
		}
    var url = en4.core.baseUrl + 'sesvideo/index/subcategory/category_id/' + cat_id;
    scriptJquery.ajax({
    method: 'post',
      dataType: 'html',
      url: url,
      data: {
				'selected':selected
      },
      success: function(responseHTML) {
        if (document.getElementById('subcat_id') && responseHTML) {
          if (document.getElementById('subcat_id-wrapper')) {
            document.getElementById('subcat_id-wrapper').style.display = "block";
          }
          document.getElementById('subcat_id').innerHTML = responseHTML;
        } else {
          if (document.getElementById('subcat_id-wrapper')) {
            document.getElementById('subcat_id-wrapper').style.display = "none";
            document.getElementById('subcat_id').innerHTML = '';
          }
					 if (document.getElementById('subsubcat_id-wrapper')) {
            document.getElementById('subsubcat_id-wrapper').style.display = "none";
            document.getElementById('subsubcat_id').innerHTML = '';
          }
        }
				showFields(cat_id,1);
      }
    }); 
  }
	function showSubSubCategory(cat_id,selectedId,isLoad) {
		var categoryId = getProfileType(document.getElementById('category_id').value);
		if(cat_id == 0){
			if (document.getElementById('subsubcat_id-wrapper')) {
				document.getElementById('subsubcat_id-wrapper').style.display = "none";
				document.getElementById('subsubcat_id').innerHTML = '';
				document.getElementsByName("0_0_1")[0].value=categoryId;				
      }
			showFields(cat_id,1,categoryId);
			return false;
		}
		showFields(cat_id,1,categoryId);
		var selected;
		if(selectedId != ''){
			var selected = selectedId;
		}
    var url = en4.core.baseUrl + 'sesvideo/index/subsubcategory/subcategory_id/' + cat_id;
    (scriptJquery.ajax({
    method: 'post',
      dataType: 'html',
      url: url,
      data: {
				'selected':selected
      },
      success: function(responseHTML) {
        if (document.getElementById('subsubcat_id') && responseHTML) {
          if (document.getElementById('subsubcat_id-wrapper')) {
            document.getElementById('subsubcat_id-wrapper').style.display = "block";
          }
          document.getElementById('subsubcat_id').innerHTML = responseHTML;
					// get category id value 
				if(isLoad == 'no')
				showFields(cat_id,1,categoryId,isLoad);
        } else {
          if (document.getElementById('subsubcat_id-wrapper')) {
            document.getElementById('subsubcat_id-wrapper').style.display = "none";
            document.getElementById('subsubcat_id').innerHTML = '';
          }
        }
      }
    }));  
  }
	function showCustom(value,isLoad){
		var categoryId = getProfileType(document.getElementById('category_id').value);
		var subcatId = getProfileType(document.getElementById('subcat_id').value);
		var id = categoryId+','+subcatId;
			showFields(value,1,id,isLoad);
		if(value == 0)
			document.getElementsByName("0_0_1")[0].value=subcatId;	
			return false;
	}
	
	
	function showCustomOnLoad(value,isLoad){
	 <?php if(isset($this->category_id) && $this->category_id != 0){ ?>
		var categoryId = getProfileType(<?php echo $this->category_id; ?>)+',';
		<?php if(isset($this->subcat_id) && $this->subcat_id != 0){ ?>
		var subcatId = getProfileType(<?php echo $this->subcat_id; ?>)+',';
		<?php  }else{ ?>
		var subcatId = '';
		<?php } ?>
		<?php if(isset($this->subsubcat_id) && $this->subsubcat_id != 0){ ?>
		var subsubcat_id = getProfileType(<?php echo $this->subsubcat_id; ?>)+',';
		<?php  }else{ ?>
		var subsubcat_id = '';
		<?php } ?>
		var id = (categoryId+subcatId+subsubcat_id).replace(/,+$/g,"");;
			showFields(value,1,id,isLoad);
		if(value == 0)
			document.getElementsByName("0_0_1")[0].value=subcatId;	
			return false;
		<?php } ?>
	}
 scriptJquery(document).ready(function() {
	var sesdevelopment = 1;
	<?php if(isset($this->category_id) && $this->category_id != 0){ ?>
			<?php if(isset($this->subcat_id)){$catId = $this->subcat_id;}else $catId = ''; ?>
      showSubCategory('<?php echo $this->category_id; ?>','<?php echo $catId; ?>','yes');
   <?php  }else{ ?>
    if(document.getElementById('subcat_id-wrapper'))
	  document.getElementById('subcat_id-wrapper').style.display = "none";
	 <?php } ?>
	 <?php if(isset($this->subsubcat_id)){ ?>
    if (<?php echo isset($this->subcat_id) && intval($this->subcat_id)>0 ? $this->subcat_id : 'sesdevelopment' ?> == 0) {
     document.getElementById('subsubcat_id-wrapper').style.display = "none";
    } else {
			<?php if(isset($this->subsubcat_id)){$subsubcat_id = $this->subsubcat_id;}else $subsubcat_id = ''; ?>
      showSubSubCategory('<?php echo $this->subcat_id; ?>','<?php echo $this->subsubcat_id; ?>','yes');
    }
	 <?php }else{ ?>
	 		 document.getElementById('subsubcat_id-wrapper').style.display = "none";
	 <?php } ?>
	 		showCustomOnLoad('','no');
  });
	
	//prevent form submit on enter
	scriptJquery("#form-upload").bind("keypress", function (e) {		
    if (e.keyCode == 13 && scriptJquery('#'+e.target.id).prop('tagName') != 'TEXTAREA') {
			e.preventDefault();
    }else{
			return true;	
		}
});
	//Ajax error show before form submit
var error = false;
var objectError ;
var counter = 0;
function validateForm(){
		var errorPresent = false;
		scriptJquery('#form-upload input, #form-upload select,#form-upload checkbox,#form-upload textarea,#form-upload radio').each(
				function(index){
						var input = scriptJquery(this);
						if(scriptJquery(this).closest('div').parent().css('display') != 'none' && scriptJquery(this).closest('div').parent().find('.form-label').find('label').first().hasClass('required') && scriptJquery(this).prop('type') != 'hidden' && scriptJquery(this).closest('div').parent().attr('class') != 'form-elements'){	
						  if(scriptJquery(this).prop('type') == 'checkbox'){
								value = '';
								if(scriptJquery('input[name="'+scriptJquery(this).attr('name')+'"]:checked').length > 0) { 
										value = 1;
								};
								if(value == '')
									error = true;
								else
									error = false;
							}else if(scriptJquery(this).prop('type') == 'select-multiple'){
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
								if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
									error = true;
								else
									error = false;
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
							}
							if(error)
								errorPresent = true;
							error = false;
						}
				}
			);
				
			return errorPresent ;
}
scriptJquery('#form-upload').submit(function(e){
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
					}else{
						scriptJquery('#upload').attr('disabled',true);
						scriptJquery('#upload').html('<?php echo $this->translate("Submitting Form ...") ; ?>');
						return true;
					}			
	});
 
	function showCustomOnLoad(value,isLoad){
	 <?php if(isset($this->category_id) && $this->category_id != 0){ ?>
		var categoryId = getProfileType(<?php echo $this->category_id; ?>)+',';
		<?php if(isset($this->subcat_id) && $this->subcat_id != 0){ ?>
		var subcatId = getProfileType(<?php echo $this->subcat_id; ?>)+',';
		<?php  }else{ ?>
		var subcatId = '';
		<?php } ?>
		<?php if(isset($this->subsubcat_id) && $this->subsubcat_id != 0){ ?>
		var subsubcat_id = getProfileType(<?php echo $this->subsubcat_id; ?>)+',';
		<?php  }else{ ?>
		var subsubcat_id = '';
		<?php } ?>
		var id = (categoryId+subcatId+subsubcat_id).replace(/,+$/g,"");;
			showFields(value,1,id,isLoad);
		if(value == 0)
			document.getElementsByName("0_0_1")[0].value=subcatId;	
			return false;
		<?php } ?>
	}
 scriptJquery(document).ready(function() {	
	 		showCustomOnLoad('','no');
			<?php if(engine_count($this->data)){ ?>
				scriptJquery('#code').val('<?php echo $this->data["code"]; ?>');
				scriptJquery('#url').val('<?php echo $this->data["url"]; ?>');
			<?php } ?>
  });
  
  function showPaidVideoOptions(value) {
    if(value == 'free') {
      if(document.getElementById('price-wrapper'))
        document.getElementById('price-wrapper').style.display = 'none';
      if(document.getElementById('sample_file-wrapper'))
        document.getElementById('sample_file-wrapper').style.display = 'none';
    } else if(value == 'paid') {
      if(document.getElementById('price-wrapper'))
        document.getElementById('price-wrapper').style.display = 'block';
      if(document.getElementById('sample_file-wrapper'))
        document.getElementById('sample_file-wrapper').style.display = 'block';
    }
  }
</script> 
