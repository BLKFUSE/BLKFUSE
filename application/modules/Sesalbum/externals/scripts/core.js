/* $Id: core.js  2015-6-16 00:00:000 SocialEngineSolutions $ */
(function() { // START NAMESPACE
var $ = 'id' in document ? document.id : window.$;
en4.album = {
  composer : false,
  getComposer : function(){
    if( !this.composer ){
      this.composer = new en4.album.acompose();
    }
    return this.composer;
  },
  rotate : function(photo_id, angle) {
    request = scriptJquery.ajax({
      dataType: 'json',
      url : en4.core.baseUrl + 'sesalbum/photo/rotate',
      data : {
        format : 'json',
        photo_id : photo_id,
        angle : angle
      },
      success: function(response) {
        // Check status
        if( $type(response) == 'object' &&
            $type(response.status) &&
            response.status == false ) {
          en4.core.showError('An error has occurred processing the request. The target may no longer exist.' + '<br /><br /><button onClick="Smoothbox.close()">Close</button>');
          return;
        } else if( $type(response) != 'object' ||
          !$type(response.status) ) {
          en4.core.showError('An error has occurred processing the request. The target may no longer exist.' + '<br /><br /><button onClick="Smoothbox.close()">Close</button>');
          return;
        }

        // Ok, let's refresh the page I guess
        window.location.reload(true);
      }
    });
  },

  flip : function(photo_id, direction) {
    request = scriptJquery.ajax({
      dataType: 'json',
      url : en4.core.baseUrl + 'sesalbum/photo/flip',
      data : {
        format : 'json',
        photo_id : photo_id,
        direction : direction
      },
      success: function(response) {
        // Check status
        if( $type(response) == 'object' &&
            $type(response.status) &&
            response.status == false ) {
          en4.core.showError('An error has occurred processing the request. The target may no longer exist.' + '<br /><br /><button onClick="Smoothbox.close()">Close</button>');
          return;
        } else if( $type(response) != 'object' ||
          !$type(response.status) ) {
          en4.core.showError('An error has occurred processing the request. The target may no longer exist.' + '<br /><br /><button onClick="Smoothbox.close()">Close</button>');
          return;
        }

        // Ok, let's refresh the page I guess
        window.location.reload(true);
      }
    });
  },

  crop : function(photo_id, x, y, w, h) {
    if( $type(x) == 'object' ) {
      h = x.h;
      w = x.w;
      y = x.y;
      x = x.x;
    }
    request = scriptJquery.ajax({
      dataType: 'json',
      url : en4.core.baseUrl + 'sesalbum/photo/crop',
      data : {
        format : 'json',
        photo_id : photo_id,
        x : x,
        y : y,
        w : w,
        h : h
      },
      success: function(response) {
        // Check status
        if( $type(response) == 'object' &&
            $type(response.status) &&
            response.status == false ) {
          en4.core.showError('An error has occurred processing the request. The target may no longer exist.' + '<br /><br /><button onClick="Smoothbox.close()">Close</button>');
          return;
        } else if( $type(response) != 'object' ||
          !$type(response.status) ) {
          en4.core.showError('An error has occurred processing the request. The target may no longer exist.' + '<br /><br /><button onClick="Smoothbox.close()">Close</button>');
          return;
        }

        // Ok, let's refresh the page I guess
        window.location.reload(true);
      }
    }); 
  }

};

en4.album.acompose = function(options){

  this.__proto__ = new Composer.Plugin.Interface(options);

  this.name = 'photo'

  this.active = false

  this.options = {}

  this.frame = false

  this.photo_id = false

  this.initialize = function(element, options){
    if( !element ) element = scriptJquery('#activity-compose-photo');
    this.elements = new Hash(elements);
    this.params = new Hash(this.params);
    this.__proto__.initialize.call(this,options);
  }

  this.activate = function(){
    this.__proto__.activate.call(this);

    this.element.style.display = '';
    document.getElementById('activity-compose-photo-input').style.display = '';
    document.getElementById$('activity-compose-photo-loading').style.display = 'none';
    document.getElementById('activity-compose-photo-preview').style.display = 'none';
    document.getElementById('activity-form').addEvent('beforesubmit', this.checkSubmit.bind(this));
    this.active = true;

    // @todo this is a hack
    scriptJquery('#activity-post-submit').hide()
  }

  this.deactivate = function(){
    if( !this.active ) return;
    this.active = false
    this.photo_id = false;
    if( this.frame ) this.frame.destroy();
    this.frame = false;
    scriptJquery('#activity-compose-photo-preview').html('');
    scriptJquery('#activity-compose-photo-input').show()
    scriptJquery(this.element).show();
    document.getElementById('activity-form').removeEvent('submit', this.checkSubmit.bind(this));;

    // @todo this is a hack
    document.getElementById('activity-post-submit').style.display = 'block';
    document.getElementById('activity-compose-photo-activate').style.display = '';
    document.getElementById('activity-compose-link-activate').style.display = '';
    this.__proto__.deactivate.call(this);

  }

  this.process = function(){
    if( this.photo_id ) return;

    if( !this.frame ){
      this.frame = new IFrame({
        src : 'about:blank',
        name : 'albumComposeFrame',
        styles : {
          display : 'none'
        }
      });
      this.frame.appendTo(this.element);
    }
    document.getElementById('activity-compose-photo-input').style.display = 'none';
    document.getElementById('activity-compose-photo-loading').style.display = '';
    document.getElementById('activity-compose-photo-form').target = 'albumComposeFrame';
    document.getElementById('activity-compose-photo-form').submit();
  }

  this.processResponse = function(responseObject){
    if( this.photo_id ) return;

    (scriptJquery.crtEle('img', {
      src : responseObject.src,
      styles : {
        //'max-width' : '100px'
      }
    })).appendTo( document.getElementById('activity-compose-photo-preview'));
    document.getElementById('activity-compose-photo-loading').style.display = 'none';
    document.getElementById('activity-compose-photo-preview').style.display = '';
    this.photo_id = responseObject.photo_id;

    // @todo this is a hack
    document.getElementById('activity-post-submit').style.display = 'block';
    document.getElementById('activity-compose-photo-activate').style.display = 'none';
    document.getElementById('activity-compose-link-activate').style.display = 'none';
  }

  this.checkSubmit = function(event)
  {
    if( this.active && this.photo_id )
    {
      //event.stop();
      document.getElementById('activity-form').attachment_type.value = 'album_photo';
      document.getElementById('activity-form').attachment_id.value = this.photo_id;
    }
  }
};
})(); // END NAMESPACE
//MAP CODE
//initialize default values
var map;
var infowindow;
var marker;
var mapLoad = true;
function initializeSesAlbumMap() {
  var mapOptions = {
    center: new google.maps.LatLng(-33.8688, 151.2195),
    zoom: 17
  };
   map = new google.maps.Map(document.getElementById('map-canvas'),
    mapOptions);

  var input =document.getElementById('locationSes');

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
	google.maps.event.addDomListener(window, 'load', initializeSesAlbumMap);
}
function editSetMarkerOnMap(){
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
function initializeSesAlbumMapList() {
if(mapLoad){
  var mapOptions = {
    center: new google.maps.LatLng(-33.8688, 151.2195),
    zoom: 17
  };
   map = new google.maps.Map(document.getElementById('map-canvas-list'),
    mapOptions);
}
if(scriptJquery('#locationSes').length)
	var input = document.getElementById('locationSes');
else
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
	if(scriptJquery('#locationSes').length){
		document.getElementById('lngSes').value = place.geometry.location.lng();
		document.getElementById('latSes').value = place.geometry.location.lat();
	}else{
		document.getElementById('lngSesList').value = place.geometry.location.lng();
		document.getElementById('latSesList').value = place.geometry.location.lat();
	}
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
		google.maps.event.addDomListener(window, 'load', initializeSesAlbumMapList);
	}
}

function editSetMarkerOnMapList(){
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
scriptJquery(document).on('click','.smoothboxOpen',function(){
	var url = scriptJquery(this).attr('href');
	openURLinSmoothBox(url);
	return false;
});
function openURLinSmoothBox(openURLsmoothbox){
	Smoothbox.open(openURLsmoothbox);
	parent.Smoothbox.close;
	return false;
}
// ALBUM LIKE ON ALBUM LISTINGS
scriptJquery(document).on('click','.sesalbum_albumlike',function(){
		var data = scriptJquery(this).attr('data-src');
		var objectDocument = this;
		 (scriptJquery.ajax({
       dataType: 'json',
			url : en4.core.baseUrl + 'sesalbum/album/like/album_id/'+data,
			data : {
				format : 'json',
				type : 'album',
				id : data,
			},
		 success: function(responseHTML) {
       var data = responseHTML; //JSON.parse(responseHTML);
			 if(data.status == 'false'){
					 if(data.error == 'Login')
							alert(en4.core.language.translate('Please login'));
					 else
							alert(en4.core.language.translate('Invalid argument supplied.'));
			 }else{
					 if(data.condition == 'increment'){
              scriptJquery(objectDocument).addClass('button_active');
							scriptJquery(objectDocument).find('span').html(data.like_count);
							showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Album Liked Successfully"))+'</span>', 'sesbasic_liked_notification');
					 }else{
              scriptJquery(objectDocument).removeClass('button_active');
							scriptJquery(objectDocument).find('span').html(data.like_count);
							showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate("Album Unliked Successfully")+'</span>');
					 }
				}
			var ObjectIncrem = scriptJquery(objectDocument).parent().parent().find('.sesalbum_list_grid_info').find('.sesalbum_list_grid_stats');
			var ObjectLength = scriptJquery(ObjectIncrem).children();
			if(ObjectLength.length >0){
					for(i=0;i<ObjectLength.length;i++){
						if(scriptJquery(ObjectLength[i]).hasClass('sesalbum_list_grid_likes')){
							var title = scriptJquery(ObjectLength[i]).attr('title').replace(/[^0-9]/g, '');
							scriptJquery(ObjectLength[i]).attr('title',scriptJquery(ObjectLength[i]).attr('title').replace(title,data.like_count));
							var innerContent = scriptJquery(ObjectLength[i]).html().replace(/[^0-9]/g, '');
							scriptJquery(ObjectLength[i]).html(scriptJquery(ObjectLength[i]).html().replace(innerContent,data.like_count));
						}
					}
			}
		}
		}));
		return false;
});
function sesRotate(photo_id,rotateAngle){
	var className;
	scriptJquery('#ses-rotate-'+rotateAngle).attr('class','icon_loading');
	if(rotateAngle == 90 || rotateAngle == 270){
		if(rotateAngle == 90)
			className = 'sesalbum_icon_photos_rotate_ccw';
		else
			className = 'sesalbum_icon_photos_rotate_cw';
		rotateSes(photo_id,rotateAngle,className);
	}else{
		if(rotateAngle == 'horizontal')
			className = 'sesalbum_icon_photos_flip_horizontal';
		else
			className = 'sesalbum_icon_photos_flip_vertical';
		flipSes(photo_id,rotateAngle,className);
	}

	return false;
}
function flipSes(photo_id,rotateAngle,className){
	request = scriptJquery.ajax({
      url : en4.core.baseUrl + 'albums/photo/flip',
      data : {
        dataType: 'json',
        format : 'json',
        photo_id : photo_id,
        direction : rotateAngle
      },
      success: function(response) {
        // Check status
        if( $type(response) == 'object' &&
            $type(response.status) &&
            response.status == false ) {
						alert(en4.core.language.translate('An error has occurred processing the request. The target may no longer exist.'));
						return;
        } else if( $type(response) != 'object' ||
          !$type(response.status) ) {
         	 alert(en4.core.language.translate('An error has occurred processing the request. The target may no longer exist.'));
           return;
        }
					if(response.status){
							scriptJquery('#ses-rotate-'+rotateAngle).attr('class',className);
						if(scriptJquery('#media_photo').length>0 && !scriptJquery('#ses_media_lightbox_container').length)
							scriptJquery('#media_photo').attr('src',response.href);
						else
							scriptJquery('#gallery-img').attr('src',response.href);
						return;
					}
      }
    });
    
		return false;
}
function rotateSes(photo_id,rotateAngle,className){
	request = scriptJquery.ajax({
      url : en4.core.baseUrl + 'albums/photo/rotate',
      data : {
        dataType: 'json',
        format : 'json',
        photo_id : photo_id,
        angle : rotateAngle
      },
      success: function(response) {
        // Check status
        if( $type(response) == 'object' &&
            $type(response.status) &&
            response.status == false ) {
 					  alert(en4.core.language.translate('An error has occurred processing the request. The target may no longer exist.'));
					  return;
        } else if( $type(response) != 'object' ||
          !$type(response.status) ) {
           alert(en4.core.language.translate('An error has occurred processing the request. The target may no longer exist.'));
           return;
        }
			 if(response.status){
							scriptJquery('#ses-rotate-'+rotateAngle).attr('class',className);
							if(scriptJquery('#media_photo').length>0 && (scriptJquery('#ses_media_lightbox_container').css('display') == 'none' ||  scriptJquery('#ses_media_lightbox_container').length == 0))
								scriptJquery('#media_photo').attr('src',response.href);
							else
								scriptJquery('#gallery-img').attr('src',response.href);
								return;
					}
      }
    });
    
		return;
}
//FAV LIKE ON ALBUM LISTING
scriptJquery(document).on('click','.sesalbum_albumFav',function(){
		var data = scriptJquery(this).attr('data-src');
		var objectDocument = this;
		 (scriptJquery.ajax({
       dataType: 'json',
			url : en4.core.baseUrl + 'sesalbum/album/fav/album_id/'+data,
			data : {
				format : 'json',
				type : 'album',
				id : data,
			},
		 success: function(responseHTML) {
       var data = responseHTML;
			 if(data.status == 'false'){
					 if(data.error == 'Login')
							alert(en4.core.language.translate('Please login'));
					 else
							alert(en4.core.language.translate('Invalid argument supplied.'));
			 }else{
					 if(data.condition == 'increment'){
              scriptJquery(objectDocument).addClass('button_active');
							scriptJquery(objectDocument).find('span').html(data.favourite_count);
							showTooltip(10,10,'<i class="fa fa-heart"></i><span>'+en4.core.language.translate("Album Favorited Successfully")+'</span>', 'sesbasic_favourites_notification');
					 }else{
              scriptJquery(objectDocument).removeClass('button_active');
							scriptJquery(objectDocument).find('span').html(data.favourite_count);
              showTooltip(10,10,'<i class="fa fa-heart"></i><span>'+en4.core.language.translate("Album Unfavorited Successfully")+'</span>');
					 }
				}
			var ObjectIncrem = scriptJquery(objectDocument).parent().parent().find('.sesalbum_list_grid_info').find('.sesalbum_list_grid_stats');
			var ObjectLength = scriptJquery(ObjectIncrem).children();
			if(ObjectLength.length >0){
					for(i=0;i<ObjectLength.length;i++){
            if(scriptJquery(ObjectLength[i]).hasClass('sesalbum_list_grid_fav')){
							var title = scriptJquery(ObjectLength[i]).attr('title').replace(/[^0-9]/g, '');
							scriptJquery(ObjectLength[i]).attr('title',scriptJquery(ObjectLength[i]).attr('title').replace(title,data.favourite_count));
							var innerContent = scriptJquery(ObjectLength[i]).html().replace(/[^0-9]/g, '');
							scriptJquery(ObjectLength[i]).html(scriptJquery(ObjectLength[i]).html().replace(innerContent,data.favourite_count));
						}
					}
			}
		}
		}));
		return false;
});
// ALBUM FAV ON ALBUM LISTINGS
scriptJquery(document).on('click','.sesalbum_photoFav ,#sesalbum_favourite',function(){
		var data = scriptJquery(this).attr('data-src');
		var objectDocument = this;
		 (scriptJquery.ajax({
       dataType: 'json',
			url : en4.core.baseUrl + 'sesalbum/photo/fav/photo_id/'+data,
			data : {
				format : 'json',
				type : 'photo',
				id : data,
			},
		 success: function(responseHTML) {
       var data = responseHTML;
			 if(data.status == 'false'){
					 if(data.error == 'Login')
							alert(en4.core.language.translate('Please login'));
					 else
							alert(en4.core.language.translate('Invalid argument supplied.'));
			 }else{
					 if(data.condition == 'increment'){
              scriptJquery(objectDocument).addClass('button_active');
							scriptJquery(objectDocument).find('span').html(data.favourite_count);
							showTooltip(10,10,'<i class="fa fa-heart"></i><span>'+en4.core.language.translate("Photo Favorited Successfully")+'</span>', 'sesbasic_favourites_notification');
					 }else{
              scriptJquery(objectDocument).removeClass('button_active');
							scriptJquery(objectDocument).find('span').html(data.favourite_count);
              showTooltip(10,10,'<i class="fa fa-heart"></i><span>'+en4.core.language.translate("Photo Unfavorited Successfully")+'</span>');
					 }
				}
			var ObjectIncrem = scriptJquery(objectDocument).parent().parent().find('.sesalbum_list_grid_info').find('.sesalbum_list_grid_stats');
			var ObjectLength = scriptJquery(ObjectIncrem).children();
			if(ObjectLength.length >0){
					for(i=0;i<ObjectLength.length;i++){
						if(scriptJquery(ObjectLength[i]).hasClass('sesalbum_list_grid_fav')){
							var title = scriptJquery(ObjectLength[i]).attr('title').replace(/[^0-9]/g, '');
							scriptJquery(ObjectLength[i]).attr('title',scriptJquery(ObjectLength[i]).attr('title').replace(title,data.favourite_count));
							var innerContent = scriptJquery(ObjectLength[i]).html().replace(/[^0-9]/g, '');
							scriptJquery(ObjectLength[i]).html(scriptJquery(ObjectLength[i]).html().replace(innerContent,data.favourite_count));
						}
					}
			}
		}
		}));
		return false;
});
//Admin featured photo/album
scriptJquery(document).on('click','.sesalbum_admin_sponsored',function(event){
		event.preventDefault();
		var data = scriptJquery(this).attr('href');
		scriptJquery(this).css('pointer-events','none');
		var objectDocument = this;
		 (scriptJquery.ajax({
       dataType: 'json',
			url : data,
			data : {
				format : 'json',
			},
		 success: function(responseHTML) {
			 scriptJquery(objectDocument).css('pointer-events','auto');
       if(responseHTML == 1)
				 		scriptJquery(objectDocument).html(en4.core.language.translate('Unmark as Sponsored'));
       else if(responseHTML == 0)
				 		scriptJquery(objectDocument).html(en4.core.language.translate('Mark Sponsored'));
		 }
		}));
		return false;
});
//Admin sponsored photo/album
scriptJquery(document).on('click','.sesalbum_admin_featured',function(event){
		event.preventDefault();
		var data = scriptJquery(this).attr('href');
		scriptJquery(this).css('pointer-events','none');
		var objectDocument = this;
		 (scriptJquery.ajax({
       dataType: 'json',
			url : data,
			data : {
				format : 'json',
			},
		 success: function(responseHTML) {
			 scriptJquery(objectDocument).css('pointer-events','auto');
       if(responseHTML == 1)
				 		scriptJquery(objectDocument).html(en4.core.language.translate('Unmark as Featured'));
       else if(responseHTML == 0)
				 		scriptJquery(objectDocument).html(en4.core.language.translate('Mark Featured'));

		 }
		}));
		return false;
});
// PHOTO LIKE ON ALBUM LISTINGS
scriptJquery(document).on('click','.sesalbum_photolike',function(){
		var data = scriptJquery(this).attr('data-src');
		var objectDocument = this;
		 (scriptJquery.ajax({
       dataType: 'json',
			url : en4.core.baseUrl + 'sesalbum/photo/like/photo_id/'+data,
			data : {
				format : 'json',
				type : 'photo',
				id : data,
			},
		 success: function(responseHTML) {
       var data = responseHTML;
			 if(data.status == 'false'){
					 if(data.error == 'Login')
							alert(en4.core.language.translate('Please login'));
					 else
							alert(en4.core.language.translate('Invalid argument supplied.'));
			 }else{
					 if(data.condition == 'increment'){
              scriptJquery(objectDocument).addClass('button_active');
							scriptJquery(objectDocument).find('span').html(data.like_count);
							showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate("Photo Liked Successfully")+'</span>', 'sesbasic_liked_notification');
					 }else{
              scriptJquery(objectDocument).removeClass('button_active');
							scriptJquery(objectDocument).find('span').html(data.like_count);
							showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate("Photo Unliked Successfully")+'</span>');
					 }

				}
			var ObjectIncrem = scriptJquery(objectDocument).parent().parent().find('.sesalbum_list_grid_info').find('.sesalbum_list_grid_stats');
			var ObjectLength = scriptJquery(ObjectIncrem).children();
			if(ObjectLength.length >0){
					for(i=0;i<ObjectLength.length;i++){
						if(scriptJquery(ObjectLength[i]).hasClass('sesalbum_list_grid_likes')){
							var title = scriptJquery(ObjectLength[i]).attr('title').replace(/[^0-9]/g, '');
							scriptJquery(ObjectLength[i]).attr('title',scriptJquery(ObjectLength[i]).attr('title').replace(title,data.like_count));
							var innerContent = scriptJquery(ObjectLength[i]).html().replace(/[^0-9]/g, '');
							scriptJquery(ObjectLength[i]).html(scriptJquery(ObjectLength[i]).html().replace(innerContent,data.like_count));
						}
					}
			}
		}
		}));
		return false;
});
// ALBUM LISTING SLIDING IMAGES
var interval;
scriptJquery(document).on({
	 mouseenter: function(){
			var imageIndex = 0;
			var imageContainerObject = this;
			var totalImageCount = scriptJquery(this).find('.sesalbum_list_grid_img').find('.ses_image_container').children('.child_image_container').length;
			var changeTime = 2000;
			interval = setInterval(function(){
				var imageURL = scriptJquery(imageContainerObject).find('.sesalbum_list_grid_img').find('.ses_image_container').children().eq(imageIndex).html();
				scriptJquery(imageContainerObject).find('.sesalbum_list_grid_img').find('.main_image_container').css('background-image', 'url(' + imageURL + ')');
				if(imageIndex == (totalImageCount-1)){
					imageIndex=0;
				}else
					imageIndex++;
			}, changeTime);
	 },
	 mouseleave: function(){
		 var imageContainerObject = this;
			if(typeof interval != 'undefined')
			clearInterval(interval);
			var totalImageCount = scriptJquery(this).find('.sesalbum_list_grid_img').find('.ses_image_container').children('.child_image_container').length;
			var imageURL = scriptJquery(imageContainerObject).find('.sesalbum_list_grid_img').find('.ses_image_container').children().eq(totalImageCount-1).html();
			scriptJquery(imageContainerObject).find('.sesalbum_list_grid_img').find('.main_image_container').css('background-image', 'url(' + imageURL + ')');
	}
}, '.sesalbum_list_grid');

scriptJquery(document).on('click','#sesLikeUnlikeButtonSesalbum',function(){
    var dataid = scriptJquery(this).attr('data-id');
    if(!scriptJquery('#sesadvancedcomment_like_actionrec_'+dataid).length){
		  scriptJquery('#comments .comments_options').find("a:eq(1)").trigger('click');
    }else{
      if(scriptJquery('#sesadvancedcomment_like_actionrec_'+dataid).hasClass('sesadvancedcommentlike')){
        scriptJquery(this).addClass(' button_active');
      }else{
        scriptJquery(this).removeClass('button_active');
      }
      scriptJquery('#sesadvancedcomment_like_actionrec_'+dataid).trigger('click');
    }
		return false;
});
scriptJquery(document).on('click','#sesLightboxLikeUnlikeButton',function(){
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
scriptJquery(document).on('click','#sescomment_button',function(){
document.getElementById('comment-form').style.display = '';
document.getElementById('comment-form').body.focus();
 scriptJquery("html, body").animate({scrollTop: scriptJquery("#comments").offset().top}, 1000);
});
scriptJquery(document).on('click','#sesImageViewerLikeUnlike',function(){
		scriptJquery('#comments .comments_options').find("a:eq(1)").trigger('click');
		return false;
});
function trim(str, chr) {
  var rgxtrim = (!chr) ? new RegExp('^\\s+|\\s+$', 'g') : new RegExp('^'+chr+'+|'+chr+'+$', 'g');
  return str.replace(rgxtrim, '');
}
// profile tab resize
scriptJquery(document).on('click','.tab_layout_sesalbum_profile_albums',function (event) {
    scriptJquery(window).trigger('resize');
});
scriptJquery(document).on('click','ul li.tab_layout_sesalbum_profile_albums',function(event){
		scriptJquery('.layout_sesalbum_profile_albums').show();
		scriptJquery(window).trigger('resize');
});
// profile update for masonry tab resize
scriptJquery(document).on('click','ul#main_tabs li.tab_layout_activity_feed',function (event) {
	 scriptJquery(window).trigger('resize');
});
// chanel photo for masonry tab resize
scriptJquery(document).on('click','ul#main_tabs li.tab_layout_sesvideo_chanel_photos',function (event) {
	 scriptJquery(window).trigger('resize');
});
scriptJquery(document).on('click','ul#main_tabs li.tab_layout_sesalbum_profile_albums',function(event){
		scriptJquery(window).trigger('resize');
});
function showTooltip(x, y, contents, className) {
	if(scriptJquery('.sesbasic_notification').length > 0)
		scriptJquery('.sesbasic_notification').hide();
	scriptJquery('<div class="sesbasic_notification '+className+'">' + contents + '</div>').css( {
		display: 'block',
	}).appendTo("body").fadeOut(5000,'',function(){
		scriptJquery(this).remove();
	});
}
function browsePhotoURL(){
	window.location.href = en4.core.baseUrl + 'albums/browse-photo';
	return false;
}
var sesalbum_cookie_set_value = [];
function setCookieSesalbum(cvalue) {
    var d = new Date();
    d.setTime(d.getTime() + (1*24*60*60*1000));
    var expires = "expires="+d.toGMTString();
		var length = sesalbum_cookie_set_value.length;
		var exists = false;
		if(length > 0){
			for (var i = 0; i < length; i++) {
				if (sesalbum_cookie_set_value[i] === cvalue)
				{
					exists = true;
					break;
				}
			}
		}
		if(!exists)
			sesalbum_cookie_set_value.push(cvalue);
    document.cookie = 'sesalbum_lightbox_value' + "=" + sesalbum_cookie_set_value + "; " + expires+"; path=/";
  }
