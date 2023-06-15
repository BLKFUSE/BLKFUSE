
<?php if(!$this->is_ajax){ 
  echo $this->partial('dashboard/left-bar.tpl', 'sescontest', array(
 	 'contest' => $this->contest,
  ));	
?>
	<div class="sesbasic_dashboard_content sesbm sesbasic_clearfix">
<?php } 
  echo $this->content()->renderWidget('sescontestjoinfees.manage-orders', array('contest_id' => $this->contest->contest_id));
?>
<?php if(!$this->is_ajax){ ?>
	</div>
  </div>
</div>
<?php  } ?>
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
<?php if($this->is_ajax) die; ?>
