<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/scripts/favourites.js'); ?>
<script type="text/javascript">
  function showPopUp(url) {
    Smoothbox.open(url);
    parent.Smoothbox.close;
  }
</script>
<?php if(engine_count($this->results) > 0) :?>
<?php if($this->showType == 'other'): ?>
  <h3><?php echo $this->translate("%s Music Albums", $this->viewer->getTitle()); ?></h3>
<?php endif; ?>
<ul class="sesmusic_side_block sesmusic_browse_listing">

  <?php foreach( $this->results as $item ): ?>
  <?php if($item && $this->viewType == 'listview'): ?>
  <li class="sesmusic_sidebar_list<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?> paid_content <?php } ?>">
  <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?>
       <?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $item)); ?>
    <?php } ?>
    <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.icon'), array('class' => 'sesmusic_sidebar_list_thumb')) ?>
    <div class="sesmusic_sidebar_list_info">
      <div class="sesmusic_sidebar_list_title">
        <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
      </div>
      <?php if(!empty($this->information) && engine_in_array('postedby', $this->information)): ?>
        <div class="sesmusic_sidebar_list_stats sesbasic_text_light">
          <?php echo $this->translate('by');?> <?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?>
        </div>
      <?php endif; ?>    
      <?php if($this->showRating && !empty($this->information) && engine_in_array('ratingCount', $this->information)): ?>
        <div class="sesmusic_sidebar_list_stats sesbasic_text_light" title="<?php echo $this->translate(array('%s rating', '%s ratings', $item->rating), $this->locale()->toNumber($item->rating)); ?>">
          <?php if( $item->rating > 0 ): ?>
          <?php for( $x=1; $x<= $item->rating; $x++ ): ?>
          <span class="sesbasic_rating_star_small fa fa-star"></span>
          <?php endfor; ?>
          <?php if( (round($item->rating) - $item->rating) > 0): ?>
          <span class="sesbasic_rating_star_small fa fa-star-half"></span>
          <?php endif; ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <div class="sesmusic_sidebar_list_stats sesmusic_list_stats sesbasic_text_light">
        <?php if (!empty($this->information) && engine_in_array('commentCount', $this->information)) :?>
          <span title="<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)); ?>">
            <i class="fa fa-comment"></i>
            <?php echo $item->comment_count; ?>
          </span>
        <?php endif; ?>
        <?php if (!empty($this->information) && engine_in_array('likeCount', $this->information)) : ?>
          <span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count));?>">
            <i class="fa fa-thumbs-up"></i>
            <?php echo $item->like_count; ?>
          </span>
        <?php endif; ?>
        <?php if (!empty($this->information) && engine_in_array('viewCount', $this->information)) : ?>
          <span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)); ?>">
            <i class="fa fa-eye"></i>
            <?php echo $item->view_count; ?>
          </span>
        <?php endif; ?>
        <?php if (!empty($this->information) && engine_in_array('songsCount', $this->information)) : ?>
          <span title="<?php echo $this->translate(array('%s song', '%s songs', $item->song_count), $this->locale()->toNumber($item->song_count)); ?>">
            <i class="fa fa-music"></i>
            <?php echo $item->song_count; ?>
          </span>
        <?php endif; ?>
      </div>
    </div>
  </li>
  <?php elseif($item && $this->viewType == 'gridview'): ?>
  <li class="sesmusic_item_grid sesbasic_bxs <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?> paid_content <?php } ?>" style="width:<?php echo $this->width ?>px;">
  <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?>
       <?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $item)); ?>
    <?php } ?>
    <div class="sesmusic_item_artwork">
    <div class="sesmusic_item_artwork_img" style="height:<?php echo $this->height ?>px;">
    	 <?php echo $this->itemPhoto($item, 'thumb.profile'); ?>
      <a href="<?php echo $item->getHref(); ?>" class="transparentbg"></a>
    </div>
    <div class="sesmusic_item_artwork_over_content sesmusic_animation">
    	<div class="sesmusic_item_sponseard_social">
      	 <?php // Featured and Sponsored and Hot Label Icon ?>
        <div class="sesmusic_item_info_label">
          <?php if(!empty($item->hot) && !empty($this->information) && engine_in_array('hot', $this->information)): ?>
          <span class="sesmusic_label_hot fa fa-star" title='<?php echo $this->translate("HOT"); ?>'></span>
          <?php endif; ?>
          <?php if(!empty($item->featured) && !empty($this->information) && engine_in_array('featured', $this->information)): ?>
          <span class="sesmusic_label_featured fa fa-star" title='<?php echo $this->translate("FEATURED"); ?>'></span>
          <?php endif; ?>
          <?php if(!empty($item->sponsored) && !empty($this->information) && engine_in_array('sponsored', $this->information)): ?>
          <span class="sesmusic_label_sponsored fa fa-star" title='<?php echo $this->translate("SPONSORED"); ?>'></span>
          <?php endif; ?>
      </div>
      <div class="sesmusic_social_item sesmusic_animation">
        <!--Social Share Button-->
        <?php if($this->information && engine_in_array('socialSharing', $this->information)) { ?>
          <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
          
          <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>

        <?php } ?>
        <!--Social Share Button-->
        
        <!--Like and Favourite Button-->
        <?php $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $canLike = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'comment');
        $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($item, $viewer); ?>
        <?php if ($canLike && !empty($viewer_id) && $this->information && engine_in_array('addLikeButton', $this->information)): ?>
          <a href="javascript:;" data-url="<?php echo $item->album_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesmusic_like_<?php echo $item->getType(); ?> <?php echo ($isLike) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count; ?></span></a>
        <?php endif; ?>
        
        <?php $isFavourite = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->isFavourite(array('resource_type' => "sesmusic_album", 'resource_id' => $item->album_id)); ?>
        <?php if(!empty($viewer_id) && $this->canAddFavourite && $this->information && engine_in_array('favourite', $this->information)): ?>
          <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesmusic_favourite_<?php echo $item->getType(); ?> <?php echo ($isFavourite)  ? 'button_active' : '' ?>"  data-url="<?php echo $item->album_id ; ?>"><i class="fa fa-heart"></i><span><?php echo $item->favourite_count; ?></span></a>
        <?php endif; ?>
        <!--Like and Favourite Button-->  
            
          <?php if($this->viewer_id): ?>
            <?php if($this->canAddPlaylist): ?>
              <a class="sesbasic_icon_btn add-white" title='<?php echo $this->translate("Add to Playlist") ?>' href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module' =>'sesmusic', 'controller' => 'song', 'action'=>'append - songs','album_id' => $item->album_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><i class="fa fa-plus"></i></a>
            <?php endif; ?>
            <?php if($this->albumlink && engine_in_array('share', $this->albumlink)): ?>
              <a class="sesbasic_icon_btn share-white" title='<?php echo $this->translate("Share") ?>' href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesmusic_album', 'id' => $item->album_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><i class="fa fa-share"></i></a>
            <?php endif; ?>
          <?php endif; ?>
      </div>
    </div>
    <div class="sesmusic_item_stats_info sesmusic_animation">
      <div class="sesmusic_item_info_stats">
        <?php if (!empty($this->information) && engine_in_array('commentCount', $this->information)) :?>
          <span>
            <?php echo $item->comment_count; ?>
            <i class="fa fa-comment"></i>
          </span>
        <?php endif; ?>
        <?php if (!empty($this->information) && engine_in_array('likeCount', $this->information)) : ?>
        <span>
        <?php echo $item->like_count; ?>
        <i class="fa fa-thumbs-up"></i>
        </span>
        <?php endif; ?>
        <?php if (!empty($this->information) && engine_in_array('viewCount', $this->information)) : ?>
        <span>
        <?php echo $item->view_count; ?>
        <i class="fa fa-eye"></i>
        </span>
        <?php endif; ?>
        <?php if (!empty($this->information) && engine_in_array('songsCount', $this->information)) : ?>
        <span>
        <?php echo $item->song_count; ?>
        <i class="fa fa-music"></i>
        </span>
        <?php endif; ?>
      </div>
      <?php if ($this->showRating && !empty($this->information) && engine_in_array('ratingCount', $this->information)) : ?>
      <div class="sesmusic_item_info_rating">
        <?php if( $item->rating > 0 ): ?>
        <?php for( $x=1; $x<= $item->rating; $x++ ): ?>
        <span class="sesbasic_rating_star_small fa fa-star"></span>
        <?php endfor; ?>
        <?php if( (round($item->rating) - $item->rating) > 0): ?>
        <span class="sesbasic_rating_star_small fa fa-star-half"></span>
        <?php endif; ?>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
    <div class="sesmusic_item_stats_play_btn sesmusic_animation">
      <a class="sesmusic_play_button" href="javascript:void(0);"><i class="fa fa-play-circle"></i></a>
    </div>
    </div>
      </div>
      <div class="sesmusic_item_info">     

        <?php if(!empty($this->information) && engine_in_array('title', $this->information)): ?>
          <div class="sesmusic_item_info_title">
            <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
          </div>    
        <?php endif; ?>

        <?php if(!empty($this->information) && engine_in_array('postedby', $this->information)): ?>
          <div class="sesmusic_item_info_owner">
            <?php echo $this->translate('by');?> <?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?>
          </div>
        <?php endif; ?>
        </div>
  </li>
  <?php endif; ?>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
