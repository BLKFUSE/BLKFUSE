<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php if( engine_count($this->quickNavigation) > 0 ): ?>
  <div class="quicklinks sesbasic_create_btn">
    <?php echo $this->navigation()->menu()->setContainer($this->quickNavigation)->render(); ?>
  </div>
<?php endif; ?>
