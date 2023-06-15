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
<div class="sescontest_congratulation_block sesbasic_clearfix sesbasic_bxs">
<?php $number = Engine_Api::_()->sescontest()->ordinal($this->rank);?>
<div class="_icon sescontest_award_icon sescontest_award_<?php echo $number;?>"><p><?php echo $number;?></p></div>
  <div class="_msg"><?php echo $this->message;?></div>
</div>
