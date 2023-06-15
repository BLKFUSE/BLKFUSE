<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _dataStatics.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php if(isset($this->likeActive)):?>
  <span title="<?php echo $this->translate(array('%s Like', '%s Likes', $contest->like_count), $this->locale()->toNumber($contest->like_count)) ?>">
    <i class="fa fa-thumbs-up"></i>
    <span><?php echo $contest->like_count;?></span>
  </span>
<?php endif;?>
<?php if(isset($this->commentActive)):?>
  <span title="<?php echo $this->translate(array('%s Comment', '%s Comments', $contest->comment_count), $this->locale()->toNumber($contest->comment_count)) ?>">
    <i class="fa fa-comment"></i>
    <span><?php echo $contest->comment_count;?></span>
  </span>
<?php endif;?>
<?php if(isset($this->viewActive)):?>
  <span title="<?php echo $this->translate(array('%s View', '%s Views', $contest->view_count), $this->locale()->toNumber($contest->view_count)) ?>">
    <i class="fa fa-eye"></i>
    <span><?php echo $contest->view_count;?></span>
  </span>
<?php endif;?>
<?php if(isset($this->favouriteActive)):?>
  <span title="<?php echo $this->translate(array('%s Favourite', '%s Favourites', $contest->favourite_count), $this->locale()->toNumber($contest->favourite_count)) ?>">
    <i class="fa fa-heart"></i>
    <span><?php echo $contest->favourite_count;?></span>
  </span>
<?php endif;?>
<?php if(isset($this->followActive) && isset($contest->follow_count)):?>
  <span title="<?php echo $this->translate(array('%s Follower', '%s Followers', $contest->follow_count), $this->locale()->toNumber($contest->follow_count)) ?>">
    <i class="fa fa-users"></i>
    <span><?php echo $contest->follow_count;?></span>
  </span>
<?php endif;?>