<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: view.tpl  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesthought/externals/styles/styles.css'); ?>
<div class="sesthoughts_item_view sesbasic_bxs">
	<header class="sesbasic_clearfix">	
    <div class="_photo">
    	<?php echo $this->htmlLink($this->owner->getHref(), $this->itemPhoto($this->owner, 'thumb.icon', $this->owner->getTitle())) ?>
    </div>
    <div class="_info">
      <p class="name"><?php echo $this->htmlLink($this->owner->getHref(), $this->owner->getTitle()) ?></p>
      <p class="sesbasic_text_light _date">
      	<span><?php echo $this->translate("Posted")?> <?php echo $this->timestamp($this->thought->creation_date) ?></span>
        <span>|</span>
        <span>
          <i class="sesbasic_icon_like_o"></i>
          <span> <?php echo $this->translate(array('%s like', '%s likes', $this->thought->like_count), $this->locale()->toNumber($this->thought->like_count)) ?></span>
        </span>
        <span>
          <i class="sesbasic_icon_comment_o"></i>
          <span><?php echo $this->translate(array('%s comment', '%s comments', $this->thought->comment_count), $this->locale()->toNumber($this->thought->comment_count)) ?></span>
        </span>
        <span>
          <i class="sesbasic_icon_view"></i>
          <span><?php echo $this->translate(array('%s view', '%s views', $this->thought->view_count), $this->locale()->toNumber($this->thought->view_count)) ?></span>
        </span>
        <?php if($this->thought->category_id && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.enablecategory', 1)) { ?>
          <span>|</span>
          <span>
          	<i class="far fa-folder-open"></i>
            <span>
              <?php $category = Engine_Api::_()->getItem('sesthought_category', $this->thought->category_id); ?>
              <a href="<?php echo $this->url(array('action' => 'index'), 'sesthought_general').'?category_id='.$this->thought->category_id; ?>"><?php echo $category->category_name; ?></a>
              <?php if($this->thought->subcat_id) { ?>
                &nbsp;&raquo;
                <?php $subcategory = Engine_Api::_()->getItem('sesthought_category', $this->thought->subcat_id); ?>
                <a href="<?php echo $this->url(array('action' => 'index'), 'sesthought_general').'?category_id='.$this->thought->category_id.'&subcat_id='.$this->thought->subcat_id; ?>"><?php echo $subcategory->category_name; ?></a>
              <?php } ?>
              <?php if($this->thought->subsubcat_id) { ?>
                &nbsp;&raquo;
                <?php $subsubcategory = Engine_Api::_()->getItem('sesthought_category', $this->thought->subsubcat_id); ?>
                <a href="<?php echo $this->url(array('action' => 'index'), 'sesthought_general').'?category_id='.$this->thought->category_id.'&subcat_id='.$this->thought->subcat_id.'&subsubcat_id='.$this->thought->subsubcat_id; ?>"><?php echo $subsubcategory->category_name; ?></a>
              <?php } ?>
            </span>
          </span>
        <?php } ?>
      </p>  
    </div>
  </header>
  <section class="_cont">
		<div class="sesthought_feed_thought">
      <?php if(engine_in_array($this->thought->mediatype, array(1, 3)) && !empty($this->thought->photo_id)) { ?>
        <div class="sesthought_img"><?php echo $this->itemPhoto($this->thought, 'thumb.main') ?></div>
      <?php } else if($this->thought->mediatype == 2 && $this->thought->code) { ?>
        <div class="sesthought_img"><?php echo $this->thought->code; ?></div>
      <?php } ?>
      <?php if(!empty($this->thought->thoughttitle)) { ?>
        <h2 class="sesthought_title">
          <?php echo $this->thought->thoughttitle; ?>
        </h2>
      <?php } ?>
    	<p class="sesthought_thought"><?php echo nl2br($this->thought->title) ?></p>
      <?php if($this->thought->source) { ?>
        <p class="sesbasic_text_light sesthought_thought_src">&mdash; <?php echo $this->thought->source; ?></p>
      <?php } ?>
      <?php if (engine_count($this->thoughtTags )):?>
        <div class="_tags">
          <?php foreach ($this->thoughtTags as $tag): ?>
            <a href='javascript:void(0);' onclick='javascript:tagAction(<?php echo $tag->getTag()->tag_id; ?>);'>#<?php echo $tag->getTag()->text?></a>&nbsp;
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>  
  </section>
  <div class="_footer sesbasic_clearfix">
		<div class="_social sesthought_social_btns">
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.allowshare', 1)):?>
        <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->thought, 'socialshare_enable_plusicon' => 0, 'socialshare_icon_limit' => 2)); ?>
      <?php endif;?>
      <?php $canComment = Engine_Api::_()->authorization()->isAllowed('sesthought_thought', $viewer, 'create');?>
      <?php if($canComment):?>
        <?php $likeStatus = Engine_Api::_()->sesthought()->getLikeStatus($this->thought->thought_id,$this->thought->getType()); ?>
        <a href="javascript:;" data-type="like_view" data-url="<?php echo $this->thought->thought_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesthought_like_<?php echo $this->thought->thought_id ?> sesthought_likefavfollow <?php echo ($likeStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-thumbs-up"></i><span><?php echo $this->thought->like_count;?></span></a>
      <?php endif;?>
    </div>
    <div class="_options">
      <?php if($this->viewer_id && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.allowshare', 1)):?>
        <a href="<?php echo $this->url(array("module" => "activity","controller" => "index","action" => "share", "type" => $this->thought->getType(), "id" => $this->thought->getIdentity(), "format" => "smoothbox"), 'default', true);?>" class="smoothbox sesbasic_icon_share"><?php echo $this->translate('Share');?></a>
      <?php endif;?>
      <?php if($this->viewer_id && $this->viewer_id != $this->thought->owner_id && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.allowreport', 1)):?>
        <a href="<?php echo $this->url(array("module" => "core","controller" => "report","action" => "create", 'subject' => $this->thought->getGuid()),'default', true);?>" class="smoothbox sesbasic_icon_report"><?php echo $this->translate('Report');?></a>
      <?php endif;?>
        <?php if( $this->mine || $this->canEdit ): ?>
        <?php echo $this->htmlLink(array('route' => 'sesthought_specific', 'action' => 'edit', 'thought_id' => $this->thought->thought_id), $this->translate('Edit Thought'), array(
          'class' => 'sessmoothbox sesbasic_icon_edit'
        )) ?>
        <?php echo $this->htmlLink(array('route' => 'sesthought_specific', 'action' => 'delete', 'thought_id' => $this->thought->thought_id, 'format' => 'smoothbox'), $this->translate('Delete Thought'), array(
          'class' => 'smoothbox sesbasic_icon_delete'
        )) ?>
      <?php endif;?>
    </div>
  </div>
</div>
<script type="thought/javascript">
	function tagAction(tag_id){
		window.location.href = '<?php echo $this->url(array("action"=>"index"),"sesthought_general",true); ?>'+'?tag_id='+tag_id;
	}
  scriptJquery('.core_main_sesthought').parent().addClass('active');
</script>
