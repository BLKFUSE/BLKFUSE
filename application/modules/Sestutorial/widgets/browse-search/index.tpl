<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sestutorial/externals/styles/styles.css'); ?>

<?php if( $this->form ): ?>
  <div class="<?php echo $this->viewType=='horizontal' ? 'sestutorial_browse_search_horizontal' : 'sestutorial_browse_search_vertical'; ?> sestutorial_bxs">
    <?php echo $this->form->render($this) ?>
  </div>
<?php endif; ?>

<script type="text/javascript">
var title_name = document.getElementById("title_name");
title_name.addEventListener("keydown", function (e) {
    if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
        this.form.submit();
    }
});
  
if(document.getElementById('category_id')) {
  scriptJquery(document).ready(function() {
  
    if (document.getElementById('category_id').value == 0) {
      document.getElementById('subcat_id-wrapper').style.display = "none";
      document.getElementById('subsubcat_id-wrapper').style.display = "none";
    }
    
    var cat_id = document.getElementById('category_id').value;
    if (document.getElementById('subcat_id')) 
      var subcat = document.getElementById('subcat_id').value;
    
    if(subcat == '')
      document.getElementById('subcat_id-wrapper').style.display = "none";
    
    if (subcat == 0)
      document.getElementById('subsubcat_id-wrapper').style.display = "none";
    
    if (document.getElementById('subsubcat_id'))
      var subsubcat = document.getElementById('subsubcat_id').value;

    if (cat_id)
      ses_subcategory(cat_id);
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

  var request = scriptJquery.ajax({
    dataType: 'html',
    url: en4.core.baseUrl + 'sestutorial/index/subcategory/category_id/' + category_id,
    data: {
    },
    success: function(responseHTML) {

      if (document.getElementById('subcat_id') && responseHTML) {
        if (document.getElementById('subcat_id-wrapper')) {
          document.getElementById('subcat_id-wrapper').style.display = "block";
        }

        document.getElementById('subcat_id').innerHTML = responseHTML;
        <?php if(isset($_GET['subcat_id']) && $_GET['subcat_id']): ?>
        document.getElementById('subcat_id').value = '<?php echo $_GET["subcat_id"] ?>';
        sessubsubcat_category('<?php echo $_GET["subcat_id"] ?>');
        <?php endif; ?>
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

//Function for get sub sub category
function sessubsubcat_category(category_id, module) {

  if (category_id == 0) {
    if (document.getElementById('subsubcat_id-wrapper')) {
      document.getElementById('subsubcat_id-wrapper').style.display = "none";
      document.getElementById('subsubcat_id').innerHTML = '';
    }
    return false;
  }

  var request = scriptJquery.ajax({
    dataType: 'html',
    url: en4.core.baseUrl + 'sestutorial/index/subsubcategory/category_id/' + category_id,
    data: {
    },
    success: function(responseHTML) {
      if (document.getElementById('subsubcat_id') && responseHTML) {
        if (document.getElementById('subsubcat_id-wrapper'))
          document.getElementById('subsubcat_id-wrapper').style.display = "block";
        document.getElementById('subsubcat_id').innerHTML = responseHTML;
        <?php if(isset($_GET['subsubcat_id']) && $_GET['subsubcat_id']): ?>
          document.getElementById('subsubcat_id').value = '<?php echo $_GET["subsubcat_id"] ?>';
        <?php endif; ?>
      } else {
        if (document.getElementById('subsubcat_id-wrapper')) {
          document.getElementById('subsubcat_id-wrapper').style.display = "none";
          document.getElementById('subsubcat_id').innerHTML = '';
        }
      }
    }
  });

} 
</script>
