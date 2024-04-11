<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _showNewsListGrid.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php  if(!$this->is_ajax): ?>
  <style>
    .displayFN{display:none !important;}
  </style>
  <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); ?> 
  <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/styles.css'); ?> 
	<?php if(is_array($this->optionsEnable) && engine_in_array('pinboard',$this->optionsEnable)):?>
		<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl .'application/modules/Sesbasic/externals/scripts/imagesloaded.pkgd.js');?>
		<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/wookmark.min.js');?>
		<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/pinboardcomment.js');?>
	<?php endif;?>
<?php endif;?>

<?php if(isset($this->identityForWidget) && !empty($this->identityForWidget)):?>
	<?php $randonNumber = $this->identityForWidget;?>
<?php else:?>
	<?php $randonNumber = $this->identity; ?>
<?php endif;?>

<?php if($this->profile_news == true):?>
  <?php $moduleName = 'sesnews';?>
<?php else:?>
  <?php $moduleName = 'sesnews';?>
<?php endif;?>

<?php $counter = 0;?>
<?php  if(isset($this->defaultOptions) && engine_count($this->defaultOptions) == 1): ?>
  <script type="application/javascript">
      en4.core.runonce.add(function() {
          scriptJquery('#tab-widget-sesnews-<?php echo $randonNumber; ?>').parent().css('display', 'none');
          scriptJquery('.sesnews_container_tabbed<?php echo $randonNumber; ?>').css('border', 'none');
      });
  </script>
<?php endif;?>

<?php if(!$this->is_ajax){ ?>
	<div class="sesbasic_view_type sesbasic_clearfix clear" style="display:<?php echo $this->bothViewEnable ? 'block' : 'none'; ?>;height:<?php echo $this->bothViewEnable ? '' : '0px'; ?>">
		<?php if(isset($this->show_item_count) && $this->show_item_count){ ?>
			<div class="sesbasic_clearfix sesbm sesnews_search_result" style="display:<?php !$this->is_ajax ? 'block' : 'none'; ?>" id="<?php echo !$this->is_ajax ? 'paginator_count_sesnews' : 'paginator_count_ajax_sesnews' ?>"><span id="total_item_count_sesnews" style="display:inline-block;"><?php echo $this->paginator->getTotalItemCount(); ?></span> <?php echo $this->paginator->getTotalItemCount() == 1 ?  $this->translate("news found.") : $this->translate("news found."); ?></div>
		<?php } ?>
		<div class="sesbasic_view_type_options sesbasic_view_type_options_<?php echo $randonNumber; ?>">
			<?php if(is_array($this->optionsEnable) && engine_in_array('list',$this->optionsEnable)){ ?>
				<a href="javascript:;" rel="list" id="sesnews_list_view_<?php echo $randonNumber; ?>" class="listicon selectView_<?php echo $randonNumber; ?> <?php if($this->view_type == 'list') { echo 'active'; } ?>" title="<?php echo ((isset($this->htmlTitle) && !empty($this->htmlTitle)) || (empty($this->htmlTitle) && !isset($this->htmlTitle)) ) ? $this->translate('List View') : '' ; ?>"></a>
			<?php } ?>
			<?php if(is_array($this->optionsEnable) && engine_in_array('grid',$this->optionsEnable)){ ?>
				<a href="javascript:;" rel="grid" id="sesnews_grid_view_<?php echo $randonNumber; ?>" class="a-gridicon selectView_<?php echo $randonNumber; ?> <?php if($this->view_type == 'grid') { echo 'active'; } ?>" title="<?php echo ((isset($this->htmlTitle) && !empty($this->htmlTitle)) || (empty($this->htmlTitle) && !isset($this->htmlTitle)) ) ? $this->translate('Grid View') : '' ; ?>"></a>
			<?php } ?>
			<?php if(is_array($this->optionsEnable) && engine_in_array('pinboard',$this->optionsEnable)){ ?>
				<a href="javascript:;" rel="pinboard" id="sesnews_pinboard_view_<?php echo $randonNumber; ?>" class="boardicon selectView_<?php echo $randonNumber; ?> <?php if($this->view_type == 'pinboard') { echo 'active'; } ?>" title="<?php echo ((isset($this->htmlTitle) && !empty($this->htmlTitle)) || (empty($this->htmlTitle) && !isset($this->htmlTitle)) ) ? $this->translate('Pinboard View') : '' ; ?>"></a>
			<?php } ?>
			<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1) && is_array($this->optionsEnable) && engine_in_array('map',$this->optionsEnable) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews_enable_location', 1)){ ?>
				<a title="<?php echo ((isset($this->htmlTitle) && !empty($this->htmlTitle)) || (empty($this->htmlTitle) && !isset($this->htmlTitle)) ) ? $this->translate('Map View') : '' ; ?>" id="sesnews_map_view_<?php echo $randonNumber; ?>" class="mapicon map_selectView_<?php echo $randonNumber;?> selectView_<?php echo $randonNumber;?> <?php if($this->view_type == 'map') { echo 'active'; } ?>" rel="map" href="javascript:;"></a>
			<?php } ?>
		</div>
	</div>
<?php } ?>
<?php $locationArray = array();?>
<?php if(!$this->is_ajax){ ?>
  <div id="scrollHeightDivSes_<?php echo $randonNumber; ?>" class="sesbasic_clearfix sesbasic_bxs clear">
    <ul class="sesnews_news_listing sesbasic_clearfix clear <?php if($this->view_type == 'grid'):?>row<?php endif;?> <?php if($this->view_type == 'pinboard'):?>sesbasic_pinboard_<?php echo $randonNumber;?><?php endif;?>" id="tabbed-widget_<?php echo $randonNumber; ?>" style="min-height:50px;">
<?php } ?>

<?php foreach( $this->paginator as $item ):?>
  <?php $href = $item->getHref();?>
  <?php $photoPath = $item->getPhotoUrl();?>
  <?php if($this->view_type == 'grid'){ ?>
    <?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/_gridView.tpl';?>
  <?php }else if($this->view_type == 'list'){ ?>
    <?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/_listView.tpl';?>
  <?php }else if(isset($this->view_type) && $this->view_type == 'pinboard'){ ?>
    <?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/_pinboardView.tpl';?>
  <?php } elseif($this->view_type == 'map') {
  		$news = $item;
   ?>
  <?php $location = '';?>
	<?php if($news->lat): ?>
		<?php $labels = '';?>
		<?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive)):?>
			<?php $labels .= "<div class=\"sesnews_grid_labels\">";?>
			<?php if(isset($this->featuredLabelActive) && $news->featured == 1):?>
				<?php $labels .= "<p class=\"sesnews_label_featured\">".$this->translate('FEATURED')."</p>";?>
			<?php endif;?>
			<?php if(isset($this->sponsoredLabelActive) && $news->sponsored == 1):?>
				<?php $labels .= "<p class=\"sesnews_label_sponsored\">".$this->translate('SPONSORED')."</p>";?>
			<?php endif;?>
			<?php $labels .= "</div>";?>
		<?php endif;?>
		<?php $vlabel = '';?>
		<?php if(isset($this->verifiedLabelActive) && $news->verified == 1) :?>
			<?php $vlabel = "<div class=\"sesnews_verified_label\" title=\"VERIFIED\"><i class=\"fa fa-check\"></i></div>";?>
		<?php endif;?>
		<?php if(isset($this->locationActive) && $news->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.location', 1)):?>
			<?php $locationText = $this->translate('Location');?>
			<?php $locationvalue = $news->location;?>
    <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
			<?php $location = "<div class=\"sesnews_list_stats sesnews_list_location sesbasic_text_light\">
			<span class=\"widthfull\">
			<i class=\"sesbasic_icon_map\" title=\"$locationText\"></i>
			<span title=\"$locationvalue\"><a href='".$this->url(array('resource_id' => $news->news_id,'resource_type'=>'sesnews_news','action'=>'get-direction'), 'sesbasic_get_direction', true)."' class=\"opensmoothboxurl\">$locationvalue</a></span>
			</span>
			</div>";?>
    <?php } else { ?>
      <?php $location = "<div class=\"sesnews_list_stats sesnews_list_location sesbasic_text_light\">
			<span class=\"widthfull\">
			<i class=\"sesbasic_icon_map\" title=\"$locationText\"></i>
			<span title=\"$locationvalue\">$locationvalue</span>
			</span>
			</div>";?>
    <?php } ?>
		<?php endif;?>
		<?php $likeButton = '';?>
		<?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($this->likeButtonActive)):?>
		<?php $LikeStatus = Engine_Api::_()->sesbasic()->getLikeStatus($news->news_id,$news->getType());?>
			<?php $likeClass = ($LikeStatus) ? ' button_active' : '' ;?>
			<?php $likeButton = '<a href="javascript:;" data-url="'.$news->getIdentity().'" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesnews_like_sesnews_news_'. $news->news_id.' sesnews_like_sesnews_news '.$likeClass .'"> <i class="fa fa-thumbs-up"></i><span>'.$news->like_count.'</span></a>';?>
		<?php endif;?>
		<?php $favouriteButton = '';?>
		<?php if(isset($this->favouriteButtonActive) && Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($news->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)){
			$favStatus = Engine_Api::_()->getDbtable('favourites', 'sesnews')->isFavourite(array('resource_type'=>'sesnews_news','resource_id'=>$news->news_id));
			$favClass = ($favStatus)  ? 'button_active' : '';
			$favouriteButton = '<a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn  sesnews_favourite_sesnews_news_'. $news->news_id.' sesnews_favourite_sesnews_news '.$favClass .'" data-url="'.$news->getIdentity().'"><i class="fa fa-heart"></i><span>'.$news->favourite_count.'</span></a>';
		}?>
		<?php $ratings = '';?>
		<?php if(Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'view') && Engine_Api::_()->getApi('core', 'sesnews')->allowReviewRating() && isset($this->ratingStarActive)):?>
			<?php if( $news->rating > 0 ): ?>
				<?php $ratings .= '<div class="sesnews_list_grid_rating" title="'.$this->translate(array('%s rating', '%s ratings', $news->rating), $this->locale()->toNumber($news->rating)).'">';?>
				<?php for( $x=1; $x<= $news->rating; $x++ ):?>
					<?php $ratings .= '<span class="sesbasic_rating_star_small fa fa-star"></span>';?>
				<?php endfor;?> 
				<?php if( (round($news->rating) - $news->rating) > 0):?>
					<?php $ratings.= '<span class="sesbasic_rating_star_small fa fa-star-half"></span>';?>
				<?php endif;?>
				<?php $ratings .= '</div>';?>
			<?php endif;?>
		<?php endif;?>
		<?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $news->getHref());?>
		<?php $stats = $ratings.'<div class="sesbasic_largemap_stats sesnews_list_stats sesbasic_clearfix"><span class="widthfull">';
		if(isset($this->commentActive)){
			$stats .= '<span title="'.$this->translate(array('%s comment', '%s comments', $news->comment_count), $this->locale()->toNumber($news->comment_count)).'"><i class="sesbasic_icon_comment_o"></i>'.$news->comment_count.'</span>';
		}
		if(isset($this->favouriteActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)){
			$stats .= '<span title="'.$this->translate(array('%s favourite', '%s favourites', $news->favourite_count), $this->locale()->toNumber($news->favourite_count)).'"><i class="sesbasic_icon_favourite_o"></i>'. $news->favourite_count.'</span>';
		}
		if(isset($this->viewActive)){
			$stats .= '<span title="'. $this->translate(array('%s view', '%s views', $news->view_count), $this->locale()->toNumber($news->view_count)).'"><i class="sesbasic_icon_view"></i>'.$news->view_count.'</span>';
		}
		if(isset($this->likeActive)){
			$stats .= '<span title="'.$this->translate(array('%s like', '%s likes', $news->like_count), $this->locale()->toNumber($news->like_count)).'"><i class="sesbasic_icon_like_o"></i>'.$news->like_count.'</span> ';
		}
		if(isset($this->ratingActive) && Engine_Api::_()->sesbasic()->getViewerPrivacy('sesnews_review', 'view')){
			$stats .= '<span  title="'.$this->translate(array('%s rating', '%s ratings', round($news->rating,1)), $this->locale()->toNumber(round($news->rating,1))).'"><i class="far fa-star"></i>'. round($news->rating,1).'/5'.'</span>';
		}
		?>
	  <?php $stats .= '<span></div>';?>
    <?php if(isset($this->socialSharingActive)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)):?>
    
    <?php $socialshareIcons = $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $news, 'socialshare_enable_plusicon' => $this->socialshare_enable_mapviewplusicon, 'socialshare_icon_limit' => $this->socialshare_icon_mapviewlimit, 'params' => 'feed')); ?>
     <?php  $socialshare = '<div class="sesnews_list_grid_thumb_btns">'.$socialshareIcons.$likeButton.$favouriteButton.'</div>';?>
    <?php else:?>
			<?php $socialshare = $likeButton.$favouriteButton;?>
    <?php endif;?>
		<?php $owner = $news->getOwner();
			$owner =  '<div class="sesnews_grid_date sesnews_list_stats sesbasic_text_light"><span><i class="far fa-user"></i>'.$this->translate("by ") .$this->htmlLink($owner->getHref(),$owner->getTitle() ).'</span></div>';
			$locationArray[$counter]['id'] = $news->getIdentity();
			$locationArray[$counter]['owner'] = $owner;
			$locationArray[$counter]['location'] = $location;
			$locationArray[$counter]['stats'] = $stats;
			$locationArray[$counter]['socialshare'] = $socialshare;
			$locationArray[$counter]['lat'] = $news->lat;
			$locationArray[$counter]['lng'] = $news->lng;
      $locationArray[$counter]['labels'] = $labels;
      $locationArray[$counter]['vlabel'] = $vlabel;
			$locationArray[$counter]['iframe_url'] = '';
			$locationArray[$counter]['image_url'] = $news->getPhotoUrl();
			$locationArray[$counter]['sponsored'] = $news->sponsored;
			$locationArray[$counter]['title'] = '<a href="'.$news->getHref().'">'.$news->getTitle().'</a>';  
			$counter++;?>
  <?php endif;?>
  
  
  <?php } ?>
<?php endforeach; ?>
<?php if($this->view_type == 'map'):?>
  <div id="map-data_<?php echo $randonNumber;?>" style="display:none;"><?php echo json_encode($locationArray,JSON_HEX_QUOT | JSON_HEX_TAG); ?></div>
  <?php if(!$this->view_more || $this->is_search):?>
    <ul id="sesnews_map_view_<?php echo $randonNumber;?>">
      <div id="map-canvas-<?php echo $randonNumber;?>" class="map sesbasic_large_map sesbm sesbasic_bxs"></div>
    </ul>
  <?php endif;?>
<?php endif;?>
<?php  if(  $this->paginator->getTotalItemCount() == 0 &&  (empty($this->widgetType)) && $this->view_type != 'map'){  ?>
  <?php if( isset($this->category) || isset($this->tag) || isset($this->text) ):?>
    <div class="tip">
      <span>
	<?php echo $this->translate('Nobody has posted a news with that criteria.');?>
	<?php if ($this->can_create && empty($this->type)):?>
	  <?php echo $this->translate('Be the first to %1$spost%2$s one!', '<a href="'.$this->url(array('action' => 'create'), "sesnews_general").'">', '</a>'); ?>
	<?php endif; ?>
      </span>
    </div>
  <?php else:?>
    <div class="tip">
      <span>
	<?php echo $this->translate('Nobody has created a news yet.');?>
	<?php if ($this->can_create && empty($this->type)):?>
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
      <?php if (isset($this->can_create) && $this->can_create && empty($this->type)):?>
	<?php echo $this->translate('%1$spost%2$s one!', '<a href="'.$this->url(array('action' => 'create'), "sesnews_general").'">', '</a>'); ?>
      <?php endif; ?>
    </span>
  </div>
<?php } ?>
  
<?php if($this->loadOptionData == 'pagging' && (empty($this->show_limited_data) || $this->show_limited_data  == 'no')): ?>
  <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sesnews"),array('identityWidget'=>$randonNumber)); ?>
<?php endif;?>
  
<?php if(!$this->is_ajax){ ?>
  </ul>
  <?php if($this->loadOptionData != 'pagging' && (empty($this->show_limited_data) || $this->show_limited_data  == 'no')):?>
    <div class="sesbasic_load_btn" style="display: none;" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > <?php echo $this->htmlLink('javascript:void(0);', $this->translate('<i class="fa fa-sync"></i>View More'), array('id' => "feed_viewmore_link_$randonNumber", 'class' => 'sesbasic_animation sesbasic_link_btn')); ?> </div>
    <div class="sesbasic_view_more_loading sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"> <img src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sesbasic/externals/images/loading.gif" /> </div>
  <?php endif;?>
  </div>

  <script type="text/javascript">
    
    var valueTabData ;
    
		<?php if($this->loadOptionData == 'auto_load' && (empty($this->show_limited_data) || $this->show_limited_data  == 'no')){ ?>
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
      document.getElementById("tabbed-widget_<?php echo $randonNumber; ?>").innerHTML = "<div class='clear sesbasic_loading_container'></div>";
      scriptJquery('#sesnews_grid_view_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#sesnews_list_view_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#sesnews_map_view_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#sesnews_pinboard_view_<?php echo $randonNumber; ?>').removeClass('active');
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
					searchParams: searchParams<?php echo $randonNumber; ?>,
					identity : '<?php echo $randonNumber; ?>',
				},
				success: function(responseHTML) {
					scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').html(responseHTML);
					
          if(scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel') == 'grid') {
            scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('row');
          } else {
            scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').removeClass('row');
          }
					if(document.getElementById("loading_image_<?php echo $randonNumber; ?>"))
					document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
					if(document.getElementById('map-data_<?php echo $randonNumber;?>') && scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber;?>').find('.active').attr('rel') == 'map'){
						var mapData = scriptJquery.parseJSON(document.getElementById('map-data_<?php echo $randonNumber;?>').innerHTML);
						if(scriptJquery.isArray(mapData) && scriptJquery(mapData).length) {
							oldMapData_<?php echo $randonNumber; ?> = [];
							newMapData_<?php echo $randonNumber ?> = mapData;
							loadMap_<?php echo $randonNumber ?> = true;
							scriptJquery.merge(oldMapData_<?php echo $randonNumber; ?>, newMapData_<?php echo $randonNumber ?>);
							initialize_<?php echo $randonNumber?>();	
							mapFunction_<?php echo $randonNumber?>();
						}
						else {
							scriptJquery('#map-data_<?php echo $randonNumber; ?>').html('');
							initialize_<?php echo $randonNumber?>();	
						}
					}
					pinboardLayout_<?php echo $randonNumber ?>('true');
				}
      }));
    });
  </script>
<?php } ?>
  
<?php if(!$this->is_ajax){ ?>
	<script type="application/javascript">
		var wookmark = undefined;
		//Code for Pinboard View
		var wookmark<?php echo $randonNumber ?>;
		function pinboardLayout_<?php echo $randonNumber ?>(force){
			if(scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber; ?>').find('.active').attr('rel') != 'pinboard'){
				scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').removeClass('sesbasic_pinboard_<?php echo $randonNumber; ?>');
				scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').css('height','auto');
				scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').css('display','');
				return;
			}
			//scriptJquery('.new_image_pinboard_<?php echo $randonNumber; ?>').css('display','none');
			var imgLoad = imagesLoaded('#tabbed-widget_<?php echo $randonNumber; ?>');
			imgLoad.on('progress',function(instance,image){
				scriptJquery(image.img).parent().parent().parent().parent().parent().show();
				scriptJquery(image.img).parent().parent().parent().parent().parent().removeClass('new_image_pinboard_<?php echo $randonNumber; ?>');
				imageLoadedAll<?php echo $randonNumber ?>(force);
			});
		}
		function imageLoadedAll<?php echo $randonNumber ?>(force){
			scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('sesbasic_pinboard_<?php echo $randonNumber; ?>');
			scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('sesbasic_pinboard');
			if (typeof wookmark<?php echo $randonNumber ?> == 'undefined' || typeof force != 'undefined') {
				(function() {
					function getWindowWidth() {
						return Math.max(document.documentElement.clientWidth, window.innerWidth || 0)
					}				
					wookmark<?php echo $randonNumber ?> = new Wookmark('.sesbasic_pinboard_<?php echo $randonNumber; ?>', {
						itemWidth: <?php echo isset($this->width_pinboard) ? str_replace(array('px','%'),array(''),$this->width_pinboard) : '300'; ?>, // Optional min width of a grid item
						outerOffset: 0, // Optional the distance from grid to parent
           <?php if($orientation = ($this->layout()->orientation == 'right-to-left')){ ?>
              align:'right',
            <?php }else{ ?>
              align:'left',
            <?php } ?>
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
     scriptJquery(window).resize(function(e){
      pinboardLayout_<?php echo $randonNumber ?>('',true);
     });
		<?php if($this->view_type == 'pinboard'):?>
        en4.core.runonce.add(function() {
				pinboardLayout_<?php echo $randonNumber ?>();
			});
		<?php endif;?>
	</script>
<?php } ?>
<?php if(isset($this->optionsListGrid['paggindData']) || isset($this->loadJs)){ ?>
	<script type="text/javascript">
		var defaultOpenTab = '<?php echo $this->defaultOpenTab; ?>';
		var params<?php echo $randonNumber; ?> = <?php echo json_encode($this->params); ?>;
		var identity<?php echo $randonNumber; ?>  = '<?php echo $randonNumber; ?>';
		var page<?php echo $randonNumber; ?> = '<?php echo $this->page + 1; ?>';
		var searchParams<?php echo $randonNumber; ?> ;
		var is_search_<?php echo $randonNumber;?> = 0;
		<?php if($this->loadOptionData != 'pagging'){ ?>
            en4.core.runonce.add(function() {
				viewMoreHide_<?php echo $randonNumber; ?>();
			});
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
// 				if(typeof requestViewMore_<?php echo $randonNumber; ?>  != "undefined"){
//                     requestViewMore_<?php echo $randonNumber; ?>.cancel();
//                 }
				requestViewMore_<?php echo $randonNumber; ?> = scriptJquery.ajax({
          dataType: 'html',
					method: 'post',
					'url': en4.core.baseUrl + "widget/index/mod/"+"<?php echo $moduleName;?>"+"/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
					'data': {
						format: 'html',
						page: page<?php echo $randonNumber; ?>,    
						params : params<?php echo $randonNumber; ?>, 
						is_ajax : 1,
						is_search:is_search_<?php echo $randonNumber;?>,
						view_more:1,
						searchParams:searchParams<?php echo $randonNumber; ?> ,
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
						if(!isSearch) {
							scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
						}
						else {
							scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
							oldMapData_<?php echo $randonNumber; ?> = [];
							isSearch = false;
						}
						
						if(document.getElementById('map-data_<?php echo $randonNumber;?>') && scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber; ?>').find('.active').attr('rel') == 'map') {
							if(document.getElementById('sesnews_map_view_<?php echo $randonNumber;?>'))	
							document.getElementById('sesnews_map_view_<?php echo $randonNumber;?>').style.display = 'block';
							var mapData = scriptJquery.parseJSON(scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').find('#map-data_<?php echo $randonNumber; ?>').html());
							if(scriptJquery.isArray(mapData) && scriptJquery(mapData).length) {
								newMapData_<?php echo $randonNumber ?> = mapData;
								for(var i=0; i < mapData.length; i++) {
									var isInsert = 1;
									for(var j= 0;j < oldMapData_<?php echo $randonNumber; ?>.length; j++){
										if(oldMapData_<?php echo $randonNumber; ?>[j]['id'] == mapData[i]['id']){
											isInsert = 0;
											break;
										}
									}
									if(isInsert){
										oldMapData_<?php echo $randonNumber; ?>.push(mapData[i]);
									}
								}	
								loadMap_<?php echo $randonNumber;?> = true;
								mapFunction_<?php echo $randonNumber?>();
							}else{
								if(typeof  map_<?php echo $randonNumber;?> == 'undefined'){
									scriptJquery('#map-data_<?php echo $randonNumber; ?>').html('');
									initialize_<?php echo $randonNumber?>();	
								}	
							}
						}
						else{
							oldMapData_<?php echo $randonNumber; ?> = [];	
						}
						document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
						pinboardLayout_<?php echo $randonNumber ?>();
					}
				});
				
				return false;
			}
		<?php }else{ ?>
			function paggingNumber<?php echo $randonNumber; ?>(pageNum){
				scriptJquery('.sesbasic_loading_cont_overlay').css('display','block');
				var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
//                 if(typeof requestViewMore_<?php echo $randonNumber; ?>  != "undefined"){
//                     requestViewMore_<?php echo $randonNumber; ?>.cancel();
//                 }
				requestViewMore_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
          dataType: 'html',
					method: 'post',
					'url': en4.core.baseUrl + "widget/index/mod/"+"<?php echo $moduleName;?>"+"/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
					'data': {
						format: 'html',
						page: pageNum,    
						params :params<?php echo $randonNumber; ?> , 
						is_ajax : 1,
						searchParams:searchParams<?php echo $randonNumber; ?>  ,
						identity : identity<?php echo $randonNumber; ?>,
						type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
					},
					success: function(responseHTML) {
						if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
						scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
						if(document.getElementById('loadingimgsesnews-wrapper'))
						scriptJquery('#loadingimgsesnews-wrapper').hide();
						scriptJquery('.sesbasic_loading_cont_overlay').css('display','none');
						document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML =  responseHTML;
						if(isSearch){
							oldMapData_<?php echo $randonNumber; ?> = [];
							isSearch = false;
						}
						
						if(document.getElementById('map-data_<?php echo $randonNumber;?>') && scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber;?>').find('.active').attr('rel') == 'map'){
							var mapData = scriptJquery.parseJSON(scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').find('#map-data_<?php echo $randonNumber; ?>').html());
							if(scriptJquery.isArray(mapData) && scriptJquery(mapData).length) {
							oldMapData_<?php echo $randonNumber; ?> = [];
							newMapData_<?php echo $randonNumber ?> = mapData;
							loadMap_<?php echo $randonNumber ?> = true;
							scriptJquery.merge(oldMapData_<?php echo $randonNumber; ?>, newMapData_<?php echo $randonNumber ?>);
							mapFunction_<?php echo $randonNumber?>();
							}
							else{
								scriptJquery('#map-data_<?php echo $randonNumber; ?>').html('');
								initialize_<?php echo $randonNumber?>();	
							}
						}
						else{
							oldMapData_<?php echo $randonNumber; ?> = [];	
						}
						pinboardLayout_<?php echo $randonNumber ?>();
					}
				}));
				
				return false;
			}
		<?php } ?>
	</script>
<?php } ?>

<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
<!--Start Map Work on Page Load-->
<?php if(!$this->is_ajax): ?>
	<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/styles.css'); ?>
	<script src="<?php echo $this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/marker.js'; ?>"></script>
	<script type="application/javascript">
		scriptJquery(document).on('click',function(){
			scriptJquery('.sesnews_list_option_toggle').removeClass('open');
		});
		var  loadMap_<?php echo $randonNumber;?> = false;
		var newMapData_<?php echo $randonNumber ?> = [];
		function mapFunction_<?php echo $randonNumber?>(){
			if(!map_<?php echo $randonNumber;?> || loadMap_<?php echo $randonNumber;?>){
			initialize_<?php echo $randonNumber?>();
			loadMap_<?php echo $randonNumber;?> = false;
			}
			if(scriptJquery('.map_selectView_<?php echo $randonNumber;?>').hasClass('active')) {
				if(!newMapData_<?php echo $randonNumber ?>)
				return false;
				<?php if($this->loadOptionData == 'pagging'){ ?>DeleteMarkers_<?php echo $randonNumber ?>();<?php }?>
				google.maps.event.trigger(map_<?php echo $randonNumber;?>, "resize");
				markerArrayData_<?php echo $randonNumber?> = newMapData_<?php echo $randonNumber ?>;
				if(markerArrayData_<?php echo $randonNumber?>.length)
				newMarkerLayout_<?php echo $randonNumber?>();
				newMapData_<?php echo $randonNumber ?> = '';
				scriptJquery('#map-data_<?php echo $randonNumber;?>').addClass('checked');
			}
		}
		var isSearch = false;
		var oldMapData_<?php echo $randonNumber; ?> = [];
		var markers_<?php echo $randonNumber;?>  = [];
		var map_<?php echo $randonNumber;?>;
		if('<?php echo $this->lat; ?>' == '') {
			var latitude_<?php echo $randonNumber;?> = '26.9110600';
			var longitude_<?php echo $randonNumber;?> = '75.7373560';
		}else{
			var latitude_<?php echo $randonNumber;?> = '<?php echo $this->lat; ?>';
			var longitude_<?php echo $randonNumber;?> = '<?php echo $this->lng; ?>';
		}
		function initialize_<?php echo $randonNumber?>() {
			var bounds_<?php echo $randonNumber;?> = new google.maps.LatLngBounds();
			map_<?php echo $randonNumber;?> = new google.maps.Map(document.getElementById('map-canvas-<?php echo $randonNumber;?>'), {
				zoom: 17,
				scrollwheel: true,
				center: new google.maps.LatLng(latitude_<?php echo $randonNumber;?>, longitude_<?php echo $randonNumber;?>),
			});
			oms_<?php echo $randonNumber;?> = new OverlappingMarkerSpiderfier(map_<?php echo $randonNumber;?>,
			{nearbyDistance:40,circleSpiralSwitchover:0 }
			);
		}
		var countMarker_<?php echo $randonNumber;?> = 0;
		function DeleteMarkers_<?php echo $randonNumber ?>(){
			//Loop through all the markers and remove
			for (var i = 0; i < markers_<?php echo $randonNumber;?>.length; i++) {
				markers_<?php echo $randonNumber;?>[i].setMap(null);
			}
			markers_<?php echo $randonNumber;?> = [];
			markerData_<?php echo $randonNumber ?> = [];
			markerArrayData_<?php echo $randonNumber?> = [];
		};
		var markerArrayData_<?php echo $randonNumber?> ;
		var markerData_<?php echo $randonNumber ?> =[];
		var bounds_<?php echo $randonNumber;?> = new google.maps.LatLngBounds();

		function newMarkerLayout_<?php echo $randonNumber?>(dataLenth){
			if(typeof dataLenth != 'undefined') {
				initialize_<?php echo $randonNumber?>();
				markerArrayData_<?php echo $randonNumber?> = scriptJquery.parseJSON(dataLenth);
			}
			if(!markerArrayData_<?php echo $randonNumber?>.length)
			return;
			
			DeleteMarkers_<?php echo $randonNumber ?>();
			markerArrayData_<?php echo $randonNumber?> = oldMapData_<?php echo $randonNumber; ?>;
			var bounds = new google.maps.LatLngBounds();
			for(i=0;i<markerArrayData_<?php echo $randonNumber?>.length;i++){
				var images = '<div class="image sesnews_map_thumb_img"><img src="'+markerArrayData_<?php echo $randonNumber?>[i]['image_url']+'"  /></div>';		
				var owner = markerArrayData_<?php echo $randonNumber?>[i]['owner'];
				var location = markerArrayData_<?php echo $randonNumber?>[i]['location'];
				var socialshare = markerArrayData_<?php echo $randonNumber?>[i]['socialshare'];
				var sponsored = markerArrayData_<?php echo $randonNumber?>[i]['sponsored'];
				var vlabel = markerArrayData_<?php echo $randonNumber?>[i]['vlabel'];
				var labels = markerArrayData_<?php echo $randonNumber?>[i]['labels'];
				var allowBlounce = <?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.bounce', 1); ?>;
				if(sponsored == 1 && allowBlounce)
				var animateClass = 'animated bounce ';
				else
				var animateClass = '';
				
				//animate class "animated bounce"
				var marker_html = '<div class="'+animateClass+'pin public marker_'+countMarker_<?php echo $randonNumber;?>+'" data-lat="'+ markerArrayData_<?php echo $randonNumber?>[i]['lat']+'" data-lng="'+ markerArrayData_<?php echo $randonNumber?>[i]['lng']+'">' +
				'<div class="wrapper">' +
				'<div class="small">' +
				'<img src="'+markerArrayData_<?php echo $randonNumber?>[i]['image_url']+'" style="height:48px;width:48px;" alt="" />' +
				'</div>' +
				'<div class="large map_large_marker"><div class="sesnews_map_thumb sesnews_grid_btns_wrap">' +
				images + socialshare+vlabel+labels+
				'</div><div class="sesbasic_large_map_content sesnews_large_map_content sesbasic_clearfix">' +
				'<div class="sesbasic_large_map_content_title">'+markerArrayData_<?php echo $randonNumber?>[i]['title']+'</div>' +owner+location+
				'</div>' +
				'<a class="icn close" href="javascript:;" title="Close"><i class="fa fa-times"></i></a>' + 
				'</div>' +
				'</div>' +
				'<span class="sesbasic_largemap_pointer"></span>' +
				'</div>';
				markerData = new RichMarker({
					position: new google.maps.LatLng(markerArrayData_<?php echo $randonNumber?>[i]['lat'], markerArrayData_<?php echo $randonNumber?>[i]['lng']),
					map: map_<?php echo $randonNumber;?>,
					flat: true,
					draggable: false,
					scrollwheel: false,
					id:countMarker_<?php echo $randonNumber;?>,
					anchor: RichMarkerPosition.BOTTOM,
					content: marker_html,
				});
				oms_<?php echo $randonNumber;?>.addListener('click', function(marker) {
					var id = marker.markerid;
					previousIndex = scriptJquery('.marker_'+ id).parent().parent().css('z-index');
					scriptJquery('.marker_'+ id).parent().parent().css('z-index','9999');
					scriptJquery('.pin').removeClass('active').css('z-index', 10);
					scriptJquery('.marker_'+ id).addClass('active').css('z-index', 200);
					scriptJquery('.marker_'+ id+' .large .close').click(function(){
					scriptJquery(this).parent().parent().parent().parent().parent().css('z-index',previousIndex);
						scriptJquery('.pin').removeClass('active');
						return false;
					});
				});
				markers_<?php echo $randonNumber;?> .push( markerData);
				markerData.setMap(map_<?php echo $randonNumber;?>);
				bounds.extend(markerData.getPosition());
				markerData.markerid = countMarker_<?php echo $randonNumber;?>;
				oms_<?php echo $randonNumber;?>.addMarker(markerData);
				countMarker_<?php echo $randonNumber;?>++;
		  }
			map_<?php echo $randonNumber;?>.fitBounds(bounds);
		}
		<?php if($this->view_type == 'map'){?>
				en4.core.runonce.add(function() {
				var mapData = scriptJquery.parseJSON(document.getElementById('map-data_<?php echo $randonNumber;?>').innerHTML);
				if(scriptJquery.isArray(mapData) && scriptJquery(mapData).length) {
					newMapData_<?php echo $randonNumber ?> = mapData;
					scriptJquery.merge(oldMapData_<?php echo $randonNumber; ?>, newMapData_<?php echo $randonNumber ?>);
					mapFunction_<?php echo $randonNumber?>();
					scriptJquery('#map-data_<?php echo $randonNumber;?>').addClass('checked')
				}
				else{
					if(typeof  map_<?php echo $randonNumber;?> == 'undefined') {
						scriptJquery('#map-data_<?php echo $randonNumber; ?>').html('');
						initialize_<?php echo $randonNumber?>();	
					}
				}
			});
		<?php } ?>
	</script> 
<?php endif;?>
<!--End Map Work-->
<?php } ?>
