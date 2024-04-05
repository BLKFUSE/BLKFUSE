<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eticktokclone/externals/styles/styles.css'); ?>
<div class="eticktokclone_popular_tags">
  <ul>
    <?php foreach($this->hashtagMaps as $hashtagMaps){ ?>
    <li>
      <a href="<?php echo $this->url(array("tag"=>$hashtagMaps["tagmap_id"]),'eticktokclone_tagged',true) ?>"><span class="_tag">#<?php echo $hashtagMaps["text"] ?></span><span class="sesbasic_font_small sesbasic_text_light"><?php echo $hashtagMaps["tagmap_count"] ?> videos</span></a>
    </li>
    <?php } ?>
  </ul>
</div>
