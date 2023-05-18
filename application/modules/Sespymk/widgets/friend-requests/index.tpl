<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sespymk
 * @package    Sespymk
 * @copyright  Copyright 2016-2017 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2017-03-03 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sespymk/externals/styles/styles.css'); ?>

<script type="text/javascript">
  var widget_request_send = function(action, user_id, notification_id, event, tokenName, tokenValue)
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
    
    var data = {
      'user_id' : user_id,
      'format' : 'json',
    };
    data[tokenName] = tokenValue;
    
    (scriptJquery.ajax({
      dataType: 'json',
      'url' : url,
      'data' : data,
      success : function(responseJSON) {
        if( !responseJSON.status ) {
          document.getElementById('user-widget-request-' + notification_id).innerHTML = responseJSON.error;
        } else {
          document.getElementById('user-widget-request-' + notification_id).innerHTML = responseJSON.message;
        }
      }
    }));
  }
</script>

<script type="text/javascript">
  function loadMore() {
  
    if (document.getElementById('view_more'))
      document.getElementById('view_more').style.display = "<?php echo ( $this->friendRequests->count() == $this->friendRequests->getCurrentPageNumber() || $this->count == 0 ? 'none' : '' ) ?>";

    if(document.getElementById('view_more'))
      document.getElementById('view_more').style.display = 'none';
    
    if(document.getElementById('loading_image'))
     document.getElementById('loading_image').style.display = '';

    en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + 'widget/index/mod/sespymk/name/friend-requests',
      'data': {
        format: 'html',
        page: "<?php echo sprintf('%d', $this->friendRequests->getCurrentPageNumber() + 1) ?>",
        viewmore: 1,
        params: '<?php echo json_encode($this->all_params); ?>',
        
      },
      success: function(responseHTML) {
        scriptJquery('#notifications_main').append(responseHTML);
        if(document.getElementById('view_more'))
          scriptJquery('#view_more').remove();
        if(document.getElementById('loading_image'))
          scriptJquery('#loading_image').remove();
        if(document.getElementById('loadmore_list'))
          scriptJquery('#loadmore_list').remove();
      }
    }));
    return false;
  }
</script>

<div class="sespymk_requests_container sesbasic_clearfix sesbasic_bxs">
  <?php if (empty($this->viewmore) && $this->linktopage): ?>
    <div class="sespymk_list_more sesbasic_clearfix">
      <a class="floatL" href="friends/requests/"><?php echo $this->translate("View Sent Requests") ?></a>
      <?php if( $this->friendRequests->getTotalItemCount() > 0 ): ?>
        <a class="floatR" href="<?php echo $this->url(array('action' => 'index'), 'recent_activity', true) ?>"><?php echo $this->translate("See All Friend Request") ?> &raquo;</a>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <?php if( $this->friendRequests->getTotalItemCount() > 0 ): ?>
    <?php if (empty($this->viewmore)): ?>
      <ul class='sespymk_list sesbasic_clearfix' id="notifications_main">
    <?php endif; ?>
      <?php foreach( $this->friendRequests as $notification ): ?>
        <?php $user = Engine_Api::_()->getItem('user', $notification->subject_id);?>
        <?php
          $tokenName = 'token_' . $user->getGuid();
          $salt = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.secret');
          $tokenValue = $this->token(null, $tokenName, $salt);
        ?>
        <li id="user-widget-request-<?php echo $notification->notification_id ?>" value="<?php echo $notification->getIdentity();?>" class="sespymk_user_<?php echo $notification->user_id ?> sespymk_list_item sesbasic_clearfix">
          <div class="sespymk_list_item_inner">
            <div class="sespymk_list_item_photo">
              <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.profile')) ?>
            </div>
            <div class="sespymk_list_item_cont">
              <div class="sespymk_list_item_title">
                <a href="<?php echo $user->getHref(); ?>"><?php echo $user->getTitle(); ?></a>
              </div>
            </div>
            <div class="sespymk_list_item_btn rightT">
              <div class="sespymk_buttons">
                <div class="sespymk_add_button">
                  <button type="submit" onclick='widget_request_send("confirm", <?php echo $this->string()->escapeJavascript($notification->getSubject()->getIdentity()) ?>, <?php echo $notification->notification_id ?>, event, "<?php echo $tokenName; ?>", "<?php echo $tokenValue; ?>")'><i class="fa fa-user-plus"></i><?php echo $this->translate('Accept Request');?></button>
                </div>
                <div>
                  <a href="javascript:void(0);" class="sesbasic_button" onclick='widget_request_send("reject", <?php echo $this->string()->escapeJavascript($notification->getSubject()->getIdentity()) ?>, <?php echo $notification->notification_id ?>, event, "<?php echo $tokenName; ?>", "<?php echo $tokenValue; ?>")'><?php echo $this->translate('Ignore request');?></a>
                </div>
              </div>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
      <?php if (!empty($this->friendRequests) && $this->friendRequests->count() > 1): ?>
        <?php if ($this->friendRequests->getCurrentPageNumber() < $this->friendRequests->count()): ?>
          <div class="clr" id="loadmore_list"></div>
          <div class="sesbasic_load_btn" id="view_more" onclick="loadMore();" >
            <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" id="feed_viewmore_link"><i class="fa fa-sync"></i><span><?php echo $this->translate('View More');?></span></a>
          </div>  
          <div class="sesbasic_load_btn" id="loading_image" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
        <?php endif; ?>
      <?php endif; ?>
    <?php if (empty($this->viewmore)): ?>
      </ul>
    <?php endif; ?>
  <!--  <div class="sespymk_list_more sesbasic_clearfix">
      <a class="floatL" href="<?php //echo $this->url(array('action' => 'index'), 'recent_activity', true) ?>"><?php //echo $this->translate("See All Friend Request") ?> &raquo;</a>
      <a class="floatR" href="friends/requests/"><?php //echo $this->translate("View Sent Requests") ?></a>
    </div>-->
  <?php else:?>
    <div class="tip"><span style="margin:10px 0;"><?php echo $this->translate('There are no New Requests.');?></span></div>
  <?php endif;?>
</div>  

<?php if($this->paginationType == 1): ?>
  <script type="text/javascript">    
     //Take refrences from: http://mootools-users.660466.n2.nabble.com/Fixing-an-element-on-page-scroll-td1100601.html
    //Take refrences from: http://davidwalsh.name/mootools-scrollspy-load
    en4.core.runonce.add(function() {
      var paginatorCount = '<?php echo $this->friendRequests->count(); ?>';
      var paginatorCurrentPageNumber = '<?php echo $this->friendRequests->getCurrentPageNumber(); ?>';
      function ScrollLoader() { 
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        if(document.getElementById('loadmore_list')) {
          if (scrollTop > 40)
            loadMore();
        }
      }
      scriptJquery(document).on('scroll',function(event) {
        ScrollLoader(); 
      });
    });    
  </script>
<?php endif; ?>
