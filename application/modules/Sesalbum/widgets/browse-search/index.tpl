<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
                 .'application/modules/Sesalbum/externals/scripts/core.js'); ?> 
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>
<div class="sesalbum_browse_search sesbasic_bxs <?php echo $this->view_type=='horizontal' ? 'sesalbum_browse_search_horizontal' : ''; ?>">
  <?php echo $this->searchForm->render($this) ?>
</div>
<?php $request = Zend_Controller_Front::getInstance()->getRequest();?>
<?php $controllerName = $request->getControllerName();?>
<?php $actionName = $request->getActionName();?>
<?php if($this->search_for == 'album'){ ?>
<script>var Searchurl = "<?php echo $this->url(array('module' =>'sesalbum','controller' => 'album', 'action' => 'get-album'),'default',true); ?>";</script>
<?php if($controllerName == 'index' && ($actionName == 'browse' || $actionName == 'all-results')){ ?>
<?php if($actionName != 'all-results'){ ?>
<?php $identity = Engine_Api::_()->sesalbum()->getIdentityWidget('sesalbum.browse-albums','widget','sesalbum_index_browse'); ?>
<?php }else{ ?>
<?php $identity = Engine_Api::_()->sesalbum()->getIdentityWidget('sesalbum.browse-albums','widget','advancedsearch_index_sesalbum_album'); ?>

<?php } ?>
<?php if($identity){ ?>
<script type="application/javascript">
    en4.core.runonce.add(function() {
		scriptJquery('#filter_form').submit(function(e){
			if(scriptJquery('.sesalbum_browse_album_listings').length > 0){
				scriptJquery('#submit').html(scriptJquery('#loadingimgsesalbum-wrapper').html());
				if(typeof paggingNumber<?php echo $identity; ?> == 'function'){
					e.preventDefault();
					searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
				  paggingNumber<?php echo $identity; ?>(1);
				}else if(typeof viewMore_<?php echo $identity; ?> == 'function'){
					e.preventDefault();
					searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
					page<?php echo $identity; ?> = 1;
				  viewMore_<?php echo $identity; ?>();
				}
			}
		return true;
		});	
});
</script>
<?php } ?>
<?php } else if($controllerName == 'index' && $actionName == 'browse-albums') { ?>
<?php $identity = Engine_Api::_()->sesalbum()->getIdentityWidget('sesalbum.browse-albums','widget','sesalbum_index_'.$this->page_id); ?>
<?php if($identity){ ?>
<script type="application/javascript">
    en4.core.runonce.add(function() {
		scriptJquery('#filter_form').submit(function(e){
			if(scriptJquery('.sesalbum_browse_album_listings').length > 0){
				scriptJquery('#submit').html(scriptJquery('#loadingimgsesalbum-wrapper').html());
				if(typeof paggingNumber<?php echo $identity; ?> == 'function'){
					e.preventDefault();
					searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
				  paggingNumber<?php echo $identity; ?>(1);
				}else if(typeof viewMore_<?php echo $identity; ?> == 'function'){
					e.preventDefault();
					searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
					page<?php echo $identity; ?> = 1;
				  viewMore_<?php echo $identity; ?>();
				}
			}
		return true;
		});	
});
</script>
<?php } ?>
<?php } } else{ ?>
<script>var Searchurl = "<?php echo $this->url(array('module' =>'sesalbum','controller' => 'photo', 'action' => 'get-photo'),'default',true); ?>";</script>
<?php if($controllerName == 'index' && ($actionName == 'browse-photo' || $actionName == 'all-results')){ ?>
<?php if($actionName == 'browse-photo'){ ?>
<?php $identity = Engine_Api::_()->sesalbum()->getIdentityWidget('sesalbum.tabbed-widget','widget','sesalbum_index_browse-photo'); ?>
<?php }else{ ?>
<?php $identity = Engine_Api::_()->sesalbum()->getIdentityWidget('sesalbum.tabbed-widget','widget','advancedsearch_index_sesalbum_photo'); ?>
<?php } ?>
<?php if($identity){ ?>
<script type="application/javascript">
    en4.core.runonce.add(function() {
		scriptJquery('#filter_form').submit(function(e){
			e.preventDefault();
			if(scriptJquery('.sesalbum_tabbed_listings').length > 0){
				scriptJquery('#submit').html(scriptJquery('#loadingimgsesalbum-wrapper').html());
				if(typeof paggingNumber<?php echo $identity; ?> == 'function'){
					e.preventDefault();
					searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
				  paggingNumber<?php echo $identity; ?>(1);
				}else if(typeof viewMore_<?php echo $identity; ?> == 'function'){
					e.preventDefault();
					searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
					page<?php echo $identity; ?> = 1;
				  viewMore_<?php echo $identity; ?>();
				}
			}
		return true;
		});	
});
<?php } ?>
</script>
<?php } ?>
<?php } ?>
<script type="text/javascript">


  en4.core.runonce.add(function() {
    AutocompleterRequestJSON('search', Searchurl, function(selecteditem) {
      //window.location.href = selecteditem.url;
    });
  });
  
	function showSubCategory(cat_id,selected) {
		var url = en4.core.baseUrl + 'sesalbum/index/subcategory/category_id/' + cat_id;
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
	
    var url = en4.core.baseUrl + 'sesalbum/index/subsubcategory/subcategory_id/' + cat_id;
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
initializeSesAlbumMapList();
}
});
en4.core.runonce.add(function() {
	if(scriptJquery('#lat-wrapper').length > 0){
		editSetMarkerOnMapList();
	}
    scriptJquery('#loadingimgsesalbum-wrapper').hide();
});

</script>
