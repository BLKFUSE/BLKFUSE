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
<?php if($this->params['viewType'] ==  'icon'):?>
<div class="sescontest_cat_iconlist_head"><?php echo $this->translate($this->params['titleC']); ?></div>
<div class="row sescontest_cat_iconlist_container sesbasic_clearfix clear sesbasic_bxs justify-content-center">
  <?php foreach( $this->paginator as $item ): ?>
  <div class="col-lg-<?php echo $this->gridblock; ?> col-md-3 col-sm-6 col-12">
  	<div class="sescontest_cat_iconlist <?php if($this->params['shapeType'] == 'circle'){?>_isround<?php } ?>" style="height:<?php echo is_numeric($this->params['height']) ? $this->params['height'].'px' : $this->params['height'] ?>;">
    	<a href="<?php echo $item->getHref(); ?>">        
        <span class="sescontest_cat_iconlist_icon" style="background-color:<?php if($this->params['show_bg_color'] == '2'){?><?php echo $item->color ? '#'.$item->color : '#999'; ?><?php } ?><?php if($this->params['show_bg_color'] == '1'){ ?><?php echo $this->params['bgColor']; ?><?php }?>;">
        <?php if($item->colored_icon != '' && !is_null($item->colored_icon) && intval($item->colored_icon)){ ?>
    			<img src="<?php echo  Engine_Api::_()->storage()->get($item->colored_icon)->getPhotoUrl(); ?>" />
    		<?php }else{ ?>
          <img src="application/modules/Sescontest/externals/images/contest-icon-big.png" />
    		<?php } ?>
    	</span>
      <?php if(isset($this->params['title'])){ ?>
        <span class="sescontest_cat_iconlist_title"><?php echo $this->translate($item->category_name); ?></span>
      <?php } ?>
      <?php if(isset($this->params['countContests'])){ ?>
        <span class="sescontest_cat_iconlist_count"><?php echo $this->translate(array('%s contest', '%s contests', $item->total_contest_categories), $this->locale()->toNumber($item->total_contest_categories))?></span>
      <?php } ?>
    </a>
  	</div>
   </div>
  <?php endforeach; ?>
  <?php  if(is_countable($this->paginator) &&  engine_count($this->paginator) == 0){  ?>
  	<div class="tip"> <span> <?php echo $this->translate('No categories found.');?> </span> </div>
  <?php } ?>
</div>
<?php else:?>
  <div class="sescontest_cat_iconlist_head sesbm"><?php echo $this->params['titleC']; ?></div>
<div class="row sescontest_category_grid_listing sesbasic_clearfix clear sesbasic_bxs justify-content-center">
  <?php foreach( $this->paginator as $item ): ?>
  <div class="col-lg-<?php echo $this->gridblock; ?> col-md-3 col-sm-6 col-12">
  <div class="sescontest_category_grid sesbm <?php if($this->params['shapeType'] == 'circle'){?>_isround<?php } ?>" style="height:<?php echo is_numeric($this->params['height']) ? $this->params['height'].'px' : $this->params['height'] ?>;"> <a href="<?php echo $item->getHref(); ?>">
    <div class="sescontest_category_grid_img">
      <?php if($item->thumbnail != '' && !is_null($item->thumbnail) && intval($item->thumbnail)){ ?>
      <span class="sescontest_animation" style="background-image:url(<?php echo  Engine_Api::_()->storage()->get($item->thumbnail)->getPhotoUrl('thumb.thumb'); ?>);"></span>
      <?php } ?>
    </div>
    <div class="sescontest_category_grid_overlay sescontest_animation"></div>
    <div class="sescontest_category_grid_info">
      <div>
        <div class="sescontest_category_grid_details sesbasic_animation">
          <?php if(isset($this->params['title'])){ ?>
          	<span><?php echo $this->translate($item->category_name); ?></span>
          <?php } ?>
          <?php if(isset($this->params['countContests'])){ ?>
          	<span class="sescontest_category_grid_stats"><?php echo $this->translate(array('%s contest', '%s contests', $item->total_contest_categories), $this->locale()->toNumber($item->total_contest_categories))?></span>
          <?php } ?>
        </div>
      </div>
    </div>
    </a>
  </div>
  </div>
  <?php endforeach; ?>
  <?php  if(is_countable($this->paginator) &&  engine_count($this->paginator) == 0){  ?>
  	<div class="tip"> <span> <?php echo $this->translate('No categories found.');?> </span> </div>
  <?php } ?>
</div>
<?php endif;?>
