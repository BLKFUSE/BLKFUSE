<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: edit.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>
<?php 
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . "externals/selectize/css/normalize.css");
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/selectize/js/selectize.js'); 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>

<script type="text/javascript">

  en4.core.runonce.add(function() {

    var tagsUrl = '<?php echo $this->url(array('controller' => 'tag', 'action' => 'suggest'), 'default', true) ?>';

    var validationUrl = '<?php echo $this->url(array('module' => 'sesvideo', 'controller' => 'index', 'action' => 'validation'), 'default', true) ?>';

    var validationErrorMessage = "<?php echo $this->translate("We could not find a video there - please check the URL and try again. If you are sure that the URL is valid, please click %s to continue.", "<a href='javascript:void(0);' onclick='javascript:ignoreValidation();'>".$this->translate("here")."</a>"); ?>";

    var checkingUrlMessage = '<?php echo $this->string()->escapeJavascript($this->translate('Checking URL...')) ?>';

    var current_code;

    var ignoreValidation = window.ignoreValidation = function() {

      document.getElementById('upload-wrapper').style.display = "block";

      document.getElementById('validation').style.display = "none";

      document.getElementById('code').value = current_code;

      document.getElementById('ignore').value = true;

    }

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
  });

</script>
<div class="layout_middle">
  <div class="generic_layout_container sesvideo_channel_form sesvideo_channel_edit_form">
    <?php echo $this->form->render($this);?>
  </div>
</div>

<script type="application/javascript">

 scriptJquery('#chanel_create_form_tabs li a').click(function(e){
	 if(scriptJquery(this).parent().hasClass('sesvideo_create_channel_tabs_btns'))
	 	return;
	 e.preventDefault();

		var liLength = scriptJquery('#chanel_create_form_tabs li');

// 		for(i=0;i<liLength.length;i++)
// 
// 			liLength[i].removeClass('active');

		if(onLoad == 'loadedElem'){

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

		onLoad = 'loadedElem';

		var className = scriptJquery(this).parent().attr('data-url');

		scriptJquery('#first_step-wrapper').hide();

		scriptJquery('#last_elem-wrapper').hide();

		scriptJquery('#first_second-wrapper').hide();

		scriptJquery('#first_third-wrapper').hide();

		scriptJquery('#'+className+'-wrapper').show();

		scriptJquery(this).parent().addClass('active');

 });

 scriptJquery("#title").keyup(function(){

		var Text = scriptJquery(this).val();

		Text = Text.toLowerCase();

		Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');

		scriptJquery("#custom_url").val(Text);        

		scriptJquery("#channelurl").html(Text);

});

if(scriptJquery('#cover_photo_preview').attr('src')){

 scriptJquery('#cover_photo_preview-wrapper').show();

}else

	scriptJquery('#cover_photo_preview-wrapper').hide();

if(scriptJquery('#thumbnail_photo_preview').attr('src')){

 scriptJquery('#thumbnail_photo_preview-wrapper').show();

}else

	scriptJquery('#thumbnail_photo_preview-wrapper').hide();

 var onLoad = 'firstLoad';

 scriptJquery('#chanel_create_form_tabs').children().eq(0).find('a').click();

 scriptJquery(document).on('click','.next_elm',function(){

		var id = scriptJquery(this).attr('id');

		scriptJquery('#'+id+'-click').trigger('click');

 });





 function readImageUrl(input,id) {

    var url = input.value;

    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();

		if(id == 'cover_photo_preview')

		 var idMsg = 'chanel_cover';

		else

			var idMsg = 'chanel_thumbnail';

    if (input.files && input.files[0] && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'gif')) {

        var reader = new FileReader();

        reader.onload = function (e) {

					 scriptJquery('#'+id+'-wrapper').show();

           scriptJquery(id).attr('src', e.target.result);

        }

				scriptJquery('#'+id+'-wrapper').show();

				scriptJquery('#'+idMsg+'-msg').hide();

        reader.readAsDataURL(input.files[0]);

    }else{

				 scriptJquery('#'+id+'-wrapper').hide();

				 scriptJquery('#'+idMsg+'-msg').show();

				 scriptJquery('#'+idMsg+'-msg').html("<br><?php echo $this->translate('Please select png,jpeg,jpg image only.'); ?>");

         scriptJquery('#'+idMsg).val('');

		}

  }

	scriptJquery('#custom_url').keyup(function(){

		scriptJquery('#channelurl').html(scriptJquery('#custom_url').val());	

	});

	scriptJquery(document).ready(function() {

		scriptJquery('#channelurl').html(scriptJquery('#custom_url').val());

	});

	scriptJquery('<span id="chanel_cover-msg" class="sesvideo_error" style="display:none"></span>').insertAfter('#chanel_cover');

	scriptJquery('<span id="chanel_thumbnail-msg" class="sesvideo_error" style="display:none"></span>').insertAfter('#chanel_thumbnail');

</script>

<script type="text/javascript">

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

			var customUrlCheck = scriptJquery('#custom_url').val();

			if(customUrlCheck != ''){

				var validation = 	sendCheckValidation(e);

				if(validation){

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

						//scriptJquery('#file_multi-wrapper').remove();

						scriptJquery('#upload').attr('disabled',true);

						scriptJquery('#upload').html('<?php echo $this->translate("Submitting Form ..."); ?>');

						return true;

					}

				}

			}else{

				return true;

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

      }

    }); 

  }

	function showSubSubCategory(cat_id,selectedId,isLoad) {

		if(cat_id == 0){

			if (document.getElementById('subsubcat_id-wrapper')) {

				document.getElementById('subsubcat_id-wrapper').style.display = "none";

				document.getElementById('subsubcat_id').innerHTML = '';

      }	

			return false;

		}

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

				

        } else {

          if (document.getElementById('subsubcat_id-wrapper')) {

            document.getElementById('subsubcat_id-wrapper').style.display = "none";

            document.getElementById('subsubcat_id').innerHTML = '';

          }

        }

      }

    }));  

  }

 scriptJquery(document).ready(function(){
			<?php if(isset($_GET['tab']) && $_GET['tab'] == 'add_videos'){ ?>
					scriptJquery('#save_third-click').trigger('click');
			<?php } ?>
			scriptJquery('#remove_chanel_cover-label').hide();

			scriptJquery('#remove_chanel_thumbnail-label').hide();

			scriptJquery('#delete-label').hide();

			scriptJquery('#upload-label').hide();

			scriptJquery('<span id="chanel_cover-msg" class="sesvideo_error" style="display:none"></span>').insertAfter('#chanel_cover');

			scriptJquery('<span id="chanel_thumbnail-msg" class="sesvideo_error" style="display:none"></span>').insertAfter('#chanel_thumbnail');

 })

 var checkURL = true;

	function sendCheckValidation(e){

		var valueField = scriptJquery('#custom_url').val();

		if(!valueField)

			return;

		var url = en4.core.baseUrl + 'sesvideo/chanel/checkurl';

    scriptJquery.ajax({
        dataType: 'html',
				url: url,

				data: {

					'data':valueField,

					'chanel_id':"<?php echo $this->chanel->chanel_id ; ?>",

				},

				success: function(responseHTML) {

					if(responseHTML == 1){

						scriptJquery('#custom_url').css('border','');

						scriptJquery('.msg').css('color','');

						scriptJquery('.msg').html('');

						checkURL = true;

						return true;

					}else{

						scriptJquery('#save-first-click').trigger('click');

						scriptJquery('#custom_url').css('border','1px solid red');

						scriptJquery('.msg').html('<i class="fa fa-times" title="Unavilable"></i>');

						scriptJquery('html, body').animate({

							scrollTop: scriptJquery('#shortURL-wrapper').position().top },

							1000

						 );

						checkURL = false;

						if(typeof e != 'undefined'){

							if(!checkValidation()){

								e.preventDefault();

								return false;	

							}	

						}

					} 

				}

    });	

	}

	scriptJquery('#custom_url').blur(function(){

		sendCheckValidation();

	});

	scriptJquery("#custom_url").keypress(function(){

   checkURL = false;

	})

	function checkValidation(){

		if(!checkURL && document.getElementById('shortURL-wrapper')){

			scriptJquery('#save-first-click').trigger('click');

			scriptJquery('html, body').animate({

				scrollTop: scriptJquery('#shortURL-wrapper').position().top },

				1000

			 );

			 document.getElementById('custom_url').focus();

			return false;

		}else{

		 var ids = '';

			scriptJquery('#added_manage_videos').find('li').each(function(el) {

				ids = ids+(scriptJquery(this).attr('id').match(/\d+/)[0])+',';

			});

			scriptJquery('#video_ids').val(ids);

			return true;

		}

	}	

	if(document.getElementById('shortURL-label'))

		document.getElementById('shortURL-label').innerHTML = "<?php echo $this->translate('Shortcut URL'); ?>";

	//get videos for chanel as per first select option in manage videos.

	getChanelVideos();

	function getChanelVideos(){

		var url = en4.core.baseUrl + 'sesvideo/chanel/manage-videos';

		scriptJquery.ajax({
        dataType: 'html',
				url: url,

				data: {

					'is_chanel':true,

					'chanel_id':"<?php echo $this->chanel->chanel_id ; ?>"

				},

				success: function(responseHTML) {

					scriptJquery('#added_manage_videos').html(responseHTML);

				var ischange = false;

				if(scriptJquery('#first_third-wrapper').css('display') == 'none'){

					scriptJquery('#first_third-wrapper').css('visibility','hidden').css('position','absolute');

					scriptJquery('#first_third-wrapper').css('display','block');

					ischange = true;

				}

					var totalHeight = scriptJquery('#added_manage_videos').height();

					if(totalHeight > 310){

							scriptJquery('.added_manage_videos').css('height','310px');	

						}else{

							scriptJquery('.added_manage_videos').css('height',totalHeight+'px');	

						}

					if(ischange){

							scriptJquery('#first_third-wrapper').css('visibility','').css('position','');

							scriptJquery('#first_third-wrapper').css('display','none');

							ischange = true;

					}

					getVideos(scriptJquery('#manage_videos').val());

				}

    });

	}

	

	function getVideos(valueField){

		var url = en4.core.baseUrl + 'sesvideo/chanel/manage-videos';

		scriptJquery.ajax({
        dataType: 'html',
				url: url,

				data: {

					'data':valueField,

				},

				success: function(responseHTML) {

					scriptJquery('#manage_videos_data').html(responseHTML);

					disableSelectedVideos();
          //sortableVideos();

				}

    });

	}

	function disableSelectedVideos(){

		 var ids = [];

		 scriptJquery('#added_manage_videos > li').each(function(el) {

           var id = el.get('id').match(/\d+/)[0];

						scriptJquery('#manage_videos_data').find('#videoId-'+id).addClass('overlay_video_added');

     });

	}
  var ids = '';
	scriptJquery(document).on('click','.add-video-manage',function(){

			scriptJquery(scriptJquery(this).parent().parent()).clone().appendTo(scriptJquery('#added_manage_videos'));

			scriptJquery(this).parent().parent().addClass('overlay_video_added');

			scriptJquery('#added_manage_videos').find('#videoId-'+scriptJquery(this).attr('data-url')).find('.sesvideo_grid_thumb').find('a').removeClass('add-video-manage');		

			scriptJquery('#added_manage_videos').find('#videoId-'+scriptJquery(this).attr('data-url')).find('.sesvideo_grid_thumb').find('a').addClass('selected-manage-video');

			scriptJquery("<span class='delete_selected_video'><a class='delete-selected' href='javascript:;'><i class='fas fa-times'></i></a></span>").insertAfter(scriptJquery('#added_manage_videos').find('#videoId-'+scriptJquery(this).attr('data-url')).find('.sesvideo_grid_thumb').find('.sesvideo_thumb_nolightbox'));			

			scriptJquery('#delete_video_ids').val(scriptJquery('#delete_video_ids').val().replace(scriptJquery(this).attr('data-url')+' ',''));

			var totalHeight = scriptJquery('#added_manage_videos').height();

			if(totalHeight > 310){

					scriptJquery('.added_manage_videos').css('height','310px');	

			}else{

					scriptJquery('.added_manage_videos').css('height',totalHeight+'px');	

			}
			ids = ids+scriptJquery(this).attr('data-url')+',';
			scriptJquery('#video_ids').val(ids);

	});

	

	scriptJquery(document).on('click','.delete_selected_video',function(){

		var parentElement = scriptJquery(this).parent().parent();

		var dataId = parentElement.attr('id');

		var dataid = dataId.replace('videoId-','');

		scriptJquery('#manage_videos_data').find('#'+dataId).removeClass('overlay_video_added');

		var getDeleteIds = scriptJquery('#delete_video_ids').val();

		scriptJquery('#delete_video_ids').val(scriptJquery('#delete_video_ids').val().replace(dataid+' ','')+dataid+' ');

		parentElement.remove();

		var totalHeight = scriptJquery('#added_manage_videos').height();

		if(totalHeight > 310){

				scriptJquery('.added_manage_videos').css('height','310px');	

		}else{

				scriptJquery('.added_manage_videos').css('height',totalHeight+'px');	

		}

	});

  scriptJquery(document).ready(function() {

	var sesdevelopment = 1;

	<?php if(isset($this->category_id) && $this->category_id != 0){ ?>

			<?php if(isset($this->subcat_id)){$catId = $this->subcat_id;}else $catId = ''; ?>

      showSubCategory(document.getElementById('category_id').value,'<?php echo $catId; ?>','yes');

   <?php  }else{?>

	 if( document.getElementById('subcat_id-wrapper'))

	  document.getElementById('subcat_id-wrapper').style.display = "none";

	 <?php } ?>

	 <?php if(isset($this->subsubcat_id)){ ?>

    if (<?php echo isset($this->subcat_id) && intval($this->subcat_id)>0 ? $this->subcat_id : 'sesdevelopment' ?> == 0) {

		if(document.getElementById('subsubcat_id-wrapper'))

     document.getElementById('subsubcat_id-wrapper').style.display = "none";

    } else {

			changeSes = true;

			<?php if(isset($this->subsubcat_id)){$subsubcat_id = $this->subsubcat_id;}else $subsubcat_id = ''; ?>

      showSubSubCategory('<?php echo $this->subcat_id; ?>','<?php echo $this->subsubcat_id; ?>','yes');

    }

	 <?php }else{?>

	 		if( document.getElementById('subsubcat_id-wrapper'))

	 		 document.getElementById('subsubcat_id-wrapper').style.display = "none";

	 <?php } ?>

  });

</script>

<script type="text/javascript">

//     var SortablesInstance;
// 
//     function  sortableVideos() {
//         SortablesInstance = new Sortables('added_manage_videos', {
//             clone: true,
//             constrain: false,
//             handle: '.sesvideo_channel_create_videoslist',
//             onComplete: function (e) {
//                 reorder(e);
//             }
//         });
// 
//     }
//     var reorder = function(e) {
//         var menuitems = e.parentNode.childNodes;
//         var ordering = {};
//         var i = 1;
//         for (var menuitem in menuitems)
//         {
//             var child_id = menuitems[menuitem].id;
// 
//             if ((child_id != undefined))
//             {
//                 ordering[child_id] = i;
//                 i++;
//             }
//         }
// 
//     }
</script>
