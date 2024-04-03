<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>
<div class="sescontest_currency_converter_popup">
	<?php echo $this->form->render() ?>
</div>

<script type="application/javascript">
scriptJquery ('#converter_price-wrapper').hide();

scriptJquery (document).on('submit','#sescontest_currency_converter',function(e){
		e.preventDefault();
		
		if(scriptJquery('#main_price').val() == ''){
				scriptJquery('#main_price').css('border','1px solid red');
				return false;
		}else{
				scriptJquery('#main_price').css('border','');
		}
		scriptJquery('#sesbasic_loading_cont_overlay_con').show();
		scriptJquery.ajax({
      method: 'post',
      url : scriptJquery(this).attr('action'),
      data : {
        format : 'json',
				curr:scriptJquery('#currency').val(),
				val:scriptJquery('#main_price').val(),
				is_ajax:true,
      },
      success: function(response) {
				scriptJquery('#sesbasic_loading_cont_overlay_con').hide();
				scriptJquery('#converter_price-wrapper').show();
				scriptJquery('#converter_price').val(response);
			}
    });
});
</script>
