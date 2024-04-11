/* $Id: core.js 9984 2013-03-20 00:00:04Z john $ */

function groupWidgetRequestSend(action, group_id, notification_id) {
  
  var url;
  if( action == 'accept' )
  {
    url = en4.core.baseUrl + 'group/member/accept';
  }
  else if( action == 'reject' )
  {
    url = en4.core.baseUrl + 'group/member/reject';
  }
  else
  {
    return false;
  }

  (scriptJquery.ajax({
    'url' : url,
    'method': 'post',
    'data' : {
      'group_id' : group_id,
      'format' : 'json'
      //'token' : '<?php //echo $this->token() ?>'
    },
    success : function(responseJSON)
    {
      if( !responseJSON.status ) {
        document.getElementById('notifications_' + notification_id).innerHTML = '<div class="request_success">' + responseJSON.error + '</div>';
      } else {
        document.getElementById('notifications_' + notification_id).innerHTML = '<div class="request_success">' + responseJSON.message + '</div>';
      }
    }
  }));
}
