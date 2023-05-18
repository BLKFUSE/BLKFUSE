<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>

<?php if(!$this->is_ajax){ 
if(isset($this->docActive)){
	$imageURL = $this->playlist->getPhotoUrl();
	if(strpos($this->playlist->getPhotoUrl(),'http') === false)
          	$imageURL = (!empty($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"] == 'on')) ? "https://" : "http://". $_SERVER['HTTP_HOST'].$this->playlist->getPhotoUrl();
  $this->doctype('XHTML1_RDFA');
  $this->headMeta()->setProperty('og:title', strip_tags($this->playlist->getTitle()));
  $this->headMeta()->setProperty('og:description', strip_tags($this->playlist->getDescription()));
  $this->headMeta()->setProperty('og:image',$imageURL);
  $this->headMeta()->setProperty('twitter:title', strip_tags($this->playlist->getTitle()));
  $this->headMeta()->setProperty('twitter:description', strip_tags($this->playlist->getDescription()));
}
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css'); ?>
<div class="sesvideo_item_view_wrapper clear">
<?php $playlist = $this->playlist; ?>
<div class="sesvideo_item_view_top sesbasic_clearfix sesbasic_bxs sesbm">
    <div class="sesvideo_item_view_artwork">
    	<?php echo $this->itemPhoto($playlist, 'thumb.profile'); ?>
    </div>
    <div class="sesvideo_item_view_info">
      <div class="sesvideo_item_view_title">
        <?php echo $playlist->getTitle() ?>
      </div>
      <?php if(!empty($this->informationPlaylist) && engine_in_array('postedby',  $this->informationPlaylist)): ?>
      	<p class="sesvideo_item_view_stats sesbasic_text_light">
          <?php echo $this->translate('Created %s by ', $this->timestamp($this->playlist->creation_date)) ?>
          <?php echo $this->htmlLink($playlist->getOwner(), $playlist->getOwner()->getTitle()) ?>
      	</p>
       <?php endif; ?>   
        <div class="sesvideo_item_view_stats sesvideo_list_stats sesbasic_text_light sesbasic_clearfix"> 
        	<?php if(!empty($this->informationPlaylist) && engine_in_array('viewCountPlaylist',  $this->informationPlaylist)): ?>
          	<span title="<?php echo $this->translate(array('%s view', '%s views', $this->playlist->view_count), $this->locale()->toNumber($this->playlist->view_count)) ?>"><i class="sesbasic_icon_view"></i><?php echo $this->locale()->toNumber($this->playlist->view_count); ?></span>
        	<?php endif; ?>
          <?php if(!empty($this->informationPlaylist) && engine_in_array('favouriteCountPlaylist', $this->informationPlaylist)): ?>
          	<span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $this->playlist->favourite_count), $this->locale()->toNumber($this->playlist->favourite_count)) ?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $this->locale()->toNumber($this->playlist->favourite_count);?></span>
      		<?php endif; ?>
      		<?php if(!empty($this->informationPlaylist) && engine_in_array('likeCountPlaylist', $this->informationPlaylist)): ?>    
	          <span title="<?php echo $this->translate(array('%s like', '%s likes', $this->playlist->like_count), $this->locale()->toNumber($this->playlist->like_count)) ?>"><i class="sesbasic_icon_like_o"></i><?php echo $this->locale()->toNumber($this->playlist->like_count); ?></span>  
      		<?php endif; ?>
        </div>
      <?php if(!empty($this->informationPlaylist) && engine_in_array('descriptionPlaylist',  $this->informationPlaylist) && $playlist->description): ?>
        <div class="sesvideo_item_view_des">
          <?php echo (nl2br($playlist->description)); ?>
        </div>
      <?php endif; ?>
       <?php
          		$urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $this->playlist->getHref()); ?>
               <div class="sesvideo_list_btns sesvideo_item_view_options"> 
                   <?php if(!empty($this->informationPlaylist) && engine_in_array('socialSharingPlaylist', $this->informationPlaylist)){ ?>
                   
                    <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->playlist, 'socialshare_icon_limit' => $this->socialshare_icon_limit, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon)); ?>

                    <?php } 
                    if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 ){
                          $this->playlisttype = 'sesvideo_playlist';
                          $getId = 'playlist_id';                                
                          $canComment =  true;
                          if(!empty($this->informationPlaylist) && engine_in_array('likeButtonPlaylist', $this->informationPlaylist) && $canComment){
                        ?>
                      <!--Like Button-->
                      <?php $LikeStatus = Engine_Api::_()->sesvideo()->getLikeStatusVideo($this->playlist->$getId,$this->playlist->getType()); ?>
                        <a href="javascript:;" data-url="<?php echo $this->playlist->$getId ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesvideo_like_<?php echo $this->playlisttype; ?> <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $this->playlist->like_count; ?></span></a>
                        <?php } ?>
                         <?php if(!empty($this->informationPlaylist) && engine_in_array('favouriteButtonPlaylist', $this->informationPlaylist) && isset($this->playlist->favourite_count)){ ?>
                        
                        <?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesvideo')->isFavourite(array('resource_type'=>$this->playlisttype,'resource_id'=>$this->playlist->$getId)); ?>
                        <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesvideo_favourite_<?php echo $this->playlisttype; ?> <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $this->playlist->$getId ; ?>"><i class="fa fa-heart"></i><span><?php echo $this->playlist->favourite_count; ?></span></a>
                      <?php } ?>
                    <?php  } ?>
                    
                <?php $viewer = Engine_Api::_()->user()->getViewer(); ?>
                	<?php if($this->viewer_id): ?>
                    <?php if(!empty($this->informationPlaylist) && engine_in_array('sharePlaylist', $this->informationPlaylist)): ?>
                      <a href="<?php echo $this->url(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesvideo_playlist', 'id' => $this->playlist->getIdentity(), 'format' => 'smoothbox'),'default',true) ?>" class="smoothbox sesbasic_icon_btn" title="<?php echo $this->translate("Share") ?>">
                      <i class="fa fa-share"></i>
                      </a>
                    <?php endif; ?>
                    
                  <?php if(!empty($this->informationPlaylist) && engine_in_array('reportPlaylist',  $this->informationPlaylist) && ($viewer->getIdentity() != $playlist->owner_id)): ?>
                    <a href="<?php echo $this->url(array('module'=>'core', 'controller'=>'report', 'action'=>'create', 'route'=>'default', 'subject'=> $this->playlist->getGuid(), 'format' => 'smoothbox'),'default',true) ?>" class="smoothbox sesbasic_icon_btn" title="<?php echo $this->translate("Report") ?>">
                    <i class="fa fa-flag"></i>
                    </a>
          				<?php endif; ?>
          
          					<?php if($viewer->getIdentity() == $playlist->owner_id || $viewer->level_id == 1 ): ?>
                    <a href="<?php echo $this->url(array('action'=>'edit', 'playlist_id'=>$this->playlist->getIdentity(),'slug'=>$this->playlist->getSlug(), 'format' => 'smoothbox'),'sesvideo_playlist_view',true) ?>" class="smoothbox sesbasic_icon_btn" title="<?php echo $this->translate("Edit Playlist") ?>">
                    <i class="fas fa-edit"></i>
                    </a>
                       <a href="<?php echo $this->url(array('action'=>'delete', 'playlist_id'=>$this->playlist->getIdentity(),'slug'=>$this->playlist->getSlug(),  'format' => 'smoothbox'),'sesvideo_playlist_view',true) ?>" class="sesbasic_icon_btn smoothbox" title="<?php echo $this->translate("Delete Playlist") ?>">
                    <i class="fa fa-trash"></i>
                    </a>
          <?php endif; ?>
        <?php endif; ?>
        </div>
    </div>
  </div>
<?php } ?>
<?php include APPLICATION_PATH . '/application/modules/Sesvideo/views/scripts/_showVideoListGrid.tpl'; ?>
</div>
