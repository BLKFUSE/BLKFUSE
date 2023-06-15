<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _advgridView.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<li class="sesnews_last_grid_block sesbasic_bxs <?php if((isset($this->my_news) && $this->my_news)){ ?>isoptions<?php } ?>" style="width:<?php echo is_numeric($this->width_advgrid) ? $this->width_advgrid.'px' : $this->width_advgrid ?>;">
		<div class="sesnews_grid_inner">
			<div class="sesnews_gird-top_news">
			  <?php if(isset($this->categoryActive)){ ?>
			    <?php if($item->category_id != '' && intval($item->category_id) && !is_null($item->category_id)):?> 
			      <?php $categoryItem = Engine_Api::_()->getItem('sesnews_category', $item->category_id);?>
					  <?php if($categoryItem):?>
							<div class="sesnews_grid_info_title floatL"><a href="<?php echo $categoryItem->getHref(); ?>"><?php echo $categoryItem->category_name; ?></a></div>
					  <?php endif;?>
					<?php endif;?>
				<?php } ?>
				<?php if(isset($this->creationDateActive)){ ?>
					<div class="sesnews_grid_date_news floatR">
												<?php if($item->publish_date): ?>
              <?php echo date('M d, Y',strtotime($item->creation_date));?>
						<?php else: ?>
              <?php echo date('M d, Y',strtotime($item->creation_date));?>
						<?php endif; ?>
					</div>
				<?php } ?>
			</div>
		<div class="sesnews_grid_thumb sesnews_thumb" style="height:<?php echo is_numeric($this->height_advgrid) ? $this->height_advgrid.'px' : $this->height_advgrid ?>;">
			<?php $href = $item->getHref();$imageURL = $photoPath;?>
			<a href="<?php echo $href; ?>" data-url = "<?php echo $item->getType() ?>" class="sesnews_thumb_img">
				<span style="background-image:url(<?php echo $imageURL; ?>);"></span>
			</a>
			<?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->verifiedLabel)):?>
				<div class="sesnews_grid_labels">
					<?php if(isset($this->featuredLabelActive) && $item->featured == 1):?>
						<p class="sesnews_label_featured"><?php echo $this->translate('FEATURED');?></p>
					<?php endif;?>
					<?php if(isset($this->sponsoredLabelActive) && $item->sponsored == 1):?>
						<p class="sesnews_label_sponsored"><?php echo $this->translate('SPONSORED');?></p>
					<?php endif;?>
					
				</div>
			<?php endif;?>
			<?php if(isset($this->verifiedLabelActive) && $item->verified == 1):?>
						<div class="sesnews_grid_verified_label" title="<?php echo $this->translate('VERIFIED');?>"><i class="fa fa-check"></i></div>
					<?php endif;?>
      <?php if((isset($this->socialSharingActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive)):?>
				<?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
				<div class="sesnews_grid_thumb_over"> 
					<a href="<?php echo $href; ?>" data-url = "<?php echo $item->getType() ?>"></a>
					<div class="sesnews_list_grid_thumb_btns">
						<?php if(isset($this->socialSharingActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)):?>
              
              <?php if($this->socialshare_icon_limit): ?>
                <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
              <?php else: ?>
                <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_gridview2plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_gridview2limit)); ?>
              <?php endif; ?>
              
							
						<?php endif;?>
						<?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 ):?>
							<?php $canComment =  $item->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');?>
							<?php if(isset($this->likeButtonActive) && $canComment):?>
								<!--Like Button-->
								<?php $LikeStatus = Engine_Api::_()->sesnews()->getLikeStatus($item->news_id,$item->getType()); ?>
								<a href="javascript:;" data-url="<?php echo $item->news_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesnews_like_sesnews_news_<?php echo $item->news_id ?> sesnews_like_sesnews_news <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count; ?></span></a>
							<?php endif;?>
							<?php if(isset($this->favouriteButtonActive) && isset($item->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)): ?>
								<?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesnews')->isFavourite(array('resource_type'=>'sesnews_news','resource_id'=>$item->news_id)); ?>
								<a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesnews_favourite_sesnews_news_<?php echo $item->news_id ?> sesnews_favourite_sesnews_news <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $item->news_id ; ?>"><i class="fa fa-heart"></i><span><?php echo $item->favourite_count; ?></span></a>
							<?php endif;?>
						<?php endif;?>
					</div>
				</div>
			<?php endif;?> 
			<div class="sesnews_grid_memta_title">
				<?php $categoryItem = Engine_Api::_()->getItem('sesnews_category', $item->category_id);?>
				<?php if($categoryItem):?>
					<span>
						<a href="<?php echo $categoryItem->getHref(); ?>"><?php echo $categoryItem->category_name; ?></a>
					</span>
				<?php endif;?>
			</div>
		</div>
		<div class="sesnews_grid_info clear clearfix sesbm">
			<div class="sesnews_grid_meta_block">
				<?php if(isset($this->byActive)){ ?>
					<div class="sesnews_list_stats sesbasic_text_dark">
						<span>
						<?php $owner = $item->getOwner(); ?>
						<?php echo $this->htmlLink($item->getOwner()->getParent(), $this->itemPhoto($item->getOwner()->getParent(), 'thumb.icon')); ?>
						<?php echo $this->translate("by") ?> <?php echo $this->htmlLink($owner->getHref(),$owner->getTitle() ) ?>
						</span>
					</div>
				<?php } ?>
				<?php if(isset($this->locationActive) && isset($item->location) && $item->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.location', 1)){ ?>
					<div class="sesnews_list_stats sesnews_list_location sesbasic_text_dark">
						<span>
							<i class="fa fa-map-marker"></i>
							<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
                <a href="<?php echo $this->url(array('resource_id' => $item->news_id,'resource_type'=>'sesnews_news','action'=>'get-direction'), 'sesbasic_get_direction', true) ;?>" class="opensmoothboxurl" title="<?php echo $item->location;?>"><?php echo $item->location;?></a>
							<?php } else { ?>
                <?php echo $item->location;?>
							<?php } ?>
						</span>
					</div>

				<?php } ?>
			</div>
			<?php if(Engine_Api::_()->getApi('core', 'sesnews')->allowReviewRating() && isset($this->ratingStarActive)):?>
					<?php echo $this->partial('_newsRating.tpl', 'sesnews', array('rating' => $item->rating, 'class' => 'sesnews_list_rating sesnews_list_view_ratting floatR', 'style' => 'margin:0px;'));?>
				<?php endif;?>
      <?php if(isset($this->titleActive)): ?>
				<div class="sesnews_grid_three_info_title">
					<?php if(strlen($item->getTitle()) > $this->title_truncation_advgrid):?>
						<?php $title = mb_substr($item->getTitle(),0,$this->title_truncation_advgrid).'...';?>
						<?php echo $this->htmlLink($item->getHref(),$title,array('title'=>$item->getTitle()) ) ?>
					<?php else: ?>
						<?php echo $this->htmlLink($item->getHref(),$item->getTitle(),array('title'=>$item->getTitle())  ) ?>
					<?php endif; ?>
					<span></span>
				</div>
			<?php endif;?>
      				
			<?php  if(isset($this->descriptionadvgridActive)){?>
				<div class="sesnews_grid_des clear">
                    <?php echo $item->getDescription($this->description_truncation_advgrid);?>
					<span></span>
				</div>
			<?php } ?>
			<div class="sesnews_comment_list sesnews_list_stats sesbasic_text_dark floatL">
				<?php if(isset($this->likeActive) && isset($item->like_count)) { ?>
					<span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $item->like_count; ?></span>
				<?php } ?>
				<?php if(isset($this->commentActive) && isset($item->comment_count)) { ?>
					<span title="<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $item->comment_count;?></span>
				<?php } ?>
				<?php if(isset($this->favouriteActive) && isset($item->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)) { ?>
					<span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $item->favourite_count;?></span>
				<?php } ?>
				<?php if(isset($this->viewActive) && isset($item->view_count)) { ?>
					<span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $item->view_count; ?></span>
				<?php } ?>
				<?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/_newsRatingStat.tpl';?>
			</div>
			<?php if(isset($this->readmoreActive)):?>
				<div class="sesnews_second_readmore_link floatR">
					<a href="<?php echo $item->getHref();?>"><?php echo $this->translate('Read More...');?></a>
				</div>
      <?php endif;?>
		</div>
	</div>
</li>
