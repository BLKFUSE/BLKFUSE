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

<?php $likeCounter = 0;?>
<?php $favouriteCounter = 0;?>
<?php $followCounter = 0;?>
<div class="sesbasic_sidebar_block sesbasic_clearfix sesbasic_bxs">
	<!------- people Liked contest ------->
  <?php if(isset($this->likeActive)):?>
    <?php if(is_countable($this->likeMembers) && engine_count($this->likeMembers)):?>
      <div class="sescontest_people_like_field sesbasic_clearfix">
        <div class="sescontest_people_like_title sesbasic_clearfix">
        	<i class="fa fa-thumbs-up"></i>
        	<span><?php echo $this->translate("People Who Liked");?></span>
        </div>
        <ul class="sesbasic_clearfix">
          <?php foreach($this->likeMembers as $member):?>
            <?php if($likeCounter == $this->params['view_more_like']):?>
              <?php break;?>
            <?php endif;?>
              <?php $item = Engine_Api::_()->getItem('user', $member['poster_id']);?>
            <li class="sescontest_people_like_list">
              <div class="sescontest_people_like_list_img" style="height:<?php echo is_numeric($this->params['height'])? $this->params['height'].'px':$this->params['height'];?>;width:<?php echo is_numeric($this->params['width'])? $this->params['width'].'px':$this->params['width'];?>;">
                <a href="<?php echo $item->getHref();?>"><?php echo $this->itemBackgroundPhoto($item, 'thumb.profile');?></a>
              </div>
            </li>
            <?php $likeCounter++;?>
          <?php endforeach;?>
          <?php if(is_countable($this->likeMembers) && engine_count($this->likeMembers) > $this->params['view_more_like']):?>
            <li class="sescontest_more_link sescontest_people_like_list">
              <a href="javascript:void(0);" onclick="getLikeData('<?php echo $this->contest->contest_id; ?>')" style="height:<?php echo is_numeric($this->params['height'])? $this->params['height'].'px':$this->params['height'];?>;width:<?php echo is_numeric($this->params['width'])?$this->params['width'].'px':$this->params['width'];?>;line-height:<?php echo is_numeric($this->params['height'])?$this->params['height'].'px':$this->params['height'];?>;">+<?php echo (engine_count($this->likeMembers)+1) - $this->params['view_more_like'];?></a>
            </li>
          <?php endif;?>
        </ul>
      </div>
    <?php endif;?>
  <?php endif;?>
  
  <?php if(isset($this->favouriteActive)):?>
    <?php if(is_countable($this->favMembers) && engine_count($this->favMembers)):?>
      <div class="sescontest_people_like_field sesbasic_clearfix">
        <div class="sescontest_people_like_title sesbasic_clearfix">
        	<i class="fa fa-heart"></i>
          <span><?php echo $this->translate("People Who Favourited");?></span>
        </div>
        <ul class="sesbasic_clearfix">
          <?php foreach($this->favMembers as $member):?>
            <?php if($favouriteCounter == $this->params['view_more_favourite']):?>
              <?php break;?>
            <?php endif;?>
            <?php $item = Engine_Api::_()->getItem('user', $member['user_id']);?>
            <li class="sescontest_people_like_list">
              <div class="sescontest_people_like_list_img" style="height:<?php echo is_numeric($this->params['height'])?$this->params['height'].'px':$this->params['height'];?>;width:<?php echo is_numeric($this->params['width'])?$this->params['width'].'px':$this->params['width'];?>;">
                <a href="<?php echo $item->getHref();?>"><?php echo $this->itemBackgroundPhoto($item, 'thumb.profile');?></a>
              </div>
            </li>
            <?php $favouriteCounter++;?>
          <?php endforeach;?>
          <?php if(is_countable($this->favMembers) && engine_count($this->favMembers) > $this->params['view_more_favourite']):?>
             <li class="sescontest_more_link sescontest_people_like_list">
              <a href="javascript:void(0);" onclick="getFavouriteData('<?php echo $this->contest->contest_id; ?>')" style="height:<?php echo is_numeric($this->params['height'])?$this->params['height'].'px':$this->params['height'];?>;width:<?php echo is_numeric($this->params['width'])?$this->params['width'].'px':$this->params['width'];?>;line-height:<?php echo is_numeric($this->params['height'])?$this->params['height'].'px':$this->params['height'];?>;">+<?php echo (engine_count($this->favMembers)+1) - $this->params['view_more_favourite'];?></a>
           </li>
          <?php endif;?>
        </ul>
      </div>
    <?php endif;?>
  <?php endif;?>
  
  <?php if(isset($this->followActive)):?>
    <?php if(is_countable($this->followMembers) && engine_count($this->followMembers)):?>
      <div class="sescontest_people_like_field sesbasic_clearfix">
      	<div class="sescontest_people_like_title sesbasic_clearfix">
        	<i class="fa fa-users"></i>
          <span><?php echo $this->translate("People Who Followed");?></span>
        </div>
        <ul class="sesbasic_clearfix">
          <?php foreach($this->followMembers as $member):?>
            <?php if($followCounter == $this->params['view_more_follow']):?>
              <?php break;?>
            <?php endif;?>
            <?php $item = Engine_Api::_()->getItem('user', $member['user_id']);?>
            <li class="sescontest_people_like_list">
              <div class="sescontest_people_like_list_img" style="height:<?php echo is_numeric($this->params['height'])?$this->params['height'].'px':$this->params['height'];?>;width:<?php echo is_numeric($this->params['width'])?$this->params['width'].'px':$this->params['width'];?>;">
                <a href="<?php echo $item->getHref();?>"><?php echo $this->itemBackgroundPhoto($item, 'thumb.profile');?> </a>
              </div>
            </li>
            <?php $followCounter++;?>
          <?php endforeach;?>
          <?php if(engine_count($this->followMembers) > $this->params['view_more_follow']):?>
            <li class="sescontest_more_link sescontest_people_like_list">
              <a href="javascript:void(0);" onclick="getFollowerData('<?php echo $this->contest->contest_id; ?>')" style="height:<?php echo is_numeric($this->params['height'])?$this->params['height'].'px':$this->params['height'];?>;width:<?php echo is_numeric($this->params['width'])?$this->params['width'].'px':$this->params['width'];?>;line-height:<?php echo is_numeric($this->params['height'])?$this->params['height'].'px':$this->params['height'];?>;">+<?php echo (engine_count($this->followMembers)+1) - $this->params['view_more_follow'];?></a>
            </li>
          <?php endif;?>
        </ul>
      </div>
    <?php endif;?>
  <?php endif;?>
</div>

<script type='text/javascript'>
  function getLikeData(value){
    if(value){
      url = en4.core.baseUrl+'sescontest/ajax/like-contest/contest_id/'+value;
      openURLinSmoothBox(url);	
      return;
    }
  }  
  function getFavouriteData(value){
    if(value){
      url = en4.core.baseUrl+'sescontest/ajax/favourite-contest/contest_id/'+value;
      openURLinSmoothBox(url);	
      return;
    }
  } 
  function getFollowerData(value){
    if(value){
      url = en4.core.baseUrl+'sescontest/ajax/follow-contest/contest_id/'+value;
      openURLinSmoothBox(url);	
      return;
    }
  } 
</script>
