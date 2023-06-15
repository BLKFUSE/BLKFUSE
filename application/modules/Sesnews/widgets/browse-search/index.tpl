<?php/** * SocialEngineSolutions * * @category   Application_Sesnews * @package    Sesnews * @copyright  Copyright 2019-2020 SocialEngineSolutions * @license    http://www.socialenginesolutions.com/license/ * @version    $Id: index.tpl  2019-02-27 00:00:00 SocialEngineSolutions $ * @author     SocialEngineSolutions */  ?><?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/scripts/core.js'); ?><?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?><div class="sesnews_browse_search <?php echo $this->view_type=='horizontal' ? 'sesnews_browse_search_horizontal' : 'sesnews_browse_search_vertical'; ?>">  <?php echo $this->form->render($this) ?></div><?php $request = Zend_Controller_Front::getInstance()->getRequest();?><?php $controllerName = $request->getControllerName(); ?><?php $actionName = $request->getActionName(); ?><?php $class = '.sesnews_news_listing';?><?php if($actionName == 'all-results'):?>  <?php $pageName = 'advancedsearch_index_sesnews';?>  <?php $widgetName = 'sesnews.browse-news';?><?php elseif($controllerName == 'index' && $actionName == 'browse'):?>  <?php $pageName = 'sesnews_index_browse';?>  <?php $widgetName = 'sesnews.browse-news';?><?php elseif($controllerName == 'rss' && $actionName == 'browse'):?>  <?php $pageName = 'sesnews_rss_browse';?>  <?php $widgetName = 'sesnews.browse-rss';?>  <?php $class = '.sesnews_search_result'; ?><?php elseif($actionName == 'browse-news'):?>  <?php $pageName = 'sesnews_index_'.$this->page_id;?>  <?php $widgetName = 'sesnews.browse-news';?><?php elseif($actionName == 'manage'):?>  <?php $pageName = 'sesnews_index_manage';?>  <?php $widgetName = 'sesnews.manage-news';?><?php elseif($actionName == 'locations'):?>  <?php $pageName = 'sesnews_index_locations';?>  <?php $widgetName = 'sesnews.news-location';?>  <?php $class = '.sesbasic_large_map';?><?php endif;?><?php $identity = Engine_Api::_()->sesnews()->getIdentityWidget($widgetName,'widget',$pageName); ?><script type="application/javascript">    en4.core.runonce.add(function() {     <?php if($controllerName == 'index' && $actionName == 'locations'):?>    scriptJquery('#filter_form').submit(function(e){      e.preventDefault();      var error = false;      scriptJquery('#loadingimgsesmember-wrapper').show();      e.preventDefault();      searchParams = scriptJquery(this).serialize();      scriptJquery('#loadingimgsesmember-wrapper').show();      callNewMarkersAjax();      return true;    });	   <?php else:?>      scriptJquery('#filter_form').submit(function(e){      e.preventDefault();      if(scriptJquery('<?php echo $class;?>').length > 0) {        scriptJquery('#tabbed-widget_<?php echo $identity; ?>').html('');        //document.getElementById("tabbed-widget_<?php echo $identity; ?>").innerHTML = "<div class='clear sesbasic_loading_container' id='loading_images_browse_<?php echo $identity; ?>'></div>";        scriptJquery('#loading_image_<?php echo $identity; ?>').show();        scriptJquery('#loadingimgsesnews-wrapper').show();        is_search_<?php echo $identity; ?> = 1;        if(typeof paggingNumber<?php echo $identity; ?> == 'function'){          isSearch = true;          e.preventDefault();          searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();          paggingNumber<?php echo $identity; ?>(1);        }else if(typeof viewMore_<?php echo $identity; ?> == 'function'){          isSearch = true;          e.preventDefault();          searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();          page<?php echo $identity; ?> = 1;          viewMore_<?php echo $identity; ?>();        }      }      return true;    });	    <?php endif; ?>  });  var Searchurl = "<?php echo $this->url(array('module' =>'sesnews','controller' => 'index', 'action' => 'get-news'),'default',true); ?>";  en4.core.runonce.add(function() {    AutocompleterRequestJSON('search', Searchurl, function(selecteditem) {      //window.location.href = selecteditem.url;    })  });    function showSubCategory(cat_id,selected) {    var url = en4.core.baseUrl + 'sesnews/index/subcategory/category_id/' + cat_id + '/type/'+ 'search';    scriptJquery.ajax({    method: 'post',      dataType: 'html',      url: url,      data: {	'selected':selected      },      success: function(responseHTML) {	if (document.getElementById('subcat_id') && responseHTML) {	  if (document.getElementById('subcat_id-wrapper')) {	    document.getElementById('subcat_id-wrapper').style.display = "inline-block";	  }	  document.getElementById('subcat_id').innerHTML = responseHTML;	} 	else {	  if (document.getElementById('subcat_id-wrapper')) {	    document.getElementById('subcat_id-wrapper').style.display = "none";	    document.getElementById('subcat_id').innerHTML = '';	  }	  if (document.getElementById('subsubcat_id-wrapper')) {	    document.getElementById('subsubcat_id-wrapper').style.display = "none";	    document.getElementById('subsubcat_id').innerHTML = '';	  }	}      }    });   }    function showSubSubCategory(cat_id,selected) {    if(cat_id == 0){      if (document.getElementById('subsubcat_id-wrapper')) {	document.getElementById('subsubcat_id-wrapper').style.display = "none";	document.getElementById('subsubcat_id').innerHTML = '';      }	      return false;    }    var url = en4.core.baseUrl + 'sesnews/index/subsubcategory/subcategory_id/' + cat_id + '/type/'+ 'search';;    (scriptJquery.ajax({    method: 'post',      dataType: 'html',      url: url,      data: {	'selected':selected      },      success: function(responseHTML) {	if (document.getElementById('subsubcat_id') && responseHTML) {	  if (document.getElementById('subsubcat_id-wrapper')) {	    document.getElementById('subsubcat_id-wrapper').style.display = "inline-block";	  }	  document.getElementById('subsubcat_id').innerHTML = responseHTML;	} 	else {	  if (document.getElementById('subsubcat_id-wrapper')) {	    document.getElementById('subsubcat_id-wrapper').style.display = "none";	    document.getElementById('subsubcat_id').innerHTML = '';	  }	}      }    }));    }    en4.core.runonce.add(function() {    if(document.getElementById('category_id')){      var catAssign = 1;      <?php if(isset($_GET['category_id']) && $_GET['category_id'] != 0){ ?>	<?php if(isset($_GET['subcat_id'])){$catId = $_GET['subcat_id'];}else $catId = ''; ?>	showSubCategory('<?php echo $_GET['category_id']; ?>','<?php echo $catId; ?>');	<?php if(isset($_GET['subsubcat_id'])){ ?>	<?php if(isset($_GET['subsubcat_id'])){$subsubcat_id = $_GET['subsubcat_id'];}else $subsubcat_id = ''; ?>	showSubSubCategory("<?php echo $_GET['subcat_id']; ?>","<?php echo $_GET['subsubcat_id']; ?>");	<?php }else{?>	document.getElementById('subsubcat_id-wrapper').style.display = "none";	<?php } ?>      <?php  }else{?>	document.getElementById('subcat_id-wrapper').style.display = "none";	document.getElementById('subsubcat_id-wrapper').style.display = "none";      <?php } ?>    }  });    en4.core.runonce.add(function() {    mapLoad = false;    if(scriptJquery('#lat-wrapper').length > 0){      scriptJquery('#lat-wrapper').css('display' , 'none');      scriptJquery('#lng-wrapper').css('display' , 'none');      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>      initializeSesNewsMapList();      <?php } ?>    }  });</script>