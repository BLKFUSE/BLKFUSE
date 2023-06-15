<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css'); ?>

<?php 
   $viewer = Engine_Api::_()->user()->getViewer();
    if ($viewer->getIdentity() == 0)
      $level = Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
    else
      $level = $viewer;
    $type = Engine_Api::_()->authorization()->getPermission($level, 'sesbasic_video', 'videoviewer');
    if ($type == 1) {
      $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
                      . 'application/modules/Sesbasic/externals/scripts/SesLightbox/photoswipe.min.js')
              ->appendFile(Zend_Registry::get('StaticBaseUrl')
                      . 'application/modules/Sesbasic/externals/scripts/SesLightbox/photoswipe-ui-default.min.js')
              ->appendFile(Zend_Registry::get('StaticBaseUrl')
                      . 'application/modules/Sesbasic/externals/scripts/videolightbox/sesvideoimagevieweradvance.js');
      $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
              . 'application/modules/Sesbasic/externals/styles/photoswipe.css');
    } else {
      $loadImageViewerFile = Zend_Registry::get('StaticBaseUrl') . 'application/modules/Sesbasic/externals/scripts/videolightbox/sesvideoimageviewerbasic.js';
      $this->headScript()->appendFile($loadImageViewerFile);
      $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
              . 'application/modules/Sesbasic/externals/styles/medialightbox.css');
    }
?>

<?php
//This forces every playlist to have a unique ID, so that a playlist can be displayed twice on the same page.
$random   = '';
for ($i=0; $i<6; $i++) { $d=rand(1,30)%2; $random .= ($d?chr(rand(65,90)):chr(rand(48,57))); }
?>
<script type="text/javascript">
  function showPopUp(url) {
    Smoothbox.open(url);
    parent.Smoothbox.close;
  }

  function loadMoreContent() {
  
    if (document.getElementById('load_more'))
      document.getElementById('load_more').style.display = "<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() || $this->count == 0 ? 'none' : '' ) ?>";

    if(document.getElementById('load_more'))
      document.getElementById('load_more').style.display = 'none';
    
    if(document.getElementById('underloading_image'))
      document.getElementById('underloading_image').style.display = '';

    en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',
      method: 'post',              
      'url': en4.core.baseUrl + 'widget/index/mod/sesvideo/name/browse-playlists',
      'data': {
        format: 'html',
        page: "<?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>",
        viewmore: 1,
        params: '<?php echo json_encode($this->all_params); ?>',        
      },
      success: function(responseHTML) {
        scriptJquery('#results_data').append(responseHTML);
        
        if(document.getElementById('load_more'))
          scriptJquery('#load_more').remove();
        
        if(document.getElementById('underloading_image'))
         scriptJquery('#underloading_image').remove();
       
        if(document.getElementById('loadmore_list'))
         scriptJquery('#loadmore_list').remove();
               scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
          theme:"minimal-dark"
        });
      }
    }));
    return false;
  }
</script>

<?php if(is_countable($this->paginator) && engine_count($this->paginator) > 0): ?>
  <?php if (empty($this->viewmore)): ?>
    <?php if(strpos($_SERVER['REQUEST_URI'],'?') == true): ?>
		<div class="sesvideo_search_result"><?php echo $this->translate(array('%s playlist found.', '%s playlists found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())); ?></div>
  <?php endif; ?>
    <!--List View-->
    <ul class="sesvideo_browse_playlist sesbasic_bxs" id="results_data">
  <?php endif; ?>
  <?php foreach ($this->paginator as $item):  ?>
    <li  class="sesvideo_listing_list sesbasic_clearfix clear">
    <div class="sesvideo_browse_playlist_thumb sesvideo_list_thumb sesvideo_thumb">
      <a href="<?php echo $item->getHref(); ?>" class="sesvideo_thumb_nolightbox">
        <span style="background-image:url(<?php echo $item->getPhotoUrl(); ?>);"></span>
      </a>
    <?php if(!empty($this->information)) { ?>
     <?php if(engine_in_array('featuredLabel', $this->information) || engine_in_array('sponsoredLabel', $this->information)){ ?>
      <p class="sesvideo_labels">
      <?php if(engine_in_array('featuredLabel', $this->information) && $item->is_featured ){ ?>
        <span class="sesvideo_label_featured"><?php echo $this->translate('FEATURED'); ?></span>
      <?php } ?>
      <?php if(engine_in_array('sponsoredLabel', $this->information) && $item->is_sponsored ){ ?>
        <span class="sesvideo_label_sponsored"><?php echo $this->translate("SPONSORED"); ?></span>
      <?php } ?>
      
      </p>
     <?php } ?>
     <?php } ?>
     	<?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
     	<div class="sesvideo_thumb_btns"> 
      	<?php if(!empty($this->information) && engine_in_array('socialSharing', $this->information)){ ?>
          <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_icon_limit' => $this->socialshare_icon_limit, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon)); ?>

				<?php } ?>  
        <?php if($this->viewer_id): ?>
          <?php if($this->viewer_id && !empty($this->information) && engine_in_array('share', $this->information)): ?>
          	<a  class="sesbasic_icon_btn" title='<?php echo $this->translate("Share") ?>' href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesvideo_playlist', 'id' => $item->playlist_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" >
          		<i class="fas fa-share-alt"></i>
          	</a>
        	<?php endif; ?>
        <?php endif; ?>
        <?php 
        if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 ){
            $itemtype = 'sesvideo_playlist';
            $getId = 'playlist_id';                                
            $canComment =  true;
            if(!empty($this->information) && engine_in_array('likeButton', $this->information) && $canComment){
          ?>
          <!--Like Button-->
          <?php $LikeStatus = Engine_Api::_()->sesvideo()->getLikeStatusVideo($item->$getId,$item->getType()); ?>
            <a href="javascript:;" data-url="<?php echo $item->$getId ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesvideo_like_<?php echo $itemtype; ?> <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count; ?></span></a>
            <?php } ?>
             <?php if(!empty($this->information) && engine_in_array('favouriteButton', $this->information) && isset($item->favourite_count)){ ?>
            
            <?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesvideo')->isFavourite(array('resource_type'=>$itemtype,'resource_id'=>$item->$getId)); ?>
            <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesvideo_favourite_<?php echo $itemtype; ?> <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $item->$getId ; ?>"><i class="fa fa-heart"></i><span><?php echo $item->favourite_count; ?></span></a>
          <?php } ?>
        <?php  } ?>
      </div>
    </div>
    
    <div class="sesvideo_list_info">
      <?php if(!empty($this->information) && engine_in_array('title', $this->information)): ?>
        <div class="sesvideo_list_title">
          <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
        </div>
      <?php endif; ?>
      <?php if(!empty($this->information) && engine_in_array('postedby', $this->information)): ?>
      <div class="sesvideo_list_date sesbasic_text_light">
        <?php echo $this->translate('Created By %s', $this->htmlLink($item->getOwner(), $item->getOwner()->getTitle())) ?>
      </div>
      <?php endif; ?>

      <div class="sesvideo_list_date sesvideo_list_stats sesbasic_text_light"> 
        <?php if(!empty($this->information) && engine_in_array('viewCount', $this->information)): ?>
        	<span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $item->view_count; ?></span>
        <?php endif; ?>
        <?php if(!empty($this->information) && engine_in_array('favouriteCount', $this->information)): ?>
        	<span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $item->favourite_count;?></span>
        <?php endif; ?>
        <?php if(!empty($this->information) && engine_in_array('likeCount', $this->information)): ?>
        	<span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $item->like_count; ?></span>
        <?php endif; ?>

          <?php if(!empty($this->information) && engine_in_array('videoCount', $this->information)): ?>
         <?php $videoCount = Engine_Api::_()->getDbtable('playlistvideos', 'sesvideo')->playlistVideosCount(array('playlist_id' => $item->playlist_id));  ?>
        <span title="<?php echo $this->translate(array('%s video', '%s videos', $videoCount), $this->locale()->toNumber($videoCount)); ?>"><i class="fas fa-video"></i><?php echo $videoCount; ?></span>
          <?php endif; ?>
      </div>

      <?php if(!empty($this->information) && engine_in_array('description', $this->information)): ?>
        <div class="sesvideo_list_des">
            <?php if(strlen($item->description) > $this->description_truncation){ 
                $description = mb_substr($item->description,0,$this->description_truncation).'...';
                echo $title = nl2br($description);
               }else{ ?>
            <?php  echo nl2br($item->description);?>
            <?php } ?>
        </div>
      <?php endif; ?>
      
      <?php if(!empty($this->information) && engine_in_array('showVideosList', $this->information)): ?>
      <?php $playlist = $item; 
      $videos = $item->getVideos();
      ?>   
      <?php if(engine_count($videos) > 0): ?>
      <div class="clear sesbasic_clearfix sesvideo_videos_minilist_container sesbasic_custom_scroll sesbm">
        <ul class="clear sesvideo_videos_minilist sesbasic_bxs">
          <?php foreach( $videos as $videoItems ): ?>
          <?php $video = Engine_Api::_()->getItem('video', $videoItems->file_id); ?>
          <?php if( !empty($video) ): ?>
          <li class="sesbasic_clearfix sesbm">
            <div class="sesvideo_videos_minilist_photo">
           		<a class="sesvideo_thumb_img sesvideo_lightbox_open" data-url = "<?php echo $video->getType() ?>" href="<?php echo $video->getHref(array('type'=>'sesvideo_playlist','item_id'=>$item->playlist_id)); ?>">
              	<span style="background-image:url(<?php echo $video->getPhotoUrl() ?>);"></span>
              </a>
            </div>
            <?php if(!empty($this->information) && engine_in_array('watchLater', $this->information)  && isset($videoItems->watchlater_id)){ ?>
              <div class="sesvideo_videos_minilist_buttons">
                <a href="javascript:;" class="sesbasic_icon_btn sesvideo_watch_later <?php echo !is_null($videoItems->watchlater_id)  ? 'selectedWatchlater' : '' ?>" title = "<?php echo !is_null($videoItems->watchlater_id)  ? $this->translate('Remove from Watch Later') : $this->translate('Add to Watch Later') ?>" data-url="<?php echo $video->video_id ; ?>"><i class="far fa-clock"></i></a>
              </div>
            <?php } ?>
            <div class="sesvideo_videos_minilist_name" title="<?php echo $video->title; ?>">
                <?php echo $this->htmlLink($video->getHref(), $video->getTitle(), array('class' => 'sesbasic_linkinherit')); ?>
            </div>
          </li>
          <?php endif; ?>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>
      <?php endif; ?>
       </div>
    </li>
  <?php endforeach; ?>

  <?php //if($this->paginationType == 1): ?>
    <?php if (!empty($this->paginator) && $this->paginator->count() > 1): ?>
      <?php if ($this->paginator->getCurrentPageNumber() < $this->paginator->count()): ?>
        <div class="clr" id="loadmore_list"></div>
        <div class="sesbasic_load_btn" style="display: block;" id="load_more" onclick="loadMoreContent();" >
          <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" id="feed_viewmore_link"><i class="fa fa-sync"></i><span><?php echo $this->translate('View More');?></span></a>
        </div>  
        <div class="sesbasic_load_btn" id="underloading_image" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
      <?php endif; ?>
     <?php endif; ?>
  <?php //else: ?>
    <?php //echo $this->paginationControl($this->paginator, null, null, array('pageAsQuery' => true, 'query' => $this->all_params)); ?>
  <?php //endif; ?>  
<?php if (empty($this->viewmore)): ?>
</ul>
<?php endif; ?>
<?php else: ?>
  <div class="tip">
    <span>
      <?php echo $this->translate('There are currently no playlists created yet.') ?>
    </span>
  </div>
<?php endif; ?>
<?php if($this->paginationType == 1): ?>
  <script type="text/javascript">    
     //Take refrences from: http://mootools-users.660466.n2.nabble.com/Fixing-an-element-on-page-scroll-td1100601.html
    //Take refrences from: http://davidwalsh.name/mootools-scrollspy-load
    en4.core.runonce.add(function() {
    
      var paginatorCount = '<?php echo $this->paginator->count(); ?>';
      var paginatorCurrentPageNumber = '<?php echo $this->paginator->getCurrentPageNumber(); ?>';
      function ScrollLoader() { 
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        if(document.getElementById('loadmore_list')) {
          if (scrollTop > 40)
            loadMoreContent();
        }
      }
      scriptJquery(document).on('scroll',function(event) { 
        ScrollLoader(); 
      });
    });    
  </script>
<?php endif; ?>
