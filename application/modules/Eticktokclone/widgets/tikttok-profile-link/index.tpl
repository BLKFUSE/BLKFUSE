<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eticktokclone/externals/styles/styles.css'); ?>
<?php $owner = Engine_Api::_()->getItem("eticktokclone_user", $this->subject->getIdentity()); ?>
<div class="eticktokclone_profile_link">
	<a href="<?php echo $owner->getHref() ?>" class="eticktokclone_follow_button"><i class="far fa-play-circle"></i><span><?php echo $this->translate("View TikTok Profile"); ?></span></a>
</div>
