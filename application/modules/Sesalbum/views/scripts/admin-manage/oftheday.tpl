<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: oftheday.tpl 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<div class="global_form_popup sesbasic_add_itemoftheday_popup">
  <?php echo $this->form->render($this) ?>
</div>
<script type="text/javascript">
  scriptJquery(``).insertBefore(scriptJquery('#starttime-date').attr("type","text").attr("autocomplete","off").attr("placeholder","Select a Date").datepicker({
    
    timepicker: false,
   })
  );
  
  scriptJquery(``).insertBefore(scriptJquery('#endtime-date').attr("type","text").attr("autocomplete","off").attr("placeholder","Select a Date").datepicker({
      
      timepicker: false,
    })
  );

  scriptJquery('#starttime-hour').hide();
  scriptJquery('#starttime-minute').hide();
  scriptJquery('#starttime-ampm').hide();
  scriptJquery('#endtime-hour').hide();
  scriptJquery('#endtime-minute').hide();
  scriptJquery('#endtime-ampm').hide();
</script>

