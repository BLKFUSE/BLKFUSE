en4.siteshare = {
  setLayoutWidth: function(elementId, width) {
    var layoutColumn = null;
    if ($(elementId).getParent('.layout_left')) {
      layoutColumn = $(elementId).getParent('.layout_left');
    } else if ($(elementId).getParent('.layout_right')) {
      layoutColumn = $(elementId).getParent('.layout_right');
    } else if ($(elementId).getParent('.layout_middle')) {
      layoutColumn = $(elementId).getParent('.layout_middle');
    }
    if (layoutColumn) {
      layoutColumn.setStyle('width', width);
    }
    $(elementId).destroy();
  }
};

en4.siteshare.socialService = {
  clickHandler: function (el) {
    var request = new Request.JSON({
      url: en4.core.baseUrl + 'siteshare/index/social-service-click',
      method: 'post',
      data: {
        format: 'json',
        shareUrl: $(el).get('data-url'),
        serviceType: $(el).get('data-service')
      },
      onSuccess: function(){
        
      }
    });
    request.send();
  }
};