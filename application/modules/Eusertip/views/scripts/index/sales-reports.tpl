<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: sales-reports.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eusertip/externals/styles/style.css'); ?>

<div class="eusertip_dashboard_content sesbm sesbasic_clearfix">

	<div class="sesbasic_dashboard_content_header sesbasic_clearfix">
		<h3><?php echo $this->translate('Sales Reports'); ?></h3>
    <br />
    <p><?php echo $this->translate('Below, you can see the sales report of fees paid by members to submit entries in your paid page on this website. Entering criteria into the filter fields will help you find specific reports. You can also download the reports in csv and excel formats.'); ?></p>
     <br />
  </div>
  <div class="eusertip_dashboard_search eusertip_sale_report_form">
  	<?php echo $this->form->render($this); ?>
	</div>
   <br />
  <?php if( isset($this->eventSaleData) && engine_count($this->eventSaleData) > 0): ?>
  <?php $defaultCurrency = Engine_Api::_()->eusertip()->defaultCurrency(); ?>
  <div class="sesbasic_dashboard_table sesbasic_bxs">
    <form method="post" >
      <table class="eusertip_manage_table">
        <thead>
          <tr>
            <th><?php echo $this->translate("S.No"); ?></th>
            <th><?php echo $this->translate("Date of Purchase") ?></th>
            <th class="centerT"> <?php echo $this->translate("Quatity") ?></th>
            <th><?php echo $this->translate("Total Amount") ?></th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $counter = 1;
            foreach ($this->eventSaleData as $item): ?>
          <tr>
            <td data-label="<?php echo $this->translate("S.No"); ?>"><?php echo $counter; ?></td>
            <td data-label="<?php echo $this->translate("Date of Purchase") ?>" class='eusertip_manage_table_bold'><?php echo Engine_Api::_()->eusertip()->dateFormat($item->creation_date); ?></td> 
            <td class="centerT" data-label="<?php echo $this->translate("Quatity") ?>"><?php echo $item->total_orders; ?></td>
            <td  data-label="<?php echo $this->translate("Total Amount") ?>" class='eusertip_manage_table_price'><?php echo Engine_Api::_()->eusertip()->getCurrencyPrice($item->totalAmountSale,$defaultCurrency); ?></td>
          </tr>
          <?php $counter++;
                endforeach; ?>
        </tbody>
      </table>
    </form>
  </div>
  <?php else: ?>
  <div class="tip">
    <span>
      <?php echo $this->translate("Currently no entry has been submitted to your paid page.") ?>
    </span>
  </div>
  <?php endif; ?>
</div>

<script type="application/javascript">
scriptJquery(document).on('click','.eusertip_report_download',function(){
	var downloadType = 	scriptJquery(this).attr('data-rel');
	if(downloadType == 'csv'){
		scriptJquery('#csv').val('1');
	}else{
			scriptJquery('#excel').val('1');
	}
	scriptJquery('#eusertip_search_form_sale_report').trigger('submit');
	scriptJquery('#csv').val('');
	scriptJquery('#excel').val('');
	
});
</script>
<style>
#startdate,
#enddate{ display:block !important;}
.widthClass{width:90px !important;}
</style>
<script type="application/javascript">
scriptJquery('#startdate').addClass('widthClass');
scriptJquery('#enddate').addClass('widthClass');
if(scriptJquery('#startdate')){
	var FromEndDateSales;
	var selectedDateSales =  new Date(scriptJquery('#startdate').val());
	scriptJquery('#startdate').datepicker({
			format: 'yyyy-m-d',
			weekStart: 1,
			autoclose: true,
			endDate: FromEndDateSales, 
	}).on('changeDate', function(ev){
		selectedDateSales = ev.date;	
		scriptJquery('#enddate').datepicker('setStartDate', selectedDateSales);
	});
	scriptJquery('#enddate').datepicker({
			format: 'yyyy-m-d',
			weekStart: 1,
			autoclose: true,
			startDate: selectedDateSales,
	}).on('changeDate', function(ev){
		FromEndDateSales	= ev.date;	
		 scriptJquery('#startdate').datepicker('setEndDate', FromEndDateSales);
	});	
}
</script>
