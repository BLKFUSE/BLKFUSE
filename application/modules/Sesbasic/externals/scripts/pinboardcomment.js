/**
 * Comments
 */
 function sesbasic_like(type, id, comment_id,widget_id){
		 (scriptJquery.ajax({
      dataType: 'json',
      method: 'post',
      url : en4.core.baseUrl + 'sesbasic/comment/like',
      'data': {
        format : 'json',
        item_type : type,
        item_id : id,
				widget_identity:widget_id,
        comment_id : comment_id
      },
      success: function(response, response2, response3, response4) {
				// Get response
				var htmlBody;
				var jsBody;
				if( $type(response) == 'object' ){ // JSON response
					htmlBody = response['body'];
				} else if( $type(response) == 'string' ){ // HTML response
					htmlBody = response3;
					jsBody = response4;
				}else{
						htmlBody = JSON.decode(response3);
				}
				// An error probably occurred
				if( !response && !response3 && $type('comments_'+id) ){
					en4.core.showError('An error has occurred processing the request. The target may no longer exist.');
					return;
				}
				if( $type(response) == 'object' && $type(response.status) && response.status == false )
				{
					en4.core.showError('An error has occurred processing the request. The target may no longer exist.' + '<br /><br /><button onclick="Smoothbox.close()">Close</button>');
					return;
				}
				document.getElementById('comments_'+id).innerHTML = htmlBody;
			if(widget_id)
				eval("pinboardLayout_"+widget_id+"('',true)");
				}
    }));
}
function sesbasic_unlike(type, id, comment_id,widget_id){
		 (scriptJquery.ajax({
       dataType: 'json',
      method: 'post',
      url : en4.core.baseUrl + 'sesbasic/comment/unlike',
      'data': {
        format : 'json',
        item_type : type,
				widget_identity:widget_id,
        item_id : id,
        comment_id : comment_id
      },
      success: function(response, response2, response3, response4) {
				// Get response
				var htmlBody;
				var jsBody;
				if( $type(response) == 'object' ){ // JSON response
					htmlBody = response['body'];
				} else if( $type(response) == 'string' ){ // HTML response
					htmlBody = response3;
					jsBody = response4;
				}else{
						htmlBody = JSON.decode(response3);
				}
				// An error probably occurred
				if( !response && !response3 && $type('comments_'+id) ){
					en4.core.showError('An error has occurred processing the request. The target may no longer exist.');
					return;
				}
				if( $type(response) == 'object' && $type(response.status) && response.status == false )
				{
					en4.core.showError('An error has occurred processing the request. The target may no longer exist.' + '<br /><br /><button onclick="Smoothbox.close()">Close</button>');
					return;
				}
				document.getElementById('comments_'+id).innerHTML = htmlBody;
				if(widget_id)
					eval("pinboardLayout_"+widget_id+"('',true)");
				}
    }));
}
function sesbasic_comment_submit(thisObj){
	var body = thisObj.elements[0].value;
	var type = thisObj.elements[1].value;
	var id = thisObj.elements[2].value;
	var widget_id = thisObj.elements[3].value;
	if(!body || !type || !id)
		return;	
		(scriptJquery.ajax({
      dataType: 'json',
      method: 'post',
      url : en4.core.baseUrl + 'sesbasic/comment/create',
       'data': {
        format : 'json',
        item_type : type,
				widget_identity:widget_id,
        item_id : id,
        body : body
      },
      success: function(response, response2, response3, response4) {
				// Get response
				var htmlBody;
				var jsBody;
				if( $type(response) == 'object' ){ // JSON response
					htmlBody = response['body'];
				} else if( $type(response) == 'string' ){ // HTML response
					htmlBody = response3;
					jsBody = response4;
				}else{
						htmlBody = JSON.decode(response3);
				}
				// An error probably occurred
				if( !response && !response3 && $type('comments_'+id) ){
					en4.core.showError('An error has occurred processing the request. The target may no longer exist.');
					return;
				}
				if( $type(response) == 'object' && $type(response.status) && response.status == false )
				{
					en4.core.showError('An error has occurred processing the request. The target may no longer exist.' + '<br /><br /><button onclick="Smoothbox.close()">Close</button>');
					return;
				}
				document.getElementById('comments_'+id).innerHTML = htmlBody;
				if(widget_id)
					eval("pinboardLayout_"+widget_id+"('',true)");
				}
    }));
}
function sesbasic_listcomment(type, id, page,widget_id){
		 (scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      url : en4.core.baseUrl + 'sesbasic/comment/list',
      'data': {
        format : 'html',
        item_type : type,
        item_id : id,
				widget_identity:widget_id,
        page_id : page
      },
      success: function(response3) {
				document.getElementById('comments_'+id).innerHTML = response3;
				if(widget_id)
					eval("pinboardLayout_"+widget_id+"('',true)");
				}
    }));

}
en4.core.sesbasiccomments = {
  deleteComment : function(type, id, comment_id,widget_id) {
    if( !confirm(en4.core.language.translate('Are you sure you want to delete this?')) ) {
      return;
    }
    (scriptJquery.ajax({
      dataType: 'json',
      url : en4.core.baseUrl + 'sesbasic/comment/delete',
      data : {
        format : 'json',
        item_type : type,
        item_id : id,
        comment_id : comment_id
      },
      success: function() {
        if( document.getElementById('comment-' + comment_id) ) {
          scriptJquery('#comment-' + comment_id).remove();
        }
        try {
          var commentCount = $$('.comments_options_'+id+' span')[0];
          var m = commentCount.get('html').match(/\d+/);
          var newCount = ( parseInt(m[0]) != 'NaN' && parseInt(m[0]) > 1 ? parseInt(m[0]) - 1 : 0 );
          commentCount.set('html', commentCount.get('html').replace(m[0], newCount));
        } catch( e ) {}
				if(widget_id)
					eval("pinboardLayout_"+widget_id+"('',true)");
      }
    }));
  }
};
function isEnterPressed(e,thisVar){
	if (e.which == 13 && !e.shiftKey){
		//stop cursor from going to new line.
		e.preventDefault();
		scriptJquery(thisVar).parent().submit();
		scriptJquery(thisVar).val('');
	}
}	
