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


<?php $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();  ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/scripts/favourites.js'); ?> 

<?php 
  if(isset($this->identityForWidget) && !empty($this->identityForWidget)):
    $randonNumber = $this->identityForWidget;
  else:
    $randonNumber = $this->identity; 
  endif;
?>
<?php if(!$this->is_ajax): ?>
		<div class="sesvideo_channel_profile_widgets_top sesbasic_clearfix">
    	<?php $url = 'music/album/create/resource_type/sesvideo_chanel/resource_id/' . $this->chanel_id; ?>
    	<a href='<?php echo $url ?>' title='Create New Music Album' class="sesbasic_button fa fa-plus"><?php echo $this->translate("Add Music"); ?></a>
    </div>
  <div class="clear sesbasic_clearfix" id="scrollHeightDivSes_<?php echo $randonNumber; ?>">
          <ul class="clear sesbasic_clearfix" id="tabbed-widget_<?php echo $randonNumber; ?>">
          <?php endif; ?>
          <?php $limit = $this->limit; ?>
          <?php if($this->paginator->getTotalItemCount() > 0): ?>
    			<?php foreach( $this->paginator as $photo ): ?>
          <?php if($this->albumPhotoOption == 'album') { ?>
            <li id="thumbs-photo-<?php echo $photo->album_id ?>" class="sesmusic_item_grid" style="width:<?php echo is_numeric($this->width) ? $this->width.'px' : $this->width ?>;">
              <div class="sesmusic_item_artwork" style="height:<?php echo is_numeric($this->height) ? $this->height.'px' : $this->height ?>;">
                <?php echo $this->htmlLink($photo, $this->itemPhoto($photo, 'thumb.main') ) ?>
                <a href="<?php echo $photo->getHref(); ?>" class="transparentbg"></a>
                <div class="sesmusic_item_info">
                  <div class="sesmusic_item_info_title">
                    <?php echo $this->htmlLink($photo->getHref(), $photo->getTitle()) ?>
                  </div>
                  <?php if(!empty($this->informationAlbum) && engine_in_array('postedBy', $this->informationAlbum)): ?>
                    <div class="sesmusic_item_info_owner">
                      <?php echo $this->translate('by %s', $this->htmlLink($photo->getOwner(), $photo->getOwner()->getTitle())) ?>
                    </div>
                  <?php endif; ?>
                  <div class="sesmusic_item_info_stats">
                    <?php if(!empty($this->informationAlbum) && engine_in_array('commentCount', $this->informationAlbum)): ?>
                      <span>
                        <?php echo $this->translate(array($photo->comment_count), $this->locale()->toNumber($photo->comment_count)) ?>
                        <i class="sesbasic_icon_comment_o"></i>
                      </span>
                    <?php endif; ?>
                    <?php if(!empty($this->informationAlbum) && engine_in_array('likeCount', $this->informationAlbum)): ?>
                      <span>
                        <?php echo $this->translate(array($photo->like_count), $this->locale()->toNumber($photo->like_count)) ?>
                        <i class="sesbasic_icon_like_o"></i>
                      </span>
                    <?php endif; ?>
                    <?php if(!empty($this->informationAlbum) && engine_in_array('viewCount', $this->informationAlbum)): ?>
                      <span>
                        <?php echo $this->translate(array($photo->view_count), $this->locale()->toNumber($photo->view_count)) ?>
                        <i class="sesbasic_icon_view"></i>
                      </span>
                    <?php endif; ?>
                    <?php if(!empty($this->informationAlbum) && engine_in_array('songCount', $this->informationAlbum)): ?>
                      <span>
                        <?php echo $this->translate(array($photo->song_count), $this->locale()->toNumber($photo->song_count)) ?>
                        <i class="fa fa-music"></i>
                      </span>
                    <?php endif; ?>
                  </div>
                  <?php if ($this->showRating && !empty($this->informationAlbum) && engine_in_array('ratingStars', $this->informationAlbum) && $photo->rating >0) : ?>
                   <span  title="<?php echo $this->translate(array('%s rating', '%s ratings', round($photo->rating,1)), $this->locale()->toNumber(round($photo->rating,1)))?>">
               <i class="far fa-star"></i><?php echo round($photo->rating,1).'/5';?>
              </span>
                  <?php endif; ?>
                  <div class="sesmusic_item_info_label">
                    <?php if($photo->hot && !empty($this->informationAlbum) && engine_in_array('hot', $this->informationAlbum)): ?>
                      <span class="sesmusic_label_hot"><?php echo $this->translate("HOT"); ?></span>
                    <?php endif; ?>
                    <?php if($photo->featured && !empty($this->informationAlbum) && engine_in_array('featured', $this->informationAlbum)): ?>
                    <span class="sesmusic_label_featured"><?php echo $this->translate('FEATURED'); ?></span>
                    <?php endif; ?>
                    <?php if($photo->sponsored && !empty($this->informationAlbum) && engine_in_array('sponsored', $this->informationAlbum)): ?>
                    <span class="sesmusic_label_sponsored"><?php echo $this->translate("SPONSORED"); ?></span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="hover_box">
                  <a title="<?php echo $photo->getTitle(); ?>" class="sesmusic_grid_link" href="<?php echo $photo->getHref(); ?>"></a>
                  <div class="hover_box_options">
                  <?php 
                  if($viewer_id): ?>
                      <?php $isFavourite = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->isFavourite(array('resource_type' => "sesmusic_album", 'resource_id' => $photo->album_id)); ?>

                      <?php if($this->addfavouriteAlbumSong && !empty($this->informationAlbum) && engine_in_array('favourite', $this->informationAlbum)): ?>
                      <a title='<?php echo $this->translate("Remove from Favorite") ?>' class="favorite-white favorite" id="sesmusic_album_unfavourite_<?php echo $photo->album_id; ?>" style ='display:<?php echo $isFavourite ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $photo->album_id; ?>', 'sesmusic_album');"><i class="fa fa-heart sesmusic_favourite"></i></a>
                      <a title='<?php echo $this->translate("Add to Favorite") ?>' class="favorite-white favorite" id="sesmusic_album_favourite_<?php echo $photo->album_id; ?>" style ='display:<?php echo $isFavourite ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $photo->album_id; ?>', 'sesmusic_album');"><i class="fa fa-heart"></i></a>
                      <input type="hidden" id="sesmusic_album_favouritehidden_<?php echo $photo->album_id; ?>" value='<?php echo $isFavourite ? $isFavourite : 0; ?>' />
                      <?php endif; ?>                
                      <?php if($this->canAddPlaylistAlbumSong && !empty($this->informationAlbum) && engine_in_array('addplaylist', $this->informationAlbum)): ?>
                      <a class="add-white" title='<?php echo $this->translate("Add to Playlist") ?>' href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module' =>'sesmusic', 'controller' => 'song', 'action'=>'append - songs','album_id' => $photo->album_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><i class="fa fa-plus"></i></a>
                      <?php endif; ?>
                    <?php if(engine_in_array('share', $this->albumlink) && !empty($this->informationAlbum) && engine_in_array('share', $this->informationAlbum)): ?>
                    <a class="share-white" title='<?php echo $this->translate("Share") ?>' href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesmusic_album', 'id' => $photo->album_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><i class="fa fa-share"></i></a>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php  ?>
                  </div>
                </div>
              </div>            
            </li>
          <?php  } ?>
          <?php $limit++; ?>
      <?php endforeach;?>
      <?php endif; ?>
      <?php if($this->paginator->getTotalItemCount() == 0): ?>
        <div class="tip">
          <span>
              <?php echo $this->translate("There are currently no albums");?>
          </span>
        </div>    
      <?php endif; ?>
    <?php if(!$this->is_ajax) { ?>
  </ul>
  <div class="sesbasic_view_more sesbasic_load_btn" style="display:none;" id="view_more_<?php echo $randonNumber; ?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array('id' => "feed_viewmore_link_$randonNumber", 'class' => 'sesbasic_animation sesbasic_link_btn fa fa-sync')); ?> </div>
  <div class="sesbasic_view_more_loading" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;">  <span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span> <?php echo $this->translate("Loading ...") ?> </div>
</div>
<script type="text/javascript">
var valueTabData ;

  //Globally define available tab array

	var requestTab_<?php echo $randonNumber; ?>;

<?php if($this->loadOptionData == 'auto_load'){ ?>
		scriptJquery(document).ready(function() {
		 sesBasicAutoScroll(window).scroll( function() {
			  var heightOfContentDiv_<?php echo $randonNumber; ?> = sesBasicAutoScroll('#scrollHeightDivSes_<?php echo $randonNumber; ?>').offset().top;
        var fromtop_<?php echo $randonNumber; ?> = sesBasicAutoScroll(this).scrollTop();
        if(fromtop_<?php echo $randonNumber; ?> > heightOfContentDiv_<?php echo $randonNumber; ?> - 100 && sesBasicAutoScroll('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block' ){
						document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
				}
     });
	});
<?php } ?>
</script>
<?php } ?>

<script type="text/javascript">
	viewMoreHide_<?php echo $randonNumber; ?>();
  function viewMoreHide_<?php echo $randonNumber; ?>() {
    if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
  }
  function viewMore_<?php echo $randonNumber; ?> (){
    var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
    document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = 'none';
    document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = '';    
    en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + 'widget/index/mod/sesvideo/name/profile-musicalbums/openTab/' + openTab_<?php echo $randonNumber; ?>,
      'data': {
        format: 'html',
        page: <?php echo $this->page + 1; ?>,    
				params :'<?php echo json_encode($this->params); ?>', 
				is_ajax : 1,
				identity : '<?php echo $randonNumber; ?>',
				//identityObject : '<?php echo $this->identityObject; ?>',
      },
      success: function(responseHTML) {
        scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
				document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
      }
    }));
    return false;
  }
</script>
