<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _resultcustomdates.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<div class="sescontest_choose_date" id="sescontest_announcement_date">
  <div id="contest_result_time-wrapper" class="form-wrapper">
    <div id="contest_result_time-label" class="form-label">
      <label for="contest_result_time" class="optional"><?php echo $this->translate('Result Announcement Date') ?></label>
    </div>
    <div id="contest_result_time-element" class="form-element">
      <span class="sescontest-date-field"><input type="text" class="displayF" name="result_date" id="sescontest_result_date" value="<?php echo isset($this->result_date) ? ($this->result_date) : ''  ?>"></span>
      <span class="sescontest-time-field"><input type="text" name="result_time" id="sescontest_result_time" value="<?php echo isset($this->result_time) ? ($this->result_time) : ''  ?>" class="ui-timepicker-input" autocomplete="off"></span>
    </div>
  </div>
</div>
<div id="contest_error_result_time-wrapper" class="form-wrapper" style="display:none;">
  <div class="form-element sescontest_create_error"><span id="contest_error_result_time-element"></span></div>
</div>
<script type="application/javascript">

 en4.core.runonce.add(function() {
	 <?php if(isset($this->subject) && $this->subject != ''){ ?>
		var sesstartCalanderDate = new Date('<?php echo date("m/d/Y",strtotime($this->subject->resulttime));  ?>');
	<?php }else{ ?>
		var sesstartCalanderDate = new Date(scriptJquery('#sescontest_end_date').val());
	<?php } ?>
	var sesselectedDate =  new Date(scriptJquery('#sescontest_end_date').val());
	var sesFromEndDate;
	sesBasicAutoScroll('#sescontest_result_time').timepicker({
			'showDuration': true,
			'timeFormat': 'g:ia',
	}).on('changeTime',function(){
		var lastTwoDigitStart = sesBasicAutoScroll('#sescontest_result_time').val().slice('-2');
		var startDate = new Date(sesBasicAutoScroll('#sescontest_result_date').val()+' '+sesBasicAutoScroll('#sescontest_result_time').val().replace(lastTwoDigitStart,'')+':00 '+lastTwoDigitStart);
        
        var lastTwoDigitEnd = sesBasicAutoScroll('#sescontest_end_time').val().slice('-2');
		var endDate = new Date(sesBasicAutoScroll('#sescontest_end_date').val()+' '+sesBasicAutoScroll('#sescontest_end_time').val().replace(lastTwoDigitEnd,'')+':00 '+lastTwoDigitEnd);
        
		var error = checkResultDateTime(startDate,endDate);
		if(error != ''){
			sesBasicAutoScroll('#contest_error_result_time-wrapper').show();
			sesBasicAutoScroll('#contest_error_result_time-element').text(error);
		}else{
			sesBasicAutoScroll('#contest_error_result_time-wrapper').hide();
		}
	});
	
	sesBasicAutoScroll('#sescontest_result_date').datepicker({
			format: 'm/d/yyyy',
			weekStart: 1,
			autoclose: true,
			startDate: sesBasicAutoScroll('#sescontest_end_date').val(),
			endDate: sesFromEndDate, 
	}).on('changeDate', function(ev){
		sesselectedDate = ev.date;
		var lastTwoDigitStart = sesBasicAutoScroll('#sescontest_result_time').val().slice('-2');
		var startDate = new Date(sesBasicAutoScroll('#sescontest_result_date').val()+' '+sesBasicAutoScroll('#sescontest_result_time').val().replace(lastTwoDigitStart,'')+':00 '+lastTwoDigitStart);
        var lastTwoDigitEnd = sesBasicAutoScroll('#sescontest_end_time').val().slice('-2');
		var endDate = new Date(sesBasicAutoScroll('#sescontest_end_date').val()+' '+sesBasicAutoScroll('#sescontest_end_time').val().replace(lastTwoDigitEnd,'')+':00 '+lastTwoDigitEnd);
		var error = checkResultDateTime(startDate,endDate);
		if(error != ''){
			sesBasicAutoScroll('#contest_error_result_time-wrapper').show();
			sesBasicAutoScroll('#contest_error_result_time-element').text(error);
		}else{
			sesBasicAutoScroll('#contest_error_result_time-wrapper').hide();
			sesFromEndDate = new Date(sesBasicAutoScroll('#sescontest_end_date').val());
			sesBasicAutoScroll('#sescontest_end_date').datepicker('setStartDate', sesselectedDate);
		}
	});
});

  function checkResultDateTime(startdate,enddate){
	var errorMessage = '';
	var checkdate = true;
	var currentTime =  new Date();
    if(<?php echo $this->subject ? 1 : 0 ?> == 0 && startdate.valueOf() < enddate.valueOf() && sesBasicAutoScroll('#sescontest_result_date').val() && 1 == '<?php echo $this->start_time_check; ?>'){
        errorMessage = "<?php echo $this->translate('Result date is lower than the contest End date. Please enter the result date greater than or equal to contest End date.')?>";	
    }else if(startdate.valueOf() < currentTime.valueOf() && sesBasicAutoScroll('#sescontest_result_date').val()){
        errorMessage = "<?php echo $this->translate('Result date can not be less than the Today\'s date.')?>";
    }
	return errorMessage;
  }
</script>
