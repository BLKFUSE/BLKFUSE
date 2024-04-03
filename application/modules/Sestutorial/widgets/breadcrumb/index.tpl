<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<div class="sestutorial_breadcrumb">
  <a href="<?php echo $this->url(array('action' => 'home'), "sestutorial_general"); ?>"><?php echo $this->translate("Tutorials Home"); ?></a>&nbsp;&raquo;
  <a href="<?php echo $this->url(array('action' => 'browse'), "sestutorial_general"); ?>"><?php echo $this->translate("Browse Tutorials"); ?></a>&nbsp;&raquo;
  <?php echo $this->subject->getTitle(); ?>
</div>
