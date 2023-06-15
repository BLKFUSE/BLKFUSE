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

<?php $randonNumber = $this->widgetId; ?>
<?php if(!$this->is_ajax){ ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); ?>
<ul class="sesnews_review_listing sesbasic_clearfix sesbasic_bxs" id="sesnews_review_listing">
<?php } ?>
<?php if( $this->paginator->getTotalItemCount() > 0 ){ ?>
  <?php foreach( $this->paginator as $item ): ?>
    <?php $reviewer = Engine_Api::_()->getItem('sesnews_news', $item->news_id);?>
    <?php  if(!$reviewer) continue;  ?>
    <li class="sesbasic_clearfix">
    	<div class="sesnews_review_listing_left">
      	<div class="sesnews_review_listing_left_photo">
          <?php echo $this->htmlLink($item->getOwner()->getHref(), $this->itemPhoto($item->getOwner(), 'thumb.profile')) ?>
        </div>
        <p class="sesnews_review_listing_left_title"><?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?></p>
        <?php if(isset($this->featuredLabelActive) && $item->featured):?>
	  <div class="sesnews_review_featured_block">
	  <p><?php echo $this->translate('Featured');?></p>
	  </div>
	<?php endif;?>
	<?php if(isset($this->verifiedLabelActive) && $item->verified):?>
	  <div class="sesnews_review_verified_block">
	    <p><?php echo $this->translate('Verified');?></p>
	  </div>
	<?php endif;?>
      </div>
      <div class="sesnews_review_listing_right sesbasic_clearfix">
        <div class="sesnews_review_listing_top sesbasic_clearfix">
          <?php if(is_array($this->stats) && engine_in_array('title', $this->stats)): ?>
            <div class='sesnews_review_listing_title sesbasic_clearfix'>
              <?php echo $this->htmlLink($item->getHref(), $item->title) ?>
            </div>
          <?php endif; ?>
        <div class="sesnews_review_listing_top_info sesbasic_clearfix">
          <?php if(engine_in_array('postedBy', $this->stats) && $reviewer): ?>
            <div class='sesnews_review_listing_top_info_img'>
              <?php echo $this->htmlLink($reviewer->getHref(), $this->itemPhoto($reviewer, 'thumb.icon')) ?>
            </div>
          <?php endif; ?>
          <div class='sesnews_review_listing_top_info_cont'>
            <?php if(engine_in_array('postedBy', $this->stats) || engine_in_array('creationDate', $this->stats)): ?>
              <p class="sesnews_review_listing_stats">
                <?php if(engine_in_array('postedBy', $this->stats) && $reviewer): ?>
                  <?php echo $this->translate('For ');?><?php echo $this->htmlLink($reviewer->getHref(), $reviewer->getTitle(), array('class' => '', 'data-src' => $reviewer->getGuid())) ?>
                <?php endif; ?>
                <?php if(engine_in_array('postedBy', $this->stats) && engine_in_array('creationDate', $this->stats)): ?> | <?php endif; ?>
                <?php if(is_array($this->stats) && engine_in_array('creationDate', $this->stats)): ?>
                  <?php echo $this->translate('about');?>
                  <?php echo $this->timestamp(strtotime($item->creation_date)) ?>
                <?php endif; ?>
               </p>
            <?php endif; ?>
            
            <p class="sesnews_review_listing_stats sesbasic_text_light">
              <?php if(is_array($this->stats) && engine_in_array('likeCount', $this->stats)): ?>
                <span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $item->like_count; ?></span>
              <?php endif; ?>
              <?php if(is_array($this->stats) && engine_in_array('commentCount', $this->stats)): ?>
                <span title="<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $item->comment_count;?></span>
              <?php endif; ?>
              <?php if(is_array($this->stats) && engine_in_array('viewCount', $this->stats)): ?>
                <span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $item->view_count; ?></span>
              <?php endif; ?>
            </p>
          </div>	
        </div>
          <div class="sesnews_review_show_rating review_ratings_listing">
            <?php if(is_array($this->stats) && engine_in_array('rating', $this->stats)): ?>
              <div class="sesbasic_rating_star">
                <?php $ratingCount = $item->rating;?>
                <?php for($i=0; $i<5; $i++){?>
            <?php if($i < $ratingCount):?>
              <span id="" class="sesnews_rating_star"></span>
            <?php else:?>
              <span id="" class="sesnews_rating_star sesnews_rating_star_disable"></span>
            <?php endif;?>
                <?php }?>
              </div>
            <?php endif ?>
            <?php if(engine_in_array('parameter', $this->stats)){ ?>
              <?php $reviewParameters = Engine_Api::_()->getDbtable('parametervalues', 'sesnews')->getParameters(array('content_id'=>$item->getIdentity(),'user_id'=>$item->owner_id)); ?>
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
            }?>
          </div>
        </div>
        <div class="sesnews_review_listing_desc sesbasic_clearfix">
          <?php if(engine_in_array('pros', $this->stats) && $item->pros && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.show.pros', 1)): ?>
            <p class="sesnews_review_listing_body">
              <b><?php echo $this->translate("Pros"); ?></b>
              <?php echo $this->string()->truncate($this->string()->stripTags($item->pros), 300) ?>
            </p>
          <?php endif; ?>
          <?php if(engine_in_array('cons', $this->stats) && $item->cons && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.show.cons', 1)): ?>
            <p class="sesnews_review_listing_body">
              <b><?php echo $this->translate("Cons"); ?></b>
              <?php echo $this->string()->truncate($this->string()->stripTags($item->cons), 300) ?>
            </p>
          <?php endif; ?>
					<?php if(engine_in_array('customfields', $this->stats)): ?>
						<?php $customFieldsData = Engine_Api::_()->sesnews()->getCustomFieldMapData($item);?>
						<?php if(engine_count($customFieldsData) > 0):?>
							<?php foreach($customFieldsData as $valueMeta):?>
								<?php if(!$valueMeta['value']):?>	
									<?php continue;?>
								<?php endif;?>
								<?php echo '<p class="sesnews_review_view_body"><b class="label">'. $valueMeta['label']. ': </b>'.
								$valueMeta['value'].'</p>';?>
							<?php endforeach;?>   
						<?php endif; ?>
					<?php endif; ?>
          <?php if(engine_in_array('description', $this->stats) && $item->description): ?>
            <p class='sesnews_review_listing_body'>
              <b><?php echo $this->translate("Description"); ?></b>
              <?php echo $this->string()->truncate($this->string()->stripTags($item->description), 300) ?>
            </p>
          <?php endif; ?>
	  <?php if(engine_in_array('recommended', $this->stats)):?>
	    <p class="sesnews_review_listing_recommended"> <b><?php echo $this->translate('Recommend News');?><i class="<?php if($item->recommended):?>fa fa-check<?php else:?>fa fa-times<?php endif;?>"></i></b></p>
	  <?php endif;?>
          <p class="sesnews_review_listing_more">
            <a href="<?php echo $item->getHref()?>" class=""><?php echo $this->translate('Continue Reading »');?></a>
          </p>
        </div>
        <?php  echo $this->partial('_reviewOptions.tpl','sesnews',array('subject'=>$item,'viewer'=>$this->viewer(),'stats'=>$this->stats)); ?>
      </div>
    </li>
  <?php endforeach; ?>
  <?php if($this->loadOptionData == 'pagging'){ ?>
      <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sesnews"),array('identityWidget'=>$randonNumber)); ?>
    <?php } ?>
<?php }else{ ?>
	<div class="tip">
    <span>
      <?php echo $this->translate('No review have been posted yet.');?>
    </span>
  </div>
<?php } ?>
<?php if(!$this->is_ajax){ ?>
</ul>
<?php  } ?>

<?php if($this->loadOptionData != 'pagging' && !$this->is_ajax):?>
  <div class="sesbasic_load_btn" style="display: none;" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array('id' => "feed_viewmore_link_$randonNumber", 'class' => 'sesbasic_animation sesbasic_link_btn fa fa-sync')); ?> </div>
<div class="sesbasic_load_btn sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"> <span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
<?php endif;?>

<script type="application/javascript">
  <?php if(!$this->is_ajax):?>
    <?php if($this->loadOptionData == 'auto_load'){ ?>
    scriptJquery( window ).load(function() {
      scriptJquery(window).scroll( function() {
	var containerId = '#sesnews_review_listing';
        if(typeof scriptJquery(containerId).offset() != 'undefined' && scriptJquery('#view_more_<?php echo $randonNumber; ?>').length > 0) {
	  var heightOfContentDiv_<?php echo $randonNumber; ?> = scriptJquery(containerId).offset().top;
	  var fromtop_<?php echo $randonNumber; ?> = scriptJquery(this).scrollTop();
	  if(fromtop_<?php echo $randonNumber; ?> > heightOfContentDiv_<?php echo $randonNumber; ?> - 100 && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block' ){
	    document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
	  }
        }
      });
    });
    <?php } ?>
  <?php endif; ?>
	var page<?php echo $randonNumber; ?> = <?php echo $this->page + 1; ?>;
	var params<?php echo $randonNumber; ?> = '<?php echo json_encode($this->stats); ?>';
	var searchParams<?php echo $randonNumber; ?> = '';
	<?php if($this->loadOptionData != 'pagging') { ?>
	viewMoreHide_<?php echo $randonNumber; ?>();
	function viewMoreHide_<?php echo $randonNumber; ?>() {
			if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
			document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
		}
	 function viewMore_<?php echo $randonNumber; ?> () {
			scriptJquery('#view_more_<?php echo $randonNumber; ?>').hide();
			scriptJquery('#loading_image_<?php echo $randonNumber; ?>').show(); 
			
			requestViewMore_<?php echo $randonNumber; ?> = scriptJquery.ajax({
        dataType: 'html',
				method: 'post',
				'url': en4.core.baseUrl + "widget/index/mod/sesnews/name/browse-reviews",
				'data': {
		format: 'html',
		page: page<?php echo $randonNumber; ?>,    
		params : params<?php echo $randonNumber; ?>, 
		is_ajax : 1,
		limit:'<?php echo $this->limit; ?>',
		widgetId : '<?php echo $this->widgetId; ?>',
		searchParams : searchParams<?php echo $randonNumber; ?>,
		loadOptionData : '<?php echo $this->loadOptionData ?>'
				},
				success: function(responseHTML) {
				scriptJquery('#sesnews_review_listing').append(responseHTML);
				scriptJquery('.sesbasic_view_more_loading_<?php echo $randonNumber;?>').hide();
				scriptJquery('#loadingimgsesnewsreview-wrapper').hide();
				viewMoreHide_<?php echo $randonNumber; ?>();
				}
			});
			
			return false;
		}
	<?php }else{ ?>
		function paggingNumber<?php echo $randonNumber; ?>(pageNum){
			scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $randonNumber?>').css('display','block');
			requestViewMore_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
        dataType: 'html',
				method: 'post',
				'url': en4.core.baseUrl + "widget/index/mod/sesnews/name/browse-reviews",
				'data': {
					format: 'html',
					page: pageNum,
					params :params<?php echo $randonNumber; ?> , 
					searchParams : searchParams<?php echo $randonNumber; ?>,
					is_ajax : 1,
					limit:'<?php echo $this->limit; ?>',
					widgetId : '<?php echo $this->widgetId; ?>',
					loadOptionData : '<?php echo $this->loadOptionData ?>'
				},
				success: function(responseHTML) {
					scriptJquery('#sesnews_review_listing').html(responseHTML);
					scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $randonNumber?>').css('display', 'none');
					scriptJquery('#loadingimgsesnewsreview-wrapper').hide();
				}
			}));
			
			return false;
		}
<?php } ?>
</script>
