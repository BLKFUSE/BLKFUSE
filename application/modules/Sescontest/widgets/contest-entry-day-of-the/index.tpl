<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $contest = $this->result;?>
<?php if($contest->getType() == 'contest'):?>
  <?php $likeDataType = 'like_view';?>
  <?php $likeClassType = "sescontest_like_".$contest->getIdentity(); ?>
  <?php $favouriteDataType = 'favourite_view';?>
  <?php $favouriteClassType = "sescontest_favourite_".$contest->getIdentity(); ?>
  <?php $followDataType = 'follow_view';?>
  <?php $followClassType = "sescontest_follow_".$contest->getIdentity(); ?>
<?php else:?>
  <?php $likeDataType = 'like_entry_view';?>
  <?php $likeClassType = "sescontest_entry_like_".$contest->participant_id; ?>
  <?php $favouriteDataType = 'favourite_entry_view';?>
  <?php $favouriteClassType = "sescontest_entry_favourite_".$contest->participant_id; ?>
  <?php if(!$this->viewer()->getIdentity()):?><?php $levelId = 5;?><?php else:?><?php $levelId = $this->viewer()->level_id;?><?php endif;?>
  <?php $voteType = Engine_Api::_()->authorization()->getPermission($levelId, 'participant', 'allow_entry_vote');?>
  <?php if ($voteType != 0 && (($voteType == 1 && $contest->owner_id != $this->viewer()->getIdentity()) || $voteType == 2)):?>
    <?php $canIntegrate = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.vote.integrate', 0);?>
  <?php else:?>
    <?php $canIntegrate = 0;?>
  <?php endif;?>
<?php endif;?>
<div class="sesbasic_bxs sesbasic_clearfix sescontest_sidebar_data">
  <div class="sescontest_advgrid_item sesbasic_clearfix item">
    <article>
      <div class="sescontest_advgrid_item_header">
        <?php if(isset($this->titleActive) ){ ?>
          <?php if(strlen($contest->getTitle()) > $this->params['title_truncation']){ 
            $title = mb_substr($contest->getTitle(),0,($this->params['title_truncation'] - 3)).'...';?>
            "<?php echo $this->htmlLink($contest->getHref(),$title); ?>"
          <?php }else{ ?>
            "<?php echo $this->htmlLink($contest->getHref(),$contest->getTitle()) ?>"
          <?php } ?>
          <?php if($contest->getType() == 'contest'):?>
          	<span class="sesbasic_text_light"><?php echo $this->translate("Contest");?></span>
          <?php endif;?>  
        <?php } ?>
      </div>
      <div class="sescontest_advgrid_item_thumb sescontest_list_thumb" style="height:<?php echo $this->params['imageheight'] ?>px;">
        <?php $href = $contest->getHref();?>
        <a href="<?php echo $href; ?>" class="sescontest_advgrid_img">
          <span class="sesbasic_animation" style="background-image:url(<?php echo $contest->getPhotoUrl('thumb.profile'); ?>);"></span>
        </a>
        <?php $owner = $contest->getOwner(); ?>
        <div class="sescontest_advgrid_item_owner">
          <span class="sescontest_advgrid_item_owner_img">
            <?php echo $this->htmlLink($owner->getHref(), $this->itemPhoto($owner, 'thumb.normal', $owner->getTitle()), array('title'=>$owner->getTitle())) ?>
          </span>
          <?php  if(isset($this->socialSharingActive) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive) || isset($this->followButtonActive)):?>
        <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $contest->getHref()); ?>
            <span class="useroption">
              <a href="javascript:void(0);" class="fa fa-angle-down"></a>
              <div class="sescontest_advgrid_item_btns">
                <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.allow.share', 1)):?>
                  <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $contest, 'socialshare_enable_plusicon' => $this->params['socialshare_enable_plusicon'], 'socialshare_icon_limit' => $this->params['socialshare_icon_limit'])); ?>
                  <?php endif;?>
                  <?php if($contest->getType() == 'participant'):?>
                    <?php $canComment =  Engine_Api::_()->authorization()->isAllowed('participant', $this->viewer(), 'comment');?>
                  <?php else:?>
                    <?php $canComment =  $contest->authorization()->isAllowed($this->viewer(), 'comment');?>
                  <?php endif;?>
                  <?php if(isset($this->likeButtonActive) && $canComment):?>
                    <?php $likeStatus = Engine_Api::_()->sescontest()->getLikeStatus($contest->getIdentity(),$contest->getType()); ?>
                    <a href="javascript:;" data-type="<?php echo $likeDataType;?>" <?php if($likeDataType == 'like_entry_view'):?>data-integrate = "<?php echo $canIntegrate;?>"<?php endif;?> data-url="<?php echo $contest->getIdentity() ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn $likeClassType sescontest_likefavfollow <?php echo ($likeStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-thumbs-up"></i><span><?php echo $contest->like_count;?></span></a>
                  <?php endif;?>
                  <?php if(isset($this->favouriteButtonActive) && (($contest->getType() == 'contest' && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.allow.favourite', 1)) || ($contest->getType() == 'participant' && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.entry.allow.favourite', 1)))):?>
                     <?php $favouriteStatus = Engine_Api::_()->getDbTable('favourites', 'sescontest')->isFavourite(array('resource_id' => $contest->getIdentity(),'resource_type' => $contest->getType())); ?>
                    <a href="javascript:;" data-type="<?php echo $favouriteDataType;?>" data-url="<?php echo $contest->getIdentity() ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn $favouriteClassType sescontest_likefavfollow <?php echo ($favouriteStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-heart"></i><span><?php echo $contest->favourite_count;?></span></a>
                  <?php endif;?>
                  <?php if($contest->getType() == 'contest' && isset($this->followButtonActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.allow.follow', 1) && $this->viewer()->getIdentity() != $contest->user_id):?>
                    <?php $followStatus = Engine_Api::_()->getDbTable('followers', 'sescontest')->isFollow(array('resource_id' => $contest->getIdentity(),'resource_type' => $contest->getType())); ?>
                    <a href="javascript:;" data-type="<?php echo $followDataType;?>" data-url="<?php echo $contest->getIdentity() ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_follow_btn $followClassType sescontest_likefavfollow <?php echo ($followStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-check"></i><span><?php echo $contest->follow_count;?></span></a>
                  <?php endif;?>
              </div>
            </span>
          <?php endif; ?>
          <?php if($contest->getType() == 'contest'):?>
            <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_mediaType.tpl';?>
          <?php endif;?>
          <span class="itemcont">
            <?php if(isset($this->postedbyActive)){ ?>
              <span class="ownername">
                <?php echo $this->translate('by');?>&nbsp;<?php echo $this->htmlLink($owner->getHref(),$owner->getTitle() ) ?>
              </span>
            <?php  } ?>
        	</span>
        </div>
        <?php if($contest->getType() == 'contest'):?>
          <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) && isset($this->hotLabelActive)){ ?>
            <div class="sescontest_list_labels sesbasic_animation">
              <?php if(isset($this->featuredLabelActive) && $contest->featured){ ?>
                <span class="sescontest_label_featured" title="<?php echo $this->translate('Featured');?>"><i class="fa fa-star"></i></span>
              <?php } ?>
              <?php if(isset($this->sponsoredLabelActive) && $contest->sponsored){ ?>
                <span class="sescontest_label_sponsored" title="<?php echo $this->translate('Sponsored');?>"><i class="fa fa-star"></i></span>
              <?php } ?>
              <span class="sescontest_label_hot" title="<?php echo $this->translate('Hot');?>"><i class="fa fa-star"></i></span>
            </div>
          <?php } ?>
          <a href="<?php echo $href; ?>" class="_viewbtn sesbasic_link_btn sesbasic_animation"><?php echo $this->translate("View Contest");?></a>
        <?php else:?>
          <a href="<?php echo $href; ?>" class="_viewbtn sesbasic_link_btn sesbasic_animation"><?php echo $this->translate("View Entry");?></a>
        <?php endif;?>
        
      </div>
      <div class="sescontest_list_item_stats">
        <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataStatics.tpl';?>
      </div>
      <?php if($contest->getType() == 'contest'):?>
          <div class="sescontest_advgrid_item_footer">
            <?php include APPLICATION_PATH . '/application/modules/Sescontest/views/scripts/_dataBar.tpl'; ?>
          </div>
      <?php endif;?>
    </article>
  </div>
</div>
