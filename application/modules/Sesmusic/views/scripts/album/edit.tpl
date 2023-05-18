<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: edit.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>


<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/styles/styles.css'); ?>

<?php $songs = $this->album->getSongs(); ?>
<div class='sesmusic_upload_form'>
  <?php echo $this->form->render($this) ?>
</div>
<div style="display:none;">
  <?php if (!empty($songs)): ?>
    <ul id="music_songlist">
      <?php foreach ($songs as $song): ?>
      <li id="song_item_<?php echo $song->albumsong_id ?>" class="file file-success">
        <a href="javascript:void(0)" class="song_action_remove file-remove" data-file_id="<?php echo $song->albumsong_id ?>"><?php echo $this->translate('Remove') ?></a>
        <span class="file-name">
          <?php echo $song->getTitle() ?>
        </span>
        <?php /*(<a href="javascript:void(0)" class="song_action_rename file-rename"><?php //echo $this->translate('rename') ?></a>*/ ?>
      </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
<?php $uploadoption = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.uploadoption', 'myComputer');
if ($uploadoption == 'both' || $uploadoption == 'soundCloud'): ?>
<a href="javascript: void(0);" onclick="return addAnotherOption();" id="addOptionLink" class="addanothersong">
  <i class="fa fa-plus sesbasic_text_light"></i>
  <?php echo $this->translate("Add another option") ?>
</a>
<script type="text/javascript">
 var soundCloudCounter = 1;
 var maxOptions = 100;
  scriptJquery(document).ready(function() {
    var newdiv = new Element('div',{
      'id' : 'soundContanier'
    }).inject(document.getElementById('options-element'), 'bottom');;

    var soundMiddle = new Element('div',{
      'id' : 'soundMiddle'
    }).inject(newdiv);
   
    var options = <?php echo Zend_Json::encode($this->options) ?>;
    var optionParent = $('options').getParent();

    var addAnotherOption = window.addAnotherOption = function (dontFocus, label) {
			var checkOption = checkAddOptions();
			if(!checkOption){
       return alert(new String('<?php echo $this->string()->escapeJavascript($this->translate("A maximum of %s options are permitted.")) ?>').replace(/%s/, maxOptions));
			 return false;
			}
      var soundId = 'songurl_' + soundCloudCounter;
      var optionElement = new Element('input', {
        'type': 'text',
        'name': 'optionsArray[]',
        'class': 'sesmusic_soundcloud',
        'value': label,
        'id': soundId,
        'onblur': 'songupload(this.id)',
        'onfocus': 'songFocusSave(this.id)',
        'events': {
          'keydown': function(event) {
            if (event.key == 'enter') {
              if (this.get('value').trim().length > 0) {
                addAnotherOption();
                return false;
              } else
                return true;
            } else
              return true;
          }
        }
      });

      if( dontFocus ) {
        optionElement.inject(soundMiddle);
      } else {
        optionElement.inject(soundMiddle).focus();
      }

      new Element('div',{
        'id': 'soundStatus_' + soundCloudCounter,
        'class': 'checkurlstatus',
      }).inject(optionElement, 'after');
      
      var loadingimg = new Element('div', {
        'id' : 'loading_image_'+soundCloudCounter,
        'class' : 'sesmusic_upload_loading',
        'styles': {'display': 'none'},
      }).inject(optionElement, 'after');

      new Element('img', {
       'src' : 'application/modules/Core/externals/images/loading.gif',
      }).inject(loadingimg);
     
      $('addOptionLink').inject(newdiv);
			$('addOptionLink').style.display = 'none';
      soundCloudCounter++;
    }

    if( $type(options) == 'array' && options.length > 0 ) {
      options.each(function(label) {
        addAnotherOption(true, label);
      });
      if( options.length == 1 ) {
        addAnotherOption(true);
      }
    } else {
      addAnotherOption(true);
    }
  });
  
  var songDefaultURL;
  function songFocusSave(id) {
    songDefaultURL = $(id).value;
  }
  
  function songupload(soundId) {
		//check for duplicate url
		var totalSongSelected = document.getElementById('soundMiddle').getElementsByTagName('input');
		for(var i = 0; i < totalSongSelected.length ; i++) 
		{
			if(totalSongSelected[i].id != soundId && document.getElementById(soundId).value != ''){
			 if(totalSongSelected[i].value == document.getElementById(soundId).value){
			 		document.getElementById(soundId).value ='';
					alert('This song url already selected.');
					return false;
			 }
			}
		}
    var id = soundId;
    var song_url = $(id).value;
    if(songDefaultURL == song_url && song_url != '')
      return false;
    if(!song_url)
      return false;
    var idsongURL = id.split('songurl_');
   document.getElementById('loading_image_'+idsongURL[1]).style.display ='';	
    (scriptJquery.ajax({
      url: en4.core.baseUrl + 'sesmusic/index/soundcloudint',
      data: {
        format: 'json',
        'song_url': song_url,
      },
      success: function(responseJSON) {
         $('loading_image_'+idsongURL[1]).style.display = 'none';
         
         if(responseJSON.file_id) {
           $('soundStatus_' + idsongURL[1]).innerHTML = '<i class="fa fa-check" title="This url is valid"></i>';
					if(!$('remove_'+idsongURL[1])){
            var destroyer = new Element('a', {
              'id' : 'remove_' + idsongURL[1],
              'class': 'removesong',
              'href' : 'javascript:void(0);',
              'html' : en4.core.language.translate('<i class="fa fa-trash" title="Remove This Song"></i>'),
              'events' : {
                'click' : function() {
                  soundDelete(responseJSON.file_id, idsongURL[1]);
                }
              }
            }).inject($('soundStatus_' + idsongURL[1]), 'after');
					}
           $('soundcloudIds').value = $('soundcloudIds').value + responseJSON.file_id + ' ';

					var checkOption = checkAddOptions();
					if(checkOption){
					 $('addOptionLink').style.display = 'block';
					}
         } else {
           $('soundStatus_' + idsongURL[1]).innerHTML = '<i class="fa fa-times" title="This url is invalid"></i>';
         }
				 return false;
      }
			
    }));
  	return false;
  }

	function checkAddOptions(){
		var totalSongSelected = document.getElementById('soundMiddle').getElementsByTagName('input');
		if(totalSongSelected.length > 0){
			var totalInputFields = totalSongSelected.length;
			if (totalInputFields >= maxOptions) {
					return false;
			}else{
					return true;
			}
			}else
					return true;
	}
  $('addOptionLink').style.display = 'none';
  function soundDelete(file_id, id) {
  
    if(!file_id)
      return;
    
    if(!id)
      return;
        
    soundcloudIds = $('soundcloudIds').value;
    $('soundcloudIds').value = soundcloudIds.replace(file_id, "");
    en4.core.request.send(scriptJquery.ajax({
      url: en4.core.baseUrl + 'sesmusic/index/soundcloud-song-delete',
      data: {
        format: 'json',
        'file_id': file_id,
      },
      success: function(responseJSON) {
        scriptJquery('#songurl_' + id).remove();
        scriptJquery('#soundStatus_' + id).remove();
        scriptJquery('#remove_' + id).remove();
      var checkOption = checkAddOptions();  
			if(checkOption)
				$('addOptionLink').style.display = 'block';
      }
    }));   
  }
</script>
<?php endif; ?>
<script type="text/javascript">  

if(document.getElementById('category_id')) {
scriptJquery(document).ready(function() {

  if (document.getElementById('category_id').value == 0) {
    document.getElementById('subcat_id-wrapper').style.display = "none";
    document.getElementById('subsubcat_id-wrapper').style.display = "none";
  }

  var cat_id = document.getElementById('category_id').value; 
  if (document.getElementById('subcat_id')) {
    var subcat = document.getElementById('subcat_id').value;
  }

  if(subcat == '') {
    document.getElementById('subcat_id-wrapper').style.display = "none";
  }

  if (subcat == 0) {
    document.getElementById('subsubcat_id-wrapper').style.display = "none";
  }

  if (document.getElementById('subsubcat_id')) {
    var subsubcat = document.getElementById('subsubcat_id').value;
  }

  if (document.getElementById('module_type'))
    var module_type = document.getElementById('module_type').value;

  if (cat_id && module_type && !subcat) {
    var temp = window.setInterval(function() {
      ses_subcategory(cat_id, module_type);
      clearInterval(temp);
    }, 2000);
  }
  
  //Check Search Form Only
  var search =  0;
  if(document.getElementById('search_params')) {
    search =  1;
  }

  var e = document.getElementById("subcat_id").length; 
  if (e == 1 && search != 1) {
    document.getElementById('subcat_id-wrapper').style.display = "none";
  }

  var e = document.getElementById("subsubcat_id").length;
  if (e == 1 && search != 1) {
    document.getElementById('subsubcat_id-wrapper').style.display = "none";
  }

});
}

//Function for get sub category
function ses_subcategory(category_id, module) {

  temp = 1;
  if (category_id == 0) {
    if (document.getElementById('subcat_id-wrapper')) {
      document.getElementById('subcat_id-wrapper').style.display = "none";
      document.getElementById('subcat_id').innerHTML = '';
    }

    if (document.getElementById('subsubcat_id-wrapper')) {
      document.getElementById('subsubcat_id-wrapper').style.display = "none";
      document.getElementById('subsubcat_id').innerHTML = '';
    }
    return false;
  }

  var url = en4.core.baseUrl + 'sesmusic/index/subcategory/category_id/' + category_id;

  en4.core.request.send(scriptJquery.ajax({
    url: url,
    data: {
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
    }
  }));
}

  //Function for get sub sub category
  function sessubsubcat_category(category_id, module) {

    if (category_id == 0) {
      if (document.getElementById('subsubcat_id-wrapper')) {
        document.getElementById('subsubcat_id-wrapper').style.display = "none";
        document.getElementById('subsubcat_id').innerHTML = '';
      }
      return false;
    }

    var url = en4.core.baseUrl + 'sesmusic/index/subsubcategory/category_id/' + category_id;

    en4.core.request.send(scriptJquery.ajax({
      url: url,
      data: {
      },
      success: function(responseHTML) {
        if (document.getElementById('subsubcat_id') && responseHTML) {
          if (document.getElementById('subsubcat_id-wrapper'))
            document.getElementById('subsubcat_id-wrapper').style.display = "block";
          document.getElementById('subsubcat_id').innerHTML = responseHTML;
        } else {
          if (document.getElementById('subsubcat_id-wrapper')) {
            document.getElementById('subsubcat_id-wrapper').style.display = "none";
            document.getElementById('subsubcat_id').innerHTML = '';
          }
        }
      }
    }));

  }

  //<![CDATA[
  en4.core.runonce.add(function() {
    new Uploader('#upload_file', {
      uploadLinkClass : 'buttonlink icon_music_new',
      uploadLinkTitle : '<?php echo $this->translate("Add Music");?>',
      uploadLinkDesc : '<?php echo $this->translate("_MUSIC_UPLOAD_DESCRIPTION");?>'
    });
    //document.getElementById('save-wrapper').inject(document.getElementById('art-wrapper'), 'after');
    
    // IMPORT SONGS INTO FORM
    if (scriptJquery('#music_songlist li.file').length) {
      scriptJquery('#music_songlist li.file').appendTo(scriptJquery('#uploaded-file-list'));
      scriptJquery('#uploaded-file-list li span.file-name').css('cursor', 'move');
      scriptJquery('#uploaded-file-list').css('display', 'block');
      scriptJquery('#remove_all_files').css('display', 'inline');
    }
    
    // SORTABLE PLAYLIST
    scriptJquery('#uploaded-file-list').sortable({
        helper: "clone",
        handle : 'span',
        stop: function( event, ui ) {
          scriptJquery.ajax({
            url: '<?php echo $this->url(array('controller'=>'album','action'=>'sort'), 'sesmusic_extended') ?>',
            noCache: true,
            dataType: 'json',
            method : 'post',
            data: {
              'format': 'json',
              'album_id': <?php echo $this->album->album_id ?>,
              'order': scriptJquery('#uploaded-file-list li').map((i,ele)=> scriptJquery(ele).attr("id")).toArray().join()
            }
          });
        }
    });
    
    // RENAME SONG
    scriptJquery('a.song_action_rename').on('click', function(){
      var origTitle = scriptJquery(this).parents('li:first').find('.file-name').text();
          origTitle = origTitle.substring(0, origTitle.length-6);
      var newTitle  = prompt('<?php echo $this->translate('What is the title of this song?') ?>', origTitle);
      var song_id   = song_action_rename(this).parents('li:first').id.split(/_/);
          song_id   = song_id[ song_id.length-1 ];

      if (newTitle && newTitle.length > 0) {
        newTitle = newTitle.substring(0, 60);
        scriptJquery(this).parents('li:first').find('.file-name').text(newTitle);
        scriptJquery.ajax({
          url: '<?php echo $this->url(array('controller'=>'song','action'=>'rename'), 'sesmusic_extended') ?>',
          method:'post',
          dataType: 'json',
          data: {
            format: 'json',
            albumsong_id: song_id,
            album_id: <?php echo $this->album->album_id ?>,
            title: newTitle
          }
        });
      }
      return false;
    });
    // REMOVE/DELETE SONG FROM PLAYLIST
    scriptJquery('a.file-remove').on('click', function() {
      deleteFile(scriptJquery(this));
    });
  });
  
  var deleteFile = function (el) {
    var song_id = el.attr('data-file_id');
    el.parents('li:first').remove();
    scriptJquery.ajax({
      url: '<?php echo $this->url(array('controller'=>'song','action'=>'deletesong'), 'sesmusic_extended') ?>',
      method:'post',
      dataType:'json',
      data: {
        format: 'json',
        albumsong_id: song_id,
        album_id: <?php echo $this->album->album_id ?>
      }
    });
  }


  scriptJquery(document).ready(function() {
    <?php if(empty($this->album->photo_id)): ?>
      if(document.getElementById('musicalbum_main_preview-wrapper'))
      document.getElementById('musicalbum_main_preview-wrapper').style.display = 'none';
    <?php endif; ?>
    <?php if(empty($this->album->album_cover)): ?>
      if(document.getElementById('musicalbum_cover_preview-wrapper'))
      document.getElementById('musicalbum_cover_preview-wrapper').style.display = 'none';
    <?php endif; ?>
  });

  //Show choose image 
  function showReadImage(input,id) {
    var url = input.value; 
    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
    if (input.files && input.files[0] && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG')){
      var reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById(id+'-wrapper').style.display = 'block';
        scriptJquery(id).attr('src', e.target.result);
      }
      document.getElementById(id+'-wrapper').style.display = 'block';
      reader.readAsDataURL(input.files[0]);
    }
  }
  scriptJquery('.core_main_sesmusic').parent().addClass('active');
</script>
