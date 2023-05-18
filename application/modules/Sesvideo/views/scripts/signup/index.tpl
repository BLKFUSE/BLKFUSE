<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php echo $this->partial($this->script[0], $this->script[1], array(
  'form' => $this->form
)) ?>
<script type="text/javascript">
    
  scriptJquery(document).ready(function() {
    if( scriptJquery("#user_signup_form") ) scriptJquery("#user_signup_form").find(".form-errors").remove();
  });
  
  function skipForm() {
    document.getElementById("skip").value = "skipForm";
    scriptJquery('#SignupForm').trigger('submit');
  }
  function finishForm() {
    document.getElementById("nextStep").value = "finish";
  }

</script>

