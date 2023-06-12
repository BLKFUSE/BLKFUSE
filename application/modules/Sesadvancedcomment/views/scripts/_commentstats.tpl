<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _commentstats.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php 
  $action = $this->action;
  $commentCount = $this->commentCount;
  $isPageSubject = !empty($this->isPageSubject) ? $this->isPageSubject : $this->viewer();
  $enableordering = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.enableordering', 'a:4:{i:0;s:6:"newest";i:1;s:6:"oldest";i:2;s:5:"liked";i:3;s:7:"replied";}'));
?>
<a class="comment_btn_open select_action_<?php echo $action->getIdentity(); ?>" data-actionid = "<?php echo $action->getIdentity(); ?>" href="javascript:void(0);"><?php echo $this->translate(array('%s comment', '%s comments',  $commentCount), $this->locale()->toNumber( $commentCount))?></a>
