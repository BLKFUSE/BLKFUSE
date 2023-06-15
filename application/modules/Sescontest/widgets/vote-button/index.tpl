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

<?php $entry = $this->subject;?>
<div class="sescontest_sidebar_button">
  <?php $showTextAlreadyVoted = true; ?>
  <?php if(strtotime(date('Y-m-d H:i:s')) > strtotime(Engine_Api::_()->getItem('contest',$entry->contest_id)->endtime)):?>
    <?php echo $this->translate('Voting Ended');?>
  <?php else:?>
    <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_voteData.tpl';?>
  <?php endif;?>
</div>
