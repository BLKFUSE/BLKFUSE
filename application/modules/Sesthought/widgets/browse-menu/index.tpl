<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<div class="headline sesthought_browse_menu">
     <div class="tabs">
  <h2>
    <?php echo $this->translate('Thoughts');?>
  </h2>
  <?php if(is_countable($this->navigation) && engine_count($this->navigation) > 0 ): ?>
      <?php
        // Render the menu
        echo $this->navigation()
          ->menu()
          ->setContainer($this->navigation)
          ->render();
      ?>
    </div>
	<?php if($this->createButton && $this->createPrivacy) { ?>
	  <div class="sesthought_create_right_btn"><a class="sessmoothbox menu_sesthought_main sesthought_main_create" href="<?php echo $this->url(array('action' => 'create'), 'sesthought_general', true); ?>"><?php echo $this->translate("Write New Thought"); ?></a></div>
	<?php } ?>
  <?php endif; ?>
</div>
