<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: request-group.tpl 9747 2012-07-26 02:08:08Z john $
 * @author	   John
 */
?>
<script type="text/javascript">
  var groupWidgetRequestSend = function(action, group_id, notification_id)
  {
    var url;
    if( action == 'accept' )
    {
      url = '<?php echo $this->url(array('controller' => 'member', 'action' => 'accept'), 'group_extended', true) ?>';
    }
    else if( action == 'reject' )
    {
      url = '<?php echo $this->url(array('controller' => 'member', 'action' => 'reject'), 'group_extended', true) ?>';
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
        if( !responseJSON.status )
        {
          document.getElementById('group-widget-request-' + notification_id).innerHTML = responseJSON.error;
        }
        else
        {
          document.getElementById('group-widget-request-' + notification_id).innerHTML = responseJSON.message;
        }
      }
    }));
  }
</script>

<li id="group-widget-request-<?php echo $this->notification->notification_id ?>">
  <?php echo $this->itemBackgroundPhoto($this->notification->getObject(), 'thumb.icon') ?>
  <div>
    <div>
      <?php echo $this->translate('%1$s has invited you to the group %2$s', $this->htmlLink($this->notification->getSubject()->getHref(), $this->notification->getSubject()->getTitle()), $this->htmlLink($this->notification->getObject()->getHref(), $this->notification->getObject()->getTitle())); ?>
    </div>
    <div>
      <button type="submit" onclick='groupWidgetRequestSend("accept", <?php echo $this->string()->escapeJavascript($this->notification->getObject()->getIdentity()) ?>, <?php echo $this->notification->notification_id ?>)'>
        <?php echo $this->translate('Join Group');?>
      </button>
      <?php echo $this->translate('or');?>
      <a href="javascript:void(0);" onclick='groupWidgetRequestSend("reject", <?php echo $this->string()->escapeJavascript($this->notification->getObject()->getIdentity()) ?>, <?php echo $this->notification->notification_id ?>)'>
        <?php echo $this->translate('ignore request');?>
      </a>
    </div>
  </div>
</li>
