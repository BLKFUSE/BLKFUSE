<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _activitycommentreply.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesadvancedcomment/views/scripts/_jsFiles.tpl'; ?>
<?php $commentreply = $this->commentreply; 
      $activitycomments = Engine_Api::_()->getDbTable('activitycomments', 'sesadvancedactivity')->rowExists($commentreply->getIdentity());
      $isPageSubject = !empty($this->isPageSubject) ? $this->isPageSubject : $this->viewer();
      $action = $this->action;
      $canComment =( $action->getTypeInfo()->commentable &&
            $this->viewer()->getIdentity() &&
            Engine_Api::_()->authorization()->isAllowed($action->getCommentableItem(), null, 'comment')
             );
      $islanguageTranslate = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.translate', 1);
     $languageTranslate = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.language', 'en');
?>
<?php if(empty($this->likeOptions)){ ?>
<li id="comment-<?php echo $commentreply->comment_id; ?>">
  <template class="owner-info"><?php echo $this->getUserInfo($this->item($commentreply->poster_type, $commentreply->poster_id)); ?></template>
  <div class="comments_author_photo">
  <?php echo $this->htmlLink($this->item($commentreply->poster_type, $commentreply->poster_id)->getHref(),
    $this->itemPhoto($this->item($commentreply->poster_type, $commentreply->poster_id), 'thumb.icon', $action->getSubject()->getTitle())
  ) ?>
  </div>
  <div class="comments_reply_info comments_info">
  	<div class="sesadvcmt_comments_options">
      <a href="javascript:void(0);" class="sesadvcmt_cmt_hideshow sesadvcmt_comments_options_icon" onclick="showhidecommentsreply('<?php echo $commentreply->comment_id ?>', '<?php echo $action->getIdentity(); ?>')"><i id="hideshow_<?php echo $commentreply->comment_id ?>_<?php echo $action->getIdentity(); ?>" class="far fa-minus-square"></i></a>
       <?php if ( $this->viewer()->getIdentity() && (($this->viewer()->getIdentity() == $commentreply->poster_id || Engine_Api::_()->getDbtable('permissions', 'authorization')->getAllowed('user', $this->viewer()->level_id, 'activity')) || Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.reportenable', 1) ) ): ?>
      <div class="sesadvcmt_pulldown_wrapper sesact_pulldown_wrapper">
        <a href="javascript:void(0);" class="sesadvcmt_comments_options_icon"><i class="fa fa-angle-down"></i></a>
        <div class="sesadvcmt_pulldown">
          <div class="sesadvcmt_pulldown_cont">
            <ul>
							<?php if(($this->viewer()->getIdentity() == $commentreply->poster_id || Engine_Api::_()->getDbtable('permissions', 'authorization')->getAllowed('user', $this->viewer()->level_id, 'activity')) || ( ($this->subject() && method_exists($this->subject(),'canDeleteComment') && $this->subject()->canDeleteComment($this->subject())) )) { ?>
								<li>
								<?php echo $this->htmlLink(array(
											'route'=>'default',
											'module'    => 'sesadvancedactivity',
											'controller'=> 'index',
											'action'    => 'delete',
											'action_id' => $action->action_id,
											'comment_id'=> $commentreply->comment_id,
											), $this->translate('Delete'), array('class' => 'sescommentsmoothbox sesadvancedcomment_delete')) ?>
								</li>
								<?php if(empty($commentreply->gif_id) && empty($commentreply->emoji_id) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.editenable', 1)){ ?>
									<?php if((($this->subject() && method_exists($this->subject(),'canEditComment') && $this->subject()->canEditComment($this->subject()))) || ($this->viewer()->getIdentity() == $commentreply->poster_id || Engine_Api::_()->getDbtable('permissions', 'authorization')->getAllowed('user', $this->viewer()->level_id, 'activity'))) { ?>
										<li><?php echo $this->htmlLink(('javascript:;'), $this->translate('Edit'), array('class' => 'sesadvancedcomment_reply_edit')) ?></li>
										<?php } ?>
									<?php } ?>
							<?php } ?>
            <?php if($this->viewer()->getIdentity() != $commentreply->poster_id){ ?>
              <?php $reportEnable = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.reportenable', 1); ?>
              <?php if($reportEnable) { ?>
                <li>
                  <?php echo $this->htmlLink(Array("module"=> "core", "controller" => "report", "action" => "create", "route" => "default", "subject" => $commentreply->getGuid()), '<span>'. $this->translate("Report") . '</span>', array('onclick' => "openSmoothBoxInUrl(this.href);return false;" ,"class" => "sesadvancedcomment_report")); ?>
                </li>
              <?php } ?>
            <?php  } ?>
            </ul>
          </div>
        </div>
      </div>
   	<?php endif; ?>
   </div> 
   <div class="comments_content">
   <span class='comments_reply_author comments_author ses_tooltip' data-src="<?php echo $this->item($commentreply->poster_type, $commentreply->poster_id)->getGuid(); ?>">
			<?php echo $this->htmlLink($this->item($commentreply->poster_type, $commentreply->poster_id)->getHref(), $this->item($commentreply->poster_type, $commentreply->poster_id)->getTitle()); ?>
			<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('everification')) { ?>
				<?php $verifieddocuments = $verifieddocuments = Engine_Api::_()->getDbTable('documents', 'everification')->getAllUserDocuments(array('user_id' => $commentreply->poster_id, 'verified' => '1', 'fetchAll' => '1')); ?>
				<?php if(count($verifieddocuments) > 0) { ?>
					<i class="sesbasic_verify_icon" title="<?php echo $this->translate('Verified') ;?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M15.67 7.06l-1.08-1.34c-.17-.22-.28-.48-.31-.77l-.19-1.7a1.51 1.51 0 0 0-1.33-1.33l-1.7-.19c-.3-.03-.56-.16-.78-.33L8.94.32c-.55-.44-1.33-.44-1.88 0L5.72 1.4c-.22.17-.48.28-.77.31l-1.7.19c-.7.08-1.25.63-1.33 1.33l-.19 1.7c-.03.3-.16.56-.33.78L.32 7.05c-.44.55-.44 1.33 0 1.88l1.08 1.34c.17.22.28.48.31.77l.19 1.7c.08.7.63 1.25 1.33 1.33l1.7.19c.3.03.56.16.78.33l1.34 1.08c.55.44 1.33.44 1.88 0l1.34-1.08c.22-.17.48-.28.77-.31l1.7-.19c.7-.08 1.25-.63 1.33-1.33l.19-1.7c.03-.3.16-.56.33-.78l1.08-1.34c.44-.55.44-1.33 0-1.88zM6.5 12L3 8.5 4.5 7l2 2 5-5L13 5.55 6.5 12z"/></svg></i>
				<?php } ?>
			<?php } ?>
   </span>
    <?php 
      $emoji = Engine_Api::_()->getApi('emoji','sesbasic')->getEmojisArray();
      $content = str_replace(array_keys($emoji),array_values($emoji),$commentreply->body);
    ?>
    <?php
        echo $this->partial(
          '_activitycommentreplycontent.tpl',
          'sesadvancedcomment',
          array('commentreply'=>$commentreply,'isPageSubject'=>$isPageSubject)
        );    
?>    
 <?php } ?>
 </div>
       <?php if( $commentreply->likes()->getLikeCount() > 0 ): ?>
    <?php $likesGroup = Engine_Api::_()->sesadvancedcomment()->commentLikesGroup($commentreply,false); 
      if(engine_count($likesGroup['data'])){ 
    ?>
    <span class="comments_likes_total">
       <span class="comments_likes_reactions">
       <?php foreach($likesGroup['data'] as $type){ ?>
        <a title="<?php echo $this->translate('%s (%s)',$type['counts'],Engine_Api::_()->sesadvancedcomment()->likeWord($type['type'])) ?>" href="javascript:;" class="sessmoothbox" data-url="<?php echo $this->url(array('module' => 'sesadvancedactivity', 'controller' => 'ajax', 'action' => 'comment-likes', 'comment_id' => $commentreply->getIdentity(), 'id' => $action->getIdentity(),'resource_type'=>$action->getType(), 'format' => 'smoothbox'), 'default', true); ?>"><i style="background-image:url(<?php echo Engine_Api::_()->sesadvancedcomment()->likeImage($type['type']);?>);"></i></a>
        <?php } ?>
      </span>
        <a href="javascript:;" class="sessmoothbox" data-url="<?php echo $this->url(array('module' => 'sesadvancedactivity', 'controller' => 'ajax', 'action' => 'comment-likes', 'comment_id' => $commentreply->getIdentity(), 'id' => $action->getIdentity(),'resource_type'=>$action->getType(), 'format' => 'smoothbox'), 'default', true); ?>"><?php echo $commentreply->likes()->getLikeCount(); ?></a>
    </span>
    <?php } ?>
  <?php endif ?>
   <ul class="comments_reply_date comments_date" id="comments_reply_<?php echo $commentreply->comment_id; ?>_<?php echo $action->getIdentity(); ?>" style="display:block;">

    <?php if( $canComment ): ?>
          <template class="owner-info"><?php echo $this->getUserInfo($this->item($commentreply->poster_type, $commentreply->poster_id)); ?></template>
    <?php $isLiked = $commentreply->likes()->isLike($isPageSubject); ?>
    <?php if( $this->viewer()->getIdentity() && $this->canComment ):
      if($likeRow =  $commentreply->likes()->getLike($isPageSubject)){
          if($likeRow->getType() == 'activity_like') {
            $item_activity_like = Engine_Api::_()->getDbTable('activitylikes', 'sesadvancedactivity')->rowExists($likeRow->like_id); 
          } else {
            $item_activity_like = Engine_Api::_()->getDbTable('corelikes', 'sesadvancedactivity')->rowExists($likeRow->like_id); 
          }
          $like = true;
          if($item_activity_like)
            $type = $item_activity_like->type;
          else 
            $type = 1;;
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
                  <span  data-text="<?php echo $this->translate($getReaction->title);?>" data-actionid="<?php echo  $action->getIdentity(); ?>" data-commentid = "<?php echo  $commentreply->getIdentity(); ?>"  data-type="<?php echo $getReaction->reaction_id; ?>" data-guid="<?php echo $isPageSubject->getGuid(); ?>"  class="sesadvancedcommentcommentlike reaction_btn sesadvcmt_hoverbox_btn"><div class="reaction sesadvcmt_hoverbox_btn_icon"> <i class="react"  style="background-image:url(<?php echo Engine_Api::_()->sesadvancedcomment()->likeImage($getReaction->reaction_id);?>)"></i> </div></span>
                  <div class="text">
                    <div><?php echo $this->translate($getReaction->title); ?></div>
                  </div>
                </span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <a href="javascript:void(0);" <?php if(!empty($_SESSION["sesfromLightbox"])){ ?> id="sesadvancedcomment_like_action_<?php echo $commentreply->getIdentity(); ?>" <?php $_SESSION["sesfromLightbox"] = ''; }else{ ?> id="sesadvancedcomment_like_actionrec_<?php echo $commentreply->getIdentity(); ?>" <?php } ?> data-like="<?php echo $this->translate('SESADVLIKEC') ?>" data-unlike="<?php echo $this->translate('SESADVUNLIKEC') ?>" data-actionid="<?php echo  $action->getIdentity(); ?>" data-commentid = "<?php echo  $commentreply->getIdentity(); ?>" data-guid="<?php echo $isPageSubject->getGuid(); ?>" data-type="1" class="sesadvancedcommentcomment<?php echo $like ? 'unlike _reaction' : 'like' ;  ?>">
            <span><?php echo $this->translate($text);?></span>
          </a> 
        </li>
    <?php endif; ?>
    <li class="sep">&middot;</li> 
  <?php endif ?>
          
     
      <?php if(empty($_SESSION['fromActivityFeed']) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.enablencommentupdownvote', 1)){ ?>
      <?php echo $this->partial('_updownvote.tpl', 'sesadvancedcomment', array('item' => $commentreply,'isPageSubject'=>$this->isPageSubject)); ?>
    <?php } ?>
      
      
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.enablenestedcomments', 1)): ?>
        <li class="comments_reply_btn">
        	<?php echo $this->htmlLink('javascript:;', $this->translate('SESADVREPLY'), array('class' => 'sesadvancedcommentreplyreply')) ?>
        </li>
        <li class="sep">&middot;</li>
      <?php endif; ?>
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.enablemessagesellpost', 1) && $this->viewer()->getIdentity() && $commentreply->poster_id != $this->viewer()->getIdentity() && $action->type == "post_self_buysell"): ?>
      <li class="comments_reply">
        <?php echo $this->htmlLink($this->url(array('owner_id' =>$commentreply->poster_id,'action'=>'contact','controller'=>'index','module'=>'sesadvancedcomment'), 'default', true), $this->translate('SESADVMESSAGE'), array('class' => 'sessmoothbox')) ?>
      </li>
      <li class="sep">&middot;</li>
    <?php endif; ?>
    <?php if(isset($comment->body) && strlen(preg_replace("/(\\\u[0-9a-f]{4})+?|\s+/","",strip_tags($content))) && $islanguageTranslate){ ?>
         <li class="comments_reply_translate"> <a href="javascript:void(0);" onClick="socialSharingPopUp('https://translate.google.com/#auto/<?php echo $languageTranslate; ?>/<?php echo urlencode(strip_tags($comment->body)); ?>','Google');return false;"><?php echo $this->translate("Translate"); ?></a>
         </li>
          <li class="sep">&middot;</li>
   <?php } ?>
      <?php if ( $this->viewer()->getIdentity() &&
               (('user' == $action->subject_type && $this->viewer()->getIdentity() == $action->subject_id) ||
                ($this->viewer()->getIdentity() == $commentreply->poster_id) ||
                Engine_Api::_()->getDbtable('permissions', 'authorization')->getAllowed('user', $this->viewer()->level_id, 'activity')  ) ): ?>
      <?php if(!empty($activitycomments->preview) && empty($activitycomments->showpreview)) { ?>
        <li id="remove_preview_<?php echo $commentreply->comment_id ?>">
          <a  href="javascript:void(0);" onclick="removePreview('<?php echo $activitycomments->getIdentity(); ?>','<?php echo $commentreply->comment_id; ?>', '<?php echo $commentreply->getType(); ?>')">
            <?php echo $this->translate("Remove Preview"); ?>
          </a>
        </li>
        <li id="remove_previewli_<?php echo $commentreply->comment_id ?>" class="sep">&middot;</li>
      <?php } endif; ?>
      
       <li class="comments_reply_timestamp">
         <?php echo $this->timestamp($commentreply->creation_date); ?>
       </li>       
    </ul>
 <?php if(empty($this->likeOptions)){ ?>
  </div>
</li>
<?php } ?>
