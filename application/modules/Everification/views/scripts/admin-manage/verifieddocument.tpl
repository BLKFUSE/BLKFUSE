<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Everification
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: verifieddocument.tpl 2019-06-05 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<form method="post" class="global_form_popup">
  <div>
    <?php if($this->user_id && $this->enable) { ?>
      <h3><?php echo $this->translate("Document Verify & Enable User?") ?></h3>
      <p>
        <?php echo $this->translate("Are you sure that you want to verify document & enable of this user?") ?>
      </p>
      <br />
      <p>
        <input type="hidden" name="confirm"/>
        <button type='submit'><?php echo $this->translate("Verify & Enable") ?></button>
        or <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>cancel</a>
      </p>
    <?php } else { ?>
      <h3><?php echo $this->translate("Verify Document?") ?></h3>
      <p>
        <?php echo $this->translate("Are you sure you want to verify this document?") ?>
      </p>
      <br />
      <p>
        <input type="hidden" name="confirm"/>
        <button type='submit'><?php echo $this->translate("Verify") ?></button>
        or <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>cancel</a>
      </p>
    <?php } ?>
  </div>
</form>
