<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: archive-data.tpl 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<li class="sesstories_archive_stories_item">
    <article>
        <?php
            $image =  "";
            if($archived['is_video']) {
              if($archived['photo']){
                $image = $archived['photo'] ;
              }else{
                $image = $story['user_image'];
              }

            }else{
              $image = $archived['media_url'];
            }
         ?>
        <div class="sesstories_archive_stories_item_img">
            <img src="<?php echo $image ?>" alt="" />
        </div>
        <div class="sesstories_archive_stories_item_cont">
            <div class="_time"><?php echo $this->timestamp(strtotime($archived['creation_date'])) ?></div>
            <div class="_caption"><?php echo $archived['comment'] ?> </div>
            <div class="_highlight_btn"><a href="javascript:;" rel="<?php echo $archived['story_id'] ?>" class="sestrories_highlight <?php echo $archived['highlight']  ? '_active' : ''; ?>"></a></div>
        </div>
    </article>
</li>
