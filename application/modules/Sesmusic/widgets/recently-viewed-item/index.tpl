<?php

?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/scripts/favourites.js'); ?>
<script type="text/javascript">
  function showPopUp(url) {
    Smoothbox.open(url);
    parent.Smoothbox.close;
  }
</script>

<?php if($this->content_type == 'sesmusic_album'): ?>
<?php if(engine_count($this->results) > 0) :?>
<ul class="sesmusic_side_block sesmusic_browse_listing">
  <?php foreach( $this->results as $album ): ?>
		<?php $item = Engine_Api::_()->getItem($album['resource_type'], $album['resource_id']);  ?>
		<?php if($item && $this->viewType == 'listView'): ?>
			<li class="sesmusic_sidebar_list<?php if(Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?> paid_content <?php } ?>">
			<?php if(Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?>
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
						<div class="sesmusic_sidebar_list_stats sesbasic_text_light">
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
							<span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>">
								<i class="fa fa-thumbs-up"></i>
								<?php echo $item->like_count; ?>
							</span>
						<?php endif; ?>
						<?php if (!empty($this->information) && engine_in_array('viewCount', $this->information)) : ?>
							<span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>">
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
			<li class="sesmusic_item_grid<?php if(Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?> paid_content <?php } ?>" style="width:<?php echo $this->width ?>px;">
			<?php if(Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?>
					<?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $item)); ?>
				<?php } ?>
				<div class="sesmusic_item_artwork" style="height:<?php echo $this->height ?>px;">
					<div class="sesmusic_item_artwork_img" style="height:<?php echo $this->height ?>px;">
						<?php echo $this->itemPhoto($item, 'thumb.profile'); ?>
						<a href="<?php echo $item->getHref(); ?>" class="transparentbg"></a>
					 </div>
				 <div class="sesmusic_item_artwork_over_content sesmusic_animation">
						<div class="sesmusic_item_sponseard_social">
							<?php // Featured and Sponsored and Hot Label Icon ?>
									<div class="sesmusic_item_info_label">
										<?php if(!empty($item->hot) && !empty($this->information) && engine_in_array('hot', $this->information)): ?>
										<span class="sesmusic_label_hot"><?php echo $this->translate("HOT"); ?></span>
										<?php endif; ?>
										<?php if(!empty($item->featured) && !empty($this->information) && engine_in_array('featured', $this->information)): ?>
										<span class="sesmusic_label_featured"><?php echo $this->translate("FEATURED"); ?></span>
										<?php endif; ?>
										<?php if(!empty($item->sponsored) && !empty($this->information) && engine_in_array('sponsored', $this->information)): ?>
										<span class="sesmusic_label_sponsored"><?php echo $this->translate("SPONSORED"); ?></span>
										<?php endif; ?>
								</div>
						</div>		
						 <div class="sesmusic_item_stats_info sesmusic_animation">
								<div class="sesmusic_item_info_stats">
											<?php if (!empty($this->information) && engine_in_array('commentCount', $this->information)) :?>
												<span>
													<?php echo $item->comment_count; ?>
													<i class="sesbasic_icon_comment_o"></i>
												</span>
											<?php endif; ?>
											<?php if (!empty($this->information) && engine_in_array('likeCount', $this->information)) : ?>
												<span>
													<?php echo $item->like_count; ?>
													<i class="sesbasic_icon_like_o"></i>
												</span>
											<?php endif; ?>
											<?php if (!empty($this->information) && engine_in_array('viewCount', $this->information)) : ?>
												<span>
													<?php echo $item->view_count; ?>
													<i class="sesbasic_icon_view_o"></i>
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
								<div class="sesmusic_social_item sesmusic_animation">
									<?php if($this->viewer_id): ?>
											<?php if($this->canAddFavourite && !empty($this->information) && engine_in_array('favourite', $this->information)): ?>
												<?php $isFavourite = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->isFavourite(array('resource_type' => "sesmusic_album", 'resource_id' => $item->album_id)); ?>
												<a title='<?php echo $this->translate("Remove from Favorite") ?>' class="favorite-white favorite" id="sesmusic_album_unfavourite_<?php echo $item->album_id; ?>" style ='display:<?php echo $isFavourite ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $item->album_id; ?>', 'sesmusic_album');"><i class="fa fa-heart sesmusic_favourite"></i></a>
												<a title='<?php echo $this->translate("Add to Favorite") ?>' class="favorite-white favorite" id="sesmusic_album_favourite_<?php echo $item->album_id; ?>" style ='display:<?php echo $isFavourite ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $item->album_id; ?>', 'sesmusic_album');"><i class="fa fa-heart"></i></a>
												<input type="hidden" id="sesmusic_album_favouritehidden_<?php echo $item->album_id; ?>" value='<?php echo $isFavourite ? $isFavourite : 0; ?>' />
											<?php endif; ?>
											<?php if($this->canAddPlaylist && !empty($this->information) && engine_in_array('addplaylist', $this->information)): ?>
												<a class="sesbasic_icon_btn add-white" title='<?php echo $this->translate("Add to Playlist") ?>' href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module' =>'sesmusic', 'controller' => 'song', 'action'=>'append - songs','album_id' => $item->album_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><i class="fa fa-plus"></i></a>
											<?php endif; ?>
											<?php if($this->albumlink && engine_in_array('share', $this->albumlink) && !empty($this->information) && engine_in_array('share', $this->information)): ?>
												<a class="sesbasic_icon_btn share-white" title='<?php echo $this->translate("Share") ?>' href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesmusic_album', 'id' => $item->album_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><i class="fas fa-share-alt"></i></a>
											<?php endif; ?>
										<?php endif; ?>
								</div>
               
					</div>
			 </div>
				<div class="sesmusic_item_info">
					<div class="sesmusic_item_info_title">
						<?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
					</div>
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
<?php elseif($this->content_type == 'sesmusic_albumsong'): ?>
	<?php if(engine_count($this->results) > 0) :?>
		<ul class="sesmusic_side_block sesmusic_browse_listing clear sesbasic_bxs">
			<?php foreach( $this->results as $album ): ?>
			<?php $item = Engine_Api::_()->getItem($album['resource_type'], $album['resource_id']); ?>
			<?php $itemAlbum = Engine_Api::_()->getItem('sesmusic_album', $item->album_id); ?>
			<?php if($item && $this->viewType == 'listView'): ?>
				<li class="sesmusic_sidebar_list">
					<div class="sesmusic_sidebar_list_photo_block">
						<?php if($item->photo_id): ?>
							<?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.icon'), array('class' => 'sesmusic_sidebar_list_thumb')) ?>
						<?php else: ?>
						<?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.icon'), array('class' => 'sesmusic_sidebar_list_thumb')) ?>
						<?php endif; ?>
						<div class="sesmusic_sidebar_list_play_btn">
							<?php $songTitle = preg_replace('/[^a-zA-Z0-9\']/', ' ', $item->getTitle()); ?>
							<?php $songTitle = str_replace("'", '', $songTitle); ?>
							<?php $path = Engine_Api::_()->sesmusic()->songImageURL($item); ?>
							<?php if($item->track_id): ?>
								<?php $uploadoption = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.uploadoption', 'myComputer');
								$consumer_key =  Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.scclientid'); ?>          
								<?php if(($uploadoption == 'both' || $uploadoption == 'soundCloud') && $consumer_key): ?>
								<?php $URL = "http://api.soundcloud.com/tracks/$item->track_id/stream?consumer_key=$consumer_key"; ?>
								<a class="sesmusic_sidebar_list_playbutton " href="javascript:void(0);" onclick="play_music('<?php echo $item->albumsong_id ?>', '<?php echo $URL; ?>', '<?php echo $songTitle; ?>', '', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
								<?php else: ?>
								<a class="sesmusic_sidebar_list_playbutton " href="javascript:void(0);"><i class="fa fa-play-circle"></i></a>
								<?php endif; ?>
							<?php else: ?>
								<?php if($item->store_link): ?>
									<?php $storeLink = !empty($item->store_link) ? (preg_match("#https?://#", $item->store_link) === 0) ? 'http://'.$item->store_link : $item->store_link : ''; ?>
									<a class="sesmusic_sidebar_list_playbutton "  href="javascript:void(0);" onclick="play_music('<?php echo $item->albumsong_id ?>', '<?php echo $item->getFilePath(); ?>', '<?php echo $songTitle; ?>', '<?php echo $storeLink ?>', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
								<?php else: ?>
									<a class="sesmusic_sidebar_list_playbutton "  href="javascript:void(0);" onclick="play_music('<?php echo $item->albumsong_id ?>', '<?php echo $item->getFilePath(); ?>', '<?php echo $songTitle; ?>', '', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
					<div class="sesmusic_sidebar_list_info">
						<div class="sesmusic_sidebar_list_title sesmusic_sidebar_list_song_title">
							
							<?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
						</div>

						<?php if(!empty($this->information) && engine_in_array('postedby', $this->information)): ?>
						<div class="sesmusic_sidebar_list_stats sesbasic_text_light">
							<?php echo $this->translate('by');?> <?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?>
						</div>
						<?php endif; ?>

						<?php if(!empty($this->information) && engine_in_array('title', $this->information) && $album->upload_param == 'album'): ?>
						<div class="sesmusic_sidebar_list_stats sesbasic_text_light">
							<?php echo $this->translate("in "); ?><?php echo $this->htmlLink($album->getHref(), $album->getTitle()) ?>
						</div>
						<?php endif; ?>      

						<?php if($this->showAlbumSongRating && !empty($this->information) && engine_in_array('ratingCount', $this->information)): ?>
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
								<span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>">
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
							<?php if (!empty($this->information) && engine_in_array('downloadCount', $this->information)) : ?>
								<span title="<?php echo $this->translate(array('%s download', '%s downloads', $item->download_count), $this->locale()->toNumber($item->download_count)); ?>">
									<i class="fa fa-download"></i>
									<?php echo $item->download_count; ?>
								</span>
							<?php endif; ?>
						</div>
					</div>
				</li>
			<?php elseif($this->viewType == 'gridview'): ?>
				<?php if($item) { ?>
					<li class="sesmusic_item_grid sesbasic_bxs <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($itemAlbum)) { ?> paid_content <?php } ?>" style="width:<?php echo $this->width ?>px;">
						<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($itemAlbum)) { ?>
						<?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $itemAlbum)); ?>
						<?php } ?>
						<div class="sesmusic_item_artwork">
							<div class="sesmusic_item_artwork_img" style="height:<?php echo $this->height ?>px;">
								<?php if($item) { ?>
								<?php if($item->photo_id) { ?>
								<?php echo $this->itemPhoto($item, 'thumb.profile'); ?>
								<?php } else { ?>
								<?php echo $this->itemPhoto($item, 'thumb.normal'); ?>
								<?php } ?>
								<a href="<?php echo $item->getHref(); ?>" class="transparentbg"></a>
								<?php } ?>
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
										<a href="javascript:;" data-url="<?php echo $item->albumsong_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesmusic_like_<?php echo $item->getType(); ?> <?php echo ($isLike) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count; ?></span></a>
									<?php endif; ?>
									
									<?php $isFavourite = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->isFavourite(array('resource_type' => "sesmusic_albumsong", 'resource_id' => $item->albumsong_id)); ?>
									<?php if(!empty($viewer_id) && $this->addfavouriteAlbumSong && $this->information && engine_in_array('favourite', $this->information)): ?>
										<a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesmusic_favourite_<?php echo $item->getType(); ?> <?php echo ($isFavourite)  ? 'button_active' : '' ?>" data-url="<?php echo $item->albumsong_id ; ?>"><i class="fa fa-heart"></i><span><?php echo $item->favourite_count; ?></span></a>
									<?php endif; ?>
									<!--Like and Favourite Button-->
									
									<?php if($this->viewer_id): ?>
										<?php if($this->canAddPlaylistAlbumSong): ?>
											<a class="sesbasic_icon_btn add-white" title='<?php echo $this->translate("Add to Playlist") ?>' href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module' =>'sesmusic', 'controller' => 'song', 'action'=>'append-songs','album_id' => $item->albumsong_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><i class="fa fa-plus"></i></a>
										<?php endif; ?>
										<?php if($this->songlink && engine_in_array('share', $this->songlink)): ?>
										<a class="sesbasic_icon_btn share-white" title='<?php echo $this->translate("Share") ?>' href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesmusic_albumsong', 'id' => $item->albumsong_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><i class="fas fa-share-alt"></i></a>
										<?php endif; ?>
									<?php endif; ?>
								</div>
								</div>
								<div class="sesmusic_item_stats_info sesmusic_animation">
									<div class="sesmusic_item_info_stats">
									<?php if (!empty($this->information) && engine_in_array('commentCount', $this->information)) :?>
										<span>
											<?php echo $item->comment_count; ?>
											<i class="sesbasic_icon_comment_o"></i>
										</span>
									<?php endif; ?>
									<?php if (!empty($this->information) && engine_in_array('likeCount', $this->information)) : ?>
										<span>
											<?php echo $item->like_count; ?>
											<i class="sesbasic_icon_like_o"></i>
										</span>
									<?php endif; ?>
									<?php if (!empty($this->information) && engine_in_array('viewCount', $this->information)) : ?>
										<span>
											<?php echo $item->view_count; ?>
											<i class="sesbasic_icon_view_o"></i>
										</span>
									<?php endif; ?>
									<?php if (!empty($this->information) && engine_in_array('downloadCount', $this->information)) : ?>
										<span>
											<?php echo $item->download_count; ?>
											<i class="fa fa-download"></i>
										</span>
									<?php endif; ?>
									</div>
									<?php if ($this->showAlbumSongRating && !empty($this->information) && engine_in_array('ratingCount', $this->information)) : ?>
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
								<?php $path = Engine_Api::_()->sesmusic()->songImageURL($item); ?>
								<?php if($item->track_id): ?>
									<?php $uploadoption = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.uploadoption', 'myComputer');
									$consumer_key =  Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.scclientid'); ?>          
									<?php if(($uploadoption == 'both' || $uploadoption == 'soundCloud') && $consumer_key): ?>
									<?php $URL = "http://api.soundcloud.com/tracks/$item->track_id/stream?consumer_key=$consumer_key"; ?>
										<a class="sesmusic_play_button" href="javascript:void(0);" onclick="play_music('<?php echo $item->albumsong_id ?>', '<?php echo $URL; ?>', '<?php echo preg_replace("/[^A-Za-z0-9\-]/", "", $item->getTitle()); ?>', '', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
									<?php else: ?>
										<a class="sesmusic_play_button" href="javascript:void(0);" onclick="play_music('<?php echo $item->albumsong_id ?>', '<?php echo $item->getFilePath(); ?>', '<?php echo $item->getTitle(); ?>', '', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
									<?php endif; ?>
								<?php else: ?>
									<a class="sesmusic_play_button" href="javascript:void(0);" onclick="play_music('<?php echo $item->albumsong_id ?>', '<?php echo $item->getFilePath(); ?>', '<?php echo $item->getTitle(); ?>', '', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
								<?php endif; ?>
								</div>
							</div>
						</div>
						<div class="sesmusic_item_info">
							<div class="sesmusic_item_info_title">
								<?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
							</div>
							<div class="sesmusic_item_info_owner">
								<?php if(!empty($this->information) && engine_in_array('title', $this->information) && $album->upload_param == 'album'): ?>
								<?php echo $this->translate("in "); ?><?php echo $this->htmlLink($album->getHref(), $album->getTitle()) ?>
								<?php endif; ?>
							</div>
							<?php if(!empty($this->information) && engine_in_array('postedby', $this->information)): ?>
								<div class="sesmusic_item_info_owner">
									<?php echo $this->translate('by');?> <?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?>
								</div>
							<?php endif; ?>
						</div>
					</li>
				<?php } ?>
			<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
<?php endif; ?>
