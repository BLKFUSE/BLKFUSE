<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _listView.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<li class="sesnews_list_news_view sesbasic_clearfix clear">
	<div class="sesnews_list_thumb sesnews_thumb" style="height:<?php echo is_numeric($this->height) ? $this->height.'px' : $this->height ?>;width:<?php echo is_numeric($this->width_list) ? $this->width_list.'px' : $this->width_list ?>;">
		<?php $href = $item->getHref();$imageURL = $photoPath;?>

		<a href="<?php echo $href; ?>" data-url = "<?php echo $item->getType() ?>" class="sesnews_thumb_img"><img src="<?php echo $imageURL; ?>" alt="" align="left" /></a>
		<?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->verifiedLabel)):?>
          <div class="sesnews_grid_labels">
            <?php if(isset($this->featuredLabelActive) && $item->featured == 1):?>
              <p class="sesnews_label_featured" title="<?php echo $this->translate('FEATURED');?>"><i class="fa fa-star"></i></p>
            <?php endif;?>
            <?php if(isset($this->sponsoredLabelActive) && $item->sponsored == 1):?>
              <p class="sesnews_label_sponsored" title="<?php echo $this->translate('SPONSORED');?>"><i class="fa fa-star"></i></p>
            <?php endif;?>
             <?php if(isset($this->hotLabelActive) && $item->hot == 1) { ?>
            <p class="sesnews_label_hot" title="<?php echo $this->translate('Hot'); ?>"><i class="fa fa-star"></i></p>
          <?php } ?>
          <?php if(isset($this->newLabelActive) && $item->latest == 1) { ?>
            <p class="sesnews_label_new" title="<?php echo $this->translate('New'); ?>"><i class="fa fa-star"></i></p>
          <?php } ?>
            <?php if(isset($this->verifiedLabelActive) && $item->verified == 1):?>
              <div class="sesnews_grid_verified_label" title="<?php echo $this->translate('VERIFIED');?>"><i class="fa fa-check"></i></div>
            <?php endif;?>
          </div>
        <?php endif;?>
		<?php if((isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive)):?>
			<?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
			<div class="sesnews_list_thumb_over">
				<a href="<?php echo $href; ?>" data-url = "<?php echo $item->getType() ?>"></a>
				<div class="sesnews_grid_btns"> 
					<?php if(isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)):?>
            
            <?php if($this->socialshare_icon_limit): ?>
              <?php echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
            <?php else: ?>
              <?php echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_listview1plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_listview1limit)); ?>
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
	</div>
	<div class="sesnews_list_info">
  <?php if(isset($this->titleActive)): ?>
			<div class="sesnews_list_info_title floatL">
				<?php if(strlen($item->getTitle()) > $this->title_truncation_list):?>
					<?php $title = mb_substr($item->getTitle(),0,$this->title_truncation_list).'...';?>
					<?php echo $this->htmlLink($item->getHref(),$title,array('title'=>$item->getTitle()));?>
				<?php else: ?>
					<?php echo $this->htmlLink($item->getHref(),$item->getTitle(),array('title'=>$item->getTitle())  ) ?>
				<?php endif;?>
			</div>
		<?php endif;?>
    <?php if(Engine_Api::_()->getApi('core', 'sesnews')->allowReviewRating() && isset($this->ratingStarActive)):?>
			<?php echo $this->partial('_newsRating.tpl', 'sesnews', array('rating' => $item->rating, 'class' => 'sesnews_list_rating sesnews_list_view_ratting floatR ', 'style' => ''));?>
    <?php endif;?>
		<div class="sesnews_admin_list">
			<?php if(isset($this->byActive)){ ?>
				<div class="sesnews_stats_list sesbasic_text_dark">
					<?php $owner = $item->getOwner(); ?>
					<span>
						<?php echo $this->htmlLink($item->getOwner()->getParent(), $this->itemPhoto($item->getOwner()->getParent(), 'thumb.icon')); ?>
						<?php echo $this->translate("by") ?> <?php echo $this->htmlLink($owner->getHref(),$owner->getTitle() ) ?>
					</span>
				</div>
			<?php } ?>
			<?php if(isset($this->creationDateActive)){ ?>
				<div class="sesnews_stats_list sesbasic_text_dark">
					<span>
						<i class="far fa-clock"></i>
            <?php if($item->publish_date): ?>
              <?php echo date('M d, Y',strtotime($item->creation_date));?>
						<?php else: ?>
              <?php echo date('M d, Y',strtotime($item->creation_date));?>
						<?php endif; ?>
					</span>
				</div>
			<?php } ?>
			<?php if(isset($this->categoryActive)){ ?>
				<?php if($item->category_id != '' && intval($item->category_id) && !is_null($item->category_id)):?> 
					<?php $categoryItem = Engine_Api::_()->getItem('sesnews_category', $item->category_id);?>
					<?php if($categoryItem):?>
						<div class="sesnews_stats_list sesbasic_text_dark">
							<span>
							<i class="far fa-folder-open" title="<?php echo $this->translate('Category'); ?>"></i> 
							<a href="<?php echo $categoryItem->getHref(); ?>"><?php echo $categoryItem->category_name; ?></a>
							</span>
						</div>
					<?php endif;?>
				<?php endif;?>
			<?php } ?>
      		<?php if(isset($this->locationActive) && isset($item->location) && $item->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.location', 1)){ ?>
			<div class="sesnews_stats_list sesbasic_text_dark sesnews_list_location">
				<span>
					<i class="sesbasic_icon_map"></i>
					<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
            <a href="<?php echo $this->url(array('resource_id' => $item->news_id,'resource_type'=>'sesnews_news','action'=>'get-direction'), 'sesbasic_get_direction', true) ;?>" class="opensmoothboxurl"  title="<?php echo $item->location;?>"><?php echo $item->location;?></a>
					<?php } else { ?>
            <?php echo $item->location;?>
					<?php } ?>
				</span>
			</div>
		<?php } ?>
		</div>
		<div class="sesnews_list_contant">
		<?php if(isset($this->descriptionlistActive)){ ?>
			<p class="sesnews_list_des">
				<?php echo $item->getDescription($this->description_truncation_list);?>
			</p>
		<?php } ?>      
		</div>
  <div class="sesnews_static_list_group">
		<div class="sesnews_list_stats sesbasic_text_dark">
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
			<div class="sesnews_list_readmore floatR"><a class="sesnews_animation" href="<?php echo $item->getHref();?>"><?php echo $this->translate('Read More');?> <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></div>
		<?php endif;?>
    </div>
		<?php if(isset($this->my_news) && $this->my_news){ ?> 
    <div class="sesnews_options_buttons sesnews_list_options sesbasic_clearfix">
				<?php if($this->can_edit){ ?>
				<a href="<?php echo $this->url(array('action' => 'edit', 'news_id' => $item->news_id), 'sesnews_specific', true); ?>" class="sesbasic_icon_btn" title="<?php echo $this->translate('Edit News'); ?>">
				<i class="fa fa-edit"></i>
				</a>
				<?php } ?>
				<?php if ($this->can_delete){ ?>
					<a href="<?php echo $this->url(array('action' => 'delete', 'news_id' => $item->news_id), 'sesnews_specific', true); ?>" class="sesbasic_icon_btn" title="<?php echo $this->translate('Delete News'); ?>" onclick='opensmoothboxurl(this.href);return false;'>
					<i class="fa fa-trash"></i>
					</a>
				<?php } ?>

		</div>
			<?php } ?>
	</div>
</li>
