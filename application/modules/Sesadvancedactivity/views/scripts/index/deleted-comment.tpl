<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: deleted-comment.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php 
if(!empty($this->commentCount)){
  $commentcount =  $this->translate(array('%s comment', '%s comments',  $this->commentCount), $this->locale()->toNumber( $this->commentCount));
}
?>
<script type="text/javascript">
  parent.document.getElementById('comment-<?php echo $this->comment_id ?>').destroy();
  <?php if(!empty($commentcount)){ ?>
    parent.scriptJquery('.comment_stats_<?php echo $this->action->getIdentity(); ?>').find('.comment_btn_open').html('<?php echo $commentcount; ?>');
  <?php }else{ ?>
    parent.scriptJquery('.comment_stats_<?php echo $this->action->getIdentity(); ?>').remove();
  <?php } ?>
  setTimeout(function()
  {
    parent.Smoothbox.close();
  }, 1000 );
</script>

  <div class="global_form_popup_message">
    <?php echo $this->message ?>
  </div>