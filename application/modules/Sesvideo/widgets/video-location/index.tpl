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

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/styles.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css'); ?>

<script type="text/javascript" src="<?php echo $this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/marker.js'; ?>"></script>
<script type="text/javascript">
var markers = [];
var map;
var oms;
function initialize() {
	var bounds = new google.maps.LatLngBounds();
	 map = new google.maps.Map(document.getElementById('map-canvas'), {
		zoom: 10,
		 scrollwheel: true,
		center: new google.maps.LatLng(<?php echo $this->lat; ?>, <?php echo $this->lng; ?>),
	});
	 oms = new OverlappingMarkerSpiderfier(map,
        {nearbyDistance:40,circleSpiralSwitchover:0 }
				);
	<?php 
	 $count = 0; 
	  $allowRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.video.rating',1);
		$allowShowPreviousRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.ratevideo.show',1);
		if($allowRating == 0){
			if($allowShowPreviousRating == 0)
				$ratingShow = false;
			 else
				$ratingShow = true;
		}else
			$ratingShow = true;
	if($this->paginator->getTotalItemCount()>0){ ?>
	<?php foreach($this->paginator as $item){
    if($item->type == 3) {
      //$urlIframe = $item->getRichContent(true, array(), true);
      //$urlIframe = '<video id="video" controls preload="auto" width="480" height="386"><source type="video/mp4" src="'.$urlIframe.'"></video>';
		} else {
      $item->getRichContent(true,array(),true);
		}
	?>
	<!--var description = <?php echo json_encode($item->getDescription()); ?>;-->
	var title = <?php echo json_encode($this->htmlLink($item->getHref(),$item->getTitle() )); ?>;
	<?php 
	$user = Engine_Api::_()->getItem('user',$item->owner_id);
	$owner = $item->getOwner();
	$ratings = '';
	$urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref());
	?>
	<?php 
	$likeButton = $favouriteButton = $addToplaylist = '';
	if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0){
		$likeButton = '<a href="javascript:;" data-url="'.$item->getIdentity().'" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesvideo_like_sesvideo_video "> <i class="fa fa-thumbs-up"></i><span>'.$item->like_count.'</span></a>';
		if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavv', 1)) { 
      $favouriteButton = '<a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesvideo_favourite_sesvideo_video " data-url="'.$item->getIdentity().'"><i class="fa fa-heart"></i><span>'.$item->favourite_count.'</span></a>';
		}
                if(Engine_Api::_()->authorization()->getPermission(Engine_Api::_()->user()->getViewer()->level_id, 'video', 'addplayl_video')) {
		$addToplaylist = '<a href="javascript:;" class="sesbasic_icon_btn sesvideo_add_playlist" onclick="opensmoothboxurl('."'".$this->url(array('action' => 'add','module'=>'sesvideo','controller'=>'playlist','video_id'=>$item->video_id),'default',true)."'".')" title="'.$this->translate('Add To Playlist').'" data-url="'.$item->getIdentity().'"><i class="fa fa-plus"></i></a>';
                    }
	}
	$socialshare = $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'param' => 'feed'));
	?>
	 <?php if($ratingShow && isset($item->rating) && $item->rating > 0 ): 
	$ratings =   '<span  title="'.$this->translate(array('%s rating', '%s ratings', round($item->rating,1)), $this->locale()->toNumber(round($item->rating,1))).'"><i class="far fa-star"></i>'. round($item->rating,1).'/5'.'</span>';
  endif; ?>
	var owner = <?php echo json_encode('<div class="sesvideo_grid_date sesvideo_list_stats sesbasic_text_light"><span><i class="far fa-user"></i>'.$this->translate("by").$this->htmlLink($owner->getHref(),$owner->getTitle() ).'</span></div>'); ?>;
	var stats = '<div class="sesbasic_largemap_stats sesvideo_list_stats sesbasic_clearfix"><span title="<?php echo $this->string()->escapeJavascript($this->translate(array('%s like', '%s likes', $item->like_count)), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $item->like_count; ?></span> <span title="<?php echo $this->string()->escapeJavascript($this->translate(array('%s comment', '%s comments', $item->comment_count)), $this->locale()->toNumber($item->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $item->comment_count;?></span><span title="<?php echo $this->string()->escapeJavascript($this->translate(array('%s favourite', '%s favourites', $item->favourite_count)), $this->locale()->toNumber($item->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $item->favourite_count;?></span><span title="<?php echo $this->string()->escapeJavascript($this->translate(array('%s view', '%s views', $item->view_count)), $this->locale()->toNumber($item->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $item->view_count; ?></span><?php echo $ratings;  ?></div>';

	var socialshare = <?php echo json_encode('<div class="sesbasic_largemap_btns sesvideo_list_btns">'.$socialshare.$likeButton.$favouriteButton.$addToplaylist.'</div>'); ?>;
		 var images = '<div class="image"><img src="<?php echo $item->getPhotoUrl(); ?>"  /></div>';			
		 var marker_html = '<div class="pin public marker_<?php echo $count; ?>" data-lat="<?php echo $item->lat; ?>" data-lng="<?php echo $item->lng; ?>">' +
		 	'<div class="iframe_url" style="display:none;"  allowfullscreen=""><?php echo $urlIframe; ?></div>' +
				'<div class="wrapper">' +
					'<div class="small">' +
						'<img src="<?php echo $item->getPhotoUrl('thumb.icon'); ?>" style="height:48px;width:48px;" alt="" />' +
					'</div>' +
					'<div class="large">' +
						images +
						'<div class="sesbasic_large_map_content sesbasic_clearfix">' +
							'<div class="sesbasic_large_map_content_title">'+title+'</div>' +owner+stats+socialshare+
						'</div>' +
						'<a class="icn close" href="javascript:;" title="Close"><i class="fa fa-times"></i></a>' + 
					'</div>' +
				'</div>' +
				'<span class="sesbasic_largemap_pointer"></span>' +
				'</div>';
			 		var marker = new RichMarker({
						position: new google.maps.LatLng(<?php echo isset($item->lat) && !empty($item->lat) ? $item->lat: 0; ?>, <?php echo isset($item->lng) && !empty($item->lng) ? $item->lng : 0; ?>),
						map: map,
						flat: true,
						draggable: false,
						scrollwheel: false,
						anchor: RichMarkerPosition.BOTTOM,
						content: marker_html
					});
			<?php	if($count == 0){ ?>
					oms.addListener('click', function(marker) {
					var id = marker.markerid;
          scriptJquery('.wrapper').find('.large').find('.image').html('');
					var iframeURL = scriptJquery('.marker_'+id).find('.iframe_url').html();
					var height = 164;
					var width = 294;
					previousIndex = scriptJquery('.marker_'+ id).parent().parent().css('z-index');
					scriptJquery('.marker_'+ id).parent().parent().css('z-index','9999');
						if(typeof iframeURL != 'undefined' && !scriptJquery('.marker_'+id).find('.wrapper').find('.large').find('.image').find('iframe').attr('src'))
						scriptJquery('.marker_'+id).find('.wrapper').find('.large').find('.image').html('<iframe src="'+iframeURL+'" height="'+height+'" width="'+width+'" style="overflow:hidden"  allowfullscreen="">');
						scriptJquery('.pin').removeClass('active').css('z-index', 10);
						scriptJquery('.marker_'+id).addClass('active').css('z-index', 200);
						scriptJquery('.marker_'+id+' .large .close').click(function(){
						scriptJquery(this).parent().parent().parent().parent().parent().css('z-index',previousIndex);
						scriptJquery('.marker_'+id).find('.wrapper').find('.large').find('.image').html('');
						scriptJquery('.pin').removeClass('active');
						return false;
					});
				});
			<?php } ?>
					markers.push(marker);
					marker.markerid = <?php echo $count; ?>;
					oms.addMarker(marker);
					marker.setMap(map);
					bounds.extend(marker.getPosition());
			<?php 
				$count++;
			} ?>
			map.fitBounds(bounds);
			<?php } ?>
}
var interval;
var countMarker = <?php echo $count; ?>;
function DeleteMarkers() {
        //Loop through all the markers and remove
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers = [];
				markerData = [];
};
var searchParams;
var markerArrayData ;
function callNewMarkersAjax(){
	 (scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + "widget/index/mod/sesvideo/name/<?php echo $this->widgetName; ?>",
      'data': {
        format: 'html',
				is_ajax : 1,
				searchParams:searchParams,
      },
      success: function(responseHTML) {
				if(document.getElementById('loadingimgsesvideo-wrapper'))
						 scriptJquery('#submit').html('Search');
				DeleteMarkers();
       	if(responseHTML){
					markerArrayData = scriptJquery.parseJSON(responseHTML);
					if(markerArrayData.length)
						newMarkerLayout();
				}
      }
    }));	
}
var markerData =[];
var previousIndex=0;
function newMarkerLayout(){
	if(!markerArrayData.length)
		return ;
	var bounds = new google.maps.LatLngBounds();
	for(i=0;i<markerArrayData.length;i++){
		var images = '<div class="image"><img src="'+markerArrayData[i]['image_url']+'"  /></div>';		
		var owner = markerArrayData[i]['owner'];
		var stats = markerArrayData[i]['stats'];
		var socialshare = markerArrayData[i]['socialshare'];
		 var marker_html = '<div class="pin public marker_'+countMarker+'" data-lat="'+ markerArrayData[i]['lat']+'" data-lng="'+ markerArrayData[i]['lng']+'">' +
			 '<div class="iframe_url" style="display:none;" >'+markerArrayData[i]['iframe_url']+'</div>' +
				'<div class="wrapper">' +
					'<div class="small">' +
						'<img src="'+markerArrayData[i]['image_url']+'" style="height:48px;width:48px;" alt="" />' +
					'</div>' +
					'<div class="large">' +
						images +
						'<div class="sesbasic_large_map_content">' +
							'<div class="sesbasic_large_map_content_title">'+markerArrayData[i]['title']+'</div>' +owner+stats+socialshare+
						'</div>' +
						'<a class="icn close" href="javascript:;" title="Close"><i class="fa fa-times"></i></a>' + 
					'</div>' +
				'</div>' +
				'<span class="sesbasic_largemap_pointer"></span>' +
				'</div>';
			  markerData = new RichMarker({
						position: new google.maps.LatLng(markerArrayData[i]['lat'], markerArrayData[i]['lng']),
						map: map,
						flat: true,
						draggable: false,
						scrollwheel: false,
						id:countMarker,
						anchor: RichMarkerPosition.BOTTOM,
						content: marker_html
				});
				oms.addListener('click', function(marker) {
					var id = marker.markerid;
          scriptJquery('.wrapper').find('.large').find('.image').html('');
					var iframeURL = scriptJquery('.marker_'+id).find('.iframe_url').html();
					var height = 164;
					var width = 294;
					previousIndex = scriptJquery('.marker_'+ id).parent().parent().css('z-index');
					scriptJquery('.marker_'+ id).parent().parent().css('z-index','9999');
					if(typeof iframeURL != 'undefined' && !scriptJquery('.marker_'+id).find('.wrapper').find('.large').find('.image').find('iframe').attr('src'))
						scriptJquery('.marker_'+id).find('.wrapper').find('.large').find('.image').html('<iframe src="'+iframeURL+'" height="'+height+'" width="'+width+'" style="overflow:hidden"  allowfullscreen="" >');
						scriptJquery('.pin').removeClass('active').css('z-index', 10);
						scriptJquery('.marker_'+ id).addClass('active').css('z-index', 200);
						scriptJquery('.marker_'+ id+' .large .close').click(function(){
							scriptJquery(this).parent().parent().parent().parent().parent().css('z-index',previousIndex);
							scriptJquery('.marker_'+id).find('.wrapper').find('.large').find('.image').html('');
							scriptJquery('.pin').removeClass('active');
							return false;
						});
				});
				markers.push( markerData);
				markerData.setMap(map);
				bounds.extend(markerData.getPosition());
				markerData.markerid = countMarker;
				oms.addMarker(markerData);
				countMarker++;
  }
	map.fitBounds(bounds);
}
google.maps.event.addDomListener(window, 'load', initialize);
scriptJquery('.sesbutton_share').click(function(e){
	e.preventDefault();
});
</script>
<div id="map-canvas" class="map sesbasic_large_map sesbm sesbasic_bxs"></div>
