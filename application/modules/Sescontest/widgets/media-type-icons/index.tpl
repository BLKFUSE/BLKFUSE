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

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>
<div class="sescontest_wel_media sesbasic_clearfic sesbasic_bxs">
  <div class="_container">
    <h3 class="sescontest_weltitle"><?php echo $this->translate($this->params['banner_title']);?></h3>
    <p class="sescontest_weldes"><?php echo $this->translate($this->params['description']);?></p>
    <div class="_cont">
      <?php if(isset($this->photoActive)):?>
        <div class="_colm">
          <article>
              <a href="<?php echo $this->url(array('action' => 'photo'),'sescontest_media',true);?>">    
              <div class="_img">
                <?php if(empty($this->params['photo_image'])):?>
                  <img src="application/modules/Sescontest/externals/images/mtyp-photo.png" />
                <?php else:?>
                  <img src="<?php echo $this->params['photo_image'];?>" />
                <?php endif;?>
              </div>
              <div class="_title"><?php echo $this->translate($this->params['photo_text']);?></div>
            </a>
          </article>
        </div>
      <?php endif;?>
      <?php if(isset($this->videoActive)):?>
        <div class="_colm">
          <article>
              <a href="<?php echo $this->url(array('action' => 'video'),'sescontest_media',true);?>">    
              <div class="_img">
                <?php if(empty($this->params['video_image'])):?>
                  <img src="application/modules/Sescontest/externals/images/mtyp-video.png" />
                <?php else:?>
                <img src="<?php echo $this->params['video_image'];?>" />
                <?php endif;?>
              </div>
              <div class="_title"><?php echo $this->translate($this->params['video_text']);?></div>
            </a>  
          </article>
        </div>
      <?php endif;?>
      <?php if(isset($this->musicActive)):?>
        <div class="_colm">
          <article>    
              <a href="<?php echo $this->url(array('action' => 'audio'),'sescontest_media',true);?>">
              <div class="_img">
                <?php if(empty($this->params['audio_image'])):?>
                  <img src="application/modules/Sescontest/externals/images/mtyp-music.png" />
                <?php else:?>
                  <img src="<?php echo $this->params['audio_image'];?>" />
                <?php endif;?>
              </div>
              <div class="_title"><?php echo $this->translate($this->params['audio_text']);?></div>
            </a>
          </article>
        </div>
      <?php endif;?>
      <?php if(isset($this->textActive)):?>
        <div class="_colm"> 
          <article>   
              <a href="<?php echo $this->url(array('action' => 'text'),'sescontest_media',true);?>">
              <div class="_img">
                <?php if(empty($this->params['text_image'])):?>
                  <img src="application/modules/Sescontest/externals/images/mtyp-writting.png" />
                <?php else:?>
                  <img src="<?php echo $this->params['text_image'];?>" />
                <?php endif;?>
              </div>
              <div class="_title"><?php echo $this->translate($this->params['blog_text']);?></div>
            </a>
          </article>
        </div>
      <?php endif;?>
    </div>
  </div>
</div>