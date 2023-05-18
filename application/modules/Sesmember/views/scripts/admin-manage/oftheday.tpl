<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: oftheday.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<div class="global_form_popup sesbasic_add_itemoftheday_popup">
  <?php echo $this->form->render($this) ?>
</div>
<script type="text/javascript">
  scriptJquery(``).insertBefore(scriptJquery('#starttime-date').attr("type","text").attr("autocomplete","off").attr("placeholder","Select a Date").datepicker({
    dateFormat: "dd/mm/yy",
    timepicker: true,
   })
  );
  
  scriptJquery(``).insertBefore(scriptJquery('#endtime-date').attr("type","text").attr("autocomplete","off").attr("placeholder","Select a Date").datepicker({
      dateFormat: "dd/mm/yy",
      timepicker: true,
    })
  );

  scriptJquery('#starttime-hour').hide();
  scriptJquery('#starttime-minute').hide();
  scriptJquery('#starttime-ampm').hide();
  scriptJquery('#endtime-hour').hide();
  scriptJquery('#endtime-minute').hide();
  scriptJquery('#endtime-ampm').hide();
</script>
