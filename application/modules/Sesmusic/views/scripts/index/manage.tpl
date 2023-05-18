<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manage.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $categoryTable = Engine_Api::_()->getDbTable('categories', 'sesmusic'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/styles/styles.css'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/scripts/favourites.js'); ?>
<?php if( 0 == engine_count($this->paginator) ): ?>
  <div class="tip">
    <span>
      <?php echo $this->translate('There is no album yet.') ?>
      <?php if( $this->canCreate ): ?>
        <?php echo $this->htmlLink(array('route' => 'sesmusic_general', 'action' => 'create'), $this->translate('Why don\'t you add some?')) ?>
      <?php endif; ?>
    </span>
  </div>
<?php else: ?>
  <ul class="sesmusic_list sesmusic_playlist_browse_listing">
    <?php foreach ($this->paginator as $album): ?>
      <li id="music_playlist_item_<?php echo $album->getIdentity() ?>" class="sesbasic_clearfix">
      <div class="sesmusic_playlist_listing_inner">
      <div class="sesmusic_playlist_listing_img_box">
      <div class="sesmusic_playlist_listing_artwork_bg_image">
         <?php if($album->photo_id): ?>
                   <?php echo $this->htmlLink($album->getHref(), $this->itemPhoto($album, 'thumb.normal'), array('class' => 'thumb')) ?>
                <?php else: ?>
                 <?php $path = $this->baseUrl() . '/application/modules/Sesmusic/externals/images/nophoto_albumsong_thumb_main.png';  ?>
                 <img src="<?php echo $path; ?>"/>
                <?php endif; ?>
        </div>
        <div class="sesmusic_playlist_listing_artwork">
          
          <?php if($album->photo_id): ?>
                  <?php echo $this->htmlLink($album->getHref(), $this->itemPhoto($album, 'thumb.normal'), array('class' => 'thumb')) ?>
                <?php else: ?>
                 <?php $path = $this->baseUrl() . '/application/modules/Sesmusic/externals/images/nophoto_albumsong_thumb_main.png';  ?>
                 <img src="<?php echo $path; ?>"/>
                <?php endif; ?>
          <div class="sesmusic_item_info_label">
            <?php if($album->hot): ?>
              <span class="sesmusic_label_hot fa fa-star" title="<?php echo $this->translate('HOT'); ?>"></span>
            <?php endif; ?>
            <?php if($album->featured): ?>
            <span class="sesmusic_label_featured fa fa-star" title="<?php echo $this->translate('FEATURED'); ?>"></span>
            <?php endif; ?>
            <?php if($album->sponsored): ?>
            <span class="sesmusic_label_sponsored fa fa-star" title="<?php echo $this->translate('SPONSORED'); ?>"></span>
            <?php endif; ?>
          </div>
        </div>
      </div>
        
        
        <div class="sesmusic_playlist_listing_info">
          <div class="sesmusic_playlist_info_title">
            <?php echo $this->htmlLink($album->getHref(), $album->getTitle()) ?>
          </div>
          <div class="sesmusic_playlist_listing_info_stats sesbasic_text_light">
            <?php echo $this->translate('Album By %s', $this->htmlLink($album->getOwner(), $album->getOwner()->getTitle())) ?>
            <?php echo $this->translate('Released %s ', $album->creation_date) ?>
          </div>
          <?php if($album->category_id): ?>
            <div class="sesmusic_playlist_listing_info_stats  sesbasic_text_light">
              <?php $catName = $categoryTable->getColumnName(array('column_name' => 'category_name', 'category_id' => $album->category_id)); ?>
              <?php echo $this->translate("Category:"); ?>
              <a href="<?php echo $this->url(array('action' => 'browse'), 'sesmusic_general', true).'?category_id='.urlencode($album->category_id) ; ?>"><?php echo $catName; ?></a>
              <?php if($album->subcat_id): ?>
              <?php $subcatName = $categoryTable->getColumnName(array('column_name' => 'category_name', 'category_id' => $album->subcat_id)); ?>
              &nbsp;&raquo;
              <a href="<?php echo $this->url(array('action' => 'browse'), 'sesmusic_general', true).'?subcat_id='.urlencode($album->subcat_id) ; ?>"><?php echo $subcatName; ?></a>
              <?php endif; ?>
              <?php if($album->subsubcat_id): ?>
              <?php $subsubcatName = $categoryTable->getColumnName(array('column_name' => 'category_name', 'category_id' => $album->subsubcat_id)); ?>
              &nbsp;&raquo;
              <a href="<?php echo $this->url(array('action' => 'browse'), 'sesmusic_general', true).'?subsubcat_id='.urlencode($album->subsubcat_id) ; ?>"><?php echo $subsubcatName; ?></a>
              <?php endif; ?>
              
            </div>
          <?php endif; ?>
          <div class="sesmusic_playlist_listing_info_stats  sesbasic_text_light">
            <?php if($this->albumlink && engine_in_array('showrating', $this->albumlink)): ?>
            <?php echo $this->translate(array('%s rating', '%s ratings', $album->rating), $this->locale()->toNumber($album->rating)) ?>
            &nbsp;|&nbsp;
            <?php endif; ?>
            <?php echo $this->translate(array('%s song', '%s songs', $album->song_count), $this->locale()->toNumber($album->song_count)) ?>
            &nbsp;|&nbsp;
            <?php echo $this->translate(array('%s favorite', '%s favorites', $album->favourite_count), $this->locale()->toNumber($album->favourite_count)) ?>
            &nbsp;|&nbsp;
            <?php echo $this->translate(array('%s comment', '%s comments', $album->comment_count), $this->locale()->toNumber($album->comment_count)) ?>
            &nbsp;|&nbsp;
            <?php echo $this->translate(array('%s view', '%s views', $album->view_count), $this->locale()->toNumber($album->view_count)) ?>
            &nbsp;|&nbsp;
            <?php echo $this->translate(array('%s like', '%s likes', $album->like_count), $this->locale()->toNumber($album->like_count)) ?>
          </div>
          
          <?php if($this->showRating): ?>
            <div class="sesmusic_list_info_stats">
              <?php if( $album->rating > 0 ): ?>
                <?php for( $x=1; $x<= $album->rating; $x++ ): ?>
                  <span class="sesbasic_rating_star_small fa fa-star"></span>
                <?php endfor; ?>
                <?php if( (round($album->rating) - $album->rating) > 0): ?>
                  <span class="sesbasic_rating_star_small fa fa-star-half"></span>
                <?php endif; ?>
              <?php endif; ?>      
            </div>
          <?php endif; ?>
          
          <div class="sesmusic_playlist_listinfo_desc">
            <?php echo $this->viewMore($album->description); ?>
          </div>
          
          <div class="sesmusic_playlist_listing_options_buttons">
            <?php if ($album->isDeletable() || $album->isEditable()): ?>
              <?php if ($album->isEditable()): ?>
                  <?php echo $this->htmlLink($album->getHref(array('route' => 'sesmusic_album_specific', 'action' => 'edit')), $this->translate('Edit Music Album'), array('class'=>'sesbasic_icon_edit')); ?>
              <?php endif; ?>
              <?php if( $album->isDeletable() ): ?>
                <?php echo $this->htmlLink(array('route' => 'sesmusic_general', 'module' => 'sesmusic', 'controller' => 'index', 'action' => 'delete', 'album_id' => $album->getIdentity(), 'format' => 'smoothbox'), $this->translate('Delete Music Album'), array('class' => 'smoothbox sesbasic_icon_delete'));
                ?>
              <?php endif; ?>
              
              <?php if($this->viewer_id): ?>
                <?php if($this->canAddPlaylist): ?>
                  <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesmusic', 'controller' => 'song', 'action' => 'append-songs', 'album_id' => $album->album_id), $this->translate('Add to Playlist'), array('class' => 'smoothbox sesbasic_icon_add')); ?>
                  <?php endif; ?>

                  <?php if($this->canAddFavourite): ?>
                  <?php $isFavourite = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->isFavourite(array('resource_type' => "sesmusic_album", 'resource_id' => $album->album_id)); ?>
                  <a title='<?php echo $this->translate("Remove from Favorite") ?>' class="sesbasic_icon_favourite sesmusic_favourite" id="sesmusic_album_unfavourite_<?php echo $album->album_id; ?>" style ='display:<?php echo $isFavourite ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $album->album_id; ?>', 'sesmusic_album');"><?php echo $this->translate("Remove from Favorite") ?></a>
                  <a title='<?php echo $this->translate("Add to Favorite") ?>' class="sesbasic_icon_favourite" id="sesmusic_album_favourite_<?php echo $album->album_id; ?>" style ='display:<?php echo $isFavourite ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $album->album_id; ?>', 'sesmusic_album');"><?php echo $this->translate("Add to Favorite") ?></a>
                  <input type ="hidden" id = "sesmusic_album_favouritehidden_<?php echo $album->album_id; ?>" value = '<?php echo $isFavourite ? $isFavourite : 0; ?>' /> 
                  <?php endif; ?>
               <?php endif; ?>
								<?php $viewer = Engine_Api::_()->user()->getViewer();
								$addstore_link = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'addstore_link'); ?>
								<?php if($addstore_link && $album->store_link): ?>
									<?php $storeLink = !empty($album->store_link) ? (preg_match("#https?://#", $album->store_link) === 0) ? 'http://'.$album->store_link : $album->store_link : ''; ?>
									<a href="<?php echo $storeLink ?>" target="_blank" class="fa fa-shopping-cart"><?php echo $this->translate("Purchase") ?></a>
								<?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
  <?php echo $this->paginationControl($this->paginator, null, null, array('pageAsQuery' => true, 'query' => $this->formValues)); ?>
<?php endif; ?>
<script type="text/javascript">
  scriptJquery('.core_main_sesmusic').parent().addClass('active');
</script>
