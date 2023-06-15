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

<?php if (!empty($this->viewer_id)): ?>
  <?php $followStatus = Engine_Api::_()->getDbTable('followers', 'sescontest')->isFollow(array('resource_id' => $this->subject->contest_id,'resource_type' => $this->subject->getType())); ?>
  <?php $followClass = (!$followStatus) ? 'fa-check' : 'fa-times' ;?>
  <?php $followText = ($followStatus) ?  $this->translate('Unfollow') : $this->translate('Follow');?>
  <div class="sescontest_sidebar_button">
    <a href='javascript:;' data-type='follow_contest_button_view' data-url='<?php echo $this->subject->getIdentity(); ?>'  data-status='<?php echo $followStatus;?>' class="sesbasic_animation sesbasic_link_btn sescontest_likefavfollow sescontest_follow_view_<?php echo $this->subject->getIdentity(); ?> sescontest_follow_contest_view"><i class='fa <?php echo $followClass ; ?>'></i><span><?php echo $followText; ?></span></a>     
  </div>
<?php endif; ?>
  

