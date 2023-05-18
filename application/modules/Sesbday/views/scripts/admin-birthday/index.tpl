<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php include APPLICATION_PATH .  '/application/modules/Sesbday/views/scripts/dismiss_message.tpl';?>
<div class='clear'>
  <div class='settings sesbday_admin_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<script type="application/javascript">

  scriptJquery('.global_form_box').submit(function(){
    parent.Smoothbox.close;
    scriptJquery('#submit').click();
  });

  scriptJquery(document).ready(function() {
    var valueEnable = document.getElementById('sesbday_birthday_enable').value;
    enableContent(valueEnable);
  });

  function enableContent(value){
    if(value == 1){
      document.getElementById('sesbday_birthday_subject-wrapper').style.display = 'block';
      document.getElementById('sesbday_birthday_content-wrapper').style.display = 'block';
    }else{
      document.getElementById('sesbday_birthday_subject-wrapper').style.display = 'none';	
      document.getElementById('sesbday_birthday_content-wrapper').style.display = 'none';
    }
  }

  scriptJquery(document).on('click','#testemail',function(event){
    Smoothbox.open(en4.core.baseUrl+'admin/sesbday/birthday/testemail/');
    parent.Smoothbox.close;
    return false;
  });
</script>
