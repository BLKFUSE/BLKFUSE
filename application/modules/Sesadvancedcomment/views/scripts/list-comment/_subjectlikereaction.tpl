<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _subjectlikereaction.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesadvancedcomment/views/scripts/_jsFiles.tpl'; ?>
<?php $subject = !empty($this->subject) ? $this->subject : $this->action; ?>
<?php 
   $likesGroup = Engine_Api::_()->sesadvancedcomment()->likesGroup($subject,'subject'); 
   $commentCount = Engine_Api::_()->sesadvancedcomment()->commentCount($subject,'subject');
   if($commentCount || engine_count($likesGroup['data'])){
?>
<li class="sesadvcmt_comments_stats">
<?php if(engine_count($likesGroup['data'])){ ?>
  <div class="comments_stats_likes">
    <span class="comments_likes_reactions">
     <?php foreach($likesGroup['data'] as $type){ ?>
      <a title="<?php echo $this->translate('%s (%s)',$type['counts'],Engine_Api::_()->sesadvancedcomment()->likeWord($type['type'])) ?>" href="javascript:;" class="sessmoothbox" data-url="<?php echo $this->url(array('module' => 'sesadvancedcomment', 'controller' => 'ajax', 'action' => 'likes', 'type' => $type['type'], 'id' => $subject->getIdentity(),'resource_type'=>$likesGroup['resource_type'],'item_id'=>$likesGroup['resource_id'], 'format' => 'smoothbox'), 'default', true); ?>"><i style="background-image:url(<?php echo Engine_Api::_()->sesadvancedcomment()->likeImage($type['type']);?>);"></i></a>
      <?php } ?>
    </span>
      <a href="javascript:;" class="sessmoothbox" data-url="<?php echo $this->url(array('module' => 'sesadvancedcomment', 'controller' => 'ajax', 'action' => 'likes', 'id' => $subject->getIdentity(),'resource_type'=>$likesGroup['resource_type'],'item_id'=>$likesGroup['resource_id'], 'format' => 'smoothbox'), 'default', true); ?>"> <?php echo $this->FluentListUsers($subject->likes()->getAllLikesUsers(),'',$subject->likes()->getLike($this->viewer()),$this->viewer()); ?></a>
  </div>
<?php } ?>
  <div class="comments_stats_comments  comment_stats_<?php echo $subject->getIdentity(); ?>"">
    <?php if($commentCount > 0){ ?>
      <?php echo $this->partial('list-comment/_commentstats.tpl','sesadvancedcomment',array('subject'=>$subject,'commentCount'=>$commentCount));  ?>
  </div>
<?php } ?>                
</li>
<?php } ?>
