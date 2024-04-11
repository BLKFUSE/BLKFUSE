<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
 <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/scripts/core.js'); 
?>

<?php //$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/SesLightbox/photoswipe.min.js'); ?>
<?php //$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/SesLightbox/photoswipe-ui-default.min.js'); ?>
<?php //$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>
<?php //$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/SesLightbox/lightbox.js'); ?>
<?php //$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/photoswipe.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/style_album.css'); ?>
<?php
if(!$this->is_ajax && isset($this->docActive)){
	$imageURL = $this->album->getPhotoUrl();
	if(strpos($this->album->getPhotoUrl(),'http') === false)
          	$imageURL = (!empty($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"] == 'on')) ? "https://" : "http://". $_SERVER['HTTP_HOST'].$this->album->getPhotoUrl();
  $this->doctype('XHTML1_RDFA');
  $this->headMeta()->setProperty('og:title', strip_tags($this->album->getTitle()));
  $this->headMeta()->setProperty('og:description', strip_tags($this->album->getDescription()));
  $this->headMeta()->setProperty('og:image',$imageURL);
  $this->headMeta()->setProperty('twitter:title', strip_tags($this->album->getTitle()));
  $this->headMeta()->setProperty('twitter:description', strip_tags($this->album->getDescription()));
}
 if(isset($this->identityForWidget) && !empty($this->identityForWidget)){
    $randonNumber = $this->identityForWidget;
 }else{
    $randonNumber = $this->identity; 
 } ?>
 <?php 
if(isset($this->canEdit)){
// First, include the Webcam.js JavaScript Library 
  $base_url = $this->layout()->staticBaseUrl;
  $this->headScript()->appendFile($base_url . 'application/modules/Sesbasic/externals/scripts/webcam.js'); 
  }
?>
<?php 
            $editItem = true;
            if($this->canEditMemberLevelPermission == 1){
              if($this->viewer->getIdentity() == $this->album->owner_id){
                $editItem = true;
              }else{
                $editItem = false;
              }
            }else if($this->canEditMemberLevelPermission == 2){
               $editItem = true;
            }else{
                $editItem = false;
            } 
            $deleteItem = true;
            if($this->canDeleteMemberLevelPermission == 1){
              if($this->viewer->getIdentity() == $this->album->owner_id){
                $deleteItem = true;
              }else{
                $deleteItem = false;
              }
            }else if($this->canDeleteMemberLevelPermission == 2){
               $deleteItem = true;
            }else{
                $deleteItem = false;
            }
             $createItem = true;
            if($this->canCreateMemberLevelPermission == 1){
              if($this->viewer->getIdentity() == $this->album->owner_id){
                $createItem = true;
              }else{
                $createItem = false;
              }
            }else{
                $createItem = false;
            }
          ?>
<?php
 if(!$this->is_ajax){
  $this->headTranslate(array(
    'Save', 'Cancel', 'delete',
  ));
?>
<script type="text/javascript">
scriptJquery(document).click(function(news){
	if(news.target.id != 'sesnews_dropdown_btn' && news.target.id != 'a_btn' && news.target.id != 'i_btn'){
		scriptJquery('#sesnews_dropdown_btn').find('.sesnews_album_option_box1').css('display','none');
		scriptJquery('#a_btn').removeClass('active');
	}
	if(news.target.id == 'change_cover_txt' || news.target.id == 'cover_change_btn_i' || news.target.id == 'cover_change_btn'){
		if(scriptJquery('#sesnews_album_change_cover_op').hasClass('active'))
			scriptJquery('#sesnews_album_change_cover_op').removeClass('active')
		else
			scriptJquery('#sesnews_album_change_cover_op').addClass('active')
	}else{
			scriptJquery('#sesnews_album_change_cover_op').removeClass('active')
	}
	if(news.target.id == 'a_btn'){
			if(scriptJquery('#a_btn').hasClass('active')){
				scriptJquery('#a_btn').removeClass('active');
				scriptJquery('.sesnews_album_option_box1').css('display','none');
			}
			else{
				scriptJquery('#a_btn').addClass('active');
				scriptJquery('.sesnews_album_option_box1').css('display','block');
			}
		}else if(news.target.id == 'i_btn'){
			if(scriptJquery('#a_btn').hasClass('active')){
				scriptJquery('#a_btn').removeClass('active');
				scriptJquery('.sesnews_album_option_box1').css('display','none');
			}
			else{
				scriptJquery('#a_btn').addClass('active');
				scriptJquery('.sesnews_album_option_box1').css('display','block');
			}
	}	
});
</script>
<div class="sesnews_album_cover_container sesbasic_bxs">
  <?php if(isset($this->album->art_cover) && $this->album->art_cover != 0 && $this->album->art_cover != ''){ 
  			 $albumArtCover =	Engine_Api::_()->storage()->get($this->album->art_cover, '')->getPhotoUrl(); 
   }else
   		$albumArtCover ='';  
?>
  <div id="sesnews_album_cover_default" class="sesnews_album_cover_thumbs" style="display:<?php echo $albumArtCover == '' ? 'block' : 'none'; ?>;">
  <ul>
  <?php
     $albumImage = Engine_Api::_()->sesnews()->getAlbumPhoto($this->album->getIdentity(),0,3); 
     $countTotal = engine_count($albumImage);
  	 foreach( $albumImage as $photo ){
     		 $imageURL = $photo->getPhotoUrl('thumb.normalmain');
          if(strpos($imageURL,'http') === false){
          	$http_s = (!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://";
          	$imageURL = $http_s.$_SERVER['HTTP_HOST'].$imageURL;
           }
           $widthPer = $countTotal == 3 ? "33.33" : ($countTotal == 2 ? "50" : '100') ; ?> 
         		<li style="height:300px;width:<?php echo $widthPer; ?>%">
                <span style="background-image:url(<?php echo $imageURL; ?>);"></span> 
           	</li>
 		<?php } ?>
 </ul>
 </div>
	<span class="sesnews_album_cover_image" id="cover_art_work_image" style="background-image:url(<?php echo $albumArtCover; ?>);"></span>
 <div style="display:none;" id="sesnews-pos-btn" class="sesnews_album_cover_positions_btns">
  	<a id="saveCoverPosition" href="javascript:;" class="sesbasic_button"><?php echo $this->translate("Save");?></a>
    <a href="javascript:;" id="cancelCoverPosition" class="sesbasic_button"><?php echo $this->translate("Cancel");?></a>
  </div>
  <span class="sesnews_album_cover_fade"></span>
  <?php if( $this->mine || $this->canEdit || $editItem): ?>
    <div class="sesnews_album_coverphoto_op" id="sesnews_album_change_cover_op">
      <a href="javascript:;" id="cover_change_btn"><i class="fa fa-camera" id="cover_change_btn_i"></i><span id="change_cover_txt"><?php echo $this->translate("Upload Cover Photo"); ?></span></a>
      <div class="sesnews_album_coverphoto_op_box sesnews_album_option_box">
      	<i class="sesnews_album_coverphoto_op_box_arrow"></i>
        <?php if($this->canEdit){ ?>
          <input type="file" id="uploadFilesesnews" name="art_cover" onchange="uploadCoverArt(this);"  style="display:none" />
          <a id="uploadWebCamPhoto" href="javascript:;"><i class="fa fa-camera"></i><?php echo $this->translate("Take Photo"); ?></a>
          <a id="coverChangesesnews" data-src="<?php echo $this->album->art_cover; ?>" href="javascript:;"><i class="fa fa-plus"></i><?php echo (isset($this->album->art_cover) && $this->album->art_cover != 0 && $this->album->art_cover != '') ? $this->translate('Change Cover Photo') : $this->translate('Add Cover Photo');; ?></a>
           <a id="coverRemovesesnews" style="display:<?php echo (isset($this->album->art_cover) && $this->album->art_cover != 0 && $this->album->art_cover != '') ? 'block' : 'none' ; ?>;" data-src="<?php echo $this->album->art_cover; ?>" href="javascript:;"><i class="fa fa-trash"></i><?php echo $this->translate('Remove Cover Photo'); ?></a>
        <?php } ?>
      </div>
    </div>
  <?php endif;?>
	<div class="sesnews_album_cover_inner">
  	<div class="sesnews_album_cover_album_cont sesbasic_clearfix">
			<div class="sesnews_album_cover_album_cont_inner">
      	<div class="sesnews_album_cover_album_owner_photo">
        	<?php $coverAlbumPhoto = $this->album->getPhotoUrl('thumb.icon','status'); 
          		if($coverAlbumPhoto == ''){
               echo $this->itemPhoto($this->albumUser, 'thumb.profile');
             	}else{
               $photoCover = Engine_Api::_()->getItem('sesnews_photo',$this->album->photo_id);
    
               $imageURL = $photoCover->getHref(); ?>
              <a class="ses-image-viewer" onclick="openLightBoxForSesPlugins('<?php echo $imageURL	; ?>','<?php echo $photoCover->getPhotoUrl(); ?>')" href="<?php echo $imageURL ?>"> 
          		<img src="<?php echo $coverAlbumPhoto; ?>" />	
              </a>
            <?php } ?>
        </div>
        <div class="sesnews_album_cover_album_info">
          <h2 class="sesnews_album_cover_title">
          	<?php echo trim($this->album->getTitle()) ? $this->album->getTitle() : '<em>' . $this->translate('Untitled') . '</em>'; ?>
          </h2>
          <div class="sesnews_album_cover_date clear sesbasic_clearfix">
            <?php  $news = Engine_Api::_()->getItem('sesnews_news',$this->album->news_id); ?>
           <?php echo  $this->translate('in').' <a href="'.$news->getHref().'"> '.$news->getTitle().'</a>'; ?><br />
          	<?php echo  $this->translate('by').' '.$this->albumUser->__toString(); ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $this->translate('Added %1$s', $this->timestamp($this->album->creation_date)); ?>           
          </div>
          <div class="clear sesbasic_clearfix sesnews_album_cover_album_info_btm">
            <div class="sesnews_album_cover_stats">
            	<div title="<?php echo $this->translate(array('%s photo', '%s photos', $this->album->count()), $this->locale()->toNumber($this->album->count()))?>">
              	<span class="sesnews_album_cover_stat_count"><?php echo $this->album->count(); ?></span>
              	<span class="sesnews_album_cover_stat_txt"><?php echo str_replace(',','',preg_replace('/[0-9]+/', '', $this->translate(array('%s Photo', '%s Photos', $this->album->count()), $this->locale()->toNumber($this->album->count())))); ?></span>
            	</div>
            	<div title="<?php echo $this->translate(array('%s view', '%s views', $this->album->view_count), $this->locale()->toNumber($this->album->view_count))?>">
              	<span class="sesnews_album_cover_stat_count"><?php echo $this->album->view_count; ?></span>
              	<span class="sesnews_album_cover_stat_txt"><?php echo str_replace(',','',preg_replace('/[0-9]+/','',$this->translate(array('%s View', '%s Views', $this->album->view_count), $this->locale()->toNumber($this->album->view_count)))); ?></span>
            	</div>
            	<div title="<?php echo $this->translate(array('%s like', '%s likes', $this->album->like_count), $this->locale()->toNumber($this->album->like_count))?>">
              	<span class="sesnews_album_cover_stat_count"><?php echo $this->album->like_count; ?></span>
              	<span class="sesnews_album_cover_stat_txt"><?php echo str_replace(',','',preg_replace('/[0-9]+/', '', $this->translate(array('%s Like', '%s Likes', $this->album->like_count), $this->locale()->toNumber($this->album->like_count)))); ?></span>
            	</div>
              <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)) { ?>
            	<div title="<?php echo $this->translate(array('%s favourite', '%s favourites', $this->album->favourite_count), $this->locale()->toNumber($this->album->favourite_count))?>">
              	<span class="sesnews_album_cover_stat_count"><?php echo $this->album->favourite_count; ?></span>
              	<span class="sesnews_album_cover_stat_txt"><?php echo str_replace(',','',preg_replace('/[0-9]+/', '', $this->translate(array('%s Favourites', '%s Favourites', $this->album->favourite_count), $this->locale()->toNumber($this->album->favourite_count)))); ?></span>
            	</div>
              <?php } ?>              
            	<div title="<?php echo $this->translate(array('%s comment', '%s comments',$this->album->comment_count), $this->locale()->toNumber($this->album->comment_count))?>">
              	<span class="sesnews_album_cover_stat_count"><?php echo $this->album->comment_count; ?></span>
              	<span class="sesnews_album_cover_stat_txt"><?php echo str_replace(',','',preg_replace('/[0-9]+/', '',  $this->translate(array('%s Comment', '%s Comments',$this->album->comment_count), $this->locale()->toNumber($this->album->comment_count)))); ?></span>
            	</div>
            </div>
          </div>
				</div>          
      </div>
    </div>
    <div class="sesnews_album_cover_footer clear sesbasic_clearfix">
      <ul id="tab_links_cover" class="sesnews_album_cover_tabs sesbasic_clearfix">
        <li data-src="album-info" class="tab_cover"><a href="javascript:;" ><?php echo $this->translate("Album Info") ; ?></a></li>
        <li class="<?php echo $this->paginator->getTotalItemCount() == 0  ? '' : "sesnews_album_cover_tabactive" ; ?> tab_cover" data-src="album-photo" style="display:<?php echo $this->paginator->getTotalItemCount() == 0  ? '' : "" ; ?>"><a href="javascript:;"><?php echo $this->translate("Photos") ; ?></a></li>
        <li class="tab_cover" data-src="album-discussion" ><a href="javascript:;"><?php echo $this->translate("Discussion") ; ?></a></li>
      </ul>
      <?php
         $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $this->album->getHref()); ?>
      <div class="sesnews_album_cover_user_options sesbasic_clearfix">
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)){ ?>
          <?php echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->album, 'param' => 'feed')); ?>
          <?php } ?>
          <?php if( $this->mine || $this->canEdit || $editItem || $deleteItem || $createItem): ?>
            <span class="sesnews_album_cover_user_options_drop_btn" id="sesnews_dropdown_btn">
              <a title="<?php echo $this->translate('Options'); ?>" href="javascript:;" id="a_btn">
                <i class="fa fa-ellipsis-v" id="i_btn"></i>
              </a>
              <div class="sesnews_album_option_box sesnews_album_option_box1">
                <?php if($createItem && $this->allow_create){ ?>
                  <a href="<?php echo $this->url(array('module' => 'sesnews', 'action' => 'create', 'album_id' => $this->album_id), 'sesnews_specific_album', true); ?>">
                  	<i class="fa fa-plus"></i>
                    <?php echo $this->translate('Add More Photos'); ?>
                  </a>
                <?php } ?>
                <?php if($editItem){ ?>
                  <a href="<?php echo $this->url(array('module' => 'sesnews', 'action' => 'edit', 'album_id' => $this->album_id), 'sesnews_specific_album', true); ?>"><i class="fa fa-edit"></i><?php echo $this->translate('Edit Settings'); ?></a>
                <?php } ?>
                <?php if($deleteItem){ ?>
                  <a class="smoothbox" href="<?php echo $this->url(array('module' => 'sesnews', 'action' => 'delete', 'album_id' => $this->album_id, 'format' => 'smoothbox'), 'sesnews_specific_album', true); ?>"><i class="fa fa-trash"></i><?php echo $this->translate('Delete Album'); ?></a>
                <?php } ?>
                <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.report', 1)){ ?>
                 <?php echo $this->htmlLink(Array("module"=> "core", "controller" => "report", "action" => "create", "route" => "default", "subject" => $this->album->getGuid(),'format' => 'smoothbox'),'<i class="fa fa-flag"></i>'.$this->translate("Report"), array("class" => "smoothbox")); ?>               
                 <?php } ?>
              </div>
        		</span>
        	<?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php $baseUrl = $this->layout()->staticBaseUrl; ?>
<div class="clear sesbasic_clearfix sesbasic_bxs" id="scrollHeightDivSes_<?php echo $randonNumber; ?>">
  <ul id="ses-image-view" class="album-photo sesnews_album_listings sesnews_album_photos_listings sesnews_album_photos_flex_view sesbasic_clearfix" style="<?php echo $this->paginator->getTotalItemCount() == 0  ? 'none' : "" ; ?>">
<?php } ?>
    <?php 
    			$limit = 0;
          foreach( $this->paginator as $photo ){
           if($this->view_type != 'masonry'){ ?>
            <li id="thumbs-photo-<?php echo $photo->photo_id ?>" class="ses_album_image_viewer sesnews_album_list_photo_grid sesnews_album_list_grid  sesea-i-<?php echo (isset($this->insideOutside) && $this->insideOutside == 'outside') ? 'outside' : 'inside'; ?> sesea-i-<?php echo (isset($this->fixHover) && $this->fixHover == 'fix') ? 'fix' : 'over'; ?> sesbm" style="width:<?php echo is_numeric($this->width) ? $this->width.'px' : $this->width ?>;">
              <?php $imageURL = $photo->getHref(); ?>
              <a class="sesnews_album_list_grid_img ses-image-viewer" onclick="openLightBoxForSesPlugins('<?php echo $imageURL	; ?>','<?php echo $photo->getPhotoUrl(); ?>')" href="<?php echo $photo->getHref(); ?>" style="height:<?php echo is_numeric($this->height) ? $this->height.'px' : $this->height ?>;"> 
                <span style="background-image: url(<?php echo $photo->getPhotoUrl('thumb.normalmain'); ?>);"></span>
              </a>
              <?php 
              if((isset($this->socialSharing)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)) || isset($this->likeButton) ){
              //album viewpage link for sharing
                $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $photo->getHref()); ?>
      					<span class="sesnews_album_list_grid_btns">
                  <?php if(isset($this->socialSharing)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)){ ?>
                    
                    <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $photo)); ?>
                    

        					<?php } 
                  $canComment =  $canComment =  $this->news->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');
                  	if(isset($this->likeButton) && Engine_Api::_()->user()->getViewer()->getIdentity() !=0 && $canComment){  ?>
                    <?php $albumLikeStatus = Engine_Api::_()->sesnews()->getLikeStatusNews($photo->photo_id,'sesnews_photo'); ?>
                    <a href="javascript:;" data-src='<?php echo $photo->photo_id; ?>' class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesnews_photolike <?php echo ($albumLikeStatus) ? 'button_active' : '' ; ?>">
                      <i class="fa fa-thumbs-up"></i>
                      <span><?php echo $photo->like_count; ?></span>
                    </a>
                  <?php }   if(isset($this->favouriteButton) && Engine_Api::_()->user()->getViewer()->getIdentity() !=0 && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)){
                  ?>
                    <?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesnews')->isFavourite(array('resource_type'=>'sesnews_photo','resource_id'=>'photo_id')); ?>
                    <a href="javascript:;" data-url='<?php echo $photo->photo_id; ?>' class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesnews_favourite <?php echo ($favStatus) ? 'button_active' : '' ; ?>">
                      <i class="fa fa-heart"></i>
                      <span><?php echo $photo->favourite_count; ?></span>
                    </a>
                    <?php }
                    ?>
              	</span>
      				<?php } ?>
              
              <?php if(isset($this->like) || isset($this->comment) || isset($this->favouriteCount) || isset($this->view) || isset($this->title) || isset($this->by)){ ?>
                <p class="sesnews_album_list_grid_info sesbasic_clearfix">
                  <?php if(isset($this->title)) { ?>
                    <span class="sesnews_album_list_grid_title">
                      <?php echo $this->htmlLink($photo, $this->htmlLink($photo, $this->string()->truncate($photo->getTitle(), $this->title_truncation), array('title'=>$photo->getTitle()))) ?>
                    </span>
                  <?php } ?>
                  <span class="sesnews_album_list_grid_stats">
                    <?php if(isset($this->by)) { ?>
                      <span class="sesnews_album_list_grid_owner">
                        <?php echo $this->translate('By');?>
                        <?php echo $this->htmlLink($photo->getOwner()->getHref(), $photo->getOwner()->getTitle(), array('class' => 'thumbs_author')) ?>
                      </span>
                    <?php }?>
                  </span>
                  <span class="sesnews_album_list_grid_stats sesbasic_text_light">
                    <?php if(isset($this->like)) { ?>
                      <span class="sesnews_album_list_grid_likes" title="<?php echo $this->translate(array('%s like', '%s likes', $photo->like_count), $this->locale()->toNumber($photo->like_count))?>">
                        <i class="sesbasic_icon_like_o"></i>
                        <?php echo $photo->like_count;?>
                      </span>
                    <?php } ?>
                     <?php if(isset($this->favouriteCount) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)) { ?>
                      <span class="sesbasic_list_grid_fav" title="<?php echo $this->translate(array('%s favourite', '%s favourites', $photo->favourite_count), $this->locale()->toNumber($photo->favourite_count))?>">
                        <i class="sesbasic_icon_favourite_o"></i>
                        <?php echo $photo->favourite_count;?>
                      </span>
                    <?php } ?>
                  <?php if(isset($this->comment)) { ?>
                    <span class="sesnews_album_list_grid_comment" title="<?php echo $this->translate(array('%s comment', '%s comments', $photo->comment_count), $this->locale()->toNumber($photo->comment_count))?>">
                      <i class="sesbasic_icon_comment_o"></i>
                      <?php echo $photo->comment_count;?>
                    </span>
                 <?php } ?>
                 <?php if(isset($this->view)) { ?>
                  <span class="sesnews_album_list_grid_views" title="<?php echo $this->translate(array('%s view', '%s views', $photo->view_count), $this->locale()->toNumber($photo->view_count))?>">
                    <i class="sesbasic_icon_view"></i>
                    <?php echo $photo->view_count;?>
                  </span>
                 <?php } ?>
                    </span>
                </p>         
              <?php } ?>
            </li>
         <?php }else{
          $imageURL = $photo->getPhotoUrl('thumb.normalmain');
          if(strpos($imageURL,'http://') === FALSE && strpos($imageURL,'https://') === FALSE)
    					$imageGetSizeURL = $_SERVER['DOCUMENT_ROOT']. DIRECTORY_SEPARATOR . substr($imageURL, 0, strpos($imageURL, "?"));
          else
          	$imageGetSizeURL = $imageURL;
    			$imageHeightWidthData = getimagesize($imageGetSizeURL);
          $width = isset($imageHeightWidthData[0]) ? $imageHeightWidthData[0] : '300';
          $height = isset($imageHeightWidthData[1]) ? $imageHeightWidthData[1] : '200'; ?>
         		<li id="thumbs-photo-<?php echo $photo->photo_id ?>" data-w="<?php echo $width ?>" data-h="<?php echo $height; ?>" class="ses_album_image_viewer sesnews_album_list_flex_thumb sesnews_album_list_photo_grid sesnews_album_list_grid sesbasic_list_photo_grid sesea-i-inside sesea-i-<?php echo (isset($this->fixHover) && $this->fixHover == 'fix') ? 'fix' : 'over'; ?>">
              <?php $imageViewerURL = $photo->getHref() ?>
              <a class="sesnews_album_list_flex_img ses-image-viewer" onclick="openLightBoxForSesPlugins('<?php echo $imageViewerURL	; ?>','<?php echo $photo->getPhotoUrl(); ?>')" href="<?php echo $photo->getHref(); ?>"> 
                <img data-src="<?php echo $imageURL; ?>" src="<?php $this->layout()->staticBaseUrl; ?>application/modules/Sesnews/externals/images/blank-img.gif" /> 
              </a>
              <?php 
              if((isset($this->socialSharing)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)) || isset($this->likeButton)){
              //album viewpage link for sharing
                $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $photo->getHref()); ?>
      					<span class="sesnews_album_list_grid_btns">
                  <?php if(isset($this->socialSharing)  && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1)){ ?>
                    
                    <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $photo)); ?>

        					<?php }
                   		$canComment =  $this->news->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');
                  	 if(isset($this->likeButton) && Engine_Api::_()->user()->getViewer()->getIdentity() !=0 && $canComment){  ?>	
                    <?php $albumLikeStatus = Engine_Api::_()->sesnews()->getLikeStatusNews($photo->photo_id,'sesnews_photo'); ?>
                    <a href="javascript:;" data-url='<?php echo $photo->photo_id; ?>' class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesnews_photolike <?php echo ($albumLikeStatus) ? 'button_active' : '' ; ?>">
                      <i class="fa fa-thumbs-up"></i>
                      <span><?php echo $photo->like_count; ?></span>
                    </a>
                  <?php } 
                  if(isset($this->favouriteButton) && Engine_Api::_()->user()->getViewer()->getIdentity() !=0 && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)){
                 ?>
                 <?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesnews')->isFavourite(array('resource_type'=>'sesnews_photo','resource_id'=>'photo_id')); ?>
                    <a href="javascript:;" data-url='<?php echo $photo->photo_id; ?>' class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesnews_favourite <?php echo ($favStatus) ? 'button_active' : '' ; ?>">
                      <i class="fa fa-heart"></i>
                      <span><?php echo $photo->favourite_count; ?></span>
                    </a>
                    <?php }
                    ?>
              	</span>
      				<?php } ?>
              
          
              
              <?php if(isset($this->like) || isset($this->comment) || isset($this->view) || isset($this->title) || isset($this->by)){ ?>
                <p class="sesnews_album_list_grid_info sesbasic_clearfix">
                  <?php if(isset($this->title)) { ?>
                    <span class="sesnews_album_list_grid_title">
                      <?php echo $this->htmlLink($photo, $this->htmlLink($photo, $this->string()->truncate($photo->getTitle(), $this->title_truncation), array('title'=>$photo->getTitle()))) ?>
                    </span>
                  <?php } ?>
                  <span class="sesnews_album_list_grid_stats">
                    <?php if(isset($this->by) && $photo->user_id) { ?>
                      <span class="sesnews_album_list_grid_owner">
                        <?php echo $this->translate('By');?>
                        <?php echo $this->htmlLink($photo->getOwner()->getHref(), $photo->getOwner()->getTitle(), array('class' => 'thumbs_author')) ?>
                      </span>
                    <?php }?>
                  </span>
                  <span class="sesnews_album_list_grid_stats sesbasic_text_light">
                    <?php if(isset($this->like)) { ?>
                      <span class="sesnews_album_list_grid_likes" title="<?php echo $this->translate(array('%s like', '%s likes', $photo->like_count), $this->locale()->toNumber($photo->like_count))?>">
                        <i class="sesbasic_icon_like_o"></i>
                        <?php echo $photo->like_count;?>
                      </span>
                    <?php } ?>
                      <?php if(isset($this->favouriteCount) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1)) { ?>
                      <span class="sesbasic_list_grid_fav" title="<?php echo $this->translate(array('%s favourite', '%s favourites', $photo->favourite_count), $this->locale()->toNumber($photo->favourite_count))?>">
                        <i class="sesbasic_icon_favourite_o"></i>
                        <?php echo $photo->favourite_count;?>
                      </span>
                    <?php } ?>
                  <?php if(isset($this->comment)) { ?>
                    <span class="sesnews_album_list_grid_comment" title="<?php echo $this->translate(array('%s comment', '%s comments', $photo->comment_count), $this->locale()->toNumber($photo->comment_count))?>">
                      <i class="sesbasic_icon_comment_o"></i>
                      <?php echo $photo->comment_count;?>
                    </span>
                 <?php } ?>
                 <?php if(isset($this->view)) { ?>
                  <span class="sesnews_album_list_grid_views" title="<?php echo $this->translate(array('%s view', '%s views', $photo->view_count), $this->locale()->toNumber($photo->view_count))?>">
                    <i class="sesbasic_icon_view"></i>
                    <?php echo $photo->view_count;?>
                  </span>
                 <?php } ?>
                    </span>
                </p>         
              <?php } ?>   
            </li>
         <?php } 
         		 $limit++;
           }
         		 if($this->loadOptionData == 'pagging'){ ?>
             <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sesnews"),array('identityWidget'=>$randonNumber)); ?>
       		  <?php }
         
          ?>
<?php if(!$this->is_ajax) { ?>
  </ul>
  <!--Album Info Tab-->
	<div class="clear sesbasic_clearfix sesnews_album_info">
    <div class="sesnews_album_info_left album-info" style="display:none;">
      <?php if( '' != trim($this->album->getDescription())){ ?>
        <div class="sesnews_album_info_desc clear"><?php echo nl2br($this->album->getDescription()); ?></div>  
      <?php }else{ ?>
      	<div class="tip">
        	<span>
        		<?php echo $this->translate("No description found.");?>
          </span>
        </div>
      <?php } ?>
		</div>
   	<div class="sesnews_album_info_left album-discussion layout_core_comments" style="display:none">
  		<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')){ ?>
                      <?php echo $this->action("list", "comment", "sesadvancedcomment", array("type" => "sesnews_album", "id" => $this->album->getIdentity(),'is_ajax_load'=>true)); 
                        }else{
                         echo $this->action("list", "comment", "core", array("type" => "sesnews_album", "id" => $this->album->getIdentity())); 
                         }
                         ?>
  	</div>
	 </div>
  <?php } ?>
  <?php if(!$this->is_ajax){ ?>
   <?php if($this->loadOptionData != 'pagging'){ ?>
    <div class="sesbasic_view_more" style="display::none;" id="view_more_<?php echo $randonNumber; ?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array('id' => "feed_viewmore_link_$randonNumber", 'class' => 'buttonlink icon_viewmore')); ?> </div>
    <div class="sesbasic_view_more_loading" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"> <img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sesbasic/externals/images/loading.gif' /></div>
  <?php } ?>
</div>

<script type="text/javascript">
<?php if(!$this->is_ajax && $this->canEdit){ ?>
scriptJquery('<div class="sesnews_album_photo_update_popup sesbasic_bxs" id="sesnews_popup_cam_upload" style="display:none"><div class="sesnews_album_photo_update_popup_overlay"></div><div class="sesnews_album_photo_update_popup_container sesnews_album_photo_update_webcam_container"><div class="sesnews_album_photo_update_popup_header"><?php echo $this->translate("Click to Take Cover Photo") ?><a class="fa fa-times" href="javascript:;" onclick="hideProfilePhotoUpload()" title="<?php echo $this->translate("Close") ?>"></a></div><div class="sesnews_album_photo_update_popup_webcam_options"><div id="sesnews_camera" style="background-color:#ccc;"></div><div class="centerT sesnews_album_photo_update_popup_btns">   <button onclick="take_snapshot()" style="margin-right:3px;" ><?php echo $this->translate("Take Cover Photo") ?></button><button onclick="hideProfilePhotoUpload()" ><?php echo $this->translate("Cancel") ?></button></div></div></div></div><div class="sesnews_album_photo_update_popup sesbasic_bxs" id="sesnews_popup_existing_upload" style="display:none"><div class="sesnews_album_photo_update_popup_overlay"></div><div class="sesnews_album_photo_update_popup_container" id="sesnews_popup_container_existing"><div class="sesnews_album_photo_update_popup_header"><?php echo $this->translate("Select a cover photo") ?><a class="fa fa-times" href="javascript:;" onclick="hideProfilePhotoUpload()" title="<?php echo $this->translate("Close") ?>"></a></div><div class="sesnews_album_photo_update_popup_content"><div id="sesnews_album_existing_data"></div><div id="sesnews_profile_existing_img" style="display:none;text-align:center;"><img src="application/modules/Sesbasic/externals/images/loading.gif" alt="<?php echo $this->translate("Loading"); ?>" style="margin-top:10px;"  /></div></div></div></div>').appendTo('body');
var canPaginatePageNumber = 1;
scriptJquery(document).on('click','#uploadWebCamPhoto',function(){
	scriptJquery('#sesnews_popup_cam_upload').show();
	<!-- Configure a few settings and attach camera -->
	Webcam.set({
		width: 320,
		height: 240,
		image_format:'jpeg',
		jpeg_quality: 90
	});
	Webcam.attach('#sesnews_camera');
});
<!-- Code to handle taking the snapshot and displaying it locally -->
function take_snapshot() {
	// take snapshot and get image data
	Webcam.snap(function(data_uri) {
		Webcam.reset();
		scriptJquery('#sesnews_popup_cam_upload').hide();
		// upload results
		scriptJquery('.sesnews_album_cover_container').append('<div id="sesnews_album_cover_loading" class="sesbasic_loading_cont_overlay"></div>');
		 Webcam.upload( data_uri, en4.core.baseUrl+'sesnews/album/upload-cover/album_id/<?php echo $this->album_id ?>' , function(code, text) {
				response = scriptJquery.parseJSON(text);
				scriptJquery('#sesnews_album_cover_loading').remove();
				scriptJquery('.sesnews_album_cover_image').css('background-image', 'url(' + response.file + ')');
				scriptJquery('#sesnews_album_cover_default').hide();
				scriptJquery('#coverChangesesnews').html('<i class="fa fa-plus"></i>'+en4.core.language.translate('Change Cover Photo'));
				scriptJquery('#coverRemovesesnews').css('display','block');
			} );
	});
}
function hideProfilePhotoUpload(){
	if(typeof Webcam != 'undefined')
	 Webcam.reset();
	canPaginatePageNumber = 1;
	scriptJquery('#sesnews_popup_cam_upload').hide();
	scriptJquery('#sesnews_popup_existing_upload').hide();
	if(typeof Webcam != 'undefined'){
		scriptJquery('.slimScrollDiv').remove();
		scriptJquery('.sesnews_album_photo_update_popup_content').html('<div id="sesnews_album_existing_data"></div><div id="sesnews_profile_existing_img" style="display:none;text-align:center;"><img src="application/modules/Sesbasic/externals/images/loading.gif" alt="Loading" style="margin-top:10px;"  /></div>');
	}
}

scriptJquery(document).on('click','#coverChangesesnews',function(){
	document.getElementById('uploadFilesesnews').click();	
});
function uploadCoverArt(input){
	 var url = input.value;
    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
    if (input.files && input.files[0] && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG' || ext == 'gif'  || ext == 'GIF'  || ext == 'webp')){
				uploadFileToServer(input.files[0]);
    }else{
				//Silence
		}
}
scriptJquery('#coverRemovesesnews').click(function(){
		scriptJquery(this).css('display','none');
		scriptJquery('.sesnews_album_cover_image').css('background-image', 'url()');
		scriptJquery('#sesnews_album_cover_default').show();
		var album_id = '<?php echo $this->album->album_id; ?>';
		uploadURL = en4.core.baseUrl+'sesnews/album/remove-cover/album_id/'+album_id;
		var jqXHR=scriptJquery.ajax({
			url: uploadURL,
			type: "POST",
			contentType:false,
			processData: false,
			cache: false,
			success: function(response){
				scriptJquery('#coverChangesesnews').html('<i class="fa fa-plus"></i>'+en4.core.language.translate('Add Cover Photo'));
				//silence
			 }
			}); 
});
scriptJquery('#changePositionOfCoverPhoto').click(function(){
		scriptJquery('.sesnews_album_cover_fade').css('display','none');
		scriptJquery('.sesnews_album_cover_inner').css('display','none');
		scriptJquery('#sesnews-pos-btn').css('display','inline-block');
});
scriptJquery(document).on('click','#cancelCoverPosition',function(){
	scriptJquery('.sesnews_album_cover_fade').css('display','block');
	scriptJquery('.sesnews_album_cover_inner').css('display','block');
	scriptJquery('#sesnews-pos-btn').css('display','none');
});
scriptJquery('#saveCoverPosition').click(function(){
	var album_id = '<?php echo $this->album->album_id; ?>';
	var bgPosition = scriptJquery('#cover_art_work_image').css('background-position');
	scriptJquery('.sesnews_album_cover_fade').css('display','block');
	scriptJquery('.sesnews_album_cover_inner').css('display','block');
	scriptJquery('#sesnews-pos-btn').css('display','none');
	var URL = en4.core.baseUrl+'albums/index/change-position/album_id/'+album_id;
	(scriptJquery.ajax({
    dataType: 'html',
		method: 'post',
		'url':URL,
		'data': {
			format: 'html',
			position: bgPosition,    
			album_id:'<?php echo $this->album_id; ?>',
		},
		success: function(responseHTML) {
			//silence
		}
	}));
});
function uploadFileToServer(files){
	scriptJquery('.sesnews_album_cover_container').append('<div id="sesnews_album_cover_loading" class="sesbasic_loading_cont_overlay"></div>');
	var formData = new FormData();
	formData.append('Filedata', files);
	uploadURL = en4.core.baseUrl+'sesnews/album/upload-cover/album_id/<?php echo $this->album_id ?>';
	var jqXHR=scriptJquery.ajax({
    dataType: 'json',
    url: uploadURL,
    type: "POST",
    contentType:false,
    processData: false,
		cache: false,
		data: formData,
		success: function(response){
			//response = scriptJquery.parseJSON(response);
			scriptJquery('#sesnews_album_cover_loading').remove();
			scriptJquery('.sesnews_album_cover_image').css('background-image', 'url(' + response.file + ')');
				scriptJquery('#sesnews_album_cover_default').hide();
			scriptJquery('#coverChangesesnews').html('<i class="fa fa-plus"></i>'+en4.core.language.translate('Change Cover Photo'));
			scriptJquery('#coverRemovesesnews').css('display','block');
     }
    }); 
}
<?php } ?>

<?php if($this->loadOptionData == 'auto_load'){ ?>
		scriptJquery(document).ready(function() {
		 scriptJquery(window).scroll( function() {
			 if(!document.getElementById('loading_image_<?php echo $randonNumber; ?>'))
			 	return false;
			  var heightOfContentDiv_<?php echo $randonNumber; ?> = scriptJquery('#scrollHeightDivSes_<?php echo $randonNumber; ?>').offset().top;
        var fromtop_<?php echo $randonNumber; ?> = scriptJquery(this).scrollTop();
        if(fromtop_<?php echo $randonNumber; ?> > heightOfContentDiv_<?php echo $randonNumber; ?> - 100 && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block' ){
						document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
				}
     });
	});
<?php } ?>
</script>
<?php } ?>
<script type="text/javascript">
<?php if(!$this->is_ajax){ ?>
		scriptJquery(document).on('click','#tab_links_cover > li',function(){
			var elemLength = scriptJquery('#tab_links_cover').children();
			
			for(i=0;i<elemLength.length;i++){
          if(scriptJquery(elemLength[i]))
					scriptJquery(scriptJquery(elemLength[i]).removeClass('sesnews_album_cover_tabactive'));
					scriptJquery('.'+scriptJquery(elemLength[i]).attr('data-src')).css('display','none');
			}
				scriptJquery(this).addClass('sesnews_album_cover_tabactive');
				scriptJquery('.'+scriptJquery(this).attr('data-src')).css('display','block');
				if("<?php echo $this->view_type ; ?>" == 'masonry'){
					scriptJquery("#ses-image-view").sesbasicFlexImage({rowHeight: <?php echo str_replace('px','',$this->height); ?>});
				}
				if(scriptJquery(this).attr('data-src') == 'album-photo'){
					scriptJquery('#sesnews-container-right').css('display','none');
					if(scriptJquery('#view_more_<?php echo $randonNumber; ?>'))
						scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display','block');
					if(scriptJquery('#view_more_<?php echo $randonNumber; ?>'))
						scriptJquery('#loading_image_<?php echo $randonNumber; ?>').css('display','none');					
					if(scriptJquery('#view_more_related_<?php echo $randonNumber; ?>'))							
							scriptJquery('#view_more_related_<?php echo $randonNumber; ?>').css('display','none');						
						if(scriptJquery('#view_more_related<?php echo $randonNumber; ?>'))
							scriptJquery('#loading_image_related_<?php echo $randonNumber; ?>').css('display','none');
				}else{
					scriptJquery('#sesnews-container-right').css('display','block');
						if(scriptJquery('#view_more_<?php echo $randonNumber; ?>'))							
							scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display','none');						
						if(scriptJquery('#view_more_<?php echo $randonNumber; ?>'))
							scriptJquery('#loading_image_<?php echo $randonNumber; ?>').css('display','none');
						if(scriptJquery('#view_more_related_<?php echo $randonNumber; ?>'))							
							scriptJquery('#view_more_related_<?php echo $randonNumber; ?>').css('display','none');						
						if(scriptJquery('#view_more_related<?php echo $randonNumber; ?>'))
							scriptJquery('#loading_image_related_<?php echo $randonNumber; ?>').css('display','none');
				}
		});
	 var divPosition = scriptJquery('.sesnews_album_cover_inner').offset();
	 scriptJquery('html, body').animate({scrollTop: divPosition.top}, "slow");
	 if("<?php echo $this->view_type ; ?>" == 'masonry'){
		scriptJquery("#ses-image-view").sesbasicFlexImage({rowHeight: <?php echo str_replace('px','',$this->height); ?>});
	 }
<?php } ?>
viewMoreHide_<?php echo $randonNumber; ?>();
  function viewMoreHide_<?php echo $randonNumber; ?>() {
    if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
			if(scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'none'){
				scriptJquery('#view_more_<?php echo $randonNumber; ?>').remove();
				scriptJquery('#loading_image_<?php echo $randonNumber; ?>').remove();
			}	
  }
	 function viewMore_<?php echo $randonNumber; ?> (){
    document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = 'none';
    document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = '';    
    (scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + 'widget/index/mod/sesnews/name/album-view-page/',
      'data': {
        format: 'html',
        page: <?php echo $this->page ; ?>,    
				params :'<?php echo json_encode($this->params); ?>', 
				is_ajax : 1,
				album_id:'<?php echo $this->album_id; ?>',
				news_id:'<?php echo $this->news_id ?>',
				identity : '<?php echo $randonNumber; ?>',
      },
      success: function(responseHTML) {
        scriptJquery('#ses-image-view').append(responseHTML);
				if("<?php echo $this->view_type ; ?>" == 'masonry'){
							scriptJquery("#ses-image-view").sesbasicFlexImage({rowHeight: <?php echo str_replace('px','',$this->height); ?>});
				}
				if(document.getElementById('loading_image_<?php echo $randonNumber; ?>'))
					document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
      }
    }));
    return false;
  }

function paggingNumber<?php echo $randonNumber; ?>(pageNum){
		 scriptJquery ('.overlay_<?php echo $randonNumber ?>').css('display','block');
			(scriptJquery.ajax({
        dataType: 'html',
				method: 'post',
				'url': en4.core.baseUrl + 'widget/index/mod/sesnews/name/album-view-page/',
				'data': {
					format: 'html',
					page: pageNum,    
					params :'<?php echo json_encode($this->params); ?>', 
					is_ajax : 1,
					identity : '<?php echo $randonNumber; ?>',
					album_id:'<?php echo $this->album_id; ?>',
				},
				success: function(responseHTML) {
					scriptJquery ('.overlay_<?php echo $randonNumber ?>').css('display','none');
					document.getElementById('ses-image-view').innerHTML =  responseHTML;
					if("<?php echo $this->view_type ; ?>" == 'masonry'){
						scriptJquery("#ses-image-view").sesbasicFlexImage({rowHeight: <?php echo str_replace('px','',$this->height); ?>});
					}
				}
			}));
			return false;
	}
</script>
