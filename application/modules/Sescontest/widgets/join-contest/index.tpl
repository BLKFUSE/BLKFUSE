<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>
<div class="sescontest_sidebar_button">
  <a href="<?php echo $this->url(array('action' => 'create', 'contest_id' => $this->contest->contest_id),'sescontest_join_contest','true');?>" class="contest_join_btn">
  	<i class="fa fa-sign-in-alt"></i>
  	<span><?php echo $this->translate("Join the Contest");?></span>
 	</a>
</div>