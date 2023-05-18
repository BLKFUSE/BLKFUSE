<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my-orders.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Epaidcontent/externals/styles/style.css'); ?>

<style>
#date-date_from,
#date-date_to{ display:block !important;}
</style>
<script type="application/javascript">
function executeAfterLoad(){
	if(!scriptJquery('#date-date_to').length )
		return;
	var FromEndDateOrder;
	var selectedDateOrder =  new Date(scriptJquery('#date-date_to').val());
	scriptJquery('#date-date_to').datepicker({
			format: 'yyyy-m-d',
			weekStart: 1,
			autoclose: true,
			endDate: FromEndDateOrder, 
	}).on('changeDate', function(ev){
		selectedDateOrder = ev.date;	
		scriptJquery('#date-date_from').datepicker('setStartDate', selectedDateOrder);
	});
	scriptJquery('#date-date_from').datepicker({
			format: 'yyyy-m-d',
			weekStart: 1,
			autoclose: true,
			startDate: selectedDateOrder,
	}).on('changeDate', function(ev){
		FromEndDateOrder	= ev.date;	
		 scriptJquery('#date-date_to').datepicker('setEndDate', FromEndDateOrder);
	});	
}
</script>
