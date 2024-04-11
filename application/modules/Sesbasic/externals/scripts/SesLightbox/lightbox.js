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
   var offsetY = window.pageYOffset;
	function makeLayoutForImageViewer(){
		if(scriptJquery('#ses_media_lightbox_container').length)
			return;
		scriptJquery('<div id="ses_media_lightbox_container" style="display:block" class="pswp" tabindex="-1" role="dialog" aria-hidden="true"><div class="pswp__bg" id="overlayViewer"></div><div class="pswp__scroll-wrap" id="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div> <div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter" style="display:none"><!-- pagging --></div><a class="pswp__button pswp__button--close" title="Close (Esc)"></a><a class="pswp__button pswp__button--share" title="Share"></a><a class="pswp__button sesalbum_toogle_screen"  href="javascript:;" onclick="toogle()" title="Toggle Fullscreen"></a><a class="pswp__button pswp__button--info pswp__button--info-show" style="display:none" id="pswp__button--info-show-lightbox" title="Show Info"></a><a class="pswp__button pswp__button--zoom" id="pswp__button--zoom" title="Zoom in/out"></a><div class="pswp__top-bar-action"><div class="pswp__top-bar-albumname" style="display:none">In <a href="javascript:;">Album Name</a></div><div class="pswp__top-bar-tag" style="display:none"><a href="javascript:;">Add Tag</a></div><div class="pswp__top-bar-share" style="display:none"><a href="javascript:;">Share</a></div><div class="pswp__top-bar-like" style="display:none"><a href="javascript:;">Like</a></div><div class="pswp__top-bar-more" style="display:none"><a href="javascript:;">Options<i class="fa fa-angle-down"></i></a><div class="pswp__top-bar-more-tooltip" style="display:none"><a href="javascript:;">Download</a><a href="javascript:;">Make Profile Picture</a></div></div></div><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="overlay-model-class pswp__share-modal--fade-in" style="display:none"></div><div class="pswp__loading-indicator"><div class="pswp__loading-indicator__line"></div></div><div id="nextprevbttn"><a class="pswp__button pswp__button--arrow--left"  id="closeViewer" title="Previous (arrow left)"></a><a class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></a></div><div class="pswp__caption"><div class="pswp__caption__center"></div></div></div><div class="ses_media_lightbox_slideshow_options sesbasic_bxs"><a href="javascript:;" id="sesalbum_slideshow_playpause"><i class="fa fa-play"></i><span>Play</span></a><a href="javascript:;" id="sesalbum_slideshow_stop"><i class="fa fa-stop"></i></a></div></div><div id="all_photo_container" style="display:none"></div><div id="last-element-content" style="display:none;"></div></div>').appendTo('body');	
	}
	scriptJquery(document).on('click','.seslightbox_image_open',function(e){
			var image = scriptJquery(this).find('img').attr('src');
			if(image)
				openDirectImageLightbox(image);
	});
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
  offsetY = window.pageYOffset;
  scriptJquery('html').css('position','fixed').css('width','100%').css('overflow','hidden');
  scriptJquery('html').css('top', -offsetY + 'px');
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
	var urlChangeState = requestedURL.replace('image-viewer-detail','view');
	urlChangeState = urlChangeState.replace('third-party-imageview-integration','view');
	history.pushState(null, null, urlChangeState);
	if(firstStartPoint == 0 && feedPhoto){
		//scriptJquery('#gallery-img').css('display','none');
	}else{
		scriptJquery('#gallery-img').attr('src',imageURL);
	}
	getImageViewerObjectData(imageURL,requestedURL,forceAllPhoto,firstStartPoint,moduleData,sesModuleData);	
	}
	img.src = imageURL;	
}
scriptJquery(document).on("click", '.seslightbox_no_prop', function (e) {
	e.preventDefault();
});
scriptJquery(document).on('click','.optionOpenImageViewerLightbox',function(){
	if(!scriptJquery('#ses_media_lightbox_container').length)
			return;
  if(document.getElementById('pswp_top_bar_more').style.display == 'block'){
		scriptJquery(this).removeClass('active');
		scriptJquery('.pswp__top-bar-more-tooltip').css('display','none');
		scriptJquery(".overlay-model-class").css('display','none');
	}else{
		scriptJquery(this).addClass('active');
		scriptJquery('.pswp__top-bar-more-tooltip').css('display','block');
		scriptJquery(".overlay-model-class").css('display','block');
	}	
});
scriptJquery(document).on('click','#pswp__button--info-show-lightbox', function(){
	if(!scriptJquery('#ses_media_lightbox_container').length)
			return;
		if(scriptJquery('#pswp__button--info-show-lightbox').hasClass('active')){
      scriptJquery("#pswp__button--info-show-lightbox").removeClass('active');
      scriptJquery("#pswp__scroll-wrap").removeClass('pswp_info_panel_open');
      scriptJquery("#pswp__scroll-wrap").addClass('pswp_info_panel_close');
    }else{
      scriptJquery("#pswp__scroll-wrap").addClass('pswp_info_panel_open');
      scriptJquery("#pswp__button--info-show-lightbox").addClass('active');
      scriptJquery("#pswp__scroll-wrap").removeClass('pswp_info_panel_close');
    }
			setTimeout(function(){ gallery.updateSize(true); }, 510);
});
 var gallery;
 var openPhotoSwipe = function(imageUrl,width,height) {
    var pswpElement = document.querySelectorAll('.pswp')[0];
    // build items array
    var items = [
        {
            src: imageUrl,
            w: width,
            h: height
        }
    ];
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
  scriptJquery('html').css('position','auto').css('overflow','auto');
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
		if(getTagData != ''){
			scriptJquery('#media_photo_next').after(getTagData);	
		}
		if(mediaTags != ''){
			scriptJquery('#media_tags').html(mediaTags);		
		}	
		scriptJquery('#ses_media_lightbox_container').remove();
		scriptJquery('#ses_media_lightbox_container_video').remove();
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
			if(scriptJquery('#pswp__button--info-show-lightbox').hasClass('active')){
				scriptJquery("#pswp__button--info-show-lightbox").removeClass('active');
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
	scriptJquery('#last-element-content').css('display','block');
	scriptJquery('#last-element-content').addClass('active');
	if(!scriptJquery('#content_last_element_lightbox').hasClass('active')){
			scriptJquery('#content_last_element_lightbox').html('<div class="ses_ml_more_popup_loading_txt">'+en4.core.language.translate("Wait,there's more")+'<span id="1-dot" style="display:none">.</span><span id="2-dot" style="display:none">.</span><span id="3-dot" style="display:none">.</span></div>');
	var changeDotCounter = setInterval(makeDotMove, 500);
			getlastElementData();
	}
	return false;
});
function getlastElementData(){
	if(document.URL.search('chanel_id') == -1)
		var URL = en4.core.baseUrl+'albums/photo/last-element-data/';
	else
		var URL = en4.core.baseUrl+'sesvideo/chanel/last-element-data/';
	imageViewerGetLastElem = scriptJquery.ajax({
      url :URL,
      data : {
        format : 'html',
      },
      success: function(responseHTML)
      {
				scriptJquery('#content_last_element_lightbox').html(responseHTML);
				scriptJquery('#content_last_element_lightbox').addClass('active');
				clearTimeout(changeDotCounter);
				return true;
      }
		}); 
		en4.core.request.send(imageViewerGetLastElem, {
			'force': true
		});	
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
function getAllPhoto(requestURL,sesModuleData){
 if(typeof sesModuleData == 'undefined'){
  requestPhotoSesalbumURL = requestURL.replace('image-viewer-detail','all-photos');
	url = '';
 }else{
	url = requestURL;
 	requestPhotoSesalbumURL = en4.core.baseUrl+'sesbasic/index/allphoto-ses-compatibility-code/';
 }
	imageViewerGetRequest = scriptJquery.ajax({
      url :requestPhotoSesalbumURL,
      data : {
        format : 'html',
				url:url,
      },
      success: function(responseHTML)
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
					return true;
      }
			}); 
			en4.core.request.send(imageViewerGetRequest, {
				'force': true
			});	
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
				requestedURL = en4.core.baseUrl+'sesbasic/index/ses-compatibility-code/';
		}else
			var url = '';
		
		 imageViewerGetRequest = scriptJquery.ajax({
      url :requestedURL,
      data : {
        format : 'html',
				url : url,
      },
      success: function(responseHTML)
      {
					scriptJquery('#nextprevbttn').html(responseHTML);
					if(scriptJquery('#last-element-content').text() == ''  && moduleData != 'yes'){
						var setHtml = scriptJquery('#last-element-content').html(scriptJquery('#content-from-element').html());
					}
					if(scriptJquery('#media_photo_next_ses').find('#gallery-img').hasClass('ses-private-image')){
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
					if((feedPhoto && firstPointObject == 0) || scriptJquery('#media_photo_next_ses').find('#gallery-img').hasClass('ses-private-image')){
						var img = document.createElement('img');
							img.onload = function(){
								openPhotoSwipe(scriptJquery('#media_photo_next_ses').find('#gallery-img').attr('src'),this.width,this.height);
								scriptJquery('.image_show_pswp').attr('src',scriptJquery('#media_photo_next_ses').find('#gallery-img').attr('src'));
						}
						img.src = scriptJquery('#media_photo_next_ses').find('#gallery-img').attr('src');
						scriptJquery('#gallery-img').css('display','block');
						firstStartPoint = 1;
					}
					scriptJquery('#media_photo_next_ses').remove();
					scriptJquery('.image_show_pswp').wrap('<div id="media_photo_next_ses" >');
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
					return true;
      }
			}); 
			en4.core.request.send(imageViewerGetRequest, {
				'force': true
			});
			index++;
			sesaIndex++;
			return ;
}
var slideShowInterval;
var speed = 6000;
var slideshow ;
function slideShow(){
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
			slideShowInterval = setInterval(changePosition, speed);			
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
function changePosition(){
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
function changeSlideshowOptions(){
	scriptJquery('.ses_media_lightbox_slideshow_options').hide();
	scriptJquery('.pswp__ui > .pswp__top-bar').show();
	scriptJquery('#nextprevbttn').show();
	slideshow = false;
	scriptJquery('#sesalbum_slideshow_playpause').find('i').removeClass('fa-pause');
	scriptJquery('#sesalbum_slideshow_playpause').find('i').addClass('fa-play');
	scriptJquery('#sesalbum_slideshow_playpause').find('span').html(en4.core.language.translate('Play'));
}
scriptJquery(document).on('click','#editBDetailsLink',function(e){
		e.preventDefault();
		scriptJquery('#titleSes').val(trim(scriptJquery('#ses_title_get').html(),' '));
		scriptJquery('#descriptionSes').val(trim(scriptJquery('#ses_title_description').html(),' '));
	if(scriptJquery('#locationSes').length >0){
		scriptJquery('#locationSes').val(trim(scriptJquery('#ses_location_data').html()));
		mapLoad = false;
		if(scriptJquery('#map-canvas').length)
			scriptJquery('#map-canvas').remove();
		initializeSesAlbumMapList();
		//if(scriptJquery('#ses_location_data').html())
				//editSetMarkerOnMap();
	 //google.maps.event.trigger(map, 'resize');
	}
		scriptJquery('#editDetailsForm').css('display','block');
		scriptJquery('#ses_pswp_info').css('display','none');
});
scriptJquery(document).on('click','#cancelBDetailsSes',function(e){
		e.preventDefault();
		scriptJquery('#editDetailsForm').css('display','none');
		scriptJquery('#ses_pswp_info').css('display','block');
});
scriptJquery(document).on('click','#saveBDetailsChanelSes',function(e){
	e.preventDefault();
	var thisObject = this;
	scriptJquery(thisObject).prop("disabled",true);
	var photo_id = scriptJquery('#photo_id_ses').val();
	var formData =  scriptJquery("#changeBPhotoDetails").serializeArray();
	scriptJquery.ajax({  
    type: "POST",  
    url: en4.core.baseUrl+'sesbasic/index/edit-detail/photo_id/'+photo_id,  
    data: formData,  
    success: function(response) {
      var data = JSON.parse(response);
			if(data.status && !data.error){
				scriptJquery(thisObject).prop("disabled",false);
				scriptJquery('#ses_title_get').html(scriptJquery('#titleSes').val());
				scriptJquery('#ses_title_description').html(scriptJquery('#descriptionSes').val());
				scriptJquery('#editDetailsForm').css('display','none')
				scriptJquery('#ses_pswp_info').css('display','block');
				return false;
			}else{
				alert(en4.core.language.translate('Something went wrong,try again later.'));	
				return false;
			}
    }
});
	return false;
});
sesLightbox = false;
feedPhoto = false;
function openLightBoxForSesPlugins(href,imageURL){
	var manageData = '';
	if(typeof imageURL == 'undefined'){
		feedPhoto = true;
		firstPointObject = 0;
		sesLightbox = true;
		var imageURL = en4.core.baseUrl+'application/modules/Sesbasic/externals/images/loading.gif';
		manageData = 'yes';
	}
	getRequestedAlbumPhotoForImageViewer(imageURL,href,'',manageData,'','yes');
	return false;
}
