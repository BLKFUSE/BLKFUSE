/* $Id:updates_notifications.js  2017-01-12 00:00:00 SocialEngineSolutions $*/

var showNotifications;
scriptJquery(document).ready(function() {
    showNotifications = function() {
    en4.activity.updateNotifications();
    abortRequest = scriptJquery.ajax({
      'url' : en4.core.baseUrl + 'sesadvancedactivity/notifications/pulldown',
      'data' : {
        'format' : 'html',
        'page' : 1
      },
      success : function( responseHTML) {
        if( responseHTML ) {
          // hide loading iconsignup
          if(document.getElementById('notifications_loading')) scriptJquery('#notifications_loading').css('display', 'none');

          document.getElementById('notifications_menu').innerHTML = responseHTML;
          scriptJquery(document).on('click','#notifications_menu', function(event){
            event.stop(); //Prevents the browser from following the link.

            var current_link = event.target;
            var notification_li = scriptJquery(current_link).closest('li');

            // if this is true, then the user clicked on the li element itself
            if( notification_li.id == 'core_menu_mini_menu_update' ) {
              notification_li = current_link;
            }

            var forward_link;
            if( current_link.get('href') ) {
              forward_link = current_link.get('href');
            } else{
              forward_link = scriptJquery(current_link).find('a:last-child').attr('href');
            }

            if( notification_li.get('class') == 'notifications_unread' ){
              notification_li.removeClass('notifications_unread');
              (scriptJquery.ajax({
                url : en4.core.baseUrl + 'activity/notifications/markread',
                data : {
                  format     : 'json',
                  'actionid' : notification_li.get('value')
                },
                success : function() {
                  window.location = forward_link;
                }
              }));
            } else {
              window.location = forward_link;
            }
          });
        } else {
          document.getElementById('notifications_loading').innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate("You have no new updates."));?>';
        }
        document.getElementById('notification_count_new').innerHTML = '';
        document.getElementById('notification_count_new').removeClass('sm_minimenu_count');
      }
    });  
    
  }
});