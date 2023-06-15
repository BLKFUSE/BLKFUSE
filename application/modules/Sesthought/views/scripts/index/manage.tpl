<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manage.tpl  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesthought/externals/scripts/core.js'); ?>
<?php 
  $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesthought/externals/styles/styles.css');
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/wookmark.min.js');
?>
<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')) { ?>
  <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/mention/jquery.mentionsInput.css'); ?>    
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl .'application/modules/Sesbasic/externals/scripts/mention/underscore-min.js'); ?>
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl .'application/modules/Sesbasic/externals/scripts/mention/jquery.mentionsInput.js'); ?>
<?php } ?>
<?php $randonNumber = 2000; ?>

<script type="text/javascript">
  var pageAction =function(page){
    document.getElementById('page').value = page;
    scriptJquery('#filter_form').trigger('submit');
  }
</script>
  <?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
    <ul class="prelative sesthoughts_listing sesbasic_bxs sesbasic_pinboard_<?php echo $randonNumber ; ?>" style="min-height:50px;" id="tabbed-widget_<?php echo $randonNumber; ?>" >
      <?php foreach( $this->paginator as $item ): ?>
        <li class="sesthoughts_list_item new_image_pinboard_<?php echo $randonNumber; ?>" >
        	<section>
          	<header class="sesbasic_clearfix">
            	<div class="_owner_thumb">
              	<?php echo $this->htmlLink($item->getOwner()->getHref(), $this->itemPhoto($item->getOwner(), 'thumb.icon', $item->getOwner()->getTitle()), array('class' => '')) ?>
              </div>
              <div class="_owner_info">
                  <div class="_owner_name"><?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?></div>
                  <div class="sesbasic_text_light _date">
                    <?php echo $this->translate('Posted');?>
                    <?php echo $this->timestamp(strtotime($item->creation_date)) ?>
                  </div>
              </div>
              	<div class="_options">
               		<span class="_options_toggle fa fa-ellipsis-v sesbasic_text_light"></span>
                	<div class="_options_pulldown">
                    <?php echo $this->htmlLink(array(
                      'action' => 'edit',
                      'thought_id' => $item->getIdentity(),
                      'route' => 'sesthought_specific',
                      'reset' => true,
                    ), $this->translate('Edit'), array(
                      'class' => 'icon_thought_edit sessmoothbox',
                    )) ?>
                    <?php
                    echo $this->htmlLink(array('route' => 'default', 'module' => 'sesthought', 'controller' => 'index', 'action' => 'delete', 'thought_id' => $item->getIdentity(), 'format' => 'smoothbox'), $this->translate('Delete'), array(
                      'class' => 'smoothbox icon_thought_delete'
                    ));
                    ?>
                	</div>
            		</div>
            </header>
            <div class='_content'>
              <div id="sesthought_description_content_<?php echo $item->getIdentity(); ?>" class='sesthought_feed_thought _des'>
                <?php if($item->mediatype == 1 && !empty($item->photo_id)) { ?>
                  <div class="sesthought_img"><?php echo $this->itemPhoto($item, 'thumb.main') ?></div>
                <?php } else if($item->mediatype == 2 && $item->code) { ?>
                  <div class="sesthought_video"><?php echo $item->code; ?></div>
                <?php } ?>
                <?php if(!empty($item->thoughttitle)) { ?>
                  <div class="sesthought_title">  
                    <?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.show', 0)) { ?>
                      <a href="<?php echo $item->getHref(); ?>"><?php echo $item->thoughttitle; ?></a>
                    <?php } else { ?>
                      <a data-url='sesthought/index/thought-popup/thought_id/<?php echo $item->getIdentity(); ?>/actionparam/manage/' class="sessmoothbox" href="javascript:;"><?php echo $item->thoughttitle; ?></a>
                    <?php } ?>
                  </div>
                <?php } ?>
                <div class="sesthought_thought">
                  <?php echo nl2br($item->title); ?>
                </div>
                <?php if($item->source) { ?>
                  <div class="sesbasic_text_light sesthought_thought_src">&mdash; <?php echo $item->source; ?></div>
                <?php } ?>
              </div>
              <?php $tags = $item->tags()->getTagMaps(); ?>
              <?php if (engine_count($tags)):?>
                <div class="_tags">
                  <?php foreach ($tags as $tag): ?>
                    <a href='javascript:void(0);' onclick='javascript:tagAction(<?php echo $tag->getTag()->tag_id; ?>);'>#<?php echo $tag->getTag()->text?></a>&nbsp;
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
              <div class="_stats sesbasic_text_light">
                <span title="<?php echo $this->translate(array('%s Like', '%s Likes', $item->like_count), $this->locale()->toNumber($item->like_count)) ?>">
                  <i class="sesbasic_icon_like_o"></i>
                  <span><?php echo $this->locale()->toNumber($item->like_count) ?></span>
                </span>
                <span title="<?php echo $this->translate(array('%s Comment', '%s Comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?>">
                  <i class="sesbasic_icon_comment_o"></i>
                  <span><?php echo $this->locale()->toNumber($item->comment_count) ?></span>
                </span>
                <span title="<?php echo $this->translate(array('%s View', '%s Views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>">
                  <i class="sesbasic_icon_view"></i>
                  <span><?php echo $this->locale()->toNumber($item->view_count) ?></span>
                </span>
                <?php if($item->category_id && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.enablecategory', 1)) { ?>
                  <span>-&nbsp;
                    <?php $category = Engine_Api::_()->getItem('sesthought_category', $item->category_id); ?>
                    <a href="<?php echo $this->url(array('action' => 'index'), 'sesthought_general').'?category_id='.$item->category_id; ?>"><?php echo $category->category_name; ?></a>
                  </span>
                <?php } ?>
                <span>-&nbsp;
                  <?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.show', 0)) { ?>
                    <a href="<?php echo $item->getHref(); ?>"><?php echo $this->translate('Read More'); ?></a>
                  <?php } else { ?>
                    <a data-url='sesthought/index/thought-popup/thought_id/<?php echo $item->getIdentity(); ?>/actionparam/manage/' class="sessmoothbox" href="javascript:;"><?php echo $this->translate('Read More'); ?></a>
                  <?php } ?>
                </span>
              </div>
            </div>
            <div class="_footer sesbasic_clearfix sesthought_social_btns">
              <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.allowshare', 1)):?>
                <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => 0, 'socialshare_icon_limit' => 2)); ?>
              <?php endif;?>
              <?php $viewer = Engine_Api::_()->user()->getViewer(); ?>
              <?php $canComment = Engine_Api::_()->authorization()->isAllowed('sesthought_thought', $viewer, 'create');?>
              <?php if($canComment):?>
                <?php $likeStatus = Engine_Api::_()->sesthought()->getLikeStatus($item->thought_id,$item->getType()); ?>
                <a href="javascript:;" data-type="like_view" data-url="<?php echo $item->thought_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesthought_like_<?php echo $item->thought_id ?> sesthought_likefavfollow <?php echo ($likeStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count;?></span></a>
              <?php endif;?>
            </div>
          </section>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php elseif($this->search): ?>
    <div class="tip">
      <span>
        <?php echo $this->translate('You do not have any thought entries that match your search criteria.');?>
      </span>
    </div>
  <?php else: ?>
    <div class="tip">
      <span>
        <?php echo $this->translate('You do not have created any thought.');?>
        <?php if( $this->canCreate ): ?>
          <?php echo $this->translate('Get started by %1$swriting%2$s a new thought.', '<a class="smoothbox" href="'.$this->url(array('action' => 'create'), 'sesthought_general').'">', '</a>'); ?>
        <?php endif; ?>
      </span>
    </div>
  <?php endif; ?>

  <?php echo $this->paginationControl($this->paginator, null, null, array(
    'pageAsQuery' => true,
    'query' => $this->formValues,
    //'params' => $this->formValues,
  )); ?>

<script type="application/javascript">
 	
	function tagAction(tag_id){
		window.location.href = '<?php echo $this->url(array("action"=>"manage"),"sesthought_general",true); ?>'+'?tag_id='+tag_id;
	}
	
  scriptJquery(document).on('click', '.sesthought_expand', function() {

    scriptJquery(this).parent().find('._des').css('max-height','none');
    //scriptJquery(this).hide();
    scriptJquery(this).closest('.sesthoughts_list_item').addClass('sesthought_content_open');
    imageLoadedAll<?php echo $randonNumber ?>(1);
  });

  var wookmark = undefined;
  var wookmark<?php echo $randonNumber ?>;
  function pinboardLayout_<?php echo $randonNumber ?>(force) {
    scriptJquery('.new_image_pinboard_<?php echo $randonNumber; ?>').css('display','none');
    imageLoadedAll<?php echo $randonNumber ?>(force);
  }
  
  function imageLoadedAll<?php echo $randonNumber ?>(force) {
  
    scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('sesbasic_pinboard_<?php echo $randonNumber; ?>');
    scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('sesbasic_pinboard');
    if (typeof wookmark<?php echo $randonNumber ?> == 'undefined' || typeof force != 'undefined') {
      (function() {
        function getWindowWidth() {
          return Math.max(document.documentElement.clientWidth, window.innerWidth || 0)
        }
        wookmark<?php echo $randonNumber ?> = new Wookmark('.sesbasic_pinboard_<?php echo $randonNumber; ?>', {
          itemWidth: <?php echo isset($this->allParams['width']) ? str_replace(array('px','%'),array(''),$this->allParams['width']) : '250'; ?>, // Optional min width of a grid item
          outerOffset: 0, // Optional the distance from grid to parent
          align:'left',
          flexibleWidth: function () {
            // Return a maximum width depending on the viewport
            return getWindowWidth() < 1024 ? '100%' : '40%';
          }
        });
      })();
    } else {
      wookmark<?php echo $randonNumber ?>.initItems();
      wookmark<?php echo $randonNumber ?>.layout(true);
    }
  }
  
  scriptJquery(document).ready(function(){
    pinboardLayout_<?php echo $randonNumber ?>();
  });
	
	scriptJquery(document).on('click','._options_toggle',function(){
		if(scriptJquery(this).hasClass('open')){
			scriptJquery(this).removeClass('open');
		}else{
			scriptJquery(this).addClass('open');
		}
			return false;
	});
	
  scriptJquery('.core_main_thought').parent().addClass('active');
</script>
