<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: oftheday.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<div class="global_form_popup sesbasic_add_itemoftheday_popup">
  <?php echo $this->form->render($this) ?>
</div>
<script type="text/javascript">
  scriptJquery(``).insertBefore(scriptJquery('#startdate-date').attr("type","text").attr("autocomplete","off").attr("placeholder","Select a Date").datepicker({
    
    timepicker: false,
   })
  );
  
  scriptJquery(``).insertBefore(scriptJquery('#enddate-date').attr("type","text").attr("autocomplete","off").attr("placeholder","Select a Date").datepicker({
      
      timepicker: false,
    })
  );

  scriptJquery('#startdate-hour').hide();
  scriptJquery('#startdate-minute').hide();
  scriptJquery('#startdate-ampm').hide();
  scriptJquery('#enddate-hour').hide();
  scriptJquery('#enddate-minute').hide();
  scriptJquery('#enddate-ampm').hide();
</script>
