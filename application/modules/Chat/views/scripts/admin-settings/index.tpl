<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Chat
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>
<?php include APPLICATION_PATH .  '/application/modules/Chat/views/scripts/_adminHeader.tpl';?>
<div class='clear'>
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
