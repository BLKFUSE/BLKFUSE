<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _dataStatics.tpl 2020-06-13 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>

<?php if(isset($this->likeCountActive)):?>
	<span class="egifts_like_count_<?php echo $item->gift_id; ?>" title="<?php echo $this->translate(array('%s Like', '%s Likes', $item->like_count), $this->locale()->toNumber($item->like_count)) ?>"><?php echo $this->translate(array('%s Like', '%s Likes', $item->like_count), $this->locale()->toNumber($item->like_count)) ?></span>
<?php endif;?>
<?php if(isset($this->viewCountActive)):?>
	<span title="<?php echo $this->translate(array('%s View', '%s Views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>"><?php echo $this->translate(array('%s View', '%s Views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?></span>
<?php endif;?>
<?php if(isset($this->favoriteCountActive)):?>
	<span class="egifts_favourite_count_<?php echo $item->gift_id; ?>" title="<?php echo $this->translate(array('%s Favourite', '%s Favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count)) ?>"><?php echo $this->translate(array('%s Favourite', '%s Favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count)) ?></span>
<?php endif;?>        

