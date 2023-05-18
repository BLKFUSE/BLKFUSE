<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>

<?php if( $this->form ): ?>
  <div class="sesbasic_browse_search sesmusic_browse_search">
    <?php echo $this->form->render($this) ?>
  </div>
<?php endif; 
	$defaultProfileFieldId = "0_0_$this->defaultProfileId";
	$profile_type = 'sesmusic_album';
?>
<?php echo $this->partial('_customFields.tpl', 'sesbasic', array()); ?>
<script type="text/javascript">
    en4.core.runonce.add(function() {
        var title_name = document.getElementById("title_name");
        title_name.addEventListener("keydown", function (e) {
            if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
                this.form.submit();
            }
        });
    });
  formObj = scriptJquery('#filter_form').find('div').find('div').find('div');
	function showSubCategory(cat_id,selected) {
    var url = en4.core.baseUrl + 'sesmusic/index/subcategory/category_id/' + cat_id + '/type/'+ 'search';
    scriptJquery.ajax({
			method: 'post',
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
    showFields(cat_id,1);
      }
    }); 
  }
 var defaultProfileFieldId = '<?php echo $defaultProfileFieldId ?>';
  var profile_type = '<?php echo $profile_type ?>';
  var previous_mapped_level = 0;
  function showFields(cat_value, cat_level,typed,isLoad) {
    var categoryId = getProfileType(formObj.find('#category_id-wrapper').find('#category_id-element').find('#category_id').val());
    var subcatId = getProfileType(formObj.find('#subcat_id-wrapper').find('#subcat_id-element').find('#subcat_id').val());
    var subsubcatId = getProfileType(formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').val());
    var type = categoryId+','+subcatId+','+subsubcatId;
    if (cat_level == 1 || (previous_mapped_level >= cat_level && previous_mapped_level != 1) || (profile_type == null || profile_type == '' || profile_type == 0)) {
      profile_type = getProfileType(cat_value);
      if (profile_type == 0) {
        profile_type = '';
      } else {
        previous_mapped_level = cat_level;
      }
      if(document.getElementById(defaultProfileFieldId))
      document.getElementById(defaultProfileFieldId).value = profile_type;
      changeFields(document.getElementById(defaultProfileFieldId),null,isLoad,type);
    }
  }
   var getProfileType = function(category_id) {
        var mapping = <?php echo Zend_Json_Encoder::encode(Engine_Api::_()->getDbTable('categories', 'sesmusic')->getMapping(array('category_id'=>true, 'profile_type'=>true,'param'=>'album'))); ?>;
              for (i = 0; i < mapping.length; i++) {	
            if (mapping[i].category_id == category_id)
            return mapping[i].profile_type;
            }
        return 0;
      }
  en4.core.runonce.add(function() {
    var defaultProfileId = '<?php echo '0_0_' . $this->defaultProfileId ?>' + '-wrapper';
     if ($type(document.getElementById(defaultProfileId)) && typeof document.getElementById(defaultProfileId) != 'undefined') {
      scriptJquery('#'+defaultProfileId).css('display', 'none');
    }
  });
  function showSubSubCategory(cat_id,selected) {
    var categoryId = getProfileType(document.getElementById('category_id').value);
    if(cat_id == 0){
      if (document.getElementById('subsubcat_id-wrapper')) {
        document.getElementById('subsubcat_id-wrapper').style.display = "none";
        document.getElementById('subsubcat_id').innerHTML = '';
      }
      showFields(cat_id,1,categoryId);
      return false;
    }
    showFields(cat_id,1,categoryId);
    var url = en4.core.baseUrl + 'sesmusic/index/subsubcategory/subcategory_id/' + cat_id + '/type/'+ 'search';;
    (scriptJquery.ajax({
			method: 'post',
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
  function showCustomOnLoad(value,isLoad){
    showFields(value,1,'',isLoad);
  }
  en4.core.runonce.add(function(){
    showCustomOnLoad('','no');
  });
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
  });</script>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>
<script type="text/javascript">
  en4.core.runonce.add(function() {
    AutocompleterRequestJSON('title_name', "<?php echo $this->url(array('module' => 'sesmusic', 'controller' => 'index', 'action' => 'search', 'actonType' => 'browse', 'sesmusic_commonsearch' => 'sesmusic_album'), 'default', true) ?>", function(selecteditem) {
      //window.location.href = selecteditem.url;
    });
  });
</script>
