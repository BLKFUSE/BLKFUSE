<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _customdates.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
  
<div class="sescontest_choose_date">
  <div id="contest_start_time-wrapper" class="form-wrapper">
    <div id="contest_start_time-label" class="form-label">
      <label for="contest_start_time" class="optional"><?php echo $this->translate('Start Date for Contest') ?></label>
    </div>
    <div id="contest_start_time-element" class="form-element">
      <span class="sescontest-date-field"><input type="text" class="displayF" name="start_date" id="sescontest_start_date" value="<?php echo isset($this->start_date) ? ($this->start_date) : ''  ?>"></span>
      <span class="sescontest-time-field"><input type="text" name="start_time" id="sescontest_start_time" value="<?php echo isset($this->start_time) ? ($this->start_time) : ''  ?>" class="ui-timepicker-input" autocomplete="off"></span>
    </div>
  </div>
  <div id="contest_end_time-wrapper" class="form-wrapper">
    <div id="contest_end_time-label" class="form-label">
      <label for="contest_end_time" class="optional"><?php echo $this->translate('End Date for Contest') ?></label>
    </div>
    <div id="contest_end_time-element" class="form-element">
      <span class="sescontest-date-field"><input class="displayF" type="text" name="end_date" id="sescontest_end_date" value="<?php echo isset($this->end_date) ? ($this->end_date) : ''  ?>"></span>
      <span class="sescontest-time-field"><input type="text" name="end_time" id="sescontest_end_time" value="<?php echo isset($this->end_time) ? ($this->end_time) : ''  ?>"></span>
    </div>
  </div>
    <div id="contest_join_start_time-wrapper" class="form-wrapper">
    <div id="contest_join_start_time-label" class="form-label">
      <label for="contest_join_start_time" class="optional"><?php echo $this->translate('Start Date for Entry Submission') ?></label>
    </div>
    <div id="contest_join_start_time-element" class="form-element">
      <span class="sescontest-date-field"><input type="text" class="displayF" name="join_start_date" id="sescontest_join_start_date" value="<?php echo isset($this->join_start_date) ? ($this->join_start_date) : ''  ?>"></span>
      <span class="sescontest-time-field"><input type="text" name="join_start_time" id="sescontest_join_start_time" value="<?php echo isset($this->join_start_time) ? ($this->join_start_time) : ''  ?>" class="ui-timepicker-input" autocomplete="off"></span>
    </div>
  </div>
  <div id="contest_join_end_time-wrapper" class="form-wrapper">
    <div id="contest_join_end_time-label" class="form-label">
      <label for="contest_join_end_time" class="optional"><?php echo $this->translate('End Date for Entry Submission') ?></label>
    </div>
    <div id="contest_join_end_time-element" class="form-element">
      <span class="sescontest-date-field"><input class="displayF" type="text" name="join_end_date" id="sescontest_join_end_date" value="<?php echo isset($this->join_end_date) ? ($this->join_end_date) : ''  ?>"></span>
      <span class="sescontest-time-field"><input type="text" name="join_end_time" id="sescontest_join_end_time" value="<?php echo isset($this->join_end_time) ? ($this->join_end_time) : ''  ?>"></span>
    </div>
  </div>
   <div id="contest_voting_start_time-wrapper" class="form-wrapper">
    <div id="contest_voting_start_time-label" class="form-label">
      <label for="contest_voting_start_time" class="optional"><?php echo $this->translate('Start Date for Voting') ?></label>
    </div>
    <div id="contest_voting_start_time-element" class="form-element">
      <span class="sescontest-date-field"><input type="text" class="displayF" name="voting_start_date" id="sescontest_voting_start_date" value="<?php echo isset($this->voting_start_date) ? ($this->voting_start_date) : ''  ?>"></span>
      <span class="sescontest-time-field"><input type="text" name="voting_start_time" id="sescontest_voting_start_time" value="<?php echo isset($this->voting_start_time) ? ($this->voting_start_time) : ''  ?>" class="ui-timepicker-input" autocomplete="off"></span>
    </div>
  </div>
    <div id="contest_voting_end_time-wrapper" class="form-wrapper">
    <div id="contest_voting_end_time-label" class="form-label">
      <label for="contest_voting_end_time" class="optional"><?php echo $this->translate('End Date for Voting') ?></label>
    </div>
    <div id="contest_voting_end_time-element" class="form-element">
      <span class="sescontest-date-field"><input class="displayF" type="text" name="voting_end_date" id="sescontest_voting_end_date" value="<?php echo isset($this->voting_end_date) ? ($this->voting_end_date) : ''  ?>"></span>
      <span class="sescontest-time-field"><input type="text" name="voting_end_time" id="sescontest_voting_end_time" value="<?php echo isset($this->voting_end_time) ? ($this->voting_end_time) : ''  ?>"></span>
    </div>
  </div>
</div>
<div id="contest_error_time-wrapper" class="form-wrapper" style="display:none;">
  <div class="form-element sescontest_create_error"><span id="contest_error_time-element"></span></div>
</div>
<script type="application/javascript">

 en4.core.runonce.add(function() {
    <?php if(isset($this->subject) && $this->subject != ''){ ?>   
	  var sesstartCalanderDate = new Date('<?php echo date("m/d/Y",strtotime($this->subject->creation_date));  ?>');
	<?php }else{ ?>
	  var sesstartCalanderDate = new Date('<?php echo date("m/d/Y");  ?>');
	<?php } ?>
    
    var sesselectedDate =  new Date(scriptJquery('#sescontest_start_date').val());
    var sesselectedContestEndDate =  new Date(scriptJquery('#sescontest_end_date').val());
    var sesselectedJoinDate =  new Date(scriptJquery('#sescontest_join_start_date').val());
    var sesselectedVotingDate =  new Date(scriptJquery('#sescontest_voting_end_date').val());
    var currentTime =  new Date();
    
    <?php if(isset($this->subject) && $this->subject != ''): ?>
     <?php if(strtotime($this->subject->starttime) <= time()):?>
        scriptJquery('.sescontest_choose_date').find('input[type=text]').attr("disabled", "true");
      <?php endif;?>
    <?php endif;?>
    
	var sesFromEndDate;
    var sesFromJoinEndDate;
    var sesFromVotingEndDate;
    var sesContestEndDate;
    var datetimes;
	sesBasicAutoScroll('#sescontest_start_time').timepicker({
			'showDuration': true,
			'timeFormat': 'g:ia',
	}).on('changeTime',function(){
		var lastTwoDigit = sesBasicAutoScroll('#sescontest_end_time').val().slice('-2');
		var endDate = new Date(sesBasicAutoScroll('#sescontest_end_date').val()+' '+sesBasicAutoScroll('#sescontest_end_time').val().replace(lastTwoDigit,'')+':00 '+lastTwoDigit);
		var lastTwoDigitStart = sesBasicAutoScroll('#sescontest_start_time').val().slice('-2');
		var startDate = new Date(sesBasicAutoScroll('#sescontest_start_date').val()+' '+sesBasicAutoScroll('#sescontest_start_time').val().replace(lastTwoDigitStart,'')+':00 '+lastTwoDigitStart);
		//var error = checkDateTime(startDate,endDate);
        var showErrorMessage = checkAllDateFields();
		if(showErrorMessage != ''){
            sesBasicAutoScroll('#contest_error_time-wrapper').show();
			sesBasicAutoScroll('#contest_error_time-element').text(showErrorMessage);
		}else{
			sesBasicAutoScroll('#contest_error_time-wrapper').hide();
		}
	});
	sesBasicAutoScroll('#sescontest_end_time').timepicker({
			'showDuration': true,
			'timeFormat': 'g:ia'
	}).on('changeTime',function(){
        var showErrorMessage = checkAllDateFields();
		if(showErrorMessage != ''){
            sesBasicAutoScroll('#contest_error_time-wrapper').show();
			sesBasicAutoScroll('#contest_error_time-element').text(showErrorMessage);
		}else{
			sesBasicAutoScroll('#contest_error_time-wrapper').hide();
		}
	});
	sesBasicAutoScroll('#sescontest_start_date').datepicker({
			format: 'm/d/yyyy',
			weekStart: 1,
			autoclose: true,
			startDate: sesstartCalanderDate,
			endDate: sesFromEndDate, 
	}).on('changeDate', function(ev){
		sesselectedDate = ev.date;
        sesBasicAutoScroll('#sescontest_join_start_date').datepicker('setStartDate', sesselectedDate);
        sesBasicAutoScroll('#sescontest_voting_start_date').datepicker('setStartDate', sesselectedDate);
        sesBasicAutoScroll('#sescontest_end_date').datepicker('setStartDate', sesselectedDate);
        sesBasicAutoScroll('#sescontest_join_end_date').datepicker('setStartDate', sesselectedDate); 
        sesBasicAutoScroll('#sescontest_voting_end_date').datepicker('setStartDate', sesselectedDate); 
        var showErrorMessage = checkAllDateFields();
		if(showErrorMessage != ''){
           sesBasicAutoScroll('#contest_error_time-wrapper').show();
		   sesBasicAutoScroll('#contest_error_time-element').text(showErrorMessage);
		}else{
           sesBasicAutoScroll('#contest_error_time-wrapper').hide();
           sesFromEndDate = new Date(sesBasicAutoScroll('#sescontest_end_date').val());
		}
	});
	sesBasicAutoScroll('#sescontest_end_date').datepicker({
			format: 'm/d/yyyy',
			weekStart: 1,
			autoclose: true,
			startDate: sesselectedDate,
	}).on('changeDate', function(ev){
		sesContestEndDate = new Date(ev.date.valueOf());
        sesBasicAutoScroll('#sescontest_result_date').datepicker('setStartDate', scriptJquery('#sescontest_end_date').val());
        sesBasicAutoScroll('#sescontest_join_end_date').datepicker('setStartDate', scriptJquery('#sescontest_start_date').val());
        sesBasicAutoScroll('#sescontest_voting_end_date').datepicker('setStartDate', scriptJquery('#sescontest_start_date').val()); 
        sesBasicAutoScroll('#sescontest_join_start_date').datepicker('setEndDate', sesContestEndDate);
        sesBasicAutoScroll('#sescontest_join_end_date').datepicker('setEndDate', sesContestEndDate);
        sesBasicAutoScroll('#sescontest_voting_start_date').datepicker('setEndDate', sesContestEndDate);
        sesBasicAutoScroll('#sescontest_voting_end_date').datepicker('setEndDate', sesContestEndDate);
		sesContestEndDate.setDate(sesContestEndDate.getDate(new Date(ev.date.valueOf())));
        var showErrorMessage = checkAllDateFields();
		if(showErrorMessage != ''){
            sesBasicAutoScroll('#contest_error_time-wrapper').show();
			sesBasicAutoScroll('#contest_error_time-element').text(showErrorMessage);
		}else{
			sesBasicAutoScroll('#contest_error_time-wrapper').hide();
			sesBasicAutoScroll('#sescontest_start_date').datepicker('setEndDate', sesContestEndDate);
            sesBasicAutoScroll('#sescontest_start_date').datepicker("setDate",scriptJquery('#sescontest_start_date').val()); 
		}
	});
    sesBasicAutoScroll('#sescontest_join_start_time').timepicker({
			'showDuration': true,
			'timeFormat': 'g:ia',
	}).on('changeTime',function(){
        var showErrorMessage = checkAllDateFields();
		if(showErrorMessage != ''){
            sesBasicAutoScroll('#contest_error_time-wrapper').show();
			sesBasicAutoScroll('#contest_error_time-element').text(showErrorMessage);
		}else{
			sesBasicAutoScroll('#contest_error_time-wrapper').hide();
		}
	});
	sesBasicAutoScroll('#sescontest_join_end_time').timepicker({
			'showDuration': true,
			'timeFormat': 'g:ia'
	}).on('changeTime',function(){
        var showErrorMessage = checkAllDateFields();
		if(showErrorMessage != ''){
            sesBasicAutoScroll('#contest_error_time-wrapper').show();
			sesBasicAutoScroll('#contest_error_time-element').text(showErrorMessage);
		}else{
			sesBasicAutoScroll('#contest_error_time-wrapper').hide();
		}
	});
	sesBasicAutoScroll('#sescontest_join_start_date').datepicker({
			format: 'm/d/yyyy',
			weekStart: 1,
			autoclose: true,
			startDate: scriptJquery('#sescontest_start_date').val(),
			endDate: sesselectedContestEndDate, 
	}).on('changeDate', function(ev){
		sesselectedJoinDate = ev.date;
        sesBasicAutoScroll('#sescontest_voting_start_date').datepicker('setStartDate', sesselectedJoinDate);
        sesBasicAutoScroll('#sescontest_join_end_date').datepicker('setStartDate', sesselectedJoinDate);
        var showErrorMessage = checkAllDateFields();
		if(showErrorMessage != ''){
            sesBasicAutoScroll('#contest_error_time-wrapper').show();
			sesBasicAutoScroll('#contest_error_time-element').text(showErrorMessage);
		}else{
			sesBasicAutoScroll('#contest_error_time-wrapper').hide();
			sesFromEndDate = new Date(sesBasicAutoScroll('#sescontest_end_date').val());
			sesBasicAutoScroll('#sescontest_end_date').datepicker('setStartDate', sesselectedDate);
		}
	});
	sesBasicAutoScroll('#sescontest_join_end_date').datepicker({
			format: 'm/d/yyyy',
			weekStart: 1,
			autoclose: true,
			startDate: sesstartCalanderDate,
            endDate: sesselectedContestEndDate,
	}).on('changeDate', function(ev){
		sesFromJoinEndDate = new Date(ev.date.valueOf());
		sesFromJoinEndDate.setDate(sesFromJoinEndDate.getDate(new Date(ev.date.valueOf())));
        var showErrorMessage = checkAllDateFields();
		if(showErrorMessage != ''){
            sesBasicAutoScroll('#contest_error_time-wrapper').show();
			sesBasicAutoScroll('#contest_error_time-element').text(showErrorMessage);
		}else{
			sesBasicAutoScroll('#contest_error_time-wrapper').hide();
			sesBasicAutoScroll('#sescontest_start_date').datepicker('setEndDate', sesFromEndDate);
		}
	});
    sesBasicAutoScroll('#sescontest_voting_start_time').timepicker({
			'showDuration': true,
			'timeFormat': 'g:ia',
	}).on('changeTime',function(){
        var showErrorMessage = checkAllDateFields();
		if(showErrorMessage != ''){
            sesBasicAutoScroll('#contest_error_time-wrapper').show();
			sesBasicAutoScroll('#contest_error_time-element').text(showErrorMessage);
		}else{
			sesBasicAutoScroll('#contest_error_time-wrapper').hide();
		}
	});
	sesBasicAutoScroll('#sescontest_voting_end_time').timepicker({
			'showDuration': true,
			'timeFormat': 'g:ia'
	}).on('changeTime',function(){
        var showErrorMessage = checkAllDateFields();
		if(showErrorMessage != ''){
            sesBasicAutoScroll('#contest_error_time-wrapper').show();
			sesBasicAutoScroll('#contest_error_time-element').text(showErrorMessage);
		}else{
			sesBasicAutoScroll('#contest_error_time-wrapper').hide();
		}
	});
	sesBasicAutoScroll('#sescontest_voting_start_date').datepicker({
			format: 'm/d/yyyy',
			weekStart: 1,
			autoclose: true,
			startDate: sesstartCalanderDate,
			endDate: sesselectedContestEndDate, 
	}).on('changeDate', function(ev){
	//	sesselectedVotingDate = ev.date;
        var showErrorMessage = checkAllDateFields();
		if(showErrorMessage != ''){
            sesBasicAutoScroll('#contest_error_time-wrapper').show();
			sesBasicAutoScroll('#contest_error_time-element').text(showErrorMessage);
		}else{
			sesBasicAutoScroll('#contest_error_time-wrapper').hide();
			sesFromEndDate = new Date(sesBasicAutoScroll('#sescontest_end_date').val());
			sesBasicAutoScroll('#sescontest_end_date').datepicker('setStartDate', sesselectedDate);
		}
	});
	sesBasicAutoScroll('#sescontest_voting_end_date').datepicker({
			format: 'm/d/yyyy',
			weekStart: 1,
			autoclose: true,
			startDate: sesstartCalanderDate,
            endDate: sesselectedContestEndDate,
	}).on('changeDate', function(ev){
		sesFromVotingEndDate = new Date(ev.date.valueOf());
		sesFromVotingEndDate.setDate(sesFromVotingEndDate.getDate(new Date(ev.date.valueOf())));
        var showErrorMessage = checkAllDateFields();
		if(showErrorMessage != ''){ 
            sesBasicAutoScroll('#contest_error_time-wrapper').show();
			sesBasicAutoScroll('#contest_error_time-element').text(showErrorMessage);
		}else{
			sesBasicAutoScroll('#contest_error_time-wrapper').hide();
			sesBasicAutoScroll('#sescontest_start_date').datepicker('setEndDate', sesFromEndDate);
		}
	});
});
  
  function checkAllDateFields() {    
    scriptJquery('.sescontest-error').removeClass("sescontest-error");
    var allDates = allDateTimeData();
    var errorMessage = '';
    var currentTime =  new Date();
    var format = 'YYYY/MM/DD HH:mm:ss';
    currentTime = moment(currentTime, format).tz(scriptJquery('#contest_timezone_jq').val()).format(format);
    currentTime =  new Date(currentTime);    
    sesstartCalanderDate = new Date(moment(currentTime, 'MM/DD/YYYY').tz(scriptJquery('#contest_timezone_jq').val()).format('MM/DD/YYYY'));
    sesBasicAutoScroll('#sescontest_start_date').datepicker('setStartDate', sesstartCalanderDate);
    if(allDates[0].valueOf() >= allDates[1].valueOf()) {
      scriptJquery('#contest_start_time-element').addClass("sescontest-error");
      return errorMessage = "<?php echo $this->translate('Contest Start date can not be greater than or equal to the contest End date.')?>";
    }
    if(allDates[0].valueOf() < currentTime.valueOf()) {
      scriptJquery('#contest_start_time-element').addClass("sescontest-error");
      return errorMessage = "<?php echo $this->translate('Contest Start date can not be a Past date, so please select a date greater than or equal to Today\'s date.')?>";
    }
    if(allDates[1].valueOf() <= allDates[0].valueOf()) {
     scriptJquery('#contest_end_time-element').addClass("sescontest-error");
     return errorMessage = "<?php echo $this->translate('Contest End date can not be less than or equal to the contest Start date.')?>";
    }
    if(allDates[1].valueOf() < currentTime.valueOf()) {
     scriptJquery('#contest_end_time-element').addClass("sescontest-error");
     return errorMessage = "<?php echo $this->translate('Contest End date can not be a Past date.')?>";
    }
    if(allDates[2].valueOf() < currentTime.valueOf()) {
     scriptJquery('#contest_join_start_time-element').addClass("sescontest-error");
     return errorMessage = "<?php echo $this->translate('Contest participation Start date can not be a Past date.')?>";
    }
    if(allDates[2].valueOf() < allDates[0].valueOf()) {
     scriptJquery('#contest_join_start_time-element').addClass("sescontest-error");
     return errorMessage = "<?php echo $this->translate('Contest participation Start date can not be less than contest Start date.')?>";
    }
    if(allDates[2].valueOf() >= allDates[1].valueOf()) {
     scriptJquery('#contest_join_start_time-element').addClass("sescontest-error");
     return errorMessage = "<?php echo $this->translate('Contest participation Start date can not be greater than or equal to the contest End date.')?>";
    }
    if(allDates[2].valueOf() >= allDates[3].valueOf()) {
     scriptJquery('#contest_join_start_time-element').addClass("sescontest-error");
     return errorMessage = "<?php echo $this->translate('Contest participation start date can not be greater than or equal to the participation End date.')?>";
    }
    if(allDates[4].valueOf() >= allDates[1].valueOf()) {
     scriptJquery('#contest_voting_start_time-element').addClass("sescontest-error");
     return errorMessage = "<?php echo $this->translate('Contest voting start date can not be greater than or equal to the contest End date.')?>";
    }
    if(allDates[4].valueOf() >= allDates[5].valueOf()) {
     scriptJquery('#contest_voting_start_time-element').addClass("sescontest-error");
     return errorMessage = "<?php echo $this->translate('Contest voting Start date can not be in greater than or equal to the contest voting end date.')?>";
    }
    if(allDates[4].valueOf() < allDates[2].valueOf()) {
     scriptJquery('#contest_voting_start_time-element').addClass("sescontest-error");
     return errorMessage = "<?php echo $this->translate('Contest voting Start date can not be less than the contest Joining Start date.')?>";
    }
    if(allDates[5].valueOf() < allDates[3].valueOf()) {
     scriptJquery('#contest_voting_end_time-element').addClass("sescontest-error");
     return errorMessage = "<?php echo $this->translate('Contest voting End date can not be in less than the contest Joining End date.')?>";
    }
    if(allDates[1].valueOf() < allDates[3].valueOf() || allDates[1].valueOf() < allDates[5].valueOf()) {
     scriptJquery('#contest_end_time-element').addClass("sescontest-error");
     return errorMessage = "<?php echo $this->translate('Contest End date can not be in less than the contest Joining End date and contest Voting End date.')?>";
    }
    if(allDates[4].valueOf() < currentTime.valueOf()) {
     scriptJquery('#contest_voting_start_time-element').addClass("sescontest-error");
     return errorMessage = "<?php echo $this->translate('Contest voting Start date can not be a Past date.')?>";
    }
    return errorMessage;
  }
  
  function allDateTimeData() {
    var lastTwoDigit = sesBasicAutoScroll('#sescontest_voting_end_time').val().slice('-2');
    var votingendDate = new Date(sesBasicAutoScroll('#sescontest_voting_end_date').val()+' '+sesBasicAutoScroll('#sescontest_voting_end_time').val().replace(lastTwoDigit,'')+':00 '+lastTwoDigit);
    var lastTwoDigitStart = sesBasicAutoScroll('#sescontest_voting_start_time').val().slice('-2');
    var votingstartDate = new Date(sesBasicAutoScroll('#sescontest_voting_start_date').val()+' '+sesBasicAutoScroll('#sescontest_voting_start_time').val().replace(lastTwoDigitStart,'')+':00 '+lastTwoDigitStart);

    var lastTwoDigitJoinStart = sesBasicAutoScroll('#sescontest_join_start_time').val().slice('-2');
    var joinStartDate = new Date(sesBasicAutoScroll('#sescontest_join_start_date').val()+' '+sesBasicAutoScroll('#sescontest_join_start_time').val().replace(lastTwoDigitJoinStart,'')+':00 '+lastTwoDigitJoinStart);

    var lastTwoDigitJoinEnd = sesBasicAutoScroll('#sescontest_join_end_time').val().slice('-2');
    var joinEndDate = new Date(sesBasicAutoScroll('#sescontest_join_end_date').val()+' '+sesBasicAutoScroll('#sescontest_join_end_time').val().replace(lastTwoDigitJoinEnd,'')+':00 '+lastTwoDigitJoinEnd);

    var lastTwoDigitContestEnd = sesBasicAutoScroll('#sescontest_end_time').val().slice('-2');
    var contestEndDate = new Date(sesBasicAutoScroll('#sescontest_end_date').val()+' '+sesBasicAutoScroll('#sescontest_end_time').val().replace(lastTwoDigitContestEnd,'')+':00 '+lastTwoDigitContestEnd);
    
    var lastTwoDigitContestStart = sesBasicAutoScroll('#sescontest_start_time').val().slice('-2');
    var contestStartDate = new Date(sesBasicAutoScroll('#sescontest_start_date').val()+' '+sesBasicAutoScroll('#sescontest_start_time').val().replace(lastTwoDigitContestStart,'')+':00 '+lastTwoDigitContestStart); 
    return [contestStartDate,contestEndDate,joinStartDate,joinEndDate,votingstartDate,votingendDate];
  }
</script>

