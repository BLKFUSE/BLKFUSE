<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: create.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php $this->uploadcheck = Zend_Controller_Front::getInstance()->getRequest()->getParam('upload', null);?>
<?php if($this->resource_type == 'sesblog_blog' && $this->resource_id): ?>
	<?php $blog = Engine_Api::_()->getItem('sesblog_blog', $this->resource_id); ?>
	<div style="margin-bottom:10px;"><a class="buttonlink sesbasic_icon_back" href="<?php echo $blog->getHref(); ?>"><?php echo $this->translate('Back to Blog'); ?></a></div>
<?php elseif($this->resource_type == 'sesevent_event' && $this->resource_id): ?>
	<?php $event = Engine_Api::_()->getItem('sesevent_event', $this->resource_id); ?>
	<div style="margin-bottom:10px;"><a class="buttonlink sesbasic_icon_back" href="<?php echo $event->getHref(); ?>"><?php echo $this->translate('Back to Event'); ?></a></div>
<?php endif; ?>


<script type="text/javascript">

  <?php if($this->uploadCheck == 'song') { ?>

    en4.core.runonce.add(function() {
      if(document.getElementById('title-wrapper'))
        document.getElementById('title-wrapper').style.display = 'none';
      if(document.getElementById('description-wrapper'))
        document.getElementById('description-wrapper').style.display = 'none';
      if(document.getElementById('auth_view-wrapper'))
        document.getElementById('auth_view-wrapper').style.display = 'none';
      if(document.getElementById('auth_comment-wrapper'))
        document.getElementById('auth_comment-wrapper').style.display = 'none';
      if(document.getElementById('search-wrapper'))
        document.getElementById('search-wrapper').style.display = 'none';
    }); 
    var uploadLinkTitle = '<?php echo $this->translate("Add Song");?>';
    var uploadLinkDesc = '<?php echo $this->translate('Click "Add Song" to select one song from your computer. After you have selected the song, they will begin to upload right away. When your upload is finished, click the button below the song list to save them.');?>';
  <?php } else { ?>
    var uploadLinkTitle = '<?php echo $this->translate("Add Music");?>';
    var uploadLinkDesc = '<?php echo $this->translate('Click "Add Music" to select one or more songs from your computer. After you have selected the songs, they will begin to upload right away. When your upload is finished, click the button below the song list to save them to your playlist.');?>';
    <?php } ?>
  
</script>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/styles/styles.css'); ?>

<div class='sesmusic_upload_form'>
  <?php echo $this->form->render($this) ?>
</div>
<?php if($this->uploadCheck == 'song') {
		$defaultProfileFieldId = "0_0_$this->defaultProfileId";
    $profile_type = 'sesmusic_albumsongs';
	?>
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/scripts/uploader.js'); ?>
	<?php echo $this->partial('_customFields.tpl', 'sesbasic', array()); ?>
  <script type="text/javascript">
    en4.core.runonce.add(function () {
      new SESUploader('#upload_file', {
        uploadLinkClass : 'buttonlink sesbasic_icon_add',
        uploadLinkTitle : uploadLinkTitle,
        uploadLinkDesc : uploadLinkDesc,
      });
    });
  </script>
<?php } else { ?>
<?php 
    $defaultProfileFieldId = "0_0_$this->defaultProfileId";
    $profile_type = 'sesmusic_album';
    ?>
	<?php echo $this->partial('_customFields.tpl', 'sesbasic', array()); ?>
  <script type="text/javascript">
    en4.core.runonce.add(function () {
      new Uploader('#upload_file', {
        uploadLinkClass : 'buttonlink sesbasic_icon_add',
        uploadLinkTitle : uploadLinkTitle,
        uploadLinkDesc : uploadLinkDesc,
      });
    });
	
  </script>
<?php } ?>

<script>
var param = '<?php echo $this->uploadcheck; ?>';
if(param != 'song'){ 
	
	 var defaultProfileFieldId = '<?php echo $defaultProfileFieldId ?>';
      var profile_type = '<?php echo $profile_type ?>';
      var previous_mapped_level = 0;
	  function showFields(cat_value, cat_level,typed,isLoad) {
            var categoryId = getProfileType(formObj.find('#category_id-wrapper').find('#category_id-element').find('#category_id').val());
            var subcatId = getProfileType(formObj.find('#subcat_id-wrapper').find('#subcat_id-element').find('#subcat_id').val());
            var subsubcatId = getProfileType(formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').val());
            var type = categoryId+','+subcatId+','+subsubcatId;
        if (cat_level == 1 || (previous_mapped_level >= cat_level && previous_mapped_level != 1) || (profile_type == null || profile_type == '' || profile_type == 0)) {
          profile_type = getProfileType(cat_value);
          if (profile_type == 0) {
            profile_type = '';
          } else {
            previous_mapped_level = cat_level;
          }
          if(document.getElementById(defaultProfileFieldId))
          document.getElementById(defaultProfileFieldId).value = profile_type;
          changeFields(document.getElementById(defaultProfileFieldId),null,isLoad,type);
        }
      }
	  var getProfileType = function(category_id) {
        var mapping = <?php echo Zend_Json_Encoder::encode(Engine_Api::_()->getDbTable('categories', 'sesmusic')->getMapping(array('category_id'=>true, 'profile_type'=>true,'param'=>'album'))); ?>;
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
          if(scriptJquery('#sessmoothbox_main').length){
            scriptJquery('#form-upload-music').find('div').find('div').find('.form-elements').find('.parent_0').closest('.form-wrapper').hide() ;
          }
        }
      });
	  function showSubCategory(cat_id,selectedId) {
            var selected;
            if(selectedId != ''){
                var selected = selectedId;
            }
        var url = en4.core.baseUrl + 'sesmusic/index/subcategory/category_id/' + cat_id+'/param/album';
        scriptJquery.ajax({
					method: 'post',
          url: url,
          data: {
                    'selected':selected
          },
          success: function(responseHTML) {
            if (formObj.find('#subcat_id-wrapper').length && responseHTML) {
              formObj.find('#subcat_id-wrapper').show();
              formObj.find('#subcat_id-wrapper').find('#subcat_id-element').find('#subcat_id').html(responseHTML);
            } else {
              if (formObj.find('#subcat_id-wrapper').length) {
                formObj.find('#subcat_id-wrapper').hide();
                formObj.find('#subcat_id-wrapper').find('#subcat_id-element').find('#subcat_id').html( '<option value="0"></option>');
              }
            }
                  if (formObj.find('#subsubcat_id-wrapper').length) {
                formObj.find('#subsubcat_id-wrapper').hide();
                formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html( '<option value="0"></option>');
              }
                    showFields(cat_id,1);
          }
        }); 
      }
	  function showQuickSubSubCategory(cat_id,object) {
       scriptJquery('#sesmusic_sussubcat_loading').show();
        var url = en4.core.baseUrl + 'sesmusic/index/subsubcategory/subcategory_id/' + cat_id+'/param/album';
        (scriptJquery.ajax({
					method: 'post',
          url: url,
          data: {
            'selected':'',
            'quickMusic':1
          },
          success: function(responseHTML) {
            if(responseHTML != ''){
              scriptJquery('.quick_music_subsubcat').show();
              scriptJquery(object).parent().parent().find('.quick_music_subsubcat').html("<select><option value=''>"+en4.core.language.translate("3rd-level Category")+"</option>"+responseHTML+"></select>");
            }
            else {
              scriptJquery(object).parent().parent().find('.quick_music_subsubcat').html("");
              scriptJquery('.quick_music_subsubcat').hide();
            }
            scriptJquery('#sesmusic_sussubcat_loading').hide();
          }
        }));  
      }
	  function showSubSubCategory(cat_id,selectedId,isLoad) {
            var categoryId = getProfileType(document.getElementById('category_id').value);
            if(cat_id == 0){
                if (formObj.find('#subsubcat_id-wrapper').length) {
                formObj.find('#subsubcat_id-wrapper').hide();
                formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html( '<option value="0"></option>');
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
          var url = en4.core.baseUrl + 'sesmusic/index/subsubcategory/subcategory_id/' + cat_id+'/param/album';
		  
        (scriptJquery.ajax({
					method:'post',
          url: url,
          data: {
                    'selected':selected
          },
          success: function(responseHTML) {
            if (formObj.find('#subsubcat_id-wrapper').length && responseHTML) {
              formObj.find('#subsubcat_id-wrapper').show();
              formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html(responseHTML);
            } else {
              if (formObj.find('#subsubcat_id-wrapper').length) {
                formObj.find('#subsubcat_id-wrapper').hide();
                formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html( '<option value="0"></option>');
              }
            }				
                }
        }));  
      }
	  function showCustom(value,isLoad){
            var categoryId = getProfileType(formObj.find('#category_id-wrapper').find('#category_id-element').find('#category_id').val());
            var subcatId = getProfileType(formObj.find('#subcat_id-wrapper').find('#subcat_id-element').find('#subcat_id').val());
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
            <?php }else{ ?>
                showFields(value,1,'',isLoad);
            <?php } ?>
        }
		en4.core.runonce.add(function(){
                formObj = scriptJquery('#form-upload-music').find('div').find('div').find('div');
                var sesdevelopment = 1;
                <?php if((isset($this->category_id) && $this->category_id != 0) || (isset($_GET['category_id']) && $_GET['category_id'] != 0)){ ?>
                        <?php if(isset($this->subcat_id)){$catId = $this->subcat_id;}else $catId = ''; ?>
                        showSubCategory('<?php echo isset($_GET['category_id'])? $_GET['category_id']:$this->category_id; ?>','<?php echo $catId; ?>','yes');
                 <?php  }else{ ?>
                    formObj.find('#subcat_id-wrapper').hide();
                 <?php } ?>
                 <?php if(isset($this->subsubcat_id) && $this->subsubcat_id != 0){ ?>
                    if (<?php echo isset($this->subcat_id) && intval($this->subcat_id) > 0 ? $this->subcat_id : 'sesdevelopment' ?> == 0) {
                     formObj.find('#subsubcat_id-wrapper').hide();
                    } else {
                        <?php if(isset($this->subsubcat_id)){$subsubcat_id = $this->subsubcat_id;}else $subsubcat_id = ''; ?>
                        showSubSubCategory('<?php echo $this->subcat_id; ?>','<?php echo $this->subsubcat_id; ?>','yes');
                    }
                 <?php }else{ ?>
                         formObj.find('#subsubcat_id-wrapper').hide();
                 <?php } ?>
                showCustomOnLoad('','no');
      });
 
}else{
	function checklink(videoUrl){
		$('checking-element').getChildren('.description').set('html', 'Checking Url....');
			$('checking-element').getChildren('.description').show();
			var url = en4.core.baseUrl+'sesmusic/index/get-iframely-information';
			scriptJquery.ajax({
				url: url,
				data: {
						uri:videoUrl			
				},
				success: function(responseHTML) {
					if(responseHTML == 1){
						$('checking-element').getChildren('.description').hide();
					}else{
						$('is_video_found').set("value", 0);
						$('checking-element').getChildren('.description').set('html', 'Url not Found');
						
					}
				}
		}); 
	}		
	var defaultProfileFieldId = '<?php echo $defaultProfileFieldId ?>';
      var profile_type = '<?php echo $profile_type ?>';
      var previous_mapped_level = 0;
	  function showFields(cat_value, cat_level,typed,isLoad) {
            var categoryId = getProfileType(formObj.find('#category_id-wrapper').find('#category_id-element').find('#category_id').val());
            var subcatId = getProfileType(formObj.find('#subcat_id-wrapper').find('#subcat_id-element').find('#subcat_id').val());
            var subsubcatId = getProfileType(formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').val());
            var type = categoryId+','+subcatId+','+subsubcatId;
        if (cat_level == 1 || (previous_mapped_level >= cat_level && previous_mapped_level != 1) || (profile_type == null || profile_type == '' || profile_type == 0)) {
          profile_type = getProfileType(cat_value);
          if (profile_type == 0) {
            profile_type = '';
          } else {
            previous_mapped_level = cat_level;
          }
          if(document.getElementById(defaultProfileFieldId))
          document.getElementById(defaultProfileFieldId).value = profile_type;
          changeFields(document.getElementById(defaultProfileFieldId),null,isLoad,type);
        }
      }
	  var getProfileType = function(category_id) {
        var mapping = <?php echo Zend_Json_Encoder::encode(Engine_Api::_()->getDbTable('categories', 'sesmusic')->getMapping(array('category_id'=>true, 'profile_type'=>true,'param'=>'song'))); ?>;
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
          if(scriptJquery('#sessmoothbox_main').length){
            scriptJquery('#form-upload-music').find('div').find('div').find('.form-elements').find('.parent_0').closest('.form-wrapper').hide() ;
          }
        }
      });
	  function showSubCategory(cat_id,selectedId) {
			
            var selected;
            if(selectedId != ''){
                var selected = selectedId;
            }
        var url = en4.core.baseUrl + 'sesmusic/index/subcategory/category_id/' + cat_id+'/param/song';
        scriptJquery.ajax({
					method: 'post',
          url: url,
          data: {
                    'selected':selected
          },
          success: function(responseHTML) {
            if (formObj.find('#subcat_id-wrapper').length && responseHTML) {
              formObj.find('#subcat_id-wrapper').show();
              formObj.find('#subcat_id-wrapper').find('#subcat_id-element').find('#subcat_id').html(responseHTML);
            } else {
              if (formObj.find('#subcat_id-wrapper').length) {
                formObj.find('#subcat_id-wrapper').hide();
                formObj.find('#subcat_id-wrapper').find('#subcat_id-element').find('#subcat_id').html( '<option value="0"></option>');
              }
            }
                  if (formObj.find('#subsubcat_id-wrapper').length) {
                formObj.find('#subsubcat_id-wrapper').hide();
                formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html( '<option value="0"></option>');
              }
                    showFields(cat_id,1);
          }
        }); 
      }
	  function showQuickSubSubCategory(cat_id,object) {
       scriptJquery('#sesmusic_sussubcat_loading').show();
        var url = en4.core.baseUrl + 'sesmusic/index/subsubcategory/subcategory_id/' + cat_id+'/param/song';
        (scriptJquery.ajax({
					method: 'post',
          url: url,
          data: {
            'selected':'',
            'quickMusic':1
          },
          success: function(responseHTML) {
            if(responseHTML != ''){
              scriptJquery('.quick_music_subsubcat').show();
              scriptJquery(object).parent().parent().find('.quick_music_subsubcat').html("<select><option value=''>"+en4.core.language.translate("3rd-level Category")+"</option>"+responseHTML+"></select>");
            }
            else {
              scriptJquery(object).parent().parent().find('.quick_music_subsubcat').html("");
              scriptJquery('.quick_music_subsubcat').hide();
            }
            scriptJquery('#sesmusic_sussubcat_loading').hide();
          }
        }));  
      }
	  function showSubSubCategory(cat_id,selectedId,isLoad) {
            var categoryId = getProfileType(document.getElementById('category_id').value);
            if(cat_id == 0){
                if (formObj.find('#subsubcat_id-wrapper').length) {
                formObj.find('#subsubcat_id-wrapper').hide();
                formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html( '<option value="0"></option>');
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
          var url = en4.core.baseUrl + 'sesmusic/index/subsubcategory/subcategory_id/' + cat_id+'/param/song';
        (scriptJquery.ajax({
					method:'post',
          url: url,
          data: {
                    'selected':selected
          },
          success: function(responseHTML) {
            if (formObj.find('#subsubcat_id-wrapper').length && responseHTML) {
              formObj.find('#subsubcat_id-wrapper').show();
              formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html(responseHTML);
            } else {
              if (formObj.find('#subsubcat_id-wrapper').length) {
                formObj.find('#subsubcat_id-wrapper').hide();
                formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html( '<option value="0"></option>');
              }
            }				
                }
        }));  
      }
	  function showCustom(value,isLoad){
            var categoryId = getProfileType(formObj.find('#category_id-wrapper').find('#category_id-element').find('#category_id').val());
            var subcatId = getProfileType(formObj.find('#subcat_id-wrapper').find('#subcat_id-element').find('#subcat_id').val());
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
            <?php }else{ ?>
                showFields(value,1,'',isLoad);
            <?php } ?>
        }
		en4.core.runonce.add(function(){
				formObj = scriptJquery('#form-upload-music').find('div').find('div').find('div');
				var sesdevelopment = 1;
				<?php if((isset($this->category_id) && $this->category_id != 0) || (isset($_GET['category_id']) && $_GET['category_id'] != 0)){ ?>
								<?php if(isset($this->subcat_id)){$catId = $this->subcat_id;}else $catId = ''; ?>
								showSubCategory('<?php echo isset($_GET['category_id'])? $_GET['category_id']:$this->category_id; ?>','<?php echo $catId; ?>','yes');
				 <?php  }else{ ?>
						formObj.find('#subcat_id-wrapper').hide();
				 <?php } ?>
				 <?php if(isset($this->subsubcat_id) && $this->subsubcat_id != 0){ ?>
						if (<?php echo isset($this->subcat_id) && intval($this->subcat_id) > 0 ? $this->subcat_id : 'sesdevelopment' ?> == 0) {
						 formObj.find('#subsubcat_id-wrapper').hide();
						} else {
								<?php if(isset($this->subsubcat_id)){$subsubcat_id = $this->subsubcat_id;}else $subsubcat_id = ''; ?>
								showSubSubCategory('<?php echo $this->subcat_id; ?>','<?php echo $this->subsubcat_id; ?>','yes');
						}
				 <?php }else{ ?>
								 formObj.find('#subsubcat_id-wrapper').hide();
				 <?php } ?>
				showCustomOnLoad('','no');
      });
}
</script>
<?php $uploadoption = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.uploadoption', 'myComputer');
if (($uploadoption == 'both' || $uploadoption == 'soundCloud')): ?>
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
    }).inject($('options-element'), 'bottom');;

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
      return;
    
    if(!song_url)
      return;
     var idsongURL = id.split('songurl_'); 
    document.getElementById('loading_image_'+idsongURL[1]).style.display ='';
    requestsend = (scriptJquery.ajax({
      url: en4.core.baseUrl + 'sesmusic/index/soundcloudint',
      data: {
        format: 'json',
        'song_url': song_url,
      },
      success: function(responseJSON) {
                 
         document.getElementById('loading_image_'+idsongURL[1]).style.display = 'none';
         
         if(responseJSON.file_id) {
           document.getElementById('soundStatus_' + idsongURL[1]).innerHTML = '<i class="fa fa-check" title="This url is valid"></i>';
					if(!document.getElementById('remove_'+idsongURL[1])){
            var destroyer = new Element('a', {
              'id' : 'remove_' + idsongURL[1],
              'class': 'removesong',
              'href' : 'javascript:void(0);',
              'html' : en4.core.language.translate('<i class="fa fa-trash" title="Remove this song"></i>'),
              'events' : {
                'click' : function() {
                  soundDelete(responseJSON.file_id, idsongURL[1]);
                }
              }
            }).inject($('soundStatus_' + idsongURL[1]), 'after');
					}
           document.getElementById('soundcloudIds').value = document.getElementById('soundcloudIds').value + responseJSON.file_id + ' ';
           if(document.getElementById('submit-wrapper').style.display == 'none') {
            document.getElementById('submit-wrapper').style.display = 'block';
           }
					var checkOption = checkAddOptions();
					if(checkOption){
					 document.getElementById('addOptionLink').style.display = 'block';
					}
         } else {
           document.getElementById('soundStatus_' + idsongURL[1]).innerHTML = '<i class="fa fa-times" title="This url is invalid"></i>';
         }
      }
    }));
  }

	function checkAddOptions() {
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
  
  document.getElementById('addOptionLink').style.display = 'none';
  function soundDelete(file_id, id) {
  
    if(!file_id)
      return;
    
    if(!id)
      return;
        
    soundcloudIds = document.getElementById('soundcloudIds').value;
    document.getElementById('soundcloudIds').value = soundcloudIds.replace(file_id, "");
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
          document.getElementById('addOptionLink').style.display = 'block';
        }
    }));
  }
</script>
<?php endif; ?>
<script type="text/javascript">
var playlist_id = <?php echo $this->album_id ?>;
function updateTextFields() {
  if ($('playlist_id').selectedIndex > 0) {
    scriptJquery('#title-wrapper').hide();
    scriptJquery('#description-wrapper').hide();
    scriptJquery('#search-wrapper').hide();
  } else {
    scriptJquery('#title-wrapper').show();
    scriptJquery('#description-wrapper').show();
    scriptJquery('#search-wrapper').show();
  }
}

// populate field if playlist_id is specified
if (playlist_id > 0) {
  $$('#playlist_id option').each(function(el, index) {
    if (el.value == playlist_id)
      $('playlist_id').selectedIndex = index;
  });
  updateTextFields();
}
</script>
<script type="text/javascript">
  scriptJquery(document).ready(function() {
    if(document.getElementById('musicalbum_main_preview-wrapper'))
    document.getElementById('musicalbum_main_preview-wrapper').style.display = 'none';
    if(document.getElementById('musicalbum_cover_preview-wrapper'))
    document.getElementById('musicalbum_cover_preview-wrapper').style.display = 'none';
  });
//Show choose image 
function showReadImage(input,id) {
  var url = input.value; 
  var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
  if (input.files && input.files[0] && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG' || ext == 'webp')){
    var reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById(id+'-wrapper').style.display = 'block';
      scriptJquery('#'+id).attr('src', e.target.result);
    }
    document.getElementById(id+'-wrapper').style.display = 'block';
    reader.readAsDataURL(input.files[0]);
  }
}
scriptJquery('.core_main_sesmusic').parent().addClass('active');
<?php 
 if(isset($this->mid)){?>
 scriptJquery(document).ready(function() {
	 if(document.getElementById('songurl_1')){
			document.getElementById('songurl_1').value = "<?php echo $this->mid; ?>";
			songupload('songurl_1');
	 }
	});
<?php
 }
?>
</script>
<?php if($this->uploadCheck == 'song') { ?>
  <script type="text/javascript">
    scriptJquery('.sesmusic_main_create').parent().removeClass('active');
  </script>
<?php } else {  ?>
  <script type="text/javascript">
    scriptJquery('.sesmusic_main_uploadsong').parent().removeClass('active');
  </script>
<?php } ?>
