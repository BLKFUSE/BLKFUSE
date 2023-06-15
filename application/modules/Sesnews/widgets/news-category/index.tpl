<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/scripts/core.js'); 
?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); ?> 
<div class="sesnews_category_grid sesbasic_clearfix sesbasic_bxs">
   <div class="row justify-content_center">
	  <?php foreach( $this->paginator as $item ):?>
			<div class="col-lg-<?php echo $this->gridblock; ?> col-md-3 col-sm-6 col-12">
				<div  <?php if(($this->show_criterias != '')){ ?> class="sesnews_thumb_contant" <?php } ?> style="height:<?php echo is_numeric($this->height) ? $this->height.'px' : $this->height?>;">
					<a href="<?php echo $item->getHref(); ?>" class="link_img img_animate">
					  <?php if($item->thumbnail != '' && !is_null($item->thumbnail) && intval($item->thumbnail)): ?>
							<img class="list_main_img" src="<?php echo  Engine_Api::_()->storage()->get($item->thumbnail)->getPhotoUrl('thumb.thumb'); ?>">
						<?php endif;?>
						<div <?php if(($this->show_criterias != '')){ ?> class="animate_contant" <?php } ?>>
            	<div>
                <?php if(isset($this->icon) && $item->cat_icon != '' && !is_null($item->cat_icon) && intval($item->cat_icon)): ?>
                  <img src="<?php echo  Engine_Api::_()->storage()->get($item->cat_icon)->getPhotoUrl('thumb.icon'); ?>" />
                <?php endif;?>
                <?php if(isset($this->title)):?>
                  <p class="title"><?php echo $this->translate($item->category_name); ?></p>
                <?php endif;?>
                <?php if($this->countNews):?>
                  <p class="count"><?php echo $this->translate(array('%s news', '%s news', $item->total_news_categories), $this->locale()->toNumber($item->total_news_categories))?></p>
                <?php endif;?>
              </div>
						</div>
					</a>
				</div>
			</div>
		<?php endforeach;?>
		<?php  if( engine_count($this->paginator) == 0):?>
			<div class="tip">
				<span>
					<?php echo $this->translate('No category found.');?>
				</span>
			</div>
		<?php endif; ?>
	</div>
</div>
