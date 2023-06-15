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
<div class="sescontest_mediatype_banner sesbasic_bxs <?php if($this->params['show_full_width'] == 'yes'){ ?>sescontest_mediatype_banner_full <?php } ?>" style="<?php if($this->params['show_full_width'] == 'yes'):?>margin-top:-<?php echo is_numeric($this->params['margin_top']) ? $this->params['margin_top'].'px' : $this->params['margin_top']?>;<?php endif;?>height:<?php echo is_numeric($this->params['cover_height']) ? $this->params['cover_height'].'px' : $this->params['cover_height'];?>;">
	<div class="sescontest_mediatype_banner_container">
  	<div class="sescontest_mediatype_banner_inner" style="height:<?php echo is_numeric($this->params['cover_height']) ? $this->params['cover_height'].'px' : $this->params['cover_height'];?>;">
      <?php if($this->banner):?>
        <?php $photo = Engine_Api::_()->storage()->get($this->banner);
        if($photo) {
        $photo = $photo->getPhotoUrl('thumb.normal'); ?>
        <div class="sescontest_mediatype_banner_img" style="background-image:url(<?php echo $photo;?>);height:<?php echo is_numeric($this->params['cover_height']) ? $this->params['cover_height'].'px' : $this->params['cover_height'];?>;"></div>
      <?php } endif;?>
      <div class="sescontest_mediatype_banner_cont">
      	<div class="_info">
      	  <?php if($this->params['show_icon']):?>
            <?php if($this->type == 'text'):?>
                <i class="far fa-file-alt"></i>
            <?php elseif($this->type == 'photo'):?>
                <i class="far fa-image"></i>
            <?php elseif($this->type == 'video'):?>
                <i class="fas fa-video"></i>
            <?php else:?>
                <i class="fas fa-music"></i>
            <?php endif;?>
          <?php endif;?>
          <h2><?php echo $this->translate($this->params['banner_title']);?></h2>
          <p><?php echo $this->translate($this->params['description']);?></p>
        </div>	
      </div>
    </div>
  </div>
</div>