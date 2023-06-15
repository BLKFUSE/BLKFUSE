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
		<a href="<?php echo $href; ?>" data-url = "<?php echo $item->getType() ?>" class="sesnews_thumb_img">
      <span style="background-image:url(<?php echo $imageURL; ?>);"></span>
		</a>
		<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enablesubs', 1) && isset($this->subscribebuttonActive) && Engine_Api::_()->user()->getViewer()->getIdentity() != $item->owner_id):?>
			<div class="sesnews_list_thumb_over sesnews_subscribe_btn">
				<div class="sesnews_grid_btns"> 
					<?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 ):?>
						<?php $checkSubscription = Engine_Api::_()->getDbTable('rsssubscriptions', 'sesnews')->checkSubscription(Engine_Api::_()->user()->getViewer(), $item); ?>
            <a id="<?php echo $item->getType(); ?>_unsubscribe_<?php echo $item->getIdentity(); ?>" style ='display:<?php echo $checkSubscription ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesnews_subs('<?php echo $item->getIdentity(); ?>', '<?php echo $item->getType(); ?>');" title="<?php echo $this->translate("Unsubscribe") ?>" class="sesnews_unsubsc"><i class="fa fa-times"></i> Unsubscribe</a>
            <a id="<?php echo $item->getType(); ?>_subscribe_<?php echo $item->getIdentity(); ?>" style ='display:<?php echo $checkSubscription ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesnews_subs('<?php echo $item->getIdentity(); ?>', '<?php echo $item->getType(); ?>');" title="<?php echo $this->translate("Subscribe") ?>"><i class="far fa-hand-pointer"></i> Subscribe</a>
            <input type="hidden" id="<?php echo $item->getType(); ?>_subshidden_<?php echo $item->getIdentity(); ?>" value='<?php echo $checkSubscription ? $checkSubscription : 0; ?>' />
					<?php endif;?>
				</div>
			</div>
		<?php endif;?> 
	</div>
	<div class="sesnews_list_info">
  <?php if(isset($this->titleActive)): ?>
			<div class="sesnews_list_info_title">
				<?php if(strlen($item->getTitle()) > $this->title_truncation_list):?>
					<?php $title = mb_substr($item->getTitle(),0,$this->title_truncation_list).'...';?>
					<?php echo $this->htmlLink($item->getHref(),$title,array('title'=>$item->getTitle()));?>
				<?php else: ?>
					<?php echo $this->htmlLink($item->getHref(),$item->getTitle(),array('title'=>$item->getTitle())  ) ?>
				<?php endif;?>
			</div>
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
		</div>
		<div class="sesnews_list_contant">
		<?php if(isset($this->descriptionlistActive)){ ?>
			<p class="sesnews_list_des sesbasic_text_light">
				<?php echo $item->getDescription($this->description_truncation_list);?>
			</p>
		<?php } ?>      
		</div>
  <div class="sesnews_static_list_group">
		<div class="sesnews_list_stats sesbasic_text_dark">
			<?php if(isset($this->viewActive) && isset($item->view_count)) { ?>
				<span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="fa fa-eye"></i><?php echo $item->view_count; ?></span>
			<?php } ?>
		</div>
		<?php if(isset($this->readmoreActive)):?>
			<div class="sesnews_list_readmore floatR"><a class="sesnews_animation" href="<?php echo $item->getHref();?>"><?php echo $this->translate('Read More');?> <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></div>
		<?php endif;?>
    </div>
	</div>
</li>
