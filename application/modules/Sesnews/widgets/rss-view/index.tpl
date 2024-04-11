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

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>



<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>

<?php if(isset($this->identityForWidget) && !empty($this->identityForWidget)):?>

	<?php $randonNumber = $this->identityForWidget;?>

<?php else:?>

	<?php $randonNumber = $this->identity;?>

<?php endif;?>

<?php if(!$this->is_ajax){ ?>

<?php $baseUrl = $this->layout()->staticBaseUrl; ?>





<div class="sesvide_breadcrumb clear sesbasic_clearfix">

  <!--breadcrumb -->

  <a href="<?php echo $this->url(array('action' => 'browse'), "sesnews_generalrss"); ?>"><?php echo $this->translate("Browse Rss"); ?></a>&nbsp;&raquo;

  <?php echo $this->rss->title; ?>

</div>

<div class="sesnews_browse_cat_top sesbm">

  <?php if(isset($this->rss->title) && !empty($this->rss->title)): ?>

    <div class="sesnews_catview_title"> 

      <?php echo $this->rss->title; ?>

    </div>

  <?php endif; ?>

  <?php if(isset($this->rss->description) && !empty($this->rss->description)): ?>

    <div class="sesnews_catview_des">

      <?php echo $this->rss->description;?>

    </div>

  <?php endif; ?>

</div>



<div id="scrollHeightDivSes_<?php echo $randonNumber; ?>" class="sesbasic_clearfix sesbasic_bxs clear">  

<?php } ?>

   <?php if($this->viewType == 'list'):?>

		<?php if(!$this->is_ajax){ ?>

		<ul class="sesnews_news_listing sesbasic_clearfix clear" id="tabbed-widget_<?php echo $randonNumber; ?>" style="display:block;">

	<?php } ?>

			<?php foreach($this->paginator as $key=>$news):?>

				<li class="sesnews_list_news_view sesbasic_clearfix clear">

					<div class="sesnews_list_thumb sesnews_thumb">

						<a href="<?php echo $news->getHref(); ?>" data-url = "<?php echo $news->getType() ?>" class="sesnews_thumb_img">

							<img src="<?php echo $news->getPhotoUrl(); ?>" alt="" />

						</a>

						<?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->verifiedLabel)):?>

          <div class="sesnews_grid_labels">

            <?php if(isset($this->featuredLabelActive) && $news->featured == 1):?>

              <p class="sesnews_label_featured" title="<?php echo $this->translate('FEATURED');?>"><i class="fa fa-star"></i></p>

            <?php endif;?>

            <?php if(isset($this->sponsoredLabelActive) && $news->sponsored == 1):?>

              <p class="sesnews_label_sponsored" title="<?php echo $this->translate('SPONSORED');?>"><i class="fa fa-star"></i></p>

            <?php endif;?>

             <?php if(isset($this->hotLabelActive) && $news->hot == 1) { ?>

            <p class="sesnews_label_hot" title="<?php echo $this->translate('Hot'); ?>"><i class="fa fa-star"></i></p>

          <?php } ?>

          <?php if(isset($this->newLabelActive) && $news->latest == 1) { ?>

            <p class="sesnews_label_new" title="<?php echo $this->translate('New'); ?>"><i class="fa fa-star"></i></p>

          <?php } ?>

            <?php if(isset($this->verifiedLabelActive) && $news->verified == 1):?>

              <div class="sesnews_grid_verified_label" title="<?php echo $this->translate('VERIFIED');?>"><i class="fa fa-check"></i></div>

            <?php endif;?>

          </div>

        <?php endif;?>

					</div>

					<div class="sesnews_list_info">

          <?php if(isset($this->titleActive)){ ?>

						<div class="sesnews_list_info_title">

							<a href="<?php echo $news->getHref(); ?>"><?php echo $news->getTitle(); ?></a>

						</div>

				  <?php } ?>

						<div class="sesnews_admin_list">

							<div class="sesnews_stats_list sesbasic_text_dark">

								<?php if(isset($this->byActive)){ ?>

									<?php $owner = $news->getOwner();?>

									<span>

										<a href="<?php echo $owner->getHref();?>"><?php echo $this->itemPhoto($owner, 'thumb.icon');?></a>

											<?php echo $this->translate('by');?>

										<a href="<?php echo $owner->getHref();?>"><?php echo $this->translate(' %1$s', $owner->getTitle()); ?></a>

									</span>

								<?php } ?>

							</div>

							<?php if(isset($this->creationDateActive)):?>

								<div class="sesnews_stats_list sesbasic_text_dark">

									<span>

										<i class="far fa-clock"></i>

										<?php echo date('M d, Y',strtotime($news->creation_date));?>		

									</span>

								</div>

							<?php endif;?>

						</div>

						<div class="sesnews_list_contant">

							<?php if(isset($this->descriptionActive)){ ?>

                              <?php echo $news->getDescription($this->description_truncation);?>

							<?php } ?> 

						</div>

						<div class="sesnews_list_stats sesbasic_text_dark">

							<?php if(isset($this->likeActive) && isset($news->like_count)) { ?>

								<span title="<?php echo $this->translate(array('%s like', '%s likes', $news->like_count), $this->locale()->toNumber($news->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $news->like_count; ?></span>

							<?php } ?>

							<?php if(isset($this->commentActive) && isset($news->comment_count)) { ?>

								<span title="<?php echo $this->translate(array('%s comment', '%s comments', $news->comment_count), $this->locale()->toNumber($news->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $news->comment_count;?></span>

							<?php } ?>

							<?php if(isset($this->viewActive) && isset($news->view_count)) { ?>

								<span title="<?php echo $this->translate(array('%s view', '%s views', $news->view_count), $this->locale()->toNumber($news->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $news->view_count; ?></span>

							<?php } ?>

							<?php if(isset($this->favouriteActive) && isset($news->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)) { ?>

								<span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $news->favourite_count), $this->locale()->toNumber($news->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $news->favourite_count; ?></span>

							<?php } ?>

							<?php if(isset($this->ratingActive) && isset($news->rating) && $news->rating > 0 && Engine_Api::_()->sesbasic()->getViewerPrivacy('sesnews_review', 'view')): ?>

								<span  title="<?php echo $this->translate(array('%s rating', '%s ratings', round($news->rating,1)), $this->locale()->toNumber(round($news->rating,1)))?>">

									<i class="far fa-star"></i><?php echo round($news->rating,1).'/5';?><?php echo $this->translate(' Ratings');?>

								</span>

							<?php endif; ?>

						</div>

						<?php if(Engine_Api::_()->getApi('core', 'sesnews')->allowReviewRating() && isset($this->ratingStarActive)):?>

							<?php echo $this->partial('_newsRating.tpl', 'sesnews', array('rating' => $news->rating, 'class' => 'sesnews_list_rating sesnews_list_view_ratting floatL', 'style' => ''));?>

						<?php endif;?>

						<?php if(isset($this->readmoreActive)){ ?>

							<div class="sesnews_list_readmore floatR"><a href="<?php echo $news->getHref();?>" class="sesnews_animation"><?php echo $this->translate('Read More'); ?> <i class="fa fa-long-arrow-alt-right" aria-hidden="true"></i></a></div>

						<?php } ?>

					</div>

				</li>  

		<?php endforeach;?>

		<?php  if(engine_count($this->paginator) == 0){  ?>

			<div class="tip">

				<span>

					<?php echo $this->translate("No news in this rss."); ?>

				</span>

			</div>

		<?php } ?>    

		<?php if($this->loadOptionData == 'pagging'): ?>

			<?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sesnews"),array('identityWidget'=>$randonNumber)); ?>

		<?php endif; ?>

	<?php if(!$this->is_ajax){ ?> 

	</ul>

	<?php } ?>

 <?php else:?>

	<?php if(!$this->is_ajax){ ?> 

	<ul class="sesnews_news_listing sesnews_news_grid_view sesbasic_clearfix clear" id="tabbed-widget_<?php echo $randonNumber; ?>">

	<?php } ?>

			<?php foreach($this->paginator as $key=>$news): ?>

				<li class="sesnews_grid sesnews_catogery_grid_view sesbasic_bxs" style="width:<?php echo $this->width.'px'; ?>">

						<div class="sesnews_grid_inner sesnews_thumb"> 

							<div class="sesnews_grid_thumb" style="height:<?php echo $this->height.'px'; ?>">

								<a href="<?php echo $news->getHref(); ?>" data-url = "<?php echo $news->getType() ?>" class="sesnews_thumb_img">

									<img src="<?php echo $news->getPhotoUrl(); ?>" alt="" />

								</a>

								<?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->hotLabelActive)){ ?>

									<div class="sesnews_list_labels ">

										<?php if(isset($this->featuredLabelActive) && $news->featured == 1){ ?>

											<p class="sesnews_label_featured"><?php echo $this->translate('FEATURED'); ?></p>

										<?php } ?>

										<?php if(isset($this->sponsoredLabelActive) && $news->sponsored == 1){ ?>

											<p class="sesnews_label_sponsored"><?php echo $this->translate("SPONSORED"); ?></p>

										<?php } ?>

									</div>

								<?php } ?>

							</div>

						<div class="sesnews_grid_info clear clearfix sesbm">

						<?php if(Engine_Api::_()->getApi('core', 'sesnews')->allowReviewRating() && isset($this->ratingStarActive)):?>

							<?php echo $this->partial('_newsRating.tpl', 'sesnews', array('rating' => $news->rating, 'class' => 'sesnews_list_rating sesnews_list_view_ratting floatR', 'style' => 'margin-bottom:5px; margin-top:5px;'));?>

						<?php endif;?>

          	<?php if(isset($this->titleActive)){ ?>

							<div class="sesnews_grid_info_title">

								<a href="<?php echo $news->getHref();?>"><?php echo $news->getTitle(); ?></a>

							</div>

							<?php } ?>

					

						<div class="sesnews_grid_meta_block">

							<div class="sesnews_list_stats sesbasic_text_light">

								<?php if(isset($this->byActive)){ ?>

									<?php $owner = $news->getOwner();?>

                    <span>

                      <a href="<?php echo $owner->gethref();?>"><?php echo $this->itemPhoto($owner, 'thumb.icon');?></a>

                        <?php echo $this->translate('by');?>

                      <a href="<?php echo $owner->getHref();?>"><?php echo $this->translate(' %1$s', $owner->getTitle()); ?></a>

                      

                    </span>

									<?php } ?>

									<?php if(isset($this->creationDateActive)) { ?>

                    |

                    <span>

                      <i class="far fa-clock"></i>

                      <?php echo date('M d, Y',strtotime($news->creation_date));?>	

                    </span>

								<?php } ?>

							</div>

						</div>

					</div>

					<div class="sesnews_grid_hover_block">

					<div class="sesnews_grid_info_hover_title">

					<a href="<?php echo $news->getHref();?>"><?php echo $news->getTitle(); ?></a>

					<span></span>

					</div>

					<div class="sesnews_grid_meta_block">

							<div class="sesnews_list_stats sesbasic_text_light">

                  <?php if(isset($this->byActive)){ ?>

                    <?php $owner = $news->getOwner();?>

                    <span>

                      <a href="<?php echo $owner->gethref();?>"><?php echo $this->itemPhoto($owner, 'thumb.icon');?></a>

                        <?php echo $this->translate('by');?>

                      <a href="<?php echo $owner->getHref();?>"><?php echo $this->translate(' %1$s', $owner->getTitle()); ?></a>

                      

                    </span>

									<?php } ?>

									<?php if(isset($this->creationDateActive)) { ?>

                    |

                    <span>

                      <i class="far fa-clock"></i>

                      <?php echo date('M d, Y',strtotime($news->creation_date));?>	

                    </span>

                  <?php } ?>

							</div>

						</div>

          <div class="sesnews_list_contant">

						<?php if(isset($this->descriptionActive)){ ?>

							<?php echo $news->getDescription($this->description_truncation);?>

						<?php } ?> 

					</div>

          

					<div class="sesnews_grid_hover_block_footer">  

						<div class="sesnews_list_stats sesbasic_text_light">

							<?php if(isset($this->likeActive) && isset($news->like_count)) { ?>

								<span title="<?php echo $this->translate(array('%s like', '%s likes', $news->like_count), $this->locale()->toNumber($news->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $news->like_count; ?></span>

							<?php } ?>

							<?php if(isset($this->commentActive) && isset($news->comment_count)) { ?>

								<span title="<?php echo $this->translate(array('%s comment', '%s comments', $news->comment_count), $this->locale()->toNumber($news->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $news->comment_count;?></span>

							<?php } ?>

							<?php if(isset($this->viewActive) && isset($news->view_count)) { ?>

								<span title="<?php echo $this->translate(array('%s view', '%s views', $news->view_count), $this->locale()->toNumber($news->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $news->view_count; ?></span>

							<?php } ?>

							<?php if(isset($this->favouriteActive) && isset($news->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)) { ?>

								<span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $news->favourite_count), $this->locale()->toNumber($news->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $news->favourite_count; ?></span>

							<?php } ?>

							<?php if(isset($this->ratingActive) && isset($news->rating) && $news->rating > 0 ): ?>

								<span  title="<?php echo $this->translate(array('%s rating', '%s ratings', round($news->rating,1)), $this->locale()->toNumber(round($news->rating,1)))?>">

								<i class="far fa-star"></i><?php echo round($news->rating,1).'/5';?>

								</span>

							<?php endif; ?>

						</div>

					<?php if(isset($this->readmoreActive)){ ?>

						<div class="sesnews_grid_read_btn floatR"><a href="<?php echo $news->getHref();?>" class="sesnews_animation"><?php echo $this->translate('Read More '); ?></a></div>

					<?php } ?>

					</div>

					</div>

					</div>

				</li>

			<?php endforeach;?>

			<?php  if(is_countable($this->paginator) &&  engine_count($this->paginator) == 0){  ?>

				<div class="tip">

					<span>

						<?php echo $this->translate("No news in this rss."); ?>

					</span>

				</div>

			<?php } ?>    

			<?php if($this->loadOptionData == 'pagging'){ ?>

				<?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sesnews"),array('identityWidget'=>$randonNumber)); ?>

			<?php } ?>

	<?php if(!$this->is_ajax){ ?> 

	</ul>

	<?php } ;?>

	<?php endif;?>

	<?php if(!$this->is_ajax){ ?>

 </div>

 <?php if($this->loadOptionData != 'pagging'){ ?>

  <div class="sesbasic_load_btn" style="display:none;" id="view_more_<?php echo $randonNumber; ?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > <?php echo $this->htmlLink('javascript:void(0);', $this->translate('<i class="fa fa-sync"></i>View More'), array('id' => "feed_viewmore_link_$randonNumber", 'class' => 'sesbasic_animation sesbasic_link_btn')); ?> </div>

  <div class="sesbasic_view_more_loading" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"> <img src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sesbasic/externals/images/loading.gif" /> </div>

  <?php } ?>

  <script type="application/javascript">

function paggingNumber<?php echo $randonNumber; ?>(pageNum){

	 scriptJquery('.sesbasic_loading_cont_overlay').css('display','block');

	 var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';

    en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',

      method: 'post',

      'url': en4.core.baseUrl + "widget/index/mod/sesnews/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,

      'data': {

        format: 'html',

        page: pageNum,    

				params :<?php echo json_encode($this->params); ?>, 

				is_ajax : 1,

				identity : '<?php echo $randonNumber; ?>',

				type:'<?php echo $this->view_type; ?>'

      },

      success: function(responseHTML) {

				scriptJquery('.sesbasic_loading_cont_overlay').css('display','none');

        document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML =  responseHTML;

				dynamicWidth();

      }

    }));

    return false;

}

</script>

  <?php } ?>

<script type="text/javascript">

var valueTabData ;

	var availableTabs_<?php echo $randonNumber; ?>;


  availableTabs_<?php echo $randonNumber; ?> = <?php echo json_encode($this->defaultOptions); ?>;

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

var defaultOpenTab ;

  viewMoreHide_<?php echo $randonNumber; ?>();

  function viewMoreHide_<?php echo $randonNumber; ?>() {

    if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))

      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";

  }

  function viewMore_<?php echo $randonNumber; ?> (){

    var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';

    document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = 'none';

    document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = '';    

    en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',

      method: 'post',

      'url': en4.core.baseUrl + "widget/index/mod/sesnews/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,

      'data': {

        format: 'html',

        page: <?php echo $this->page + 1; ?>,    

				params :<?php echo json_encode($this->params); ?>, 

				is_ajax : 1,

				identity : '<?php echo $randonNumber; ?>',

      },

      success: function(responseHTML) {

        scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);

				document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';

				dynamicWidth();

      }

    }));

    return false;

  }

<?php if(!$this->is_ajax){ ?>

function dynamicWidth(){

	var objectClass = scriptJquery('.sesnews_cat_news_list_info');

	for(i=0;i<objectClass.length;i++){

			scriptJquery(objectClass[i]).find('div').find('.sesnews_cat_news_list_content').find('.sesnews_cat_news_list_title').width(scriptJquery(objectClass[i]).width());

	}

}

dynamicWidth();

<?php } ?>

</script>

