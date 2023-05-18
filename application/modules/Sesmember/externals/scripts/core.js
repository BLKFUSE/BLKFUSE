var map_event;
var infowindow_event;
var marker_event;
var mapLoad_event = true;
//list page map
function initializeSesMemberMapList() {
  if (scriptJquery('#locationSes').length)
  var input = document.getElementById('locationSes');
  else
  var input = document.getElementById('locationSesList');

  var autocomplete = new google.maps.places.Autocomplete(input);

  google.maps.event.addListener(autocomplete, 'place_changed', function () {

    var place = autocomplete.getPlace();
    if (!place.geometry) {
      return;
    }
    document.getElementById('lngSesList').value = place.geometry.location.lng();
    document.getElementById('latSesList').value = place.geometry.location.lat();
  });
  if (mapLoad_event) {
    google.maps.event.addDomListener(window, 'load', initializeSesMemberMapList);
  }
}


function likeUnlike(id, type) {
  if ($(type + '_likeunlike_' + id))
    var userId = $(type + '_likeunlike_' + id).value

  en4.core.request.send(scriptJquery.ajax({
    dataType: 'json',
    url: en4.core.baseUrl + 'sesmember/index/like-unlike',
    data: {
      format: 'json',
      'id': id,
      'type': type,
      'userId': userId
    },
    success: function (responseJSON) {
      if (responseJSON.like_id) {
        if ($(type + '_likeunlike_' + id))
          $(type + '_likeunlike_' + id).value = responseJSON.like_id;
        if ($(type + '_like_' + id))
          $(type + '_like_' + id).style.display = 'none';
        if ($(type + '_unlike_' + id))
          $(type + '_unlike_' + id).style.display = 'inline-block';
      } else {
        if ($(type + '_likeunlike_' + id))
          $(type + '_likeunlike_' + id).value = 0;
        if ($(type + '_like_' + id))
          $(type + '_like_' + id).style.display = 'inline-block';
        if ($(type + '_unlike_' + id))
          $(type + '_unlike_' + id).style.display = 'none';
      }
			if(responseJSON.condition == 'reduced'){
				showTooltipSesbasic('10','10','<i class="fa fa-thumbs-down"></i><span>'+(en4.core.language.translate("Member unliked successfully"))+'</span>','sesbasic_unliked_notification');
			}else{
				showTooltipSesbasic('10','10','<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Member liked successfully"))+'</span>','sesbasic_liked_notification');
			}
    }
  }));
}
function sesMemberLocation(i) {
   if(!document.getElementById('ses_location_'+i))
  return;

  var input = document.getElementById('ses_location_'+i);
  var autocomplete =  new google.maps.places.Autocomplete(input);
  google.maps.event.addListener(autocomplete, 'place_changed', function() {
    var place = autocomplete.getPlace();
    if (!place.geometry)
    return;
    document.getElementById('ses_lng').value = place.geometry.location.lng();
    document.getElementById('ses_lat').value = place.geometry.location.lat();

    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'latLng': new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng())}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK && results.length) {
	if (results[0]) {
	  for(var i=0; i<results[0].address_components.length; i++) {
	    var postalCode = results[0].address_components[i].long_name;
	  }
	}
	if (results[1]) {
	  var indice=0;
	  for (var j=0; j<results.length; j++) {
	    if (results[j].types[0]=='locality') {
	      indice=j;
	      break;
	    }
	  }
	  for (var i=0; i<results[j].address_components.length; i++) {
	    if (results[j].address_components[i].types[0] == "locality") {
	      //this is the object you are looking for
	      city = results[j].address_components[i].long_name;
	    }
	    if (results[j].address_components[i].types[0] == "administrative_area_level_1") {
	      //this is the object you are looking for
	      state = results[j].address_components[i].long_name;
	    }
	    if (results[j].address_components[i].types[0] == "country") {
	      //this is the object you are looking for
	      country = results[j].address_components[i].long_name;
	    }
	  }
	  if(postalCode)
	  scriptJquery('#ses_zip').val(postalCode);
	  if(city)
	  	scriptJquery('#ses_city').val(city);
	  if(state)
	 	 scriptJquery('#ses_state').val(state);
	  if(country)
	 	 scriptJquery('#ses_country').val(country);
	}
      }
    });
  });

}


scriptJquery(document).on('click', '.member_followfriend_request', function() {
    var sesthis = this;
    en4.core.request.send(scriptJquery.ajax({
    dataType: 'html',
    url: en4.core.baseUrl + 'sesmember/index/add-friend',
    data: {
      format: 'html',
      'user_id': scriptJquery(this).attr('data-src')
    },
    success: function(responseHTML) {
      var result = responseHTML;
      if(typeof result.status == 'undefined')
     	 sestooltipOrigin.tooltipster('content', responseHTML).data('ajax', 'cached');
      else if(result.status == 1)
      	sestooltipOrigin.tooltipster('content', result.message).data('ajax', 'cached');
      else
      alert(result.message);
    }
  }));

});


scriptJquery(document).on('click', '.sesmember_follow_user', function () {
	var element = scriptJquery(this);
  if (!scriptJquery (element).attr('data-url'))
  return;
  var id = scriptJquery (element).attr('data-url');
  var widget = scriptJquery (element).attr('data-widgte');

//   if (scriptJquery (element).find('i').hasClass('fa-check')){
//   	scriptJquery (element).find('i').removeClass('fa-check').addClass('fa-times');
// 		scriptJquery (element).find('span').html(en4.core.language.translate(sesmemberUnfollow));
// 	}
//   else{
//   	scriptJquery (element).find('i').removeClass('fa-times').addClass('fa-check');
// 		scriptJquery (element).find('span').html(en4.core.language.translate(sesmemeberFollow));
// 	}
  (scriptJquery.ajax({
    dataType: 'html',
    method: 'post',
    'url': en4.core.baseUrl + 'sesmember/index/follow',
    'data': {
      format: 'html',
      id: scriptJquery (element).attr('data-url'),
      type: itemType,
      widget:widget,
    },
    success: function(responseHTML) {

      var response = jQuery.parseJSON(responseHTML);
      if (response.error)
      	alert(en4.core.language.translate('Something went wrong,please try again later'));
      else {
        if(response.autofollow == 1)  {
            var elementCount = '.sesmember_follow_user_'+id;
            //scriptJquery (elementCount).find('span').html(response.count);
            if (response.condition == 'reduced') {
                scriptJquery (elementCount).find('i').removeClass('fa-times').addClass('fa-check');
                scriptJquery (elementCount).find('span').html(en4.core.language.translate(sesmemeberFollow));
                showTooltipSesbasic('10','10','<i class="fa fa-times"></i><span>'+(en4.core.language.translate("Member unfollow successfully"))+'</span>','sesbasic_unfollow_notification');
            }
            else {
                scriptJquery (elementCount).find('span').html(en4.core.language.translate(sesmemberUnfollow));
                scriptJquery (elementCount).find('i').removeClass('fa-check').addClass('fa-times');
                showTooltipSesbasic('10','10','<i class="fa fa-check"></i><span>'+(en4.core.language.translate("Member follow successfully"))+'</span>','sesbasic_follow_notification');
            }
        } else {
            scriptJquery(element).replaceWith(response.message);
        }
      }
      return true;
    }
  }));
});

scriptJquery(document).on('click', '.member_addfriend_request', function() {
    var sesthis = this;
    en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',
    url: en4.core.baseUrl + 'sesmember/index/add-friend',
    data: {
      format: 'html',
      'user_id': scriptJquery(this).attr('data-src')
    },
    success: function(responseHTML) {
      var result = responseHTML;
      if(typeof result.status == 'undefined')
     	 sestooltipOrigin.tooltipster('content', responseHTML).data('ajax', 'cached');
      else if(result.status == 1)
      	sestooltipOrigin.tooltipster('content', result.message).data('ajax', 'cached');
      else
      alert(result.message);
    }
  }));

});

scriptJquery(document).on('click', '.member_cancelfriend_request', function() {
    var sesthis = this;
    en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',
    url: en4.core.baseUrl + 'sesmember/index/cancel-friend',
    data: {
      format: 'html',
      'user_id': scriptJquery(this).attr('data-src')
    },
    success: function(responseHTML) {
      var result = responseHTML;
      if(typeof result.status == 'undefined') {
				sestooltipOrigin.tooltipster('content', responseHTML).data('ajax', 'cached');
      }
      else if(result.status == 1) {
				sestooltipOrigin.tooltipster('content', result.message).data('ajax', 'cached');
      }
      else
      alert(result.message);
    }
  }));
});

scriptJquery(document).on('click', '.member_removefriend_request', function() {
    var sesthis = this;
    en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',
    url: en4.core.baseUrl + 'sesmember/index/remove-friend',
    data: {
      format: 'html',
      'user_id': scriptJquery(this).attr('data-src')
    },
    success: function(responseHTML) {
      var result = responseHTML;
      if(typeof result.status == 'undefined') {
				sestooltipOrigin.tooltipster('content', responseHTML).data('ajax', 'cached');
      }
      else if(result.status == 1) {
				sestooltipOrigin.tooltipster('content', result.message).data('ajax', 'cached');
      }
      else
      alert(result.message);
    }
  }));
});

scriptJquery(document).on('click', '.member_acceptfriend_request', function() {
    var sesthis = this;
    en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',
    url: en4.core.baseUrl + 'sesmember/index/accept-friend',
    data: {
      format: 'html',
      'user_id': scriptJquery(this).attr('data-src')
    },
    success: function(responseHTML) {
      var result = responseHTML;
      if(typeof result.status == 'undefined') {
				sestooltipOrigin.tooltipster('content', responseHTML).data('ajax', 'cached');
      }
      else if(result.status == 1) {
				sestooltipOrigin.tooltipster('content', result.message).data('ajax', 'cached');
      }
      else
     	 alert(result.message);
    }
  }));
});

function like_data_sesmember(element, functionName, itemType) {
  if (!scriptJquery (element).attr('data-url'))
  return;
  var id = scriptJquery (element).attr('data-url');
  if (scriptJquery (element).hasClass('button_active'))
  scriptJquery (element).removeClass('button_active');
  else
  scriptJquery (element).addClass('button_active');
  (scriptJquery.ajax({
    dataType: 'html',
    method: 'post',
    'url': en4.core.baseUrl + 'sesmember/index/' + functionName,
    'data': {
      format: 'html',
      id: scriptJquery (element).attr('data-url'),
      type: itemType,
    },
    success: function(responseHTML) {
      var response = jQuery.parseJSON(responseHTML);
      if (response.error)
    	  alert(en4.core.language.translate('Something went wrong,please try again later'));
      else {
				var elementCount = '.sesmember_like_user_'+id;
				scriptJquery (elementCount).find('span').html(response.count);
				if (response.condition == 'reduced') {
					scriptJquery (elementCount).removeClass('button_active');
					showTooltipSesbasic('10','10','<i class="fa fa-thumbs-down"></i><span>'+(en4.core.language.translate("Member unliked successfully"))+'</span>','sesbasic_member_likeunlike');
				}
				else {
					scriptJquery (elementCount).addClass('button_active');
					showTooltipSesbasic('10','10','<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Member liked successfully"))+'</span>','sesbasic_liked_notification');
				}
				if(response.user_verified == 1)
					scriptJquery('.sesmember_verified_sign_'+id).show();
				else
					scriptJquery('.sesmember_verified_sign_'+id).hide();
      }
      return true;
    }
  }));
}

function like_review_data_sesmember(element, functionName, itemType) {
  if (!scriptJquery (element).attr('data-url'))
  return;
  var id = scriptJquery (element).attr('data-url');
  if (scriptJquery (element).hasClass('button_active'))
  scriptJquery (element).removeClass('button_active');
  else
  scriptJquery (element).addClass('button_active');
  (scriptJquery.ajax({
    dataType: 'html',
    method: 'post',
    'url': en4.core.baseUrl + 'sesmember/review/' + functionName,
    'data': {
      format: 'html',
      id: scriptJquery (element).attr('data-url'),
      type: itemType,
    },
    success: function(responseHTML) {
      var response = jQuery.parseJSON(responseHTML);
      if (response.error)
      alert(en4.core.language.translate('Something went wrong,please try again later'));
      else {
				var elementCount = '.sesmember_like_user_review_'+id;
				scriptJquery (elementCount).find('span').html(response.count);
				if (response.condition == 'reduced') {
					scriptJquery (elementCount).removeClass('button_active');
					showTooltipSesbasic('10','10','<i class="fa fa-thumbs-down"></i><span>'+(en4.core.language.translate("Review unliked successfully"))+'</span>','sesbasic_member_likeunlike');
				}
				else {
					scriptJquery (elementCount).addClass('button_active');
					showTooltipSesbasic('10','10','<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Review liked successfully"))+'</span>','sesbasic_member_likeunlike');
				}
      }
      return true;
    }
  }));
}

scriptJquery(document).on('click', '.sesmember_button_like_user', function () {
  var element = scriptJquery(this);
  if (!scriptJquery (element).attr('data-url'))
  return;
  var id = scriptJquery (element).attr('data-url');
  if (scriptJquery (element).find('i').hasClass('fa-thumbs-up')){
    scriptJquery (element).find('i').removeClass('fa-thumbs-up').addClass('fa-thumbs-down');
    scriptJquery (element).find('span').html(en4.core.language.translate('Unlike'));
  }
  else{
    scriptJquery (element).find('i').removeClass('fa-thumbs-down').addClass('fa-thumbs-up');
    scriptJquery (element).find('span').html(en4.core.language.translate('Like'));
  }
  (scriptJquery.ajax({
    dataType: 'html',
  method: 'post',
    'url': en4.core.baseUrl + 'sesmember/index/like',
    'data': {
      format: 'html',
      id: scriptJquery (element).attr('data-url'),
      type: itemType,
    },
    success: function(responseHTML) {
      var response = jQuery.parseJSON(responseHTML);
      if (response.error)
      alert(en4.core.language.translate('Something went wrong,please try again later'));
      else {
        var elementCount = '.sesmember_button_like_user_'+id;
        //scriptJquery (elementCount).find('span').html(response.count);
        if (response.condition == 'reduced') {
          scriptJquery (elementCount).find('i').removeClass('fa-thumbs-down').addClass('fa-thumbs-up');
          scriptJquery (elementCount).find('span').html(en4.core.language.translate('Like'));
          showTooltipSesbasic('10','10','<i class="fa fa-thumbs-down"></i><span>'+(en4.core.language.translate("Member unliked successfully"))+'</span>','sesbasic_member_likeunlike');
        }
        else {
          scriptJquery (elementCount).find('span').html(en4.core.language.translate('Unlike'));
          scriptJquery (elementCount).find('i').removeClass('fa-thumbs-up').addClass('fa-thumbs-down');
          showTooltipSesbasic('10','10','<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Member liked successfully"))+'</span>','sesbasic_liked_notification');
        }
        if(response.user_verified == 1)
        scriptJquery('.sesmember_verified_sign_'+id).show();
        else
        scriptJquery('.sesmember_verified_sign_'+id).hide();
      }
      return true;
    }
  }));
});




scriptJquery(document).on('click', '.sesmember_like_user', function () {
  like_data_sesmember(this, 'like', 'user');
});

scriptJquery(document).on('click', '.sesmember_like_user_review', function () {
  like_review_data_sesmember(this, 'like', 'user');
});

function reviewVotes(elem,type){
	scriptJquery(elem).parent().parent().find('p').first().html('<span style="color:green;font-weight:bold">Thanks for your vote!</span>');
	var element = scriptJquery(this);
  if (!scriptJquery(elem).attr('data-href'))
  	return;
	var text = scriptJquery(elem).find('.title').html();
  var id = scriptJquery (elem).attr('data-href');
  (scriptJquery.ajax({
    dataType: 'html',
    method: 'post',
    'url': en4.core.baseUrl + 'sesmember/index/review-votes',
    'data': {
      format: 'html',
      id: id,
      type: type,
    },
    success: function(responseHTML) {
      var response = jQuery.parseJSON(responseHTML);
      if (response.error)
      	alert(en4.core.language.translate('Something went wrong,please try again later'));
      else {
					//scriptJquery (elementCount).find('span').html(response.count);
					if (response.condition == 'reduced') {
						scriptJquery (elem).removeClass('active');
						scriptJquery (elem).find('span').eq(1).html(response.count);
						//showTooltipSesbasic('10','10','<span>'+(en4.core.language.translate(text+" unliked successfully"))+'</span>','sesbasic_unliked_notification')
					}
					else {
						scriptJquery (elem).addClass('active');
						scriptJquery (elem).find('span').eq(1).html(response.count);
						//showTooltipSesbasic('10','10','<span>'+(en4.core.language.translate(text+" liked successfully"))+'</span>','sesbasic_liked_notification')
					}
      }
      return true;
    }
  }));
}

//review votes js
scriptJquery(document).on('click', '.sesmember_review_useful', function (e) {
  reviewVotes(this, '1');
  if(scriptJquery ('.sesmember_review_useful').hasClass('active')){
  scriptJquery ('.sesmember_review_useful').removeClass('no-cursor');
  scriptJquery ('.sesmember_review_cool').removeClass('no-cursor');
  scriptJquery ('.sesmember_review_funny').removeClass('no-cursor');
}else{
  scriptJquery ('.sesmember_review_funny').addClass('no-cursor');
  scriptJquery ('.sesmember_review_cool').addClass('no-cursor');
}
});
scriptJquery(document).on('click', '.sesmember_review_funny', function (e) {
  reviewVotes(this, '2');
  if(scriptJquery ('.sesmember_review_funny').hasClass('active')){
  scriptJquery ('.sesmember_review_useful').removeClass('no-cursor');
  scriptJquery ('.sesmember_review_cool').removeClass('no-cursor');
  scriptJquery ('.sesmember_review_funny').removeClass('no-cursor');
}else{
scriptJquery ('.sesmember_review_useful').addClass('no-cursor');
  scriptJquery ('.sesmember_review_cool').addClass('no-cursor');
}
});
scriptJquery(document).on('click', '.sesmember_review_cool', function (e) {
  reviewVotes(this, '3');
  if(scriptJquery ('.sesmember_review_cool').hasClass('active')){
  scriptJquery ('.sesmember_review_useful').removeClass('no-cursor');
  scriptJquery ('.sesmember_review_cool').removeClass('no-cursor');
  scriptJquery ('.sesmember_review_funny').removeClass('no-cursor');
}else{
  scriptJquery ('.sesmember_review_useful').addClass('no-cursor');
  scriptJquery ('.sesmember_review_funny').addClass('no-cursor');
}
});

var feturedBlockId;
scriptJquery(document).on('click','.fromExistingAlbumPhoto', function(){
  feturedBlockId = '';
  feturedBlockId = scriptJquery(this).attr('id');
  scriptJquery('#sesmember_popup_existing_upload').show();
  showHtml();
  scriptJquery('#sesmember_popup_cam_upload').show();
  scriptJquery('#sesmember_popup_existing_upload').show();
  existingAlbumPhotosGet();
});

scriptJquery(document).on('click','a[id^="sesmember_profile_upload_existing_photos_"]',function(event){
  event.preventDefault();
  var id = scriptJquery(this).attr('id').match(/\d+/)[0];
  if(!id)
  return;
	scriptJquery('#save_featured_photo').css('pointer-events','').css('cursor','');
  var imageSource = scriptJquery(this).find('span').css('background-image').replace('url(','').replace(')','').replace('"','').replace('"','');
  scriptJquery('#sesmember-profile-upload-loading').show();
  if(feturedBlockId == 'featured_image_1') {
    scriptJquery('#featured_photo_1').val(id);
    scriptJquery('#'+feturedBlockId).html('<img src='+ imageSource+ '>');
    scriptJquery('#hide_cancel_1').html('<a href="javascript:void(0);" class="" onclick="javascript:removeBlock(\'block_1\', \'1\');"></a>');

  }
  else if(feturedBlockId == 'featured_image_2') {
    if(scriptJquery('#featured_photo_1').val() == '') {
      scriptJquery('#featured_photo_1').val(id);
      scriptJquery('#featured_image_1').html('<img src='+ imageSource+ '>');
      scriptJquery('#hide_cancel_1').html('<a href="javascript:void(0);" class="" onclick="javascript:removeBlock(\'block_1\', \'1\');"></a>');
    }
    else {
    scriptJquery('#featured_photo_2').val(id);
    scriptJquery('#'+feturedBlockId).html('<img src='+ imageSource+ '>');
    scriptJquery('#hide_cancel_2').html('<a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock(\'block_2\', \'2\');"></a>');
    }
  }
  else if(feturedBlockId == 'featured_image_3') {
    if(scriptJquery('#featured_photo_1').val() == '') {
      scriptJquery('#featured_photo_1').val(id);
      scriptJquery('#featured_image_1').html('<img src='+ imageSource+ '>');
      scriptJquery('#hide_cancel_1').html('<a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock(\'block_1\', \'1\');"></a>');
    }
    else if(scriptJquery('#featured_photo_2').val() == '') {
      scriptJquery('#featured_photo_2').val(id);
      scriptJquery('#featured_image_2').html('<img src='+ imageSource+ '>');
      scriptJquery('#hide_cancel_2').html('<a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock(\'block_2\', \'2\');"></a>');
    }
    else {
    scriptJquery('#featured_photo_3').val(id);
    scriptJquery('#'+feturedBlockId).html('<img src='+ imageSource+ '>');
    scriptJquery('#hide_cancel_3').html('<a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock(\'block_3\', \'3\');"></a>');
    }
  }
  else if(feturedBlockId == 'featured_image_4') {
    if(scriptJquery('#featured_photo_1').val() == '') {
      scriptJquery('#featured_photo_1').val(id);
      scriptJquery('#featured_image_1').html('<img src='+ imageSource+ '>');
      scriptJquery('#hide_cancel_1').html('<a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock(\'block_1\', \'1\');"></a>');
    }
    else if(scriptJquery('#featured_photo_2').val() == '') {
      scriptJquery('#featured_photo_2').val(id);
      scriptJquery('#featured_image_2').html('<img src='+ imageSource+ '>');
      scriptJquery('#hide_cancel_2').html('<a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock(\'block_2\', \'2\');"></a>');
    }
    else if(scriptJquery('#featured_photo_3').val() == '') {
      scriptJquery('#featured_photo_3').val(id);
      scriptJquery('#featured_image_3').html('<img src='+ imageSource+ '>');
      scriptJquery('#hide_cancel_3').html('<a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock(\'block_3\', \'3\');"></a>');
    }
    else {
    scriptJquery('#featured_photo_4').val(id);
    scriptJquery('#'+feturedBlockId).html('<img src='+ imageSource+ '>');
    scriptJquery('#hide_cancel_4').html('<a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock(\'block_4\', \'4\');"></a>');
    }
  }
  else if(feturedBlockId == 'featured_image_5') {
    if(scriptJquery('#featured_photo_1').val() == '') {
      scriptJquery('#featured_photo_1').val(id);
      scriptJquery('#featured_image_1').html('<img src='+ imageSource+ '>');
      scriptJquery('#hide_cancel_1').html('<a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock(\'block_1\', \'1\');"></a>');
    }
    else if(scriptJquery('#featured_photo_2').val() == '') {
      scriptJquery('#featured_photo_2').val(id);
      scriptJquery('#featured_image_2').html('<img src='+ imageSource+ '>');
      scriptJquery('#hide_cancel_2').html('<a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock(\'block_2\', \'2\');"></a>');
    }
    else if(scriptJquery('#featured_photo_3').val() == '') {
      scriptJquery('#featured_photo_3').val(id);
      scriptJquery('#featured_image_3').html('<img src='+ imageSource+ '>');
      scriptJquery('#hide_cancel_3').html('<a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock(\'block_3\', \'3\');"></a>');
    }
    else if(scriptJquery('#featured_photo_4').val() == '') {
      scriptJquery('#featured_photo_4').val(id);
      scriptJquery('#featured_image_4').html('<img src='+ imageSource+ '>');
      scriptJquery('#hide_cancel_4').html('<a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock(\'block_4\', \'4\');"></a>');
    }
    else {
    scriptJquery('#featured_photo_5').val(id);
    scriptJquery('#'+feturedBlockId).html('<img src='+ imageSource+ '>');
    scriptJquery('#hide_cancel_5').html('<a href="javascript:void(0);" class="fa fa-times" onclick="javascript:removeBlock(\'block_5\', \'5\');"></a>');
    }
  }
  hideProfileAlbumPhotoUpload();
});

scriptJquery(document).on('click','#save_featured_photo',function(event){
  event.preventDefault();
  scriptJquery('.sesmember_featured_photos_block_overlay').show();
  var photoId1 = scriptJquery('#featured_photo_1').val();
  var photoId2 = scriptJquery('#featured_photo_2').val();
  var photoId3 = scriptJquery('#featured_photo_3').val();
  var photoId4 = scriptJquery('#featured_photo_4').val();
  var photoId5 = scriptJquery('#featured_photo_5').val();
  sessmoothboxclose();
  var URL = en4.core.staticBaseUrl+'sesmember/index/featured-photos/';
  (scriptJquery.ajax({
    dataType: 'html',
    method: 'post',
    'url': URL ,
    'data': {
      format: 'html',
      photoId1: photoId1,
      photoId2: photoId2,
      photoId3: photoId3,
      photoId4: photoId4,
      photoId5: photoId5,
    },
    success: function(responseHTML) {
      scriptJquery('.layout_sesmember_member_featured_photos').html(responseHTML);
      scriptJquery('.sesmember_featured_photos_block_overlay').hide();
    }
  }));
});

scriptJquery(document).on('click','a[id^="sesmember_existing_album_see_more_"]',function(event){
  event.preventDefault();
  var thatObject = this;
  scriptJquery(thatObject).parent().hide();
  var id = scriptJquery(this).attr('id').match(/\d+/)[0];
  var pageNum = parseInt(scriptJquery(this).attr('data-src'),10);
  scriptJquery('#sesmember_existing_album_see_more_loading_'+id).show();
  if(pageNum == 0){
    scriptJquery('#sesmember_existing_album_see_more_page_'+id).remove();
    return;
  }
  var URL = en4.core.staticBaseUrl+'sesmember/index/existing-album-photos/';
  (scriptJquery.ajax({
    dataType: 'html',
    method: 'post',
    'url': URL ,
    'data': {
      format: 'html',
      page: pageNum+1,
      id: id,
    },
    success: function(responseHTML) {
      scriptJquery('#sesmember_photo_content_'+id).append(responseHTML);

      var dataSrc = scriptJquery('#sesmember_existing_album_see_more_page_'+id).html();
      scriptJquery('#sesmember_existing_album_see_more_'+id).attr('data-src',dataSrc);
      scriptJquery('#sesmember_existing_album_see_more_page_'+id).remove();
      if(dataSrc == 0)
      scriptJquery('#sesmember_existing_album_see_more_'+id).parent().remove();
      else
      scriptJquery(thatObject).parent().show();
      scriptJquery('#sesmember_existing_album_see_more_loading_'+id).hide();
    }
  }));
});

scriptJquery(document).ready(function(){
  if(typeof sesmemeberLocation != "undefined" && sesmemeberLocation == 1 && enableLocation == 1) {
    var locationElement = scriptJquery('#timezone-wrapper');
    var tabIndex = scriptJquery('#timezone').attr('tabindex');
    var locationTabIndex = tabIndex-1;
    for(i=0;i<locationElement.length;i++) {
      var html = '<div id="ses_location-wrapper" class="form-wrapper"><div id="ses_location-label" class="form-label"><label for="ses_location" class="optional">'+en4.core.language.translate("Location")+'</label></div><div id="ses_location-element" class="form-element"><input tabIndex="'+locationTabIndex+'" name="ses_location" id="ses_location_'+i+'" value="" placeholder="Enter a location" autocomplete="off" type="text"></div></div><input name="ses_lat" value="" id="ses_lat" type="hidden"><input name="ses_lng" value="" id="ses_lng" type="hidden"><input name="ses_zip" value="" id="ses_zip" type="hidden"><input name="ses_city" value="" id="ses_city" type="hidden"><input name="ses_state" value="" id="ses_state" type="hidden"><input name="ses_country" value="" id="ses_country" type="hidden">';
        if(scriptJquery(locationElement[i]).closest('form').attr('id') == 'signup_account_form') {
            scriptJquery(html).insertBefore(locationElement[i]);
            sesMemberLocation(i);
        }
    }
  }
})
scriptJquery(document).ready(function(){
  if(scriptJquery ('.sesmember_review_cool').hasClass('active')){
  scriptJquery ('.sesmember_review_useful').addClass('no-cursor');
  scriptJquery ('.sesmember_review_funny').addClass('no-cursor');
}else if(scriptJquery ('.sesmember_review_useful').hasClass('active')){
  scriptJquery ('.sesmember_review_cool').addClass('no-cursor');
  scriptJquery ('.sesmember_review_funny').addClass('no-cursor');
}else if(scriptJquery ('.sesmember_review_funny').hasClass('active')){
  scriptJquery ('.sesmember_review_cool').addClass('no-cursor');
  scriptJquery ('.sesmember_review_useful').addClass('no-cursor');
}
})
