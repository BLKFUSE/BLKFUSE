<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: edit.tpl  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesthought/externals/scripts/core.js'); ?>
<?php
  $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesthought/externals/styles/styles.css');

?>
<?php 
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . "externals/selectize/css/normalize.css");
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/selectize/js/selectize.js'); 
?>
<script type="text/javascript">


  function showSubCategory(cat_id,selectedId,isLoad) {
  
		var selected;
		if(selectedId != '')
			var selected = selectedId;
		
    var url = en4.core.baseUrl + 'sesthought/category/subcategory/category_id/' + cat_id;
    scriptJquery.ajax({
			method:'post',
      dataType: 'html',
      url: url,
      data: {
        'selected':selected
      },
      success: function(responseHTML) {
        if (document.getElementById('subcat_id') && responseHTML) {
          if (formObj.find('#subcat_id-wrapper')) {
            formObj.find('#subcat_id-wrapper').show();
          }
          formObj.find('#subcat_id-wrapper').find('#subcat_id-element').find('#subcat_id').html(responseHTML);
        } else {
          if (formObj.find('#subcat_id-wrapper')) {
            formObj.find('#subcat_id-wrapper').hide();
            document.getElementById('subcat_id').innerHTML = '<option value="0"></option>';
          }
        }
			  if (formObj.find('#subsubcat_id-wrapper')) {
					formObj.find('#subsubcat_id-wrapper').hide();
					document.getElementById('subsubcat_id').innerHTML = '<option value="0"></option>';
				}
      }
    });
  }
  
	function showSubSubCategory(cat_id,selectedId,isLoad) {

		if(cat_id == 0) {
			if (formObj.find('#subsubcat_id-wrapper')) {
				formObj.find('#subsubcat_id-wrapper').hide();
				document.getElementById('subsubcat_id').innerHTML = '';
      }
			return false;
		}

		var selected;
		if(selectedId != ''){
			var selected = selectedId;
		}
		
    var url = en4.core.baseUrl + 'sesthought/category/subsubcategory/subcategory_id/' + cat_id;
    (scriptJquery.ajax({
			method:'post',
      dataType: 'html',
      url: url,
      data: {
				'selected':selected
      },
      success: function(responseHTML) {
        if (document.getElementById('subsubcat_id') && responseHTML) {
          if (formObj.find('#subsubcat_id-wrapper')) {
            formObj.find('#subsubcat_id-wrapper').show();
            formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html(responseHTML);
          }
       } else {
					// get category id value
					if (formObj.find('#subsubcat_id-wrapper')) {
						formObj.find('#subsubcat_id-wrapper').hide();
						document.getElementById('subsubcat_id').innerHTML = '<option value="0"></option>';
					} 
				}
			}
    }));
  }

  en4.core.runonce.add(function() {

    formObj = scriptJquery('#sesthoughts_create').find('div').find('div').find('div');
    var sesdevelopment = 1;
    
    <?php if(isset($this->category_id) && $this->category_id != 0) { ?>
			<?php if(isset($this->subcat_id)) { $catId = $this->subcat_id; } else $catId = ''; ?>
      showSubCategory('<?php echo $this->category_id; ?>','<?php echo $catId; ?>','yes');
    <?php  } else { ?>
      formObj.find('#subcat_id-wrapper').hide();
    <?php } ?>
    
	  <?php if(isset($this->subsubcat_id)) { ?>
      if (<?php echo isset($this->subcat_id) && intval($this->subcat_id)>0 ? $this->subcat_id : 'sesdevelopment' ?> == 0) {
       formObj.find('#subsubcat_id-wrapper').hide();
      } else {
        <?php if(isset($this->subsubcat_id)){$subsubcat_id = $this->subsubcat_id;}else $subsubcat_id = ''; ?>
        showSubSubCategory('<?php echo $this->subcat_id; ?>','<?php echo $this->subsubcat_id; ?>','yes');
      }
    <?php } else { ?>
      formObj.find('#subsubcat_id-wrapper').hide();
	  <?php } ?>
  });

  en4.core.runonce.add(function() {
    scriptJquery('#tags').selectize({
      maxItems: 10,
      valueField: 'label',
      labelField: 'label',
      searchField: 'label',
      create: true,
      load: function(query, callback) {
        if (!query.length) return callback();
        scriptJquery.ajax({
          url: '<?php echo $this->url(array('controller' => 'tag', 'action' => 'suggest'), 'default', true) ?>',
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

  function addThought(formObject) {
  
    var validationFmThought = validateThoughtForm();
    
    if(scriptJquery('#title').val() == '') {
      alert('<?php echo $this->string()->escapeJavascript("Please fill requried fields."); ?>');
      return false;
    }

    if(validationFmThought) {
      
      var input = scriptJquery(formObject);
      alert('<?php echo $this->string()->escapeJavascript("Please fill the red mark fields"); ?>');
      if(typeof objectError != 'undefined') {
        var errorFirstObject = scriptJquery(objectError).parent().parent();
          scriptJquery('html, body').animate({
          scrollTop: errorFirstObject.offset().top
        }, 2000);
      }

      return false;
    } else {
      submitThought(formObject);
    }
  }
  

  function submitThought(formObject) {
  
    scriptJquery('#sesthought_overlay').show();
    var formData = new FormData(formObject);
    formData.append('is_ajax', 1);
    formData.append('thought_id', '<?php echo $this->thought_id; ?>');
    scriptJquery.ajax({
      url: "sesthought/index/edit/",
      type: "POST",
      contentType:false,
      processData: false,
      cache: false,
      data: formData,
      success: function(response) {
        var result = scriptJquery.parseJSON(response);
        if(result.status == 1) {
          scriptJquery('#sesthought_overlay').hide();
          scriptJquery('#sessmoothbox_container').html("<div id='sesthought_success_message' class='sesprofilefield_success_message sesthought_success_message'><i class='fa-check-circle-o'></i><span>You have successfully posted discussion.</span></div>");
          scriptJquery('#sesthought_success_message').fadeOut("slow", function(){
            setTimeout(function() {
              sessmoothboxclose();
              var url = '<?php echo $this->url(array('action' => 'manage'), 'sesthought_general', true); ?>';
              window.location.href = url;
              
            }, 1000);
          });
        }
      }
    });
  }
</script>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesthought/externals/styles/styles.css'); ?>
<div class="sesthought_create_popup sesbasic_bxs">
  <div class="sesbasic_loading_cont_overlay" id="sesthought_overlay"></div>
  <?php if(empty($this->is_ajax) ) { ?>
      <?php echo $this->form->render($this);?>
  <?php } ?>
</div>
<?php die; ?>