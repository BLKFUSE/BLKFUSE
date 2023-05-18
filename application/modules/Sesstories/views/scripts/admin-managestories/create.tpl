<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: create.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<script type="text/javascript">
  var isEndDateRequired = '<?php echo $this->enableenddate; ?>';
  var MAX_UPLOAD_SIZE_NAME =  '<?php echo $this->upload_max_size ?>'; 
  var MAX_UPLOAD_SIZE_BYTES =  <?php echo $this->max_file_upload_in_bytes ?>; 
  
  function validFileSize(file) {
    var fileElement = document.getElementById("file");
    var size = fileElement.files[0].size;
    console.log(size , MAX_UPLOAD_SIZE_BYTES);
    if (size > MAX_UPLOAD_SIZE_BYTES)
    {
      fileElement.value = "";
      alert("File size must under "+MAX_UPLOAD_SIZE_NAME);
      return;
    }
  }

</script>
<div class='settings sesbasic_popup_form'>
  <?php echo $this->form->render($this); ?>
</div>
<style>
  .calendar_output_span{display:none}
  #date-hour, #date-minute, #date-ampm{display:none;}
  #starttime-hour, #starttime-minute, #starttime-ampm{display:none;}
  #endtime-hour, #endtime-minute, #endtime-ampm{display:none;}
</style>
<script type="text/javascript">
  
  function enableenddatse(value){
    if(value == 0){
      scriptJquery("#endtime-wrapper").hide();
    }else{
      scriptJquery("#endtime-wrapper").show();
    }
  }
  scriptJquery(document).ready(function(e){
    enableenddatse(scriptJquery('input[name="enableenddate"]:checked').val());
  })

  scriptJquery('#starttime-date').attr("type","text").attr("autocomplete","off").attr("placeholder","Select a Date").datepicker({
    dateFormat: "mm/dd/yy",
    timepicker: true,
    })
  scriptJquery('#endtime-date').attr("type","text").attr("autocomplete","off").attr("placeholder","Select a Date").datepicker({
      dateFormat: "mm/dd/yy",
      timepicker: true,
  })

  scriptJquery("#endtime-hour").val("1");
  scriptJquery("#endtime-minute").val("0"); 
  scriptJquery("#endtime-ampm").val("AM");
  scriptJquery("#starttime-hour").val("1");
  scriptJquery("#starttime-minute").val("0");
  scriptJquery("#starttime-ampm").val("AM");
    
</script>
