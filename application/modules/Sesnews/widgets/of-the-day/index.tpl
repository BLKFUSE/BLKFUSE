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
<?php if($this->type == 'grid1'):?>
	<div class="sesnews_news_of_the_day">
		<ul class="sesnews_album_listing sesbasic_bxs">
			<?php $limit = 0;?>
			<?php $itemNews = Engine_Api::_()->getItem('sesnews_news',$this->news_id);?>
			<?php if($itemNews):?>
				<li class="sesnews_grid sesnews_list_grid_thumb sesnews_list_grid sesa-i-<?php echo (isset($this->insideOutside) && $this->insideOutside == 'outside') ? 'outside' : 'inside'; ?> sesa-i-<?php echo (isset($this->fixHover) && $this->fixHover == 'fix') ? 'fix' : 'over'; ?> sesbm" style="width:<?php echo is_numeric($this->width) ? $this->width.'px' : $this->width ?>;">
					<div class="sesnews_grid_inner sesnews_thumb">
						<div class="sesnews_grid_thumb sesnews_thumb" style="height:<?php echo is_numeric($this->height) ? $this->height.'px' : $this->height ?>;"> <a class="sesnews_thumb_img" href="<?php echo $itemNews->getHref(); ?>">
							<img src="<?php echo $itemNews->getPhotoUrl('thumb.normal'); ?>" alt="" />
						</a>
						<?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->verifiedLabel)):?>
          <div class="sesnews_grid_labels">
            <?php if(isset($this->featuredLabelActive) && $itemNews->featured == 1):?>
              <p class="sesnews_label_featured" title="<?php echo $this->translate('FEATURED');?>"><i class="fa fa-star"></i></p>
            <?php endif;?>
            <?php if(isset($this->sponsoredLabelActive) && $itemNews->sponsored == 1):?>
              <p class="sesnews_label_sponsored" title="<?php echo $this->translate('SPONSORED');?>"><i class="fa fa-star"></i></p>
            <?php endif;?>
             <?php if(isset($this->hotLabelActive) && $itemNews->hot == 1) { ?>
            <p class="sesnews_label_hot" title="<?php echo $this->translate('Hot'); ?>"><i class="fa fa-star"></i></p>
          <?php } ?>
          <?php if(isset($this->newLabelActive) && $itemNews->latest == 1) { ?>
            <p class="sesnews_label_new" title="<?php echo $this->translate('New'); ?>"><i class="fa fa-star"></i></p>
          <?php } ?>
            <?php if(isset($this->verifiedLabelActive) && $itemNews->verified == 1):?>
              <div class="sesnews_grid_verified_label" title="<?php echo $this->translate('VERIFIED');?>"><i class="fa fa-check"></i></div>
            <?php endif;?>
          </div>
        <?php endif;?>
						</div>
						<?php if(isset($this->likeActive) || isset($this->commentActive) || isset($this->viewActive) || isset($this->titleActive) || isset($this->favouriteActive) || isset($this->byActive)){ ?>
							<div class="sesnews_grid_info clear sesbasic_clearfix sesbm">
								<?php if(isset($this->titleActive)) { ?>
									<div class="sesnews_grid_info_title"> <?php echo $this->htmlLink($itemNews, $this->string()->truncate($itemNews->getTitle(), $this->title_truncation),array('title'=>$itemNews->getTitle())) ; ?> </div>
								<?php } ?>
								<div class="sesnews_list_grid_info sesbasic_clearfix">
									<div class="sesnews_list_stats">
										<?php if(isset($this->byActive)) { ?>
											<span class="sesnews_list_grid_owner"> <a href="<?php echo $itemNews->getOwner()->getHref();?>"><?php echo $this->itemPhoto($itemNews->getOwner(), 'thumb.icon');?></a> <?php echo $this->translate('By');?> <?php echo $this->htmlLink($itemNews->getOwner()->getHref(), $itemNews->getOwner()->getTitle(), array('class' => 'thumbs_author')) ?> </span>
										<?php }?>
									</div>
									<div class="sesnews_list_stats sesnews_list_location sesbasic_text_light"> <span> <i class="fa fa-map-marker"></i><?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?><a href="<?php echo $this->url(array('resource_id' => $itemNews->news_id,'resource_type'=>'sesnews_news','action'=>'get-direction'), 'sesbasic_get_direction', true) ;?>" class="opensmoothboxurl"><?php echo $itemNews->location;?></a><?php } else { ?><?php echo $itemNews->location;?><?php } ?></span> </div>
								</div>
							</div>
						<?php } ?>
						<div class="sesnews_grid_hover_block">
							<div class="sesnews_grid_info_hover_title"> 
								<?php echo $this->htmlLink($itemNews, $this->string()->truncate($itemNews->getTitle(), $this->title_truncation),array('title'=>$itemNews->getTitle())) ; ?> <span></span> 
							</div>
							<div class="sesnews_grid_des clear"><?php echo $itemNews->getDescription($this->description_truncation);?></div>
							<div class="sesnews_grid_hover_block_footer">
								<div class="sesnews_list_stats sesbasic_text_light">
									<?php if(isset($this->likeActive)) { ?>
										<span class="sesnews_list_grid_likes" title="<?php echo $this->translate(array('%s like', '%s likes', $itemNews->like_count), $this->locale()->toNumber($itemNews->like_count))?>"> <i class="sesbasic_icon_like_o"></i> <?php echo $itemNews->like_count;?> </span>
									<?php } ?>
									<?php if(isset($this->commentActive)) { ?>
										<span class="sesnews_list_grid_comment" title="<?php echo $this->translate(array('%s comment', '%s comments', $itemNews->comment_count), $this->locale()->toNumber($itemNews->comment_count))?>"> <i class="sesbasic_icon_comment_o"></i> <?php echo $itemNews->comment_count;?> </span>
									<?php } ?>
									<?php if(isset($this->viewActive)) { ?>
										<span class="sesnews_list_grid_views" title="<?php echo $this->translate(array('%s view', '%s views', $itemNews->view_count), $this->locale()->toNumber($itemNews->view_count))?>"> <i class="sesbasic_icon_view"></i> <?php echo $itemNews->view_count;?> </span>
									<?php } ?>
									<?php if(isset($this->favouriteActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)) { ?>
										<span class="sesnews_list_grid_fav" title="<?php echo $this->translate(array('%s favourite', '%s favourites', $itemNews->favourite_count), $this->locale()->toNumber($itemNews->favourite_count))?>"> <i class="sesbasic_icon_favourite_o"></i> <?php echo $itemNews->favourite_count;?> </span>
									<?php } ?>
									<?php if(Engine_Api::_()->sesbasic()->getViewerPrivacy('sesnews_review', 'view') && isset($this->ratingActive) && isset($itemNews->rating)): ?>
										<span title="<?php echo $this->translate(array('%s rating', '%s ratings', round($itemNews->rating,1)), $this->locale()->toNumber(round($itemNews->rating,1)))?>"><i class="far fa-star"></i><?php echo round($itemNews->rating,1).'/5';?></span>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<?php if((isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive)):?>
							<?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $itemNews->getHref()); ?>
							<div class="sesnews_list_grid_thumb_btns"> 
								<?php if(isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)):?>
								
                  <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $itemNews, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
								<?php endif;?>
								<?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 ):?>
									<?php $canComment =  $itemNews->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');?>
									<?php if(isset($this->likeButtonActive) && $canComment):?>
										<!--Like Button-->
										<?php $LikeStatus = Engine_Api::_()->sesnews()->getLikeStatus($itemNews->news_id,$itemNews->getType()); ?>
										<a href="javascript:;" data-url="<?php echo $itemNews->news_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesnews_like_sesnews_news_<?php echo $itemNews->news_id ?> sesnews_like_sesnews_news <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $itemNews->like_count; ?></span></a>
									<?php endif;?>
									<?php if(isset($this->favouriteButtonActive) && isset($itemNews->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)): ?>
										<?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesnews')->isFavourite(array('resource_type'=>'sesnews_news','resource_id'=>$itemNews->news_id)); ?>
										<a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesnews_favourite_sesnews_news_<?php echo $itemNews->news_id ?> sesnews_favourite_sesnews_news <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $itemNews->news_id ; ?>"><i class="fa fa-heart"></i><span><?php echo $itemNews->favourite_count; ?></span></a>
									<?php endif;?>
								<?php endif;?>
							</div>
						<?php endif;?> 
						</div>
				</li>
			<?php endif;?>
			<?php $limit++;?>
		</ul>
	</div>
<?php elseif($this->type == 'grid2'):?>
	<div class="sesnews_news_second_of_the_day">
		<ul class="sesnews_album_listing sesbasic_bxs">
			<?php $limit = 0;?>
			<?php $itemNews = Engine_Api::_()->getItem('sesnews_news',$this->news_id);?>
			<?php if($itemNews):?>
			<li class="sesnews_grid sesnews_list_grid_thumb sesnews_list_grid sesa-i-<?php echo (isset($this->insideOutside) && $this->insideOutside == 'outside') ? 'outside' : 'inside'; ?> sesa-i-<?php echo (isset($this->fixHover) && $this->fixHover == 'fix') ? 'fix' : 'over'; ?> sesbm" style="width:<?php echo is_numeric($this->width) ? $this->width.'px' : $this->width ?>;">
				<div class="sesnews_grid_inner sesnews_thumb">
					<div class="sesnews_grid_thumb sesnews_thumb" style="height:<?php echo is_numeric($this->height) ? $this->height.'px' : $this->height ?>;"> <a class="sesnews_thumb_img" href="<?php echo $itemNews->getHref(); ?>"> <span class="main_image_container" style="background-image: url(<?php echo $itemNews->getPhotoUrl('thumb.main'); ?>);"></span> </a>
						<?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive)){ ?>
						<div class="sesnews_grid_labels">
							<?php if(isset($this->featuredLabelActive) && $itemNews->featured == 1){ ?>
							<p class="sesnews_label_featured"><?php echo $this->translate("Featured"); ?></p>
							<?php } ?>
							<?php if(isset($this->sponsored)  && $itemNews->sponsoredLabelActive == 1){ ?>
							<p class="sesnews_label_sponsored"><?php echo $this->translate("Sponsored"); ?></p>
							<?php } ?>
						</div>
						<?php if(isset($this->verifiedLabelActive) && $itemNews->verified == 1):?>
							<div class="sesnews_verified_label" title="<?php echo $this->translate('VERIFIED');?>"><i class="fa fa-check"></i></div>
						<?php endif;?>
						<?php } ?>
						</div>
						<?php if(isset($this->likeActive) || isset($this->commentActive) || isset($this->viewActive) || isset($this->titleActive) || isset($this->favouriteActive) || isset($this->byActive)){ ?>
					<div class="sesnews_grid_info clear sesbasic_clearfix sesbm">
						<?php if(isset($this->titleActive)) { ?>
						<div class="sesnews_category_grid_info_title"> <?php echo $this->htmlLink($itemNews, $this->string()->truncate($itemNews->getTitle(), $this->title_truncation),array('title'=>$itemNews->getTitle())) ; ?> </div>
						<?php } ?>
						<div class="sesnews_list_grid_info sesbasic_clearfix">
							<div class="sesnews_list_stats">
								<?php if(isset($this->byActive)) { ?>
									<span class="sesnews_list_grid_owner"> <a href="<?php echo $itemNews->getOwner()->getHref();?>">
									<?php echo $this->itemPhoto($itemNews->getOwner(), 'thumb.icon');?></a> <?php echo $this->translate('By');?> <?php echo $this->htmlLink($itemNews->getOwner()->getHref(), $itemNews->getOwner()->getTitle(), array('class' => 'thumbs_author')) ?> </span>
								<?php }?>
							</div>
							<div class="sesnews_list_stats sesnews_list_location sesbasic_text_light"> <span> <i class="fa fa-map-marker"></i> <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?><a href="<?php echo $this->url(array('resource_id' => $itemNews->news_id,'resource_type'=>'sesnews_news','action'=>'get-direction'), 'sesbasic_get_direction', true) ;?>" class="opensmoothboxurl"><?php echo $itemNews->location;?></a><?php } else { ?><?php echo $itemNews->location;?><?php } ?></span> </div>
						</div>
						<?php } ?>
						<div class="sesnews_list_stats sesbasic_text_light">
							<?php if(isset($this->likeActive)) { ?>
								<span class="sesnews_list_grid_likes" title="<?php echo $this->translate(array('%s like', '%s likes', $itemNews->like_count), $this->locale()->toNumber($itemNews->like_count))?>"> <i class="sesbasic_icon_like_o"></i> <?php echo $itemNews->like_count;?> </span>
							<?php } ?>
							<?php if(isset($this->commentActive)) { ?>
								<span class="sesnews_list_grid_comment" title="<?php echo $this->translate(array('%s comment', '%s comments', $itemNews->comment_count), $this->locale()->toNumber($itemNews->comment_count))?>"> <i class="sesbasic_icon_comment_o"></i> <?php echo $itemNews->comment_count;?> </span>
							<?php } ?>
							<?php if(isset($this->viewActive)) { ?>
								<span class="sesnews_list_grid_views" title="<?php echo $this->translate(array('%s view', '%s views', $itemNews->view_count), $this->locale()->toNumber($itemNews->view_count))?>"> <i class="sesbasic_icon_view"></i> <?php echo $itemNews->view_count;?> </span>
							<?php } ?>
							<?php if(isset($this->favouriteActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)) { ?>
								<span class="sesnews_list_grid_fav" title="<?php echo $this->translate(array('%s favourite', '%s favourites', $itemNews->favourite_count), $this->locale()->toNumber($itemNews->favourite_count))?>"> <i class="sesbasic_icon_favourite_o"></i> <?php echo $itemNews->favourite_count;?> </span>
							<?php } ?>
							<?php if(Engine_Api::_()->sesbasic()->getViewerPrivacy('sesnews_review', 'view') && isset($this->ratingActive) && isset($itemNews->rating)): ?>
								<span title="<?php echo $this->translate(array('%s rating', '%s ratings', round($itemNews->rating,1)), $this->locale()->toNumber(round($itemNews->rating,1)))?>"><i class="far fa-star"></i><?php echo round($itemNews->rating,1).'/5';?></span>
							<?php endif; ?>
					</div>
					
				</div>
				<div class="sesnews_list_thumb_over">
											<?php if((isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive)):?>
							<?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $itemNews->getHref()); ?>
							<div class="sesnews_list_grid_thumb_btns"> 
								<?php if(isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)):?>
                  
                  <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $itemNews, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>

								<?php endif;?>
								<?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 ):?>
									<?php $canComment =  $itemNews->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');?>
									<?php if(isset($this->likeButtonActive) && $canComment):?>
										<!--Like Button-->
										<?php $LikeStatus = Engine_Api::_()->sesnews()->getLikeStatus($itemNews->news_id,$itemNews->getType()); ?>
										<a href="javascript:;" data-url="<?php echo $itemNews->news_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesnews_like_sesnews_news_<?php echo $itemNews->news_id ?> sesnews_like_sesnews_news <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $itemNews->like_count; ?></span></a>
									<?php endif;?>
									<?php if(isset($this->favouriteButtonActive) && isset($itemNews->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)): ?>
										<?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesnews')->isFavourite(array('resource_type'=>'sesnews_news','resource_id'=>$itemNews->news_id)); ?>
										<a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesnews_favourite_sesnews_news_<?php echo $itemNews->news_id ?> sesnews_favourite_sesnews_news <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $itemNews->news_id ; ?>"><i class="fa fa-heart"></i><span><?php echo $itemNews->favourite_count; ?></span></a>
									<?php endif;?>
								<?php endif;?>
							</div>
						<?php endif;?>
					</div>
				</div>
			</li>
			<?php endif;?>
			<?php $limit++;
		?>
		</ul>
	</div>
<?php elseif($this->type == 'grid3'):?>
<div class="sesnews_news_three_of_the_day">
 <?php $limit = 0;?>
    <?php $itemNews = Engine_Api::_()->getItem('sesnews_news',$this->news_id);?>
    <?php if($itemNews):?>
  <div class="sesnews_last_grid_block sesbasic_bxs " style="width:140px;">
    <div class="sesnews_grid_inner">
      <div class="sesnews_grid_thumb sesnews_thumb" style="height:160px;"> <a class="sesnews_thumb_img" href="<?php echo $itemNews->getHref(); ?>"> 
      <?php if(isset($this->likeActive) || isset($this->commentActive) || isset($this->viewActive) || isset($this->titleActive) || isset($this->favouriteActive) || isset($this->byActive)){ ?>
      <span class="main_image_container" style="background-image: url(<?php echo $itemNews->getPhotoUrl('thumb.main'); ?>);"></span> </a>
        <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive)){ ?>
        <div class="sesnews_grid_labels">
          <?php if(isset($this->featuredLabelActive) && $itemNews->featured == 1){ ?>
          <p class="sesnews_label_featured"><?php echo $this->translate("Featured"); ?></p>
          <?php } ?>
          <?php if(isset($this->sponsoredLabelActive)  && $itemNews->sponsored == 1){ ?>
          <p class="sesnews_label_sponsored"><?php echo $this->translate("Sponsored"); ?></p>
          <?php } ?>
        </div>
        <?php } ?>
				<?php if(isset($this->verifiedLabelActive) && $itemNews->verified == 1):?>
					<div class="sesnews_grid_verified_label" title="<?php echo $this->translate('VERIFIED');?>"><i class="fa fa-check"></i></div>
				<?php endif;?>
        <div class="sesnews_grid_thumb_over">
          <?php if((isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive)):?>
						<?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $itemNews->getHref()); ?>
						<div class="sesnews_list_grid_thumb_btns"> 
							<?php if(isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)):?>
                
                <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $itemNews, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
							<?php endif;?>
							<?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 ):?>
								<?php $canComment =  $itemNews->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');?>
								<?php if(isset($this->likeButtonActive) && $canComment):?>
									<!--Like Button-->
									<?php $LikeStatus = Engine_Api::_()->sesnews()->getLikeStatus($itemNews->news_id,$itemNews->getType()); ?>
									<a href="javascript:;" data-url="<?php echo $itemNews->news_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesnews_like_sesnews_news_<?php echo $itemNews->news_id ?> sesnews_like_sesnews_news <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $itemNews->like_count; ?></span></a>
								<?php endif;?>
								<?php if(isset($this->favouriteButtonActive) && isset($itemNews->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)): ?>
									<?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesnews')->isFavourite(array('resource_type'=>'sesnews_news','resource_id'=>$itemNews->news_id)); ?>
									<a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesnews_favourite_sesnews_news_<?php echo $itemNews->news_id ?> sesnews_favourite_sesnews_news <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $itemNews->news_id ; ?>"><i class="fa fa-heart"></i><span><?php echo $itemNews->favourite_count; ?></span></a>
								<?php endif;?>
							<?php endif;?>
						</div>
					<?php endif;?>
        </div>
      </div>
      <div class="sesnews_grid_info clear clearfix sesbm">
        <div class="sesnews_grid_meta_block">
					<?php if($itemNews->category_id != '' && intval($itemNews->category_id) && !is_null($itemNews->category_id)):?> 
						<?php $categoryItem = Engine_Api::_()->getItem('sesnews_category', $itemNews->category_id);?>
						<?php if($categoryItem):?>
							<div class="sesnews_grid_memta_title floatL">
								<span>
									<a href="<?php echo $categoryItem->getHref(); ?>"><?php echo $categoryItem->category_name; ?></a>
								</span>
							</div>
						<?php endif;?>
					<?php endif;?>
          <?php if(Engine_Api::_()->getApi('core', 'sesnews')->allowReviewRating() && isset($this->ratingStarActive)):?>
				<?php echo $this->partial('_newsRating.tpl', 'sesnews', array('rating' => $itemNews->rating, 'class' => 'sesnews_list_rating sesnews_list_view_ratting floatR', 'style' => 'margin:0px;'));?>
			<?php endif;?>
        </div>
        <?php if(isset($this->titleActive)) { ?>
        <div class="sesnews_grid_three_info_title"> <?php echo $this->htmlLink($itemNews, $this->string()->truncate($itemNews->getTitle(), $this->title_truncation),array('title'=>$itemNews->getTitle())) ; ?> </div>
        <?php } ?>
        <div class="sesnews_grid_meta_block">
          <div class="sesnews_list_stats sesbasic_text_dark">
            <?php if(isset($this->byActive)) { ?>
            <span class="sesnews_list_grid_owner"> <a href="<?php $itemNews->getOwner()->getHref();?>"><?php echo $this->itemPhoto($itemNews->getOwner(), 'thumb.icon');?></a> <?php echo $this->translate('By');?> <?php echo $this->htmlLink($itemNews->getOwner()->getHref(), $itemNews->getOwner()->getTitle(), array('class' => 'thumbs_author')) ?> </span>
            <?php }?>
            | <span><i class="fa fa-map-marker"></i>&nbsp;<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?><a href="<?php echo $this->url(array('resource_id' => $itemNews->news_id,'resource_type'=>'sesnews_news','action'=>'get-direction'), 'sesbasic_get_direction', true) ;?>" class="opensmoothboxurl"><?php echo $itemNews->location;?></a><?php } else { ?><?php echo $itemNews->location;?><?php } ?></span> </div>
        </div>
        <div class="sesnews_grid_contant">
          <?php echo $itemNews->getDescription($this->description_truncation);?>
        </div>
        <div class="sesnews_comment_list sesnews_list_stats sesbasic_text_dark floatL">
          <?php if(isset($this->likeActive)) { ?>
          <span class="sesnews_list_grid_likes" title="<?php echo $this->translate(array('%s like', '%s likes', $itemNews->like_count), $this->locale()->toNumber($itemNews->like_count))?>"> <i class="sesbasic_icon_like_o"></i> <?php echo $itemNews->like_count;?> </span>
          <?php } ?>
          <?php if(isset($this->commentActive)) { ?>
          <span class="sesnews_list_grid_comment" title="<?php echo $this->translate(array('%s comment', '%s comments', $itemNews->comment_count), $this->locale()->toNumber($itemNews->comment_count))?>"> <i class="sesbasic_icon_comment_o"></i> <?php echo $itemNews->comment_count;?> </span>
          <?php } ?>
          <?php if(isset($this->viewActive)) { ?>
          <span class="sesnews_list_grid_views" title="<?php echo $this->translate(array('%s view', '%s views', $itemNews->view_count), $this->locale()->toNumber($itemNews->view_count))?>"> <i class="sesbasic_icon_view"></i> <?php echo $itemNews->view_count;?> </span>
          <?php } ?>
          <?php if(isset($this->favouriteActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)) { ?>
          <span class="sesnews_list_grid_fav" title="<?php echo $this->translate(array('%s favourite', '%s favourites', $itemNews->favourite_count), $this->locale()->toNumber($itemNews->favourite_count))?>"> <i class="fa fa-heart"></i> <?php echo $itemNews->favourite_count;?> </span>
          <?php } ?>
          <?php if(Engine_Api::_()->sesbasic()->getViewerPrivacy('sesnews_review', 'view') && isset($this->ratingActive) && isset($itemNews->rating)): ?>
							<span title="<?php echo $this->translate(array('%s rating', '%s ratings', round($itemNews->rating,1)), $this->locale()->toNumber(round($itemNews->rating,1)))?>"><i class="far fa-star"></i><?php echo round($itemNews->rating,1).'/5';?></span>
						<?php endif; ?></div>
        <div class="sesnews_second_readmore_link floatR"> <a href="<?php echo $itemNews->getHref();?>"><?php echo $this->translate('Read More...');?></a> </div>
      </div>
      <?php } ?>
    </div>
  </div>
      <?php endif;?>
    <?php $limit++;
   ?></div>
 <?php endif;?>
