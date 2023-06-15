<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: edit-slide.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php include APPLICATION_PATH .  '/application/modules/Sesevent/views/scripts/dismiss_message.tpl';?>

 <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesevent', 'controller' => 'manage', 'action' => 'slides'), $this->translate('Back to Manage Slides'), array('class' => 'buttonlink sesbasic_icon_back')) ?><br />

<div class='clear'>
  <div class='settings global_form_popup'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
