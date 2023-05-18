<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<?php if (!empty($this->viewer_id)): ?>
  <?php      
  $likeUser = Engine_Api::_()->sesbasic()->getLikeStatus($this->subject->user_id, 'user');
  $likeClass = (!$likeUser) ? 'fa-thumbs-up' : 'fa-thumbs-down' ;
  $likeText = ($likeUser) ?  $this->translate('UNLIKE') : $this->translate('LIKE') ;
  ?>
  <div class="sesmember_button">
    <a href='javascript:;' data-url='<?php echo $this->subject->getIdentity(); ?>' class='sesbasic_animation sesbasic_link_btn sesmember_button_like_user sesmember_button_like_user_<?php echo $this->subject->getIdentity(); ?>'><i class='fa <?php echo $likeClass ; ?>'></i><span><?php echo $likeText; ?></span></a>     
  </div>
<?php endif; ?>
