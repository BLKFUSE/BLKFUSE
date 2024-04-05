/* $Id: sesalbumimageviewerbasic.js  2015-6-16 00:00:000 SocialEngineSolutions $ */
var dataCommentSes = '';
	// store the default browser URL for change state after closing image viewer
	var defaultHashURL = '';
	defaultHashURL = document.URL;
	var firstStartPoint = 0;
	firstStartPointModule = 0;
	var getTagData;
	var mediaTags ;
  
  var sesCustomPhotoURL = false;
  
var offsetY = window.pageYOffset;
function makeLayoutForImageViewer(){
	if(scriptJquery('#ses_media_lightbox_container').length)
		return;
	scriptJquery('<div id="ses_media_lightbox_container" class="ses_media_lightbox_container"><div class="ses_media_lightbox_overlay" id="crossSes"></div> <div class="ses_media_lightbox_content"> <div class="ses_media_lightbox_left"><div class="ses_media_lightbox_item_wrapper"><div class="ses_media_lightbox_item"><img id="gallery-img" src="" alt="" /></div></div> <div class="ses_media_lightbox_nav_btns"><a id="nav-btn-next" style="display:none" class="ses_media_lightbox_nav_btn_next" ></a><a id="nav-btn-prev" class="ses_media_lightbox_nav_btn_prev" style="display:none;" ></a></div> </div><div class="ses_media_lightbox_information"></div><a href="javascript:;" id="fsbutton"  class="cross ses_media_lightbox_close_btn"><i></i></a></div></div>').appendTo('body');	
}
function getRequestedAlbumPhotoForImageViewer(imageURL,requestedURL,manageData){
	if(openPhotoInLightBoxSesalbum == 0){
		window.location.href = requestedURL.replace('image-viewer-detail','view');
		return;
	}
	makeLayoutForImageViewer();
  if(firstStartPoint == 0){
    offsetY = window.pageYOffset;
    scriptJquery('html').css('position','fixed').css('width','100%').css('overflow-y','hidden');
		scriptJquery('html').css('top', -offsetY + 'px');
  }
	scriptJquery('#ses_media_lightbox_container').show();
	// scriptJquery('body').css({ 'overflow': 'hidden' });
 //check function call from image viewer or direct
 if(!dataCommentSes){
		dataCommentSes = scriptJquery('.layout_core_comments').html();
		getTagData = scriptJquery('#media_photo_div').find('*[id^="tag_"]');
		scriptJquery('#media_photo_div').find('*[id^="tag_"]').remove();
		mediaTags =	scriptJquery('#media_tags').html();
		scriptJquery('#media_tags').html('');
	}
	scriptJquery('.layout_core_comments').html('');
	history.pushState(null, null, requestedURL.replace('image-viewer-detail','view'));
	
	var height = scriptJquery('.ses_media_lightbox_content').height();
	var width = scriptJquery('.ses_media_lightbox_left').width();
	scriptJquery('#media_photo_next_ses').css('height',height+'px');
	//scriptJquery('#mainImageContainer').css('height',height+'px');
	scriptJquery('#gallery-img').css('max-height',height+'px');
	scriptJquery('#gallery-img').css('max-width',width+'px');
	//scriptJquery('#heightOfImageViewerContent').css('height', scriptJquery('.ses_media_lightbox_content').height()+'px');
	scriptJquery('#gallery-img').attr('src',imageURL);
	scriptJquery('.ses_media_lightbox_information').html('');
	scriptJquery('.ses_media_lightbox_options').remove();
	scriptJquery('#nav-btn-prev').hide();
	scriptJquery('.ses_media_lightbox_nav_btn_next').css('display','none');
	getImageViewerObjectData(imageURL,requestedURL,manageData);	
}
if(typeof sesalbuminstall != 'undefined' && sesalbuminstall == 1) {
// other module open in popup viewer code
scriptJquery(document).on("click", ".thumbs_photo", function (e) {
	var requestedURL = scriptJquery(this).attr('href');
	if(typeof scriptJquery(this).attr('onclick') != 'undefined')
		return;
	// check for view module pages images
	if ((requestedURL.indexOf("event_id") === -1 && requestedURL.indexOf("group_id") === -1 ) || requestedURL.indexOf("photo_id") === -1 ){
			return true;
	}
	if(openPhotoInLightBoxSesalbum == 0 || (openGroupPhotoInLightBoxSesalbum == 0 && requestedURL.indexOf("group_id") > -1 ) || (openEventPhotoInLightBoxSesalbum == 0 && requestedURL.indexOf("event_id") > -1)){
		window.location.href = requestedURL;
		return;
	}
		e.preventDefault();
	
		if(requestedURL){
			scriptJquery('#ses_media_lightbox_container').show();
			scriptJquery('body').css({ 'overflow': 'hidden' });
			history.pushState(null, null, hashTagURL);
			openLightBoxForSesPlugins(requestedURL);
		}
});

//message photo popup
scriptJquery(document).on('click','.message_attachment_info',function(e){
		e.preventDefault();
		feedPhoto = true;
		var imageObject = scriptJquery(this).find('div').find('a');
		var getImageHref = imageObject.attr('href');
		if(getImageHref.search('album_id') == -1 || getImageHref.search('photo_id') == -1){
			window.location.href = getImageHref;
			return;
		}
		var imageSource = scriptJquery(this).parent().find('.message_attachment_photo').find('img').attr('src');
		if(!imageSource){
			window.location.href = getImageHref;
			return;
		}
		if(openPhotoInLightBoxSesalbum == 0 ){
			window.location.href = getImageHref;
			return;
		}
		getImageHref = getImageHref.replace('view','image-viewer-detail');
		getRequestedAlbumPhotoForImageViewer(imageSource,getImageHref);
});
// activity feed image popup
scriptJquery(document).on("click", '.feed_attachment_album_photo', function (e) {
	e.preventDefault();
  if(scriptJquery(this).find('div').hasClass('sesadvancedactivity_buysell'))
    return;
	var imageObject = scriptJquery(this).find('div').find('a');
  var getImageHref = imageObject.attr('href');
	var imageSource = imageObject.find('img').attr('src');
	getImageHref = getImageHref.replace('view','image-viewer-detail');
  if(openPhotoInLightBoxSesalbum == 0 || getImageHref.indexOf('photo_id') < 0){
		window.location.href = getImageHref;
	}
	if(typeof imageSource == 'undefined')
		imageSource = en4.core.baseUrl+'application/modules/Sesbasic/externals/images/loading.gif';
	getRequestedAlbumPhotoForImageViewer(imageSource,getImageHref);
});
}
//Close image viewer
scriptJquery(document).on('click','.ses_media_lightbox_overlay, #crossSes, .cross',function (e) {
	if(scriptJquery('#ses_media_lightbox_container').css('display') != 'none'){
		// scriptJquery('body').css({ 'overflow': 'initial' });
    scriptJquery('html').css('position','initial').css('overflow','initial');
    scriptJquery(window).scrollTop(offsetY);
    if(scriptJquery('.emoji_content').css('display') == 'block')
      scriptJquery('.exit_emoji_btn').click();
		history.pushState(null, null, defaultHashURL);
		scriptJquery('.layout_core_comments').html(dataCommentSes);
		clearInterval(slideShowInterval);
		e.preventDefault();
		firstStartPoint = 0;
		sesLightbox = false;
		sesaIndex=0;
		dataCommentSes = '';
		firstStartPointModule = 0;
		scriptJquery('#media_photo_next').after(getTagData);
		scriptJquery('#media_tags').html(mediaTags);		
		mediaTags = '';
		getTagData = '';
    sesCustomPhotoURL = false;

		// scriptJquery('html').css('position','inherit').css('overflow-y','auto');
		scriptJquery(window).scrollTop(offsetY);
	
		scriptJquery('#ses_media_lightbox_container').remove();
	}
});
//http://johndyer.name/native-fullscreen-javascript-api-plus-jquery-plugin/
var is_fullscreen = 0;
(function() {
	var 
		SESfullScreenApi = { 
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
		SESfullScreenApi.supportsFullScreen = true;
	} else {	 
		// check for fullscreen support by vendor prefix
		for (var i = 0, il = browserPrefixes.length; i < il; i++ ) {
			SESfullScreenApi.prefix = browserPrefixes[i];
			if (typeof document[SESfullScreenApi.prefix + 'CancelFullScreen' ] != 'undefined' ) {
				SESfullScreenApi.supportsFullScreen = true;
				break;
			}
		}
	}
	
	// update methods to do something useful
	if (SESfullScreenApi.supportsFullScreen) {
		SESfullScreenApi.fullScreenEventName = SESfullScreenApi.prefix + 'fullscreenchange';
		SESfullScreenApi.isFullScreen = function() {
			switch (this.prefix) {	
				case '':
					return document.fullScreen;
				case 'webkit':
					return document.webkitIsFullScreen;
				default:
					return document[this.prefix + 'FullScreen'];
			}
		}
		SESfullScreenApi.requestFullScreen = function(el) {
			return (this.prefix === '') ? el.requestFullScreen() : el[this.prefix + 'RequestFullScreen']();
		}
		SESfullScreenApi.cancelFullScreen = function(el) {
			return (this.prefix === '') ? document.cancelFullScreen() : document[this.prefix + 'CancelFullScreen']();
		}		
	}

	// jQuery plugin
	if (typeof jQuery != 'undefined') {
		jQuery.fn.requestFullScreen = function() {
			return this.each(function() {
				var el = jQuery(this);
				if (SESfullScreenApi.supportsFullScreen) {
					SESfullScreenApi.requestFullScreen(el);
				}
			});
		};
	}
	// export api
	window.SESfullScreenApi = SESfullScreenApi;	
})();

// do something interesting with fullscreen support
var fsButton = document.getElementById('fsbutton');
function toogle(){
if(is_fullscreen == 0)
	window.SESfullScreenApi.requestFullScreen(document.body);	
else
	window.SESfullScreenApi.cancelFullScreen(document.body);
}
if (window.SESfullScreenApi.supportsFullScreen) {	
	document.addEventListener(SESfullScreenApi.fullScreenEventName, function() {
		if (SESfullScreenApi.isFullScreen()) {
			is_fullscreen = 1;
			scriptJquery('.sesalbum_toogle_screen').css('backgroundPosition','-44px 0');
			scriptJquery('#ses_media_lightbox_container').addClass('fullscreen');
			var height = scriptJquery('.ses_media_lightbox_content').height();
			var width = scriptJquery('.ses_media_lightbox_left').width();
			scriptJquery('#media_photo_next_ses').css('height',height+'px');
			scriptJquery('#gallery-img').css('max-height',height+'px');
			scriptJquery('#gallery-img').css('max-width',width+'px');
			scriptJquery('#heightOfImageViewerContent').css('height', scriptJquery('.ses_media_lightbox_content').height()+'px');
			scriptJquery('#flexcroll').slimscroll({
			 height: 'auto',
			 start : scriptJquery('#ses_media_lightbox_media_info'),
			});
			scriptJquery('#gallery-img').attr('src',scriptJquery('#image-src-sesalbum-lightbox-hidden').html());
			window.wheelzoom(document.querySelector('#gallery-img'));
		} else {
			is_fullscreen = 0;
			clearInterval(slideShowInterval);
			scriptJquery('.sesalbum_toogle_screen').css('backgroundPosition','0 0');
			scriptJquery('#ses_media_lightbox_container').removeClass('fullscreen');
			var height = scriptJquery('.ses_media_lightbox_content').height();
			var width = scriptJquery('.ses_media_lightbox_left').width();
			scriptJquery('#media_photo_next_ses').css('height',height+'px');
			scriptJquery('#gallery-img').css('max-height',height+'px');
			scriptJquery('#gallery-img').css('max-width',width+'px');
			scriptJquery('#heightOfImageViewerContent').css('height', scriptJquery('.ses_media_lightbox_content').height()+'px');
			scriptJquery('#flexcroll').slimscroll({
			 height: 'auto',
			 start : scriptJquery('#ses_media_lightbox_media_info'),
			});
			scriptJquery('#gallery-img').attr('src',scriptJquery('#image-src-sesalbum-lightbox-hidden').html());
			window.wheelzoom(document.querySelector('#gallery-img'));
		}
	}, true);
} else {
	scriptJquery('#fsbutton').hide();
}

//Key Events
scriptJquery(document).on('keyup', function (e) {
		
		if(!scriptJquery('#ses_media_lightbox_container').length){
			return false;
		}
		e.preventDefault();
		//Close popup on esc
		if (e.keyCode === 27) { document.getElementById('crossSes').click();return false; }
		//Next Img On Right Arrow Click
		if (e.keyCode === 39) { 
			if(scriptJquery('#'+e.target.id).prop('tagName') == 'INPUT' || scriptJquery('#'+e.target.id).prop('tagName') == 'TEXTAREA')
				return;
			NextImageViewerPhoto();return false; 
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
			scriptJquery('#sesalbum_favourite').trigger('click');
		}
		//Prev Img on Left Arrow Click
		if (e.keyCode === 37) { 
			if(scriptJquery('#'+e.target.id).prop('tagName') == 'INPUT' || scriptJquery('#'+e.target.id).prop('tagName') == 'TEXTAREA')
				return;
			PrevImageViewerPhoto(); return false;
		}
});
function checkThirdPartyModules(imageURL){
	if(imageURL){
		if (imageURL.indexOf("event_id") === -1 && imageURL.indexOf("group_id") === -1){
			return true;
		}else{
			return false;
		}
	}
		return false;
}
function NextImageViewerPhoto(){
	if(scriptJquery('#ses_media_lightbox_container').css('display') == 'none'){
			return false;;	
	}
	if(scriptJquery('#nav-btn-next').length){
			document.getElementById('nav-btn-next').click();
	}
	return false;
}
function PrevImageViewerPhoto(){
	if(scriptJquery('#ses_media_lightbox_container').css('display') == 'none'){
			return false;;	
	}
	if(scriptJquery('#nav-btn-prev').length){
		document.getElementById('nav-btn-prev').click();
	}
	return false;
}
function ajax_download(url) {
    var $iframe,
        iframe_doc,
        iframe_html;
    if (($iframe = scriptJquery('#download_iframe')).length === 0) {
        $iframe = scriptJquery("<iframe id='download_iframe'" +
                    " style='display: none' src='about:blank'></iframe>"
                   ).appendTo("body");
    }
    iframe_doc = $iframe[0].contentWindow || $iframe[0].contentDocument;
    if (iframe_doc.document) {
        iframe_doc = iframe_doc.document;
    }
    iframe_html = "<html><head></head><body><form method='POST' action='" +
                  url +"'>"
        iframe_html +="</form></body></html>";
    iframe_doc.open();
    iframe_doc.write(iframe_html);
    scriptJquery(iframe_doc).find('form').submit();
}
scriptJquery(document).on("click", ".ses-album-photo-download", function (e) {
	e.preventDefault();
  ajax_download(scriptJquery(this).prop('href'));
});
scriptJquery(document).on('click','.ses-image-viewer',function(e){
		scriptJquery('.layout_core_comments').html('');	
		e.preventDefault();
		return false;
});
function getImageViewerObjectData(imageURL,requestedURL,manageData){
		 if(typeof manageData != 'undefined'){
				var url = requestedURL;
        if(sesCustomPhotoURL) {
          requestedURL = en4.core.baseUrl+'sesbasic/lightbox/image-viewer-detail/';
        } else { 
          requestedURL = en4.core.baseUrl+'albums/photo/ses-compatibility-code/';
        }
		 }else
				var url = '';
		 imageViewerGetRequest = scriptJquery.ajax({
      dataType: 'html',
      url :requestedURL,
      data : {
        format : 'html',
				url:url,
      },
      success : function(responseHTML)
      {
					scriptJquery('.ses_media_lightbox_content').html('');
      		scriptJquery('.ses_media_lightbox_content').html(responseHTML);
					var height = scriptJquery('.ses_media_lightbox_content').height();
					var width = scriptJquery('.ses_media_lightbox_left').width();
					scriptJquery('#media_photo_next_ses').css('height',height+'px');
					//scriptJquery('#mainImageContainer').css('height',height+'px');
					if(scriptJquery('#media_photo_next_ses').find('#gallery-img').hasClass('ses-private-image')){
							scriptJquery('.ses_media_lightbox_options_btns').hide();
							scriptJquery('.ses_media_lightbox_tag_btn').hide();
							scriptJquery('.ses_media_lightbox_share_btn').hide();
							scriptJquery('.ses_media_lightbox_more_btn').hide();
					}else	if(scriptJquery('#media_photo_next_ses').find('#gallery-img').hasClass('ses-blocked-album')){
							scriptJquery('.ses_media_lightbox_information').hide();
							sespromptPasswordCheck();
					}else{
							scriptJquery('.ses_media_lightbox_options_btns').show();
							scriptJquery('.ses_media_lightbox_tag_btn').show();
							scriptJquery('.ses_media_lightbox_share_btn').show();
							scriptJquery('.ses_media_lightbox_more_btn').show();
					}
					scriptJquery('#gallery-img').css('max-height',height+'px');
					scriptJquery('#gallery-img').css('max-width',width+'px');
					scriptJquery('#heightOfImageViewerContent').css('height', scriptJquery('.ses_media_lightbox_content').height()+'px');
					scriptJquery('#flexcroll').slimscroll({
					 height: 'auto',
					 start : scriptJquery('#ses_media_lightbox_media_info'),
					});
					window.wheelzoom(document.querySelector('#gallery-img'));
					//initImage();
					/*if(scriptJquery('#map-canvas').length>0 && !map)
						initializeSesAlbumMap();*/
					return true;
      }
			}); 
// 			en4.core.request.send(imageViewerGetRequest, {
// 				'force': true
// 			});
}
var slideShowInterval;
var speed = 6000;
function slideShow(){
	if(!scriptJquery('#ses_media_lightbox_container').hasClass('fullscreen'))
		toogle();
		slideShowInterval = setInterval(function(){changePositionPhoto();}, speed);
}
function changePositionPhoto() {
	if(scriptJquery('#nav-btn-next').length){
		document.getElementById('nav-btn-next').click();
	}else{
		toogle();
		clearInterval(slideShowInterval);
	}
}

scriptJquery(document).on('click','#editDetailsLink',function(e){
		e.preventDefault();
		scriptJquery('#titleSes').val(scriptJquery('#ses_title_get').html());
		scriptJquery('#descriptionSes').val(scriptJquery('#ses_title_description').html());
		scriptJquery('#editDetailsForm').css('display','block');
		scriptJquery('#ses_media_lightbox_media_info').css('display','none');
		if(scriptJquery('#locationSes').length >0){
        scriptJquery('#locationSes').val(trim(scriptJquery('#locationVenue').val()));
        mapLoad = false;
        if(scriptJquery('#map-canvas').length)
            scriptJquery('#map-canvas').remove();
        initializeSesAlbumMapList();
    }
});


scriptJquery(document).on('click','#cancelDetailsSes',function(e){
  e.preventDefault();
  scriptJquery('#editDetailsForm').css('display','none');
  scriptJquery('#ses_media_lightbox_media_info').css('display','block');
});

scriptJquery(document).on('click','#saveDetailsSes',function(e){
	e.preventDefault();
	var thisObject = this;
	scriptJquery(thisObject).prop("disabled",true);
  var photo_type_ses = scriptJquery('#photo_type_ses').val();
	var photo_id = scriptJquery('#photo_id_ses').val();
	var album_id = scriptJquery('#album_id_ses').val();
	var formData =  scriptJquery("#changePhotoDetails").serializeArray();
  if(sesCustomPhotoURL) {
    var URL = en4.core.baseUrl+'sesbasic/lightbox/edit-detail/album_id/'+album_id+'/item_id/'+photo_id+'/item_type/'+photo_type_ses;
  } else {
    var URL = en4.core.baseUrl+'albums/photo/edit-detail/album_id/'+album_id+'/photo_id/'+photo_id;
  }
  
	scriptJquery.ajax({  
    type: "POST",
    url: URL,
    data: formData,  
    success: function(response) {  
      var data = JSON.parse(response);
			if(data.status && !data.error){
				scriptJquery(thisObject).prop("disabled",false);
				if(scriptJquery('#titleSes').val()){
				var truncate_title = jQuery.trim(scriptJquery('#titleSes').val()).substring(0,scriptJquery('#title_value_get').val())+'...';
				}else{ var truncate_title = "";}
				scriptJquery('#ses_title_get').html(truncate_title);
				scriptJquery('#ses_title_description').html(scriptJquery('#descriptionSes').val());
				scriptJquery('#ses_location_data').html(scriptJquery('#locationSes').val());
				scriptJquery('#editDetailsForm').css('display','none')
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

// scriptJquery(document).on('click','#changeSesPhotoDetails',function(e){
// 	e.preventDefault();
// 	var thisObject = this;
// 	scriptJquery(thisObject).prop("disabled",true);
// 	var formData =  scriptJquery("#changePhotoDetails").serializeArray();
// 	scriptJquery.ajax({  
//     type: "POST",  
//     url: en4.core.baseUrl+'sesalbum/photo/change-sesdetail/',  
//     data: formData,  
//     success: function(response) {  
//       var data = JSON.parse(response);
// 			if(data.status && !data.error){
// 				scriptJquery(thisObject).prop("disabled",false);
// 				scriptJquery('#ses_title_get').html(scriptJquery('#titleSes').val());
// 				scriptJquery('#ses_title_description').html(scriptJquery('#descriptionSes').val());
// 				scriptJquery('#editDetailsForm').css('display','none')
// 				scriptJquery('#ses_media_lightbox_media_info').css('display','block');
// 				return false;
// 			}else{
// 				alert(en4.core.language.translate('Something went wrong,try again later.'));	
// 				return false;
// 			}
//     }
// });
// 	return false;
// });

scriptJquery(document).on('click','#comments .comments_options a',function(event) {
	var thisObject = this;
	var htmlOnclick = scriptJquery(this).attr('onclick');
	if(typeof htmlOnclick != 'undefined' && htmlOnclick.search('comments') != -1 && scriptJquery('.sesalbum_othermodule_like_button').length){
			if(scriptJquery('.sesalbum_othermodule_like_button').hasClass('button_active')){
				scriptJquery('.sesalbum_othermodule_like_button').removeClass('button_active');
				showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate("Photo Unliked Successfully")+'</span>');
				return;
			}else{
				scriptJquery('.sesalbum_othermodule_like_button').addClass('button_active');
				showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate("Photo Liked Successfully")+'</span>', 'sesbasic_liked_notification');
				return ;
			}	
	}
		var currentURL = window.location.href;
	if(currentURL.search('video_id') != -1)
			var itemType = 'Chanel';
	else if(currentURL.search('chanel_id') != -1)
			var itemType = 'Chanel Photo';
	else if(htmlOnclick.search('album') == -1)
		return true;
	
	if(htmlOnclick.search('comments') != -1){
		// unlike code
		if(currentURL.search('album_id') != -1)
			var itemType = 'Photo';
		else if(currentURL.search('video_id') != -1)
			var itemType = 'Chanel';
		else if(currentURL.search('chanel_id') != -1)
			var itemType = 'Chanel Photo';
		else
			var itemType = 'Album';
		if(htmlOnclick.search('unlike') != -1){
		 if(scriptJquery('#ses_media_lightbox_container').css('display') == 'block'){
		 	scriptJquery('#sesLightboxLikeUnlikeButton').removeClass('button_active');
			scriptJquery('#sesLightboxLikeUnlikeButton').find('#like_unlike_count').html(parseInt(scriptJquery('#sesLightboxLikeUnlikeButton').find('#like_unlike_count').html())-1);
		 }else{
		 	scriptJquery('#sesLikeUnlikeButton').removeClass('button_active');
		 }
		 showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate(itemType+" Unliked Successfully")+'</span>');
		}else{
			if(scriptJquery('#ses_media_lightbox_container').css('display') == 'block'){
		 		scriptJquery('#sesLightboxLikeUnlikeButton').addClass('button_active');
				scriptJquery('#sesLightboxLikeUnlikeButton').find('#like_unlike_count').html(parseInt(scriptJquery('#sesLightboxLikeUnlikeButton').find('#like_unlike_count').html())+1);
			}else{
				scriptJquery('#sesLikeUnlikeButton').addClass('button_active');
			}
			showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate(itemType+" Liked Successfully")+'</span>', 'sesbasic_liked_notification');
		}
	}
});

//compatability code for SES
scriptJquery(document).ready(function(){
  if(typeof sesshowShowInfomation != "undefined"){
    //override lightbox function
    scriptJquery('<script type="application/javascript">function openSeaocoreLightBox(href){if(href.search("/albums/photo/view/album_id/") != -1) return;openLightBoxForSesPlugins(href);return false;}</script>').appendTo("body");	
  }
});

sesLightbox = false;
function openLightBoxForSesPlugins(href,imageURL){
	var manageData  = 'yes';
	if(typeof imageURL == 'undefined'){
		var imageURL = en4.core.baseUrl+'application/modules/Sesbasic/externals/images/loading.gif';
	}
  sesCustomPhotoURL = true;
	getRequestedAlbumPhotoForImageViewer(imageURL,href,manageData);
}
