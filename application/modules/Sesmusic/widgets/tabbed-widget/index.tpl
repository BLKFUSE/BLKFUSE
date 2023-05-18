<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>

<?php if($this->canAddFavourite): ?>
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/scripts/favourites.js'); ?>
<?php endif; ?>

<?php if(isset($this->identityForWidget) && !empty($this->identityForWidget)):
  $randonNumber = $this->identityForWidget;
else:
  $randonNumber = $this->identity; 
endif; ?>

<?php if(!$this->is_ajax){ ?>
<?php if(!$this->showTabType): ?>
<div class="layout_core_container_tabs">
  <div class="tabs_alt tabs_parent">
<?php else: ?>
<div class="sesbasic_tabs_container sesbasic_clearfix">
  <?php if($this->defaultOptions): ?>
    <div class="sesbasic_tabs sesbasic_clearfix">
  <?php endif; ?>
<?php endif; ?>
    <?php if($this->defaultOptions): ?>
      <ul>
        <?php foreach($this->defaultOptions as $valueOptions){ ?>
        <?php $value = str_replace('1',' ',$valueOptions); ?>
          <li <?php if($this->defaultOpenTab == $valueOptions){ ?>class="active"<?php } ?> id="sesTabContainer_<?php echo $randonNumber; ?>_<?php echo $valueOptions; ?>">
            <a href="javascript:;" onclick="changeTabSes_<?php echo $randonNumber; ?>('<?php echo $valueOptions; ?>')"><?php echo $this->translate(ucwords($value)); ?></a>
          </li>
        <?php } ?>
      </ul>
    </div>
  <?php endif; ?>
  <div id="scrollHeightDivSes_<?php echo $randonNumber; ?>" class="sesbasic_clearfix">    
    <ul class="sesmusic_browse_listing clear sesbasic_bxs" id="tabbed-widget_<?php echo $randonNumber; ?>">
    <?php } ?>
          <?php $limit = $this->limit; ?>
          <?php if(is_countable($this->paginator) && engine_count($this->paginator) > 0): ?>
    			<?php foreach( $this->paginator as $item ): ?>
          <li id="thumbs-photo-<?php echo $item->photo_id ?>" class="sesmusic_item_grid sesbasic_bxs<?php if(Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?> paid_content <?php } ?>" style="width:<?php echo str_replace('px','',$this->width).'px'; ?>;">    
          <?php if(Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?>
              <?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $item)); ?>
           <?php } ?>        
              <div class="sesmusic_item_artwork">
                <div class="sesmusic_item_artwork_img" style="height:<?php echo str_replace('px','',$this->height).'px'; ?>;">
                <?php echo $this->itemPhoto($item, 'thumb.profile'); ?>
                <a href="<?php echo $item->getHref(); ?>" class="transparentbg"></a>
                </div>
                <div class="sesmusic_item_artwork_over_content sesmusic_animation">
                	<div class="sesmusic_item_sponseard_social">
                  	<?php // Featured and Sponsored and Hot Label Icon ?>
                  	<div class="sesmusic_item_info_label">
                    <?php if(!empty($item->hot) && !empty($this->information) && engine_in_array('hot', $this->information)): ?>
                    <span class="sesmusic_label_hot fa fa-star" title='<?php echo $this->translate("HOT"); ?>'></span>
                    <?php endif; ?>
                    <?php if(!empty($item->featured) && !empty($this->information) && engine_in_array('featured', $this->information)): ?>
                    <span class="sesmusic_label_featured fa fa-star" title='<?php echo $this->translate("FEATURED"); ?>'></span>
                    <?php endif; ?>
                    <?php if(!empty($item->sponsored) && !empty($this->information) && engine_in_array('sponsored', $this->information)): ?>
                    <span class="sesmusic_label_sponsored fa fa-star" title='<?php echo $this->translate("SPONSORED"); ?>'></span>
                    <?php endif; ?>
                  </div>
                  	<div class="sesmusic_social_item sesmusic_animation">
                    <!--Social Share Button-->
                    <?php if($this->information && engine_in_array('socialSharing', $this->information)) { ?>
                      <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
                      
                      <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
                      
                     
                    <?php } ?>
                    <!--Social Share Button-->
                    
                    <!--Like and Favourite Button-->
                    <?php $viewer = Engine_Api::_()->user()->getViewer();
                    $viewer_id = $viewer->getIdentity();
                    $canLike = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'comment');
                    $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($item, $viewer); ?>
                    <?php if ($canLike && !empty($viewer_id) && $this->information && engine_in_array('addLikeButton', $this->information)): ?>
                      <a href="javascript:;" data-url="<?php echo $item->album_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesmusic_like_<?php echo $item->getType(); ?> <?php echo ($isLike) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count; ?></span></a>
                    <?php endif; ?>
                    
                    <?php $isFavourite = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->isFavourite(array('resource_type' => "sesmusic_album", 'resource_id' => $item->album_id)); ?>
                    <?php if(!empty($viewer_id) && $this->canAddFavourite && $this->information && engine_in_array('favourite', $this->information)): ?>
                      <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesmusic_favourite_<?php echo $item->getType(); ?> <?php echo ($isFavourite)  ? 'button_active' : '' ?>"  data-url="<?php echo $item->album_id ; ?>"><i class="fa fa-heart"></i><span><?php echo $item->favourite_count; ?></span></a>
                    <?php endif; ?>
                    <!--Like and Favourite Button--> 
             
                     
                    <?php if($this->viewer_id ): ?>
                  
                      <?php if($this->canAddPlaylist && $this->information && engine_in_array('addplaylist', $this->information)): ?>
                       <a class="sesbasic_icon_btn add-white" title="Add to Playlist" href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module' =>'sesmusic', 'controller' => 'song', 'action'=>'append - songs','album_id' => $item->album_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><i class="fa fa-plus"></i></a>
                      <?php endif; ?>
                                        
                    <?php if(!empty($this->albumlink) && engine_in_array('share', $this->albumlink) && $this->information && engine_in_array('share', $this->information)): ?>
                      <a class="sesbasic_icon_btn share-white" title="Share" href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesmusic_album', 'id' => $item->album_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><i class="fa fa-share"></i></a>
                    <?php endif; ?>
                    <?php endif; ?>
                     
                  </div>
                  </div>
                  <div class="sesmusic_item_stats_info sesmusic_animation">
                  	    <div class="sesmusic_item_info_stats">
                    <?php if (!empty($this->information) && engine_in_array('commentCount', $this->information)) :?>
                    <span>
                      <?php echo $item->comment_count; ?>
                      <i class="fa fa-comment"></i>
                    </span>
                    <?php endif; ?>
                    <?php if (!empty($this->information) && engine_in_array('likeCount', $this->information)) : ?>
                    <span>
                      <?php echo $item->like_count; ?>
                      <i class="fa fa-thumbs-up"></i>
                    </span>
                    <?php endif; ?>
                    <?php if (!empty($this->information) && engine_in_array('viewCount', $this->information)) : ?>
                    <span>
                      <?php echo $item->view_count; ?>
                      <i class="fa fa-eye"></i>
                    </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($this->information) && engine_in_array('songCount', $this->information)) : ?>
                    <span>
                      <?php echo $item->song_count; ?>
                      <i class="fa fa-music"></i>
                    </span>
                    <?php endif; ?>
                  </div>

                  <?php if ($this->showRating && !empty($this->information) && engine_in_array('ratingStars', $this->information)) : ?>
                    <div class="sesmusic_item_info_rating">
                      <?php if( $item->rating > 0 ): ?>
                      <?php for( $x=1; $x<= $item->rating; $x++ ): ?>
                      <span class="sesbasic_rating_star_small fa fa-star"></span>
                      <?php endfor; ?>
                      <?php if( (round($item->rating) - $item->rating) > 0): ?>
                      <span class="sesbasic_rating_star_small fa fa-star-half"></span>
                      <?php endif; ?>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>
                  </div>
                  <div class="sesmusic_item_stats_play_btn sesmusic_animation">   
                  <a class="sesmusic_play_button" href="<?php echo $item->getHref(); ?>"><i class="fa fa-play-circle"></i></a>
                  </div>
                </div>
              </div>
              <div class="sesmusic_item_info">     
                  <?php if(!empty($this->information) && engine_in_array('title', $this->information)): ?>
                    <div class="sesmusic_item_info_title">
                      <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
                    </div>    
                  <?php endif; ?>

                  <?php if(!empty($this->information) && engine_in_array('postedby', $this->information)): ?>
                  <div class="sesmusic_item_info_owner">
                    <?php echo $this->translate('by');?> <?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?>
                  </div>
                  <?php endif; ?>

              

                  
                </div>
                <div class="hover_box">
                  <a title="<?php echo $item->getTitle(); ?>" href="<?php echo $item->getHref(); ?>" class="sesmusic_grid_link"></a>
                  <div class="hover_box_options">

                  </div>
                </div>
            </li>
          <?php $limit++; endforeach;?>
          <?php else: ?>
          <div class="tip">
            <span>
             <?php echo $this->translate('Nobody has created a music album with that criteria..') ?>
            </span>
          </div>
          <?php endif; ?>
          <?php if(!$this->is_ajax){ ?>
        </ul>
    <?php } ?>
    <?php if (!empty($this->paginator) && $this->paginator->count() > 1): ?>
      <?php if ($this->paginator->getCurrentPageNumber() < $this->paginator->count()): ?>
        <div class="clr" id="loadmore_list_<?php echo $randonNumber; ?>"></div>
        <div class="sesbasic_view_more sesbasic_load_btn" style="display:none;" id="view_more_<?php echo $randonNumber; ?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > 
          <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" ><i class="fa fa-repeat"></i><span><?php echo $this->translate('View More');?></span></a> 
        </div>
        <div class="sesbasic_view_more_loading" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"> 
          <span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span> 
        </div>
        <?php endif; ?>
     <?php endif; ?>
        <?php if(!$this->is_ajax){ ?>
      </div>
    </div>
<?php } ?>
<script type="text/javascript">
  //Globally define available tab array  
  var availableTabs_<?php echo $randonNumber; ?>;
  var requestTab_<?php echo $randonNumber; ?>;
  availableTabs_<?php echo $randonNumber; ?> = <?php echo json_encode($this->defaultOptions); ?>;

</script>


<script type="text/javascript">
	function changeTabSes_<?php echo $randonNumber; ?>(valueTab) {
  
			var id = '_<?php echo $randonNumber; ?>';
			var length = availableTabs_<?php echo $randonNumber; ?>.length;
			for (var i = 0; i < length; i++) {
					if(availableTabs_<?php echo $randonNumber; ?>[i] == valueTab)
						scriptJquery('#sesTabContainer'+id+'_'+availableTabs_<?php echo $randonNumber; ?>[i]).addClass('active');
					else
						scriptJquery('#sesTabContainer'+id+'_'+availableTabs_<?php echo $randonNumber; ?>[i]).removeClass('active');
			}
		if(valueTab){
				
				document.getElementById("tabbed-widget_<?php echo $randonNumber; ?>").innerHTML = "<div class='clear sesbasic_loading_container'></div>";
				
        if(document.getElementById("view_more_<?php echo $randonNumber; ?>"))
          document.getElementById("view_more_<?php echo $randonNumber; ?>").style.display = 'none';
	
			 
			 requestTab_<?php echo $randonNumber; ?> = scriptJquery.ajax({
				method: 'post',
				'url': en4.core.baseUrl + 'widget/index/mod/sesmusic/name/tabbed-widget/openTab/' + valueTab,
				'data': {
					format: 'html',
					page:  1,    
					params :'<?php echo json_encode($this->params); ?>', 
					is_ajax : 1,
					identity : '<?php echo $randonNumber; ?>',
				},
				success: function(responseHTML) {
					document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML = '';
					scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
				}
    	});
		
    return false;			
		}
	}

  en4.core.runonce.add(function() {
    viewMoreHide_<?php echo $randonNumber; ?>();
  });
  
  function viewMoreHide_<?php echo $randonNumber; ?>() {
    if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
  }
  
  function viewMore_<?php echo $randonNumber; ?> () {
  
    var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
    if(document.getElementById("view_more_<?php echo $randonNumber; ?>"))
    document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = 'none';
    document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = '';    
    en4.core.request.send(scriptJquery.ajax({
      method: 'post',
      'url': en4.core.baseUrl + 'widget/index/mod/sesmusic/name/tabbed-widget/openTab/' + openTab_<?php echo $randonNumber; ?>,
      'data': {
        format: 'html',
        page: <?php echo $this->page + 1; ?>,    
				params :'<?php echo json_encode($this->params); ?>', 
				is_ajax : 1,
				identity : '<?php echo $randonNumber; ?>',
      },
      success: function(responseHTML) {
        document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
        scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
            if(document.getElementById("view_more_<?php echo $randonNumber; ?>"))
        document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = 'block';

        if(document.getElementById('loadmore_list_<?php echo $randonNumber; ?>'))
         scriptJquery('#loadmore_list_<?php echo $randonNumber; ?>').remove();
        if(document.getElementById('view_more_<?php echo $randonNumber; ?>'))
          scriptJquery('#view_more_<?php echo $randonNumber; ?>').remove();
        if(document.getElementById('loading_image_<?php echo $randonNumber; ?>'))
         scriptJquery('#loading_image_<?php echo $randonNumber; ?>').remove();
      }
    }));
    return false;
  }
  
  <?php if($this->loadOptionData) { ?>
    scriptJquery( window ).load(function() {
      var paginatorCount = '<?php echo $this->paginator->count(); ?>';
      var paginatorCurrentPageNumber = '<?php echo $this->paginator->getCurrentPageNumber(); ?>';
      function ScrollLoader<?php echo $randonNumber; ?>() {
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        if(document.getElementById('loadmore_list_<?php echo $randonNumber; ?>')) {
          if (scrollTop > 40)
            viewMore_<?php echo $randonNumber; ?>();
        }
      }
      window.addEvent('scroll', function() {
        ScrollLoader<?php echo $randonNumber; ?>(); 
      });
    });
  <?php } ?>
</script>
