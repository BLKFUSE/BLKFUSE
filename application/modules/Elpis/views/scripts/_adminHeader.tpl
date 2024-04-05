<?php

/**
* SocialEngine
*
* @category   Application_Extensions
* @package    Core
* @copyright  Copyright 2006-2020 Webligo Developments
* @license    http://www.socialengine.com/license/
* @version    $Id: _admin_header.tpl 9785 2012-09-25 08:34:18Z $
*/

?>
<?php echo $this->partial('_admin_breadcrumb.tpl', 'core', array('parentMenuItemName' => 'core_admin_main_elpis')); ?>

<h2 class="page_heading"><?php echo $this->translate('Elpis Theme') ?></h2>

<?php if( engine_count($this->navigation) ): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render(); ?>
  </div>
<?php endif; ?>
