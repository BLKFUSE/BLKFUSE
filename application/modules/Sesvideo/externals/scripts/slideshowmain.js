var autoPlayId,firstVideoSrc=false,isOnRegister = false,IsfinishVideoNext = false,clearIntvalSesVideoSlideshow1,clearIntvalSesVideoSlideshow2 ;
jQuery(document).ready(function($){
	var slidesWrapper = document.getElementById('.cd-hero-slider');
	//check if a .cd-hero-slider exists in the DOM onended=""
	if ( slidesWrapper.length > 0 ) {
		var primaryNav = document.getElementById('.cd-primary-nav'),
			sliderNav = document.getElementById('.cd-slider-nav'),
			navigationMarker = document.getElementById('.cd-marker'),
			slidesNumber = slidesWrapper.children('li').length,
			visibleSlidePosition = 0,
			autoPlayDelay = 5000;
		//upload videos (if not on mobile devices)
		uploadVideo(slidesWrapper);
		//autoplay slider
		if(!firstVideoSrc)
			setAutoplay(slidesWrapper, slidesNumber, autoPlayDelay);
		//on mobile - open/close primary navigation clicking/tapping the menu icon
		primaryNav.on('click', function(event){
			if($(event.target).is('.cd-primary-nav')) $(this).children('ul').toggleClass('is-visible');
		});
		//change visible slide
		sliderNav.on('click', 'li', function(event){
			event.preventDefault();
			var selectedItem = $(this);
			if(!selectedItem.hasClass('selected')) {
				// if it's not already selected
				var selectedPosition = selectedItem.index(),
					activePosition = slidesWrapper.find('li.selected').index();
				
				if( activePosition < selectedPosition) {
					nextSlide(slidesWrapper.find('.selected'), slidesWrapper, sliderNav, selectedPosition);
				} else {
					prevSlide(slidesWrapper.find('.selected'), slidesWrapper, sliderNav, selectedPosition);
				}
				//this is used for the autoplay
				visibleSlidePosition = selectedPosition;
				updateSliderNavigation(sliderNav, selectedPosition);
				updateNavigationMarker(navigationMarker, selectedPosition+1);
				//reset autoplay
				setAutoplay(slidesWrapper, slidesNumber, autoPlayDelay);
				if(slidesWrapper.find('li.selected').hasClass('cd-bg-video')){
					 clearInterval(autoPlayId);
					 clearInterval(clearIntvalSesVideoSlideshow1);
					 clearInterval(clearIntvalSesVideoSlideshow1);	
				}
			}
		});
	}
	function nextSlide(visibleSlide, container, pagination, n){
		visibleSlide.removeClass('selected from-left from-right').addClass('is-moving').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
			visibleSlide.removeClass('is-moving');
		});
		container.children('li').eq(n).addClass('selected from-right').prevAll().addClass('move-left');
		checkVideo(visibleSlide, container, n);
	}
	function prevSlide(visibleSlide, container, pagination, n){
		visibleSlide.removeClass('selected from-left from-right').addClass('is-moving').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
			visibleSlide.removeClass('is-moving');
		});
		container.children('li').eq(n).addClass('selected from-left').removeClass('move-left').nextAll().removeClass('move-left');
		checkVideo(visibleSlide, container, n);
	}
	function updateSliderNavigation(pagination, n) {
		var navigationDot = pagination.find('.selected');
		navigationDot.removeClass('selected');
		pagination.find('li').eq(n).addClass('selected');
	}
	function setAutoplay(wrapper,length,delay){
	 if(wrapper.hasClass('autoplay') && !isOnRegister && !IsfinishVideoNext){
			clearInterval(autoPlayId);
			autoPlayId = window.setInterval(function(){autoplaySlider(length)}, delay);
	 }
	}
	function autoplaySlider(length) {
		if( visibleSlidePosition < length - 1) {
			nextSlide(slidesWrapper.find('.selected'), slidesWrapper, sliderNav, visibleSlidePosition + 1);
			visibleSlidePosition +=1;
		} else {
			prevSlide(slidesWrapper.find('.selected'), slidesWrapper, sliderNav, 0);
			visibleSlidePosition = 0;
		}
		updateNavigationMarker(navigationMarker, visibleSlidePosition+1);
		updateSliderNavigation(sliderNav, visibleSlidePosition);
	}
	function uploadVideo(container) {
		container.find('.cd-bg-video-wrapper').each(function(){
			var videoWrapper = $(this);
			if( videoWrapper.is(':visible') ) {
				// if visible - we are not on a mobile device 
				var	videoUrl = videoWrapper.data('video'),
				videoImage = videoWrapper.data('image');
					video = $('<video onended="finishVideoNext()" controls preload><source src="'+videoUrl+'" type="video/mp4" /></video><div class="cd-hero-slider-video-img" style="background-image:url('+videoImage+');"></div>');
				video.appendTo(videoWrapper);
				// play video if first slide
				if(videoWrapper.parent('.cd-bg-video.selected').length > 0) {
					 if(!Modernizr.touch){
							 scriptJquery(video).find('video').show();
							 video.get(0).play();
							 firstVideoSrc = true;
					 }else{
							scriptJquery(video).eq(0).hide();
							clearInterval(autoPlayId);
							autoPlayId = window.setInterval(function(){autoplaySlider(length)}, autoPlayDelay);
					 }
				};
			}
		});
	}
	function checkVideo(hiddenSlide, container, n) {
		//check if a video outside the viewport is playing - if yes, pause it
		var hiddenVideo = hiddenSlide.find('video');
		if( hiddenVideo.length > 0 ) hiddenVideo.get(0).pause();
		//check if the select slide contains a video element - if yes, play the video
		var visibleVideo = container.children('li').eq(n).find('video');
		if( visibleVideo.length > 0 ) { 
			clearInterval(autoPlayId);
			clearInterval(clearIntvalSesVideoSlideshow2);
			clearInterval(clearIntvalSesVideoSlideshow1);
			if(!Modernizr.touch){
					scriptJquery(visibleVideo).show();
					visibleVideo.get(0).play();
			 }else{
					scriptJquery(visibleVideo).hide();
					clearInterval(autoPlayId);
					autoPlayId = window.setInterval(function(){autoplaySlider(length)}, autoPlayDelay);
			}
		}else{
			IsfinishVideoNext = false;
		}
	}
	function updateNavigationMarker(marker, n) {
		marker.removeClassPrefix('item').addClass('item-'+n);
	}
	$.fn.removeClassPrefix = function(prefix) {
		//remove all classes starting with 'prefix'
	    this.each(function(i, el) {
	        var classes = el.className.split(" ").filter(function(c) {
	            return c.lastIndexOf(prefix, 0) !== 0;
	        });
	        el.className = $.trim(classes.join(" "));
	    });
	    return this;
	};
	
});
