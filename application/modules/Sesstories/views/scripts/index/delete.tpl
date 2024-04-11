<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: delete.tpl 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesstories/externals/scripts/core.js'); ?>
<div class="global_form_popup">
	<?php echo $this->form->render($this) ?>
</div>
<script type="application/javascript">
    <?php if($this->status){ ?>
        parent.storyDeleted();
        parent.Smoothbox.close();
    <?php } ?>
function removePopup() {

    parent.seshoverStopPlay(false);
    parent.Smoothbox.close();
}
</script>
