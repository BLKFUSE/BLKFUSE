
scriptJquery(document).on('click', '.egifts_likefavourite', function () {
  egifts_likefavourite_data(this, 'egifts_likefavourite');
});
//common function for like comment ajax
function egifts_likefavourite_data(element) {
  if (!scriptJquery(element).attr('data-type'))
    return;
  var clickType = scriptJquery(element).attr('data-type');
  var functionName;
  var itemType;
  var contentId;
  var classType;
  var likeTrigger = false;
  if (clickType == 'egifts_like_view') {
    functionName = 'like';
    itemType = 'egifts_gift';
    var contentId = scriptJquery(element).attr('data-url');
    var elementId = '.egifts_like_' + contentId;
    if (scriptJquery(elementId).hasClass('btnactive')) {
      scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html()) - 1);
    } else {
      scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html()) + 1);
    }
  } else if (clickType == 'egifts_favourite_view') {
    functionName = 'favourite';
    itemType = 'egifts_gift';
    contentId = scriptJquery(element).attr('data-url');
    var elementId = '.egifts_favourite_' + contentId;
    if (scriptJquery(elementId).hasClass('btnactive')) {
      scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html()) - 1);
    } else {
      scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html()) + 1);
    }
  }
  if (!scriptJquery(element).attr('data-url'))
    return;
  if(scriptJquery(element).hasClass('btnactive')) {
    scriptJquery(elementId).each(function(){
      scriptJquery(this).removeClass('btnactive');
    });
  }else{
    scriptJquery(elementId).each(function(){
      scriptJquery(this).addClass('btnactive');
    });
  }
  
  (scriptJquery.ajax({
    method: 'post',
    dataType: 'html',
    url: en4.core.baseUrl + 'egifts/ajax/' + functionName,
    data: {
      format: 'html',
      id: contentId,
      type: itemType,
    },
    success: function (responseHTML) {
      var response = jQuery.parseJSON(responseHTML);
      if (response.error)
        alert(en4.core.language.translate('Something went wrong,please try again later'));
      else {
        if (response.condition == 'reduced') {
          if(functionName == 'like') {
            scriptJquery('.egifts_like_view_' + contentId).html('<span>' + en4.core.language.translate("Like") + '</span>');
            if(scriptJquery('.egifts_like_count_'+contentId)) {
              scriptJquery('.egifts_like_count_'+contentId).each(function(){
                scriptJquery(this).html(response.title);
                scriptJquery(this).attr('title',response.title);
              });
            }
          } 
          if(functionName == 'favourite') {
              if(scriptJquery('.egifts_favourite_count_'+contentId)) {
                scriptJquery('.egifts_favourite_count_'+contentId).each(function(){
                  scriptJquery(this).html(response.title);
                  scriptJquery(this).attr('title',response.title);
                });
              }  
          }
        } else {
          if(functionName == 'like') {
            if(scriptJquery('.egifts_like_count_'+contentId)) {
                scriptJquery('.egifts_like_count_'+contentId).each(function(){
                  scriptJquery(this).html(response.title);
                  scriptJquery(this).attr('title',response.title);
                });
            }
          }
          if(functionName == 'favourite') {
            if(scriptJquery('.egifts_favourite_count_'+contentId)) {
              scriptJquery('.egifts_favourite_count_'+contentId).each(function(){
                scriptJquery(this).html(response.title);
                scriptJquery(this).attr('title',response.title);
              });
            }
          }
        }
      }
      return true;
    }
  }));
}
