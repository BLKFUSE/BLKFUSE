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
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesthought/externals/scripts/core.js'); ?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesthought/externals/styles/styles.css'); ?>

<?php if($this->allParams['viewType'] == 'list') { ?>
  <ul class="sesthoughts_sidebar_listing sesbasic_bxs">
    <?php foreach( $this->thoughts as $item ): ?>
      <li class="sesbasic_clearfix">
        <?php if($item->mediatype == 1 && !empty($item->photo_id)) { ?>
          <div class="sesthought_img"><?php echo $this->itemPhoto($item, 'thumb.main') ?></div>
        <?php } else if($item->mediatype == 2 && $item->code) { ?>
          <div class="sesthought_video"><?php echo $item->code; ?></div>
        <?php } ?>
        <?php if(is_array($this->allParams['information']) && engine_in_array('title', $this->allParams['information']) && !empty($item->thoughttitle)) { ?>
          <div class="sesthought_title">  
            <?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.show', 0)) { ?>
              <a href="<?php echo $item->getHref(); ?>"><?php echo $item->thoughttitle; ?></a>
            <?php } else { ?>
              <a data-url='sesthought/index/thought-popup/thought_id/<?php echo $item->getIdentity(); ?>' class="sessmoothbox" href="javascript:;"><?php echo $item->thoughttitle; ?></a>
            <?php } ?>
          </div>
        <?php } ?>
        <div class='_des'>
          <?php echo $this->string()->truncate($this->string()->stripTags($item->getDescription()), $this->allParams['description_truncation']) ?>
        </div>
        <?php if(is_array($this->allParams['information']) &&  (engine_in_array('likeCount', $this->allParams['information']) || engine_in_array('commentCount', $this->allParams['information']) || engine_in_array('viewCount', $this->allParams['information']) || engine_in_array('permalink', $this->allParams['information']))) { ?>
          <div class="_stats sesbasic_text_light">
            <?php if(is_array($this->allParams['information']) && engine_in_array('likeCount', $this->allParams['information'])) { ?>
              <span title="<?php echo $this->translate(array('%s Like', '%s Likes', $item->like_count), $this->locale()->toNumber($item->like_count)) ?>">
                <i class="sesbasic_icon_like_o"></i>
                <span><?php echo $this->locale()->toNumber($item->like_count) ?></span>
              </span>
            <?php } ?>
            <?php if(is_array($this->allParams['information']) && engine_in_array('commentCount', $this->allParams['information'])) { ?>
              <span title="<?php echo $this->translate(array('%s Comment', '%s Comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?>">
                <i class="sesbasic_icon_comment_o"></i>
                <span><?php echo $this->locale()->toNumber($item->comment_count) ?></span>
              </span>
            <?php } ?>
            <?php if(is_array($this->allParams['information']) && engine_in_array('viewCount', $this->allParams['information'])) { ?>
              <span title="<?php echo $this->translate(array('%s View', '%s Views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>">
                <i class="sesbasic_icon_view"></i>
                <span><?php echo $this->locale()->toNumber($item->view_count) ?></span>
              </span>
            <?php } ?>
            <?php if(is_array($this->allParams['information']) && engine_in_array('permalink', $this->allParams['information'])) { ?>
              <span>- 
                <?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.show', 0)) { ?>
                  <a href="<?php echo $item->getHref(); ?>"><?php echo $this->translate('Read More'); ?></a>
                <?php } else { ?>
                  <a data-url='sesthought/index/thought-popup/thought_id/<?php echo $item->getIdentity(); ?>' class="sessmoothbox" href="javascript:;"><?php echo $this->translate('Read More'); ?></a>
                <?php } ?>
              </span>
            <?php } ?>
          </div>
        <?php } ?>
        <div class="_info">
          <?php if(is_array($this->allParams['information']) && engine_in_array('postedby', $this->allParams['information'])) { ?>
          <div class="_owner_thumb">
            <?php echo $this->htmlLink($item->getOwner()->getHref(), $this->itemPhoto($item->getOwner(), 'thumb.icon', $item->getOwner()->getTitle())) ?>
          </div>
          <?php } ?>
          <?php if(is_array($this->allParams['information']) && (engine_in_array('posteddate', $this->allParams['information']) || engine_in_array('postedby', $this->allParams['information']) || engine_in_array('likebutton', $this->allParams['information']) || engine_in_array('likebutton', $this->allParams['information']))) { ?>
            <div class="_owner_info">
              <?php if(is_array($this->allParams['information']) && engine_in_array('postedby', $this->allParams['information'])) { ?>
                <?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?>
              <?php } ?>
              <?php if(is_array($this->allParams['information']) && engine_in_array('posteddate', $this->allParams['information'])) { ?>
                <span class="sesbasic_text_light">- <?php echo $this->timestamp(strtotime($item->creation_date)) ?></span>
              <?php } ?>
              <p class="_social">
                <?php if(engine_in_array('socialSharing', $this->allParams['information']) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.allowshare', 1)):?>
                  <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->allParams['socialshare_enable_plusicon'], 'socialshare_icon_limit' => $this->allParams['socialshare_icon_limit'])); ?>
                <?php endif;?>
                <?php $canComment = Engine_Api::_()->authorization()->isAllowed('sesthought_thought', $viewer, 'create');?>
                <?php if(is_array($this->allParams['information']) && engine_in_array('likebutton', $this->allParams['information']) && $canComment):?>
                  <?php $likeStatus = Engine_Api::_()->sesthought()->getLikeStatus($item->thought_id,$item->getType()); ?>
                  <a href="javascript:;" data-type="like_view" data-url="<?php echo $item->thought_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesthought_like_<?php echo $item->thought_id ?> sesthought_likefavfollow <?php echo ($likeStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count;?></span></a>
                <?php endif;?>
              </p>
            </div>
          <?php } ?>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
<?php } else { ?>
  <?php 
    $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesthought/externals/styles/styles.css');
    $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/wookmark.min.js');
  ?>
  <?php $randonNumber = 5000; ?>
  <ul class="sesthoughts_other_listing prelative sesthoughts_listing sesbasic_bxs sesbasic_pinboard_<?php echo $randonNumber ; ?>" style="min-height:50px;" id="widget_<?php echo $randonNumber; ?>" >
    <?php foreach( $this->thoughts as $item ): ?>
      <li class="sesbasic_clearfix sesthoughts_list_item newwidget_image_pinboard_<?php echo $randonNumber; ?>">
        <section>
          <header class="sesbasic_clearfix">
            <?php if(is_array($this->allParams['information']) && engine_in_array('postedby', $this->allParams['information'])) { ?>
            <div class="_owner_thumb">
              <?php echo $this->htmlLink($item->getOwner()->getHref(), $this->itemPhoto($item->getOwner(), 'thumb.icon', $item->getOwner()->getTitle())) ?>
            </div>
            <?php } ?>
            <div class="_owner_info">
              <?php if(is_array($this->allParams['information']) && engine_in_array('postedby', $this->allParams['information'])) { ?>
                <div class="_owner_name"><?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?></div>
              <?php } ?>
              <?php if(is_array($this->allParams['information']) && engine_in_array('posteddate', $this->allParams['information'])) { ?>
                <div class="sesbasic_text_light _date"><?php echo $this->timestamp(strtotime($item->creation_date)) ?></div>
              <?php } ?>
            </div>
          </header>
          <div class="_content">
            <?php if($item->mediatype == 1 && !empty($item->photo_id)) { ?>
              <div class="sesthought_img"><?php echo $this->itemPhoto($item, 'thumb.main') ?></div>
            <?php } else if($item->mediatype == 2 && $item->code) { ?>
              <div class="sesthought_video"><?php echo $item->code; ?></div>
            <?php } ?>
            <?php if(engine_in_array('title', $this->allParams['information']) && !empty($item->thoughttitle)) { ?>
              <div class="sesthought_title">  
                <?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.show', 0)) { ?>
                  <a href="<?php echo $item->getHref(); ?>"><?php echo $item->thoughttitle; ?></a>
                <?php } else { ?>
                  <a data-url='sesthought/index/thought-popup/thought_id/<?php echo $item->getIdentity(); ?>' class="sessmoothbox" href="javascript:;"><?php echo $item->thoughttitle; ?></a>
                <?php } ?>
              </div>
            <?php } ?>
						<div class="sesthought_thought">
            	<?php echo $item->getTitle(); //$this->string()->truncate($this->string()->stripTags($item->getTitle()), $this->allParams['description_truncation']) ?>
            </div>
            <div class="_stats sesbasic_text_light">
              <?php if(is_array($this->allParams['information']) && engine_in_array('likeCount', $this->allParams['information'])) { ?>
                <span title="<?php echo $this->translate(array('%s Like', '%s Likes', $item->like_count), $this->locale()->toNumber($item->like_count)) ?>">
                  <i class="sesbasic_icon_like_o"></i>
                  <span><?php echo $this->locale()->toNumber($item->like_count) ?></span>
                </span>
              <?php } ?>
              <?php if(is_array($this->allParams['information']) && engine_in_array('commentCount', $this->allParams['information'])) { ?>
                <span title="<?php echo $this->translate(array('%s Comment', '%s Comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?>">
                  <i class="sesbasic_icon_comment_o"></i>
                  <span><?php echo $this->locale()->toNumber($item->comment_count) ?></span>
                </span>
              <?php } ?>
              <?php if(is_array($this->allParams['information']) && engine_in_array('viewCount', $this->allParams['information'])) { ?>
                <span title="<?php echo $this->translate(array('%s View', '%s Views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>">
                  <i class="sesbasic_icon_view"></i>
                  <span><?php echo $this->locale()->toNumber($item->view_count) ?></span>
                </span>
              <?php } ?>
              <?php if(is_array($this->allParams['information']) && engine_in_array('permalink', $this->allParams['information'])) { ?>
                <span>- 
                  <?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.show', 0)) { ?>
                    <a href="<?php echo $item->getHref(); ?>"><?php echo $this->translate('Read More'); ?></a>
                  <?php } else { ?>
                    <a data-url='sesthought/index/thought-popup/thought_id/<?php echo $item->getIdentity(); ?>' class="sessmoothbox" href="javascript:;"><?php echo $this->translate('Read More'); ?></a>
                  <?php } ?>
                </span>
              <?php } ?>
            </div>
          </div>
          <div class="_footer sesbasic_clearfix sesthought_social_btns">
            <?php if(engine_in_array('socialSharing', $this->allParams['information']) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.allowshare', 1)):?>
              <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->allParams['socialshare_enable_plusicon'], 'socialshare_icon_limit' => $this->allParams['socialshare_icon_limit'])); ?>
            <?php endif;?>
            <?php $canComment = Engine_Api::_()->authorization()->isAllowed('sesthought_thought', $viewer, 'create');?>
            <?php if(is_array($this->allParams['information']) && engine_in_array('likebutton', $this->allParams['information']) && $canComment):?>
              <?php $likeStatus = Engine_Api::_()->sesthought()->getLikeStatus($item->thought_id,$item->getType()); ?>
              <a href="javascript:;" data-type="like_view" data-url="<?php echo $item->thought_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesthought_like_<?php echo $item->thought_id ?> sesthought_likefavfollow <?php echo ($likeStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count;?></span></a>
            <?php endif;?>
          </div>
        </section>
      </li>
    <?php endforeach; ?>
  </ul>
  <script type="application/javascript">
    
    var wookmark = undefined;
    var wookmark<?php echo $randonNumber ?>;
    function pinboardLayout_<?php echo $randonNumber ?>(force) {
      scriptJquery('.newwidget_image_pinboard_<?php echo $randonNumber; ?>').css('display','none');
      imageLoadedAll<?php echo $randonNumber ?>(force);
    }
    
    function imageLoadedAll<?php echo $randonNumber ?>(force) {
    
      scriptJquery('#widget_<?php echo $randonNumber; ?>').addClass('sesbasic_pinboard_<?php echo $randonNumber; ?>');
      scriptJquery('#widget_<?php echo $randonNumber; ?>').addClass('sesbasic_pinboard');
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
<?php } ?>
