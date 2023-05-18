<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbasic
 * @package    Sesbasic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: currency-converter.tpl 2016-07-26 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/styles.css'); ?>
<div class="sesbasic_currency_converter_popup">
	<?php echo $this->form->render() ?>
</div>

<script type="application/javascript">
scriptJquery ('#converter_price-wrapper').hide();

scriptJquery (document).on('submit','#sesbasic_currency_converter',function(e){

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
      onComplete: function(response) {
				scriptJquery('#sesbasic_loading_cont_overlay_con').hide();
				scriptJquery('#converter_price-wrapper').show();
				scriptJquery('#converter_price').val(response);
			}
    });
});
</script>
