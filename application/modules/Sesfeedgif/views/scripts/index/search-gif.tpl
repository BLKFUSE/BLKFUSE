<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesfeedgif
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: search-gif.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php if($this->paginator->getTotalItemCount() > 0) { ?>
<?php if(empty($this->searchvalue)) { ?>
<div class="ses_emoji_search_content sesbasic_custom_scroll">
  
    <ul class="sesfeedgif_search_results">
<?php } ?>
      <?php
      foreach($this->paginator as $gif) {
        if($gif->file_id == 0) continue; ?>
        <li rel="<?php echo $gif->image_id; ?>">
          <a href="javascript:;" class="_sesadvgif_gif">
            <img src="<?php echo Engine_Api::_()->storage()->get($gif->file_id, '')->getPhotoUrl(); ?>" alt="" />
          </a>
        </li>
      <?php 
      } ?>
<?php if(empty($this->searchvalue)) { ?>
    </ul>
  
</div>
<?php } ?>
<?php } ?>
<?php if($this->paginator->getTotalItemCount() == 0) { ?>
  <div class="ses_emoji_search_noresult">
    <i class="far fa-frown sesbasic_text_light" aria-hidden="true"></i>
    <span class="sesbasic_text_light"><?php echo $this->translate("No GIF image found.") ?></span>
  </div>
<?php } ?>
<script type="application/javascript">
  canPaginateExistingPhotos = "<?php echo ($this->paginator->count() == 0 ? '0' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? '0' : '1' ))  ?>";
  canPaginatePageNumber = "<?php echo $this->page + 1; ?>";
</script>
<?php die; ?>