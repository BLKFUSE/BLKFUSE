<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/moment.min.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/daterangepicker.min.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/daterangepicker.css'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/datepicker/bootstrap-datepicker.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/datepicker.css'); ?>
<div class="sesbasic_clearfix sesbasic_bxs sescontest_browse_search <?php echo $this->view_type=='horizontal' ? 'sescontest_browse_search_horizontal' : 'sescontest_browse_search_vertical'; ?>">
  <?php echo $this->form->render($this) ?>
</div>
<?php $request = Zend_Controller_Front::getInstance()->getRequest();?>
<?php $controllerName = $request->getControllerName();?>
<?php $actionName = $request->getActionName();?>
<?php $class = '.sescontest_contest_listing';?>
<?php if($actionName == 'all-results'):?>
<?php $pageName = 'advancedsearch_index_sescontest';?>
<?php $widgetName = 'sescontest.browse-contests';?>
<?php elseif($actionName == 'manage'):?>
  <?php $pageName = 'sescontest_index_manage';?>
  <?php $widgetName = 'sescontest.manage-contests';?>
<?php elseif($actionName == 'pinboard'):?>
<?php $pageName = 'sescontest_index_pinboard';?>
<?php $widgetName = 'sescontest.browse-contests';?>
<?php elseif($actionName == 'browse-contests'):?>
<?php $pageName = 'sescontest_index_'.$this->page_id;?>
<?php $widgetName = 'sescontest.browse-contests';?>
<?php else:?>
   <?php $pageName = 'sescontest_index_browse';?>
  <?php $widgetName = 'sescontest.browse-contests';?>
<?php endif;?>
<?php $identity = Engine_Api::_()->sesbasic()->getIdentityWidget($widgetName,'widget',$pageName); ?>

<script type="application/javascript">
    function formSubmit<?php echo $identity; ?>(obj){
        if(scriptJquery('<?php echo $class;?>').length > 0){
            scriptJquery('#tabbed-widget_<?php echo $identity; ?>').html('');
            scriptJquery('#loading_image_<?php echo $identity; ?>').show();
            scriptJquery('#loadingimgsescontest-wrapper').show();
            is_search_<?php echo $identity; ?> = 1;
            if(typeof paggingNumber<?php echo $identity; ?> == 'function'){
                isSearch = true;
                searchParams<?php echo $identity; ?> = scriptJquery(obj).serialize();
                paggingNumber<?php echo $identity; ?>(1);
            }else if(typeof viewMore_<?php echo $identity; ?> == 'function'){
                isSearch = true;
                searchParams<?php echo $identity; ?> = scriptJquery(obj).serialize();
                page<?php echo $identity; ?> = 1;
                viewMore_<?php echo $identity; ?>();
            }
        }
    }
    en4.core.runonce.add(function () {
    scriptJquery(document).on('submit','#filter_form',function(e){
      e.preventDefault();
      formSubmit<?php echo $identity; ?>(this);
      return true;
    });	
  });
  var Searchurl = "<?php echo $this->url(array('module' =>'sescontest','controller' => 'ajax', 'action' => 'get-contest'),'default',true); ?>";
  en4.core.runonce.add(function() {
    AutocompleterRequestJSON('search', Searchurl, function(selecteditem) {
      //window.location.href = selecteditem.url;
    });
  });
  
  function showSubCategory(cat_id,selected) {
    var url = en4.core.baseUrl + 'sescontest/ajax/subcategory/category_id/' + cat_id + '/type/'+ 'search';
    scriptJquery.ajax({
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
	} 
	else {
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

    var url = en4.core.baseUrl + 'sescontest/ajax/subsubcategory/subcategory_id/' + cat_id + '/type/'+ 'search';;
    (scriptJquery.ajax({
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
	} 
	else {
	  if (document.getElementById('subsubcat_id-wrapper')) {
	    document.getElementById('subsubcat_id-wrapper').style.display = "none";
	    document.getElementById('subsubcat_id').innerHTML = '';
	  }
	}
      }
    }));  
  }

    en4.core.runonce.add(function () {
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

  scriptJquery(function() {
    scriptJquery('input[name="show_date_field"]').daterangepicker({
      opens: 'left'
    }, function(start, end, label) {
      console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });
  });
</script>
<style>
  .datepicker .footer button.apply:before{content:"Search";}
  .datepicker .footer button.cancel:before{content:"Cancel";}
</style>
