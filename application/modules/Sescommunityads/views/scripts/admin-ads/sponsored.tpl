<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: sponsored.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>


<script type="text/javascript">
  
  
</script>
<div class="global_form_popup sesbasic_add_itemoftheday_popup">
  <?php echo $this->form->render($this) ?>
</div>
<script type="text/javascript">
  scriptJquery(``).insertBefore(scriptJquery('#enddate-date').attr("type","text").attr("autocomplete","off").attr("placeholder","Select a Date").datepicker({
      dateFormat: "dd/mm/yy",
      timepicker: true,
    })
  );
  scriptJquery('#enddate-hour').hide();
  scriptJquery('#enddate-minute').hide();
  scriptJquery('#enddate-ampm').hide();
</script>
