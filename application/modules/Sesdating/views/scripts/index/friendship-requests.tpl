<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: friendship-requests.tpl 2016-11-22 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>

<script type="text/javascript">
  var userWidgetRequestSend = function(action, user_id, notification_id, event)
  { 
    event.stopPropagation();
    var url;
    if( action == 'confirm' ) {
      url = '<?php echo $this->url(array('module' => 'sesbasic', 'controller' => 'friends', 'action' => 'confirm'), 'default', true) ?>';
    } else if( action == 'reject' ) {
      url = '<?php echo $this->url(array('module' => 'sesbasic', 'controller' => 'friends', 'action' => 'reject'), 'default', true) ?>';
    } else if( action == 'add' ) {
      url = '<?php echo $this->url(array('module' => 'sesbasic', 'controller' => 'friends', 'action' => 'add'), 'default', true) ?>';
    } else {
      return false;
    }

    (scriptJquery.ajax({
      url : url,
      dataType: 'json',
      data : {
        'user_id' : user_id,
        'format' : 'json',
        'token' : '<?php echo $this->token() ?>'
      },
      success : function(responseJSON) {
        if( !responseJSON.status ) {
          if(document.getElementById('user-widget-request-' + notification_id))
            document.getElementById('user-widget-request-' + notification_id).innerHTML = responseJSON.error;
          if(document.getElementById('user_PeopleYouMayKnow_' + notification_id))
            document.getElementById('user_PeopleYouMayKnow_' + notification_id).innerHTML = responseJSON.error;
        } else {
          if(document.getElementById('user-widget-request-' + notification_id))
            document.getElementById('user-widget-request-' + notification_id).innerHTML = responseJSON.message;
          if(document.getElementById('user_PeopleYouMayKnow_' + notification_id))
            document.getElementById('user_PeopleYouMayKnow_' + notification_id).innerHTML = responseJSON.message;
        }
      }
    }));
  }
</script>

<?php if( $this->friendRequests->getTotalItemCount() > 0 ): ?>  
  <ul class='pulldown_content_list' id="notifications_main" onclick="redirectPage(event);">
    <?php foreach( $this->friendRequests as $notification ): ?>
    <?php $user = Engine_Api::_()->getItem('user', $notification->subject_id);?>
      <li id="user-widget-request-<?php echo $notification->notification_id ?>" class="clearfix <?php if( !$notification->read ): ?>pulldown_content_list_highlighted<?php endif; ?>"  value="<?php echo $notification->getIdentity();?>">
        <div class="pulldown_item_photo">
          <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon')) ?>
        </div>
        <div class="pulldown_item_content">
          <p class="pulldown_item_content_title">
            <?php echo $notification->__toString() ?>
          </p>
          <p class="pulldown_item_content_btns clearfix">
            <?php //echo $this->timestamp(strtotime($notification->date)) ?>
          <button type="submit" onclick='userWidgetRequestSend("confirm", <?php echo $this->string()->escapeJavascript($notification->getSubject()->getIdentity()) ?>, <?php echo $notification->notification_id ?>, event)'>
            <?php echo $this->translate('Add Friend');?>
          </button>
          <button class="button button_alt" onclick='userWidgetRequestSend("reject", <?php echo $this->string()->escapeJavascript($notification->getSubject()->getIdentity()) ?>, <?php echo $notification->notification_id ?>, event)'>
            <?php echo $this->translate('ignore request');?>
          </button>
          </p>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>


  <div class="pulldown_options">
    <a href="<?php echo $this->url(array('action' => 'index'), 'recent_activity', true) ?>"><?php echo $this->translate("See All Friend Request") ?></a>
  </div>
<?php else:?>
  <div class="pulldown_loading"><?php echo $this->translate('You have no any friend request.');?></div>
<?php endif;?>
<?php if(engine_count($this->peopleyoumayknow) > 0): ?>
  <div class="sesdating_pulldown_header"><?php echo $this->translate("People You May Know"); ?></div>
  <ul class='pulldown_content_list' id="user_PeopleYouMayKnow_main">
    <?php foreach( $this->peopleyoumayknow as $notification ): ?>
    <?php $user = Engine_Api::_()->getItem('user', $notification->user_id);?>
      <li id="user_PeopleYouMayKnow_<?php echo $notification->user_id ?>" class="clearfix"  value="<?php echo $notification->getIdentity();?>">
        <div class="pulldown_item_photo">
          <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon')) ?>
        </div>
        <div class="pulldown_item_content">
          <p class="pulldown_item_content_title">
            <a href="<?php echo $user->getHref(); ?>"><?php echo $user->getTitle(); ?></a>
          </p>
          <p class="pulldown_item_content_btns clearfix">
            <button type="submit" onclick='userWidgetRequestSend("add", <?php echo $this->string()->escapeJavascript($user->user_id) ?>, <?php echo $user->user_id ?>, event)'><?php echo $this->translate('Add Friend');?></button>
            <button class="button button_alt" onclick='removePeopleYouMayKnow(<?php echo $user->getIdentity(); ?>)'><?php echo $this->translate('Remove');?></button>
          </p>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<script type="text/javascript">
  
  function removePeopleYouMayKnow(id) {
    event.stopPropagation();
    if(document.getElementById('user_PeopleYouMayKnow_' +id)) {
      scriptJquery('#user_PeopleYouMayKnow_'+id).remove();
    }
    if (document.getElementById('user_PeopleYouMayKnow_main').getChildren().length == 0) {
      document.getElementById('user_PeopleYouMayKnow_main').innerHTML = "<div class='tip' id=''><span><?php echo $this->translate('There are no more members.');?> </span></div>";
    }
  }
  
  function redirectPage(event) {
    
    event.stopPropagation();
    var url;
    var current_link = event.target;
    var notification_li = $(current_link).getParent('div');
    if(current_link.get('href') == null && $(current_link).get('tag')!='img') {
      if($(current_link).get('tag') == 'li') {
        var element = $(current_link).getElements('div:last-child');
        var html = element[0].outerHTML;
        var doc = document.createElement("html");
        doc.innerHTML = html;
        var links = doc.getElementsByTagName("a");
        var url = links[links.length - 1].getAttribute("href");
      }
      else
      url = $(notification_li).getElements('a:last-child').get('href');
      if(typeof url == 'object') {
        url = url[0];
      }
      notification_li.removeClass('pulldown_content_list_highlighted');
      scriptJquery.ajax({
        url : en4.core.baseUrl + 'sesdating/notifications/markreadmention',
        dataType: 'json',
        data : {
          format     : 'json',
          'actionid' : scriptJquery(current_link).closest('li').attr('value')
        },
        success : function() {
          window.location = url;
        }
      });
    }
  }
</script>
