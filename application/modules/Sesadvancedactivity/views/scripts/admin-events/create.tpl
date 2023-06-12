<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: create.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<script type="text/javascript">
  
</script>
<div class='sesbasic_popup_form settings'>
  <?php echo $this->form->render($this); ?>
</div>
<style>
  .calendar_output_span{display:none}
  #date-hour, #date-minute, #date-ampm{display:none;}
  #starttime-hour, #starttime-minute, #starttime-ampm{display:none;}
  #endtime-hour, #endtime-minute, #endtime-ampm{display:none;}
  
</style>

<script>
  scriptJquery("#date-hour").val("1");
  scriptJquery("#date-minute").val("0");
  scriptJquery("#date-ampm").val("AM");

  scriptJquery("#endtime-hour").val("1");
  scriptJquery("#endtime-minute").val("0"); 
  scriptJquery("#endtime-ampm").val("AM");
  scriptJquery("#starttime-hour").val("1");
  scriptJquery("#starttime-minute").val("0");
  scriptJquery("#starttime-ampm").val("AM");
  en4.core.runonce.add(function() {

  scriptJquery('#date-date').attr("type","text").attr("autocomplete","off").attr("placeholder","Select a Date").datepicker({
    
    timepicker: true,
  })
  scriptJquery('#starttime-date').attr("type","text").attr("autocomplete","off").attr("placeholder","Select a Date").datepicker({
    
    timepicker: true,
    })
  scriptJquery('#endtime-date').attr("type","text").attr("autocomplete","off").attr("placeholder","Select a Date").datepicker({
      
      timepicker: true,
  })

    
    choosedate(<?php echo $this->visibility; ?>);
  });
function choosedate(value) {
  if(value != 4) {
    if(document.getElementById('starttime-wrapper'))
      document.getElementById('starttime-wrapper').style.display = 'none';
    if(document.getElementById('endtime-wrapper'))
      document.getElementById('endtime-wrapper').style.display = 'none';
  } else {
    if(document.getElementById('starttime-wrapper'))
      document.getElementById('starttime-wrapper').style.display = 'block';
    if(document.getElementById('endtime-wrapper'))
      document.getElementById('endtime-wrapper').style.display = 'block';
  }
}
</script>
