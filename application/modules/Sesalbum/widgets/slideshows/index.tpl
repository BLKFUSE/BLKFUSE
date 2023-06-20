<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>

<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
                 .'application/modules/Sesalbum/externals/scripts/core.js'); ?> 
<?php
$this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
          . 'application/modules/Sesbasic/externals/scripts/flexcroll.js');

?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
                 .'application/modules/Sesbasic/externals/scripts/tagger.js'); ?> 
                          
<?php
$base_url = $this->layout()->staticBaseUrl; ?>
<?php
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js');
$this->headLink()->appendStylesheet($base_url . 'application/modules/Sesbasic/externals/styles/slideshow.css'); 
$this->headLink()->appendStylesheet($base_url . 'application/modules/Sesalbum/externals/styles/styles.css');
?>
<script type="text/javascript">
sesowlJqueryObject(document).ready(function() {
	sesowlJqueryObject('#sesalbum_slideshow<?php echo $this->identity ?>').owlCarousel({
		loop:true,
		dots:false,
		nav:true,
		margin:0,
		items:1,
		singleItem:true
	})
	sesowlJqueryObject(".owl-prev").html('<i class="fas fa-chevron-left"></i>');
	sesowlJqueryObject(".owl-next").html('<i class="fas fa-chevron-right"></i>');
});
</script>
<div class="sesbasic_content_slideshow_container sesbasic_bxs" style="height:<?php echo $this->height_container.'px'; ?>" id="sesbasic_content_slideshow_container">
  <div id="sesalbum_slideshow<?php echo $this->identity ?>" class="sesalbum_members_slideshow">
  	<?php $limit = 0;
     			$allowRatingAlbum = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.album.rating',1);
					$allowShowPreviousRatingAlbum = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.ratealbum.show',1);
          if($allowRatingAlbum == 0){
          	if($allowShowPreviousRatingAlbum == 0)
            	$ratingShowAlbum = false;
             else
             	$ratingShowAlbum = true;
          }else
          	$ratingShowAlbum = true;
          $allowRatingPhoto = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.photo.rating',1);
					$allowShowPreviousRatingPhoto = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.ratephoto.show',1);
          if($allowRatingPhoto == 0){
          	if($allowShowPreviousRatingPhoto == 0)
            	$ratingShowPhoto= false;
             else
             	$ratingShowPhoto = true;
          }else
          	$ratingShowPhoto = true;?>
    <?php foreach( $this->paginator as $item ):
     $photoURL = $item->getPhotoUrl(''); ?>
      <?php if($this->typeWidget == 1 || $this->typeWidget == 3){ ?>
      	 <?php        		 

          if(strpos($photoURL,'http://') === FALSE && strpos($photoURL,'https://') === FALSE)
    				 $imageGetSizeURL = $_SERVER['DOCUMENT_ROOT']. DIRECTORY_SEPARATOR . substr($photoURL, 0, strpos($photoURL, "?"));
          else
         		 $imageGetSizeURL = $photoURL;
    			$imageHeightWidthData = getimagesize($imageGetSizeURL);         
          $width = isset($imageHeightWidthData[0]) ? $imageHeightWidthData[0] : '300';
          $height = isset($imageHeightWidthData[1]) ? $imageHeightWidthData[1] : '200'; 
          if($width >= 500)
          	$photoURL = $item->getPhotoUrl('thumb.main');?>
        <div id="sesalbum_slideshow_id_<?php echo $limit+1; ?>" class="slideshow_sesalbum_plugin ses_album_image_viewer sesalbum_list_grid sesa-i-<?php echo (isset($this->insideOutside) && $this->insideOutside == 'outside') ? 'outside' : 'inside'; ?> sesa-i-<?php echo (isset($this->fixHover) && $this->fixHover == 'fix') ? 'fix' : 'over'; ?> sesbm  <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesalbum',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?> paid_content <?php } ?> sd">
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesalbum',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?>
               <?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $item)); ?>
              <?php } ?>
          <?php 
          $imageURL = Engine_Api::_()->sesalbum()->getImageViewerHref($item,array('limit'=>$limit,'status'=>$this->type,'order'=>$this->order)); ?>
          <a class="sesalbum_list_grid_img ses-image-viewer" onclick="getRequestedAlbumPhotoForImageViewer('<?php echo $item->getPhotoUrl(); ?>','<?php echo $imageURL	; ?>')" href="<?php echo Engine_Api::_()->sesalbum()->getHrefPhoto($item->getIdentity(),$item->album_id); ?>" style="height:<?php echo $this->height_container.'px'; ?>"> 
            <span style="background-image: url(<?php echo $photoURL; ?>);"></span> 
          </a>
          <?php 
            if(isset($this->socialSharing) || isset($this->likeButton) || isset($this->favouriteButton)){
            //album viewpage link for sharing
            $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
            <span class="sesalbum_list_grid_btns">
              <?php if(isset($this->socialSharing)){ ?>
                
                <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>

                <?php } 
                $canComment =  Engine_Api::_()->getItem('album',$item->album_id )->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');
                  if(isset($this->likeButton) && Engine_Api::_()->user()->getViewer()->getIdentity() !=0 && $canComment){
                ?>
                <!--Photo Like Button-->
                <?php $albumLikeStatus = Engine_Api::_()->sesalbum()->getLikeStatusPhoto($item->photo_id); ?>
                  <a href="javascript:;" data-src='<?php echo $item->photo_id; ?>' class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesalbum_photolike <?php echo ($albumLikeStatus) ? 'button_active' : '' ; ?>">
                    <i class="fa fa-thumbs-up"></i>
                    <span><?php echo $item->like_count; ?></span>
                  </a>
                  <?php } 
                  $canFavourite =  Engine_Api::_()->authorization()->isAllowed('album',Engine_Api::_()->user()->getViewer(), 'favourite_photo');
                  if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.allow.favouritephoto', 1) && Engine_Api::_()->user()->getViewer()->getIdentity() !=0 && isset($this->favouriteButton) && $canFavourite){
                  $albumFavStatus = Engine_Api::_()->getDbtable('favourites', 'sesalbum')->isFavourite(array('resource_type'=>'album_photo','resource_id'=>$item->photo_id)); ?>
                    <a href="javascript:;" data-src='<?php echo $item->photo_id; ?>' class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesalbum_photoFav <?php echo ($albumFavStatus)>0 ? 'button_active' : '' ; ?>">
                      <i class="fa fa-heart"></i>
                      <span><?php echo $item->favourite_count; ?></span>
                    </a>
                  <?php } ?>
            </span>
          <?php } ?>
              <?php if(isset($this->featured) || isset($this->sponsored)){ ?>
                <span class="sesalbum_labels_container">
                  <?php if(isset($this->featured) && $item->is_featured == 1){ ?>
                    <span class="sesalbum_label_featured"><?php echo $this->translate("Featured"); ?></span>
                  <?php } ?>
                  <?php if(isset($this->sponsored)  && $item->is_sponsored == 1){ ?>
                    <span class="sesalbum_label_sponsored"><?php echo $this->translate("Sponsored"); ?></span>
                  <?php } ?>
                </span>
              <?php } ?>
              
              <?php if(isset($this->like) || isset($this->comment) || isset($this->view) || isset($this->title) || isset($this->rating) || isset($this->favouriteCount) || isset($this->downloadCount)  || isset($this->by)){ ?>
                <p class="sesalbum_list_grid_info sesbasic_clearfix">
                  <?php if(isset($this->title)) { ?>
                    <span class="sesalbum_list_grid_title">
                      <?php echo $this->htmlLink($item, $this->htmlLink($item, $this->string()->truncate($item->getTitle(), $this->title_truncation), array('title'=>$item->getTitle()))) ?>
                    </span>
                  <?php } ?>
                  <span class="sesalbum_list_grid_stats">
                    <?php if(isset($this->by)) { ?>
                      <span class="sesalbum_list_grid_owner">
                        <?php echo $this->translate('By');?>
                        <?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle(), array('class' => 'thumbs_author')) ?>
                      </span>
                    <?php }?>
                    <?php if(isset($this->rating) && $ratingShowPhoto) { ?>
                     	 <?php
                          $user_rate = Engine_Api::_()->getDbtable('ratings', 'sesalbum')->getCountUserRate('album_photo',$item->photo_id);
                          $textuserRating = $user_rate == 1 ? 'user' : 'users'; 
                          $textRatingText = $item->rating == 1 ? 'rating' : 'ratings'; ?>
                          <span class="sesalbum_list_grid_rating" title="<?php echo $item->rating.' '.$this->translate($textRatingText.' by').' '.$user_rate.' '.$this->translate($textuserRating); ?>">
                        <?php if( $item->rating > 0 ): ?>
                          <?php for( $x=1; $x<= $item->rating; $x++ ): ?>
                            <span class="sesbasic_rating_star_small fa fa-star"></span>
                          <?php endfor; ?>
                          <?php if( (round($item->rating) - $item->rating) > 0): ?>
                            <span class="sesbasic_rating_star_small fa fa-star-half"></span>
                          <?php endif; ?>
                        <?php endif; ?> 
                      </span>
                  	<?php } ?>
                  </span>
                  <span class="sesalbum_list_grid_stats sesbasic_text_light">
                    <?php if(isset($this->like)) { ?>
                      <span class="sesalbum_list_grid_likes" title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count))?>">
                        <i class="sesbasic_icon_like_o"></i>
                        <?php echo $item->like_count;?>
                      </span>
                    <?php } ?>
                  <?php if(isset($this->comment)) { ?>
                    <span class="sesalbum_list_grid_comment" title="<?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count))?>">
                      <i class="sesbasic_icon_comment_o"></i>
                      <?php echo $item->comment_count;?>
                    </span>
                 <?php } ?>
                 <?php if(isset($this->view)) { ?>
                  <span class="sesalbum_list_grid_views" title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count))?>">
                    <i class="sesbasic_icon_view"></i>
                    <?php echo $item->view_count;?>
                  </span>
                 <?php } ?>
                 <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.allow.favouritephoto', 1) && isset($this->favouriteCount)) { ?>
                    <span class="sesalbum_list_grid_fav" title="<?php echo $this->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count))?>">
                      <i class="sesbasic_icon_favourite_o"></i> 
                      <?php echo $item->favourite_count;?>            
                    </span>
                  <?php } ?>
                  <?php if(isset($this->downloadCount)) { ?>
                <span class="sesalbum_list_grid_download" title="<?php echo $this->translate(array('%s download', '%s downloads', $item->download_count), $this->locale()->toNumber($item->download_count))?>">
                  <i class="fa fa-download"></i> 
                  <?php echo $item->download_count;?>            
                </span>
              <?php } ?>
                  </span>
                </p>         
              <?php } ?>
        </div>
      <?php }
     $limit++;
     endforeach; ?>
  </div>
</div>

