<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: thought-popup.tpl  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesthought/externals/styles/styles.css'); ?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<div class="sesthought_item_view_popup sesbasic_bxs">
	<div class="sesbasic_clearfix _details">
  	<div class="_photo">
      <?php 
        $item = $this->thoughts;
        echo $this->htmlLink($item->getOwner()->getHref(), $this->itemPhoto($item->getOwner(), 'thumb.icon', $item->getOwner()->getTitle()), array()) 
      ?>
    </div>
    <div class="_info">
    	<p class="name"><a href="<?php echo $item->getOwner()->getHref(); ?>"><?php echo $item->getOwner()->getTitle(); ?></a></p>
      <p class="sesbasic_text_light _date">
      	<span><?php echo $this->translate("Posted")?> <?php echo $this->timestamp(strtotime($item->creation_date)) ?></span>
        <span>|</span>
        <span>
          <i class="sesbasic_icon_like_o"></i>
          <span><?php echo $this->translate(array('%s Like', '%s Likes', $item->like_count), $this->locale()->toNumber($item->like_count)) ?></span>
        </span>
        <span>
          <i class="sesbasic_icon_comment_o"></i>
          <span><?php echo $this->translate(array('%s Comment', '%s Comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?></span>
        </span>
        <span>
          <i class="sesbasic_icon_view"></i>
          <span><?php echo $this->translate(array('%s View', '%s Views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?></span>
        </span>
        <?php if($item->category_id && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.enablecategory', 1)) { ?>
          <span>|</span>
          <span>
          	<i class="far fa-folder-open"></i>
            <span>
              <?php $category = Engine_Api::_()->getItem('sesthought_category', $item->category_id); ?>
              <a href="<?php echo $this->url(array('action' => 'index'), 'sesthought_general').'?category_id='.$item->category_id; ?>"><?php echo $category->category_name; ?></a>
              <?php if($item->subcat_id) { ?>
                &nbsp;&raquo;
                <?php $subcategory = Engine_Api::_()->getItem('sesthought_category', $item->subcat_id); ?>
                <a href="<?php echo $this->url(array('action' => 'index'), 'sesthought_general').'?category_id='.$item->category_id.'&subcat_id='.$item->subcat_id; ?>"><?php echo $subcategory->category_name; ?></a>
              <?php } ?>
              <?php if($item->subsubcat_id) { ?>
                &nbsp;&raquo;
                <?php $subsubcategory = Engine_Api::_()->getItem('sesthought_category', $item->subsubcat_id); ?>
                <a href="<?php echo $this->url(array('action' => 'index'), 'sesthought_general').'?category_id='.$item->category_id.'&subcat_id='.$item->subcat_id.'&subsubcat_id='.$item->subsubcat_id; ?>"><?php echo $subsubcategory->category_name; ?></a>
              <?php } ?>
            </span>
          </span>
        <?php } ?>
        <?php if($this->viewer_id) { ?>
          <div class="_options">
            <span class="_options_toggle fa fa-ellipsis-v sesbasic_text_light"></span>  
            <div class="_options_pulldown">
              <?php if($this->viewer_id && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.allowshare', 1)):?>
                <a href="javascript:void(0);" class="sesbasic_icon_share" onclick="sesthoughtSmoothBox('<?php echo $this->escape($this->url(array("module" => "activity","controller" => "index","action" => "share", "type" => $item->getType(), "id" => $item->getIdentity(), "format" => "smoothbox"), 'default', true)); ?>'); return false;"><?php echo $this->translate('Share');?></a>
              <?php endif;?>
              <?php if($this->viewer_id && $this->viewer_id != $item->owner_id && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.allowreport', 1)):?>
                <a href="<?php echo $this->url(array("module" => "core","controller" => "report","action" => "create", 'subject' => $item),'default', true);?>" class="smoothbox sesbasic_icon_report"><?php echo $this->translate('Report');?></a>
              <?php endif;?>
                <?php if($this->actionparam == 'manage' && $this->canEdit ): ?>
                <?php echo $this->htmlLink(array('route' => 'sesthought_specific', 'action' => 'edit', 'thought_id' => $item->getIdentity()), $this->translate('Edit Thought'), array(
                  'class' => 'buttonlink sessmoothbox sesbasic_icon_edit'
                )) ?>
                <a href="javascript:void(0);" class="buttonlink sesbasic_icon_delete" onclick="sesthoughtSmoothBox('<?php echo $this->escape($this->url(array("action" => "delete", 'thought_id'=> $item->getIdentity(), "format" => "smoothbox"), 'sesthought_specific', true)); ?>'); return false;"><?php echo $this->translate('Delete');?></a>
              <?php endif;?>
            </div>
          </div>
        <?php } ?>
      </p>
    </div>
  </div>
  <div class="_cont sesthought_feed_thought">
    <?php if(!empty($item->photo_id)) { ?>
      <div class="sesthought_img"><?php echo $this->itemPhoto($item, 'thumb.main') ?></div>
    <?php } ?>
    <?php if(!empty($item->thoughttitle)) { ?>
      <h2 class="sesthought_title">
        <?php echo $item->thoughttitle; ?>
      </h2>
    <?php } ?>
    <div class="sesthought_thought">
      <?php echo nl2br($item->title); ?>
    </div>
    <?php if($item->source) { ?>
      <div class="sesbasic_text_light sesthought_thought_src">&mdash; <?php echo $item->source; ?></div>
    <?php } ?>
    <div class="_tags">
      <?php if (engine_count($this->thoughtTags )):?>
        <div class="_tags">
          <?php foreach ($this->thoughtTags as $tag): ?>
            <a href='javascript:void(0);' onclick='javascript:tagAction(<?php echo $tag->getTag()->tag_id; ?>);'>#<?php echo $tag->getTag()->text?></a>&nbsp;
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="_social sesthought_social_btns sesbasic_clearfix">
    <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.allowshare', 1)):?>
      <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => 0, 'socialshare_icon_limit' => 2)); ?>
    <?php endif;?>
    <?php $viewer = Engine_Api::_()->user()->getViewer();?>
    <?php $canComment = Engine_Api::_()->authorization()->isAllowed('sesthought_thought', $viewer, 'create');?>
    <?php if($canComment):?>
      <?php $likeStatus = Engine_Api::_()->sesthought()->getLikeStatus($item->thought_id,$item->getType()); ?>
      <a href="javascript:;" data-type="like_view" data-url="<?php echo $item->thought_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesthought_like_<?php echo $item->thought_id ?> sesthought_likefavfollow <?php echo ($likeStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count;?></span></a>
    <?php endif;?>
    
  </div>
  <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')){ ?>
    <div class="_comments">
      <?php echo $this->action("list", "comment", "sesadvancedcomment", array("type" => $item->getType(), "id" => $item->getIdentity()));  ?>
    </div>
  <?php } else { ?>
    <div class="_comments">
      <?php echo $this->action("list", "comment", "core", array("type" => "sesthought_thought", "id" => $item->getIdentity())); ?>
    </div>
  <?php } ?>
</div>
<script type="text/javascript">
 	
	function tagAction(tag_id){
		window.location.href = '<?php echo $this->url(array("action"=>"index"),"sesthought_general",true); ?>'+'?tag_id='+tag_id;
	}
  
	scriptJquery(document).on('click','._options_toggle',function(){
		if(scriptJquery(this).hasClass('open')){
			scriptJquery(this).removeClass('open');
		}else{
			scriptJquery(this).addClass('open');
		}
			return false;
	});
	function sesthoughtSmoothBox(url) {
  Smoothbox.open(url);
  parent.Smoothbox.close;
}
</script>
