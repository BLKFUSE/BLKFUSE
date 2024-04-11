
/* $Id: sesalbumimagevieweradvance.js  2015-6-16 00:00:000 SocialEngineSolutions $ */
	var dataCommentSes = '';
	// store the default browser URL for change state after closing image viewer
	var defaultHashURL = '';
	var requestPhotoSesalbumURL;
	defaultHashURL = document.URL;
	var firstStartPoint = canPaginateAllPhoto = 0;
	firstStartPointModule = 0;
	var height;
	var width;
	var getTagData;
	var mediaTags ;

  var sesCustomPhotoURL = false;

	function makeLayoutForImageViewer(){
		if(scriptJquery('#ses_media_lightbox_container').length)
			return;
		scriptJquery('<div id="ses_media_lightbox_container" style="display:block" class="pswp" tabindex="-1" role="dialog" aria-hidden="true"><div class="pswp__bg" id="overlayViewer"></div><div class="pswp__scroll-wrap" id="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div> <div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter" style="display:none"><!-- pagging --></div><a class="pswp__button pswp__button--close" title="Close (Esc)"></a><a class="pswp__button pswp__button--share" title="Share"></a><a class="pswp__button sesalbum_toogle_screen"  href="javascript:;" onclick="toogle()" title="Toggle Fullscreen"></a><a class="pswp__button pswp__button--info pswp__button--info-show" style="display:none" id="pswp__button--info-show" title="Show Info"></a><a class="pswp__button pswp__button--zoom" id="pswp__button--zoom" title="Zoom in/out"></a><div class="pswp__top-bar-action"><div class="pswp__top-bar-albumname" style="display:none">In <a href="javascript:;">Album Name</a></div><div class="pswp__top-bar-tag" style="display:none"><a href="javascript:;">Add Tag</a></div><div class="pswp__top-bar-share" style="display:none"><a href="javascript:;">Share</a></div><div class="pswp__top-bar-like" style="display:none"><a href="javascript:;">Like</a></div><div class="pswp__top-bar-more" style="display:none"><a href="javascript:;">Options<i class="fa fa-angle-down"></i></a><div class="pswp__top-bar-more-tooltip" style="display:none"><a href="javascript:;">Download</a><a href="javascript:;">Make Profile Picture</a></div></div></div><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="overlay-model-class pswp__share-modal--fade-in" style="display:none"></div><div class="pswp__loading-indicator"><div class="pswp__loading-indicator__line"></div></div><div id="nextprevbttn"><a class="pswp__button pswp__button--arrow--left"  id="closeViewer" title="Previous (arrow left)"></a><a class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></a></div><div class="pswp__caption"><div class="pswp__caption__center"></div></div></div><div class="ses_media_lightbox_slideshow_options sesbasic_bxs"><a href="javascript:;" id="sesalbum_slideshow_playpause"><i class="fa fa-play"></i><span>Play</span></a><a href="javascript:;" id="sesalbum_slideshow_stop"><i class="fa fa-stop"></i></a></div></div><div id="all_photo_container" style="display:none"></div><div id="last-element-content" style="display:none;"></div></div>').appendTo('body');
	}
	scriptJquery(document).on('click','.seslightbox_image_open',function(e){
			var image = scriptJquery(this).find('img').attr('src');
			if(image)
				openDirectImageLightbox(image);
	});
  var offsetY = window.pageYOffset;
	function openDirectImageLightbox(imageURL){
		makeLayoutForImageViewer();
		var img = document.createElement('img');
		img.onload = function(){
		 width = this.width;
		 height = this.height;
		 openPhotoSwipe(imageURL,width,height);
		//check function call from image viewer or direct
		scriptJquery('.pswp__top-bar-action').css('display','none');
		scriptJquery('.pswp__button pswp__button--info').css('display','none');
		scriptJquery('#nav-btn-next').css('display','none');
		scriptJquery('#nav-btn-prev').css('display','none');
		}
		img.src = imageURL;
	}
  function getRequestedAlbumPhotoForImageViewer(imageURL,requestedURL,forceAllPhoto,moduleData,sesModuleData){
	makeLayoutForImageViewer();
  if(firstStartPoint == 0){
    offsetY = window.pageYOffset;
		scriptJquery('html').css('position','fixed').css('width','100%').css('overflow-y','hidden');
		scriptJquery('html').css('top', -offsetY + 'px');
  }
	if(imageURL.search('direct') != -1){
		var album_id ;
		if(requestedURL.indexOf("album_id") > -1 ){
			var explodeForAlbumId = requestedURL.split('album_id');
			if(explodeForAlbumId[1]){
				// for extra precaution
				var explodeForIdAlbum =explodeForAlbumId[1].split('/');
				if(explodeForIdAlbum[1])
					 album_id = explodeForIdAlbum[1];
				else
					 album_id = explodeForIdAlbum[0];
			}
		}
		window.location.href = en4.core.baseUrl+'albums/view/'+album_id;
		return;
	}
	if(openPhotoInLightBoxSesalbum == 0){
		window.location.href = requestedURL.replace('image-viewer-detail','view');
		return;
	}
	scriptJquery('#ses_media_lightbox_container').css('display','block');
	var img = document.createElement('img');
	img.onload = function(){
	//scriptJquery('#ses_media_lightbox_container').show();
	 width = this.width;
	 height = this.height;
	 openPhotoSwipe(imageURL,width,height);
  //check function call from image viewer or direct
	if(!dataCommentSes){
		dataCommentSes = scriptJquery('.layout_core_comments').html();
		scriptJquery('.layout_core_comments').html('');
		getTagData = scriptJquery('#media_photo_div').find('*[id^="tag_"]');
		scriptJquery('#media_photo_div').find('*[id^="tag_"]').remove();
		mediaTags =	scriptJquery('#media_tags').html();
		scriptJquery('#media_tags').html('');
	}
	scriptJquery('.pswp__preloader').addClass('pswp__preloader--active');
	scriptJquery('.pswp__top-bar-action').css('display','none');
	scriptJquery('#nav-btn-next').css('display','none');
	scriptJquery('#nav-btn-prev').css('display','none');
	
	if(scriptJquery('#ses_media_lightbox_container').hasClass('pswp--zoomed-in')) {
		scriptJquery('#ses_media_lightbox_container').removeClass('pswp--zoomed-in')
	}
	
	var urlChangeState = requestedURL.replace('image-viewer-detail','view');
	urlChangeState = urlChangeState.replace('third-party-imageview-integration','view');
	history.pushState(null, null, urlChangeState);
	if(firstStartPoint == 0 && feedPhoto){
		//scriptJquery('#gallery-img').css('display','none');
	}else{
		scriptJquery('#gallery-img').attr('src',imageURL);
	}
	if(typeof forceAllPhoto != 'undefined'){
		scriptJquery('#content_last_element_lightbox').removeClass('active');
		sesaIndex = 0;
	}
  getImageViewerObjectData(imageURL,requestedURL,forceAllPhoto,firstStartPoint,moduleData,sesModuleData);
	}
	img.src = imageURL;
}
function getRequestedAlbumPhotoForImageViewerParty(imageURL,requestedURL,data1,data2,data3,sesModuleData){

	if(openPhotoInLightBoxSesalbum == 0 || (openGroupPhotoInLightBoxSesalbum == 0 && requestedURL.indexOf("group_id") > -1 ) || (openEventPhotoInLightBoxSesalbum == 0 && requestedURL.indexOf("event_id") > -1) && typeof sesModuleData == 'undefined'){
		window.location.href = requestedURL;
		return;
	}
	makeLayoutForImageViewer();
	scriptJquery('#ses_media_lightbox_container').show();
 //check function call from image viewer or direct
 if(!dataCommentSes)
	dataCommentSes = scriptJquery('.layout_core_comments').html();
	scriptJquery('.layout_core_comments').html('');
	scriptJquery('#nav-btn-prev').hide();
	scriptJquery('.ses_media_lightbox_nav_btn_next').css('display','none');
	history.pushState(null, null, requestedURL);
	if(firstStartPointModule == 0){
		scriptJquery('.ses_media_lightbox_item').html('<div class="sesbasic_view_more_loading"><img src="'+en4.core.baseUrl+'application/modules/Sesbasic/externals/images/loading.gif" /></div>')
		firstStartPointModule = 1;
	}else{
		scriptJquery('#gallery-img').attr('src',imageURL);
	}
	scriptJquery('.ses_pswp_information').html('');
	scriptJquery('.ses_media_lightbox_options').remove();
	if(typeof sesModuleData == 'undefined')
		requestedURL = changeImageViewerURL(requestedURL);
	getImageViewerObjectData(imageURL,requestedURL,data1,data2,data3,sesModuleData);
}
var feedPhoto = false;
scriptJquery(document).ready(function(){
if(typeof sesalbuminstall != 'undefined' && sesalbuminstall == 1) {
// other module open in popup viewer code
scriptJquery(document).on("click", ".thumbs_photo", function (e) {
	var requestedURL = scriptJquery(this).attr('href');
	if(typeof scriptJquery(this).attr('onclick') != 'undefined')
		return;
	// check for view module pages images
	if ((requestedURL.indexOf("event_id") === -1 && requestedURL.indexOf("group_id") === -1) || requestedURL.indexOf("photo_id") === -1 ){
			return true;
	}
	if(openPhotoInLightBoxSesalbum == 0 || (openGroupPhotoInLightBoxSesalbum == 0 && requestedURL.indexOf("group_id") > -1 ) || (openEventPhotoInLightBoxSesalbum == 0 && requestedURL.indexOf("event_id") > -1)){
		window.location.href = requestedURL;
		return;
	}
		e.preventDefault();
		if(requestedURL){
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
	feedPhoto = true;
  if(scriptJquery(this).find('div').hasClass('sesadvancedactivity_buysell'))
    return false;
	var imageObject = scriptJquery(this).find('div').find('a');
  var getImageHref = imageObject.attr('href');
	var imageSource = imageObject.find('img').attr('src');
  if(openPhotoInLightBoxSesalbum == 0 || getImageHref.indexOf('photo_id') < 0){
		window.location.href = getImageHref;
		return;
	}
	getImageHref = getImageHref.replace('view','image-viewer-detail');
	if(typeof imageSource == 'undefined')
		imageSource = en4.core.baseUrl+'application/modules/Sesbasic/externals/images/loading.gif';
	getRequestedAlbumPhotoForImageViewer(imageSource,getImageHref);
});
}
});
scriptJquery(document).on('click','.optionOpenImageViewer',function(){
	if(!scriptJquery('#ses_media_lightbox_container').length)
			return;
  if(document.getElementById('pswp_top_bar_more').style.display == 'block'){
		//scriptJquery(this).removeClass('active');
		scriptJquery('.pswp__top-bar-more-tooltip').css('display','none');
		scriptJquery(".overlay-model-class").css('display','none');
	}else{
		//scriptJquery(this).addClass('active');
		scriptJquery('.pswp__top-bar-more-tooltip').css('display','block');
		scriptJquery(".overlay-model-class").css('display','block');
	}
});
scriptJquery(document).on('click','#pswp__button--info-show', function(){
  if(!scriptJquery('#ses_media_lightbox_container').length)
    return;
  if(scriptJquery('#pswp__button--info-show').hasClass('active')) {
      scriptJquery("#pswp__button--info-show").removeClass('active');
      scriptJquery("#pswp__scroll-wrap").removeClass('pswp_info_panel_open');
      scriptJquery("#pswp__scroll-wrap").addClass('pswp_info_panel_close');
      scriptJquery("#pswp__button--info-show").attr('title', "Show Info");
  } else {
    scriptJquery("#pswp__button--info-show").attr('title', "Hide Info");
    scriptJquery("#pswp__scroll-wrap").addClass('pswp_info_panel_open');
    scriptJquery("#pswp__button--info-show").addClass('active');
    scriptJquery("#pswp__scroll-wrap").removeClass('pswp_info_panel_close');
    scriptJquery("#pswp__button--info-show").attr('title', "Hide Info");
  }
  setTimeout(function(){ gallery.updateSize(true); }, 510);
});
 var gallery;
 var openPhotoSwipe = function(imageUrl,width,height, iframeData) {
    var pswpElement = document.querySelectorAll('.pswp')[0];

    // build items array
    if(typeof iframeData != 'undefined'){
      var items = [
          {
            html: '<div style="text-align:center;" id="sesvideo_lightbox_content">'+iframeData+'</div>'
          },
      ];
    } else {
      var items = [
          {
              src: imageUrl,
              w: width,
              h: height
          }
      ];
    }
    // define options (if needed)
		/*
			options.mainClass = 'pswp--minimal--dark';
			options.barsSize = {top:0,bottom:0};
			options.captionEl = false;
			options.fullscreenEl = false;
			options.shareEl = false;
			options.bgOpacity = 0.85;
			options.tapToClose = true;
			timeToIdle: 4000;
			options.tapToToggleControls = false;
		*/
    var options = {
        history: false,
        focus: false,
				tapToClose: false,
				shareEl: false,
				closeOnScroll:false,
				clickToCloseNonZoomable: false,
        showAnimationDuration: 0,
        hideAnimationDuration: 0,
				closeOnVerticalDrag : false,
    };
    gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
    gallery.init();
    setTimeout(function(){
      if(!scriptJquery('#media_photo_next_ses_lightbox').length){
       scriptJquery('.image_show_pswp').wrap('<div id="media_photo_next_ses_lightbox" >');
      }
      if(typeof executeTaggerFn == 'function')
        executeTaggerFn();
        executeTaggerFn = function () {};
     }, 1000);
    en4.core.runonce.trigger();
		// before close
		gallery.listen('close', function() {
			closeFunctionCall();
		});
		// before destroy event
		gallery.listen('destroy', function() {
			closeFunctionCall();
		});
};
function closeFunctionCall(){
	if(!scriptJquery('#ses_media_lightbox_container').length)
			return;
	scriptJquery('html').css('position','inherit').css('overflow-y','auto');
  scriptJquery(window).scrollTop(offsetY);

  if(scriptJquery('.emoji_content').css('display') == 'block')
    scriptJquery('.exit_emoji_btn').click();
	index = 0;
	sesaIndex=0;
	if(dataCommentSes)
		scriptJquery('.layout_core_comments').html(dataCommentSes);
		history.pushState(null, null, defaultHashURL);
		clearInterval(slideShowInterval);
		firstStartPoint = 0;
		dataCommentSes = '';
		slideshow = false;
		sesLightbox = false;
		firstStartPointModule = 0;
		var setcookiedata = '';
		if(getTagData != ''){
			scriptJquery('#media_photo_next').after(getTagData);
		}
		if(mediaTags != ''){
			scriptJquery('#media_tags').html(mediaTags);
		}
		scriptJquery('#ses_media_lightbox_container').remove();
		scriptJquery('#ses_media_lightbox_container_video').remove();
    sesCustomPhotoURL = false;
}
// fullscreen code
function changeImageViewerResolution(type){
	if(!scriptJquery('#ses_media_lightbox_container').length)
			return;
	if(type == 'fullscreen'){
		scriptJquery('#ses_media_lightbox_container').addClass('fullscreen');
	}else{
		scriptJquery('#ses_media_lightbox_container').removeClass('fullscreen');
	}
	return true;
}

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

if(is_fullscreen == 0 || slideshow)
	window.SESfullScreenApi.requestFullScreen(document.body);
else
	window.SESfullScreenApi.cancelFullScreen(document.body);
}

if (window.SESfullScreenApi.supportsFullScreen) {
	document.addEventListener(SESfullScreenApi.fullScreenEventName, function() {
		if(!scriptJquery('#ses_media_lightbox_container').length)
			return;
		if (SESfullScreenApi.isFullScreen()) {
			is_fullscreen = 1;
			scriptJquery('#ses_media_lightbox_container').addClass('fullscreen');
			scriptJquery('.sesalbum_toogle_screen').css('backgroundPosition','-44px 0');
			if(scriptJquery('#pswp__button--info-show').hasClass('active')){
				scriptJquery("#pswp__button--info-show").removeClass('active');
				scriptJquery("#pswp__scroll-wrap").removeClass('pswp_info_panel_open');
				scriptJquery("#pswp__scroll-wrap").addClass('pswp_info_panel_close');
				setTimeout(function(){ gallery.updateSize(true); }, 510);
			}
			scriptJquery('.pswp__button--close').hide();
		} else {
			scriptJquery('.sesalbum_toogle_screen').css('backgroundPosition','0 0');
			scriptJquery('.ses_media_lightbox_slideshow_options').hide();
			scriptJquery('.pswp__ui > .pswp__top-bar').show();
			scriptJquery('#nextprevbttn').show();
			slideshow = false;
			scriptJquery('#sesalbum_slideshow_playpause').find('i').removeClass('fa-pause');
			scriptJquery('#sesalbum_slideshow_playpause').find('i').addClass('fa-play');
			scriptJquery('#sesalbum_slideshow_playpause').find('span').html(en4.core.language.translate('Play'));
			is_fullscreen = 0;
			clearInterval(slideShowInterval);
			scriptJquery('.pswp__button--close').show();
			scriptJquery('#ses_media_lightbox_container').removeClass('fullscreen');
		}
		if(typeof slideshow != 'undefined' && slideshow){
				scriptJquery('.ses_media_lightbox_slideshow_options').show();
				scriptJquery('.pswp__ui > .pswp__top-bar').hide();
				scriptJquery('#nextprevbttn').hide();
		}
	}, true);
} else {
	scriptJquery('#fsbutton').hide();
}
//Key Events
scriptJquery(document).on('keyup', function (e) {
		if(scriptJquery('#'+e.target.id).prop('tagName') == 'INPUT' || scriptJquery('#'+e.target.id).prop('tagName') == 'TEXTAREA')
				return true;
		if(!scriptJquery('#ses_media_lightbox_container').length){
			return false;
		}
		e.preventDefault();
		//Next Img On Right Arrow Click
		if (e.keyCode === 39) {
			NextImageViewerPhoto();return false;
		}
		// like code
		if (e.keyCode === 76) {
			scriptJquery('#sesLightboxLikeUnlikeButton').trigger('click');
		}
		// favourite code
		if (e.keyCode === 70) {
			if(scriptJquery('#sesalbum_favourite').length > 0)
				scriptJquery('#sesalbum_favourite').trigger('click');
		}
		//Prev Img on Left Arrow Click
		if (e.keyCode === 37) {
			PrevImageViewerPhoto(); return false;
		}
});
function NextImageViewerPhoto(){
	if(scriptJquery('.pswp').attr('aria-hidden') == 'true'){
			return false;;
	}
	if(scriptJquery('#nav-btn-next').length){
			document.getElementById('nav-btn-next').click();
	}else if(scriptJquery('#last-element-btn').length){
			scriptJquery('#last-element-btn').click();
	}
	return false;
}
function PrevImageViewerPhoto(){
	if(scriptJquery('.pswp').attr('aria-hidden') == 'true'){
			return false;
	}
	if(scriptJquery('#nav-btn-prev').length){
		document.getElementById('nav-btn-prev').click();
	}else if(scriptJquery('#first-element-btn').length){
			document.getElementById('show-all-photo-container').click();
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
		e.preventDefault();
		return false;
});
scriptJquery(document).on('click','#show-all-photo-container',function(){
	if(scriptJquery(this).hasClass('active')){
		scriptJquery(this).removeClass('active');
		scriptJquery('#all_photo_container').css('display','none');
	}else{
		scriptJquery(this).addClass('active');
		scriptJquery('#all_photo_container').css('display','block');
	}
});
scriptJquery(document).on('click','#ses_media_lightbox_all_photo_id > a',function(){
		scriptJquery('#all_photo_container').css('display','none');
		scriptJquery('#show-all-photo-container').removeClass('active');
		if(scriptJquery('#close-all-photos').length>0)
			scriptJquery('#close-all-photos').removeClass('active');
});
scriptJquery(document).on('click','.ses_ml_more_popup_a_list > a , .ses_ml_more_popup_bc > a',function(){
		scriptJquery('#last-element-content').removeClass('active');
		scriptJquery('#last-element-content').css('display','none');
		scriptJquery('#ses_ml_photos_panel_wrapper').html('');
});
scriptJquery(document).on('click','#morepopup_bkbtn_btn',function(){
	scriptJquery('.ses_ml_photos_panel_content').find('div').find('a').eq(0).click();
});
scriptJquery(document).click(function(event){
	if(!scriptJquery('#ses_media_lightbox_container').length)
			return;
	if((event.target.id != 'close-all-photos' && event.target.id != 'a_btn_btn') && event.target.id != 'last-element-btn' && (event.target.id != 'morepopup_closebtn' && event.target.id != 'morepopup_closebtn_btn')){
		scriptJquery('#all_photo_container').css('display','none');
		scriptJquery('#show-all-photo-container').removeClass('active');
		scriptJquery('#last-element-content').css('display','none');
		scriptJquery('#last-element-content').removeClass('active');
		if(scriptJquery('#close-all-photos').length>0)
			scriptJquery('#close-all-photos').removeClass('active');
	}
	if(event.target.id == 'a_btn_btn' || event.target.id == 'show-all-photo-container' || event.target.id == 'close-all-photos' || event.target.id == 'first-element-btn'){
			if(scriptJquery('#close-all-photos').hasClass('active')){
				scriptJquery('#close-all-photos').removeClass('active');
				scriptJquery('#all_photo_container').css('display','none');
				scriptJquery('#show-all-photo-container').removeClass('active');
			}else{
				scriptJquery('#close-all-photos').addClass('active');
				scriptJquery('#show-all-photo-container').addClass('active');
				scriptJquery('#all_photo_container').css('display','block');
			}
	}else	if(event.target.id == 'morepopup_closebtn' || event.target.id == 'morepopup_closebtn_btn' || event.target.id == 'last-element-btn'){
		if(scriptJquery('#last-element-content').hasClass('active')){
			scriptJquery('#last-element-content').removeClass('active');
			scriptJquery('#last-element-content').css('display','none');
		}else{
			scriptJquery('#last-element-content').addClass('active');
			scriptJquery('#last-element-content').css('display','block');
		}
	}
	if((event.target.id != 'overlay-model-class' && event.target.id != 'overlay-model-class-down') && scriptJquery('#overlay-model-class').hasClass('active')){
		if(scriptJquery('#overlay-model-class').hasClass('active')){
			scriptJquery('#overlay-model-class').removeClass('active');
			scriptJquery('.pswp__top-bar-more-tooltip').css('display','none');
			scriptJquery(".overlay-model-class").css('display','none');
		}else{
			scriptJquery('#overlay-model-class').addClass('active');
			scriptJquery('.pswp__top-bar-more-tooltip').css('display','block');
			scriptJquery(".overlay-model-class").css('display','block');
		}
	}
});
var changeDotCounter;
scriptJquery(document).on('click','#last-element-btn',function(){
	if(!scriptJquery('#ses_media_lightbox_container').length)
			return;
  if(sesCustomPhotoURL) {
    var resource_id = scriptJquery('#last-element-btn').attr('data-resource-id');
    var resource_type = scriptJquery('#last-element-btn').attr('data-resource-type');
  }
	scriptJquery('#last-element-content').css('display','block');
	scriptJquery('#last-element-content').addClass('active');
	if(!scriptJquery('#content_last_element_lightbox').hasClass('active')){
			scriptJquery('#content_last_element_lightbox').html('<div class="ses_ml_more_popup_loading_txt">'+en4.core.language.translate("Wait,there's more")+'<span id="1-dot" style="display:none">.</span><span id="2-dot" style="display:none">.</span><span id="3-dot" style="display:none">.</span></div>');
	var changeDotCounter = setInterval(makeDotMove, 500);
    if(sesCustomPhotoURL) {
      getlastElementData(resource_id, resource_type);
    } else {
      getlastElementData();
    }
	}
	return false;
});

function getlastElementData(resource_id, resource_type) {
	if(document.URL.search('chanel_id') == -1) {
    if(sesCustomPhotoURL) {
      var URL = en4.core.baseUrl+'sesbasic/lightbox/last-element-data/resource_id/'+resource_id+'/resource_type/'+resource_type;
    } else{
      var URL = en4.core.baseUrl+'albums/photo/last-element-data/';
    }
  }
	else
		var URL = en4.core.baseUrl+'sesvideo/chanel/last-element-data/';
	imageViewerGetLastElem = scriptJquery.ajax({
      dataType: 'html',
      url :URL,
      data : {
        format : 'html',
      },
      success : function(responseHTML)
      {
				scriptJquery('#content_last_element_lightbox').html(responseHTML);
				scriptJquery('#content_last_element_lightbox').addClass('active');
				clearTimeout(changeDotCounter);
				return true;
      }
		});
// 		en4.core.request.send(imageViewerGetLastElem, {
// 			'force': true
// 		});
}
function makeDotMove(){
	if(!scriptJquery('#ses_media_lightbox_container').length)
			return;
	if(scriptJquery('#1-dot').css('display') == 'none')
		scriptJquery('#1-dot').show();
	else if(scriptJquery('#2-dot').css('display') == 'none')
		scriptJquery('#2-dot').show();
	else if(scriptJquery('#3-dot').css('display') == 'none')
		scriptJquery('#3-dot').show();
	else{
		scriptJquery('#1-dot').hide();
		scriptJquery('#2-dot').hide();
		scriptJquery('#3-dot').hide();
	}
}
var imageViewerGetRequest;
function getAllPhoto(requestURL,sesModuleData){
 if(typeof sesModuleData == 'undefined'){
  requestPhotoSesalbumURL = requestURL.replace('image-viewer-detail','all-photos');
	url = '';
 }else{
	url = requestURL;
  if(sesCustomPhotoURL) {
    requestPhotoSesalbumURL = en4.core.baseUrl+'sesbasic/lightbox/allphoto-ses-compatibility-code/';
  } else {
    requestPhotoSesalbumURL = en4.core.baseUrl+'sesalbum/photo/allphoto-ses-compatibility-code/';
  }
 }
	imageViewerGetRequest = scriptJquery.ajax({
      dataType: 'html',
      url :requestPhotoSesalbumURL,
      data : {
        format : 'html',
				url:url,
      },
      success : function(responseHTML)
      {
					scriptJquery('#all_photo_container').html(responseHTML);
				if( !scriptJquery.trim( scriptJquery('#ses_media_lightbox_all_photo_id').html() ).length )
					scriptJquery('#show-all-photo-container').hide();
				else
					scriptJquery('#show-all-photo-container').show();
					var photo_id = scriptJquery('#sesalbum_photo_id_data_src').attr('data-src');
					scriptJquery('#all-photo-container').slimscroll({
					 height: 'auto',
					 alwaysVisible :true,
					 color :'#ffffff',
					 railOpacity :'0.5',
					 disableFadeOut :true,
					});
					 scriptJquery('#all-photo-container').slimScroll().bind('slimscroll', function(event, pos){
						if(canPaginateAllPhoto == '1' && pos == 'bottom') {
							 sesphotolightbox_123();
						}
        });
				if(photo_id){
					scriptJquery(document).removeClass('currentthumb');
					scriptJquery('#photo-lightbox-id-'+photo_id).addClass('currentthumb');
		 		}

        //if (!matchMedia('only screen and (min-width: 767px)').matches && scriptJquery('#pswp__button--info-show').hasClass('active')) {
        // if(sesshowShowInfomation == 1) {
        //   scriptJquery('#pswp__button--info-show').trigger('click');
        // }
        //}
					return true;
      }
			});
// 			en4.core.request.send(imageViewerGetRequest, {
// 				'force': true
// 			});
}
scriptJquery(document).on('click','#first-element-btn',function(){
	if(!scriptJquery('#ses_media_lightbox_container').length)
			return;
	document.getElementById('show-all-photo-container').click();
});
index = 0;
sesaIndex = 0;
function getImageViewerObjectData(imageURL,requestedURL,forceAllPhoto,firstPointObject,moduleData,sesModuleData){
		if(((index == 0 || typeof forceAllPhoto != 'undefined')) && sesaIndex == 0)
				getAllPhoto(requestedURL,sesModuleData);
		if(typeof sesModuleData != 'undefined'){
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
				url : url,
      },
      success : function(responseHTML)
      {
					scriptJquery('#nextprevbttn').html(responseHTML);
					if(scriptJquery('#last-element-content').text() == ''  && moduleData != 'yes'){
						var setHtml = scriptJquery('#last-element-content').html(scriptJquery('#content-from-element').html());
					}
          var isPrivate = scriptJquery('#media_photo_next_ses').find('#gallery-img').hasClass('ses-private-image');
          scriptJquery('.media_photo_next_ses_btn').remove();
					if(isPrivate){
							scriptJquery('.pswp__top-bar-share').hide();
							scriptJquery('.pswp__top-bar-more').hide();
							scriptJquery('.pswp__top-bar-msg').hide();
					}else{
							scriptJquery('.pswp__top-bar-share').show();
							scriptJquery('.pswp__top-bar-more').show();
							scriptJquery('.pswp__top-bar-msg').show();
					}
					scriptJquery('#content-from-element').html('');
					scriptJquery('.pswp__top-bar').html(scriptJquery('#imageViewerId').html());
					scriptJquery('.pswp__preloader').removeClass('pswp__preloader--active');
					scriptJquery('.pswp__top-bar-action').css('display','block');
						if(((feedPhoto && firstPointObject == 0) || isPrivate) && !scriptJquery('#sesalbum_check_privacy_album').hasClass('ses-blocked-album') ){
						var img = document.createElement('img');
							img.onload = function(){
								openPhotoSwipe(scriptJquery('#sesalbum_photo_id_data_org').attr('data-src'),this.width,this.height);
								scriptJquery('.image_show_pswp').attr('src',scriptJquery('#sesalbum_photo_id_data_org').attr('data-src'));
						}
						img.src = scriptJquery('#sesalbum_photo_id_data_org').attr('data-src');
						scriptJquery('#gallery-img').css('display','block');
					}
          firstStartPoint = 1;
					var changedImage = false;
					if(scriptJquery('#sesalbum_check_privacy_album').hasClass('ses-blocked-album')){
						var password = prompt("Enter the password for album "+scriptJquery('#sesalbum_album_title').html());
						if(typeof password != 'object' && password.toLowerCase() == trim(scriptJquery('#sesalbum_album_password').html())){
							scriptJquery('.pswp__top-bar-share').show();
							scriptJquery('.pswp__top-bar-more').show();
							scriptJquery('.pswp__top-bar-msg').show();
							scriptJquery('.pswp__button--info-show').show();
							scriptJquery('.pswp__top-bar-tag').show();
							scriptJquery('#ses_pswp_information').css('display','');
							setCookieSesalbum(scriptJquery('#sesalbum_album_album_id').attr('data-src'));
							changedImage = true;
							var img = document.createElement('img');
							img.onload = function(){
								openPhotoSwipe(scriptJquery('#sesalbum_photo_id_data_org').attr('data-src'),this.width,this.height);
							}
							img.src = scriptJquery('#sesalbum_photo_id_data_org').attr('data-src');
// 							if(typeof imageViewerGetRequest  != 'undefined'){
// 									imageViewerGetRequest.cancel();
// 							}
							getAllPhoto(requestedURL);
						}else{
							changedImage = false;
							scriptJquery('.pswp__top-bar-share').hide();
							scriptJquery('.pswp__button--info-show').hide();
							scriptJquery('.pswp__top-bar-more').hide();
							scriptJquery('.pswp__top-bar-msg').hide();
							scriptJquery('#ses_pswp_information').hide();
							scriptJquery('.pswp__top-bar-tag').hide();
						}
					}
					if((!scriptJquery('#sesalbum_check_privacy_album').hasClass('ses-blocked-album') && !scriptJquery('#sesalbum_check_privacy_album').hasClass('ses-private-image')) || changedImage){
						var img = document.createElement('img');
						img.onload = function(){
							openPhotoSwipe(scriptJquery('#sesalbum_photo_id_data_org').attr('data-src'),this.width,this.height);
						}
						img.src = scriptJquery('#sesalbum_photo_id_data_org').attr('data-src');
					}
					scriptJquery('#sesalbum_check_privacy_album').remove();
					scriptJquery('#sesalbum_album_password').remove();
					scriptJquery('#sesalbum_album_title').remove();
					scriptJquery('#sesalbum_photo_id_data_org').remove();
					var htmlInfo = scriptJquery('#ses_pswp_information').html();
					scriptJquery('#ses_pswp_information').html('');
					if(scriptJquery('.ses_pswp_information').length)
						scriptJquery('.ses_pswp_information').remove();
					scriptJquery( '<div class="ses_pswp_information">'+htmlInfo+'</div>' ).insertAfter( "#pswp__scroll-wrap" );
					var photo_id = scriptJquery('#sesalbum_photo_id_data_src').attr('data-src');
					if(moduleData != 'yes')
						scriptJquery('.currentthumb').removeClass('currentthumb');
					scriptJquery('#photo-lightbox-id-'+photo_id).addClass('currentthumb');
					/*if(scriptJquery('#map-canvas').length>0)
						initializeSesAlbumMap();*/
					scriptJquery('#heightOfImageViewerContent').css('height', scriptJquery('.ses_pswp_information').height()+'px');
					scriptJquery('#flexcroll').slimscroll({
					 height: 'auto',
					 start : scriptJquery('#ses_pswp_info'),
					});
					if( !scriptJquery.trim( scriptJquery('#ses_media_lightbox_all_photo_id').html() ).length )
						scriptJquery('#show-all-photo-container').hide();
					else
						scriptJquery('#show-all-photo-container').show();
		  if(sesshowShowInfomation == 1) {
				if (matchMedia('only screen and (min-width: 769px)').matches) {
					if(!scriptJquery('.pswp__scroll-wrap').hasClass('.pswp_info_panel_open') && !scriptJquery('.pswp__scroll-wrap').hasClass('.pswp_info_panel_close')){
						scriptJquery('#pswp__button--info-show').addClass('active');
						scriptJquery('.pswp__scroll-wrap').addClass('pswp_info_panel_open');
						
						setTimeout(function(){ gallery.updateSize(true); }, 510);
					}
				}
				if (matchMedia('only screen and (max-width: 768px)').matches) {
					if(!scriptJquery('.pswp__scroll-wrap').hasClass('.pswp_info_panel_open') && !scriptJquery('.pswp__scroll-wrap').hasClass('.pswp_info_panel_close')){
						scriptJquery('#pswp__button--info-show').removeClass('active');
						setTimeout(function(){ gallery.updateSize(true); }, 510);
					}
				}
		  }
					return true;
      }
			});
// 			en4.core.request.send(imageViewerGetRequest, {
// 				'force': true
// 			});
			index++;
			sesaIndex++;
			return ;
}
var slideShowInterval;
var speed = 6000;
var slideshow ;
function slideShow() {
		slideshow = true;
		toogle();
		scriptJquery('.ses_media_lightbox_slideshow_options').show();
		scriptJquery('.pswp__ui > .pswp__top-bar').hide();
		scriptJquery('#nextprevbttn').hide();
}
scriptJquery(document).on('click','#sesalbum_slideshow_playpause',function(){
		if(scriptJquery(this).find('i').hasClass('fa-play')){
			scriptJquery(this).find('i').addClass('fa-pause');
			scriptJquery(this).find('i').removeClass('fa-play');
			scriptJquery(this).find('span').html('Pause');
			slideShowInterval = setInterval(function(){changePositionPhoto();}, speed);
		}else{
			clearInterval(slideShowInterval);
			scriptJquery(this).find('i').removeClass('fa-pause');
			scriptJquery(this).find('i').addClass('fa-play');
			scriptJquery(this).find('span').html('Play');
		}
});
scriptJquery(document).on('click','#sesalbum_slideshow_stop',function(){
		slideshow = false;
		clearInterval(slideShowInterval);
		toogle();
});
function changePositionPhoto(){
 if(!scriptJquery('#last-element-btn').length){
		if(scriptJquery('#nav-btn-next').length){
			document.getElementById('nav-btn-next').click();
		}else if(sesLightbox){
			scriptJquery(this).find('i').removeClass('fa-pause');
			scriptJquery(this).find('i').addClass('fa-play');
			scriptJquery(this).find('span').html('Play');
			clearInterval(slideShowInterval);
			changeSlideshowOptions();
		}else{
			changePosition();
		}
 }else{
	 	scriptJquery('#last-element-btn').click();
		scriptJquery(this).find('i').removeClass('fa-pause');
		scriptJquery(this).find('i').addClass('fa-play');
		scriptJquery(this).find('span').html('Play');
		clearInterval(slideShowInterval);
		changeSlideshowOptions();
 }
}

function changeSlideshowOptions() {
	scriptJquery('.ses_media_lightbox_slideshow_options').hide();
	scriptJquery('.pswp__ui > .pswp__top-bar').show();
	scriptJquery('#nextprevbttn').show();
	slideshow = false;
	scriptJquery('#sesalbum_slideshow_playpause').find('i').removeClass('fa-pause');
	scriptJquery('#sesalbum_slideshow_playpause').find('i').addClass('fa-play');
	scriptJquery('#sesalbum_slideshow_playpause').find('span').html(en4.core.language.translate('Play'));
}

scriptJquery(document).on('click','#editDetailsLink',function(e){
  e.preventDefault();
  scriptJquery('#titleSes').val(scriptJquery('#ses_title_get').html());
  scriptJquery('#descriptionSes').val(scriptJquery('#ses_title_description').html());
  scriptJquery('#editDetailsForm').css('display','block');
  scriptJquery('#ses_pswp_info').css('display','none');
    if(scriptJquery('#locationSes').length >0){
        scriptJquery('#locationSes').val(trim(scriptJquery('#locationVenue').val()));
        mapLoad = false;
        if(scriptJquery('#map-canvas').length)
            scriptJquery('#map-canvas').remove();
        initializeSesAlbumMapList();
        //if(scriptJquery('#ses_location_data').html())
        //editSetMarkerOnMap();
        //google.maps.event.trigger(map, 'resize');
    }

});

scriptJquery(document).on('click','#cancelDetailsSes',function(e){
  e.preventDefault();
  scriptJquery('#editDetailsForm').css('display','none');
  scriptJquery('#ses_pswp_info').css('display','block');
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
// 				scriptJquery('#ses_pswp_info').css('display','block');
// 				return false;
// 			}else{
// 				alert(en4.core.language.translate('Something went wrong,try again later.'));
// 				return false;
// 			}
//     }
// });
// 	return false;
// });


// Common function
scriptJquery(document).on('click','#saveDetailsSes',function(e) {

	if(scriptJquery('#zipCode').val()){
		var zipCodeValue = scriptJquery('#zipCode').val();
		var validateMatched = '^[0-9]{5,6}$';
		if(zipCodeValue!="" && (!zipCodeValue.match(validateMatched))){
			scriptJquery('#validationMsg').html('Please provide a valid Zip Code');
			return false;
		}scriptJquery('#validationMsg').html("");
	}
	e.preventDefault();
	var thisObject = this;
	scriptJquery(thisObject).prop("disabled",true);
	var photo_id = scriptJquery('#photo_id_ses').val();
  var photo_type_ses = scriptJquery('#photo_type_ses').val();
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
				if(scriptJquery('#countrynName').val() != '')
				scriptJquery('#ses_manual_location_data').html(scriptJquery('#countrynName').val());
				scriptJquery('#editDetailsForm').css('display','none');
				scriptJquery('#ses_pswp_info').css('display','block');
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
	var thisObject = this;
	var htmlOnclick = scriptJquery(this).attr('onclick');
	if(typeof htmlOnclick != 'undefined' && htmlOnclick.search('comments') != -1 && scriptJquery('.sesalbum_othermodule_like_button').length){
			if(scriptJquery('.sesalbum_othermodule_like_button').hasClass('button_active')){
				scriptJquery('.sesalbum_othermodule_like_button').removeClass('button_active');
				showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate("Unliked Successfully")+'</span>');
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
		 }else
			scriptJquery('#sesLikeUnlikeButton').removeClass('button_active');
			showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate(itemType+" Unliked Successfully")+'</span>');
		}else{
			if(scriptJquery('#ses_media_lightbox_container').css('display') == 'block'){
		 		scriptJquery('#sesLightboxLikeUnlikeButton').addClass('button_active');
				scriptJquery('#sesLightboxLikeUnlikeButton').find('#like_unlike_count').html(parseInt(scriptJquery('#sesLightboxLikeUnlikeButton').find('#like_unlike_count').html())+1);
			}
		 	else
				scriptJquery('#sesLikeUnlikeButton').addClass('button_active');
			showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate(itemType+" Liked Successfully")+'</span>', 'sesbasic_liked_notification');
		}
	}
});

//compatability code for SES
scriptJquery(document).ready(function(){
  if(typeof sesshowShowInfomation != "undefined"){
    //override lightbox function of addons
    scriptJquery('<script type="application/javascript">function openSeaocoreLightBox(href){if(href.search("/albums/photo/view/album_id/") != -1) return;openLightBoxForSesPlugins(href);return false;}</script>').appendTo("body");
  }
});

sesLightbox = false;
function openLightBoxForSesPlugins(href,imageURL) {
	var manageData = '';
	if(typeof imageURL == 'undefined') {
		feedPhoto = true;
		firstPointObject = 0;
		sesLightbox = true;
		var imageURL = en4.core.baseUrl+'application/modules/Sesbasic/externals/images/loading.gif';
		manageData = 'yes';
	}
  sesCustomPhotoURL = true;
//   if(sesCustomPhotoURL) {
//     sesLightbox = true;
//   }
	getRequestedAlbumPhotoForImageViewer(imageURL,href,'',manageData,'','yes');
	return false;
}
