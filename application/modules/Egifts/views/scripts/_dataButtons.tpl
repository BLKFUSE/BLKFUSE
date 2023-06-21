<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _dataButtons.tpl 2020-06-13 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php  $viewer = Engine_Api::_()->user()->getViewer(); ?>
<?php $viewerId = $viewer->getIdentity();?>
<?php  if(!$viewerId)
          return;
?>
<?php if(isset($this->likeButtonActive)):?>
<?php $likeStatus = Engine_Api::_()->egifts()->getLikeStatus($item->gift_id,$item->getType()); ?>
<div class="_likebtn">
<a href="javascript:;" data-url="<?php echo $item->gift_id; ?>" data-type="egifts_like_view" 	class="egifts_btn egifts_like_btn egifts_like_<?php echo $item->gift_id; ?> 
	egifts_likefavourite <?php echo ($likeStatus) ? 'btnactive' : '' ; ?>"> <i class="fa fa-thumbs-up sesbasic_text_light"></i><span><?php echo $item->like_count;?></span></a>
</div>
<?php endif;  ?>
<?php if(isset($this->favoriteButtonActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('egifts.allow.favourite', 1)):?>
<?php $favouriteStatus = Engine_Api::_()->getDbTable('favourites', 'egifts')->isFavourite(array('resource_id' => $item->gift_id,'resource_type' => $item->getType())); ?>
<div class="_favbtn">
  <a href="javascript:;" class="egifts_btn egifts_fav_btn egifts_likefavourite egifts_favourite_<?php echo $item->gift_id; ?>  <?php echo ($favouriteStatus) ? 'btnactive' : '' ; ?>" data-type="egifts_favourite_view" data-url="<?php echo $item->gift_id; ?>"><i class="fa fa-heart sesbasic_text_light"></i><span><?php echo $item->favourite_count;?></span></a>
</div>
<?php endif; ?>