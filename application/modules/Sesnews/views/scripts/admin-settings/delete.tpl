<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: delete.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<form method="post" class="global_form_popup">
  <div>
    <h3><?php echo $this->translate("Delete News Category?") ?></h3>
    <p>
      <?php echo $this->translate("Are you sure that you want to delete this category? It will not be recoverable after being deleted.") ?>
    </p>
    <br />
    <p>
      <input type="hidden" name="confirm" value="<?php echo $this->news_id?>"/>
      <button type='submit'><?php echo $this->translate("Delete") ?></button>
      or <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>cancel</a>
    </p>
  </div>
</form>
