//Tooltip code for verification icon
scriptJquery(document).on('mouseover mouseout', '.sesbasic_verify_tip', function(event) {
  scriptJquery(this).tooltipster({
        interactive: true,
        content: '',
        contentCloning: false,
        contentAsHTML: true,
        animation: 'fade',
        updateAnimation:false,
        functionBefore: function(origin, continueTooltip) {
            //get attr
            if(typeof scriptJquery(origin).attr('data-rel') == 'undefined')
                var guid = scriptJquery(origin).attr('data-src');
            else
                var guid = scriptJquery(origin).attr('data-rel');

            // we'll make this function asynchronous and allow the tooltip to go ahead and show the loading notification while fetching our data.
            

            if(typeof scriptJquery(this).parent().find('.sesbasic_member_verification_tip').html() == 'undefined') {
              var data = "<div class='sesbasic_member_verification_tip'>"+scriptJquery(this).parent().parent().find('.sesbasic_member_verification_tip').html()+"</div>";
            } else {
              var data = "<div class='sesbasic_member_verification_tip'>"+scriptJquery(this).parent().find('.sesbasic_member_verification_tip').html()+"</div>";
            }
            if(scriptJquery(data).children().length){
               origin.tooltipster('content', data);
               continueTooltip();
            }
        },
	});
	scriptJquery(this).tooltipster('show');
});

//option show hide code
scriptJquery(document).mouseup(function (e)
{
  var container = scriptJquery(".sesact_pulldown_wrapper");
  if (!container.is(e.target) // if the target of the click isn't the container...
      && container.has(e.target).length === 0) // ... nor a descendant of the container
  {
    container.removeClass('sesact_pulldown_active');
    //container.hide();
  }else if(scriptJquery(e.target).hasClass('sesact_pulldown_wrapper') || scriptJquery(e.target).closest('.sesact_pulldown_wrapper').length){
      if(scriptJquery(e.target).hasClass('sesact_pulldown_wrapper')){
        if( scriptJquery(e.target).hasClass('sesact_pulldown_active'))
          scriptJquery(e.target).removeClass('sesact_pulldown_active');
        else{
          container.removeClass('sesact_pulldown_active');
          scriptJquery(e.target).addClass('sesact_pulldown_active');
        }
      }else{
        if( scriptJquery(e.target).closest('.sesact_pulldown_wrapper').hasClass('sesact_pulldown_active'))
          scriptJquery(e.target).closest('.sesact_pulldown_wrapper').removeClass('sesact_pulldown_active');
        else{
          container.removeClass('sesact_pulldown_active');
          scriptJquery(e.target).closest('.sesact_pulldown_wrapper').addClass('sesact_pulldown_active');
        }
      }
  }
});
//tooltip code
var sestooltipOrigin;
scriptJquery(document).on('mouseover mouseout', '.ses_tooltip', function(event) {
	if(sesbasicdisabletooltip)
		return false;

	scriptJquery(this).tooltipster({
					interactive: true,
					content: '<div class="sesbasic_tooltip_loading">Loading...</div>',
					contentCloning: false,
					contentAsHTML: true,
					animation: 'fade',
					updateAnimation:false,
					functionBefore: function(origin, continueTooltip) {
						//get attr
						if(typeof scriptJquery(origin).attr('data-rel') == 'undefined')
							var guid = scriptJquery(origin).attr('data-src');
						else
							var guid = scriptJquery(origin).attr('data-rel');
							// we'll make this function asynchronous and allow the tooltip to go ahead and show the loading notification while fetching our data.
							continueTooltip();
						  sestooltipOrigin = scriptJquery(this);
							if (origin.data('ajax') !== 'cached') {
                  if (!snscache.has(guid)) {
                      scriptJquery.ajax({
                            type: 'POST',
                            url: en4.core.baseUrl+'sesbasic/tooltip/index/guid/'+guid,
                            success: function(data) {
                              snscache.set(guid, data);
                              origin.tooltipster('content', snscache.get(guid)).data('ajax', 'cached');
                            }
                      });
                  } else {
                    origin.tooltipster('content', snscache.get(guid)).data('ajax', 'cached');
                  }
							}
					}
	});
	scriptJquery(this).tooltipster('show');
});
let snscache = new Map();

scriptJquery(document).on('change','#myonoffswitch',function(){
	ses_view_adultContent();
})
//adult content switch
var isActiveRequest;
function ses_view_adultContent(){
	var	url = en4.core.baseUrl+'sesbasic/index/adult/';
	var isActiveRequest =	(scriptJquery.ajax({
      method: 'post',
      'url': url,
      'data': {
        format: 'html',
      },
      success: function(responseHTML) {
        //keep Silence
				location.reload();
      }
    }));
		
}

function socialSharingPopUp(url,title, saveURL, type, showCount){

  //if(1) {
    var	urlsave = en4.core.baseUrl+'sessocialshare/index/savesocialsharecount/';
    var socialShareCountSave =	(scriptJquery.ajax({
        method: 'post',
        'url': urlsave,
        'data': {
          title: title,
          pageurl: saveURL,
          type: type,
          format: 'html',
        },
        success: function(responseHTML) {
          //keep Silence
          //location.reload();
          if(showCount == 1) {
            var countType = scriptJquery('.sessocialshare_count_'+type).html();
            scriptJquery('.sessocialshare_count_'+type).html(++countType);
          }
        }
    }));
    

  //}

  if(type) {
    if(title == 'Facebook')
      url = url; //+encodeURI('%26fbrefresh%3Drefresh', '_blank');
    if(title == 'Google')
      window.open(url, '_blank');
    else
      window.open(url, '_blank');
  } else {
    if(title == 'Facebook')
      url = url; //+encodeURI('%26fbrefresh%3Drefresh');
    if(title == 'Google')
      window.open(url, title ,'height=500,width=850');
    else
      window.open(url, title ,'height=500,width=800');
  }
	return false;
}
function openSmoothBoxInUrl(url){
	Smoothbox.open(url);
	parent.Smoothbox.close;
	return false;
}
//send quick share link
function sesAjaxQuickShare(url){
	if(!url)
		return;
	scriptJquery('.sesbasic_popup_slide_close').trigger('click');
	(scriptJquery.ajax({
      method: 'post',
      'url': url,
      'data': {
        format: 'html',
				is_ajax : 1
      },
      success: function(responseHTML) {
        //keep Silence
        showTooltipSesbasic('10','10','<i class="fa fa-envelope"></i><span>'+(en4.core.language.translate("Quick share successfully "))+'</span>','sesbasic_message_notification');
      }
    }));
}
//make href in tab container
function tabContainerHrefSesbasic(tabId){
	if(scriptJquery('#main_tabs').length){
		var tab = scriptJquery('#main_tabs').find('.tab_'+tabId);
		if(tab.length){
			var hrefTab = window.location.href;
			var queryString = '';
			if(hrefTab.indexOf('?') > 0){
				var splitStringQuery = hrefTab.split('?');
				hrefTab = splitStringQuery[0];
				if(typeof splitStringQuery[1] != 'undefined'){
					queryString = '?'+splitStringQuery[1];
				}
			}
			if(hrefTab.indexOf('/tab/') > 0){
				hrefTab = hrefTab.split('/');
				hrefTab.pop();
				hrefTab.pop();
				hrefTab = hrefTab.join('/');
			}
			hrefTab = hrefTab+'/tab/'+tabId+queryString
			tab.find('a').attr('href',hrefTab);
			var clickElem = tab.find('a').attr('onclick')+';return false;';
			tab.find('a').attr('onclick',clickElem);
		}
	}
}
//content like, favourite, rated and follow auto tooltip from left bottom.
function showTooltipSesbasic(x, y, contents, className) {
	if(scriptJquery('.sesbasic_notification').length > 0)
		scriptJquery('.sesbasic_notification').hide();
		scriptJquery('<div class="sesbasic_notification '+className+'">' + contents + '</div>').css( {
		display: 'block',
	}).appendTo("body").fadeOut(5000,'',function(){
		scriptJquery(this).remove();
	});
}


scriptJquery(document).on('click','.sesbasic_favourite_sesbasic_video',function(){
  like_favourite_data_photo(this,'favourite',itemType,'<i class="fa fa-heart"></i><span>'+(en4.core.language.translate("Video added as Favourite successfully"))+'</span>','<i class="fa fa-heart"></i><span>'+(en4.core.language.translate("Video Unfavourited successfully"))+'</span>','sesbasic_favourites_notification');
});


scriptJquery(document).on('click','.sesbasic_favourite_sesbasic_photo',function(){
  like_favourite_data_photo(this,'favourite','','','<i class="fa fa-heart"></i><span>'+(en4.core.language.translate("Photo added as Favourite successfully"))+'</span>','<i class="fa fa-heart"></i><span>'+(en4.core.language.translate("Photo Unfavourited successfully"))+'</span>','sesbasic_favourites_notification');
});

//common function for like comment ajax
function like_favourite_data_photo(element,functionName,itemType,moduleName,likeNoti,unLikeNoti,className){

		if(!scriptJquery(element).attr('data-url'))
			return;
		if(scriptJquery(element).hasClass('button_active')){
				scriptJquery(element).removeClass('button_active');
		}else
				scriptJquery(element).addClass('button_active');

    var URL = en4.core.baseUrl + moduleName+'/index/'+functionName;
    if(itemType) {
      itemType = itemType;
    } else {
      itemType = scriptJquery(element).attr('data-type');
      if(itemType == 'sespage_photo') {
        moduleName = 'sespage';
        var URL = en4.core.baseUrl + moduleName+'/ajax/'+functionName;
      }
    }
		 (scriptJquery.ajax({
      method: 'post',
      'url':  URL,
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


scriptJquery(document).on('click','.openSmoothbox',function(e){
  var url = scriptJquery(this).attr('href');
  openSmoothBoxInUrl(url);
  return false;
});
scriptJquery(document).on('click','.opensmoothboxurl',function(e){
  var url = scriptJquery(this).attr('href');
  openSmoothBoxInUrl(url);
  return false;
});
//open url in smoothbox
function opensmoothboxurl(openURLsmoothbox){
  openSmoothBoxInUrl(openURLsmoothbox);
	return false;
}

scriptJquery(document).on('click','#sesbasic_btn_currency',function(){
	if(scriptJquery(this).hasClass('active')){
		scriptJquery(this).removeClass('active');
		scriptJquery('#sesbasic_currency_change').hide();
	}else{
		scriptJquery(this).addClass('active');
		scriptJquery('#sesbasic_currency_change').show();
	}
});
//currency change
scriptJquery(document).on('click','ul#sesbasic_currency_change_data li > a',function(){
	var currencyId = scriptJquery(this).attr('data-rel');
	setSesCookie('sesbasic_currencyId',currencyId,365);
	location.reload();
});
function setSesCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = "expires="+d.toGMTString();
	document.cookie = cname + "=" + cvalue + "; " + expires+';path=/;';
}

// Option Pulldown
scriptJquery(document).on('click','.sesbasic_pulldown_toggle',function(){
	if(scriptJquery(this).hasClass('showpulldown')){
		scriptJquery(this).removeClass('showpulldown');
	}else{
		scriptJquery('.sesbasic_pulldown_toggle').removeClass('showpulldown');
		scriptJquery(this).addClass('showpulldown');
	}
		return false;
});
scriptJquery(document).click(function(){
	scriptJquery('.sesbasic_pulldown_toggle').removeClass('showpulldown');
});


// light box like work
scriptJquery(document).on('click','#sesbasicLightboxLikeUnlikeButton',function() {
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

scriptJquery(document).on("contextmenu",".smoothbox",function(){
    return false;
});
