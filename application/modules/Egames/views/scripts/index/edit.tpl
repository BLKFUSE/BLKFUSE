<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: edit.tpl 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
 <div class="content_create_form">
    <?php echo $this->form->render($this) ?>
</div>

<script type="text/javascript">


    var updateTextFields = function()
	{
		scriptJquery('#subcat_id-wrapper').hide();
        scriptJquery('#subsubcat_id-wrapper').hide();
		<?php if($this->game_id){ ?>
			if((scriptJquery('#subcat_id option').length > 1 && scriptJquery('#category_id').val() > 0) || (scriptJquery('#subcat_id').val() != 0 && scriptJquery('#subcat_id').val() != '' && scriptJquery('#subcat_id').val() != null ))
				scriptJquery('#subcat_id-wrapper').show();
			if((scriptJquery('#subsubcat_id option').length > 1  && scriptJquery('#subcat_id').val() > 0 ) || (scriptJquery('#subsubcat_id').val() != 0 && scriptJquery('#subsubcat_id').val() != '' && scriptJquery('#subsubcat_id').val() != null))
				scriptJquery('#subsubcat_id-wrapper').show();
		<?php } ?>
	}
     en4.core.runonce.add(updateTextFields);


     function showSubCategory(cat_id,selectedId) {
		var selected;
		if(selectedId != ''){
			var selected = selectedId;
		}
    var url = en4.core.baseUrl + 'egames/index/subcategory/category_id/' + cat_id;
    scriptJquery.ajax({
      url: url,
      data: {
				'selected':selected
      },
      success: function(responseHTML) {
        if (document.getElementById('subcat_id') && responseHTML.trim() != "") {
          if (document.getElementById('subcat_id-wrapper')) {
            document.getElementById('subcat_id-wrapper').style.display = "block";
          }
          document.getElementById('subcat_id').innerHTML = responseHTML;
        } else {
          if (document.getElementById('subcat_id-wrapper')) {
            document.getElementById('subcat_id-wrapper').style.display = "none";
            document.getElementById('subcat_id').innerHTML = '<option value="0"></option>';
          }
        }
			  if (document.getElementById('subsubcat_id-wrapper')) {
					document.getElementById('subsubcat_id-wrapper').style.display = "none";
					document.getElementById('subsubcat_id').innerHTML = '<option value="0"></option>';
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
    var url = en4.core.baseUrl + 'egames/index/subsubcategory/subcategory_id/' + cat_id;
    (scriptJquery.ajax({
      url: url,
      data: {
				'selected':selected
      },
      success: function(responseHTML) {
        if (document.getElementById('subsubcat_id') && responseHTML.trim() != "") {
          if (document.getElementById('subsubcat_id-wrapper')  && scriptJquery('#album').val() == 0) {
            document.getElementById('subsubcat_id-wrapper').style.display = "block";
						 document.getElementById('subsubcat_id').innerHTML = responseHTML;
          }					
       }else{
					// get category id value 						
					if (document.getElementById('subsubcat_id-wrapper')) {
						document.getElementById('subsubcat_id-wrapper').style.display = "none";
						document.getElementById('subsubcat_id').innerHTML = '<option value="0"></option>';
					} 
				}
			}
    }));  
  }
</script>
