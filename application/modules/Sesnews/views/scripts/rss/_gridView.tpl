<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _gridView.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<li class="col-lg-<?php echo $this->gridblock; ?> col-md-3 col-sm-6 col-12">
 <div class="sesnews_grid sesbasic_bxs <?php if((isset($this->my_news) && $this->my_news)){ ?>isoptions<?php } ?>">
  <div class="sesnews_grid_inner sesnews_thumb">
    <div class="sesnews_grid_thumb" style="height:<?php echo is_numeric($this->height_grid) ? $this->height_grid.'px' : $this->height_grid ?>;">
      <?php $href = $item->getHref();$imageURL = $photoPath;?>
      <a href="<?php echo $href; ?>" data-url = "<?php echo $item->getType() ?>" class="sesnews_thumb_img"> <span style="background-image:url(<?php echo $imageURL; ?>);"></span> </a>
	<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enablesubs', 1) && isset($this->subscribebuttonActive) && Engine_Api::_()->user()->getViewer()->getIdentity() != $item->owner_id):?>
			<div class="sesnews_list_thumb_over sesnews_subscribe_btn">
				<div class="sesnews_grid_btns"> 
					<?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 ):?>
						<?php $checkSubscription = Engine_Api::_()->getDbTable('rsssubscriptions', 'sesnews')->checkSubscription(Engine_Api::_()->user()->getViewer(), $item); ?>
            <a id="<?php echo $item->getType(); ?>_unsubscribe_<?php echo $item->getIdentity(); ?>" style ='display:<?php echo $checkSubscription ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesnews_subs('<?php echo $item->getIdentity(); ?>', '<?php echo $item->getType(); ?>');" title="<?php echo $this->translate("Unsubscribe") ?>" class="sesnews_unsubsc"><i class="fa fa-times"></i></a>
            <a id="<?php echo $item->getType(); ?>_subscribe_<?php echo $item->getIdentity(); ?>" style ='display:<?php echo $checkSubscription ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesnews_subs('<?php echo $item->getIdentity(); ?>', '<?php echo $item->getType(); ?>');" title="<?php echo $this->translate("Subscribe") ?>"><i class="far fa-hand-pointer"></i></a>
            <input type="hidden" id="<?php echo $item->getType(); ?>_subshidden_<?php echo $item->getIdentity(); ?>" value='<?php echo $checkSubscription ? $checkSubscription : 0; ?>' />
					<?php endif;?>
				</div>
			</div>
		<?php endif;?> 
      <?php if(isset($this->categoryActive)){ ?>
      <?php if($item->category_id != '' && intval($item->category_id) && !is_null($item->category_id)):?>
      <?php $categoryItem = Engine_Api::_()->getItem('sesnews_category', $item->category_id);?>
      <?php if($categoryItem):?>
      <div class="sesnews_grid_memta_title">
        <?php $categoryItem = Engine_Api::_()->getItem('sesnews_category', $item->category_id);?>
        <?php if($categoryItem):?>
        <span> <a href="<?php echo $categoryItem->getHref(); ?>"><?php echo $categoryItem->category_name; ?></a> </span>
        <?php endif;?>
      </div>
      <?php endif;?>
      <?php endif;?>
      <?php } ?>
    </div>
    <div class="sesnews_grid_info clear clearfix sesbm">
      <?php if(isset($this->titleActive) ){ ?>
      <div class="sesnews_grid_info_title">
        <?php if(strlen($item->getTitle()) > $this->title_truncation_grid):?>
        <?php $title = mb_substr($item->getTitle(),0,$this->title_truncation_grid).'...';?>
        <?php echo $this->htmlLink($item->getHref(),$title,array('title'=>$item->getTitle()) ) ?>
        <?php else: ?>
        <?php echo $this->htmlLink($item->getHref(),$item->getTitle(),array('title'=>$item->getTitle())  ) ?>
        <?php endif; ?>
      </div>
      <?php } ?>
      <div class="sesnews_grid_meta_block">
        <?php if(isset($this->byActive)){ ?>
        <div class="sesnews_list_stats sesbasic_text_light"> <span>
          <?php $owner = $item->getOwner(); ?>
          <?php echo $this->htmlLink($item->getOwner()->getParent(), $this->itemPhoto($item->getOwner()->getParent(), 'thumb.icon')); ?> <?php echo $this->translate("by") ?> <?php echo $this->htmlLink($owner->getHref(),$owner->getTitle() ) ?>&nbsp;| </span> </div>
        <?php } ?>
        <?php if(isset($this->creationDateActive)): ?>
        <div class="sesnews_list_stats sesbasic_text_light"> <span><i class=" far fa-clock"></i> 						
            <?php if($item->publish_date): ?>
              <?php echo date('M d, Y',strtotime($item->creation_date));?>
						<?php else: ?>
              <?php echo date('M d, Y',strtotime($item->creation_date));?>
						<?php endif; ?>&nbsp;|</span> </div>
        <?php endif;?>
      </div>
    </div>
    <div class="sesnews_grid_hover_block">
      <div class="sesnews_grid_info_hover_title">
        <?php if(isset($this->titleActive)): ?>
        <?php if(strlen($item->getTitle()) > $this->title_truncation_grid):?>
        <?php $title = mb_substr($item->getTitle(),0,$this->title_truncation_grid).'...';?>
        <?php echo $this->htmlLink($item->getHref(),$title,array('title'=>$item->getTitle()) ) ?>
        <?php else: ?>
        <?php echo $this->htmlLink($item->getHref(),$item->getTitle(),array('title'=>$item->getTitle())  ) ?>
        <?php endif; ?>
        <span></span>
        <?php endif;?>
      </div>
      <div class="sesnews_grid_meta_block">
        <?php if(isset($this->byActive)){ ?>
        <div class="sesnews_list_stats sesbasic_text_light"> <span>
          <?php $owner = $item->getOwner(); ?>
          <?php echo $this->htmlLink($item->getOwner()->getParent(), $this->itemPhoto($item->getOwner()->getParent(), 'thumb.icon')); ?> <?php echo $this->translate("by") ?> <?php echo $this->htmlLink($owner->getHref(),$owner->getTitle() ) ?>&nbsp;| </span> </div>
        <?php } ?>
        <?php if(isset($this->creationDateActive)): ?>
        <div class="sesnews_list_stats sesbasic_text_light"> <span><i class=" far fa-clock"></i> <?php echo date('M d',strtotime($item->creation_date));?>&nbsp;|</span> </div>
        <?php endif;?>
      </div>
      <?php  if(isset($this->descriptiongridActive)){?>
      <div class="sesnews_grid_des clear"> <?php echo $item->getDescription($this->description_truncation_grid);?> </div>
      <?php } ?>
      <div class="sesnews_grid_hover_block_footer">
        <div class="sesnews_list_stats sesbasic_text_light">
          <?php if(isset($this->viewActive) && isset($item->view_count)) { ?>
          <span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="fa fa-eye"></i><?php echo $item->view_count; ?></span>
          <?php } ?>
        </div>
        <?php if(isset($this->readmoreActive)):?>
        <div class="sesnews_grid_read_btn floatR"><a href="<?php echo $href; ?>"><?php echo $this->translate('Read More...');?></a></div>
        <?php endif;?>
      </div>
    </div>
  </div>
 </div>
</li>
