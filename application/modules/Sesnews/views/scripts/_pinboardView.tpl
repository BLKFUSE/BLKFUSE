<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _pinboardView.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<li class="sesnews_pinboard_list sesbasic_bxs new_image_pinboard_<?php echo $randonNumber; ?>">
	<div class="sesnews_pinboard_list_item <?php if((isset($this->my_news) && $this->my_news)){ ?>isoptions<?php } ?>">
		<div class="sesnews_pinboard_list_item_top sesnews_thumb sesbasic_clearfix">
			<div class="sesnews_pinboard_list_item_thumb">
				<a href="<?php echo $item->getHref()?>" data-url = "<?php echo $item->getType() ?>" class="<?php echo $item->getType() != 'sesnews_chanel' ? 'sesnews_thumb_img' : 'sesnews_thumb_nolightbox' ?>">
					<img src="<?php echo $photoPath; ?>">
					<span style="background-image:url(<?php echo $photoPath; ?>);display:none;"></span>
				</a>
        <?php if(isset($this->categoryActive)){ ?>
          <?php if($item->category_id != '' && intval($item->category_id) && !is_null($item->category_id)):?> 
            <?php $categoryItem = Engine_Api::_()->getItem('sesnews_category', $item->category_id);?>
            <?php if($categoryItem):?>
              <div class="sesnews_pinboard_categry sesnews_list_stats sesbasic_text_light">
                <span>
                  <a href="<?php echo $categoryItem->getHref(); ?>"><i class="far fa-folder-open" title="<?php echo $this->translate('Category'); ?>"></i> 
                  <span><?php echo $categoryItem->category_name; ?></span></a>
                </span>
              </div>
            <?php endif;?>
          <?php endif;?>
			</div> 
			<?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->verifiedLabel)):?>
				<div class="sesnews_pinboard_labels">
					<?php if(isset($this->featuredLabelActive) && $item->featured == 1):?>
						<p class="sesnews_label_featured" title="Featured"><?php echo $this->translate('<i class="fa fa-star"></i>');?></p>
					<?php endif;?>
					<?php if(isset($this->sponsoredLabelActive) && $item->sponsored == 1):?>
						<p class="sesnews_label_sponsored" title="Sponsored"><?php echo $this->translate('<i class="fa fa-star"></i>');?></p>
					<?php endif;?>
					<?php if(isset($this->hotLabelActive) && $item->hot == 1) { ?>
            <p class="sesnews_label_hot" title="<?php echo $this->translate('Hot'); ?>"><i class="fa fa-star"></i></p>
          <?php } ?>
          <?php if(isset($this->newLabelActive) && $item->latest == 1) { ?>
            <p class="sesnews_label_new" title="<?php echo $this->translate('New'); ?>"><i class="fa fa-star"></i></p>
          <?php } ?>
				</div>
			<?php endif;?>
       <?php if(isset($this->verifiedLabelActive) && $item->verified == 1):?>
        <div class="sesnews_verified_label" title="<?php echo $this->translate('VERIFIED');?>"><i class="fa fa-check"></i></div>
      <?php endif;?>
			<?php if((isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive)):?>
				<?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
				<div class="sesnews_list_thumb_over"> 
					<a href="<?php echo $href; ?>" data-url = "<?php echo $item->getType() ?>"></a> 
					<div class="sesnews_list_grid_thumb_btns"> 
						<?php if(isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)):?>
              
              <?php if($this->socialshare_icon_limit): ?>
                <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
              <?php else: ?>
                <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_pinviewplusicon, 'socialshare_icon_limit' => $this->socialshare_icon_pinviewlimit)); ?>
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

    <div class="sesnews_pinboard_info_news sesbasic_clearfix">
      <div class="sesnews_pinboard_list_item_cont sesbasic_clearfix">
        <?php } ?>
        <?php if(Engine_Api::_()->getApi('core', 'sesnews')->allowReviewRating() && isset($this->ratingStarActive)):?>
          <?php echo $this->partial('_newsRating.tpl', 'sesnews', array('rating' => $item->rating, 'class' => 'sesnews_list_rating sesnews_list_view_ratting floatR', 'style' => ''));?>
        <?php endif;?>
        <?php if(isset($this->titleActive)):?>
          <div class="sesnews_pinboard_title">
            <?php if(strlen($item->getTitle()) > $this->title_truncation_pinboard):?>
              <?php $title = mb_substr($item->getTitle(),0,$this->title_truncation_pinboard).'...';?>
              <?php echo $this->htmlLink($item->getHref(),$title ) ?>
            <?php else: ?>
              <?php echo $this->htmlLink($item->getHref(),$item->getTitle() ) ?>
            <?php endif;?>   
          </div>  
        <?php endif;?>
        
        <div class="sesnews_pinboard_meta_news">
          <?php if(isset($this->byActive)):?>
						<div class="sesnews_list_stats sesbasic_text_dark sesbasic_clearfix">
							<?php $owner = $item->getOwner(); ?>
							<?php echo $this->htmlLink($item->getOwner()->getParent(), $this->itemPhoto($item->getOwner()->getParent(), 'thumb.icon')); ?>
							<span class="sesnews_pinboard_list_item_poster_info_title"><?php echo $this->translate('by ');?><?php echo $this->htmlLink($owner->getHref(),$owner->getTitle() ) ?></span>
						</div>
          <?php endif;?>
          <?php if(isset($this->creationDateActive)){ ?>
            <div class="sesnews_list_stats sesbasic_text_dirk">
              <?php if($item->publish_date): ?>
                <span><i class="far fa-clock"></i> <a href="<?php echo $this->url(array('action'=>'browse'),'sesnews_general',true).'?date='.date('Y-m-d',strtotime($item->creation_date)); ?>"><?php echo date('M d',strtotime($item->creation_date));?></a></span>
              <?php else: ?>
                <span><i class="far fa-clock"></i> <a href="<?php echo $this->url(array('action'=>'browse'),'sesnews_general',true).'?date='.date('Y-m-d',strtotime($item->creation_date)); ?>"><?php echo date('M d',strtotime($item->creation_date));?></a></span>
              <?php endif; ?>
            </div>
          <?php } ?>
          <?php if(isset($this->locationActive) && isset($item->location) && $item->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews_enable_location', 1)){ ?>
            <div class="sesnews_list_stats sesbasic_text_dark sesnews_list_location">
              <span>
                <i class="sesbasic_icon_map"></i>
                <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
                  <a href="<?php echo $this->url(array('resource_id' => $item->news_id,'resource_type'=>'sesnews_news','action'=>'get-direction'), 'sesbasic_get_direction', true) ;?>" class="opensmoothboxurl" title="<?php echo $item->location;?>"><?php echo $item->location;?></a>
                <?php } else { ?>
                  <?php echo $item->location;?>
                <?php } ?>
              </span>
            </div>
          <?php } ?>
        </div>
        <?php if(isset($this->descriptionpinboardActive)){ ?>
          <div class="sesnews_pinboard_list_item_des sesbasic_text_light clear">
          	<?php echo $item->getDescription($this->description_truncation_pinboard);?>
          </div>
        <?php } ?>
        <?php if(isset($this->readmoreActive)):?>
              <div class="sesnews_pinboard_readmore"><a href="<?php echo $item->getHref();?>"><?php echo $this->translate('Read More');?></a></div>
            <?php endif;?>
        <div class="sesnews_pinboard_list_item_btm sesbasic_clearfix">
          <div class="sesnews_pinboard_list_item_btm_cont sesbasic_text_light sesbasic_clearfix">
            <?php $owner = $item->getOwner(); ?>
            <div class="sesnews_pinboard_list_statics sesbasic_text_dark">
              <?php if(isset($this->likeActive) && isset($item->like_count)) { ?>
                <span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $item->like_count; ?></span>
              <?php } ?>
              <?php if(isset($this->commentActive) && isset($item->comment_count)) { ?>
                <span title="<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $item->comment_count;?></span>
              <?php } ?>                  
              <?php if(isset($this->viewActive) && isset($item->view_count)) { ?>
                <span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $item->view_count; ?></span>
              <?php } ?>
              <?php if(isset($this->favouriteActive) && isset($item->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)) { ?>
                <span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $item->favourite_count;?></span>
              <?php } ?>
              <?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/_newsRatingStat.tpl';?>
            </div>
          </div>
          <?php if(isset($this->my_news) && $this->my_news ){ ?>
            <?php if($this->can_edit || $this->can_delete){ ?>
              <div class="sesnews_listing_in_grid2_date sesbasic_text_light">
                <span class="sesnews_list_option_toggle fa fa-ellipsis-v sesbasic_text_light"></span>
                <div class="sesnews_listing_options_pulldown">
                  <?php if($this->can_edit){ 
                  echo $this->htmlLink(array('route' => 'sesnews_specific','action' => 'edit','news_id' => $item->news_id), $this->translate('Edit News')) ; 
                  } ?>
                  <?php if ($this->can_delete){
                  echo $this->htmlLink(array('route' => 'sesnews_specific','action' => 'delete', 'news_id' => $item->news_id), $this->translate('Delete News'), array('onclick' =>'opensmoothboxurl(this.href);return false;'));
                  } ?>
                </div>
              </div>
            <?php } ?>
          <?php } ?>
          <?php if(isset($this->enableCommentPinboardActive)):?>
            <?php $itemType = '';?>
            <?php if(isset($item->news_id)):?> 
              <?php $item_id = $item->news_id;?>
              <?php $itemType = 'sesnews_news';?>
            <?php endif; ?>
            <?php if(($itemType != '')): ?>
              <div class="sesnews_pinboard_list_comments">
                <?php echo (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment') ? $this->action('list', 'comment', 'sesadvancedcomment', array('type' => $itemType, 'id' => $item_id,'page'=>'')) : $this->action("list", "comment", "sesbasic", array("item_type" => $itemType, "item_id" => $item_id,"widget_identity"=>$randonNumber))); ?></div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</li>
