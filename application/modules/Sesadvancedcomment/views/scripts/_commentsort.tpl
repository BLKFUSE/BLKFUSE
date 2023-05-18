<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _commentsort.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php 
  $action = $this->action;
  $commentCount = $this->commentCount;
  $isPageSubject = !empty($this->isPageSubject) ? $this->isPageSubject : $this->viewer();
  $enableordering = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.enableordering', 'a:4:{i:0;s:6:"newest";i:1;s:6:"oldest";i:2;s:5:"liked";i:3;s:7:"replied";}'));
?>
<?php  $reverseOrder = Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.commentreverseorder', false); ?>

<?php if(!empty($enableordering)) { ?>
<div class="sesadvcmt_comments_sort">
<div class="sesadvcmt_pulldown_wrapper sesact_pulldown_wrapper" data-actionid = "<?php echo $action->getIdentity(); ?>">
  <a href="javascript:void(0);" class="search_advcomment_txt"><span><b><?php echo $this->translate('Sort By:') ?></b> <?php echo $this->translate($reverseOrder) ? $this->translate('Newest') : $this->translate('Oldest') ?> </span> <i class="fa fa-caret-down"></i></a>
  <div class="sesadvcmt_pulldown">
    <div class="sesadvcmt_pulldown_cont">
      <ul class="search_adv_comment">
        <?php if(engine_in_array('newest', $enableordering)): ?>
        <li><a href="javascript:;" data-type="newest" class="search_adv_comment_a <?php echo (($reverseOrder && @$this->onlyComment) || $this->searchType == 'newest' ? 'active' : '') ?>"><?php echo $this->translate("Newest"); ?></a></li>
        <?php endif; ?>
        <?php if(engine_in_array('oldest', $enableordering)): ?>
        <li><a href="javascript:;" data-type="oldest" class="search_adv_comment_a <?php echo ((!$reverseOrder && @$this->onlyComment) || $this->searchType == 'oldest' ? 'active' : '') ?>"><?php echo $this->translate("Oldest"); ?></a></li>
        <?php endif; ?>
        <?php if(engine_in_array('liked', $enableordering)): ?>
        <li><a href="javascript:;" data-type="liked" class="search_adv_comment_a <?php echo ($this->searchType == 'liked' ? 'active' : '') ?>"><?php echo $this->translate("Liked"); ?></a></li>
        <?php endif; ?>
        <?php if(engine_in_array('replied', $enableordering)): ?>
        <li><a href="javascript:;" data-type="replied" class="search_adv_comment_a <?php echo ($this->searchType == 'replied' ? 'active' : '') ?>"><?php echo $this->translate("Replied"); ?></a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>
</div>
<?php } ?>
