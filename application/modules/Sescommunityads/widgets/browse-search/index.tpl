<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/scripts/core.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/styles/styles.css'); ?>

<div class="sescmads_search sesbasic_bxs <?php echo $this->view_type=='horizontal' ? 'sescmads_search_horizontal' : 'sescmads_search_vertical'; ?>">
  <?php echo $this->form->render($this) ?>
</div>

<?php $request = Zend_Controller_Front::getInstance()->getRequest();?>
<?php $controllerName = $request->getControllerName();?>
<?php $actionName = $request->getActionName(); ?>

<?php if($actionName == 'browse'){?>
  <?php $pageName = 'sescommunityads_index_browse';?>
  <?php $widgetName = 'sescommunityads.browse-ads';?>
<?php } ?>

<?php $identity = Engine_Api::_()->sescommunityads()->getIdentityWidget($widgetName,'widget',$pageName); ?>
<script type="application/javascript">
  scriptJquery(document).ready(function(){
    if(scriptJquery('#content_type').length && scriptJquery('#content_type').val() != "promote_content"){
      scriptJquery('#content_module-wrapper').hide();  
    }
    scriptJquery('#loadingimgsescommunityads-wrapper').hide();
    scriptJquery('#filter_form').submit(function(e){
      e.preventDefault();
      if(scriptJquery('#sescomm_widget_<?php echo $identity; ?>').length > 0){
        scriptJquery('#sescomm_widget_<?php echo $identity; ?>').html('');
        scriptJquery('#loading_image_<?php echo $identity; ?>').show();
        scriptJquery('#loadingimgsescommunityads-wrapper').show();
        is_search_<?php echo $identity; ?> = 1;
        if(typeof paggingNumber<?php echo $identity; ?> == 'function'){
          document.getElementById('sescomm_widget_<?php echo $identity; ?>').innerHTML = "<div class='clear sesbasic_loading_container' id='loading_images_browse_<?php echo $identity; ?>'></div>";
          isSearch = true;
          e.preventDefault();
          searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
          paggingNumber<?php echo $identity; ?>(1);
        }else if(typeof viewMore_<?php echo $identity; ?> == 'function'){
          isSearch = true;
          e.preventDefault();
          searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
          page<?php echo $identity; ?> = 1;
          viewMore_<?php echo $identity; ?>();
        }
      }
      return true;
    });	
  });
  
  function showSubCategory(cat_id,selected) {
    var url = en4.core.baseUrl + 'sescommunityads/index/subcategory/category_id/' + cat_id + '/type/'+ 'search';
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

    var url = en4.core.baseUrl + 'sescommunityads/index/subsubcategory/subcategory_id/' + cat_id + '/type/'+ 'search';;
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
  function getModule(value){
      if(value == "promote_content"){
          scriptJquery('#content_module-wrapper').show();
      }else{
        scriptJquery('#content_module-wrapper').hide();  
      }
  }
  scriptJquery(document).ready(function() {
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
  
  scriptJquery(document).ready(function(){
    mapLoad = false;
    if(scriptJquery('#lat-wrapper').length > 0){
      scriptJquery('#lat-wrapper').css('display' , 'none');
      scriptJquery('#lng-wrapper').css('display' , 'none');
      sescommMapSearch();
    }
  });
</script>
