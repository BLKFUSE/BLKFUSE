<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _dataSharing.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<?php $viewerId = $viewer->getIdentity();?>
<?php if($contest->is_approved):?>
  <?php if(isset($this->socialSharingActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.allow.share', 1)):?>
    <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $contest, 'socialshare_enable_plusicon' => $this->params["socialshare_enable_plusicon"], 'socialshare_icon_limit' => $this->params["socialshare_icon_limit"])); ?>
  <?php endif;?>
  <?php $canComment = Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'create');?>
  <?php if(isset($this->likeButtonActive) && $canComment):?>
    <?php $likeStatus = Engine_Api::_()->sescontest()->getLikeStatus($contest->contest_id,$contest->getType()); ?>
    <a href="javascript:;" title="<?php echo ($likeStatus) ? $this->translate('Unlike') : $this->translate('Like') ; ?>" data-type="like_view" data-url="<?php echo $contest->contest_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sescontest_like_<?php echo $contest->contest_id ?> sescontest_likefavfollow <?php echo ($likeStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-thumbs-up"></i><span><?php echo $contest->like_count;?></span></a>
  <?php endif;?>
  <?php if($viewerId):?>
    <?php if(isset($this->favouriteButtonActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.allow.favourite', 1)):?>
      <?php $favouriteStatus = Engine_Api::_()->getDbTable('favourites', 'sescontest')->isFavourite(array('resource_id' => $contest->contest_id,'resource_type' => $contest->getType())); ?>
      <a href="javascript:;" title="<?php echo ($favouriteStatus) ? $this->translate('Remove as Favourite') : $this->translate('Add to Favourite') ; ?>" data-type="favourite_view" data-url="<?php echo $contest->contest_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sescontest_favourite_<?php echo $contest->contest_id ?> sescontest_likefavfollow <?php echo ($favouriteStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-heart"></i><span><?php echo $contest->favourite_count;?></span></a>
    <?php endif;?>
    <?php if($viewerId != $contest->user_id && isset($this->followButtonActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.allow.follow', 1)):?>
      <?php $followStatus = Engine_Api::_()->getDbTable('followers', 'sescontest')->isFollow(array('resource_id' => $contest->contest_id,'resource_type' => $contest->getType())); ?>
      <a href="javascript:;" title="<?php echo ($followStatus) ? $this->translate('Unfollow') : $this->translate('Follow') ; ?>" data-type="follow_view" data-url="<?php echo $contest->contest_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_follow_btn sescontest_follow_<?php echo $contest->contest_id ?> sescontest_likefavfollow <?php echo ($followStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-check"></i><span><?php echo $contest->follow_count;?></span></a>
    <?php endif;?>
  <?php endif;?>
<?php endif;?>