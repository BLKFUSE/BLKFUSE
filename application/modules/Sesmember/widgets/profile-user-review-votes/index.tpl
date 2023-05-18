<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<?php $getUserInfoItem = Engine_Api::_()->sesmember()->getUserInfoItem($this->subject->user_id); ?>
<div class="sesbasic_sidebar_block sesmember_review-vots">
<p><b>Review Votes</b></p>
	<ul>
  	<li><i class="useful"></i><?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.first','Useful'); ?> <?php echo $getUserInfoItem->useful_count; ?></li>
    <li><i class="funny"></i> <?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.second','Funny'); ?> <?php echo $getUserInfoItem->funny_count; ?></li>
    <li><i class="cool"></i> <?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.third','Cool'); ?> <?php echo $getUserInfoItem->cool_count; ?></li>
  </ul>
</div>
