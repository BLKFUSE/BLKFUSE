<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: edit.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/scripts/core.js'); 
?>
<?php 
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . "externals/selectize/css/normalize.css");
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/selectize/js/selectize.js'); 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); ?>


<div class="headline">
  <h2>
    <?php echo $this->translate('Sesnews');?>
  </h2>
  <div class="tabs">
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render();?>
  </div>
</div>

<?php $defaultProfileFieldId = "0_0_".$this->defaultProfileId;$profile_type = 2;?>
<?php echo $this->partial('_customFields.tpl', 'sesbasic', array()); ?>
<div class="sesnews_news_form"> <?php echo $this->form->render(); ?></div>

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
  <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews_enable_location', 1)){ ?>
    scriptJquery(document).ready(function(){
      scriptJquery('#lat-wrapper').css('display' , 'none');
      scriptJquery('#lng-wrapper').css('display' , 'none');
      scriptJquery('#mapcanvas-element').attr('id','map-canvas');
      scriptJquery('#map-canvas').css('height','200px');
      scriptJquery('#mapcanvas-wrapper').hide();
      scriptJquery('#map-canvas').css('width','500px');
      scriptJquery('#ses_location-label').attr('id','ses_location_data_list');
      scriptJquery('#ses_location_data_list').html("<?php echo isset($_POST['location']) ? $_POST['location'] : '' ; ?>");
      scriptJquery('#ses_location-wrapper').css('display','none');
      initializeSesNewsMap();
    });
    scriptJquery( window ).load(function() {
      editMarkerOnMapNewsEdit();
    });
  <?php } ?>
</script>

<script type="application/javascript">
  var defaultProfileFieldId = '<?php echo $defaultProfileFieldId ?>';
  var profile_type = '<?php echo $profile_type ?>';
  var previous_mapped_level = 0;
  function showFields(cat_value, cat_level,typed,isLoad) {
	if(isLoad == 'custom'){
		var type = typed;
	}else{
		var categoryId = getProfileType(document.getElementById('category_id').value);
		var subcatId = getProfileType(document.getElementById('subcat_id').value);
		var subsubcatId = getProfileType(document.getElementById('subsubcat_id').value);
		var type = categoryId+','+subcatId+','+subsubcatId;
		
	}
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
    var mapping = <?php echo Zend_Json_Encoder::encode(Engine_Api::_()->getDbTable('categories', 'sesnews')->getMapping(array('category_id', 'profile_type'))); ?>;
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
  
  function showSubCategory(cat_id,selectedId, isLoad) {
 
    var selected;
    if(selectedId != '')
    var selected = selectedId;
    var url = en4.core.baseUrl + 'sesnews/index/subcategory/category_id/' + cat_id;
    scriptJquery.ajax({
    method: 'post',
      dataType: 'html',
      url: url,
      data: {'selected':selected},
      success: function(responseHTML) {

	  if (document.getElementById('subcat_id') && responseHTML) {
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
	
	<?php if(isset($this->subsubcat_id)){$subsubcat_id = $this->subsubcat_id;}else $subsubcat_id = ''; ?>
        showSubSubCategory('<?php echo $this->subcat_id; ?>','<?php echo $this->subsubcat_id; ?>','yes');
	if(isLoad != 'yes')
	showFields(cat_id,1);
      }
    }); 
  }
  
  function showSubSubCategory(cat_id,selectedId,isLoad) {     
    var categoryId = getProfileType(document.getElementById('category_id').value);
   
    if(cat_id == 0){
 
      if(isLoad != 'yes')
      showFields(cat_id,1,categoryId);
      return false;
    }
    if(isLoad != 'yes')
    showFields(cat_id,1,categoryId);
    var selected;
    if(selectedId != ''){
      var selected = selectedId;
    }
    var url = en4.core.baseUrl + 'sesnews/index/subsubcategory/subcategory_id/' + cat_id;
    (scriptJquery.ajax({
    method: 'post',
      dataType: 'html',
      url: url,
      data: {'selected':selected},
      success: function(responseHTML) {
      
	  if (document.getElementById('subsubcat_id') && responseHTML) {
	    if (document.getElementById('subsubcat_id-wrapper')) {
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
  
  function showCustom(value,isLoad){

    var categoryId = getProfileType(document.getElementById('category_id').value);
    var subcatId = getProfileType(document.getElementById('subcat_id').value);
    var id = categoryId+','+subcatId;
    if(isLoad != 'yes')
    showFields(value,1,id,isLoad);
    if(value == 0)
    document.getElementsByName("0_0_1")[0].value=subcatId;	
    return false;
  }
  
  function showCustomOnLoad(value,isLoad){
    <?php if(isset($this->category_id) && $this->category_id != 0){ ?>
      var categoryId = getProfileType(<?php echo $this->category_id; ?>)+',';
    <?php }else{ ?>
      var categoryId = '0';
    <?php } ?>
    <?php if(isset($this->subcat_id) && $this->subcat_id != 0){ ?>
      var subcatId = getProfileType(<?php echo $this->subcat_id; ?>)+',';
    <?php  }else{ ?>
      var subcatId = '0';
    <?php } ?>
    <?php if(isset($this->subsubcat_id) && $this->subsubcat_id != 0){ ?>
      var subsubcat_id = getProfileType(<?php echo $this->subsubcat_id; ?>)+',';
    <?php  }else{ ?>
      var subsubcat_id = '0';
    <?php } ?>
    var id = (categoryId+subcatId+subsubcat_id).replace(/,+$/g,"");;
    showFields(value,1,id,'custom');
    if(value == 0)
    document.getElementsByName("0_0_1")[0].value=subcatId;	
    return false;
  }
  
  scriptJquery(document).ready(function(e){
  var sesdevelopment = 1;
  <?php if(isset($this->category_id) && $this->category_id != 0){ ?>
    <?php if(isset($this->subcat_id)){$catId = $this->subcat_id;}else $catId = ''; ?>
  showSubCategory('<?php echo $this->category_id; ?>','<?php echo $catId; ?>','yes');
  <?php  }else{ ?>
    if(document.getElementById('subcat_id-wrapper'))
    document.getElementById('subcat_id-wrapper').style.display = "none";
  <?php } ?>
  <?php if(isset($this->subsubcat_id)){ ?>
    if (<?php echo isset($this->subcat_id) && intval($this->subcat_id)>0 ? $this->subcat_id : 'sesdevelopment' ?> == 0) {
    document.getElementById('subsubcat_id-wrapper').style.display = "none";
    } 
  <?php }else{ ?>
    document.getElementById('subsubcat_id-wrapper').style.display = "none";
  <?php } ?>
  showCustomOnLoad('','no');
  });

//prevent form submit on enter
  scriptJquery("#form-upload").bind("keypress", function (e) {		
    if (e.keyCode == 13 && scriptJquery('#'+e.target.id).prop('tagName') != 'TEXTAREA') {
    e.preventDefault();
    }else{
    return true;	
    }
  });
	//Ajax error show before form submit
var error = false;
var objectError ;
var counter = 0;
  function validateForm(){
    var errorPresent = false; 
    scriptJquery('#sesnews_edit input, #sesnews_edit select,#sesnews_edit checkbox,#sesnews_edit textarea,#sesnews_edit radio').each(
      function(index){
	var input = scriptJquery(this);
	if(scriptJquery(this).closest('div').parent().css('display') != 'none' && scriptJquery(this).closest('div').parent().find('.form-label').find('label').first().hasClass('required') && scriptJquery(this).prop('type') != 'hidden' && scriptJquery(this).closest('div').parent().attr('class') != 'form-elements'){	
	  if(scriptJquery(this).prop('type') == 'checkbox'){
	    value = '';
	    if(scriptJquery('input[name="'+scriptJquery(this).attr('name')+'"]:checked').length > 0) { 
	      value = 1;
	    };
	    if(value == '')
	    error = true;
	    else
	    error = false;
	  }
	  else if(scriptJquery(this).prop('type') == 'select-multiple'){
	    if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
	    error = true;
	    else
	    error = false;
	  }
	  else if(scriptJquery(this).prop('type') == 'select-one' || scriptJquery(this).prop('type') == 'select' ){
	    if(scriptJquery(this).val() === '')
	    error = true;
	    else
	    error = false;
	  }
	  else if(scriptJquery(this).prop('type') == 'radio'){
	    if(scriptJquery("input[name='"+scriptJquery(this).attr('name').replace('[]','')+"']:checked").val() === '')
	    error = true;
	    else
	    error = false;
	  }
	  else if(scriptJquery(this).prop('type') == 'textarea' && scriptJquery(this).prop('id') == 'body'){
	    if(tinyMCE.get('body').getContent() === '' || tinyMCE.get('body').getContent() == null)
	    error = true;
	    else
	    error = false;
	  }
	  else if(scriptJquery(this).prop('type') == 'textarea') {
	    if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
	    error = true;
	    else
	    error = false;
	  }
	  else{
	    if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
	    error = true;
	    else
	    error = false;
	  }
	  if(error){
	    if(counter == 0){
	      objectError = this;
	    }
	    counter++
	  }
	  else{
	  }
	  if(error)
	  errorPresent = true;
	  error = false;
	}
      }
    );
    return errorPresent ;
  }
  scriptJquery('#sesnews_edit').submit(function(e){
    var validationFm = validateForm();
    if(validationFm) {
      alert('<?php echo $this->string()->escapeJavascript("Please fill the red mark fields"); ?>');
      if(typeof objectError != 'undefined'){
	var errorFirstObject = scriptJquery(objectError).parent().parent();
	scriptJquery('html, body').animate({
	scrollTop: errorFirstObject.offset().top
	}, 2000);
      }
      return false;	
    }
    else{
      scriptJquery('#upload').attr('disabled',true);
      scriptJquery('#upload').html('<?php echo $this->translate("Submitting Form ...") ; ?>');
      return true;
    }			
  });
</script>

<script type="text/javascript">
  scriptJquery('.core_main_sesnews').parent().addClass('active');
</script>
