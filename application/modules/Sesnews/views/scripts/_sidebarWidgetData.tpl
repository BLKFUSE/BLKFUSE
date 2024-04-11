<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _sideWidgetData.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php 

$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); 
?>
<?php foreach( $this->results as $item ): ?>
	<?php if($this->view_type == 'list'):?>
		<li class="sesnews_sidebar_news_list sesbasic_clearfix">
			<div class="sesnews_sidebar_news_list_img <?php if($this->image_type == 'rounded'):?>sesnews_sidebar_image_rounded<?php endif;?> sesnews_list_thumb sesnews_thumb" style="height:<?php echo is_numeric($this->height) ? $this->height.'px' : $this->height_list ?>;width:<?php echo is_numeric($this->width) ? $this->width.'px' : $this->width ?>;">
				<?php $href = $item->getHref();$imageURL = $item->getPhotoUrl('thumb.normal');?>
				<a href="<?php echo $href; ?>" class="sesnews_thumb_img">
					<img src="<?php echo $imageURL; ?>" alt="" />
				</a>     
			</div>
			<div class="sesnews_sidebar_news_list_cont">
				<?php  if(isset($this->titleActive)): ?>
					<div class="sesnews_sidebar_news_list_title sesnews_list_info_title">
						<?php if(strlen($item->getTitle()) > $this->title_truncation):?>
							<?php $title = mb_substr($item->getTitle(),0,$this->title_truncation).'...';?>
							<?php echo $this->htmlLink($item->getHref(),$title, array('class' => '', 'data-src' => $item->getGuid()));?>
						<?php  else : ?>
							<?php echo $this->htmlLink($item->getHref(),$item->getTitle(), array('class' => '', 'data-src' => $item->getGuid())) ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>       
				<?php if(Engine_Api::_()->getApi('core', 'sesnews')->allowReviewRating() && isset($this->ratingStarActive)):?>
					<?php echo $this->partial('_newsRating.tpl', 'sesnews', array('rating' => $item->rating, 'class' => 'sesnews_list_rating sesnews_list_view_ratting', 'style' => 'margin-bottom:0px; margin-top:10px;'));?>
				<?php endif;?>  
					<?php if(isset($this->byActive)): ?>
						<?php $owner = $item->getOwner(); ?>
            <div class="sesnews_sidebar_news_list_date sesnews_sidebar_list_date">
						  <span><?php echo $this->translate('By');?> <?php echo $this->htmlLink($owner->getHref(),$owner->getTitle()) ?></span>
            </div>
					<?php endif; ?>
					<?php if(isset($this->creationDateActive)):?>
           <div class="sesnews_sidebar_news_list_date sesnews_sidebar_list_date">
						 <span><i class="far fa-calendar"></i> <?php echo date('M d, Y',strtotime($item->creation_date));?></span>
           </div>
					<?php endif;?>
				<?php  if(isset($this->locationActive) && $item->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.location', 1)): ?>
					<div class="sesnews_list_stats sesnews_list_location">
						<span class="widthfull">
							<i class="sesbasic_icon_map sesbasic_text_light" title="<?php echo $this->translate('Location');?>"></i>
							<span title="<?php echo $item->location; ?>"><?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?><a href="<?php echo $this->url(array('resource_id' => $item->news_id,'resource_type'=>'sesnews_news','action'=>'get-direction'), 'sesbasic_get_direction', true); ?>" class="openSmoothbox"><?php echo $item->location ?></a><?php } else { ?><?php echo $item->location ?><?php } ?></span>
						</span>
					</div>
				<?php endif; ?>
				<?php if(isset($this->categoryActive)): ?>
					<?php if($item->category_id != '' && intval($item->category_id) && !is_null($item->category_id)):?>
						<?php $categoryItem = Engine_Api::_()->getItem('sesnews_category', $item->category_id);?>
            <?php if($categoryItem): ?>
              <div class="sesnews_sidebar_news_list_date">
                  <i class="far fa-folder-open sesbasic_text_light" title="<?php echo $this->translate('Category:'); ?>"></i> 
                  <span><a href="<?php echo $categoryItem->getHref(); ?>">
                  <?php echo $categoryItem->category_name; ?></a>
                  <?php $subcategory = Engine_Api::_()->getItem('sesnews_category',$item->subcat_id); ?>
                  <?php if($subcategory && $item->subcat_id): ?>
                    &nbsp;&raquo;&nbsp;<a href="<?php echo $subcategory->getHref(); ?>"><?php echo $subcategory->category_name; ?></a>
                  <?php endif; ?>
                  <?php $subsubcategory = Engine_Api::_()->getItem('sesnews_category',$item->subsubcat_id); ?>
                  <?php if($subsubcategory && $item->subsubcat_id): ?>
                    &nbsp;&raquo;&nbsp;<a href="<?php echo $subsubcategory->getHref(); ?>"><?php echo $subsubcategory->category_name; ?></a>
                  <?php endif; ?>
                </span>
              </div>
            <?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
				<div class="sesnews_sidebar_news_list_date sesbasic_text_light">
					<?php if(isset($this->likeActive) && isset($item->like_count)) { ?>
						<span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o sesbasic_text_light"></i><?php echo $item->like_count; ?></span>
					<?php } ?>
					<?php if(isset($this->commentActive) && isset($item->comment_count)) { ?>
						<span title="<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count))?>"><i class="sesbasic_icon_comment_o sesbasic_text_light"></i><?php echo $item->comment_count;?></span>
					<?php } ?>
					<?php if(isset($this->viewActive) && isset($item->view_count)) { ?>
						<span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="sesbasic_icon_view sesbasic_text_light"></i><?php echo $item->view_count; ?></span>
					<?php } ?>
					<?php if(isset($this->favouriteActive) && isset($item->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)) { ?>
						<span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count))?>"><i class="sesbasic_icon_favourite_o sesbasic_text_light"></i><?php echo $item->favourite_count; ?></span>
					<?php } ?>
					<?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/_newsRatingStat.tpl';?>
				</div>
			</div>
		</li>
	<?php elseif($this->view_type == 'grid1'): ?>
  <li class="col-lg-<?php echo $this->gridblock; ?> col-md-3 col-sm-6 col-12">
		<div class="sesnews_sidebar_grid sesnews_grid sesnews_list_grid_thumb sesnews_list_grid sesbasic_clearfix sesbasic_bxs sesbm">
			<div class="sesnews_grid_inner sesnews_thumb">
				<div class="sesnews_grid_thumb sesnews_thumb" style="height:<?php echo is_numeric($this->height) ? $this->height.'px' : $this->height ?>;">
					<?php $href = $item->getHref();$imageURL = $item->getPhotoUrl('thumb.profile');?>
					<a href="<?php echo $href; ?>" class="sesnews_thumb_img">
						<span class="main_image_container" style="background-image:url(<?php echo $imageURL; ?>);"></span>
					</a>
					<?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive)): ?>
						<div class="sesnews_grid_labels ">
							<?php if(isset($this->featuredLabelActive) && $item->featured): ?>
								<p class="sesnews_label_featured"><?php echo $this->translate('FEATURED');?></p>
							<?php endif; ?>
							<?php if(isset($this->sponsoredLabelActive) && $item->sponsored): ?>
								<p class="sesnews_label_sponsored"><?php echo $this->translate('SPONSORED');?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if(isset($this->verifiedLabelActive) && $item->verified == 1): ?>
						<div class="sesnews_verified_label" title="<?php echo $this->translate("VERIFIED"); ?>" style=""><i class="fa fa-check"></i></div>
					<?php endif; ?>
					<?php if((isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive)):?>
						<?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
						<div class="sesnews_grid_hover_block"> 
							<a href="<?php echo $href; ?>" data-url = "<?php echo $item->getType() ?>"></a>
							<div class="sesnews_list_grid_thumb_btns"> 
								<?php if(isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)): ?>
                  
                  <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item)); ?>

								<?php endif;?>
								<?php $itemtype = 'sesnews_news';?>
								<?php $getId = 'news_id';?>
								<?php $canComment =  $item->authorization()->isAllowed($this->viewer, 'comment');?>
								<?php if(isset($this->likeButtonActive) && $canComment):?>
									<!--Like Button-->
									<?php $LikeStatus = Engine_Api::_()->sesnews()->getLikeStatusNews($item->$getId, $item->getType()); ?>
									<a href="javascript:;" data-url="<?php echo $item->$getId ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesnews_like_<?php echo $itemtype; ?> <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count; ?></span></a>
								<?php endif; ?>
								<?php if(isset($this->favouriteButtonActive) && isset($item->favourite_count) && $this->viewer_id && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)):?>
									<?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesnews')->isFavourite(array('resource_type'=>'sesnews_news','resource_id'=>$item->news_id));
									$favClass = ($favStatus)  ? 'button_active' : '';?>
									<?php $shareOptions = "<a href='javascript:;' class='sesbasic_icon_btn sesbasic_icon_btn_count sesnews_favourite_sesnews_news_". $item->news_id." sesbasic_icon_fav_btn sesnews_favourite_sesnews_news ".$favClass ."' data-url=\"$item->news_id\"><i class='fa fa-heart'></i><span>$item->favourite_count</span></a>";?>
									<?php echo $shareOptions;?>
								<?php endif;?>
								<?php if(isset($this->listButtonActive) && $this->viewer_id): ?>
									<a href="javascript:;" onclick="opensmoothboxurl('<?php echo $this->url(array('action' => 'add','module'=>'sesnews','controller'=>'list','news_id'=>$item->news_id),'default',true); ?>')" class="sesbasic_icon_btn  sesnews_add_list"  title="<?php echo  $this->translate('Add To List'); ?>" data-url="<?php echo $item->news_id ; ?>"><i class="fa fa-plus"></i></a>
								<?php endif; ?>
							</div>
							<div class="sesnews_grid_hover_block_footer">
								<?php if(isset($this->categoryActive)): ?>
									<?php if($item->category_id != '' && intval($item->category_id) && !is_null($item->category_id)):?>
										<?php $categoryItem = Engine_Api::_()->getItem('sesnews_category', $item->category_id);?>
										<?php if($categoryItem): ?>
			                <div class="sesnews_admin sesbasic_clearfix">
			                  <span>
			                    <i class="far fa-folder-open sesbasic_text_light" title="<?php echo $this->translate('Category:'); ?>"></i>
			                    <a href="<?php echo $categoryItem->getHref(); ?>"><?php echo $categoryItem->category_name; ?></a>
			                    <?php $subcategory = Engine_Api::_()->getItem('sesnews_category',$item->subcat_id); ?>
			                    <?php if($subcategory && $item->subcat_id) { ?>
			                    &nbsp;&raquo;&nbsp;<a href="<?php echo $subcategory->getHref(); ?>"><?php echo $subcategory->category_name; ?></a>
			                    <?php } ?>
			                    <?php $subsubcategory = Engine_Api::_()->getItem('sesnews_category',$item->subsubcat_id); ?>
			                    <?php if($subsubcategory && $item->subsubcat_id) { ?>
			                    &nbsp;&raquo;&nbsp;<a href="<?php echo $subsubcategory->getHref(); ?>"><?php echo $subsubcategory->category_name; ?></a>
			                    <?php } ?>
			                  </span>
			                </div>
			              <?php endif; ?>
									<?php endif; ?>
								<?php endif; ?>
								<div class="sesnews_list_stats">
									<?php if(isset($this->likeActive) && isset($item->like_count)) { ?>
										<span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o sesbasic_text_light"></i><?php echo $item->like_count; ?></span>
									<?php } ?>
									<?php if(isset($this->commentActive) && isset($item->comment_count)) { ?>
										<span title="<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count))?>"><i class="sesbasic_icon_comment_o sesbasic_text_light"></i><?php echo $item->comment_count;?></span>
									<?php } ?>
									<?php if(isset($this->viewActive) && isset($item->view_count)) { ?>
										<span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="sesbasic_icon_view sesbasic_text_light"></i><?php echo $item->view_count; ?></span>
									<?php } ?>
									<?php if(isset($this->favouriteActive) && isset($item->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)) { ?>
										<span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count))?>"><i class="sesbasic_icon_favourite_o sesbasic_text_light"></i><?php echo $item->favourite_count; ?></span>
									<?php } ?>
									<?php if(Engine_Api::_()->sesbasic()->getViewerPrivacy('sesnews_review', 'view') && isset($this->ratingActive) && isset($itemNews->rating)): ?>
										<span title="<?php echo $this->translate(array('%s rating', '%s ratings', round($itemNews->rating,1)), $this->locale()->toNumber(round($itemNews->rating,1)))?>"><i class="far fa-star"></i><?php echo round($itemNews->rating,1).'/5';?></span>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<div class="sesnews_grid_info clear sesbasic_clearfix sesbm">
					<?php if(isset($this->titleActive) ): ?>
						<div class="sesnews_grid_info_title">
							<?php if(strlen($item->getTitle()) > $this->title_truncation):?>
								<?php $title = mb_substr($item->getTitle(),0,$this->title_truncation).'...';?>
								<?php echo $this->htmlLink($item->getHref(),$title, array('class' => '', 'data-src' => $item->getGuid())) ?>
							<?php else: ?>
								<?php echo $this->htmlLink($item->getHref(),$item->getTitle(), array('class' => '', 'data-src' => $item->getGuid())) ?>
							<?php endif; ?>
							<?php if(isset($this->verifiedLabelActive) && $item->verified == 1): ?>
								<i class="sesnews_verified_sign fa fa-check-circle"></i>
							<?php endif; ?>
						</div>
					<?php endif; ?>
          <?php if(Engine_Api::_()->getApi('core', 'sesnews')->allowReviewRating() && isset($this->ratingStarActive)):?>
					<?php echo $this->partial('_newsRating.tpl', 'sesnews', array('rating' => $item->rating, 'class' => 'sesnews_list_rating sesnews_list_view_ratting', 'style' => 'margin-bottom:5px;'));?>
				<?php endif;?>
					<?php if(isset($this->byActive)): ?>
						<div class="sesnews_admin sesbasic_clearfix">
							<?php $owner = $item->getOwner(); ?>
							<span class="sesnews_list_stats sesbasic_text_light">
								<a href="<?php echo $owner->getHref();?>"><?php echo $this->itemPhoto($owner, 'thumb.icon');?></a>
							 <?php echo $this->htmlLink($owner->getHref(),$owner->getTitle()) ?>
							</span>
						</div>
					<?php endif; ?>
					<?php if(isset($this->locationActive) && $item->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.location', 1)):?>
						<div class="sesnews_list_location">
							<span class="widthfull">
							<i class="sesbasic_icon_map sesbasic_text_light" title="<?php echo $this->translate('Location');?>"></i>
								<span title="<?php echo $item->location; ?>"><?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?><a href="<?php echo $this->url(array('resource_id' => $item->news_id,'resource_type'=>'sesnews_news','action'=>'get-direction'), 'sesbasic_get_direction', true); ?>" class="openSmoothbox"><?php echo $item->location ?></a><?php } else { ?><?php echo $item->location ?><?php } ?></span>
							</span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
   </li>
	<?php endif; ?>
<?php endforeach; ?>
