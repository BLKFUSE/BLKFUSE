<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: dismiss_message.tpl 2020-11-03 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<h2><?php echo $this->translate("Short TikTak Videos Plugin for Mobile Apps") ?></h2>
<div class="sesbasic_nav_btns">
    <a href="<?php echo 'admin/tickvideo/settings/support' ?>"  class="help-btn">Help Center</a>
</div>

<?php if(is_countable($this->navigation) && engine_count($this->navigation) ): ?>
<div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render(); ?>
</div>
<?php endif; ?>
