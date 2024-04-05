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

<div class="eticktokclone_member_profile">
  <div class="eticktokclone_member_profile_info">
    <div class="_img">
      <div class="_tag"><i class="sesbasic_text_light fas fa-hashtag"></i></div>
    </div>
    <div class="_cont">
      <h1>#<?php echo $this->tag->text; ?></h1> 
      <p class="sesbasic_text_light"><?php echo $this->paginator->getTotalItemCount(); ?> videos</p> 
    </div>
  </div>
</div>
