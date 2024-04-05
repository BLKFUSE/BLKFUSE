/* $Id: sesbasicimageviewerbasic.js  2015-6-16 00:00:000 SocialEngineSolutions $ */
var dataCommentSes = '';
	// store the default browser URL for change state after closing image viewer
	var defaultHashURL = '';
	defaultHashURL = document.URL;
	var firstStartPoint = 0;
	firstStartPointModule = 0;
	var getTagData;
	var mediaTags ;
  var offsetY = window.pageYOffset;
function defaultLayourForVideoPopup(){
	scriptJquery('<div id="ses_media_lightbox_container_video" class="ses_media_lightbox_container_video"><div class="ses_media_lightbox_overlay" id="crossSes"></div> <div class="ses_media_lightbox_content"> <div class="ses_media_lightbox_left"><div class="ses_media_lightbox_item_wrapper "><div class="ses_media_lightbox_item"><img id="video_data_lightbox" src="" alt="" /></div></div> <div class="ses_media_lightbox_nav_btns"><a id="nav-btn-next" style="display:none" class="ses_media_lightbox_nav_btn_next" ></a><a id="nav-btn-prev" class="ses_media_lightbox_nav_btn_prev" style="display:none;" ></a></div> </div><div class="ses_media_lightbox_information"></div><a href="javascript:;" id="fsbutton"  class="cross ses_media_lightbox_close_btn"><i></i></a></div></div>').appendTo('body');	
}
function getRequestedVideoForImageViewer(imageURL,requestedURL){
	if(openVideoInLightBoxsesbasic == 0){
		window.location.href = requestedURL.replace(videoURLsesbasic+'/imageviewerdetail',videoURLsesbasic);
		return;
	}
  if(!scriptJquery('#ses_media_lightbox_container_video').length)
	  defaultLayourForVideoPopup();
	scriptJquery('#ses_media_lightbox_container_video').show();
	// scriptJquery('body').css({ 'overflow': 'hidden' });
  if(firstStartPoint == 0){
    offsetY = window.pageYOffset;
    scriptJquery('html').css('position','fixed').css('width','100%').css('overflow','hidden');
    scriptJquery('html').css('top', -offsetY + 'px');
  }
 //check function call from image viewer or direct
 if(!dataCommentSes){
		dataCommentSes = scriptJquery('.layout_core_comments').html();
		getTagData = scriptJquery('#media_photo_div').find('*[id^="tag_"]');
		scriptJquery('#media_photo_div').find('*[id^="tag_"]').remove();
		mediaTags =	scriptJquery('#media_tags').html();
		scriptJquery('#media_tags').html('');
	}
	scriptJquery('.layout_core_comments').html('');
	history.pushState(null, null, requestedURL.replace(videoURLsesbasic+'/imageviewerdetail',videoURLsesbasic));
	var height = scriptJquery('.ses_media_lightbox_content').height();
	var width = scriptJquery('.ses_media_lightbox_left').width();
	scriptJquery('#media_photo_next_ses').css('height',height +'px');
	scriptJquery('#video_data_lightbox').css('max-height',height +'px');
	scriptJquery('#video_data_lightbox').css('max-width',width+'px');
	scriptJquery('#video_data_lightbox').attr('src',imageURL);
	scriptJquery('.ses_media_lightbox_information').html('');
	scriptJquery('.ses_media_lightbox_options').remove();
	scriptJquery('#nav-btn-prev').hide();
	scriptJquery('.ses_media_lightbox_nav_btn_next').css('display','none');
	requestedURL = changeurlsesbasic(requestedURL);
	getVideoViewerObjectData(imageURL,requestedURL);	
}
function changeurlsesbasic(url){
	if(url.search('imageviewerdetail') == -1){
	  url = url.replace(videoURLsesbasic,videoURLsesbasic+'/imageviewerdetail');
	}
		return url;
}
//Close image viewer
scriptJquery(document).on('click','.ses_media_lightbox_overlay, #crossSes, .cross',function (e) {
	if(typeof checkRequestmoduleIsVideo != "function")
		return;
	scriptJquery('html').css('position','initial').css('width','100%').css('overflow','initial').css("top","auto");

	if(scriptJquery('#ses_media_lightbox_container_video').css('display') != 'none'){
		scriptJquery('body').css({ 'overflow': 'initial' });
    scriptJquery(window).scrollTop(offsetY);
    if(scriptJquery('.emoji_content').css('display') == 'block')
      scriptJquery('.exit_emoji_btn').click();
		history.pushState(null, null, defaultHashURL);
		scriptJquery('.layout_core_comments').html(dataCommentSes);
		e.preventDefault();
		firstStartPoint = 0;
		dataCommentSes = '';
		firstStartPointModule = 0;
		scriptJquery('#media_photo_next').after(getTagData);
		scriptJquery('#media_tags').html(mediaTags);		
		mediaTags = '';
		getTagData = '';
	}
	scriptJquery('#ses_media_lightbox_container_video').remove();
	scriptJquery('#ses_media_lightbox_container_video').remove();
});
// fullscreen code
function changeImageViewerResolution(type){
	if(typeof checkRequestmoduleIsVideo != "function")
		return;
	if(type == 'fullscreen'){
		scriptJquery('#ses_media_lightbox_container_video').addClass('fullscreen');
	}else{
		scriptJquery('#ses_media_lightbox_container_video').removeClass('fullscreen');
	}
	return true;
}
//http://johndyer.name/native-fullscreen-javascript-api-plus-jquery-plugin/
var is_fullscreen = 0;
(function() {
	var 
		fullScreenApi = { 
			supportsFullScreen: false,
			isFullScreen: function() { return false; }, 
			requestFullScreen: function() {}, 
			cancelFullScreen: function() {},
			fullScreenEventName: '',
			prefix: ''
		},
		browserPrefixes = 'webkit moz o ms khtml'.split(' ');
	// check for native support
	if (typeof document.cancelFullScreen != 'undefined') {
		fullScreenApi.supportsFullScreen = true;
	} else {	 
		// check for fullscreen support by vendor prefix
		for (var i = 0, il = browserPrefixes.length; i < il; i++ ) {
			fullScreenApi.prefix = browserPrefixes[i];
			if (typeof document[fullScreenApi.prefix + 'CancelFullScreen' ] != 'undefined' ) {
				fullScreenApi.supportsFullScreen = true;
				break;
			}
		}
	}
	
	// update methods to do something useful
	if (fullScreenApi.supportsFullScreen) {
		fullScreenApi.fullScreenEventName = fullScreenApi.prefix + 'fullscreenchange';
		fullScreenApi.isFullScreen = function() {
			switch (this.prefix) {	
				case '':
					return document.fullScreen;
				case 'webkit':
					return document.webkitIsFullScreen;
				default:
					return document[this.prefix + 'FullScreen'];
			}
		}
		fullScreenApi.requestFullScreen = function(el) {
			return (this.prefix === '') ? el.requestFullScreen() : el[this.prefix + 'RequestFullScreen']();
		}
		fullScreenApi.cancelFullScreen = function(el) {
			return (this.prefix === '') ? document.cancelFullScreen() : document[this.prefix + 'CancelFullScreen']();
		}		
	}

	// jQuery plugin
	if (typeof jQuery != 'undefined') {
		jQuery.fn.requestFullScreen = function() {
			return this.each(function() {
				var el = jQuery(this);
				if (fullScreenApi.supportsFullScreen) {
					fullScreenApi.requestFullScreen(el);
				}
			});
		};
	}
	// export api
	window.fullScreenApi = fullScreenApi;	
})();

// do something interesting with fullscreen support
var fsButton = document.getElementById('fsbutton');
function toogle(){
if(is_fullscreen == 0)
	window.fullScreenApi.requestFullScreen(document.body);	
else
	window.fullScreenApi.cancelFullScreen(document.body);
}
if (window.fullScreenApi.supportsFullScreen) {	
	document.addEventListener(fullScreenApi.fullScreenEventName, function() {
		if (fullScreenApi.isFullScreen()) {
			is_fullscreen = 1;
			scriptJquery('.scriptJquery_toogle_screen').css('backgroundPosition','-44px 0');
			scriptJquery('#ses_media_lightbox_container_video').addClass('fullscreen');
			scriptJquery('.ses_media_lightbox_information').hide();
			var height = scriptJquery('.ses_media_lightbox_content').height();
			var width = scriptJquery('.ses_media_lightbox_left').width();
			scriptJquery('#media_photo_next_ses').css('height',height+'px');
			scriptJquery('#video_data_lightbox').css('max-height',height+'px');
			scriptJquery('#video_data_lightbox').css('max-width',width+'px');
			scriptJquery('#heightOfImageViewerContent').css('height', scriptJquery('.ses_media_lightbox_content').height()+'px');
      scriptJquery('#flexcroll').slimscroll({
        height: 'auto',
        start : scriptJquery('#ses_media_lightbox_media_info'),
      });
			scriptJquery('#video_data_lightbox').attr('src',scriptJquery('#image-src-scriptJquery-lightbox-hidden').html());
		} else {
			is_fullscreen = 0;
			scriptJquery('.ses_media_lightbox_information').show();
			scriptJquery('.scriptJquery_toogle_screen').css('backgroundPosition','0 0');
			scriptJquery('#ses_media_lightbox_container_video').removeClass('fullscreen');
			var height = scriptJquery('.ses_media_lightbox_content').height();
			var width = scriptJquery('.ses_media_lightbox_left').width();
			scriptJquery('#media_photo_next_ses').css('height',height+'px');
			scriptJquery('#video_data_lightbox').css('max-height',height+'px');
			scriptJquery('#video_data_lightbox').css('max-width',width+'px');
			scriptJquery('#heightOfImageViewerContent').css('height', scriptJquery('.ses_media_lightbox_content').height()+'px');
      scriptJquery('#flexcroll').slimscroll({
        height: 'auto',
        start : scriptJquery('#ses_media_lightbox_media_info'),
      });
		}
	}, true);
} else {
	scriptJquery('#fsbutton').hide();
}
//Key Events
scriptJquery(document).on('keyup', function (e) {
	if(typeof checkRequestmoduleIsVideo != "function")
	return;
		if(scriptJquery('#'+e.target.id).prop('tagName') == 'INPUT' || scriptJquery('#'+e.target.id).prop('tagName') == 'TEXTAREA')
				return true;
		e.preventDefault();
		//Close popup on esc
		if (e.keyCode === 27) {scriptJquery('.cross').trigger('click');return false; }
		//Next Img On Right Arrow Click
		if (e.keyCode === 39) { 
			if(scriptJquery('#'+e.target.id).prop('tagName') == 'INPUT' || scriptJquery('#'+e.target.id).prop('tagName') == 'TEXTAREA')
				return;
			NextImageViewerVideo();return false; 
		}
		// like code
		if (e.keyCode === 76) {
			if(scriptJquery('#'+e.target.id).prop('tagName') == 'INPUT' || scriptJquery('#'+e.target.id).prop('tagName') == 'TEXTAREA')
				return;
			scriptJquery('#sesLightboxLikeUnlikeButton').trigger('click');
		}
		// favourite code
		if (e.keyCode === 70) {
			if(scriptJquery('#'+e.target.id).prop('tagName') == 'INPUT' || scriptJquery('#'+e.target.id).prop('tagName') == 'TEXTAREA')
				return;
			scriptJquery('#scriptJquery_favourite').trigger('click');
		}
		//Prev Img on Left Arrow Click
		if (e.keyCode === 37) { 
			if(scriptJquery('#'+e.target.id).prop('tagName') == 'INPUT' || scriptJquery('#'+e.target.id).prop('tagName') == 'TEXTAREA')
				return;
			PrevImageViewerVideo(); return false;
		}
});
function NextImageViewerVideo(){
	if(scriptJquery('#ses_media_lightbox_container_video').css('display') == 'none'){
			return false;;	
	}
	if(scriptJquery('#nav-btn-next').length){
			document.getElementById('nav-btn-next').click();
	}
	return false;
}
function PrevImageViewerVideo(){
	if(scriptJquery('#ses_media_lightbox_container_video').css('display') == 'none'){
			return false;;	
	}
	if(scriptJquery('#nav-btn-prev').length){
		document.getElementById('nav-btn-prev').click();
	}
	return false;
}
function getVideoViewerObjectData(imageURL,requestedURL){
		 imageViewerGetRequest = scriptJquery.ajax({
      url :requestedURL,
      data : {
        format : 'html',
      },
      success : function(responseHTML)
      {
					scriptJquery('.ses_media_lightbox_content').html('');
      		scriptJquery('.ses_media_lightbox_content').html(responseHTML);
					var height = scriptJquery('.ses_media_lightbox_item_wrapper').height();
					var width = scriptJquery('.ses_media_lightbox_item_wrapper').width();
					if(scriptJquery('#media_photo_next_ses').find('#video_data_lightbox').hasClass('ses-private-image')){
							scriptJquery('#video_data_lightbox').remove();
							scriptJquery('.ses_media_lightbox_options_btns').hide();
							scriptJquery('.ses_media_lightbox_tag_btn').hide();
							scriptJquery('.ses_media_lightbox_share_btn').hide();
							scriptJquery('.ses_media_lightbox_more_btn').hide();
							scriptJquery('.ses_media_lightbox_information').hide();
							scriptJquery('#video_data_lightbox').remove();
							scriptJquery('#gallery-img').show();
					}else	if(scriptJquery('#media_photo_next_ses').find('#video_data_lightbox').hasClass('ses-blocked-video')){
							scriptJquery('.ses_media_lightbox_information').hide();
							sespromptPasswordCheck();
					}else{
							scriptJquery('.ses_media_lightbox_options_btns').show();
							scriptJquery('.ses_media_lightbox_tag_btn').show();
							scriptJquery('.ses_media_lightbox_share_btn').show();
							scriptJquery('.ses_media_lightbox_more_btn').show();
							scriptJquery('.ses_media_lightbox_information').show();
							scriptJquery('#video_data_lightbox').show();
							scriptJquery('#gallery-img').hide();
					}
					scriptJquery('.ses_media_lightbox_content').css('height',height+'px');
					scriptJquery('#video_data_lightbox').css('max-height',height+'px');
					scriptJquery('#video_data_lightbox').css('max-width',width+'px');
					scriptJquery('#video_data_lightbox').css('width',width+'px');	
					var marginTop = scriptJquery('.ses_media_lightbox_options').height();
					if(scriptJquery('.sesbasic_view_embed').find('iframe').length){
						scriptJquery('.sesbasic_view_embed').find('iframe').css('height',parseInt(height-(marginTop*2))+'px');
						scriptJquery('.sesbasic_view_embed').find('iframe').css('width',width+'px');
						scriptJquery('.sesbasic_view_embed').find('iframe').css('margin-top',marginTop+'px');
						scriptJquery('.sesbasic_view_embed').find('iframe').css('margin-bottom',marginTop+'px');		
						var srcAttr = scriptJquery('.sesbasic_view_embed').find('iframe').attr('src');		
					}else{
						scriptJquery('.sesbasic_view_embed').find('video').css('margin-top',(height/4)+'px');
					}
					if(scriptJquery('#map-canvas').length>0)
						initializeSesVideoMap();

            scriptJquery('#heightOfImageViewerContent').css('height', scriptJquery('.ses_media_lightbox_content').height()+'px');
                  
            scriptJquery('#flexcroll').slimscroll({
              height: 'auto',
              start : scriptJquery('#ses_media_lightbox_media_info'),
            });
					return true;
      	}
			});
}
function changePosition(){
	if(typeof checkRequestmoduleIsVideo != "function")
		return;
	if(scriptJquery('#nav-btn-next').length){
		document.getElementById('nav-btn-next').click();
	}else{
		toogle();
	}
}
scriptJquery(document).on('click','#editDetailsLinkVideo',function(e){
		e.preventDefault();
		scriptJquery('#titleSes').val(trim(scriptJquery('#ses_title_get').html(),' '));
		scriptJquery('#descriptionSes').val(trim(scriptJquery('#ses_title_description').html(),' '));
    if(scriptJquery('#locationSes').length >0 && typeof editSetMarkerOnMapVideo == 'function'){
		scriptJquery('#locationSes').val(trim(scriptJquery('#ses_location_data').html()));
		editSetMarkerOnMapVideo();
		google.maps.event.trigger(map, 'resize');
	}
		scriptJquery('#editDetailsFormVideo').css('display','block');
		scriptJquery('#ses_media_lightbox_media_info').css('display','none');
});
scriptJquery(document).on('click','#cancelDetailssesbasic',function(e){
		e.preventDefault();
		scriptJquery('#editDetailsFormVideo').css('display','none');
		scriptJquery('#ses_media_lightbox_media_info').css('display','block');
});
scriptJquery(document).on('click','#saveDetailssesbasic',function(e){
	e.preventDefault();
	var thisObject = this;
	scriptJquery(thisObject).prop("disabled",true);
	var video_id = scriptJquery('#video_id_ses').val();
	var formData =  scriptJquery("#changePhotoDetailsVideo").serializeArray();
	scriptJquery.ajax({  
    type: "POST",  
    url: en4.core.baseUrl+moduleName+'/index/edit-detail/video_id/'+video_id,  
    data: formData,  
    success: function(response) {  
      var data = JSON.parse(response);
			if(data.status && !data.error){
				scriptJquery(thisObject).prop("disabled",false);
				scriptJquery('#ses_title_get').html(scriptJquery('#titleSes').val());
				scriptJquery('#ses_title_description').html(scriptJquery('#descriptionSes').val());
				scriptJquery('#ses_location_data').html(scriptJquery('#locationSes').val());
				scriptJquery('#editDetailsFormVideo').css('display','none')
				scriptJquery('#ses_media_lightbox_media_info').css('display','block');
			if(scriptJquery('#locationSes').val() != '')
				scriptJquery('#seslocationIn').html('In');
			else
				scriptJquery('#seslocationIn').html('');
				return false;
			}else{
				alert(en4.core.language.translate('Something went wrong,try again later.'));	
				return false;
			}
    }
});
	return false;
});
scriptJquery(document).on('click','#comments .comments_options a',function(event){
	if(typeof checkRequestmoduleIsVideo != "function")
		return;
	var thisObject = this;
	var htmlOnclick = scriptJquery(this).attr('onclick');
	if(htmlOnclick.search('video') == -1 && htmlOnclick.search('sesvideo_chanelphoto') == -1)
		return true;
	if(htmlOnclick.search('comments') != -1){
		// unlike code
		var currentURL = window.location.href;
		if(currentURL.search('video_id') != -1)
			var itemType = 'Chanel';
		else if(currentURL.search('chanel_id') != -1)
			var itemType = 'Chanel Photo';
		else
			var itemType = 'Video';
		if(htmlOnclick.search('unlike') != -1){
		 if(scriptJquery('#ses_media_lightbox_container_video').css('display') == 'block'){
		 	scriptJquery('#sesLightboxLikeUnlikeButtonVideo').removeClass('button_active');
			scriptJquery('#sesLightboxLikeUnlikeButtonVideo').find('#like_unlike_count').html(parseInt(scriptJquery('#sesLightboxLikeUnlikeButtonVideo').find('#like_unlike_count').html())-1);
		 }
		 showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate(itemType+" removed from like successfully")+'</span>');
		}else{
			if(scriptJquery('#ses_media_lightbox_container_video').css('display') == 'block'){
		 		scriptJquery('#sesLightboxLikeUnlikeButtonVideo').addClass('button_active');
				scriptJquery('#sesLightboxLikeUnlikeButtonVideo').find('#like_unlike_count').html(parseInt(scriptJquery('#sesLightboxLikeUnlikeButtonVideo').find('#like_unlike_count').html())+1);
			}
			showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate(itemType+" Like Successfully")+'</span>', 'sesbasic_liked_notification');
		}
	}
});

var map;
var infowindow;
var marker;
var mapLoad = true;
function initializeSesVideoMap() {
  var mapOptions = {
    center: new google.maps.LatLng(-33.8688, 151.2195),
    zoom: 17
  };
   map = new google.maps.Map(document.getElementById('map-canvas'),
    mapOptions);

  var input = document.getElementById('locationSes');
  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.bindTo('bounds', map);

   infowindow = new google.maps.InfoWindow();
   marker = new google.maps.Marker({
    map: map,
    anchorPoint: new google.maps.Point(0, -29)
  });

  google.maps.event.addListener(autocomplete, 'place_changed', function() {
    infowindow.close();
    marker.setVisible(false);
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      return;
    }

    // If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);  // Why 17? Because it looks good.
    }
    marker.setIcon(/** @type {google.maps.Icon} */({
      url: place.icon,
      size: new google.maps.Size(71, 71),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(17, 34),
      scaledSize: new google.maps.Size(35, 35)
    }));
		document.getElementById('lngSes').value = place.geometry.location.lng();
		document.getElementById('latSes').value = place.geometry.location.lat();
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);

    var address = '';
    if (place.address_components) {
      address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
      ].join(' ');
    }
    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
    infowindow.open(map, marker);
		return false;
  }); 
	google.maps.event.addDomListener(window, 'load', initializeSesVideoMap);
}
function editSetMarkerOnMapVideo(){
	geocoder = new google.maps.Geocoder();
	var address = trim(document.getElementById('ses_location_data').innerHTML);
	var lat = document.getElementById('lngSes').value;
	var lng = document.getElementById('latSes').value;
  var latlng = new google.maps.LatLng(lat, lng);
    geocoder.geocode({'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
          map.setZoom(17);
          marker = new google.maps.Marker({
              position: latlng,
              map: map
          });
          infowindow.setContent(results[0].formatted_address);
          infowindow.open(map, marker);
      } else {
        //console.log("Map failed to show location due to: " + status);
      }
    });

}
