<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/scripts/core.js'); 
?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); ?> 
<div class="sesnews_review_view sesnews_profile_review sesbasic_bxs sesbasic_clearfix">
	<div class="sesnews_review_view_top sesbasic_clearfix">
    <?php if(is_array($this->stats) && engine_in_array('title', $this->stats)): ?>
      <div class="sesnews_review_view_title"><?php echo $this->review->getTitle() ?></div>
    <?php endif; ?>
    <div class="sesnews_review_view_item_info sesbasic_clearfix">
      <?php if(engine_in_array('postedin', $this->stats)): ?>
      	<div class="sesnews_review_view_info_img">
        	<?php echo $this->htmlLink($this->item->getHref(), $this->itemPhoto($this->item, 'thumb.icon')); ?>
        </div>
      <?php endif; ?> 
      <div class="sesnews_review_view_info_cont sesbasic_clearfix">
        <?php if(engine_in_array('postedin', $this->stats) && engine_in_array('creationDate', $this->stats)) : ?>
          <p class='sesnews_review_view_stats sesbasic_text_light sesbasic_clearfix'>
            <?php if(engine_in_array('postedin', $this->stats)): ?>
              <?php echo $this->translate('For');?> <?php echo $this->htmlLink($this->item, $this->item) ?>
            <?php endif; ?>
            <?php if(engine_in_array('postedin', $this->stats) && engine_in_array('creationDate', $this->stats)) : ?> | <?php endif; ?>
            <?php if(is_array($this->stats) && engine_in_array('creationDate', $this->stats)): ?>
              <?php echo $this->translate('about').' '.$this->timestamp($this->review->creation_date) ?>
            <?php endif; ?> 
          </p>
        <?php endif; ?> 
        <p class="sesnews_review_view_stats sesbasic_text_light">
          <?php if(is_array($this->stats) && engine_in_array('likeCount', $this->stats)): ?>
          <span><i class="sesbasic_icon_like_o sesbasic_text_light"></i><?php echo $this->translate(array('%s like', '%s likes', $this->review->like_count), $this->locale()->toNumber($this->review->like_count)); ?></span>
          <?php endif; ?>
          <?php if(is_array($this->stats) && engine_in_array('commentCount', $this->stats)): ?>
          <span><i class="sesbasic_icon_comment_o sesbasic_text_light"></i><?php echo $this->translate(array('%s comment', '%s comments', $this->review->comment_count), $this->locale()->toNumber($this->review->comment_count))?></span>
          <?php endif; ?>
          <?php if(is_array($this->stats) && engine_in_array('viewCount', $this->stats)): ?>
          <span><i class="sesbasic_icon_view sesbasic_text_light"></i><?php echo $this->translate(array('%s view', '%s views', $this->review->view_count), $this->locale()->toNumber($this->review->view_count))?></span>
          <?php endif; ?>
        </p>
      </div>
  	</div>
    <div class="sesnews_review_show_rating sesnews_review_listing_star">
      <?php if(engine_in_array('rating', $this->stats)){ ?>
      <div class="sesbasic_rating_star">
        <?php $ratingCount = $this->review->rating;?>
        <?php for($i=0; $i<5; $i++){?>
          <?php if($i < $ratingCount):?>
            <span id="" class="sesnews_rating_star"></span>
          <?php else:?>
            <span id="" class="sesnews_rating_star sesnews_rating_star_disable"></span>
          <?php endif;?>
        <?php }?>
      </div>
      <?php } ?>
      <?php if(engine_in_array('parameter', $this->stats)){ ?>
      <?php $reviewParameters = Engine_Api::_()->getDbtable('parametervalues', 'sesnews')->getParameters(array('content_id'=>$this->review->getIdentity(),'user_id'=>$this->review->owner_id)); ?>
      <?php if(engine_count($reviewParameters)>0){ ?>
        <div class="sesnews_review_show_rating_box sesbasic_clearfix">
          <?php foreach($reviewParameters as $reviewP){ ?>
            <div class="sesbasic_clearfix">
              <div class="sesnews_review_show_rating_label"><?php echo $reviewP['title']; ?></div>
              <div class="sesnews_review_show_rating_parameters sesbasic_rating_parameter sesbasic_rating_parameter_small">
                <?php $ratingCount = $reviewP['rating'];?>
                <?php for($i=0; $i<5; $i++){?>
                  <?php if($i < $ratingCount):?>
                    <span id="" class="sesbasic-rating-parameter-unit"></span>
                  <?php else:?>
                    <span id="" class="sesbasic-rating-parameter-unit sesbasic-rating-parameter-unit-disable"></span>
                  <?php endif;?>
                <?php }?>
              </div>
            </div>
          <?php } ?>
        </div>
      <?php } 
      }
      ?>
  	</div>
  </div>
  <div class="sesnews_review_contant_disc">
  <?php if(engine_in_array('pros', $this->stats) && $this->review->pros && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.show.pros', 1)): ?>
    <div class="sesnews_review_view_cont_row"><b class="label"><?php echo $this->translate("Pros"); ?></b><?php echo $this->review->pros; ?></div>
  <?php endif; ?>
  <?php if(engine_in_array('cons', $this->stats) && $this->review->cons && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.show.cons', 1)): ?>
    <div class="sesnews_review_view_cont_row"><b class="label"><?php echo $this->translate("Cons"); ?></b><?php echo $this->review->cons; ?></div>
  <?php endif; ?>
  <?php if(engine_in_array('customfields', $this->stats)): ?>
  	<?php $customFieldsData = Engine_Api::_()->sesnews()->getCustomFieldMapData($this->review); 
    	if(engine_count($customFieldsData) > 0){ 
         foreach($customFieldsData as $valueMeta){
         if(!$valueMeta['value'])	
         	continue;
          echo '<p class="sesnews_review_view_cont_row"><b class="label">'. $valueMeta['label']. ': </b>'.
                $valueMeta['value'].'</p>';
         }     
 			 } ?>
  <?php endif; ?>
  <?php if(engine_in_array('description', $this->stats) && $this->review->description && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.review.summary', 1)): ?>
    <div class='sesnews_review_view_cont_row'>
    	<b class="label"><?php echo $this->translate("Summary"); ?></b>
      <div class="sesbasic_html_block"><?php echo $this->review->description; ?></div>
    </div>
  <?php endif; ?>
  <?php if(engine_in_array('recommended', $this->stats)): ?>
  	<p class="sesnews_review_view_cont_row sesnews_review_view_recommended">
      <b><?php echo $this->translate("Recommended"); ?>
      <?php if($this->review->recommended): ?>
      	<i class="fa fa-check"></i></b>
      <?php else: ?>
        <i class="fa fa-times"></i></b>
      <?php endif; ?>
    </p>
  <?php endif; ?>
  </p>
</div>
<div class="sesnews_layout_contant" style="padding:0px;">
	<div class="sesnews_footer_two_news ">
		<div class="sesnews_shear_news sesbasic_bxs">
		  <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $this->review->getHref()); ?>
		  <?php if(engine_in_array('socialSharing', $this->stats) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)):?>
		  
        <?php echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->review, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>

			<?php endif;?>
			<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.allow.share', 1) && $this->viewer()->getIdentity() && engine_in_array('share', $this->stats) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)): ?>
			<div class="sesnews_static_icons sesbasic_clearfix">
				<a href="<?php echo $this->url(array('module' => 'activity', 'controller' => 'index', 'action' => 'share', 'type' => $this->review->getType(), 'id' => $this->review->getIdentity()), 'default', true);?>" class="share_icon smoothbox"><i class="fas fa-share-alt"></i><?php echo $this->translate('Share');?></a>
			</div>
			<?php endif; ?>
			<?php $canComment =  $this->review->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');?>
			<?php if(engine_in_array('likeButton', $this->stats) && $canComment):?>
				<?php $LikeStatus = Engine_Api::_()->sesnews()->getLikeStatus($this->review->review_id,$this->review->getType()); ?>
				<div class="sesnews_static_icons sesbasic_clearfix">
					<a href="javascript:;" data-url="<?php echo $this->review->review_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_like_btn  sesnews_like_sesnews_review_<?php echo $this->review->review_id ?> sesnews_like_sesnews_review_view <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"><i class="<?php if($LikeStatus):?>fa fa-thumbs-down<?php else:?>fa fa-thumbs-up<?php endif;?>"></i><span><?php if($LikeStatus):?><?php echo $this->translate('Unlike');?><?php else:?><?php echo $this->translate('Like');?><?php endif;?></span></a>
				</div>
			<?php endif;?>
		</div>
	</div>
</div>
