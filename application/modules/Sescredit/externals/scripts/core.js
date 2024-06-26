scriptJquery(document).on('submit', '#sescreditupgrademember',function(e) {
  if(scriptJquery('.selectupgrademember:checked').length == 0) {
   alert('Please select atleast one member level.');
   return;
  }
  e.preventDefault();
  var formData = new FormData(this);
  var jqXHR=scriptJquery.ajax({
    url: en4.core.baseUrl +"sescredit/index/show-member-level",
    type: "POST",
    contentType:false,
    processData: false,
    data: formData,
    success: function(response){
      response = scriptJquery.parseJSON(response);
      if(response.status == 'true') {
        scriptJquery('#show_upgrade_option').html('You have already sent request for membership upgrade.');
		scriptJquery('#sessmoothbox_container').html("<div id='sespage_contact_message' class='sespage_contact_popup sesbasic_bxs'><div class='sesbasic_tip clearfix'><img src='application/modules/Sespage/externals/images/success.png' alt=''><span>You have sent upgrade request Successfully</span></div></div>");
      	scriptJquery('.sessmoothbox_overlay').fadeOut(3000, function(){sessmoothboxclose();});
      }
    }
  });
  return false;
});

//Badge Tooltip
var sestooltipOrigin;
scriptJquery(document).on('mouseover mouseout', '.sescredit_badge_tip_wrapper', function(event) {
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
        continueTooltip();
      var data = "<div class='sescredit_badge_tip sesbasic_bxs sesbasic_bg'>"+scriptJquery(this).parent().find('.sescredit_badge_tip').html()+"<div>";
      origin.tooltipster('content', data).data('ajax', 'cached');

    }
  });
  scriptJquery(this).tooltipster('show');
});