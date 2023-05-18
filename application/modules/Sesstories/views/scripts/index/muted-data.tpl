<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: muted-data.tpl 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<li class="sesstories_stories_muted_item">
    <div class="_thumb">
        <img class="thumb_icon" src="<?php echo $muted['user_image'] ?>" alt="<?php echo $muted['user_title'] ?>" />
    </div>
    <div class="_info">
        <div class="_name"><?php echo $muted['user_title'] ?></div>
    </div>
    <div class="_btn">
        <button rel="<?php echo $muted['options']['mute_id']; ?>" class="sesstories_unmute"><?php echo $this->translate("SESUnmute"); ?></button>
    </div>
</li>