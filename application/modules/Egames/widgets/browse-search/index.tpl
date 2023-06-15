<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Egames/externals/styles/styles.css'); ?>

<div class="egames_browse_search sesbasic_bxs <?php echo $this->view_type=='horizontal' ? 'egames_browse_search_horizontal' : ''; ?>">
  <?php echo $this->searchForm->render($this) ?>
</div>
<?php $request = Zend_Controller_Front::getInstance()->getRequest();?>
<?php $controllerName = $request->getControllerName();?>
<?php $actionName = $request->getActionName();?>
<?php if($controllerName == 'index' && $actionName == 'browse') { ?>
<?php $identity = Engine_Api::_()->egames()->getIdentityWidget('egames.browse-games','widget','egames_index_'.$this->page_id); ?>
<?php if($identity){ ?>
<script type="application/javascript">
    en4.core.runonce.add(function() {
		scriptJquery('#filter_form').submit(function(e){
				scriptJquery('#loadingimgegames-wrapper').show()
				if(typeof loadMoreGames == 'function'){
					e.preventDefault();
					searchParamsGames = scriptJquery(this).serialize();
					pageGame = 1;
				  	loadMoreGames("remove");
				}else{
					return true;
				}
					return false;
		});	
});
</script>
<?php } ?>
<?php } ?>
<script type="text/javascript">

  en4.core.runonce.add(function() {
    AutocompleterRequestJSON('search', "<?php echo $this->url(array('module' =>'egames','controller' => 'index', 'action' => 'get-games'),'default',true); ?>", function(selecteditem) {
    });
  });

	function showSubCategory(cat_id,selected) {
		var url = en4.core.baseUrl + 'egames/index/subcategory/category_id/' + cat_id;
		scriptJquery.ajax({
			method: 'post',
			url: url,
			data: {
				'selected':selected
      },
			success: function(responseHTML) {
				if (document.getElementById('subcat_id') && responseHTML.trim() != "") {
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
	
    var url = en4.core.baseUrl + 'egames/index/subsubcategory/subcategory_id/' + cat_id;
    (scriptJquery.ajax({
			method: 'post',
      url: url,
			data: {
				'selected':selected
      },
      success: function(responseHTML) {
        if (document.getElementById('subsubcat_id') && responseHTML.trim() != "") {
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
	scriptJquery("#loadingimgegames-wrapper").hide();
  });
</script>
