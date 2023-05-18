<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>

<div class="<?php echo $this->view_type=='horizontal' ? 'sesbasic_browse_search_horizontal' : 'sesbasic_browse_search_vertical'; ?>">
  <?php echo $this->searchForm->render($this) ?>
</div>
<?php $request = Zend_Controller_Front::getInstance()->getRequest();?>
<?php $controllerName = $request->getControllerName();?>
<?php $actionName = $request->getActionName();?>
<?php if($this->search_for == 'video'){ ?>
<script type="application/javascript">var Searchurl = "<?php echo $this->url(array('module' =>'sesvideo','controller' => 'index', 'action' => 'get-video'),'default',true); ?>";</script>
<?php if($controllerName == 'index' && $actionName != 'locations'){ ?>
<?php if($actionName == 'all-results'): ?>
<?php $identity = Engine_Api::_()->sesvideo()->getIdentityWidget('sesvideo.browse-video','widget','advancedsearch_index_sesvideo_video'); ?>
<?php elseif($actionName == 'browse'): ?>
<?php $identity = Engine_Api::_()->sesvideo()->getIdentityWidget('sesvideo.browse-video','widget','sesvideo_index_browse'); ?>
<?php elseif($actionName == 'browse-videos' && $this->pageName): ?>
<?php $identity = Engine_Api::_()->sesvideo()->getIdentityWidget('sesvideo.browse-video','widget', $this->pageName); ?>
<?php elseif($actionName == 'browse-pinboard'): ?>
<?php $identity = Engine_Api::_()->sesvideo()->getIdentityWidget('sesvideo.browse-video','widget','sesvideo_index_browse-pinboard'); ?>
<?php endif; ?>

<?php if(@$identity){ ?>
<script type="application/javascript">
    en4.core.runonce.add(function() {
		scriptJquery('#filter_form').submit(function(e){
			e.preventDefault();
			if(scriptJquery('.sesvideo_video_listing').length > 0){
				scriptJquery('#tabbed-widget_<?php echo $identity; ?>').html('');
				scriptJquery('#loading_image_<?php echo $identity; ?>').show();
				scriptJquery('#submit').html(scriptJquery('#loadingimgsesvideo-wrapper').html());
				if(typeof paggingNumber<?php echo $identity; ?> == 'function'){
					searchFlag = 1;
					e.preventDefault();
					searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
				  paggingNumber<?php echo $identity; ?>(1);
				}else if(typeof viewMore_<?php echo $identity; ?> == 'function'){
					e.preventDefault();
					searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
					page<?php echo $identity; ?> = 1;
					searchFlag = 1;
				  viewMore_<?php echo $identity; ?>();
				}
			}
		return true;
		});	
});
</script>
<?php } ?>
<?php }else if($controllerName == 'index' && $actionName == 'locations'){?>
	<script type="application/javascript">var Searchurl = "<?php echo $this->url(array('module' =>'sesvideo','controller' => 'index', 'action' => 'get-video'),'default',true); ?>";</script>
  <script type="application/javascript">
en4.core.runonce.add(function() {
		scriptJquery('#filter_form').submit(function(e){
			e.preventDefault();
			var error = false;
			if(scriptJquery('#locationSesList').val() == ''){
				scriptJquery('#locationSesList').css('border-color','red');
				error = true;
			}else{
				scriptJquery('#locationSesList').css('border-color','');
			}
			if(scriptJquery('#miles').val() == 0){
				error = true;
				scriptJquery('#miles').css('border-color','red');
			}else{
				scriptJquery('#miles').css('border-color','');
			}
			if(map && !error){
				scriptJquery('#submit').html(scriptJquery('#loadingimgsesvideo-wrapper').html());
					e.preventDefault();
					searchParams = scriptJquery(this).serialize();
				  callNewMarkersAjax();
			}
		return true;
		});	
});
</script>
<?php } ?>
<?php }else if($this->search_for == 'chanel'){ ?>
<script type="application/javascript">var Searchurl = "<?php echo $this->url(array('module' =>'sesvideo','controller' => 'chanel', 'action' => 'get-chanel'),'default',true); ?>";</script>

<?php $identity = 0;
    if($actionName == 'all-results'){ ?>
<?php $identity = Engine_Api::_()->sesvideo()->getIdentityWidget('sesvideo.browse-chanel','widget','advancedsearch_index_sesvideo_chanel'); ?>
<?php }elseif($controllerName == 'chanel' && $actionName == 'browse'){ ?>
<?php $identity = Engine_Api::_()->sesvideo()->getIdentityWidget('sesvideo.browse-chanel','widget','sesvideo_chanel_browse'); ?>
<?php } ?>
<?php if($identity){ ?>
<script type="application/javascript">
    en4.core.runonce.add(function() {
		scriptJquery('#filter_form').submit(function(e){
			e.preventDefault();
			if(scriptJquery('.layout_sesvideo_browse_chanel').length > 0){
				scriptJquery('#scrollHeightDivSes_<?php echo $identity; ?>').html('');
				scriptJquery('#loading_image_<?php echo $identity; ?>').show();
				scriptJquery('#submit').html(scriptJquery('#loadingimgsesvideo-wrapper').html());
				if(typeof paggingNumber<?php echo $identity; ?> == 'function'){
					searchFlag = 1;
					e.preventDefault();
					searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
				  paggingNumber<?php echo $identity; ?>(1);
				}else if(typeof viewMore_<?php echo $identity; ?> == 'function'){
					e.preventDefault();
					searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
					page<?php echo $identity; ?> = 1;
					searchFlag = 1;
				  viewMore_<?php echo $identity; ?>();
				}
			}
		return true;
		});	
});

</script>
<?php } ?>
<?php } ?>
<script type="text/javascript">

  en4.core.runonce.add(function() {
    AutocompleterRequestJSON('search', Searchurl, function(selecteditem) {
    })
  });

	function showSubCategory(cat_id,selected) {
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
						document.getElementById('subcat_id-wrapper').style.display = "inline-block";
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
	function showSubSubCategory(cat_id,selected) {
		if(cat_id == 0){
			if (document.getElementById('subsubcat_id-wrapper')) {
				document.getElementById('subsubcat_id-wrapper').style.display = "none";
				document.getElementById('subsubcat_id').innerHTML = '';
      }	
			return false;
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
            document.getElementById('subsubcat_id-wrapper').style.display = "inline-block";
          }
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
en4.core.runonce.add(function() {
	if(document.getElementById('category_id')){
	 var catAssign = 1;
	<?php if(isset($_GET['category_id']) && $_GET['category_id'] != 0){ ?>
			<?php if(isset($_GET['subcat_id'])){$catId = $_GET['subcat_id'];}else $catId = ''; ?>
      showSubCategory('<?php echo $_GET['category_id']; ?>','<?php echo $catId; ?>');
	 <?php if(isset($_GET['subsubcat_id'])){ ?>
			<?php if(isset($_GET['subsubcat_id'])){$subsubcat_id = $_GET['subsubcat_id'];}else $subsubcat_id = ''; ?>
      showSubSubCategory("<?php echo $_GET['subcat_id']; ?>","<?php echo $_GET['subsubcat_id']; ?>");
	 <?php }else{?>
	 		 document.getElementById('subsubcat_id-wrapper').style.display = "none";
	 <?php } ?>
	 <?php  }else{?>
	  document.getElementById('subcat_id-wrapper').style.display = "none";
		document.getElementById('subsubcat_id-wrapper').style.display = "none";
	 <?php } ?>
	}
  });
en4.core.runonce.add(function() {
mapLoad = false;
if(scriptJquery('#lat-wrapper').length > 0){
	scriptJquery('#lat-wrapper').css('display' , 'none');
	scriptJquery('#lng-wrapper').css('display' , 'none');
	initializeSesVideoMapList();
}
});
scriptJquery( window ).load(function() {
	if(scriptJquery('#lat-wrapper').length > 0){
		//initializeSesVideoMapList();
	}
});
en4.core.runonce.add(function() {
scriptJquery('#loadingimgsesvideo-wrapper').hide();
    });
</script>
