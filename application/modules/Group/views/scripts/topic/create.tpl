<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: create.tpl 9747 2012-07-26 02:08:08Z john $
 * @author	   John
 */
?>
<div class="layout_middle">
  <div class="generic_layout_container">
    <h2>
      <?php echo $this->group->__toString() ?>
      <?php echo $this->translate('&#187; Discussions');?>
    </h2>
    
    <?php echo $this->form->render($this) ?>
  </div>
</div>
