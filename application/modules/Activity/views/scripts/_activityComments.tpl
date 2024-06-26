<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Activity
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: _activityText.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     Jung
 */
?>

<?php $this->headScript()
        ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Activity/externals/scripts/core.js'); ?>
        
<?php if( empty($this->actions) ) {
  echo $this->translate("The action you are looking for does not exist.");
  return;
} else {
   $actions = $this->actions;
}
$composerOptions = Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.composer.options');
$attachUserTags = engine_in_array("userTags", $composerOptions);
$hashtagEnabled = engine_in_array("hashtags", $composerOptions);
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Core/externals/scripts/comments_composer.js');

if ($attachUserTags) {
  $this->headScript()
    ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Core/externals/scripts/comments_composer_tag.js');
} ?>

<script type="text/javascript">
  var CommentLikesTooltips;
  var commentComposer = new Hash();
  en4.core.runonce.add(function() {
    // Add hover event to get likes
    scriptJquery('.comments_comment_likes').on('mouseover', function(event) {
      var el = scriptJquery(this);
      if( !el.data('tip-loaded')) {
        el.data('tip-loaded', true);
        el.attr('title', '<?php echo  $this->string()->escapeJavascript($this->translate('Loading...')) ?>');
        var id = el.attr('id').match(/\d+/)[0];
        // Load the likes
        var url = '<?php echo $this->url(array('module' => 'activity', 'controller' => 'index', 'action' => 'get-likes'), 'default', true) ?>';
        var action_id = 0;
        action_id = el.closest('.activity-item').eq(0).attr('id').match(/\d+/)[0];
        var req = scriptJquery.ajax({
          url : url,
          dataType : 'json',
          method : 'post',
          data : {
            format : 'json',
            //type : 'core_comment',
            action_id : action_id,
            comment_id : id
          },
          success : function(responseJSON) {
            el.attr('title', responseJSON.body);
            el.tooltip("close");
            el.tooltip("open"); // Force it to update the text
          }
        });
      }
    }).tooltip({
      classes: {
        "ui-tooltip": "comments_comment_likes_tips"
      }
    });
    // Enable links in comments
    scriptJquery('.comments_body').enableLinks();
  });
</script>

<?php if( !$this->getUpdate ): ?>
<ul class='feed' id="activity-feed">
<?php endif ?>
  
<?php
  foreach( $actions as $action ): // (goes to the end of the file)
    try { // prevents a bad feed item from destroying the entire page
      // Moved to controller, but the items are kept in memory, so it shouldn't hurt to double-check
      if( !$action->getTypeInfo()->enabled ) continue;
      if( !$action->getSubject() || !$action->getSubject()->getIdentity() ) continue;
      if( !$action->getObject() || !$action->getObject()->getIdentity() ) continue;
      
      ob_start();
    ?>
    <script type="text/javascript">
      en4.core.runonce.add(function () {
        en4.activity.bindEditLink(<?php echo $action->getIdentity() ?>);
      });
    </script>
  <?php if( !$this->noList ): ?><li id="activity-item-<?php echo $action->action_id ?>" class="activity-item"  data-activity-feed-item="<?php echo $action->action_id ?>"><?php endif; ?>
    <?php $this->commentForm->setActionIdentity($action->action_id) ?>
<!--
    
    
    
    
    <?php // User's profile photo ?>
    <div class='feed_item_photo'><?php echo $this->htmlLink($action->getSubject()->getHref(),
      $this->itemPhoto($action->getSubject(), 'thumb.icon', $action->getSubject()->getTitle(false))
    ) ?></div>


    <div class='feed_item_body'>
      
      <?php // Main Content ?>
      <span class="<?php echo ( empty($action->getTypeInfo()->is_generated) ? 'feed_item_posted' : 'feed_item_generated' ) ?>">
        <?php echo $action->getContent() ?>
      </span>

      <?php echo $this->editActivity($action);?>
      <?php // Attachments ?>
      <?php if( $action->getTypeInfo()->attachable && $action->attachment_count > 0 ): // Attachments ?>
        <div class='feed_item_attachments'>
          <?php if( $action->attachment_count > 0 && engine_count($action->getAttachments()) > 0 ): ?>
            <?php if(null != ( $richContent = current($action->getAttachments())->item->getRichContent()) ): ?>
              <?php echo $richContent; ?>
            <?php else: ?>
              <?php foreach( $action->getAttachments() as $attachment ): ?>
                <span class='feed_attachment_<?php echo $attachment->meta->type ?>'>
                <?php if( $attachment->meta->mode == 0 ): // Silence ?>
                <?php elseif( $attachment->meta->mode == 1 ): // Thumb/text/title type actions ?>
                  <div>
                    <?php 
                      if ($attachment->item->getType() == "core_link")
                      {
                        $attribs = Array('target'=>'_blank');
                      }
                      else
                      {
                        $attribs = Array();
                      } 
                    ?>
                    <?php if( $attachment->item->getPhotoUrl() ): ?>
                      <?php echo $this->htmlLink($attachment->item->getHref(), $this->itemPhoto($attachment->item, 'thumb.normal', $attachment->item->getTitle()), $attribs) ?>
                    <?php endif; ?>
                    <div>
                      <div class='feed_item_link_title'>
                        <?php
                          echo $this->htmlLink($attachment->item->getHref(), $attachment->item->getTitle() ? $attachment->item->getTitle() : '', $attribs);
                        ?>
                      </div>
                      <div class='feed_item_link_desc'>
                        <?php echo $this->viewMore($attachment->item->getDescription()) ?>
                      </div>
                    </div>
                  </div>
                <?php elseif( $attachment->meta->mode == 2 ): // Thumb only type actions ?>
                  <div class="feed_attachment_photo">
                    <?php echo $this->htmlLink($attachment->item->getHref(), $this->itemPhoto($attachment->item, 'thumb.normal', $attachment->item->getTitle()), array('class' => 'feed_item_thumb')) ?>
                  </div>
                <?php elseif( $attachment->meta->mode == 3 ): // Description only type actions ?>
                  <?php echo $this->viewMore($attachment->item->getDescription()); ?>
                <?php elseif( $attachment->meta->mode == 4 ): // Multi collectible thingy (@todo) ?>
                <?php endif; ?>
                </span>
              <?php endforeach; ?>
              <?php endif; ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>
     -->

      <?php // Icon, time since, action links ?>
      <?php
        $icon_type = 'activity_icon_'.$action->type;
        list($attachment) = $action->getAttachments();
        if( is_object($attachment) && $action->attachment_count > 0 && $attachment->item ):
          $icon_type .= ' item_icon_'.$attachment->item->getType() . ' ';
        endif;
        $canComment = ( $action->getTypeInfo()->commentable &&
            $this->viewer()->getIdentity() &&
            Engine_Api::_()->authorization()->isAllowed($action->getCommentableItem(), null, 'comment') &&
            !empty($this->commentForm) );
        $class = 'feed_item_date feed_item_icon '.$icon_type;
      ?>
      <div class="<?php echo !$this->noList ? $class : "feed_item_icon"; ?>">
        <ul>
          <?php if(!$this->noList): ?>
            <li>
              <?php echo $this->timestamp($action->getTimeValue()) ?>
            </li>
          <?php endif; ?>
          <?php if( $canComment ): ?>
            <?php if( $action->likes()->isLike($this->viewer()) ): ?>
              <li class="feed_item_option_unlike">
                <span>-</span>
                <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Unlike'), array('onclick'=>'javascript:en4.activity.unlike('.$action->action_id.');')) ?>
              </li>
            <?php else: ?>
              <li class="feed_item_option_like">
                <span>-</span>
                <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Like'), array('onclick'=>'javascript:en4.activity.like('.$action->action_id.');')) ?>
              </li>
            <?php endif; ?>
            <?php if( Engine_Api::_()->getApi('settings', 'core')->core_spam_comment ): // Comments - likes ?>
              <li class="feed_item_option_comment">
                <span>-</span>
                <?php echo $this->htmlLink(array('route'=>'default','module'=>'activity','controller'=>'index','action'=>'viewcomment','action_id'=>$action->getIdentity(),'format'=>'smoothbox'), $this->translate('Comment'), array(
                  'class'=>'smoothbox',
                )) ?>
              </li>
            <?php else: ?>
              <li class="feed_item_option_comment">
                <span>-</span>
                <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Comment'), array(
                    'onclick'=>'showCommentBody(' . $action->action_id . ')')) ?>
              </li>
            <?php endif; ?>
            <?php if( $this->viewAllComments ): ?>
              <script type="text/javascript">
                en4.core.runonce.add(function() {
                  document.getElementById('<?php echo $this->commentForm->getAttrib('id') ?>').style.display = "";
                  document.getElementById('<?php echo $this->commentForm->submit->getAttrib('id') ?>').style.display = "block";
                  document.getElementById('<?php echo $this->commentForm->body->getAttrib('id') ?>').focus();
                });
              </script>
            <?php endif ?>
          <?php endif; ?>
          <?php if ($action->canEdit()): ?>
            <li class="feed_item_option_edit">
                <span>-</span>
                <a href="javascript:void(0);"><?php echo $this->translate('Edit') ?></a>
            </li>
          <?php endif; ?>
          <?php if( $this->viewer()->getIdentity() && (
                $this->activity_moderate || (
                  $this->allow_delete && (
                    ('user' == $action->subject_type && $this->viewer()->getIdentity() == $action->subject_id) ||
                    ('user' == $action->object_type && $this->viewer()->getIdentity()  == $action->object_id)
                  )
                )
            ) ): ?>
            <li class="feed_item_option_delete">
              <span>-</span>
              <?php echo $this->htmlLink(array(
                'route' => 'default',
                'module' => 'activity',
                'controller' => 'index',
                'action' => 'delete',
                'action_id' => $action->action_id
              ), $this->translate('Delete'), array('class' => 'smoothbox')) ?>
            </li>
          <?php endif; ?>
          <?php // Report ?>
          <?php if( $this->viewer()->getIdentity() != $action->subject_id && $this->viewer()->getIdentity()): ?>
            <li class="feed_item_option_report">
              <span>-</span>
              <?php echo $this->htmlLink(array('module'=>'core','controller'=>'report','action'=>'create','route'=>'default','subject'=>$action->getGuid(),'format' => 'smoothbox'), $this->translate("Report"), array('class' => 'smoothbox')); ?>
            </li>
          <?php endif; ?>
          <?php // Share ?>
          <?php if( $action->getTypeInfo()->shareable && $this->viewer()->getIdentity() ): ?>
            <?php if( $action->getTypeInfo()->shareable == 1 && $action->attachment_count == 1 && ($attachment = $action->getFirstAttachment()) ): ?>
              <li class="feed_item_option_share">
                <span>-</span>
                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'activity', 'controller' => 'index', 'action' => 'share', 'type' => $attachment->item->getType(), 'id' => $attachment->item->getIdentity(), 'format' => 'smoothbox'), $this->translate('Share'), array('class' => 'smoothbox', 'title' => 'Share')) ?>
              </li>
            <?php elseif( $action->getTypeInfo()->shareable == 2 ): ?>
              <li class="feed_item_option_share">
                <span>-</span>
                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'activity', 'controller' => 'index', 'action' => 'share', 'type' => $subject->getType(), 'id' => $subject->getIdentity(), 'format' => 'smoothbox'), $this->translate('Share'), array('class' => 'smoothbox', 'title' => 'Share')) ?>
              </li>
            <?php elseif( $action->getTypeInfo()->shareable == 3 ): ?>
              <li class="feed_item_option_share">
                <span>-</span>
                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'activity', 'controller' => 'index', 'action' => 'share', 'type' => $action->object_type, 'id' => $action->object_id, 'format' => 'smoothbox'), $this->translate('Share'), array('class' => 'smoothbox', 'title' => 'Share')) ?>
              </li>
            <?php elseif( $action->getTypeInfo()->shareable == 4 ): ?>
              <li class="feed_item_option_share">
                <span>-</span>
                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'activity', 'controller' => 'index', 'action' => 'share', 'type' => $action->getType(), 'id' => $action->getIdentity(), 'format' => 'smoothbox'), $this->translate('Share'), array('class' => 'smoothbox', 'title' => 'Share')) ?>
              </li>
            <?php endif; ?>
          <?php endif; ?>
        </ul>
      </div>
      <?php if( $action->getTypeInfo()->commentable ): // Comments - likes ?>
   
        <div class='comments' >


          <ul>
            <?php if( $action->likes()->getLikeCount() > 0 && (engine_count($action->likes()->getAllLikesUsers())>0) ): ?>
              <li>
                <div></div>
                <div class="comments_likes">
                  <?php if( $action->likes()->getLikeCount() <= 3 || $this->viewAllLikes ): ?>
                    <?php echo $this->translate(array('%s likes this.', '%s like this.', $action->likes()->getLikeCount()), $this->fluentList($action->likes()->getAllLikesUsers()) )?>

                  <?php else: ?>
                    <?php echo $this->htmlLink($action->getHref(array('show_likes' => true)),
                      $this->translate(array('%s person likes this', '%s people like this', $action->likes()->getLikeCount()), $this->locale()->toNumber($action->likes()->getLikeCount()) )
                    ) ?>
                  <?php endif; ?>
                </div>
              </li>
            <?php endif; ?>
            <?php if( $action->comments()->getCommentCount() > 0 ): ?>
              <?php if( $action->comments()->getCommentCount() > 5 && !$this->viewAllComments): ?>
                <li>
                  <div></div>
                  <div class="comments_viewall">
                    <?php if( $action->comments()->getCommentCount() > 2): ?>
                      <?php echo $this->htmlLink($action->getHref(array('show_comments' => true)),
                          $this->translate(array('View all %s comment', 'View all %s comments', $action->comments()->getCommentCount()),
                          $this->locale()->toNumber($action->comments()->getCommentCount()))) ?>
                    <?php else: ?>
                      <?php echo $this->htmlLink('javascript:void(0);',
                          $this->translate(array('View all %s comment', 'View all %s comments', $action->comments()->getCommentCount()),
                          $this->locale()->toNumber($action->comments()->getCommentCount())),
                          array('onclick'=>'en4.activity.viewComments('.$action->action_id.');')) ?>
                    <?php endif; ?>
                  </div>
                </li>
              <?php endif; ?>
              <?php foreach( $action->getComments($this->viewAllComments) as $comment ): ?>
                <li id="comment-<?php echo $comment->comment_id ?>">
                  <div class="comments_author_photo">
                    <?php echo $this->htmlLink($this->item($comment->poster_type, $comment->poster_id)->getHref(),
                      $this->itemBackgroundPhoto($this->item($comment->poster_type, $comment->poster_id), 'thumb.icon', $action->getSubject()->getTitle(false))
                    ) ?>
                  </div>
                  <div class="comments_info">
                   <span class='comments_author'>
                     <?php echo $this->htmlLink($this->item($comment->poster_type, $comment->poster_id)->getHref(), $this->item($comment->poster_type, $comment->poster_id)->getTitle()); ?>
                   </span>
                   <span class="comments_body">
                     <?php echo $this->viewMore(Engine_Text_Emoji::decode($comment->body)) ?>
                   </span>
                   <ul class="comments_date">
                     <li class="comments_timestamp">
                       <?php echo $this->timestamp($comment->creation_date); ?>
                     </li>
                     <?php if ( $this->viewer()->getIdentity() &&
                               (('user' == $action->subject_type && $this->viewer()->getIdentity() == $action->subject_id) ||
                                ($this->viewer()->getIdentity() == $comment->poster_id) ||
                                $this->activity_moderate ) ): ?>
                     <li class="sep">-</li>
                     <li class="comments_delete">
                       <?php echo $this->htmlLink(array(
                            'route'=>'default',
                            'module'    => 'activity',
                            'controller'=> 'index',
                            'action'    => 'delete',
                            'action_id' => $action->action_id,
                            'comment_id'=> $comment->comment_id,
                            ), $this->translate('delete'), array('class' => 'smoothbox')) ?>
                     </li>
                      <?php endif; ?>
                      <?php if( $canComment ):
                        $isLiked = $comment->likes()->isLike($this->viewer());
                      ?>
                        <li class="sep">-</li>
                        <li class="comments_like">
                          <?php if( !$isLiked ): ?>
                            <a href="javascript:void(0)" onclick="en4.activity.like(<?php echo sprintf("'%d', %d", $action->getIdentity(), $comment->getIdentity()) ?>)">
                              <?php echo $this->translate('like') ?>
                            </a>
                          <?php else: ?>
                            <a href="javascript:void(0)" onclick="en4.activity.unlike(<?php echo sprintf("'%d', %d", $action->getIdentity(), $comment->getIdentity()) ?>)">
                              <?php echo $this->translate('unlike') ?>
                            </a>
                          <?php endif ?>
                        </li>
                      <?php endif ?>
                      <?php if( $comment->likes()->getLikeCount() > 0 ): ?>
                        <li class="sep">-</li>
                        <li class="comments_likes_total">
                          <a href="javascript:void(0);" id="comments_comment_likes_<?php echo $comment->comment_id ?>" class="comments_comment_likes" title="<?php echo $this->translate('Loading...') ?>">
                            <?php echo $this->translate(array('%s likes this', '%s like this', $comment->likes()->getLikeCount()), $this->locale()->toNumber($comment->likes()->getLikeCount())) ?>
                          </a>
                        </li>
                      <?php endif ?>
                      <?php if( $this->viewer()->getIdentity() != $comment->poster_id ): ?>
                        <li class="sep">-</li>
                        <li class="comments_report">
                          <?php echo $this->htmlLink(array('module'=>'core','controller'=>'report','action'=>'create','route'=>'default','subject'=>$comment->getGuid(),'format' => 'smoothbox'), $this->translate("Report"), array('class' => 'smoothbox')); ?>
                        </li>
                      <?php endif; ?>
                    </ul>
                  </div>
                </li>
              <?php endforeach; ?>
            <?php endif; ?>
          </ul>
          <?php if(!Engine_Api::_()->getApi('settings', 'core')->core_spam_comment && $canComment ) echo $this->commentForm->render() /*
          <form>
            <textarea rows='1'>Add a comment...</textarea>
            <button type='submit'>Post</button>
          </form>
          
          
          */ ?>
    <script type="text/javascript">
      var attachComment = function(action_id){
        en4.core.runonce.add(function(){
          //scriptJquery('#activity-comment-body-' + action_id).autogrow();
          var attachComposerTag = '<?php echo $attachUserTags ?>';
          var composeCommentInstance = new CommentsComposer(scriptJquery('#activity-comment-body-' + action_id), {
            'submitCallBack' : en4.activity.comment,
            hashtagEnabled : '<?php echo $hashtagEnabled ?>',
          });
          if (attachComposerTag === '1') {
            composeCommentInstance.addPlugin(new CommentsComposer.Plugin.Tag({
              enabled: true,
              suggestOptions : {
                'url' : '<?php echo $this->url(array(), 'default', true) . 'user/friends/suggest' ?>',
                'data' : {
                  'format' : 'json',
                  'includeSelf':true
                }
              },
              'suggestProto' : 'request.json',
              'suggestParam' : [],
            }));
          }
          commentComposer[action_id] = composeCommentInstance;
        });
      };
      var action_id = '<?php echo $action->action_id ?>';
      attachComment(action_id);
      var showCommentBody = function (action_id) {
        scriptJquery('#activity-comment-form-' + action_id).css("display","");
        scriptJquery('#activity-comment-submit-' + action_id).css("display","block");
        if(commentComposer[action_id]){
          commentComposer[action_id].focus();
        }
      }
    </script>
          <!--
        </div>
      <?php endif; ?>

    </div>
  <?php if( !$this->noList ): ?></li><?php endif; ?>

<?php
      ob_end_flush();
    } catch (Exception $e) {
      ob_end_clean();
      if( APPLICATION_ENV === 'development' ) {
        echo $e->__toString();
      }
    };
  endforeach;
?>

<?php if( !$this->getUpdate ): ?>
</ul>
<?php endif ?>
-->
