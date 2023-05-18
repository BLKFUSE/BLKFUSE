<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Elivestreaming
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2019-10-01 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>

<?php include APPLICATION_PATH .  '/application/modules/Elivestreaming/views/scripts/dismiss_message.tpl';?>
<div class="settings sesbasic_admin_form sesact_global_setting">
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>

<script type="text/javascript">
  en4.core.runonce.add(function() {
    var firstCheck = "<?php echo $this->isCheck; ?>";
    if(firstCheck=="0")
      scriptJquery("#elivestreaming_storieslivedefaultimage-wrapper").hide();
      scriptJquery("input[name$='elivestreaming_showliveimage']").click(function() {
      isCheck = scriptJquery(this).val();
      if(isCheck==0){
        scriptJquery("#elivestreaming_storieslivedefaultimage-wrapper").hide();
      }else if(isCheck==1){
        scriptJquery("#elivestreaming_storieslivedefaultimage-wrapper").show();
      }
    });
  });
</script>
