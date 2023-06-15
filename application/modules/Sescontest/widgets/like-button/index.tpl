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
  <?php $likeStatus = Engine_Api::_()->sescontest()->getLikeStatus($this->subject->contest_id,$this->subject->getType()); ?>
  <?php $likeClass = (!$likeStatus) ? 'fa fa-thumbs-up' : 'fa fa-thumbs-down' ;?>
  <?php $likeText = ($likeStatus) ?  $this->translate('Unlike') : $this->translate('Like');?>
  <div class="sescontest_sidebar_button">
    <a href='javascript:;' data-type='like_contest_button_view' data-url='<?php echo $this->subject->getIdentity(); ?>' data-status='<?php echo $likeStatus;?>' class="sesbasic_animation sesbasic_link_btn sescontest_likefavfollow sescontest_like_view_<?php echo $this->subject->getIdentity(); ?> sescontest_like_contest_view"><i class='fa <?php echo $likeClass ; ?>'></i><span><?php echo $likeText; ?></span></a>     
  </div>
<?php endif; ?>
