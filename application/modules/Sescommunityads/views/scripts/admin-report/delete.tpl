<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: delete.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<div class="settings">
  <div class='global_form'>
    <form method="post">
      <div>
        <h3><?php echo $this->translate("Delete Report?") ?></h3>
        <p>
          <?php echo $this->translate("Are you sure that you want to delete this report? It will not be recoverable after being deleted.") ?>
        </p>
        <br />
        <p>
          <input type="hidden" name="confirm" value="<?php echo $id?>"/>
          <button type='submit'>Delete</button>
          <?php echo $this->translate("or") ?>
          <a href='javascript:;' onClick="javascript:parent.Smoothbox.close();">
          <?php echo $this->translate("cancel") ?></a>
        </p>
      </div>
    </form>
  </div>
</div>
