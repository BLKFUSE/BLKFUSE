//MAP CODE 
//initialize default values
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
function editMarkerOnMapVideoEdit(){
	geocoder = new google.maps.Geocoder();
	var address = trim(document.getElementById('locationSes').value);
	var lat = document.getElementById('latSes').value;
	var lng = document.getElementById('lngSes').value;
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
//list page map 
function initializeSesVideoMapList() {
if(mapLoad){
  var mapOptions = {
    center: new google.maps.LatLng(-33.8688, 151.2195),
    zoom: 17
  };
   map = new google.maps.Map(document.getElementById('map-canvas-list'),
    mapOptions);
}
  var input =document.getElementById('locationSesList');

  var autocomplete = new google.maps.places.Autocomplete(input);
if(mapLoad)
  autocomplete.bindTo('bounds', map);

if(mapLoad){
   infowindow = new google.maps.InfoWindow();
   marker = new google.maps.Marker({
    map: map,
    anchorPoint: new google.maps.Point(0, -29)
  });
}
  google.maps.event.addListener(autocomplete, 'place_changed', function() {
	
	if(mapLoad){
    infowindow.close();
    marker.setVisible(false);
	}
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      return;
    }
	if(mapLoad){
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
	}
		document.getElementById('lngSesList').value = place.geometry.location.lng();
		document.getElementById('latSesList').value = place.geometry.location.lat();
if(mapLoad){
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);
}
    var address = '';
    if (place.address_components) {
      address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
      ].join(' ');
    }
  if(mapLoad){
	  infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
    infowindow.open(map, marker);
		return false;
	}
	}); 
	if(mapLoad){
		google.maps.event.addDomListener(window, 'load', initializeSesVideoMapList);
	}
}

function editSetMarkerOnMapListVideo(){
	geocoder = new google.maps.Geocoder();
if(mapLoad){
	if(document.getElementById('ses_location_data_list'))
		var address = trim(document.getElementById('ses_location_data_list').innerHTML);
}else{
	if(document.getElementById('locationSesList'))
		var address = trim(document.getElementById('locationSesList').innerHTML);	
}
	var lat = document.getElementById('lngSesList').value;
	var lng = document.getElementById('latSesList').value;
  var latlng = new google.maps.LatLng(lat, lng);
    geocoder.geocode({'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
          map.setCenter(results[0].geometry.location);
          marker = new google.maps.Marker({
              position: results[0].geometry.location,
              map: map
          });
          infowindow.setContent(results[0].formatted_address);
          infowindow.open(map, marker);
      } else {
        //console.log("Map failed to show location due to: " + status);
      }
    });
}
function openURLinSmoothBox(openURLsmoothbox){
	Smoothbox.open(openURLsmoothbox);
	parent.Smoothbox.close;
	return false;
}
function showTooltip(x, y, contents, className) {
	if(scriptJquery('.sesbasic_notification').length > 0)
		scriptJquery('.sesbasic_notification').hide();
	scriptJquery('<div class="sesbasic_notification '+className+'">' + contents + '</div>').css( {
		display: 'block',
	}).appendTo("body").fadeOut(5000,'',function(){
		scriptJquery(this).remove();	
	});
}
scriptJquery(document).on('click','#sesLightboxLikeUnlikeButtonVideo',function(){
	if(typeof checkRequestmoduleIsVideo != "function")
		return;
   
    var dataid = scriptJquery(this).attr('data-id');
    if(!scriptJquery('#sesadvancedcomment_like_action_'+dataid).length){
		  scriptJquery('#comments .comments_options').find("a:eq(1)").trigger('click');
    }else{
      var count = scriptJquery(this).find('#like_unlike_count').html();
      if(scriptJquery('#sesadvancedcomment_like_action_'+dataid).hasClass('sesadvancedcommentlike')){
        count = parseInt(count) + 1;
        scriptJquery(this).addClass(' button_active');
      }else{
        count = parseInt(count) - 1;
        scriptJquery(this).removeClass('button_active');
      }
      scriptJquery(this).find('#like_unlike_count').html(count);
      scriptJquery('#sesadvancedcomment_like_action_'+dataid).trigger('click');	
    }
    return false;
});
function trim(str, chr) {
  var rgxtrim = (!chr) ? new RegExp('^\\s+|\\s+$', 'g') : new RegExp('^'+chr+'+|'+chr+'+$', 'g');
  return str.replace(rgxtrim, '');
}
scriptJquery(document).on('click','#openVideoInLightbox',function(){
	var URL = window.location.href;
	//URL = URL.replace(videoURLsesvideo,videoURLsesvideo+'/imageviewerdetail')
	videoURLsesbasic = videoURLsesvideo;
	if(openVideoInLightBoxsesbasic== 0 ){
		window.location.href = getImageHref;
		return true;
	}
	moduleName = 'sesvideo';
	itemType = 'sesvideo_video';
	var image = scriptJquery('#sesvideo_image_video_url').attr('data-src');
	getRequestedVideoForImageViewer(image,URL);
});
scriptJquery(document).on('click','.ses-video-viewer',function(e){
		e.preventDefault();
});
function checkRequestmoduleIsVideo(){
	if(scriptJquery('#ses_media_lightbox_container_video').length > 0)
		return true;
	else
		return false;
}
scriptJquery(document).on('click','.sesbasic_form_opn',function(e){
		 scriptJquery(this).parent().parent().find('form').show();
		 scriptJquery(this).parent().parent().find('form').focus();
		 var widget_id = scriptJquery(this).data('rel');
		 if(widget_id)
				eval("pinboardLayout_"+widget_id+"()");			
});
//send quick share link
function sessendQuickShare(url){
	if(!url)
		return;
	scriptJquery('.sesbasic_popup_slide_close').trigger('click');
	(scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': url,
      'data': {
        format: 'html',
				is_ajax : 1
      },
      success: function(responseHTML) {
        //keep Silence
				showTooltip('10','10','<i class="fa fa-envelope"></i><span>'+(en4.core.language.translate("Quick share successfully"))+'</span>','sesbasic_message_notification');
      }
    }));
}
//check embed function exists for message
function checkFunctionEmbed(){
	if( typeof flashembed == 'function'){
		//silence
	}else{
		 if(scriptJquery('.sesvideo_attachment_info').length){
				var href = scriptJquery('.sesvideo_attachment_info').find('a').attr('href');
				window.location.href = href;
				return false;
		 }
	}
	return;	
}
//open url in smoothbox
function opensmoothboxurl(openURLsmoothbox){
	Smoothbox.open(openURLsmoothbox);
	parent.Smoothbox.close;
	return false;
}
scriptJquery(document).on('click','.sesvideo_list_option_toggle',function(){
  if(scriptJquery(this).hasClass('open')){
    scriptJquery(this).removeClass('open');
  }else{
    scriptJquery(this).addClass('open');
  }
    return false;
});
//add to watch later function ajax.
scriptJquery(document).on('click','.sesvideo_watch_later',function(){
		var that = this;
		if(!scriptJquery(this).attr('data-url'))
			return;
		if(scriptJquery(this).hasClass('selectedWatchlater')){
				scriptJquery(this).removeClass('selectedWatchlater');
		}else
				scriptJquery(this).addClass('selectedWatchlater');
		 (scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url':  en4.core.baseUrl + 'sesvideo/watchlater/add/',
      'data': {
        format: 'html',
        id: scriptJquery(this).attr('data-url'),
      },
      success: function(responseHTML) {
        var response =jQuery.parseJSON( responseHTML );
				if(response.error)
					alert(en4.core.language.translate('Something went wrong,please try again later'));
				else{
					if(response.status == 'delete'){
						showTooltip('10','10','<i class="far fa-clock"></i><span>'+(en4.core.language.translate("Video removed successfully from watch later"))+'</span>');
							scriptJquery(that).removeClass('selectedWatchlater');
					}else{
						showTooltip('10','10','<i class="far fa-clock"></i><span>'+(en4.core.language.translate("Video successfully added to watch later"))+'</span>','sesbasic_watchlater_notification');
							scriptJquery(that).addClass('selectedWatchlater');
					}
				}
					return true;
      }
    }));
		
});
//common function for like comment ajax
function like_favourite_data(element,functionName,itemType,likeNoti,unLikeNoti,className){
		if(!scriptJquery(element).attr('data-url'))
			return;
		if(scriptJquery(element).hasClass('button_active')){
				scriptJquery(element).removeClass('button_active');
		}else
				scriptJquery(element).addClass('button_active');
		 (scriptJquery.ajax({
       dataType: 'html',
      method: 'post',
      'url':  en4.core.baseUrl + 'sesvideo/index/'+functionName,
      'data': {
        format: 'html',
        id: scriptJquery(element).attr('data-url'),
				type:itemType,
      },
      success: function(responseHTML) {
        var response =jQuery.parseJSON( responseHTML );
				if(response.error)
					alert(en4.core.language.translate('Something went wrong,please try again later'));
				else{
					scriptJquery(element).find('span').html(response.count);
					if(response.condition == 'reduced'){
							scriptJquery(element).removeClass('button_active');
							showTooltip(10,10,unLikeNoti)
							return true;
					}else{
							scriptJquery(element).addClass('button_active');
							showTooltip(10,10,likeNoti,className)
							return false;
					}
				}
      }
    }));
}
scriptJquery(document).on('click','.sesvideo_favourite_sesvideo_video',function(){
	like_favourite_data(this,'favourite','sesvideo_video','<i class="fa fa-heart"></i><span>'+(en4.core.language.translate("Video added as Favourite successfully"))+'</span>','<i class="fa fa-heart"></i><span>'+(en4.core.language.translate("Video Unfavorited successfully"))+'</span>','sesbasic_favourites_notification');
});
scriptJquery(document).on('click','.sesvideo_like_sesvideo_video',function(){
	like_favourite_data(this,'like','sesvideo_video','<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Video Liked successfully"))+'</span>','<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Video Unliked successfully"))+'</span>','sesbasic_liked_notification');
});
scriptJquery(document).on('click','.sesvideo_like_sesvideo_playlist',function(){
	like_favourite_data(this,'like','sesvideo_playlist','<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Playlist Liked successfully"))+'</span>','<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Playlist Unliked successfully"))+'</span>','sesbasic_liked_notification');
});
scriptJquery(document).on('click','.sesvideo_favourite_sesvideo_playlist',function(){
	like_favourite_data(this,'favourite','sesvideo_playlist','<i class="fa fa-heart"></i><span>'+(en4.core.language.translate("Playlist added as Favourite successfully"))+'</span>','<i class="fa fa-heart"></i><span>'+(en4.core.language.translate("Playlist Unfavorited successfully"))+'</span>','sesbasic_favourites_notification');		
});
scriptJquery(document).on('click','.sesvideo_favourite_sesvideo_chanel',function(){
	like_favourite_data(this,'favourite','sesvideo_chanel','<i class="fa fa-heart"></i><span>'+(en4.core.language.translate("Channel added as Favourite successfully"))+'</span>','<i class="fa fa-heart"></i><span>'+(en4.core.language.translate("Channel Unfavorited successfully"))+'</span>','sesbasic_favourites_notification');
});
scriptJquery(document).on('click','.sesvideo_like_sesvideo_chanel',function(){
	like_favourite_data(this,'like','sesvideo_chanel','<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Channel Liked successfully"))+'</span>','<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Channel Unliked successfully"))+'</span>','sesbasic_liked_notification');
});
scriptJquery(document).on('click','.sesvideo_favourite_sesvideo_artist',function(){
	like_favourite_data(this,'favourite','sesvideo_artist','<i class="fa fa-heart"></i><span>'+(en4.core.language.translate("Artist added as Favourite successfully"))+'</span>','<i class="fa fa-heart"></i><span>'+(en4.core.language.translate("Artist Unfavorited successfully"))+'</span>','sesbasic_favourites_notification');
});
scriptJquery(document).on('click','.sesvideo_chanel_follow',function(){
		if(!scriptJquery(this).attr('data-url'))
			return;
		if(scriptJquery(this).hasClass('button_active')){
				scriptJquery(this).removeClass('button_active');
				if(scriptJquery(this).hasClass('button_chanel'))
					scriptJquery(this).html(en4.core.language.translate("Follow"));
		}else{
				scriptJquery(this).addClass('button_active');
				if(scriptJquery(this).hasClass('button_chanel'))
					scriptJquery(this).html(en4.core.language.translate("Un-Follow"));
		}
			var that = this;
		 (scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url':  en4.core.baseUrl + "sesvideo/chanel/follow/chanel_id/"+scriptJquery(this).attr('data-url'),
      'data': {
        format: 'html',
        chanel_id:scriptJquery(this).attr('data-url'),
      },
      success: function(responseHTML) {
        var response =jQuery.parseJSON( responseHTML );
				if(response.error)
					alert(en4.core.language.translate('Something went wrong,please try again later'));
				else{
					scriptJquery(that).find('span').html(response.count);
					if(response.condition == 'reduced'){
							showTooltip('10','10','<i class="fa fa-check"></i><span>'+(en4.core.language.translate("Channel un-follow successfully"))+'</span>','sesbasic_favourites_notification');
							scriptJquery(that).removeClass('button_active');
							if(scriptJquery(that).hasClass('button_chanel'))
								scriptJquery(that).html(en4.core.language.translate("Follow"));
					}else{
							scriptJquery(that).addClass('button_active');
							showTooltip('10','10','<i class="fa fa-check"></i><span>'+(en4.core.language.translate("Channel follow successfully"))+'</span>','sesbasic_follow_notification');
							if(scriptJquery(that).hasClass('button_chanel'))
								scriptJquery(that).html(en4.core.language.translate("Un-Follow"));
					}
				}
					return true;
      }
    }));
});


scriptJquery(document).on("click", '.sesvideo_lightbox_open', function (e) {
	if( /iPhone|iPad|iPod|BlackBerry|IEMobile/i.test(navigator.userAgent) ) {
		return true;
	}
	e.preventDefault();
	var imageObject = scriptJquery(this);
  var getImageHref = imageObject.attr('href');
	videoURLsesbasic = videoURLsesvideo;
	if(openVideoInLightBoxsesbasic == 0 ){
		window.location.href = getImageHref;
		return true;
	}
	moduleName = 'sesvideo';
	itemType = 'sesvideo_video';
	var imageSource = imageObject.find('span').css('background-image').replace('url(','').replace(')','').replace('"','').replace('"','');
	getImageHref = getImageHref.replace(videoURLsesbasic+'/imageviewerdetail',videoURLsesbasic);
	getRequestedVideoForImageViewer(imageSource,getImageHref);
});

var sesvideo_cookie_set_value = [];
function setCookieSesvideo(cvalue) {
  var d = new Date();
  d.setTime(d.getTime() + (1*24*60*60*1000));
  var expires = "expires="+d.toGMTString();
  var length = sesvideo_cookie_set_value.length;
  var exists = false;
  if(length > 0){
    for (var i = 0; i < length; i++) {
      if (sesvideo_cookie_set_value[i] === cvalue) 
      {
        exists = true;   
        break;
      }
    }
  }
  if(!exists)
    sesvideo_cookie_set_value.push(cvalue);
  document.cookie = 'sesvideo_lightbox_value' + "=" + sesvideo_cookie_set_value + "; " + expires+"; path=/"; 
}
scriptJquery(document).on('click','.sesvideo_home_btn',function () {
    var data = scriptJquery(this).data('action');
    if(data){
        window.location.href = videosURLsesvideos+'/'+data;
    }
})
 function showtypevalue(value) {
    if(value == 'carouselview')
      scriptJquery('#limitCarousel-wrapper').show();
    else
      scriptJquery('#limitCarousel-wrapper').hide();
  }
