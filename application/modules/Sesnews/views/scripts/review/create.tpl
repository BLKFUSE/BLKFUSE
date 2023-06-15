<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: create.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<style>
.sesnews_review_form .global_form div.form-label label.requiredstart:after{content: " *";color: #f00;}
</style>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); ?>
<?php 
	$defaultProfileFieldId = "0_0_$this->defaultProfileId";
	$profile_type = 2;
  echo $this->partial('_customFields.tpl', 'sesbasic', array()); 
?>

<?php $tab_id = Engine_Api::_()->sesbasic()->getWidgetTabId(array('name' => 'sesnews.news-reviews'));?>
<div class="layout_middle">
  <div class="generic_layout_container">
    <div class="clear sesnews_order_view_top">
      <a href="<?php echo $this->item->getHref().'/tab/'.$tab_id; ?>" class="buttonlink sesbasic_icon_back"><?php echo $this->translate("Back To News"); ?></a>
    </div>

<script>

  var defaultProfileFieldId = '<?php echo $defaultProfileFieldId ?>';
  var profile_type = '<?php echo $profile_type ?>';
  var previous_mapped_level = 0;
  
  
 function showFields(cat_value, cat_level,typed,isLoad) {
		var categoryId = getProfileType('<?php echo $this->category_id ?>');
		var subcatId = getProfileType('<?php echo $this->subcat_id ?>');
		var subsubcatId = getProfileType('<?php echo $this->subsubcat_id ?>');
		var type = categoryId+','+subcatId+','+subsubcatId;
    if (cat_level == 1 || (previous_mapped_level >= cat_level && previous_mapped_level != 1) || (profile_type == null || profile_type == '' || profile_type == 0)) {
      profile_type = getProfileType(cat_value);
      if (profile_type == 0) {
        profile_type = '';
      } else {
        previous_mapped_level = cat_level;
      }
      document.getElementById(defaultProfileFieldId).value = profile_type;
      changeFields(document.getElementById(defaultProfileFieldId),null,isLoad,type);
    }
  }
  var getProfileType = function(category_id) {
    var mapping = <?php echo Zend_Json_Encoder::encode(Engine_Api::_()->getDbTable('categories', 'sesnews')->getMapping(array('category_id', 'profile_type_review'))); ?>;
		  for (i = 0; i < mapping.length; i++) {	
      	if (mapping[i].category_id == category_id)
        return mapping[i].profile_type_review;
    	}
    return 0;
  }
  en4.core.runonce.add(function() {
		scriptJquery('.sesnews_form_rating_star').find('label').addClass('requiredstart');
    var defaultProfileId = '<?php echo '0_0_' . $this->defaultProfileId ?>' + '-wrapper';
     if ($type(document.getElementById(defaultProfileId)) && typeof document.getElementById(defaultProfileId) != 'undefined') {
      scriptJquery('#'+defaultProfileId).css('display', 'none');
    }
  });
	function showCustomOnLoad(value,isLoad){
	 <?php if(isset($this->category_id) && $this->category_id != 0){ ?>
		var categoryId = getProfileType(<?php echo $this->category_id; ?>)+',';
		<?php if(isset($this->subcat_id) && $this->subcat_id != 0){ ?>
		var subcatId = getProfileType(<?php echo $this->subcat_id; ?>)+',';
		<?php  }else{ ?>
		var subcatId = '';
		<?php } ?>
		<?php if(isset($this->subsubcat_id) && $this->subsubcat_id != 0){ ?>
		var subsubcat_id = getProfileType(<?php echo $this->subsubcat_id; ?>)+',';
		<?php  }else{ ?>
		var subsubcat_id = '';
		<?php } ?>
		var id = (categoryId+subcatId+subsubcat_id).replace(/,+$/g,"");;
			showFields(value,1,id,isLoad);
		if(value == 0)
			document.getElementsByName("0_0_1")[0].value=subcatId;	
			return false;
		<?php } ?>
	}
 scriptJquery(document).ready(function() {	
	 		showCustomOnLoad('','no');
  });
  
</script> 
    <div class="sesnews_review_form"> 
      <?php echo $this->form->render($this);?>
    </div>
  </div>
</div>
