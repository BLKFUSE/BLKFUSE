<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _sidebartabbedlist.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php  if(!$this->is_ajax): ?>
  <style>
    .displayFN{display:none !important;}
  </style>
  <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); ?> 
<?php endif;?>

<?php if(isset($this->identityForWidget) && !empty($this->identityForWidget)):?>
	<?php $randonNumber = $this->identityForWidget;?>
<?php else:?>
	<?php $randonNumber = $this->identity; ?>
<?php endif;?>
<?php $moduleName = 'sesnews';?>

<?php $counter = 0;?>
<?php  if(isset($this->defaultOptions) && engine_count($this->defaultOptions) == 1): ?>
  <script type="application/javascript">
    scriptJquery('#tab-widget-sesnews-<?php echo $randonNumber; ?>').parent().css('display','none');
    scriptJquery('.sesnews_container_tabbed<?php echo $randonNumber; ?>').css('border','none');
  </script>
<?php endif;?>


<?php $locationArray = array();?>
<?php if(!$this->is_ajax){ ?>
  <div id="scrollHeightDivSes_<?php echo $randonNumber; ?>" class="sesbasic_clearfix sesbasic_bxs clear">
    <ul class="sesnews_news_listing sesbasic_clearfix clear <?php if($this->view_type == 'grid'):?>row<?php endif;?>" id="sidebar-tabbed-widget_<?php echo $randonNumber; ?>" style="min-height:50px;">
<?php } ?>

<?php foreach( $this->paginator as $item ): ?>
  <?php $href = $item->getHref();?>
  <?php $photoPath = $item->getPhotoUrl();?>
  <?php if($this->view_type == 'list'){ ?>
    <li class="sesnews_sidebar_news_list sesbasic_clearfix">
			<div class="sesnews_sidebar_news_list_img" style="height:<?php echo is_numeric($this->height_list) ? $this->height_list.'px' : $this->height_list ?>;width:<?php echo is_numeric($this->width_list) ? $this->width_list.'px' : $this->width_list ?>;">
        <?php $href = $item->getHref();$imageURL = $photoPath;?>
        <a href="<?php echo $href; ?>" data-url = "<?php echo $item->getType() ?>">
          <img src="<?php echo $imageURL; ?>" alt="" />
        </a>
      </div>
      <div class="sesnews_sidebar_news_list_cont">
        <div class="sesnews_sidebar_news_list_title">
          <?php if(strlen($item->getTitle()) > $this->title_truncation_list):?>
            <?php $title = mb_substr($item->getTitle(),0,$this->title_truncation_list).'...';?>
            <?php echo $this->htmlLink($item->getHref(),$title,array('title'=>$item->getTitle()));?>
          <?php else: ?>
            <?php echo $this->htmlLink($item->getHref(),$item->getTitle(),array('title'=>$item->getTitle())  ) ?>
          <?php endif;?>
        </div>
				<?php if(isset($this->byActive) || isset($this->creationDateActive)){ ?>
          <div class="sesnews_sidebar_news_list_date">
          	<?php if(isset($this->byActive)){ ?>
              <?php $owner = $item->getOwner(); ?>
              <span>
                <?php echo $this->translate("by") ?> <?php echo $this->htmlLink($owner->getHref(),$owner->getTitle() ) ?>
              </span>
            <?php } ?>
            <?php if(isset($this->byActive) && isset($this->creationDateActive)){ ?><span>|</span><?php } ?>
            <?php if(isset($this->creationDateActive)){ ?>
              <span title="<?php echo date('M d, Y',strtotime($item->creation_date));?>">
              	<?php echo date('M d, Y',strtotime($item->creation_date));?>
              </span>
            <?php } ?>
          </div>
        <?php } ?>
        <?php if(isset($this->categoryActive)){ ?>
          <?php if($item->category_id != '' && intval($item->category_id) && !is_null($item->category_id)):?> 
            <?php $categoryItem = Engine_Api::_()->getItem('sesnews_category', $item->category_id);?>
            <?php if($categoryItem):?>
              <div class="sesnews_sidebar_news_list_date">
                <i class="far fa-folder-open sesbasic_text_light" title="<?php echo $this->translate('Category'); ?>"></i> 
                <a href="<?php echo $categoryItem->getHref(); ?>"><?php echo $this->translate($categoryItem->category_name); ?></a>
              </div>
            <?php endif;?>
          <?php endif;?>
        <?php } ?>
        <?php if(isset($this->locationActive) && isset($item->location) && $item->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.location', 1)){ ?>
          <div class="sesnews_sidebar_news_list_date">
            <i class="sesbasic_icon_map sesbasic_text_light"></i>
            <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
              <a href="<?php echo $this->url(array('resource_id' => $item->news_id,'resource_type'=>'sesnews_news','action'=>'get-direction'), 'sesbasic_get_direction', true) ;?>" class="opensmoothboxurl"><?php echo $item->location;?></a>
            <?php } else { ?>
              <?php echo $item->location;?>
            <?php } ?>
          </div>
        <?php } ?>
				<div class="sesnews_sidebar_news_list_date sesbasic_text_light">
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
    </li>
  <?php } else if($this->view_type == 'grid'){ ?>
  <li class="col-lg-<?php echo $this->gridblock; ?> col-md-3 col-sm-6 col-12">
  	<div class="sesnews_grid sesbasic_bxs <?php if((isset($this->my_news) && $this->my_news)){ ?>isoptions<?php } ?>">
    <div class="sesnews_grid_inner sesnews_thumb">
      <div class="sesnews_grid_thumb" style="height:<?php echo is_numeric($this->height_grid) ? $this->height_grid.'px' : $this->height_grid ?>;">
        <?php $href = $item->getHref();$imageURL = $photoPath;?>
        <a href="<?php echo $href; ?>" data-url = "<?php echo $item->getType() ?>" class="sesnews_thumb_img">
          <img src="<?php echo $imageURL; ?>" alt="" />
        </a>
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
      
        <?php if(isset($this->categoryActive)){ ?>
          <?php if($item->category_id != '' && intval($item->category_id) && !is_null($item->category_id)):?> 
            <?php $categoryItem = Engine_Api::_()->getItem('sesnews_category', $item->category_id);?>
            <?php if($categoryItem):?>
              <div class="sesnews_grid_memta_title">
                <?php $categoryItem = Engine_Api::_()->getItem('sesnews_category', $item->category_id);?>
                <?php if($categoryItem):?>
                  <span>
                    <a href="<?php echo $categoryItem->getHref(); ?>"><?php echo $categoryItem->category_name; ?></a>
                  </span>
                <?php endif;?>
              </div>
            <?php endif;?>
          <?php endif;?>
        <?php } ?>
      </div>
        <div class="sesnews_grid_info clear clearfix sesbm">
          <?php if(isset($this->titleActive) ){ ?>
            <div class="sesnews_grid_info_title">
            <?php if(strlen($item->getTitle()) > $this->title_truncation_grid):?>
              <?php $title = mb_substr($item->getTitle(),0,$this->title_truncation_grid).'...';?>
              <?php echo $this->htmlLink($item->getHref(),$title,array('title'=>$item->getTitle()) ) ?>
            <?php else: ?>
              <?php echo $this->htmlLink($item->getHref(),$item->getTitle(),array('title'=>$item->getTitle())  ) ?>
            <?php endif; ?>
            </div>
          <?php } ?>
          <div class="sesnews_grid_meta_block">
            <?php if(isset($this->byActive)){ ?>
              <div class="sesnews_list_stats sesbasic_text_light">
                <span>
                  <?php $owner = $item->getOwner(); ?>
                  <?php echo $this->htmlLink($item->getOwner()->getParent(), $this->itemPhoto($item->getOwner()->getParent(), 'thumb.icon')); ?>
                  <?php echo $this->translate("by") ?> <?php echo $this->htmlLink($owner->getHref(),$owner->getTitle() ) ?>&nbsp;|
                </span>
              </div>
            <?php } ?>
            <?php if(isset($this->creationDateActive)): ?>
              <div class="sesnews_list_stats sesbasic_text_light">
                <span><i class=" far fa-clock"></i> <?php echo date('M d',strtotime($item->creation_date));?></span>
              </div>
            <?php endif;?>
            <?php if(isset($this->locationActive) && isset($item->location) && $item->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.location', 1)){ ?>
              <div class="sesnews_list_stats sesnews_list_location sesbasic_text_light">
                <span>
                  <i class="sesbasic_icon_map"></i>
                  <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
                    <a href="<?php echo $this->url(array('resource_id' => $item->news_id,'resource_type'=>'sesnews_news','action'=>'get-direction'), 'sesbasic_get_direction', true) ;?>" class="opensmoothboxurl"><?php echo $item->location;?></a>
                  <?php } else { ?>
                    <?php echo $item->location;?>
                  <?php } ?>
                </span>
              </div>
            <?php } ?>
          </div>
        </div>
        <div class="sesnews_grid_hover_block">
          <div class="sesnews_grid_info_hover_title">
            <?php if(strlen($item->getTitle()) > $this->title_truncation_grid):?>
              <?php $title = mb_substr($item->getTitle(),0,$this->title_truncation_grid).'...';?>
            <?php echo $this->htmlLink($item->getHref(),$title,array('title'=>$item->getTitle()) ) ?>
            <?php else: ?>
              <?php echo $this->htmlLink($item->getHref(),$item->getTitle(),array('title'=>$item->getTitle())  ) ?>
            <?php endif; ?>
            <span></span>
          </div>
          <?php  if(isset($this->descriptiongridActive)){?>
          <div class="sesnews_grid_des clear">
            <?php echo $item->getDescription($this->description_truncation_grid);?>
          </div>
          <?php } ?>
          <div class="sesnews_grid_hover_block_footer">
            <div class="sesnews_list_stats sesbasic_text_light">
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
            <?php if($this->readmoreActive) { ?>
            <div class="sesnews_grid_read_btn floatR"><a href="<?php echo $href; ?>">Read More...</a></div>
            <?php } ?>
          </div>
        </div>
          <?php if((isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive)):?>
        <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
        <div class="sesnews_list_thumb_over"> 
          <a href="<?php echo $href; ?>" data-url = "<?php echo $item->getType() ?>"></a>
          <div class="sesnews_list_grid_thumb_btns">
            <?php if(isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)):?>
              <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item)); ?>

            <?php endif;?>
            <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 ):?>
              <?php $canComment =  $item->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');?>
              <?php if(isset($this->likeButtonActive) && $canComment):?>
                <!--Like Button-->
                <?php $LikeStatus = Engine_Api::_()->sesnews()->getLikeStatus($item->news_id,$item->getType()); ?>
                <a href="javascript:;" data-url="<?php echo $item->news_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesnews_like_sesnews_news <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count; ?></span></a>
              <?php endif;?>
              <?php if(isset($this->favouriteButtonActive) && isset($item->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)): ?>
                <?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesnews')->isFavourite(array('resource_type'=>'sesnews_news','resource_id'=>$item->news_id)); ?>
                <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesnews_favourite_sesnews_news <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $item->news_id ; ?>"><i class="fa fa-heart"></i><span><?php echo $item->favourite_count; ?></span></a>
              <?php endif;?>
            <?php endif;?>
          </div>
        </div>
        <?php endif;?> 
      </div>
     </div>
    </li>
  <?php }?>
<?php endforeach; ?>

<?php  if(  $this->paginator->getTotalItemCount() == 0 &&  (empty($this->widgetType)) && $this->view_type != 'map'){  ?>
  <?php if( isset($this->category) || isset($this->tag) || isset($this->text) ):?>
    <div class="tip">
      <span>
	<?php echo $this->translate('Nobody has posted a news with that criteria.');?>
	<?php if ($this->can_create):?>
	  <?php echo $this->translate('Be the first to %1$spost%2$s one!', '<a href="'.$this->url(array('action' => 'create'), "sesnews_general").'">', '</a>'); ?>
	<?php endif; ?>
      </span>
    </div>
  <?php else:?>
    <div class="tip">
      <span>
	<?php echo $this->translate('Nobody has created a news yet.');?>
	<?php if ($this->can_create):?>
	  <?php echo $this->translate('Be the first to %1$spost%2$s one!', '<a href="'.$this->url(array('action' => 'create'), "sesnews_general").'">', '</a>'); ?>
	<?php endif; ?>
      </span>
    </div>
  <?php endif; ?>
<?php }else if( $this->paginator->getTotalItemCount() == 0 && isset($this->tabbed_widget) && $this->tabbed_widget){?>
  <div class="tip">
    <span>
      <?php $errorTip = ucwords(str_replace('SP',' ',$this->defaultOpenTab)); ?>
      <?php echo $this->translate("There are currently no %s",$errorTip);?>
      <?php if (isset($this->can_create) && $this->can_create):?>
	<?php echo $this->translate('%1$spost%2$s one!', '<a href="'.$this->url(array('action' => 'create'), "sesnews_general").'">', '</a>'); ?>
      <?php endif; ?>
    </span>
  </div>
<?php } ?>
  
<?php if($this->loadOptionData == 'pagging' && !($this->show_limited_data)): ?>
  <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sesnews"),array('identityWidget'=>$randonNumber)); ?>
<?php endif;?>
  
<?php if(!$this->is_ajax){ ?>
  </ul>
  <?php if($this->loadOptionData != 'pagging' && !($this->show_limited_data)):?>
    <div class="sesbasic_view_more sesnews_news_listing_more" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array('id' => "feed_viewmore_link_$randonNumber", 'class' => 'buttonlink fa fa-arrow-circle-down')); ?> </div>
    <div class="sesbasic_view_more_loading sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"> <img src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Core/externals/images/loading.gif" /> </div>
  <?php endif;?>
  </div>

  <script type="text/javascript">
    
    var valueTabData ;
    
		<?php if($this->loadOptionData == 'auto_load' && !($this->show_limited_data)){ ?>
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
    scriptJquery(document).on('click','.selectView_<?php echo $randonNumber; ?>',function(){
      if(scriptJquery(this).hasClass('active'))
      return;
      if(document.getElementById("view_more_<?php echo $randonNumber; ?>"))
      document.getElementById("view_more_<?php echo $randonNumber; ?>").style.display = 'none';
      document.getElementById("sidebar-tabbed-widget_<?php echo $randonNumber; ?>").innerHTML = "<div class='clear sesbasic_loading_container' style='margin-top:10px;'></div>";
      scriptJquery('#sesnews_grid_view_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#sesnews_list_view_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display','none');
      scriptJquery('#loading_image_<?php echo $randonNumber; ?>').css('display','none');
      scriptJquery(this).addClass('active');
//       if (typeof(requestTab_<?php echo $randonNumber; ?>) != 'undefined') {
// 				requestTab_<?php echo $randonNumber; ?>.cancel();
//       }
//       if (typeof(requestViewMore_<?php echo $randonNumber; ?>) != 'undefined') {
// 				requestViewMore_<?php echo $randonNumber; ?>.cancel();
//       }
      requestTab_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
        dataType: 'html',
				method: 'post',
				'url': en4.core.baseUrl + "widget/index/mod/"+"<?php echo $moduleName;?>"+"/name/<?php echo $this->widgetName; ?>/openTab/" + defaultOpenTab,
				'data': {
					format: 'html',
					page: 1,
					type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
					params : <?php echo json_encode($this->params); ?>, 
					is_ajax : 1,
					identity : '<?php echo $randonNumber; ?>',
				},
				success: function(responseHTML) {
					document.getElementById('sidebar-tabbed-widget_<?php echo $randonNumber; ?>').innerHTML = responseHTML;
          if(scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel') == 'grid') {
            scriptJquery('#sidebar-tabbed-widget_<?php echo $randonNumber; ?>').addClass('row');
          } else {
            scriptJquery('#sidebar-tabbed-widget_<?php echo $randonNumber; ?>').removeClass('row');
          }
					if(document.getElementById("loading_image_<?php echo $randonNumber; ?>"))
					document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
				}
      }));
    });
  </script>
<?php } ?>
<?php if(isset($this->optionsListGrid['paggindData']) || isset($this->loadJs)){ ?>
	<script type="text/javascript">
		var defaultOpenTab = '<?php echo $this->defaultOpenTab; ?>';
		var params<?php echo $randonNumber; ?> = <?php echo json_encode($this->params); ?>;
		var identity<?php echo $randonNumber; ?>  = '<?php echo $randonNumber; ?>';
		var page<?php echo $randonNumber; ?> = '<?php echo $this->page + 1; ?>';
		var searchParams<?php echo $randonNumber; ?> ;
		<?php if($this->loadOptionData != 'pagging'){ ?>
			viewMoreHide_<?php echo $randonNumber; ?>();	
			function viewMoreHide_<?php echo $randonNumber; ?>() {
				if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
				document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
			}
			function viewMore_<?php echo $randonNumber; ?> (){
				scriptJquery('#view_more_<?php echo $randonNumber; ?>').hide();
				scriptJquery('#loading_image_<?php echo $randonNumber; ?>').show(); 
				var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
				//document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = 'none';
				//document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = '';    
				requestViewMore_<?php echo $randonNumber; ?> = scriptJquery.ajax({
          dataType: 'html',
					method: 'post',
					'url': en4.core.baseUrl + "widget/index/mod/"+"<?php echo $moduleName;?>"+"/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
					'data': {
						format: 'html',
						page: page<?php echo $randonNumber; ?>,    
						params : params<?php echo $randonNumber; ?>, 
						is_ajax : 1,
						view_more:1,
						identity : '<?php echo $randonNumber; ?>',
						type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
						identityObject:'<?php echo isset($this->identityObject) ? $this->identityObject : "" ?>'
					},
					success: function(responseHTML) {
						if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
						scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
						if(document.getElementById('loadingimgsesnews-wrapper'))
						scriptJquery('#loadingimgsesnews-wrapper').hide();
						if(document.getElementById('map-data_<?php echo $randonNumber;?>') )
						scriptJquery('#map-data_<?php echo $randonNumber;?>').remove();
							scriptJquery('#sidebar-tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
						document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
					}
				});
				
				return false;
			}
		<?php }else{ ?>
			function paggingNumber<?php echo $randonNumber; ?>(pageNum){
				scriptJquery('.sesbasic_loading_cont_overlay').css('display','block');
				var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
				requestViewMore_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
          dataType: 'html',
					method: 'post',
					'url': en4.core.baseUrl + "widget/index/mod/"+"<?php echo $moduleName;?>"+"/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
					'data': {
						format: 'html',
						page: pageNum,    
						params :params<?php echo $randonNumber; ?> , 
						is_ajax : 1,
						identity : identity<?php echo $randonNumber; ?>,
						type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
					},
					success: function(responseHTML) {
						if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
						scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
						if(document.getElementById('loadingimgsesnews-wrapper'))
						scriptJquery('#loadingimgsesnews-wrapper').hide();
						scriptJquery('.sesbasic_loading_cont_overlay').css('display','none');
						document.getElementById('sidebar-tabbed-widget_<?php echo $randonNumber; ?>').innerHTML =  responseHTML;
					}
				}));
				
				return false;
			}
		<?php } ?>
	</script>
<?php } ?>

<?php if(!$this->is_ajax): ?>
	<script type="application/javascript">
		scriptJquery(document).on('click',function(){
			scriptJquery('.sesnews_list_option_toggle').removeClass('open');
		});
	</script> 
<?php endif;?>
<!--End Map Work-->
