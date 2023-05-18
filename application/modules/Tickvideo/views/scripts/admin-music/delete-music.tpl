<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: delete-music.tpl 2020-11-03 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<form method="post" class="global_form_popup" action="<?php echo $this->url(array()) ?>">
  <div>
    <h3><?php echo $this->translate("Delete This Music?") ?></h3>
    <p>
      <?php echo $this->translate("Are you sure that you want to delete this %s? It will not be recoverable after being deleted.", 'Music') ?>
    </p>
    <br />
    <p>
      <input type="hidden" name="id" value="<?php echo $this->item_id?>"/>
      <button type='submit'><?php echo $this->translate("Delete") ?></button>
      <?php echo $this->translate("or") ?>
			<a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>
				<?php echo $this->translate("cancel") ?>
			</a>
    </p>
  </div>
</form>
