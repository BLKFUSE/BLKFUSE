<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _activitycommentbodyoptions.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php 
  $canComment = $this->canComment;
  $comment = $this->comment;
  $activitycomments = Engine_Api::_()->getDbTable('activitycomments', 'sesadvancedactivity')->rowExists($comment->getIdentity());
  $isPageSubject = !empty($this->isPageSubject) ? $this->isPageSubject : $this->viewer();
  $actionBody = $this->actionBody;

  $islanguageTranslate = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.translate', 1);
 $languageTranslate = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.language', 'en');
     
?>

 <?php if( $comment->likes()->getLikeCount() > 0 ): ?>
    <?php $likesGroup = Engine_Api::_()->sesadvancedcomment()->commentLikesGroup($comment,false); 
      $counts = 0;
      if(engine_count($likesGroup['data'])){ 
    ?>
    <span class="comments_likes_total">
       <span class="comments_likes_reactions">
       <?php foreach($likesGroup['data'] as $type){
        $counts = $type['counts'] + $counts;
        ?>
        <a title="<?php echo $this->translate('%s (%s)',$type['counts'],Engine_Api::_()->sesadvancedcomment()->likeWord($type['type'])) ?>" href="javascript:;" class="sessmoothbox" data-url="<?php echo $this->url(array('module' => 'sesadvancedactivity', 'controller' => 'ajax', 'action' => 'comment-likes', 'comment_id' => $comment->getIdentity(), 'id' => $actionBody->getIdentity(),'resource_type'=>$actionBody->getType(), 'format' => 'smoothbox'), 'default', true); ?>"><i style="background-image:url(<?php echo Engine_Api::_()->sesadvancedcomment()->likeImage($type['type']);?>);"></i></a>
        <?php } ?>
      </span>
        <a href="javascript:;" class="sessmoothbox" data-url="<?php echo $this->url(array('module' => 'sesadvancedactivity', 'controller' => 'ajax', 'action' => 'comment-likes', 'comment_id' => $comment->getIdentity(), 'id' => $actionBody->getIdentity(),'resource_type'=>$actionBody->getType(), 'format' => 'smoothbox'), 'default', true); ?>"><?php echo $counts; ?></a>
    </span>
    <?php } ?>
  <?php endif ?> 

<ul class="comments_date" id="comments_reply_<?php echo $comment->comment_id; ?>_<?php echo $actionBody->getIdentity(); ?>" style="display:block;">
  <?php if( $canComment ): ?>
    <template class="owner-info"><?php echo $this->getUserInfo($comment->getOwner()); ?></template>
    <?php $isLiked = $comment->likes()->isLike($isPageSubject); ?>
    <?php if( $this->viewer()->getIdentity() && $this->canComment ):
      if($likeRow =  $comment->likes()->getLike($isPageSubject)){ 
          if($likeRow->getType() == 'activity_like') {
            $item_activity_like = Engine_Api::_()->getDbTable('activitylikes', 'sesadvancedactivity')->rowExists($likeRow->like_id); 
          } else {
            $item_activity_like = Engine_Api::_()->getDbTable('corelikes', 'sesadvancedactivity')->rowExists($likeRow->like_id); 
          }
          $like = true;
          if($item_activity_like)
            $type = $item_activity_like->type;
          else 
            $type = 1;

          $imageLike = Engine_Api::_()->sesadvancedcomment()->likeImage($type);
          $text = Engine_Api::_()->sesadvancedcomment()->likeWord($type);
       }else{
          $like = false;
          $type = '';
          $imageLike = '';
          $text = 'SESADVLIKE';
       }
       ?>
        <li class="feed_item_option_<?php echo $like ? 'unlike' : 'like'; ?> actionBox showEmotions <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.reactionenable', 1)):?> sesadvcmt_hoverbox_wrapper <?php endif; ?>">
          <?php $getReactions = Engine_Api::_()->getDbTable('reactions', 'sesadvancedcomment')->getReactions(array('userside' => 1, 'fetchAll' => 1)); ?>
          <?php if(engine_count($getReactions) > 0): ?>
            <div class="sesadvcmt_hoverbox">
              <?php foreach($getReactions as $getReaction): ?>
                <span>
                  <span  data-text="<?php echo $this->translate($getReaction->title);?>" data-actionid="<?php echo  $actionBody->getIdentity(); ?>" data-commentid = "<?php echo  $comment->getIdentity(); ?>"  data-type="<?php echo $getReaction->reaction_id; ?>" data-guid="<?php echo $isPageSubject->getGuid(); ?>"  class="sesadvancedcommentcommentlike reaction_btn sesadvcmt_hoverbox_btn"><div class="reaction sesadvcmt_hoverbox_btn_icon"> <i class="react"  style="background-image:url(<?php echo Engine_Api::_()->sesadvancedcomment()->likeImage($getReaction->reaction_id);?>)"></i> </div></span>
                  <div class="text">
                    <div><?php echo $this->translate($getReaction->title); ?></div>
                  </div>
                </span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <a href="javascript:void(0);" <?php if(!empty($_SESSION["sesfromLightbox"])){ ?> id="sesadvancedcomment_like_action_<?php echo $comment->getIdentity(); ?>" <?php $_SESSION["sesfromLightbox"] = ''; }else{ ?> id="sesadvancedcomment_like_actionrec_<?php echo $comment->getIdentity(); ?>" <?php } ?> data-like="<?php echo $this->translate('SESADVLIKEC') ?>" data-unlike="<?php echo $this->translate('SESADVUNLIKEC') ?>" data-actionid="<?php echo  $actionBody->getIdentity(); ?>" data-commentid = "<?php echo  $comment->getIdentity(); ?>" data-guid="<?php echo $isPageSubject->getGuid(); ?>" data-type="1" class="sesadvancedcommentcomment<?php echo $like ? 'unlike _reaction' : 'like' ;  ?>">
            <span><?php echo $this->translate($text);?></span>
          </a> 
        </li>
    <?php endif; ?>
    <li class="sep">&middot;</li> 
  <?php endif ?>
    <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.enablenestedcomments', 1)): ?>
      <li class="comments_reply">
        <?php echo $this->htmlLink('javascript:;', $this->translate('SESADVREPLY'), array('class' => 'sesadvancedcommentreply')) ?>
      </li>
      <li class="sep">&middot;</li>
    <?php endif; ?>
    <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.enablemessagesellpost', 1) && $this->viewer()->getIdentity() && $comment->poster_id != $this->viewer()->getIdentity() && $actionBody->type == "post_self_buysell"): ?>
      <li class="comments_reply">
        <?php echo $this->htmlLink($this->url(array('owner_id' =>$comment->poster_id,'action'=>'contact','controller'=>'index','module'=>'sesadvancedcomment'), 'default', true), $this->translate('SESADVMESSAGE'), array('class' => 'sessmoothbox')) ?>
      </li>
      <li class="sep">&middot;</li>
    <?php endif; ?>
  <?php $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
  if ($viewer_id && (('user' == $actionBody->subject_type && $viewer_id == $actionBody->subject_id) || ($viewer_id == $comment->poster_id))): ?>
  <?php if($activitycomments->preview && empty($activitycomments->showpreview)) { ?>
    <li id="remove_preview_<?php echo $comment->comment_id ?>">
      <a  href="javascript:void(0);" onclick="removePreview('<?php echo $activitycomments->getIdentity(); ?>','<?php echo $comment->comment_id; ?>', '<?php echo $comment->getType(); ?>')">
        <?php echo $this->translate("Remove Preview"); ?>
      </a>
    </li>
    <li id="remove_previewli_<?php echo $comment->comment_id ?>" class="sep">&middot;</li>
  <?php } endif; ?>
  <?php 
    $emoji = Engine_Api::_()->getApi('emoji','sesbasic')->getEmojisArray();
    $content = str_replace(array_keys($emoji),array_values($emoji),$comment->body);

  ?>
  <?php if( $canComment ) { ?>
  <?php } ?>
      <?php if(strlen(preg_replace("/(\\\u[0-9a-f]{4})+?|\s+/","",strip_tags($content))) && $islanguageTranslate){ ?>
         <li class="comments_reply_translate"> <a href="javascript:void(0);" onClick="socialSharingPopUp('https://translate.google.com/#auto/<?php echo $languageTranslate; ?>/<?php echo urlencode(strip_tags($comment->body)); ?>','Google');return false;"><?php echo $this->translate("Translate"); ?></a>
         </li>
          <li class="sep">&middot;</li>
   <?php } ?>
  <li class="comments_timestamp">
    <?php echo $this->timestamp($comment->creation_date); ?>
  </li>
  	<?php if(empty($_SESSION['fromActivityFeed']) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.enablencommentupdownvote', 1)){ ?>
      <?php echo $this->partial('_updownvote.tpl', 'sesadvancedcomment', array('item' => $comment,'isPageSubject'=>$this->isPageSubject)); ?>
    <?php } ?>
</ul>
