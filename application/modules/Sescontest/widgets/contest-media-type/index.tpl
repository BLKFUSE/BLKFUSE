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

<div class="sescontest_media_type sesbasic_bxs">
  <?php if($this->media_type == 3 || $this->media_type == 1):?>
  	 <div class="sescontest_media_type_icon">
      <?php if($this->contest->contest_type == 1):?>
      <a href="<?php echo $this->url(array('action' => 'text'),'sescontest_media',true);?>" class="sesbasic_linkinherit"><i class="far fa-file-alt"></i></a>
      <?php elseif($this->contest->contest_type == 2):?>
      <a href="<?php echo $this->url(array('action' => 'photo'),'sescontest_media',true);?>" class="sesbasic_linkinherit"><i class="far fa-image"></i></a>
      <?php elseif($this->contest->contest_type == 3):?>
      <a href="<?php echo $this->url(array('action' => 'video'),'sescontest_media',true);?>" class="sesbasic_linkinherit"><i class="fas fa-video"></i></a>
      <?php elseif($this->contest->contest_type == 4):?>
      <a href="<?php echo $this->url(array('action' => 'audio'),'sescontest_media',true);?>" class="sesbasic_linkinherit"><i class="fas fa-music"></i></a>
      <?php endif;?>
    </div>
  <?php endif;?>
  <?php if($this->media_type == 3 || $this->media_type == 2):?>
  	<div class="sescontest_media_type_content">
    	<?php if($this->contest->contest_type == 1):?>
        <p><a href="<?php echo $this->url(array('action' => 'text'),'sescontest_media',true);?>" class="sesbasic_linkinherit"><?php echo $this->translate('Text');?></a></p>
    	<?php elseif($this->contest->contest_type == 2):?>
        <p><a href="<?php echo $this->url(array('action' => 'photo'),'sescontest_media',true);?>" class="sesbasic_linkinherit"><?php echo $this->translate('Photo');?></a></p>
   		<?php elseif($this->contest->contest_type == 3):?>
        <p><a href="<?php echo $this->url(array('action' => 'video'),'sescontest_media',true);?>" class="sesbasic_linkinherit"><?php echo $this->translate('Video');?></a></p>
    	<?php elseif($this->contest->contest_type == 4):?>
        <p><a href="<?php echo $this->url(array('action' => 'audio'),'sescontest_media',true);?>" class="sesbasic_linkinherit"><?php echo $this->translate('Music');?></a></p>
    	<?php endif;?>
    </div>  
  <?php endif;?>
</div>
