
<?php $options = Engine_Api::_()->getDbTable('options','sescontest')->getAllOptions();
	if(engine_count($options)){ ?>
<div id="custom-fields-container" style="display:none;">
  <?php foreach($options as $valueOptions){
  		//get all meta values related to options
      $metaValues = Engine_Api::_()->getDbTable('metas','sescontest')->getMetaData($valueOptions['option_id']);
      if(!engine_count($metaValues))
      	continue;
   ?>
   <div>
  	<div style="font-weight:bold;"><a class="openClass" href="javascript:;"><?php echo $valueOptions['label']; ?></a></div>
    	<ul class="metaValues" style="display:none;">
    	<?php foreach($metaValues as $metaValue){ ?>
      		<li>
          	<input type="checkbox" name="1_<?php echo $valueOptions['option_id'].'_'.$metaValue['field_id']; ?>" value="<?php echo  $valueOptions['option_id']; ?>" <?php if(engine_in_array('1_'.$valueOptions['option_id'].'_'.$metaValue['field_id'],$this->customFields)){ ?> checked="checked" <?php } ?> /><?php echo $metaValue['label']; ?> 
          </li>
      <?php } ?>
      </ul>
  </div>
  <?php } ?>  	
</div>
<?php } ?>
<script type="application/javascript">
function customField(value){
	if(value == 2){
		scriptJquery('#custom-fields-container').show();
	}else{
			scriptJquery('#custom-fields-container').hide();
	}
}
scriptJquery(document).ready(function(e){
	var valueS = (document.querySelector('input[name="custom_fields"]:checked').value);
	if(valueS == 2){
		scriptJquery('#custom_fields-'+valueS).trigger('click');	
	}
});
scriptJquery(document).on('click','.openClass',function(e){
	if(scriptJquery(this).hasClass('active')){
		scriptJquery(this).removeClass('active');
		scriptJquery(this).parent().parent().find('.metaValues').hide();
	}else{
		scriptJquery(this).addClass('active');
		scriptJquery(this).parent().parent().find('.metaValues').show();	
	}	
});
</script>
