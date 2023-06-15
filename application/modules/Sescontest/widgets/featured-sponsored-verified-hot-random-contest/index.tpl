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

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?> 
<div class="sescontest_featured_contest_view sesbasic_clearfix sesbasic_bxs clear">
	<?php $contestCount = 1;?>
<?php $dateinfoParams['starttime'] = true; ?>
  <?php $dateinfoParams['endtime']  =  true; ?>
  <?php $dateinfoParams['timezone']  =  true; ?>
	<?php foreach($this->contests as $contest):?>
    <div class="featured_contest_list sesbasic_bxs <?php if($contestCount == 1):?>featured_contest_listing<?php endif;?>" style="height:<?php echo is_numeric($this->params['height']) ? $this->params['height'].'px' : $this->params['height']; ?>;">
      <div class="featured_contest_list_inner sescontest_thumb">
      <a href="<?php echo $contest->getHref();?>"><img src="<?php echo $contest->getPhotoUrl();?>" /></a>
      <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_mediaType.tpl';?>
      <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->hotLabel)):?>
        <div class="sescontest_list_labels sesbasic_animation">
          <?php if(isset($this->featuredLabelActive) &&$contest->featured == 1):?>
            <span class="sescontest_label_featured" title="<?php echo $this->translate('FEATURED');?>"><i class="fa fa-star"></i></span>
          <?php endif;?>
          <?php if(isset($this->sponsoredLabelActive) && $contest->sponsored == 1):?>
            <span class="sescontest_label_sponsored" title="<?php echo $this->translate('SPONSORED');?>"><i class="fa fa-star"></i></span>
          <?php endif;?>
          <?php if(isset($this->hotLabelActive) && $contest->hot == 1):?>
            <span class="sescontest_label_hot" title="<?php echo $this->translate('HOT');?>"><i class="fa fa-star"></i></span>
          <?php endif;?>
        </div>
      <?php endif;?>
      <?php if(isset($this->socialSharingActive) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive)):?>
        <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $contest->getHref()); ?>
        <div class="sescontest_list_thumb_over">
          <a href="<?php echo $contest->getHref(); ?>" data-url = "<?php echo $contest->getType() ?>"></a>
          <div class="sescontest_list_btns"> 
            <?php if(isset($this->socialSharingActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.allow.share', 1)):?>
              
              <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $contest, 'socialshare_enable_plusicon' => $this->params['socialshare_enable_plusicon'], 'socialshare_icon_limit' => $this->params['socialshare_icon_limit'])); ?>
            <?php endif;?>
            <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 ):?>
              <?php $canComment =  $contest->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');?>
              <?php if(isset($this->likeButtonActive) && $canComment):?>
                <!--Like Button-->
                <?php $LikeStatus = Engine_Api::_()->sescontest()->getLikeStatus($contest->contest_id,$contest->getType()); ?>
                <a href="javascript:;" data-type="like_view" data-url="<?php echo $contest->contest_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sescontest_like_<?php echo $contest->contest_id ?> sescontest_likefavfollow <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-thumbs-up"></i><span><?php echo $contest->like_count;?></span></a>
              <?php endif;?>
              <?php if(isset($this->favouriteButtonActive) && isset($contest->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.allow.favourite', 1)): ?>
                <?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sescontest')->isFavourite(array('resource_type'=>'contest','resource_id'=>$contest->contest_id)); ?>
                <a href="javascript:;" data-type="favourite_view" data-url="<?php echo $contest->contest_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sescontest_favourite_<?php echo $contest->contest_id ?> sescontest_likefavfollow <?php echo ($favStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-heart"></i><span><?php echo $contest->favourite_count;?></span></a>
              <?php endif;?>
            <?php endif;?>
          </div>
        </div>
      <?php endif;?>		
      <div class="featured_contest_list_contant">
        <?php if($contest->category_id != '' && intval($contest->category_id) && !is_null($contest->category_id)):?> 
          <?php $categoryItem = Engine_Api::_()->getItem('sescontest_category', $contest->category_id);?>
          <?php if($categoryItem):?>
            <p class="featured_tag"><a href="<?php echo $categoryItem->getHref(); ?>"><?php echo $categoryItem->category_name; ?></a></p>
          <?php endif;?>
        <?php endif;?>
        <p class="title">
          <a href="<?php echo $contest->getHref();?>"><?php echo $contest->getTitle();?></a>
          <?php if(isset($this->verifiedLabelActive) && $contest->verified == 1):?>
            <i class="sescontest_label_verified fa fa-check-circle" title='<?php echo $this->translate("Verified");?>'></i>
          <?php endif;?>
        </p>
        <?php if(isset($this->byActive)):?>
       		<p class="_by"><?php echo $this->translate('by');?>&nbsp;<?php echo $this->htmlLink($contest->getOwner()->getHref(), $contest->getOwner()->getTitle());?></p>	
        <?php endif; ?>
        <?php if(isset($this->descriptionActive)):?>
       		<p class="_des"><?php echo $this->string()->truncate($this->string()->stripTags($contest->description), $this->params['description_truncation']) ?></p>	
        <?php endif; ?>
        <div class="sesbasic_clearfix">
          <?php if(isset($this->startenddateActive)):?>
            <div class="sescontest_list_stats">
            	<span>
                <i class="fa fa-calendar"></i>
                <?php echo $this->contestStartEndDates($contest, $dateinfoParams);?>
            	</span>
            </div>
          <?php endif;?>
           <div class="sescontest_list_stats floatL">
              <?php if(isset($this->likeActive) && isset($contest->like_count)) { ?>
                <span title="<?php echo $this->translate(array('%s like', '%s likes', $contest->like_count), $this->locale()->toNumber($contest->like_count)); ?>"><i class="fa fa-thumbs-up"></i><?php echo $contest->like_count; ?></span>
              <?php } ?>
              <?php if(isset($this->commentActive) && isset($contest->comment_count)) { ?>
                <span title="<?php echo $this->translate(array('%s comment', '%s comments', $contest->comment_count), $this->locale()->toNumber($contest->comment_count))?>"><i class="fa fa-comment"></i><?php echo $contest->comment_count;?></span>
              <?php } ?>
              <?php if(isset($this->favouriteActive) && isset($contest->favourite_count) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.enable.favourite', 1)) { ?>
                <span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $contest->favourite_count), $this->locale()->toNumber($contest->favourite_count))?>"><i class="fa fa-heart"></i><?php echo $contest->favourite_count;?></span>
              <?php } ?>
              <?php if(isset($this->viewActive) && isset($contest->view_count)) { ?>
                <span title="<?php echo $this->translate(array('%s view', '%s views', $contest->view_count), $this->locale()->toNumber($contest->view_count))?>"><i class="fa fa-eye"></i><?php echo $contest->view_count; ?></span>
              <?php } ?>
              <?php if(isset($this->followActive) && isset($contest->follow_count)) { ?>
                <span title="<?php echo $this->translate(array('%s follower', '%s followers', $contest->follow_count), $this->locale()->toNumber($contest->follow_count))?>"><i class="fa fa-users"></i><?php echo $contest->follow_count; ?></span>
              <?php } ?>
              <?php if(isset($this->followActive) && isset($contest->follow_count)) { ?>
                <span title="<?php echo $this->translate(array('%s entry', '%s entries', $contest->join_count), $this->locale()->toNumber($contest->join_count))?>"><i class="fa fa-sign-in-alt"></i><?php echo $contest->join_count; ?></span>
              <?php } ?>
            </div>
        </div>
      </div>
    </div>
		</div>
		<?php $contestCount++;?>
	<?php endforeach;?>
</div>

