scriptJquery(document).on('click','.sescontest_likefavfollow',function(){
	sescontest_likefavourite_data(this,'sescontest_likefavfollow');
});
scriptJquery(document).on('click','.selectvoting',function(){
   var elem = scriptJquery(this);
   var parent = scriptJquery(this).parent();
   var value = parent.attr('rel'); 
   scriptJquery(parent).find('._votebtn').show();
   scriptJquery('#submitdatavalue').val(scriptJquery('#submitdatavalue').val() + value+' ');
});
scriptJquery(document).on('click','.deselectvoting',function(){
   var elem = scriptJquery(this);
   var parent = scriptJquery(this).parent();
   var value = parent.attr('rel');
   scriptJquery(elem).hide();
   scriptJquery('#submitdatavalue').val(scriptJquery('#submitdatavalue').val().replace(value+' ',''));
});
//common function for like comment ajax
function sescontest_likefavourite_data(element) {
    if (!scriptJquery(element).attr('data-type'))
		return;
    var clickType = scriptJquery(element).attr('data-type');
    var functionName;
    var itemType;
    var contentId;
    var classType;
    var canIntegrate = 0;
    if(clickType == 'like_entry_view') {
      canIntegrate = scriptJquery(element).attr('data-integrate');
      functionName = 'like';
      itemType = 'participant';
      var contentId = scriptJquery(element).attr('data-url');
      var elementId = '.sescontest_entry_like_'+contentId;
      if(scriptJquery(elementId).hasClass('button_active')) {
        scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html())-1);
      }
      else {
        scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html())+1);
      }
    }
    else if(clickType == 'favourite_entry_view') {
      functionName = 'favourite';
      itemType = 'participant';
      var contentId = scriptJquery(element).attr('data-url');
      var elementId = '.sescontest_entry_favourite_'+contentId;
      if(scriptJquery(elementId).hasClass('button_active')) {
        scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html())-1);
      }
      else {
        scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html())+1);
      }
    }
    else if(clickType == 'like_view') {
      functionName = 'like';
      itemType = 'contest';
      var contentId = scriptJquery(element).attr('data-url');
      var elementId = '.sescontest_like_'+contentId;
      if(scriptJquery(elementId).hasClass('button_active')) {
        scriptJquery(elementId).attr('title','Like');
        scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html())-1);
      }
      else {
        scriptJquery(elementId).attr('title','Unlike');
        scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html())+1);
      }
    }
    else if(clickType == 'like_contest_button_view') {
      classType = 'secontest_like_contest_view';
      functionName = 'like';
      itemType = 'contest';
      contentId = scriptJquery(element).attr('data-url');
      var elementId = '.sescontest_like_'+contentId;
      if (scriptJquery(element).data('status') == 1) {
        scriptJquery('.sescontest_like_view_'+contentId).html('<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate("Like")+'</span>');
        scriptJquery('.sescontest_like_contest_view').data("status",0);
      }
      else {
        scriptJquery('.sescontest_like_view_'+contentId).html('<i class="fa fa-thumbs-down"></i><span>'+en4.core.language.translate("Unlike")+'</span>');
        scriptJquery('.sescontest_like_contest_view').data("status",1);
      }
    }
    else if(clickType == 'favourite_view') {
      functionName = 'favourite';
      itemType = 'contest';
      contentId = scriptJquery(element).attr('data-url');
      var elementId = '.sescontest_favourite_'+contentId;

      if(scriptJquery(elementId).hasClass('button_active')) {
        scriptJquery(elementId).attr('title','Add to Favourite');
        scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html())-1);
      }
      else {
        scriptJquery(elementId).attr('title','Remove as Favourite');
        scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html())+1);
      }
    }
    else if(clickType == 'favourite_contest_button_view') {
      classType = 'secontest_favourite_contest_view';
      functionName = 'favourite';
      itemType = 'contest';
      contentId = scriptJquery(element).attr('data-url');
      var elementId = '.sescontest_favourite_'+contentId;
      if(scriptJquery(element).data('status') == 1) {
        scriptJquery('.sescontest_favourite_view_'+contentId).html('<i class="fa fa-heart"></i><span>'+en4.core.language.translate("Add to Favorites")+'</span>');
        scriptJquery('.secontest_favourite_contest_view').data("status",0);
      }
      else {
        scriptJquery('.sescontest_favourite_view_'+contentId).html('<i class="far fa-heart"></i><span>'+en4.core.language.translate("Favorited")+'</span>');
        scriptJquery('.secontest_favourite_contest_view').data("status",1);
      }
    }
    else if(clickType == 'follow_view') {
      functionName = 'follow';
      itemType = 'contest';
      contentId = scriptJquery(element).attr('data-url');
      var elementId = '.sescontest_follow_'+contentId;

      if(scriptJquery(elementId).hasClass('button_active')) {
        scriptJquery(elementId).attr('title','Follow');
        scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html())-1);
      }
      else {
        scriptJquery(elementId).attr('title','Unfollow');
        scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html())+1);
      }
    }
    else if(clickType == 'follow_contest_button_view') {
      classType = 'secontest_follow_contest_view';
      functionName = 'follow';
      itemType = 'contest';
      contentId = scriptJquery(element).attr('data-url');
      var elementId = '.sescontest_follow_'+contentId;
      if(scriptJquery(element).data('status') == 1) {
        scriptJquery('.sescontest_follow_view_'+contentId).html('<i class="fa fa-check"></i><span>'+en4.core.language.translate("Follow")+'</span>');
        scriptJquery('.sescontest_follow_contest_view').data("status",0);
      }
      else {
        scriptJquery('.sescontest_follow_view_'+contentId).html('<i class="fa fa-times"></i><span>'+en4.core.language.translate("UnFollow")+'</span>');
        scriptJquery('.sescontest_follow_contest_view').data("status",1);
      }
    }
	if (!scriptJquery(element).attr('data-url'))
		return;
    
	if (scriptJquery(element).hasClass('button_active')) {
		scriptJquery(element).removeClass('button_active');
	} else
		scriptJquery(element).addClass('button_active');
	(scriptJquery.ajax({
		method: 'post',
		'url': en4.core.baseUrl + 'sescontest/ajax/' + functionName,
		'data': {
          format: 'html',
          id: contentId,
          type: itemType,
          integration:canIntegrate,
		},
		success: function(responseHTML) {
			var response = jQuery.parseJSON(responseHTML);
			if (response.error)
				alert(en4.core.language.translate('Something went wrong,please try again later'));
			else {
              scriptJquery(elementId).find('span').html(response.count);
              if (response.condition == 'reduced') {
                scriptJquery(elementId).removeClass('button_active');
              } 
              else {
                scriptJquery (elementId).addClass('button_active');
              }
			}
            if(canIntegrate == 1 && response.vote_status) {
              scriptJquery('#sescontest_vote_button_'+contentId).html('<i class="far fa-hand-point-up"></i><span>Voted</span>');
              scriptJquery('#sescontest_vote_button_'+contentId).addClass('disable');
            }
		  return true;
		}
	}));
}

(function(){
  
	const DateFn = {
    getFluentTimeSinceContest : function(now, ref) {
      //var ref = this;
      var val;
      if( !now ) now = new Date();
			var server = new Date(now);
			var client = new Date();
			var serverOffset = server - client;
		
      var deltaNormal = (ref - now - serverOffset) / 1000;
      //var deltaNormal = (now - ref + serverOffset) / 1000;
      var delta = Math.abs(deltaNormal);
      var isPlus = (deltaNormal > 0);
      
      var distance = new Date(ref).getTime() - now.getTime();
      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
      return [days,hours,minutes,seconds];
    },
    getFluentTimeSinceContestMiddle : function(now, ref)
    {
      //var ref = this;
      var val;
      if( !now ) now = new Date();
			var server = new Date(now);
			var client = new Date();
			var serverOffset = server - client;
		
      var deltaNormal = (ref - now - serverOffset) / 1000;
      //var deltaNormal = (now - ref + serverOffset) / 1000;
      var delta = Math.abs(deltaNormal);
      var isPlus = (deltaNormal > 0);
      
      var distance = new Date(ref).getTime() - now.getTime();
      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
      if(days <=0 && hours <=0 && minutes <=0 && seconds <=0) {
        return "false";
      }
      return [days,hours,minutes,seconds];
    }
  }
  
		scriptJquery( window ).load(function() {
			setInterval(function(){
				var now = new Date();
				scriptJquery('.sescontest-timestamp-middle').each(function(index, element){
					var ref = new Date(element.title);
					var newStamp = DateFn.getFluentTimeSinceContestMiddle(now, ref);
					if(typeof newStamp == "string"){
						scriptJquery(element).parent().parent().parent().parent().hide();
					}else{
						scriptJquery(element).parent().parent().parent().show();
						var obj = scriptJquery(element).parent().parent().find('.time_circles');
						obj.find('.textDiv_Days').find('span').html(newStamp[0]);
						obj.find('.textDiv_Hours').find('span').html(newStamp[1]);
						obj.find('.textDiv_Minutes').find('span').html(newStamp[2]);
						obj.find('.textDiv_Seconds').find('span').html(newStamp[3]);
					}
				});
			}, 1000);
		});
		
		scriptJquery( window ).load(function() {
			setInterval(function(){
				var now = new Date();
				scriptJquery('.sescontest-timestamp-update').each(function(index,element){
					var ref = new Date(element.title);
					var newStamp = DateFn.getFluentTimeSinceContest(now, ref);
					var obj = scriptJquery(element).closest('.countdown-contest');
					
					if(newStamp[0] <= 0 && newStamp[1] <= 0 && newStamp[2] <= 0 && newStamp[3] <= 0) {
						var obj = scriptJquery(element).closest('.sescontest_countdown_mini');
						if(obj.length <= 0) {
							obj = scriptJquery(element).closest('.sescontest_countdown_view');
						}
						obj.find('.countdown-contest').hide();
						obj.find('.finish-message').show();
					}
					obj.find('.day').html(newStamp[0]);
					obj.find('.hour').html(newStamp[1]);
					obj.find('.minute').html(newStamp[2]);
					obj.find('.second').html(newStamp[3]);
				});
			}, 1000);
		});
  })();


  function categorySlider(autoplay, obj, arow, centerMode,infinite) {
    sesBasicAutoScroll(obj).slick({
      infinite: infinite,
      autoplay:autoplay,
      arrows: arow,
      dots: true,
      slidesToShow: 1,
      variableWidth: true,
      slidesToScroll: 1,
      dots:false,
      centerMode: centerMode,
    });
    scriptJquery(obj).removeClass('contest_carousel');
  }
  function makeSlidesObject() {
   var elm = scriptJquery('.contest_carousel');
    for(i=0; i<elm.length;i++) {
      var autoPlay  = false;
      var infinite  = false;
      var arow = false;
      var centerMode = false;
      var width = scriptJquery(elm[i]).data('width');
     if(scriptJquery(elm[i]).attr('rel')*width > scriptJquery(elm[i]).width()) {
       autoPlay = false;
       arow = true;
       centerMode = true;
       infinite  = true;
     }
     categorySlider(autoPlay, scriptJquery(elm[i]),arow, centerMode,infinite);
    }   
  }
  scriptJquery(document).on('ready', function() {
   makeSlidesObject();
  });
	
	// Category Slideshow
	function categorySlidshow(autoplay, obj, arow, centerMode) {
    sesBasicAutoScroll(obj).slick({
      infinite: false,
	  centerPadding:'0px',
      autoplay:false,
      arrows: arow,
      dots: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      dots:false,
    })
  }
  scriptJquery(document).on('ready', function() {
    var elm = scriptJquery('.sescontest_category_slideshow');
    for(i=0; i<elm.length;i++) {
      var autoPlay  = false;
      var arow = false;
      var centerMode = false;
	  var width = scriptJquery(elm[i]).data('width');
      if(scriptJquery(elm[i]).attr('rel') > 1) {
        autoPlay = true;
        arow = true;
      }
      categorySlidshow(autoPlay, scriptJquery(elm[i]),arow, centerMode);
    }
  });
	
//open sidebar share buttons
scriptJquery(document).on('click','.sescontest_sidebar_option_btn',function(){
	if(scriptJquery(this).hasClass('open')){
		scriptJquery(this).removeClass('open');
	}else{
		scriptJquery('.sescontest_sidebar_option_btn').removeClass('open');
		scriptJquery(this).addClass('open');
	}
		return false;
});
scriptJquery(document).click(function(){
	scriptJquery('.sescontest_sidebar_option_btn').removeClass('open');
});

function openURLinSmoothBox(openURLsmoothbox){
  Smoothbox.open(openURLsmoothbox);
  parent.Smoothbox.close;
  return false;
}

