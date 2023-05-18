<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/styles/styles.css'); ?>
<div class="sescmads_report_form">
  <?php echo $this->form->render($this); ?>
</div>

<script type="text/javascript">
  scriptJquery(``).insertBefore(scriptJquery('#start-date').attr("type","text").attr("autocomplete","off").attr("placeholder","Select a Date").datepicker({
    dateFormat: "dd/mm/yy",
    timepicker: true,
   })
  );
  
  scriptJquery(``).insertBefore(scriptJquery('#end-date').attr("type","text").attr("autocomplete","off").attr("placeholder","Select a Date").datepicker({
      dateFormat: "dd/mm/yy",
      timepicker: true,
    })
  );

  scriptJquery('#start-hour').hide();
  scriptJquery('#start-minute').hide();
  scriptJquery('#start-ampm').hide();
  scriptJquery('#end-hour').hide();
  scriptJquery('#end-minute').hide();
  scriptJquery('#end-ampm').hide();
  scriptJquery('#calendar_output_span_start-date').hide();
  scriptJquery('#calendar_output_span_end-date').hide();
</script>

<script type="application/javascript">
// scriptJquery('#start-hour').hide();
// scriptJquery('#start-minute').hide();
// scriptJquery('#start-ampm').hide();
// scriptJquery('#end-hour').hide();
// scriptJquery('#end-minute').hide();
// scriptJquery('#end-ampm').hide();
// scriptJquery('#start_group').show();
// scriptJquery('#end_group').show();
function changeType(value){
  if(value == "month"){
    scriptJquery('#start_group').show();
    scriptJquery('#end_group').show();
    scriptJquery('#cal_grp').hide();
  }else{
    scriptJquery('#cal_grp').show();
    scriptJquery('#start_group').hide();
    scriptJquery('#end_group').hide();
  }
}

function formate(value){
  if(value == "campaign"){
    scriptJquery('#ads-wrapper').hide();
    scriptJquery('#campaign-wrapper').show();
  }else{
    scriptJquery('#ads-wrapper').show();
    scriptJquery('#campaign-wrapper').hide();
  }
}
scriptJquery(document).ready(function(){
  changeType(scriptJquery('#type').val());
  formate(scriptJquery('#format_type').val());
  
  scriptJquery('#start-hour').val('1');
  scriptJquery('#start-minute').val('0');
  scriptJquery('#start-ampm').val('AM');
  scriptJquery('#end-hour').val('1');
  scriptJquery('#end-minute').val('0');
  scriptJquery('#end-ampm').val('AM');
})
</script>
