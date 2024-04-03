<?php
echo $this->partial('dashboard/left-bar.tpl', 'sescontest', array(
	'contest' => $this->contest,
      ));	
?>
<div class="sesbasic_dashboard_content sesbm sesbasic_clearfix">
  <div class="sesbasic_dashboard_form">
<?php echo $this->form->render($this) ?>
  </div>
</div>
<script type="application/javascript">
scriptJquery('#currency').hide();
scriptJquery('#currency-wrapper').hide();

<?php if(Engine_Api::_()->sescontestjoinfees()->isMultiCurrencyAvailable()){ ?>
	scriptJquery('#entry_fees-element').append('<span class="fa fa-retweet sescontest_convert_icon sesbasic_link_btn" id="sescontest_currency_coverter" title="<?php echo $this->translate("Convert to %s",Engine_Api::_()->getApi("settings","core")->getSetting("sesmultiplecurrency.defaultcurrency","USD"));?>"></span>');
	scriptJquery('#entry_fees-label').append('<span> (<?php echo Engine_Api::_()->getApi("settings","core")->getSetting("sesmultiplecurrency.defaultcurrency","USD"); ?>)</span>');
<?php }else{ ?>
	scriptJquery('#entry_fees-label').append('<span> (<?php echo Engine_Api::_()->sescontestjoinfees()->defaultCurrency(); ?>)</span>');
<?php } ?>

</script>
