<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: edit.tpl  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php 
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . "externals/selectize/css/normalize.css");
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/selectize/js/selectize.js'); 
?>

<?php include APPLICATION_PATH .  '/application/modules/Sestutorial/views/scripts/dismiss_message.tpl';?>
<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sestutorial', 'controller' => 'manage', 'action' => 'index'), $this->translate("Back to Add & Manage Tutorials"), array('class'=>'sestutorial_icon_back buttonlink')) ?>


<br class="clear" /><br />

<script type="text/javascript">
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
</script>
<div class='clear'>
  <div class='settings sestutorial_admin_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<script>
scriptJquery(document).ready(function(){
	scriptJquery('#subcat_id-wrapper').css('display' , 'none');
	scriptJquery('#subsubcat_id-wrapper').css('display' , 'none');
});
</script>

<script type="text/javascript">

  function showSubCategory(cat_id,selectedId,isLoad) {
		var selected;
		if(selectedId != ''){
			var selected = selectedId;
		}
    var url = en4.core.baseUrl + 'sestutorial/index/subcategory/category_id/' + cat_id;
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
            document.getElementById('subcat_id-wrapper').style.display = "flex";
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
    var url = en4.core.baseUrl + 'sestutorial/index/subsubcategory/subcategory_id/' + cat_id;
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
            document.getElementById('subsubcat_id-wrapper').style.display = "flex";
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

  scriptJquery(document).ready(function() {
    var sesdevelopment = 1;
    <?php if(isset($this->category_id) && $this->category_id != 0) { ?>
      <?php if(isset($this->subcat_id)){
        $catId = $this->subcat_id;
      } else $catId = ''; ?>
      showSubCategory('<?php echo $this->category_id; ?>','<?php echo $catId; ?>','yes');
    <?php  } else { ?>
      document.getElementById('subcat_id-wrapper').style.display = "none";
    <?php } ?>
    <?php if(isset($this->subsubcat_id)) { ?>
      if (<?php echo isset($this->subcat_id) && intval($this->subcat_id)>0 ? $this->subcat_id : 'sesdevelopment' ?> == 0) {
      document.getElementById('subsubcat_id-wrapper').style.display = "none";
      } else {
      <?php if(isset($this->subsubcat_id)){$subsubcat_id = $this->subsubcat_id;}else $subsubcat_id = ''; ?>
      showSubSubCategory('<?php echo $this->subcat_id; ?>','<?php echo $this->subsubcat_id; ?>','yes');
      }
    <?php } else { ?>
    document.getElementById('subsubcat_id-wrapper').style.display = "none";
    <?php } ?>
  });
</script>
<style type="text/css">
.sestutorial-autosuggest {
	position:absolute;
	padding:0px;
	width:300px;
	list-style:none;
	z-index:50;
	border:1px solid #d0d1d5;
	margin:0px;
	list-style:none;
	cursor:pointer;
	white-space:nowrap;
	background:#fff;
}
.sestutorial-autosuggest > li {
	padding:3px;
	margin:0 !important;
	overflow:hidden;
}
.sestutorial-autosuggest > li + li {
	border-top:1px solid #d0d1d5;
}
.sestutorial-autosuggest > li img {
	max-width:25px;
	max-height:25px;
	display:block;
	float:left;
	margin-right:5px;
}
.sestutorial-autosuggest > li.autocompleter-selected {
	background:#eee;
	color:#555;
}
.sestutorial-autosuggest > li.autocompleter-choices {
	font-size:.8em;
}
.sestutorial-autosuggest > li.autocompleter-choices .autocompleter-choice {
	line-height:25px;
}
.sestutorial-autosuggest > li:hover {
	background:#eee;
	color:#555;
}
.sestutorial-autosuggest > li span.autocompleter-queried {
	font-weight:bold;
}
ul.sestutorial-autosuggest .search-working {
	background-image:none;
}
.autocompleter-choice {
	cursor:pointer;
}
.autocompleter-choice:hover {
	color:#5ba1cd;
}
</style>
