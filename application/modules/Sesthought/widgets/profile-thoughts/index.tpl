<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<?php $viewerId = $viewer->getIdentity();?>
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

  en4.core.runonce.add(function() {

    <?php if( !$this->renderOne ): ?>
    
      var anchor = scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').parent();
      
      document.getElementById('sesthought_profile_previous').style.display = '<?php echo ( $this->paginator->getCurrentPageNumber() == 1 ? 'none' : '' ) ?>';
      document.getElementById('sesthought_profile_next').style.display = '<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' ) ?>';

      scriptJquery('#sesthought_profile_previous').off('click').on('click', function() {
        en4.core.request.send(scriptJquery.ajax({
          url : en4.core.baseUrl + 'widget/index/content_id/' + <?php echo sprintf('%d', $this->identity) ?>,
          data : {
            format : 'html',
            subject : en4.core.subject.guid,
            is_ajax:1,
            page : <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() - 1) ?>,
            params:'<?php echo json_encode($this->allParams); ?>',
          }
        }), {
          'element' : anchor
        });
        pinboardLayout_<?php echo $randonNumber ?>();
      });
      
      scriptJquery('#sesthought_profile_next').off('click').on('click', function(){
        en4.core.request.send(scriptJquery.ajax({
          url : en4.core.baseUrl + 'widget/index/content_id/' + <?php echo sprintf('%d', $this->identity) ?>,
          data : {
            format : 'html',
            subject : en4.core.subject.guid,
            is_ajax:1,
            page : <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>,
            params:'<?php echo json_encode($this->allParams); ?>',
          }
        }), {
          'element' : anchor
        });
        pinboardLayout_<?php echo $randonNumber ?>();
      });
    <?php endif; ?>

  });
</script>


<?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
	<div class="sesbasic_bxs sesbasic_clearfix">
    <ul class="prelative sesthoughts_listing sesbasic_pinboard_<?php echo $randonNumber ; ?>" style="min-height:50px;" id="tabbed-widget_<?php echo $randonNumber; ?>" >
      <?php foreach( $this->paginator as $item ): ?>
      
        <li class="sesthoughts_list_item new_image_pinboard_<?php echo $randonNumber; ?>" >
        	<section>
          	<header class="sesbasic_clearfix">
            	<div class="_owner_thumb">
              	<?php echo $this->htmlLink($item->getOwner()->getHref(), $this->itemPhoto($item->getOwner(), 'thumb.icon', $item->getOwner()->getTitle()), array('class' => '')) ?>
              </div>
              <?php if(is_array($this->allParams['stats']) && ( engine_in_array('postedby', $this->allParams['stats']) || engine_in_array('posteddate', $this->allParams['stats']))): ?>
                <div class="_owner_info">
                  <?php if(engine_in_array('postedby', $this->allParams['stats'])): ?>
                    <div class="_owner_name"><?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?></div>
                  <?php endif; ?>
                  <?php if(is_array($this->allParams['stats']) && engine_in_array('posteddate', $this->allParams['stats'])) : ?>
                    <div class="sesbasic_text_light _date">
                      <?php echo $this->translate('Posted');?>
                      <?php echo $this->timestamp(strtotime($item->creation_date)) ?>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </header>
            <div class='_content'>
              <div id="sesthought_description_content_<?php echo $item->getIdentity(); ?>" class='sesthought_feed_thought _des'>
                <?php if($item->mediatype == 1 && !empty($item->photo_id)) { ?>
                  <div class="sesthought_img"><?php echo $this->itemPhoto($item, 'thumb.main') ?></div>
                <?php } else if($item->mediatype == 2 && $item->code) { ?>
                  <div class="sesthought_video"><?php echo $item->code; ?></div>
                <?php } ?>
                <?php if(engine_in_array('title', $this->allParams['stats']) && !empty($item->thoughttitle)) { ?>
                  <div class="sesthought_title">  
                    <?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.show', 0)) { ?>
                      <a href="<?php echo $item->getHref(); ?>"><?php echo $item->thoughttitle; ?></a>
                    <?php } else { ?>
                      <a data-url='sesthought/index/thought-popup/thought_id/<?php echo $item->getIdentity(); ?>' class="sessmoothbox" href="javascript:;"><?php echo $item->thoughttitle; ?></a>
                    <?php } ?>
                  </div>
                <?php } ?>
                <div class="sesthought_thought">
                  <?php echo nl2br($item->title); ?>
                </div>
                <?php if(is_array($this->allParams['stats']) && engine_in_array('source', $this->allParams['stats']) && $item->source) { ?>
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
              <?php if(is_array($this->allParams['stats']) && (engine_in_array('likecount', $this->allParams['stats']) || engine_in_array('commentcount', $this->allParams['stats']) || engine_in_array('viewcount', $this->allParams['stats']) || engine_in_array('category', $this->allParams['stats']) || engine_in_array('permalink', $this->allParams['stats']))): ?>
                <div class="_stats sesbasic_text_light">
                  <?php if(engine_in_array('likecount', $this->allParams['stats'])) { ?>
                    <span title="<?php echo $this->translate(array('%s Like', '%s Likes', $item->like_count), $this->locale()->toNumber($item->like_count)) ?>">
                      <i class="sesbasic_icon_like_o"></i>
                      <span><?php echo $this->locale()->toNumber($item->like_count) ?></span>
                    </span>
                  <?php } ?>
                  <?php if(is_array($this->allParams['stats']) && engine_in_array('commentcount', $this->allParams['stats'])) { ?>
                    <span title="<?php echo $this->translate(array('%s Comment', '%s Comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?>">
                      <i class="sesbasic_icon_comment_o"></i>
                      <span><?php echo $this->locale()->toNumber($item->comment_count) ?></span>
                    </span>
                  <?php } ?>
                  <?php if(is_array($this->allParams['stats']) && engine_in_array('viewcount', $this->allParams['stats'])) { ?>
                    <span title="<?php echo $this->translate(array('%s View', '%s Views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>">
                      <i class="sesbasic_icon_view"></i>
                      <span><?php echo $this->locale()->toNumber($item->view_count) ?></span>
                    </span>
                  <?php } ?>
                  <?php if(engine_in_array('category', $this->allParams['stats']) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.enablecategory', 1) && $item->category_id) { ?>
                    <span> 
                      <?php $category = Engine_Api::_()->getItem('sesthought_category', $item->category_id); ?>
                      -&nbsp;<a href="<?php echo $this->url(array('action' => 'index'), 'sesthought_general').'?category_id='.$item->category_id; ?>"><?php echo $category->category_name; ?></a>
                    </span>
                  <?php } ?>
                  <?php if(is_array($this->allParams['stats']) && engine_in_array('permalink', $this->allParams['stats'])) { ?>
                    <span>-&nbsp;
                      <?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.show', 0)) { ?>
                        <a href="<?php echo $item->getHref(); ?>"><?php echo $this->translate('Read More'); ?></a>
                      <?php } else { ?>
                        <a data-url='sesthought/index/thought-popup/thought_id/<?php echo $item->getIdentity(); ?>' class="sessmoothbox" href="javascript:;"><?php echo $this->translate('Read More'); ?></a>
                      <?php } ?>
                    </span>
                  <?php } ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="_footer sesbasic_clearfix sesthought_social_btns">
              <?php if(is_array($this->allParams['stats']) && engine_in_array('socialSharing', $this->allParams['stats']) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.allowshare', 1)):?>
                <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->allParams['socialshare_enable_plusicon'], 'socialshare_icon_limit' => $this->allParams['socialshare_icon_limit'])); ?>
              <?php endif;?>
              <?php $canComment = Engine_Api::_()->authorization()->isAllowed('sesthought_thought', $viewer, 'create');?>
              <?php if(is_array($this->allParams['stats']) && engine_in_array('likebutton', $this->allParams['stats']) && $canComment):?>
                <?php $likeStatus = Engine_Api::_()->sesthought()->getLikeStatus($item->thought_id,$item->getType()); ?>
                <a href="javascript:;" data-type="like_view" data-url="<?php echo $item->thought_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesthought_like_<?php echo $item->thought_id ?> sesthought_likefavfollow <?php echo ($likeStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count;?></span></a>
              <?php endif;?>
            </div>
          </section>
        </li>
      <?php endforeach; ?>
    </ul>
    <div>
      <div id="sesthought_profile_previous" class="paginator_previous">
        <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Previous'), array(
          'onclick' => '',
          'class' => 'buttonlink icon_previous'
        )); ?>
      </div>
      <div id="sesthought_profile_next" class="paginator_next">
        <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Next'), array(
          'onclick' => '',
          'class' => 'buttonlink_right icon_next'
        )); ?>
      </div>
    </div>
  </div>

<?php else:?>
  <div class="sesbasic_tip">
    <img src="<?php echo Engine_Api::_()->sesthought()->getFileUrl(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought_thought_no_photo', 'application/modules/Sesthought/externals/images/thought-icon.png')); ?>" alt="" />
    <span>
      <?php echo $this->translate('There are no thought entry yet.'); ?>
    </span>
  </div>
<?php endif; ?>

<script type="application/javascript">
 	
	function tagAction(tag_id){
		window.location.href = '<?php echo $this->url(array("action"=>"index"),"sesthought_general",true); ?>'+'?tag_id='+tag_id;
	}
	
	scriptJquery(document).on('click', '.tab_layout_sesthought_profile_thoughts', function() {
    imageLoadedAll<?php echo $randonNumber ?>();
	});
	
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
          itemWidth: <?php echo isset($this->allParams['width']) ? str_replace(array('px','%'),array(''),$this->allParams['width']) : '300'; ?>, // Optional min width of a grid item
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

</script>
