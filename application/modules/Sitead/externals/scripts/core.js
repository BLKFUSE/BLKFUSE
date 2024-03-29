en4.sitead = {
  sendReq: function(container, content_id, isAdboardPage, requestParams) {
    var url = en4.core.baseUrl + 'widget';
    var params = {
      format: 'html',
      is_ajax_load: 1,
      subject: en4.core.subject.guid,
      isAdboardPage: isAdboardPage
    };
    if (!content_id) {
      url = en4.core.baseUrl + 'widget/index/mod/communityad/name/ads';
    } else {
      params.content_id = content_id;
    }
    if (requestParams)
      params = $merge(requestParams, params);
    var request = new Request.HTML({
      url: url,
      method: 'get',
      data: params,
      onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript) {
        if (!responseHTML.trim()) {
          container.destroy();
          return;
        }
        container.empty();
        Elements.from(responseHTML).inject(container);
        en4.core.runonce.trigger();
        Smoothbox.bind(container);
      }
    });
    request.send();
  }
};
var sitead_likeinfo = function(ad_id, resource_type, resource_id, owner_id, widgetType, core_like) {
      // SENDING REQUEST TO AJAX
      var request = createLike(ad_id, resource_type, resource_id, owner_id, widgetType, core_like);
      // RESPONCE FROM AJAX
      request.addEvent('complete', function(responseJSON) {
        if (responseJSON.like_id)
        {
          $(widgetType + '_likeid_info_' + ad_id).value = responseJSON.like_id;
          $(resource_type + '_' + widgetType + '_most_likes_' + ad_id).style.display = 'none';
          $(resource_type + '_' + widgetType + '_unlikes_' + ad_id).style.display = 'block';
        }
        else
        {
          $(widgetType + '_likeid_info_' + ad_id).value = 0;
          $(resource_type + '_' + widgetType + '_most_likes_' + ad_id).style.display = 'block';
          $(resource_type + '_' + widgetType + '_unlikes_' + ad_id).style.display = 'none';
        }
      });
    } 
/* $Id: core.js 2011-02-16 9:40:21Z SocialEngineAddOns Copyright 2009-2011 BigStep Technologies Pvt. Ltd. $ */

	// Use: Ads Display.
// Function Call: When click on cross of any advertisment.
function adReport(div_id, widgetType) {
  $(widgetType + '_ad_cancel_' + div_id).style.display = 'block';
}

// Use: Ads Display.
// Function Call: After click on cross of any ads then show option of 'undo' if click on the 'undo'.
function adReportUndo(div_id, widgetType) {
  $(widgetType + '_ad_cancel_' + div_id).style.display = 'none';
  if ($(widgetType + '_other_' + div_id).checked) {
    $(widgetType + '_other_' + div_id).checked = false;
    $(widgetType + '_other_text_' + div_id).style.display = 'none';
    $(widgetType + '_other_text_' + div_id).value = 'Type your reason here...';
    $(widgetType + '_other_button_' + div_id).style.display = 'none';
  }
}

// Use: Ads Display.
// Function Call: After click on cross of any ads then show radio button if click on 'other' type radio button.
function otherAdReportCannel(adRadioValue, div_id, widgetType) {
  // Condition: When click on 'other radio button'.
  if (adRadioValue == 4) {
    $(widgetType + '_other_text_' + div_id).style.display = 'block';
    $(widgetType + '_other_button_' + div_id).style.display = 'block';
  }
}

// Use: Ads Display
// Function Call: When save entry in data base.
function adReportSave(adCancelReasion, adsId, divId, widgetType) {
  var adDescription = 0;
  // Condition: Find out 'Description' if select other options from radio button.

  if (adCancelReasion == 'Other') {
    if ($(widgetType + '_other_text_' + divId).value != 'Type your reason here...') {
      adDescription = $(widgetType + '_other_text_' + divId).value;
    }
  }
  $(widgetType + '_ad_cancel_' + divId).innerHTML = '<center><img src="application/modules/Seaocore/externals/images/core/loading.gif" alt=""></center>';
  en4.core.request.send(new Request.HTML({
    url: en4.core.baseUrl + 'sitead/display/adsave',
    data: {
      format: 'html',
      adCancelReasion: adCancelReasion,
      adDescription: adDescription,
      adsId: adsId
    }
  }), {
    'element': $(widgetType + '_ad_cancel_' + divId)
  })
}
	
	// Function: For 'Advertisment' liked or unliked.
	function createLike( ad_id, resource_type, resource_id, owner_id, widgetType, core_like )
	{
		var like_id = $(widgetType + '_likeid_info_'+ ad_id).value;
		var request = new Request.JSON({    
			url : en4.core.baseUrl + 'sitead/display/globallikes',
			data : {
				format : 'json',
				'ad_id' : ad_id,
				'resource_type' : resource_type,	
				'resource_id' : resource_id,
				'owner_id' : owner_id,
				'like_id' : like_id,
				'core_like' : core_like
			}
		});  
		request.send();
		return request;
	}
