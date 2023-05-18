<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: testemail.tpl  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<div class='global_form_popup'>
  <?php echo $this->form->render($this); ?>
</div>
<script type="application/javascript">
  scriptJquery(document).on('click', '#send_test_email',function(event){
    var email = document.getElementById('email').value;
    if(email){
      parent.scriptJquery('#testemailval').val(email);
      parent.scriptJquery('.global_form_box').trigger('submit');
      parent.Smoothbox.close;
      return false;
    }
  });
</script>
