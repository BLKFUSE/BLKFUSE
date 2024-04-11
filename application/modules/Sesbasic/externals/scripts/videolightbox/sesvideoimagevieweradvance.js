	/*$Id: sesVideoimagevieweradvance.js  2015-6-16 00:00:000 SocialEngineSolutions $ */
	var dataCommentSes = '';
	//store the default browser URL for change state after closing image viewer
	var defaultHashURL = '';
	var requestVideosesbasicURL;
	defaultHashURL = document.URL;
	var firstStartPoint = canPaginateAllVideo = 0;
	firstStartPointModule = 0;
	var height;
	var width;
	var getTagData;
	var mediaTags ;
  var offsetY = window.pageYOffset;
  function getRequestedVideoForImageViewer(imageURL,requestedURL){
	if(openVideoInLightBoxsesbasic == 0){
		window.location.href = requestedURL.replace(videoURLsesbasic+'/imageviewerdetail',videoURLsesbasic);
		return true;
	}
  if(!scriptJquery('#ses_media_lightbox_container_video').length)
	  makeVideoLightboxLayout();
    if(firstStartPoint == 0){
      offsetY = window.pageYOffset;
      scriptJquery('html').css('position','fixed').css('width','100%').css('overflow-y','hidden');
      // scriptJquery('html').css('top', -offsetY + 'px');
    }
	var img = document.createElement('img');
	img.onload = function(){
		//scriptJquery('#ses_media_lightbox_container_video').show();
		 width = this.width;
		 height = this.height;
		 scriptJquery('#ses_media_lightbox_container_video').show();
		 openPhotoSwipeVideo(imageURL,width,height);
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
		var urlChangeState = requestedURL.replace(videoURLsesbasic+'/imageviewerdetail',videoURLsesbasic);
		history.pushState(null, null, urlChangeState);
		requestedURL = changeurlsesbasic(requestedURL);
		scriptJquery('#gallery-img').attr('src',imageURL);
		var htmlElement = document.querySelector("html");
		scriptJquery(htmlElement).css('overflow-y','hidden');
		getImageViewerObjectDataVideo(imageURL,requestedURL);	
	}
	img.src = imageURL;	
}
scriptJquery(document).on('click','.optionOpenImageViewer',function(){
	if(typeof checkRequestmoduleIsVideo != "function" && !checkRequestmoduleIsVideo())
			return;
  if(document.getElementById('overlay-model-class').style.display == 'block'){
		scriptJquery(this).removeClass('active');
		scriptJquery('.pswp__top-bar-more-tooltip').css('display','none');
		scriptJquery(".overlay-model-class").css('display','none');
	}else{
		scriptJquery(this).addClass('active');
		scriptJquery('.pswp__top-bar-more-tooltip').css('display','block');
		scriptJquery(".overlay-model-class").css('display','block');
	}	
});
scriptJquery(document).on('click','#pswp__button--info-show', function(){
	if(typeof checkRequestmoduleIsVideo != "function" && !checkRequestmoduleIsVideo())
			return;
	if(scriptJquery('#pswp__button--info-show').hasClass('active')){
		scriptJquery("#pswp__button--info-show").removeClass('active');
		scriptJquery("#pswp__scroll-wrap").removeClass('pswp_info_panel_open');
		scriptJquery("#pswp__scroll-wrap").addClass('pswp_info_panel_close');
    scriptJquery("#pswp__button--info-show").attr('title', "Show Info");
	}else{
		scriptJquery("#pswp__scroll-wrap").addClass('pswp_info_panel_open');
		scriptJquery("#pswp__button--info-show").addClass('active');
		scriptJquery("#pswp__scroll-wrap").removeClass('pswp_info_panel_close');
    scriptJquery("#pswp__button--info-show").attr('title', "Close");
	}
	setTimeout(function(){ galleryVideo.updateSize(true); }, 510);
});
function makeVideoLightboxLayout(){
  scriptJquery('<div id="ses_media_lightbox_container_video" class="pswp" tabindex="-1" role="dialog" aria-hidden="true"><div class="pswp__bg" id="overlayViewer"></div><div class="pswp__scroll-wrap" id="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div> <div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter" style="display:none"><!-- pagging --></div><a class="pswp__button pswp__button--close" title="Close (Esc)"></a><a class="pswp__button pswp__button--share" title="Share"></a><a class="pswp__button sesbasic_toogle_screen"  href="javascript:;" onclick="toogle()" title="Toggle Fullscreen"></a><a class="pswp__button pswp__button--info pswp__button--info-show" id="pswp__button--info-show" title="Show Info"></a><a class="pswp__button pswp__button--zoom" id="pswp__button--zoom" title="Zoom in/out"></a><div class="pswp__top-bar-action"><div class="pswp__top-bar-albumname" style="display:none">In <a href="javascript:;">Album Name</a></div><div class="pswp__top-bar-tag" style="display:none"><a href="javascript:;">Add Tag</a></div><div class="pswp__top-bar-share" style="display:none"><a href="javascript:;">Share</a></div><div class="pswp__top-bar-like" style="display:none"><a href="javascript:;">Like</a></div><div class="pswp__top-bar-more" style="display:none"><a href="javascript:;">Options<i class="fa fa-angle-down"></i></a><div class="pswp__top-bar-more-tooltip" style="display:none"><a href="javascript:;">Download</a><a href="javascript:;">Make Profile Picture</a></div></div></div><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="overlay-model-class pswp__share-modal--fade-in" style="display:none"></div><div class="pswp__loading-indicator"><div class="pswp__loading-indicator__line"></div></div><div id="nextprevbttn"><a class="pswp__button pswp__button--arrow--left"  id="closeViewer" title="Previous (arrow left)"></a><a class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></a></div><div class="pswp__caption"><div class="pswp__caption__center"></div></div></div></div><div id="all_video_container" style="display:none"></div><div id="last-element-content" style="display:none;"></div></div>').appendTo('body');
};
 var galleryVideo;
 var openPhotoSwipeVideo = function(imageUrl,width,height,iframeData) {
    var pswpElement = document.querySelectorAll('.pswp')[0];
    // build items array
	if(typeof iframeData != 'undefined'){
    var items = [
				{
        	html: '<div style="text-align:center;" id="sesbasic_lightbox_content">'+iframeData+'</div>'
    		},
    ];
	}else{
			var items = [
				{
            src: imageUrl,
            w: width,
            h: height
        }
			]
	}
    // define options (if needed)
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
    galleryVideo = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
    galleryVideo.init();
		// before close
		galleryVideo.listen('close', function() {
			closeFunctionCallVideo();
		});
		// before destroy event
		galleryVideo.listen('destroy', function() {
			closeFunctionCallVideo();
		});
};          
function closeFunctionCallVideo(){
	if(typeof checkRequestmoduleIsVideo != "function")
			return;
	var htmlElement = document.querySelector("html");
	// scriptJquery(htmlElement).css('overflow-y','scroll');
  scriptJquery(htmlElement).css('position','inherit').css('overflow-y','auto');
  // scriptJquery(htmlElement).css('top','0');
  scriptJquery(window).scrollTop(offsetY);
  if(scriptJquery('.emoji_content').css('display') == 'block')
    scriptJquery('.exit_emoji_btn').click();
	index = 0;
	if(dataCommentSes)
		scriptJquery('.layout_core_comments').html(dataCommentSes);
		history.pushState(null, null, defaultHashURL);
		firstStartPoint = 0;
		dataCommentSes = '';
		firstStartPointModule = 0;
		if(getTagData != ''){
			scriptJquery('#media_photo_next').after(getTagData);	
		}
		if(mediaTags != ''){
			scriptJquery('#media_tags').html(mediaTags);		
		}	
		scriptJquery('#ses_media_lightbox_container_video').remove();
}
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
		if(typeof checkRequestmoduleIsVideo != "function")
			return;
		if (fullScreenApi.isFullScreen()) {
			is_fullscreen = 1;
			scriptJquery('#ses_media_lightbox_container_video').addClass('fullscreen');
			scriptJquery('.scriptJquery_toogle_screen').css('backgroundPosition','-44px 0');
			if(scriptJquery('#pswp__button--info-show').hasClass('active')){
				scriptJquery("#pswp__button--info-show").removeClass('active');
				scriptJquery("#pswp__scroll-wrap").removeClass('pswp_info_panel_open');
				scriptJquery("#pswp__scroll-wrap").addClass('pswp_info_panel_close');
				setTimeout(function(){ galleryVideo.updateSize(true); }, 510);
			}
			scriptJquery('.pswp__button--close').hide();
		} else {
			scriptJquery('.scriptJquery_toogle_screen').css('backgroundPosition','0 0');
			scriptJquery('.pswp__ui > .pswp__top-bar').show();
			scriptJquery('#nextprevbttn').show();
			is_fullscreen = 0;
			scriptJquery('.pswp__button--close').show();
			scriptJquery('#ses_media_lightbox_container_video').removeClass('fullscreen');
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
		//Next Img On Right Arrow Click
		if (e.keyCode === 39) { 
			NextImageViewerVideo();return false; 
		}
		// like code
		if (e.keyCode === 76) {
			scriptJquery('#sesLightboxLikeUnlikeButtonVideo').trigger('click');
		}
		// favourite code
		if (e.keyCode === 70) {
			if(scriptJquery('#scriptJquery_favourite').length > 0)
				scriptJquery('#scriptJquery_favourite').trigger('click');
		}
		//Prev Img on Left Arrow Click
		if (e.keyCode === 37) { 
			PrevImageViewerVideo(); return false;
		}
});
function NextImageViewerVideo(){
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
function PrevImageViewerVideo(){
	if(scriptJquery('.pswp').attr('aria-hidden') == 'true'){
			return false;
	}
	if(scriptJquery('#nav-btn-prev').length){
		document.getElementById('nav-btn-prev').click();
	}else if(scriptJquery('#first-element-btn').length){
			document.getElementById('show-all-video-container').click();
	}
	return false;
}
scriptJquery(document).on('click','#show-all-video-container',function(){
	if(typeof checkRequestmoduleIsVideo != "function")
		return;
	if(scriptJquery(this).hasClass('active')){
		scriptJquery(this).removeClass('active');
		scriptJquery('#all_video_container').css('display','none');
	}else{
		scriptJquery(this).addClass('active');
		scriptJquery('#all_video_container').css('display','block');
	}
});
scriptJquery(document).on('click','#ses_media_lightbox_all_video_id > a',function(){
	if(typeof checkRequestmoduleIsVideo != "function")
		return;
		scriptJquery('#all_video_container').css('display','none');
		scriptJquery('#show-all-video-container').removeClass('active');
		if(scriptJquery('#close-all-videos').length>0)
			scriptJquery('#close-all-videos').removeClass('active');
});
scriptJquery(document).on('click','.ses_ml_more_popup_a_list > a , .ses_ml_more_popup_bc > a',function(){
	if(typeof checkRequestmoduleIsVideo != "function")
		return;
		scriptJquery('#last-element-content').removeClass('active');
		scriptJquery('#last-element-content').css('display','none');
		scriptJquery('#ses_ml_photos_panel_wrapper').html('');
		index = 0;
});
scriptJquery(document).on('click','#morepopup_bkbtn_btn',function(){
	if(typeof checkRequestmoduleIsVideo != "function")
		return;
	scriptJquery('.ses_ml_photos_panel_content').find('div').find('a').eq(0).click();
});
scriptJquery(document).click(function(event){
	if(typeof checkRequestmoduleIsVideo != "function")
		return;
	if((event.target.id != 'close-all-videos' && event.target.id != 'a_btn_btn') && event.target.id != 'last-element-btn' && (event.target.id != 'morepopup_closebtn' && event.target.id != 'morepopup_closebtn_btn')){
		scriptJquery('#all_video_container').css('display','none');
		scriptJquery('#show-all-video-container').removeClass('active');
		scriptJquery('#last-element-content').css('display','none');
		scriptJquery('#last-element-content').removeClass('active');
		if(scriptJquery('#close-all-videos').length>0)
			scriptJquery('#close-all-videos').removeClass('active');
	}
	if(event.target.id == 'a_btn_btn' || event.target.id == 'show-all-video-container' || event.target.id == 'close-all-videos' || event.target.id == 'first-element-btn'){
			if(scriptJquery('#close-all-videos').hasClass('active')){
				scriptJquery('#close-all-videos').removeClass('active');
				scriptJquery('#all_video_container').css('display','none');
				scriptJquery('#show-all-video-container').removeClass('active');
			}else{
				scriptJquery('#close-all-videos').addClass('active');
				scriptJquery('#show-all-video-container').addClass('active');
				scriptJquery('#all_video_container').css('display','block');
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
	if(typeof checkRequestmoduleIsVideo != "function")
		return;
	scriptJquery('#last-element-content').css('display','block');
	scriptJquery('#last-element-content').addClass('active');
	if(!scriptJquery('#content_last_element_lightbox').hasClass('active')){
			scriptJquery('#content_last_element_lightbox').html('<div class="ses_ml_more_popup_loading_txt">'+en4.core.language.translate("Wait,there's more")+'<span id="1-dot" style="display:none">.</span><span id="2-dot" style="display:none">.</span><span id="3-dot" style="display:none">.</span></div>');
	var changeDotCounter = setInterval(makeDotMoveVideo, 500);
			getlastElementDataVideo(scriptJquery(this).attr('data-rel'),scriptJquery(this).attr('data-id'));
	}
	return false;
});
function getlastElementDataVideo(type,item_id){
	var URL = en4.core.baseUrl+moduleName+'/index/last-element-data/type/'+type+'/'+item_id;
	imageViewerGetLastElem = scriptJquery.ajax({
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
}
function makeDotMoveVideo(){
	if(typeof checkRequestmoduleIsVideo != "function")
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
scriptJquery(document).on('click','.ses-image-viewer',function(e){
		e.preventDefault();
		return false;
});
function getAllVideo(requestURL){
  requestVideoscriptJqueryURL = requestURL.replace(videoURLsesbasic+'/imageviewerdetail',videoURLsesbasic+'/all-videos');
	imageViewerGetRequest = scriptJquery.ajax({
      url :requestVideoscriptJqueryURL,
      data : {
        format : 'html',
      },
      success : function(responseHTML)
      {
					scriptJquery('#all_video_container').html(responseHTML);
				if( !scriptJquery.trim( scriptJquery('#ses_media_lightbox_all_video_id').html() ).length ) 
					scriptJquery('#show-all-video-container').hide();
				else
					scriptJquery('#show-all-video-container').show();
					var video_id = scriptJquery('#sesbasic_video_id_data_src').attr('data-src');
					scriptJquery('#all-video-container').slimscroll({
					 height: 'auto',
					 alwaysVisible :true,
					 color :'#ffffff',
					 railOpacity :'0.5',
					 disableFadeOut :true,					 
					});
					 scriptJquery('#all-video-container').slimScroll().bind('slimscroll', function(event, pos){
						if(canPaginateAllVideo == '1' && pos == 'bottom') {
							 sesbasiclightbox_123();
						}
        });
				if(video_id){
					scriptJquery(document).removeClass('currentthumb');
					scriptJquery('#video-lightbox-id-'+video_id).addClass('currentthumb');
		 		}
        if(sesbasicShowInformation == 1) {
          scriptJquery('#pswp__button--info-show').trigger('click');
        }
					return true;
      }
			}); 	
}
scriptJquery(document).on('click','#first-element-btn',function(){
	if(typeof checkRequestmoduleIsVideo != "function")
		return;
	document.getElementById('show-all-video-container').click();
});
index = 0;
function getImageViewerObjectDataVideo(imageURL,requestedURL){
		if((index == 0))
				getAllVideo(requestedURL);
		if(scriptJquery('#pswp__button--info-show').hasClass('active') && index != 0){
      scriptJquery("#pswp__button--info-show").removeClass('active');
      scriptJquery("#pswp__scroll-wrap").removeClass('pswp_info_panel_open');
      scriptJquery("#pswp__scroll-wrap").addClass('pswp_info_panel_close');
			setTimeout(function(){ galleryVideo.updateSize(true); }, 510);
    }
		 imageViewerGetRequest = scriptJquery.ajax({
      url :requestedURL,
      data : {
        format : 'html',
      },
      success : function(responseHTML)
				{
						scriptJquery('#nextprevbttn').html(responseHTML);
						if(scriptJquery('#last-element-content').text() == ''){
							var setHtml = scriptJquery('#last-element-content').html(scriptJquery('#content-from-element').html());
						}
						scriptJquery('.pswp__top-bar').html(scriptJquery('#imageViewerId').html());
						scriptJquery('.pswp__preloader').removeClass('pswp__preloader--active');
						scriptJquery('.pswp__top-bar-action').css('display','block');
						var changeIframeSize = true;
						if(scriptJquery('#media_video_next_ses').hasClass('ses-private-image')){
								var imagePrivateUrl = scriptJquery('#media_video_next_ses').find('#gallery-img').attr('src');
								scriptJquery('.pswp__top-bar-share').hide();
								scriptJquery('.pswp__top-bar-more').hide();
								scriptJquery('.pswp__top-bar-msg').hide();
								scriptJquery('.pswp__button--info-show').hide();
								scriptJquery('#ses_pswp_information').hide();
								var img = document.createElement('img');
								img.onload = function(){
									openPhotoSwipeVideo(imagePrivateUrl,this.width,this.height);
									scriptJquery('.image_show_pswp').attr('src',imagePrivateUrl);
							}
							img.src = scriptJquery('#media_video_next_ses').find('#gallery-img').attr('src');
							scriptJquery('#media_video_next_ses').remove();
						}else if(scriptJquery('#media_video_next_ses').hasClass('ses-blocked-video')){
								var password = prompt("Enter the password for video '"+scriptJquery('#sesbasic_video_title').html()+"'");
								if(typeof password != 'object' && password.toLowerCase() == trim(scriptJquery('#sesbasic_video_password').html())){
									scriptJquery('.pswp__top-bar-share').show();
									scriptJquery('.pswp__top-bar-more').show();
                  var video_id = scriptJquery('#sesbasic_video_id_data_src').attr('data-src');
                  setCookieSesvideo(video_id);
                  
									scriptJquery('.pswp__top-bar-msg').show();
									scriptJquery('.pswp__button--info-show').show();
									scriptJquery('#ses_pswp_information').css('display','');
									openPhotoSwipeVideo('','','',scriptJquery('#video_data_lightbox').find('.sesbasic_view_embed_lightbox').html());
									scriptJquery('#media_video_next_ses').remove();
                  getAllVideo(requestedURL);
								}else{
									scriptJquery('.pswp__top-bar-share').hide();
									scriptJquery('.pswp__button--info-show').hide();
									scriptJquery('.pswp__top-bar-more').hide();
									scriptJquery('.pswp__top-bar-msg').hide();
									scriptJquery('#ses_pswp_information').hide();
									var img = document.createElement('img');
									img.onload = function(){
										openPhotoSwipeVideo(scriptJquery(img).attr('src'),this.width,this.height);
										scriptJquery('.image_show_pswp').attr('src',scriptJquery(img).attr('src'));
										scriptJquery('#media_video_next_ses').remove();
									}
									img.src = scriptJquery('#media_video_next_ses').find('#gallery-img').attr('src');
									if(scriptJquery('#video_embed_lightbox').length)
										scriptJquery('#video_embed_lightbox').find('iframe').src('');
									changeIframeSize = false;
								}
						}else{
								scriptJquery('.pswp__top-bar-share').show();
								scriptJquery('.pswp__button--info-show').show();
								scriptJquery('.pswp__top-bar-more').show();
								scriptJquery('.pswp__top-bar-msg').show();
								scriptJquery('#ses_pswp_information').css('display','');
								if(typeof flashembedAttach == 'function'){
									openPhotoSwipeVideo('','','',scriptJquery('#video_data_lightbox').find('.sesbasic_view_embed_lightbox').html());
									//check flashembed object exists on page or not ,if not incluse it
									if(!(typeof flashembed == 'function')){
										new Asset.javascript( en4.core.baseUrl+'externals/flowplayer/flashembed-1.0.1.pack.js',{
												onLoad :flashembedAttach
										});
									} else {
										flashembedAttach();
									}
									changeIframeSize = true;
									flashembedAttach = null;
								}else{
									openPhotoSwipeVideo('','','',scriptJquery('#video_data_lightbox').find('.sesbasic_view_embed_lightbox').html());
								}
								scriptJquery('#media_video_next_ses').remove();
						}
						scriptJquery('#media_video_next_ses').remove();
						scriptJquery('#sesbasic_video_password').remove();
						scriptJquery('#sesbasic_video_title').remove();
						scriptJquery('#content-from-element').html('');	
						if(changeIframeSize){				
							var height = scriptJquery('.pswp__zoom-wrap').height();
							var width = scriptJquery('.pswp__zoom-wrap').width();
							var marginTop = scriptJquery('.pswp__top-bar').height();
							if(scriptJquery('#sesbasic_lightbox_content').find('iframe').length){
								scriptJquery('#sesbasic_lightbox_content ').find('iframe').css('height',parseInt(height-marginTop)+'px');
								scriptJquery('#sesbasic_lightbox_content').find('iframe').css('width',width+'px');
								scriptJquery('#sesbasic_lightbox_content ').find('iframe').attr('height',parseInt(height-marginTop));
								scriptJquery('#sesbasic_lightbox_content').find('iframe').attr('width',width);
								scriptJquery('#sesbasic_lightbox_content').find('iframe').css('margin-top',marginTop+'px');
								scriptJquery('#sesbasic_lightbox_content').find('iframe').css('margin-bottom',marginTop+'px');
								if(scriptJquery('#sesbasic_lightbox_content').find('iframe').attr('src').indexOf('?') > -1){
									var urlQuery = '&width='+width+'&height='+parseInt(height-marginTop);
								}else
									var urlQuery = '?width='+width+'&height='+parseInt(height-marginTop);
								var srcAttr = scriptJquery('#sesbasic_lightbox_content').find('iframe').attr('src')+urlQuery;
							}else if(scriptJquery('#sesbasic_lightbox_content').find('video').length){
								scriptJquery('#sesbasic_lightbox_content').find('video').css('margin-top',(height/4)+'px');
							}else{
								scriptJquery('#sesbasic_lightbox_content').find('object').css('margin-top',(height/4)+'px');	
							}
						}
						var htmlInfo = scriptJquery('#ses_pswp_information').html();
						scriptJquery('#ses_pswp_information').html('');
						if(scriptJquery('.ses_pswp_information').length)
							scriptJquery('.ses_pswp_information').remove();
						scriptJquery( '<div class="ses_pswp_information">'+htmlInfo+'</div>' ).insertAfter( "#pswp__scroll-wrap" );
						var video_id = scriptJquery('#sesbasic_video_id_data_src').attr('data-src');
						scriptJquery('.currentthumb').removeClass('currentthumb');
						scriptJquery('#video-lightbox-id-'+video_id).addClass('currentthumb');
						if(scriptJquery('#map-canvas').length>0 && typeof initializeSesVideoMap == 'function')
							initializeSesVideoMap();
						else{
							scriptJquery('#locationSes').hide();
							scriptJquery('#map-canvas').hide();
						}
						scriptJquery('#heightOfImageViewerContent').css('height', scriptJquery('.ses_pswp_information').height()+'px');
						scriptJquery('#flexcroll').slimscroll({
						 height: 'auto',
						 start : scriptJquery('#ses_pswp_info'),
						});
						if( !scriptJquery.trim( scriptJquery('#ses_media_lightbox_all_video_id').html() ).length ) 
							scriptJquery('#show-all-video-container').hide();
						else
							scriptJquery('#show-all-video-container').show();
							
						if(typeof srcAttr != 'undefined'){
							scriptJquery('#sesbasic_lightbox_content').find('iframe').attr('src',srcAttr);
							var aspect = 16 / 9;
							var el = document.getElementById(scriptJquery('#sesbasic_lightbox_content').find('iframe').attr('id'));
							if(typeof el == "undefined" || !el)
								return;
							var parent = el.getParent();
							var parentSize = parent.getSize();
							el.set("width", parentSize.x);
							el.set("height", parentSize.x / aspect);	
						}						
						return true;
				}
			}); 
			index++;
			return false;
}
function changeurlsesbasic(url){
	if(url.search('imageviewerdetail') == -1){
	  url = url.replace(videoURLsesbasic,videoURLsesbasic+'/imageviewerdetail');
	}
		return url;
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
		scriptJquery('#ses_pswp_info').css('display','none');
});
scriptJquery(document).on('click','#cancelDetailssesbasic',function(e){
		e.preventDefault();
		scriptJquery('#editDetailsFormVideo').css('display','none');
		scriptJquery('#ses_pswp_info').css('display','block');
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
			scriptJquery('#sesLightboxLikeUnlikeButtonVideo').find('#like_unlike_count').html(parseInt(scriptJquery('#sesLightboxLikeUnlikeButton').find('#like_unlike_count').html())-1);
		 }else
			scriptJquery('#sesLikeUnlikeButton').removeClass('button_active');
			showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate(itemType+" removed from like successfully")+'</span>');
		}else{
			if(scriptJquery('#ses_media_lightbox_container_video').css('display') == 'block'){
		 		scriptJquery('#sesLightboxLikeUnlikeButtonVideo').addClass('button_active');
				scriptJquery('#sesLightboxLikeUnlikeButtonVideo').find('#like_unlike_count').html(parseInt(scriptJquery('#sesLightboxLikeUnlikeButton').find('#like_unlike_count').html())+1);
			}
		 	else
				scriptJquery('#sesLightboxLikeUnlikeButtonVideo').addClass('button_active');
			showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate(itemType+" like successfully")+'</span>', 'sesbasic_liked_notification');
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
