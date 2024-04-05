function showTooltipSesbasicMembership(x, y, contents, className) {
	if(scriptJquery('.sesbasic_notification').length > 0)
		scriptJquery('.sesbasic_notification').hide();
	scriptJquery('<div class="sesbasic_notification '+className+'">' + contents + '</div>').css( {
		display: 'block',
	}).appendTo("body").fadeOut(5000,'',function(){
		scriptJquery(this).remove();	
	});
}
var sesaddFriendRequest,sescancelFriendRequest, sesremoveFriend, sesacceptFriend;
scriptJquery(document).on('click', '.sesbasic_member_addfriend_request', function() {
    var sesthis = this;
		var data = {
      'user_id' : scriptJquery(this).attr('data-src'),
      'format' : 'html',
			'parambutton': scriptJquery(this).attr('data-rel'),
    };

	 data[scriptJquery(this).attr('data-tokenname')] = scriptJquery(this).attr('data-tokenvalue');
   sesaddFriendRequest =  (scriptJquery.ajax({
    url: en4.core.baseUrl + 'sesbasic/membership/add-friend',
    'data': data,
    success: function(responseHTML) {
			var result = scriptJquery.parseJSON(responseHTML);
			if(result.status == 1){
     		scriptJquery(sesthis).parent().html(result.message);
				showTooltip('10','10','<i class="fa fa-check-circle"></i><span>'+(en4.core.language.translate(result.tip))+'</span>','sesbasic_friend_request_notification');
			}
			else
				 en4.core.showError(en4.core.language.translate(result.message));
    }
  }));
  
});

scriptJquery(document).on('click', '.sesbasic_member_cancelfriend_request', function() {
  
    var sesthis = this;
		var data = {
      'user_id' : scriptJquery(this).attr('data-src'),
      'format' : 'html',
			'parambutton': scriptJquery(this).attr('data-rel'),
    };

		data[scriptJquery(this).attr('data-tokenname')] = scriptJquery(this).attr('data-tokenvalue');
    sescancelFriendRequest = (scriptJquery.ajax({
    url: en4.core.baseUrl + 'sesbasic/membership/cancel-friend',
    'data': data,
    success: function(responseHTML) {
     var result = scriptJquery.parseJSON(responseHTML);
			if(result.status == 1){
     		scriptJquery(sesthis).parent().html(result.message);
				showTooltip('10','10','<i class="fa fa-times-circle"></i><span>'+(en4.core.language.translate(result.tip))+'</span>','sesbasic_friend_remove_notification');
			}
			else
				 en4.core.showError(en4.core.language.translate(result.message));
    }
  }));
});

scriptJquery(document).on('click', '.sesbasic_member_removefriend_request', function() {
    var sesthis = this;
		var data = {
      'user_id' : scriptJquery(this).attr('data-src'),
      'format' : 'html',
			'parambutton': scriptJquery(this).attr('data-rel'),
    };

		data[scriptJquery(this).attr('data-tokenname')] = scriptJquery(this).attr('data-tokenvalue');
    sesremoveFriend = (scriptJquery.ajax({
    url: en4.core.baseUrl + 'sesbasic/membership/remove-friend',
    'data':data,
    success: function(responseHTML) {
    var result = scriptJquery.parseJSON(responseHTML);
			if(result.status == 1){
     		scriptJquery(sesthis).parent().html(result.message);
				showTooltip('10','10','<i class="fa fa-times-circle"></i><span>'+(en4.core.language.translate(result.tip))+'</span>','sesbasic_friend_remove_notification');
			}
			else
				 en4.core.showError(en4.core.language.translate(result.message));
    }
  }));
});

scriptJquery(document).on('click', '.sesbasic_member_acceptfriend_request', function() {
    var sesthis = this;
		var data = {
      'user_id' : scriptJquery(this).attr('data-src'),
      'format' : 'html',
			'parambutton': scriptJquery(this).attr('data-rel'),
    };

		data[scriptJquery(this).attr('data-tokenname')] = scriptJquery(this).attr('data-tokenvalue');
    sesacceptFriend = (scriptJquery.ajax({
    url: en4.core.baseUrl + 'sesbasic/membership/accept-friend',
    'data': data,
    success: function(responseHTML) {
    var result = scriptJquery.parseJSON(responseHTML);
			if(result.status == 1){
     		scriptJquery(sesthis).parent().html(result.message);
				showTooltip('10','10','<i class="fa fa-check-circle"></i><span>'+(en4.core.language.translate(result.tip))+'</span>','sesbasic_friend_request_notification');
			}
			else
				 en4.core.showError(en4.core.language.translate(result.message));
    }
  }));
});
