<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Everification
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: deletedocument.tpl 2019-06-05 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<form method="post" class="global_form_popup">
  <div>
    <h3><?php echo $this->translate("Delete Document Only?") ?></h3>
    <p>
      <?php echo $this->translate("Are you sure that you want to delete this document? It will not be recoverable after being deleted.") ?>
    </p>
    <br />
    <p>
      <input type="hidden" name="confirm" />
      <button type='submit'><?php echo $this->translate("Delete") ?></button>
      or <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>cancel</a>
    </p>
  </div>
</form>
