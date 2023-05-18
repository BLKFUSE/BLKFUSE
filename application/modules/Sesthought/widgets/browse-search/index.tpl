<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<script>

  function showSubCategory(cat_id,selected) {

    scriptJquery.ajax({
      dataType: 'html',
      url: en4.core.baseUrl + 'sesthought/category/subcategory/category_id/' + cat_id,
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

    (scriptJquery.ajax({
      dataType: 'html',
      url: en4.core.baseUrl + 'sesthought/category/subsubcategory/subcategory_id/' + cat_id,
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
    if(document.getElementById('category_id')) {
      <?php if(isset($_GET['category_id']) && $_GET['category_id'] != 0) { ?>
        <?php if(isset($_GET['subcat_id'])){$catId = $_GET['subcat_id'];}else $catId = ''; ?>
          showSubCategory('<?php echo $_GET['category_id']; ?>','<?php echo $catId; ?>');
        <?php if(isset($_GET['subsubcat_id'])){ ?>
          <?php if(isset($_GET['subsubcat_id'])){$subsubcat_id = $_GET['subsubcat_id'];}else $subsubcat_id = ''; ?>
            showSubSubCategory("<?php echo $_GET['subcat_id']; ?>","<?php echo $_GET['subsubcat_id']; ?>");
          <?php } else { ?>
            document.getElementById('subsubcat_id-wrapper').style.display = "none";
          <?php } ?>
      <?php  } else { ?>
        document.getElementById('subcat_id-wrapper').style.display = "none";
        document.getElementById('subsubcat_id-wrapper').style.display = "none";
      <?php } ?>
    }
  });
</script>
<?php $randonNumber = 8000; ?>
<?php $request = Zend_Controller_Front::getInstance()->getRequest();?>
<?php $actionName = $request->getActionName();?>
<?php //if($actionName == 'browse') { ?>
  <script type="application/javascript">
  en4.core.runonce.add(function() {
    scriptJquery(document).on('submit','#filter_form',function(e) {
        e.preventDefault();
        scriptJquery('#loadingimgsesprayer-wrapper').show();
        scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').html('');
        searchParams = scriptJquery(this).serialize();
        page = 1;
        loadMoreTHOUGHT();
      return true;
    });
  });
  </script>
<?php //} ?>
<?php if( $this->form ): ?>
	<div class="sesthought_browse_search sesbasic_bxs sesbasic_clearfix <?php if($this->viewType == 'horizontal') { ?> sesthought_browse_search_horrizontal<?php } ?>">
  	<?php echo $this->form->render($this) ?>
  </div>
<?php endif ?>
<script>
en4.core.runonce.add(function() {
  scriptJquery('#loadingimgsesthought-element').hide();
});
</script>
