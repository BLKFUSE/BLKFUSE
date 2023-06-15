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

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>

<?php if(isset($this->identityForWidget) && !empty($this->identityForWidget)):?>
	<?php $randonNumber = $this->identityForWidget;?>
<?php else:?>
	<?php $randonNumber = $this->identity;?>
<?php endif;?>

<?php if(!$this->is_ajax):?>
  <div id="scrollHeightDivSes_<?php echo $randonNumber;?>" class="sesbasic_bxs"> 
		<div id="category-news-widget_<?php echo $randonNumber; ?>" class="sesbasic_clearfix">
<?php endif;?>
  <?php foreach( $this->paginatorCategory as $item): ?>
  	<div class="sesnews_category_news sesbasic_clearfix">
      <div class="sesnews_category_header sesbasic_clearfix">
        <p class="floatL"><a href="<?php echo $item->getBrowseNewsHref(); ?>?category_id=<?php echo $item->category_id ?>" title="<?php echo $this->translate($item->category_name); ?>"><?php echo $this->translate($item->category_name); ?></a></p>
				<?php if(isset($this->seemore_text) && $this->seemore_text != ''): ?>
					<span <?php echo $this->allignment_seeall == 'right' ?  'class="floatR"' : ''; ?>><a  class="sesbasic_link_btn" href="<?php echo $item->getBrowseNewsHref(); ?>?category_id=<?php echo $item->category_id ?>"><?php $seemoreTranslate = $this->translate($this->seemore_text); ?>
					<?php echo str_replace('[category_name]',$this->translate($item->category_name),$seemoreTranslate); ?></a></span>
				<?php endif;?>
      </div>
      <?php if(isset($this->resultArray['news_data'][$item->category_id])):?>
        <?php $bigBlg = 0;?>
        <?php	foreach($this->resultArray['news_data'][$item->category_id] as $item):?>
            <?php if(!$bigBlg):?>
            <div class="sesnews_category_item_single">
            <div class="sesnews_category_item_single_info">
              <div class="sesnews_entry_img">
                <a href="<?php echo $item->getHref();?>"><img src="<?php echo $item->getPhotoUrl('thumb.main'); ?>" /></a>
              </div>
              <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->verifiedLabel)):?>
          <div class="sesnews_grid_labels">
            <?php if(isset($this->featuredLabelActive) && $item->featured == 1):?>
              <p class="sesnews_label_featured" title="<?php echo $this->translate('FEATURED');?>"><i class="fa fa-star"></i></p>
            <?php endif;?>
            <?php if(isset($this->sponsoredLabelActive) && $item->sponsored == 1):?>
              <p class="sesnews_label_sponsored" title="<?php echo $this->translate('SPONSORED');?>"><i class="fa fa-star"></i></p>
            <?php endif;?>
             <?php if(isset($this->hotLabelActive) && $item->hot == 1) { ?>
            <p class="sesnews_label_hot" title="<?php echo $this->translate('Hot'); ?>"><i class="fa fa-star"></i></p>
          <?php } ?>
          <?php if(isset($this->newLabelActive) && $item->latest == 1) { ?>
            <p class="sesnews_label_new" title="<?php echo $this->translate('New'); ?>"><i class="fa fa-star"></i></p>
          <?php } ?>
            <?php if(isset($this->verifiedLabelActive) && $item->verified == 1):?>
              <div class="sesnews_grid_verified_label" title="<?php echo $this->translate('VERIFIED');?>"><i class="fa fa-check"></i></div>
            <?php endif;?>
          </div>
        <?php endif;?>
              <div class="sesnews_category_item_single_content">
							<?php if(isset($this->titleActive)): ?>
								<p class="title"><a href="<?php echo $item->getHref();?>"><?php echo $item->getTitle(); ?></a></p>
							<?php endif;?>
							<?php if(Engine_Api::_()->getApi('core', 'sesnews')->allowReviewRating() && isset($this->ratingStarActive)):?>
								<?php echo $this->partial('_newsRating.tpl', 'sesnews', array('rating' => $item->rating, 'class' => 'sesnews_list_rating sesnews_list_view_ratting', 'style' => 'margin-bottom:0px;'));?>
							<?php endif;?>
              <div class="entry_meta">
                <?php if(isset($this->byActive)){ ?>
                <div class="sesnews_stats_list sesbasic_text_dark  floatL">
                  <?php $owner = $item->getOwner(); ?>
                  <span>
                    <?php echo $this->htmlLink($item->getOwner()->getParent(), $this->itemPhoto($item->getOwner()->getParent(), 'thumb.icon')); ?>
                    <?php echo $this->htmlLink($owner->getHref(),$owner->getTitle() ) ?>
                  </span>
                </div>
			         <?php } ?>
                <?php if(isset($this->creationDateActive)):?>
									<div class="entry_meta-date floatL"><i class="far fa-clock"></i> <?php echo date('M d, Y',strtotime($item->creation_date));?></div>
                <?php endif;?>
                <div class="entry_meta-comment floatR">
									<?php if(isset($this->likeActive) && isset($item->like_count)) { ?>
										<span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $item->like_count; ?></span>
									<?php } ?>
									<?php if(isset($this->commentActive) && isset($item->comment_count)) { ?>
										<span title="<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $item->comment_count;?></span>
									<?php } ?>
									<?php if(isset($this->favouriteActive) && isset($item->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)) { ?>
										<span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $item->favourite_count;?></span>
									<?php } ?>
									<?php if(isset($this->viewActive) && isset($item->view_count)) { ?>
										<span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $item->view_count; ?></span>
									<?php } ?>
									<?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/_newsRatingStat.tpl';?>
                </div>
              </div>
              </div>
              </div>
            </div>
            <div class="sesnews-category_item_list sesbasic_custom_scroll">
            <?php else:?>
              <div class="sesnews_category_item sesbasic_clearfix">
                <div class="wrapper_list sesbasic_clearfix">
                  <div class="sesnews_entry_img">
                    <a href="<?php echo $item->getHref();?>"><img src="<?php echo $item->getPhotoUrl('thumb.main'); ?>" /></a>
                  </div>
                  <?php if(isset($this->titleActive)):?>
                    <a href="<?php echo $item->getHref();?>"><p class="title"><?php echo $item->getTitle();?></p></a>          
                  <?php endif;?>
									<?php if(Engine_Api::_()->getApi('core', 'sesnews')->allowReviewRating() && isset($this->ratingStarActive)):?>
										<?php echo $this->partial('_newsRating.tpl', 'sesnews', array('rating' => $item->rating, 'class' => 'sesnews_list_rating sesnews_list_view_ratting', 'style' => 'margin-bottom:0px;'));?>
									<?php endif;?>
                  <div class="entry_meta">
                    <?php if(isset($this->creationDateActive)):?>
                      <div class="entry_meta-date floatL">
                        <?php echo date('M d, Y',strtotime($item->creation_date));?>
                      </div>
                    <?php endif; ?>
                    <div class="entry_meta-comment floatR">
											<?php if(isset($this->likeActive) && isset($item->like_count)) { ?>
												<span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $item->like_count; ?></span>
											<?php } ?>
											<?php if(isset($this->commentActive) && isset($item->comment_count)) { ?>
												<span title="<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $item->comment_count;?></span>
											<?php } ?>
											<?php if(isset($this->favouriteActive) && isset($item->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)) { ?>
												<span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $item->favourite_count;?></span>
											<?php } ?>
											<?php if(isset($this->viewActive) && isset($item->view_count)) { ?>
												<span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $item->view_count; ?></span>
											<?php } ?>
											<?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/_newsRatingStat.tpl';?>
                    </div>
                  </div>
                </div>
                </div>
            <?php endif;?>
          <?php $bigBlg++;?>
        <?php endforeach;?>
        <?php $bigBlg = 0;?>
      <?php endif;?>
      </div>
		</div>
  <?php endforeach;?>
	<?php if($this->paginatorCategory->getTotalItemCount() == 0 && !$this->is_ajax):?>
		<div class="tip">
			<span>
				<?php echo $this->translate('Nobody has created an news yet.');?>
				<?php if ($this->can_create):?>
					<?php echo $this->translate('Be the first to %1$screate%2$s one!', '<a href="'.$this->url(array('action' => 'create','module'=>'sesnews'), "sesnews_general",true).'">', '</a>'); ?>
				<?php endif; ?>
			</span>
		</div>
	<?php endif; ?> 
	<?php if($this->loadOptionData == 'pagging'): ?>
		<?php echo $this->paginationControl($this->paginatorCategory, null, array("_pagging.tpl", "sesnews"),array('identityWidget'=>$randonNumber)); ?>
	<?php endif; ?>
<?php if(!$this->is_ajax){ ?>
		</div>
	</div>
	<?php if($this->loadOptionData != 'pagging') { ?>
		<div class="sesbasic_view_more" style="display::none;" id="view_more_<?php echo $randonNumber; ?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array('id' => "feed_viewmore_link_$randonNumber", 'class' => 'buttonlink icon_viewmore')); ?> </div>
		<div class="sesbasic_view_more_loading" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"> <img src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sesbasic/externals/images/loading.gif" /> </div>
	<?php  } ?>
<?php } ?>

<script type="text/javascript">
	<?php if($this->loadOptionData == 'auto_load'){ ?>
		scriptJquery( window ).load(function() {
			scriptJquery(window).scroll( function() {
				var heightOfContentDiv_<?php echo $randonNumber; ?> = scriptJquery('#scrollHeightDivSes_<?php echo $randonNumber; ?>').offset().top;
				var fromtop_<?php echo $randonNumber; ?> = scriptJquery(this).scrollTop();
				if(fromtop_<?php echo $randonNumber; ?> > heightOfContentDiv_<?php echo $randonNumber; ?> - 100 && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block' ){
					document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
				}
			});
		});
	<?php } ?>
	viewMoreHide_<?php echo $randonNumber; ?>();
	function viewMoreHide_<?php echo $randonNumber; ?>() {
		if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
		document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginatorCategory->count() == 0 ? 'none' : ($this->paginatorCategory->count() == $this->paginatorCategory->getCurrentPageNumber() ? 'none' : '' )) ?>";
	}
	function viewMore_<?php echo $randonNumber; ?> (){
		document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = 'none';
		document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = '';    
		en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',
			method: 'post',
			'url': en4.core.baseUrl + "widget/index/mod/sesnews/name/<?php echo $this->widgetName; ?>",
			'data': {
			format: 'html',
			page: <?php echo $this->page + 1; ?>,    
			params :'<?php echo json_encode($this->params); ?>', 
			is_ajax : 1,
			identity : '<?php echo $randonNumber; ?>',
			},
			success: function(responseHTML) {
        scriptJquery('#category-news-widget_<?php echo $randonNumber; ?>').append(responseHTML);
				
				document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
        scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
          theme:"minimal-dark"
          });
        
			}
		}));
		return false;
	}
	<?php if(!$this->is_ajax){ ?>
		function paggingNumber<?php echo $randonNumber; ?>(pageNum){
			scriptJquery('.sesbasic_loading_cont_overlay').css('display','block');
			en4.core.request.send(scriptJquery.ajax({
        dataType: 'html',
				method: 'post',
				'url': en4.core.baseUrl + "widget/index/mod/sesnews/name/<?php echo $this->widgetName; ?>",
				'data': {
					format: 'html',
					page: pageNum,    
					params :'<?php echo json_encode($this->params); ?>', 
					is_ajax : 1,
					identity : '<?php echo $randonNumber; ?>',
					type:'<?php echo $this->view_type; ?>'
				},
				success: function(responseHTML) {
					scriptJquery('.sesbasic_loading_cont_overlay').css('display','none');
					document.getElementById('category-news-widget_<?php echo $randonNumber; ?>').innerHTML =  responseHTML;
          scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
          theme:"minimal-dark"
          });
					dynamicWidth();
				}
			}));
			return false;
		}
	<?php } ?>
</script>
