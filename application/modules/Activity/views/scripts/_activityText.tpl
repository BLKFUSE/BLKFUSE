<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Activity
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: _activityText.tpl 10194 2014-05-01 17:41:40Z mfeineman $
 * @author     Jung
 */
?>
<?php if( empty($this->actions) ) {
  echo $this->translate("The action you are looking for does not exist.");
  return;
} else {
  $actions = $this->actions;
}
$composerOptions = Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.composer.options');
if(!empty($composerOptions)) {
$attachUserTags = engine_in_array("userTags", $composerOptions);
$hashtagEnabled = engine_in_array("hashtags", $composerOptions);
}

$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Core/externals/scripts/comments_composer.js');

if (@$attachUserTags) {
$this->headScript()
->appendFile($this->layout()->staticBaseUrl . 'application/modules/Core/externals/scripts/comments_composer_tag.js');
} ?>

<?php $this->headScript()
        ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Activity/externals/scripts/core.js');
?>
<script type="text/javascript">
    var CommentLikesTooltips;
    var commentComposer = new Hash();
    en4.core.runonce.add(function() {
        // Add hover event to get likes
        scriptJquery('.comments_comment_likes').on('mouseover', function(e) {
            var el = scriptJquery(this);
            if( !el.data('tip-loaded')) {
                el.data('tip-loaded', true);
                el.attr('title', '<?php echo  $this->string()->escapeJavascript($this->translate('Loading...')) ?>');
                var id = el.attr('id').match(/\d+/)[0];
                // Load the likes
                var url = '<?php echo $this->url(array('module' => 'activity', 'controller' => 'index', 'action' => 'get-likes'), 'default', true) ?>';
                var req = scriptJquery.ajax({
                    url : url,
                    dataType : 'json',
                    method : 'post',
                    data : {
                        format : 'json',
                        //type : 'core_comment',
                        action_id : el.closest('.activity-item').eq(0).attr('id').match(/\d+/)[0],
                        comment_id : id
                    },
                    success : function(responseJSON) {
                        el.attr('title', responseJSON.body);
                        el.tooltip("close");
                        el.tooltip("open");
                    }
                });
            }
        }).tooltip({
          classes: {
            "ui-tooltip": "comments_comment_likes_tips"
          }
        });
    });
</script>

<?php if( !$this->getUpdate ): ?>
<ul class='feed' <?php if(empty($this->hideOptions)) { ?> id="activity-feed" <?php } ?>>
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
  <?php if( !$this->noList ): ?><li <?php if(empty($this->hideOptions)) { ?> id="activity-item-<?php echo $action->action_id ?>" <?php } ?> class="activity-item" data-activity-feed-item="<?php echo $action->action_id ?>"><?php endif; ?>
    <?php if(empty($this->hideOptions)) { ?>
      <?php $this->commentForm->setActionIdentity($action->action_id) ?>
    <?php } ?>
    <?php // User's profile photo ?>
    <div class='feed_item_photo'><?php echo $this->htmlLink($action->getSubject()->getHref(),
      $this->itemBackgroundPhoto($action->getSubject(), 'thumb.icon', $action->getSubject()->getTitle(false))
      ) ?></div>

    <div class='feed_item_body'>
      <?php 
        $icon_type = 'activity_icon_'.$action->type;
        list($attachment) = $action->getAttachments();
        if( is_object($attachment) && $action->attachment_count > 0 && $attachment->item ):
        $icon_type .= ' item_icon_'.$attachment->item->getType() . ' ';
        endif; 
      ?>
      <?php // Main Content ?>
      <?php if(empty($this->hideOptions)) { ?>
        <?php echo $this->editActivity($action);?>
      <?php } ?>
      <span class="feed_item_body_content <?php echo ( empty($action->getTypeInfo()->is_generated) ? 'feed_item_posted' : 'feed_item_generated' ) ?>">
        <?php echo $this->getActionContent($action, $this->similarActivities)?>
        <div class="feed_item_date <?php echo $icon_type ?>">
          <a href="<?php echo $action->getHref(); ?>"><?php echo $this->timestamp($action->getTimeValue()) ?></a>
          <?php echo $this->lastEditedActivity($action); ?>
        </div>
      </span>

      <?php // Private Content ?>
      <?php $viewPermission = $action->getObject()->authorization()->isAllowed($this->viewer(), 'view'); ?>
      <?php if(!$viewPermission || (isset($action->getObject()->approved) && empty($action->getObject()->approved))) { ?>
        <div class="feed_item_private_message">
          <div class="feed_item_private_message_icon">
            <i class="fas fa-lock"></i>
          </div>
          <div class="feed_item_private_message_content">
            <p><?php echo $this->translate("You do not have access to view this content."); ?></p>
            <p><?php echo $this->translate("This usually happens when owner has not shared this content with you or it's been deleted."); ?></p>
          </div>
        </div>
      <?php } else { ?>

      <?php // Attachments ?>
      <?php if( $action->getTypeInfo()->attachable && $action->attachment_count > 0 ): // Attachments ?>
      <div class='feed_item_attachments'>
        <?php if( $action->attachment_count > 0 && is_array($action->getAttachments()) && engine_count($action->getAttachments()) > 0 ): ?>
        <?php if(null != ( $richContent = current($action->getAttachments())->item->getRichContent()) ): ?>
        <?php echo $richContent; ?>
        <?php else: ?>
        <?php foreach( $action->getAttachments() as $key => $attachment ): ?>
        <span class='feed_attachment_<?php echo $attachment->meta->type ?>'>
                <?php if( $attachment->meta->mode == 0 ): // Silence ?>
          <?php elseif( $attachment->meta->mode == 1 ): // Thumb/text/title type actions ?>
                  <div>
                    <?php
                      if ($attachment->item->getType() == "core_link"  || $attachment->item->getType() == 'storage_file')
                      {
                        $attribs = Array('target'=>'_blank');
                      }
                      else
                      {
                        $attribs = Array();
                      }
                    ?>
                    <?php if( $attachment->item->getPhotoUrl() ): ?>
                    <?php echo $this->htmlLink($attachment->item->getHref(), $this->itemPhoto($attachment->item, 'thumb.profile', $attachment->item->getTitle()), $attribs) ?>
                    <?php endif; ?>
                    
                    <?php $attachmentTitle = $this->htmlLink($attachment->item->getHref(), $attachment->item->getTitle() ? Engine_Api::_()->core()->DecodeEmoji($attachment->item->getTitle()) : '', $attribs); ?>
                    <?php if($attachment->item->getType() == 'activity_action') {
                        $previousAction = $action;
                        $previousAttachment = $attachment;
                        $action = Engine_Api::_()->getItem('activity_action', $attachment->item->getIdentity());;
                        $hideOptions = true;
                        $attachmentDes = $this->partial('_activityText.tpl', 'activity', array('actions' => array($action), 'hideOptions' => $hideOptions));
                        //include('application/modules/Activity/views/scripts/_activityText.tpl');
                        $action = $previousAction;
                        $hideOptions = false;
                        $attachment = $previousAttachment;
                        $previousAction = $previousAttachment = "";
                      } else { ?>
                      <?php $attachmentDescription = Engine_Api::_()->core()->DecodeEmoji($attachment->item->getDescription()); ?>
                      <?php if (strip_tags($action->body) != $attachmentDescription): ?>
                        <?php if(engine_in_array($action->type, array('forum_topic_create', 'forum_topic_reply', 'group_topic_create', 'event_topic_create', 'group_topic_reply', 'event_topic_reply'))) { ?>
                          <?php $attachmentDes = $this->viewMore(Engine_Api::_()->core()->smileyToEmoticons(strip_tags($attachmentDescription)), 255, 1027, 511, false); ?>
                        <?php } else { ?>
                          <?php $attachmentDes = $this->viewMore(Engine_Api::_()->core()->smileyToEmoticons(strip_tags($attachmentDescription)), 255, 1027, 511, false); ?>
                        <?php } ?>
                      <?php endif; ?>
                    <?php } ?>
                    <?php if(($attachment->item->getTitle() && $attachmentTitle) || $attachmentDes) { ?>
                      <div>
                        <?php if($attachmentTitle) { ?>
                          <div class='feed_item_link_title'>
                            <?php
                              echo $attachmentTitle;
                            ?>
                          </div>
                        <?php } ?>
                        <?php  if($attachmentDes) { ?>
                          <div class='feed_item_link_desc'>
                            <?php echo $attachmentDes; ?>
                          </div>
                        <?php } ?>
                      </div>
                    <?php } ?>
                  </div>
                <?php elseif( $attachment->meta->mode == 2 ): // Thumb only type actions ?>
                    <?php if (!$this->action_id && engine_count($action->getAttachments()) > $this->viewMaxPhoto && $key === $this->viewMaxPhoto - 1): ?>
											<div class="feed_attachment_photo">
												<a href="<?php echo $action->getHref(); ?>">
													<span class="feed_attachment_photo_overlay"></span>
													<span class="feed_attachment_photo_more_count"><?php echo '+' . (engine_count($action->getAttachments()) - $this->viewMaxPhoto  + 1) ?></span>
													<?php echo $this->itemBackgroundPhoto($attachment->item)?>
												</a>
											</div>
											<?php break; ?>
                    <?php endif; ?>
                    <?php if(isset($attachment->item->approved) && empty($attachment->item->approved)) continue; ?>
                    <div class="feed_attachment_photo">
											<a href="<?php echo $attachment->item->getHref(); ?>">
												<?php echo $this->itemBackgroundPhoto($attachment->item)?>
											</a>
											<?php if($attachment->item->getTitle() || $attachment->item->getDescription()) { ?>
												<div>
													<?php if($attachment->item->getTitle()) { ?>
														<div class='feed_item_link_title'>
															<?php echo $this->htmlLink($attachment->item->getHref(), $attachment->item->getTitle() ? Engine_Api::_()->core()->DecodeEmoji($attachment->item->getTitle()) : '', @$attribs); ?>
														</div>
													<?php } ?>
													<?php if($attachment->item->getDescription()) { ?>
														<div class='feed_item_link_desc'>
															<?php $attachmentDescription = Engine_Api::_()->core()->DecodeEmoji($attachment->item->getDescription()); ?>
															<?php if (strip_tags($action->body) != $attachmentDescription): ?>
																<?php echo $this->viewMore(Engine_Api::_()->core()->smileyToEmoticons(strip_tags($attachmentDescription))); ?>
															<?php endif; ?>
														</div>
													<?php } ?>
												</div>
											<?php } ?>
										</div>
								<?php elseif( $attachment->meta->mode == 3 ): // Description only type actions ?>
									<?php if(engine_in_array($action->type, array('forum_topic_create', 'forum_topic_reply', 'group_topic_create', 'event_topic_create', 'group_topic_reply', 'event_topic_reply'))) { ?>
										<div class='feed_item_link_title'>
											<?php echo $this->htmlLink($attachment->item->getHref(), $attachment->item->getTitle() ? Engine_Api::_()->core()->DecodeEmoji($attachment->item->getTitle()) : '', $attribs); ?>
										</div>
										<?php echo $this->viewMore(strip_tags($attachment->item->getDescription()), 255, 1027, 511, false); ?>
									<?php } else { ?>
                    <?php echo $this->viewMore(Engine_Api::_()->core()->smileyToEmoticons(strip_tags($attachment->item->getDescription())), 255, 1027, 511, false); ?>
									<?php } ?>
								<?php elseif( $attachment->meta->mode == 4 ): // Multi collectible thingy (@todo) ?>
								<?php endif; ?>
							</span>
        <?php endforeach; ?>
        <?php endif; ?>
        <?php endif; ?>
      </div>
      <?php endif; ?>
      

      <?php if( !empty($this->hashtag[$action->action_id]) ): ?>
      <div class="hashtag_activity_item">
        <ul>
          <?php
            $url = $this->url(array('controller' => 'hashtag', 'action' => 'index'), "core_hashtags") . "?search=";
          for( $i = 0; $i < engine_count($this->hashtag[$action->action_id]); $i++ ) {
          ?>
          <li>
            <a href="<?php echo $url . urlencode($this->hashtag[$action->action_id][$i]); ?>"><?php echo $this->hashtag[$action->action_id][$i]; ?></a>
          </li>
          <?php } ?>
        </ul>
      </div>
      <?php endif; ?>
      
      <?php if(empty($this->hideOptions)) { ?>
      <div id='comment-likes-activity-item-<?php echo $action->action_id ?>'>

        <?php // Icon, time since, action links ?>
        <?php
        $canComment = ( $action->getTypeInfo()->commentable &&
        $this->viewer()->getIdentity() &&
        Engine_Api::_()->authorization()->isAllowed($action->getCommentableItem(), null, 'comment') &&
        !empty($this->commentForm) );
        ?>
        <div class='feed_item_icon'>
          <ul>
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
              'data-form-id' => $this->commentForm->getAttrib('id'),
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
            ($this->viewer()->getIdentity() == $this->activity_group) || (
            $this->allow_delete && (
            ('user' == $action->subject_type && $this->viewer()->getIdentity() == $action->subject_id) ||
            ('user' == $action->object_type && $this->viewer()->getIdentity()  == $action->object_id)
            )
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
            <?php $shareableItem = $action->getShareableItem();?>
            <?php if( $shareableItem && $this->viewer()->getIdentity() ): ?>
            <li class="feed_item_option_share">
              <span>-</span>
                <?php if($action->type == 'share') { ?>
                  <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'activity', 'controller' => 'index', 'action' => 'share', 'type' => $attachment->item->getType(), 'id' => $attachment->item->getIdentity(), 'action_id' => $action->getIdentity(), 'format' => 'smoothbox'), $this->translate('Share'), array('class' => 'smoothbox', 'title' => 'Share')) ?>
                <?php } else { ?>
                  <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'activity', 'controller' => 'index', 'action' => 'share', 'type' => $shareableItem->getType(), 'id' => $shareableItem->getIdentity(), 'action_id' => $action->getIdentity(), 'format' => 'smoothbox'), $this->translate('Share'), array('class' => 'smoothbox', 'title' => 'Share')) ?>
                <?php } ?>
            </li>
            <?php endif; ?>
          </ul>
        </div>
        <!--</div> End of Comment-Likes -->

        <?php if( $action->getTypeInfo()->commentable ): // Comments - likes ?>
        <div class='comments'>
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

            <?php
              //echo '<pre>';
            //var_dump($action->getComments($this->viewAllComments));
            //echo '</pre>';
            //die('The End');
            ?>

            <?php
                $comments = $action->getComments($this->viewAllComments);
            $commentLikes = $action->getCommentsLikes($comments, $this->viewer());
            ?>
            <?php foreach( $comments as $comment ): ?>
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
                        $isLiked = !empty($commentLikes[$comment->comment_id]);
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
          <?php if(!Engine_Api::_()->getApi('settings', 'core')->core_spam_comment ) { ?>
          <?php if( $canComment ) echo $this->commentForm->render() /*
          <form>
            <textarea rows='1'>Add a comment...</textarea>
            <button type='submit'>Post</button>
          </form>
          */ ?>
          <?php } ?>
        </div>
        <script type="text/javascript">
            var attachComment = function(action_id){
                //en4.core.runonce.add(function(){
                   //scriptJquery('#activity-comment-body-' + action_id).autogrow();
                    var attachComposerTag = '<?php echo @$attachUserTags ?>';
                    var composeCommentInstance = new CommentsComposer(scriptJquery('#activity-comment-body-' + action_id), {
                        'submitCallBack' : en4.activity.comment,
                        hashtagEnabled : '<?php echo @$hashtagEnabled ?>',
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
                //});
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
        <?php endif; ?>

      </div> <!-- End of Comment Likes -->
      <?php } ?>
      <?php } ?>

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
