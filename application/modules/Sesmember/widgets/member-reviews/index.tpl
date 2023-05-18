<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<?php $randonNumber = $this->widgetId; ?>
<?php if(!$this->is_ajax){ ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmember/externals/styles/styles.css'); ?>
<script type="text/javascript">
var previous_rate_value;
  function showReviewForm() {
    document.getElementById('sesmember_review_create_form').style.display = 'block';
		var openObject = scriptJquery('#sesmember_review_create_form');
				scriptJquery('html, body').animate({
					scrollTop: openObject.offset().top
				}, 2000);
				if(typeof review_cover_data_rate_id != 'undefined'){
					previous_rate_value = scriptJquery('#rate_value').val();
					window.rate(review_cover_data_rate_id);	
				}
  }
</script>
<?php $editReviewPrivacy = Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'edit');?>
<?php if($this->viewer()->getIdentity() && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.allow.review', 1) && $this->allowedCreate):?>
  <div class="sesbasic_profile_tabs_top sesbasic_clearfix sesmember_review_profile_btn">
    <a id="sesmember_create_button" href="javascript:void(0)" onclick="showReviewForm();" class="sesbasic_button sesbasic_icon_add" style="display:<?php if($this->cancreate && !$this->isReview): ?>block;<?php else:?>none;<?php endif;?>"><?php echo $this->translate('Write a Review');?></a>
    <a id="sesmember_edit_button" href="javascript:void(0)" onclick="showReviewForm();" class="sesbasic_button sesbasic_icon_edit" style="display:<?php if($editReviewPrivacy && $this->isReview):?>block;<?php else:?>none;<?php endif;?>"><?php echo $this->translate('Update Review');?></a>
  </div>
<?php endif;?>
<?php if( $this->paginator->getTotalItemCount() > 0 ){ ?>
  <div class="sesmember_profile_reviews_filters sesbasic_bxs sesbasic_clearfix">
    <?php echo $this->content()->renderWidget('sesmember.browse-review-search',array('review_search'=>1,'review_stars'=>1,'reviewRecommended'=>1,'review_title'=>0,'view_type'=>'horizontal','isWidget'=>true,'user_id'=>$this->subject->getIdentity(),'widgetIdentity'=>$this->identity)); ?>
  </div>
<?php } ?>
  <ul class="sesmember_review_listing sesbasic_clearfix sesbasic_bxs" id="sesmember_review_listing">
<?php } ?>
  <?php if( $this->paginator->getTotalItemCount() > 0 ){ ?>
    <?php foreach( $this->paginator as $item ): ?>
      <li class="sesbasic_clearfix <?php if($item->owner_id == $this->viewer()->getIdentity()):?>sesmember_owner_review<?php endif;?>">
        <div class="sesmember_review_listing_top sesbasic_clearfix">
          <?php if(is_array($this->stats) && engine_in_array('title', $this->stats)): ?>
            <div class='sesmember_review_listing_title sesbasic_clearfix'>
              <?php echo $this->htmlLink($item->getHref(), $item->title) ?>
            </div>
          <?php endif; ?>
          <div class="sesmember_review_listing_top_info sesbasic_clearfix">
            <?php if(is_array($this->stats) && engine_in_array('postedBy', $this->stats)): ?>
              <div class='sesmember_review_listing_top_info_img'>
                <?php echo $this->htmlLink($item->getOwner()->getHref(), $this->itemPhoto($item->getOwner(), 'thumb.icon')) ?>
              </div>
            <?php endif; ?>
            
	    			<div class='sesmember_review_listing_top_info_cont'>
            	<?php if(engine_in_array('postedBy', $this->stats) || engine_in_array('creationDate', $this->stats)): ?>
              	<p class="sesmember_review_listing_stats sesbasic_text_light">
                  <?php if(is_array($this->stats) && engine_in_array('postedBy', $this->stats)): ?>
                    <?php echo $this->translate('by');?>&nbsp;<?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle(), array('class' => 'ses_tooltip', 'data-src' => $item->getOwner()->getGuid())) ?>
                  <?php endif; ?>
                  <?php if(engine_in_array('postedBy', $this->stats) && engine_in_array('creationDate', $this->stats)): ?> | <?php endif; ?>
                  <?php if(is_array($this->stats) && engine_in_array('creationDate', $this->stats)): ?>		
                    <?php echo $this->translate('about');?>
                    <?php echo $this->timestamp(strtotime($item->creation_date)) ?>
                  <?php endif; ?>
              	</p>
              <?php endif; ?>
              <p class="sesbasic_text_light sesmember_review_listing_stats">
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

          <div class="sesmember_review_show_rating review_ratings_listing">
            <?php if(is_array($this->stats) && engine_in_array('rating', $this->stats)): ?>
              <div class="sesbasic_rating_star">
                <?php $ratingCount = $item->rating;?>
                <?php for($i=0; $i<5; $i++){?>
                  <?php if($i < $ratingCount):?>
                    <span id="" class="sesmember_rating_star"></span>
                  <?php else:?>
                    <span id="" class="sesmember_rating_star sesmember_rating_star_disable"></span>
                  <?php endif;?>
                <?php }?>
              </div>
            <?php endif ?>
            <?php if(engine_in_array('parameter', $this->stats)){ ?>
              <?php $reviewParameters = Engine_Api::_()->getDbtable('parametervalues', 'sesmember')->getParameters(array('content_id'=>$item->getIdentity(),'user_id'=>$item->owner_id)); ?>
              <?php if(engine_count($reviewParameters)>0){ ?>
                <div class="sesmember_review_show_rating_box sesbasic_clearfix">
                  <?php foreach($reviewParameters as $reviewP){ ?>
                    <div class="sesbasic_clearfix">
                      <div class="sesmember_review_show_rating_label"><?php echo $reviewP['title']; ?></div>
                      <div class="sesmember_review_show_rating_parameters sesbasic_rating_parameter sesbasic_rating_parameter_small">
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
        <div class="sesmember_review_listing_desc sesbasic_clearfix">
          <?php if(engine_in_array('pros', $this->stats) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.show.pros', 1)): ?>
            <p class="sesmember_review_listing_body">
              <b><?php echo $this->translate("Pros"); ?></b>
              <?php echo $this->string()->truncate($this->string()->stripTags($item->pros), 300) ?>
            </p>
          <?php endif; ?>
          <?php if(engine_in_array('cons', $this->stats) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.show.cons', 1)): ?>
            <p class="sesmember_review_listing_body">
              <b><?php echo $this->translate("Cons"); ?></b>
              <?php echo $this->string()->truncate($this->string()->stripTags($item->cons), 300) ?>
            </p>
          <?php endif; ?>
          <?php if(engine_in_array('description', $this->stats) && $item->description): ?>
            <p class='sesmember_review_listing_body'>
              <b><?php echo $this->translate("Description"); ?></b>
              <?php echo $this->string()->truncate($this->string()->stripTags($item->description), 300) ?>
            </p>
          <?php endif; ?>
	  <p class="sesmember_review_listing_recommended"> <b><?php echo $this->translate('Recommended');?><i class="<?php if($item->recommended):?>fa fa-check<?php else:?>fa fa-times<?php endif;?>"></i></b></p>
          <p class="sesmember_review_listing_more">
          	<a href="<?php echo $item->getHref(); ?>" class=""><?php echo $this->translate("Continue Reading"); ?> &raquo;</a>
          </p>
        </div>
  			<?php  echo $this->partial('_reviewOptions.tpl','sesmember',array('subject'=>$item,'viewer'=>Engine_Api::_()->user()->getViewer(),'stats'=>$this->stats)); ?>        
      </li>
    <?php endforeach; ?>
     <?php if($this->loadOptionData == 'pagging'){ ?>
      <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sesmember"),array('identityWidget'=>$randonNumber)); ?>
    <?php } ?>
  <?php }else{ ?>
  <div class="tip">
    <span>
      <?php echo $this->translate('No review have been posted on this member yet.');?>
    </span>
  </div>
  <?php } ?>
<?php if(!$this->is_ajax){ ?>
  </ul>
  <?php if($this->loadOptionData != 'pagging' && !$this->is_ajax):?>
  <div class="sesbasic_load_btn" style="display: none;" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" ><a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" id="feed_viewmore_link_<?php echo $randonNumber; ?>"><i class="fa fa-repeat"></i><span><?php echo $this->translate('View More');?></span></a></div>
<div class="sesbasic_load_btn sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"> <span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
<?php endif;?>

<?php if(($this->allowedCreate && $this->cancreate && $this->viewer()->getIdentity() && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.allow.review', 1) && !$this->isReview) || ($editReviewPrivacy)): ?>
  <div id="sesmember_review_create_form" class="sesmember_review_form_block" style="display:none;"> 
    <?php echo $this->form->render($this);?>
    <div class="sesbasic_loading_cont_overlay" style="display:none"></div>
  </div>
<?php endif;?>
<script type="text/javascript">
  function closeReviewForm() {
    document.getElementById('sesmember_review_create_form').style.display = 'none';
		var openObject = scriptJquery('.sesmember_review_profile_btn');
				scriptJquery('html, body').animate({
					scrollTop: openObject.offset().top
				}, 2000);
				if(scriptJquery('#sesmember_edit_button').length && previous_rate_value != 'undefined'){
					window.rate(previous_rate_value);
				}
  }
</script>
<?php } ?>

<script type="application/javascript">
  <?php if(!$this->is_ajax):?>
	
	scriptJquery(document).on('click','.sesmember_own_update_review',function(e){
			e.preventDefault();
			showReviewForm();
	});
	
    <?php if($this->loadOptionData == 'auto_load'){ ?>
    scriptJquery( window ).load(function() {
      scriptJquery(window).scroll( function() {
	var containerId = '#sesmember_review_listing';
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
				'url': en4.core.baseUrl + "widget/index/mod/sesmember/name/member-reviews",
				'data': {
		format: 'html',
		page: page<?php echo $randonNumber; ?>,    
		params : params<?php echo $randonNumber; ?>, 
		is_ajax : 1,
		limit:'<?php echo $this->limit; ?>',
		widgetId : '<?php echo $this->widgetId; ?>',
		searchParams : searchParams<?php echo $randonNumber; ?>,
		user_id:'<?php echo $this->user_id; ?>',
		loadOptionData : '<?php echo $this->loadOptionData ?>'
				},
				success: function(responseHTML) {
				scriptJquery('#sesmember_review_listing').append(responseHTML);
				scriptJquery('.sesbasic_view_more_loading_<?php echo $randonNumber;?>').hide();
				scriptJquery('#loadingimgsesmemberreview-wrapper').hide();
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
				'url': en4.core.baseUrl + "widget/index/mod/sesmember/name/member-reviews",
				'data': {
					format: 'html',
					page: pageNum,
					user_id:'<?php echo $this->user_id; ?>',
					params :params<?php echo $randonNumber; ?> , 
					searchParams : searchParams<?php echo $randonNumber; ?>,
					is_ajax : 1,
					limit:'<?php echo $this->limit; ?>',
					widgetId : '<?php echo $this->widgetId; ?>',
					loadOptionData : '<?php echo $this->loadOptionData ?>'
				},
				success: function(responseHTML) {
					scriptJquery('#sesmember_review_listing').html(responseHTML);
					scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $randonNumber?>').css('display', 'none');
					scriptJquery('#loadingimgsesmemberreview-wrapper').hide();
				}
			}));
			
			return false;
		}
<?php } ?>
  var tabId_pE1 = '<?php echo $this->identity; ?>';
  scriptJquery(document).ready(function() {
    tabContainerHrefSesbasic(tabId_pE1);	
  });
</script>
