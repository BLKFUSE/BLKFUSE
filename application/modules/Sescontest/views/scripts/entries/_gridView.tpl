<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _gridView.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php if(strlen($entry->getTitle()) > $this->params['grid_title_truncation']):?>
  <?php $title = mb_substr($entry->getTitle(),0,$this->params['grid_title_truncation']).'...';?>
<?php else: ?>
  <?php $title = $entry->getTitle();?>
<?php endif; ?>
<?php if(!$this->viewer()->getIdentity()):?><?php $levelId = 5;?><?php else:?><?php $levelId = $this->viewer()->level_id;?><?php endif;?>
<?php $voteType = Engine_Api::_()->authorization()->getPermission($levelId, 'participant', 'allow_entry_vote');?>
<?php if ($voteType != 0 && (($voteType == 1 && $entry->owner_id != $this->viewer()->getIdentity()) || $voteType == 2)):?>
  <?php $canIntegrate = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.vote.integrate', 0);?>
<?php else:?>
  <?php $canIntegrate = 0;?>
<?php endif;?>
<li class="sescontest_entry_item" style="width:<?php echo is_numeric($this->params['width_grid']) ? $this->params['width_grid'].'px' : $this->params['width_grid'];?>;">
   <article class="sesbasic_clearfix" style="min-height:386px;">
     <div class="sescontest_entry_item_thumb sescontest_list_thumb" style="height:<?php echo is_numeric($this->params['height_grid']) ? $this->params['height_grid'].'px' : $this->params['height_grid'];?>;">
      <?php if(isset($this->photoActive)):?>
        <a class="sescontest_entry_item_thumb_img"><span style="background-image:url(<?php echo $entry->getPhotoUrl('thumb.main'); ?>);"></span></a>
      <?php endif;?>
       <div class="sescontest_list_thumb_over">
       	<a href="<?php echo $entry->getHref();?>"></a>
         <div class="sescontest_list_btns">
           <?php if(isset($this->socialsharingActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.entry.allow.share', 1)):?>
            <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $entry, 'socialshare_enable_plusicon' => $this->params['socialshare_enable_plusicon'], 'socialshare_icon_limit' => $this->params['socialshare_icon_limit'])); ?>
           <?php endif;?>
           <?php if(isset($this->likeButtonActive) && $this->canComment):?>
             <a href="javascript:;" data-type="like_entry_view" data-integrate = "<?php echo $canIntegrate;?>" data-url="<?php echo $entry->participant_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sescontest_entry_like_<?php echo $entry->participant_id ?> sescontest_likefavfollow <?php echo ($likeStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-thumbs-up"></i><span><?php echo $entry->like_count;?></span></a>
           <?php endif;?>
           <?php if(isset($this->favouriteButtonActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.entry.allow.favourite', 1)):?>
            <a href="javascript:;" data-type="favourite_entry_view" data-url="<?php echo $entry->contest_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sescontest_entry_favourite_<?php echo $entry->contest_id ?> sescontest_likefavfollow <?php echo ($favouriteStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-heart"></i><span><?php echo $entry->favourite_count;?></span></a> 
           <?php endif;?>
         </div>
       </div>
     </div>
     <div class="sescontest_entry_item_det sesbasic_clearfix">
       <?php if(isset($this->pPhotoActive)):?>
        <div class="sescontest_entry_owner_photo">
          <?php if($owner->photo_id):?>
          <a href="<?php echo $owner->getHref();?>"><img src="<?php echo Engine_Api::_()->storage()->get($owner->photo_id)->getPhotoUrl('thumb.icon'); ?>" alt=""></a>
          <?php else:?>
            <a href="<?php echo $owner->getHref();?>"><img src="application/modules/User/externals/images/nophoto_user_thumb_icon.png" alt=""></a>
          <?php endif;?>
        </div>
       <?php endif;?>
       <?php if(isset($this->voteButtonActive)):?>
        <div class="sescontest_entry_item_vote_btn">
          <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_voteData.tpl';?>
        </div>
       <?php endif;?>
       <div class="sescontest_entry_item_det_info">
         <?php if(isset($this->titleActive)):?>
           <div class="sescontest_entry_item_title"><a href="<?php echo $entry->getHref();?>"><?php echo $title;?></a></div>
         <?php endif;?>
         <div class="sescontest_entry_item_date sesbasic_text_light"><?php if(isset($this->pNameActive)):?><?php echo $this->translate('By');?>&nbsp;<?php echo $this->htmlLink($owner->getHref(), $owner->getTitle()) ?>&nbsp;<?php endif;?><?php if(isset($this->submitDateActive)):?><?php echo $this->translate('on');?>&nbsp;<?php echo date('d-m-Y',strtotime($entry->creation_date));?><?php endif;?></div>
       </div>
     </div>
     <?php if(isset($this->griddescriptionActive)):?>
      <div class="sescontest_entry_item_discription sesbasic_clearfix">
        <p><?php echo $this->string()->truncate($this->string()->stripTags($entry->description), $this->params['grid_description_truncation']) ?></p>	
      </div>
     <?php endif;?>
       <div class="sescontest_entry_item_footer sesbasic_clearfix">
       <span class="sescontest_entry_item_stats floatL sesbasic_text_light"> 
         <?php if(isset($this->likeCountActive)):?>
          <span title="<?php echo $this->translate(array('%s Like', '%s Likes', $entry->like_count), $this->locale()->toNumber($entry->like_count)) ?>">
            <i class="fa fa-thumbs-up"></i>
            <span><?php echo $entry->like_count;?></span>
          </span>
         <?php endif;?>
         <?php if(isset($this->commentCountActive)):?>
          <span title="<?php echo $this->translate(array('%s Comment', '%s Comments', $entry->comment_count), $this->locale()->toNumber($entry->comment_count)) ?>">
            <i class="fa fa-comment"></i>
            <span><?php echo $entry->comment_count;?></span>
          </span>
         <?php endif;?>
         <?php if(isset($this->viewCountActive)):?>
          <span title="<?php echo $this->translate(array('%s View', '%s Views', $entry->view_count), $this->locale()->toNumber($entry->view_count)) ?>">
            <i class="fa fa-eye"></i>
            <span><?php echo $entry->view_count;?></span>
          </span>
         <?php endif;?>
         <?php if(isset($this->favouriteCountActive)):?>
          <span title="<?php echo $this->translate(array('%s Favourite', '%s Favourites', $entry->favourite_count), $this->locale()->toNumber($entry->favourite_count)) ?>">
            <i class="fa fa-heart"></i>
            <span><?php echo $entry->favourite_count;?></span>
          </span>
         <?php endif;?>
       </span>
       <?php if(isset($this->voteCountActive) && (!$this->contest->resulttime || strtotime($this->contest->resulttime) <= time())):?>
          <span class="floatR sescontest_entry_item_vote">
            <?php echo $this->translate(array('%s Vote', '%s Votes', $entry->vote_count), $this->locale()->toNumber($entry->vote_count)) ?>
          </span>
        <?php endif;?>
     </div>
   </article>
 </li>