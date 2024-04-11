<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbasic
 * @package    Sesbasic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: color-chooser.tpl 2015-07-25 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesbasic/views/scripts/dismiss_message.tpl';?>
<?php  
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/jscolor/jscolor.js');
?>
<h2 class="page_heading"><?php echo $this->translate('SocialNetworking.Solutions (SNS) Basic Required Plugin'); ?></h2>
<?php if (engine_count($this->navigation)): ?>
  <div class='tabs'>
    <?php
    // Render the menu
    echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>

<div class='clear'>
  <div class='settings sesbasic_admin_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<script type="text/javascript">

  hashSign = '#';
</script>
