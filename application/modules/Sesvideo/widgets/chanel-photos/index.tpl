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

<?php if(!$this->is_ajax && $this->canCreate){ ?>
  <div class="sesbasic_profile_tabs_top sesbasic_clearfix">
    <?php // echo $this->htmlLink($this->url(array('chanel_id' =>$this->subject->chanel_id,'action'=>'photos'),'sesvideo_chanel'), $this->translate('Add Photos'), array('class'=>'sesbasic_button fa fa-plus')); ?>
    <?php 
    $viewer = Engine_Api::_()->user()->getViewer();
    if(($this->subject->owner_id == $viewer->getIdentity() || $viewer->level_id==1 ) && $this->canCreate): ?>
    <a href="<?php echo $this->url(array('chanel_id' =>$this->subject->chanel_id,'action'=>'photos'),'sesvideo_chanel'); ?>" class="sesbasic_button"><i class="fa fa-plus"></i><span><?php echo $this->translate("Add Photos"); ?></span></a>
  <?php endif; ?>
  </div>
<?php } ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesalbum/externals/styles/styles.css'); ?>
<?php if(isset($this->identityForWidget) && !empty($this->identityForWidget)){
  $randonNumber = $this->identityForWidget;
}else{
  $randonNumber = $this->identity; 
 }?>
<?php if(!$this->is_ajax){ ?>
<div class="clear sesbasic_clearfix" id="scrollHeightDivSes_<?php echo $randonNumber; ?>">
  <ul class="sesalbum_listings sesalbum_profile_listings sesalbum_photos_flex_view sesbasic_bxs sesbasic_clearfix" id="tabbed-widget_<?php echo $randonNumber; ?>">
<?php } ?>
          <?php $limit = $this->limit;
    			foreach( $this->paginator as $photo ): ?> 
    <?php if($this->view_type != 'masonry'){ ?>
            <li id="thumbs-photo-<?php echo $photo->chanelphoto_id ?>"  class="ses_album_image_viewer sesalbum_list_grid_thumb sesalbum_list_photo_grid sesalbum_list_grid  sesa-i-<?php echo (isset($this->insideOutside) && $this->insideOutside == 'outside') ? 'outside' : 'inside'; ?> sesa-i-<?php echo (isset($this->fixHover) && $this->fixHover == 'fix') ? 'fix' : 'over'; ?> sesbm sesalbum_profile_list_width_<?php echo $randonNumber; ?>">
              <?php $imageURL = Engine_Api::_()->sesvideo()->getImageViewerHref($photo); ?>
              <a class="sesalbum_list_grid_img ses-image-viewer sesbm sesalbum_profile_list_height_<?php echo $randonNumber; ?>" onclick="getRequestedAlbumPhotoForImageViewer('<?php echo $photo->getPhotoUrl(); ?>','<?php echo $imageURL	; ?>')" href="<?php echo $photo->getHref(); ?>"> 
           <span style="background-image: url(<?php echo $photo->getPhotoUrl('thumb.normalmain'); ?>);"></span> 
              </a>
              <?php 
        if(isset($this->socialSharing) || isset($this->likeButton)){
           //album viewpage link for sharing
          $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $photo->getHref()); ?>
      <span class="sesalbum_list_grid_btns">
      <?php if(isset($this->socialSharing)){ ?>
        <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $photo, 'socialshare_icon_limit' => $this->socialshare_icon_limit, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon)); ?>

        			<?php } 
              	$canComment =   Engine_Api::_()->getItem('sesvideo_chanel',$photo->chanel_id)->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');
              	if(isset($this->likeButton) && Engine_Api::_()->user()->getViewer()->getIdentity() !=0 && $canComment){  ?>
                	<!--Chanel Like Button-->
                  <?php $chanelLikeStatus = Engine_Api::_()->sesvideo()->getLikeStatusVideo($photo->chanelphoto_id,'chanelphoto'); ?>
              <a href="javascript:;" data-src='<?php echo $photo->chanelphoto_id; ?>' class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesalbum_chanelphotolike <?php echo ($chanelLikeStatus) ? 'button_active' : '' ; ?>">
                    <i class="fa fa-thumbs-up"></i>
                    <span><?php echo $photo->like_count; ?></span>
                  </a>
                  <?php }  ?>
              	</span>
      				<?php } ?>
              <?php if(isset($this->like) || isset($this->comment) || isset($this->view) || isset($this->title)){ ?>
                <p class="sesalbum_list_grid_info sesbasic_clearfix">
                  <?php if(isset($this->title)) { ?>
                    <span class="sesalbum_list_grid_title">
                      <?php echo $this->htmlLink($photo, $this->htmlLink($photo, $this->string()->truncate($photo->getTitle(), $this->title_truncation), array('title'=>$photo->getTitle()))) ?>
                    </span>
                  <?php } ?>
                  <?php if(isset($this->by)) { ?>
                    <span class="sesalbum_list_grid_owner">
                      <?php echo $this->translate('By');?>
                      <?php echo $this->htmlLink($photo->getOwner()->getHref(), $photo->getOwner()->getTitle(), array('class' => 'thumbs_author')) ?>
                    </span>
                  <?php }?>
                  <span class="sesalbum_list_grid_stats sesbasic_text_light">
                    <?php if(isset($this->like)) { ?>
                      <span class="sesalbum_list_grid_likes" title="<?php echo $this->translate(array('%s like', '%s likes', $photo->like_count), $this->locale()->toNumber($photo->like_count))?>">
                        <i class="sesbasic_icon_like_o"></i>
                        <?php echo $photo->like_count;?>
                      </span>
                    <?php } ?>
                  <?php if(isset($this->comment)) { ?>
                    <span class="sesalbum_list_grid_comment" title="<?php echo $this->translate(array('%s comment', '%s comments', $photo->comment_count), $this->locale()->toNumber($photo->comment_count))?>">
                      <i class="sesbasic_icon_comment_o"></i>
                      <?php echo $photo->comment_count;?>
                    </span>
                 <?php } ?>
                 <?php if(isset($this->view)) { ?>
                  <span class="sesalbum_list_grid_views" title="<?php echo $this->translate(array('%s view', '%s views', $photo->view_count), $this->locale()->toNumber($photo->view_count))?>">
                    <i class="sesbasic_icon_view"></i>
                    <?php echo $photo->view_count;?>
                  </span>
                 <?php } ?>
                 <?php if(isset($this->downloadCount)) { ?>
                <span class="sesalbum_list_grid_download" title="<?php echo $this->translate(array('%s download', '%s downloads', $photo->download_count), $this->locale()->toNumber($photo->download_count))?>">
                  <i class="fa fa-download"></i> 
                  <?php echo $photo->download_count;?>            
                </span>
              <?php } ?>
                </span>
              </p>         
              <?php } ?>  
            </li>
         <?php }else{ ?>
          <?php $imageURL = $photo->getPhotoUrl('thumb.normalmain');
          if(strpos($imageURL,'http') === false){
          	$http_s = (!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://";
          	$imageURL = $http_s.$_SERVER['HTTP_HOST'].$imageURL;
           }
    			$imageHeightWidthData = getimagesize($imageURL);
          $width = isset($imageHeightWidthData[0]) ? $imageHeightWidthData[0] : '300';
          $height = isset($imageHeightWidthData[1]) ? $imageHeightWidthData[1] : '200'; ?>
         		<li id="thumbs-photo-<?php echo $photo->chanelphoto_id ?>" data-w="<?php echo $width ?>" data-h="<?php echo $height; ?>" class="ses_album_image_viewer sesalbum_list_flex_thumb sesalbum_list_photo_grid sesalbum_list_grid sesa-i-inside sesa-i-<?php echo (isset($this->fixHover) && $this->fixHover == 'fix') ? 'fix' : 'over'; ?>">
              <?php $imageViewerURL = Engine_Api::_()->sesvideo()->getImageViewerHref($photo,array('limit'=>$limit)); ?>
              <a class="sesalbum_list_flex_img ses-image-viewer" onclick="getRequestedAlbumPhotoForImageViewer('<?php echo $photo->getPhotoUrl(); ?>','<?php echo $imageViewerURL	; ?>')" href="<?php echo $photo->getHref();  ?>"> 
                <img data-src="<?php echo $imageURL; ?>" src="<?php $this->layout()->staticBaseUrl; ?>application/modules/Sesalbum/externals/images/blank-img.gif" /> 
              </a>
              <?php 
        if(isset($this->socialSharing) || isset($this->likeButton)){
           //chanel photo viewpage link for sharing
          $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $photo->getHref()); ?>
      <span class="sesalbum_list_grid_btns">
      <?php if(isset($this->socialSharing)){ ?>
      
        <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $photo, 'socialshare_icon_limit' => $this->socialshare_icon_limit, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon)); ?>

        			<?php } 
             $canComment =  Engine_Api::_()->getItem('sesvideo_chanel',$photo->chanel_id)->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');
              	if(isset($this->likeButton) && Engine_Api::_()->user()->getViewer()->getIdentity() != 0 && $canComment){  ?>
                	<!--Album Like Button-->
                  <?php $chanelLikeStatus = Engine_Api::_()->sesvideo()->getLikeStatusVideo($photo->chanelphoto_id,'chanelphoto'); ?>
                  <a href="javascript:;" data-src='<?php echo $photo->chanelphoto_id; ?>' class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesalbum_chanelphotolike <?php echo ($chanelLikeStatus) ? 'button_active' : '' ; ?>">
                    <i class="fa fa-thumbs-up"></i>
                    <span><?php echo $photo->like_count; ?></span>
                  </a>
                  <?php } ?>
             	</span>
      				<?php } ?>
              <?php if(isset($this->like) || isset($this->comment) || isset($this->view) || isset($this->title)){ ?>
                <p class="sesalbum_list_grid_info sesbasic_clearfix">
                  <?php if(isset($this->title)) { ?>
                    <span class="sesalbum_list_grid_title">
                      <?php echo $this->htmlLink($photo, $this->htmlLink($photo, $this->string()->truncate($photo->getTitle(), $this->title_truncation), array('title'=>$photo->getTitle()))) ?>
                    </span>
                  <?php } ?>
                  <?php if(isset($this->by)) { ?>
                    <span class="sesalbum_list_grid_owner">
                      <?php echo $this->translate('By');?>
                      <?php echo $this->htmlLink($photo->getOwner()->getHref(), $photo->getOwner()->getTitle(), array('class' => 'thumbs_author')) ?>
                    </span>
                  <?php }?>
                  <span class="sesalbum_list_grid_stats sesbasic_text_light">
                    <?php if(isset($this->like)) { ?>
                      <span class="sesalbum_list_grid_likes" title="<?php echo $this->translate(array('%s like', '%s likes', $photo->like_count), $this->locale()->toNumber($photo->like_count))?>">
                        <i class="sesbasic_icon_like_o"></i>
                        <?php echo $photo->like_count;?>
                      </span>
                    <?php } ?>
                  <?php if(isset($this->comment)) { ?>
                    <span class="sesalbum_list_grid_comment" title="<?php echo $this->translate(array('%s comment', '%s comments', $photo->comment_count), $this->locale()->toNumber($photo->comment_count))?>">
                      <i class="sesbasic_icon_comment_o"></i>
                      <?php echo $photo->comment_count;?>
                    </span>
                 <?php } ?>
                 <?php if(isset($this->view)) { ?>
                  <span class="sesalbum_list_grid_views" title="<?php echo $this->translate(array('%s view', '%s views', $photo->view_count), $this->locale()->toNumber($photo->view_count))?>">
                    <i class="sesbasic_icon_view"></i>
                    <?php echo $photo->view_count;?>
                  </span>
                 <?php } ?>
                 <?php if(isset($this->downloadCount)) { ?>
                <span class="sesalbum_list_grid_download" title="<?php echo $this->translate(array('%s download', '%s downloads', $photo->download_count), $this->locale()->toNumber($photo->download_count))?>">
                  <i class="fa fa-download"></i> 
                  <?php echo $photo->download_count;?>            
                </span>
              <?php } ?>
                </span>
              </p>         
              <?php } ?> 
            </li>
         <?php } ?>
    <?php $limit++;
    			endforeach;
       if($this->load_content == 'pagging'){ ?>
 		 <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sesalbum"),array('identityWidget'=>$randonNumber)); ?>
 <?php } ?>
           <?php  if(  $this->paginator->getTotalItemCount() == 0){  ?>
    <div class="tip">
      <span>
      	<?php echo $this->translate("There are currently no photos.");?>
      </span>
    </div>    
    <?php } ?>
    <?php if(!$this->is_ajax){ ?>
  </ul>
   <?php if($this->load_content != 'pagging'){ ?>
  <div class="sesbasic_view_more sesbasic_load_btn" style="display:none;" id="view_more_<?php echo $randonNumber; ?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array('id' => "feed_viewmore_link_$randonNumber", 'class' => 'sesbasic_animation sesbasic_link_btn fa fa-sync')); ?> </div>
  <div class="sesbasic_view_more_loading" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"> <span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span>  </div>
  <?php  } ?>
</div>
<script type="text/javascript">

 if("<?php echo $this->view_type ; ?>" == 'masonry'){
	scriptJquery("#tabbed-widget_<?php echo $randonNumber; ?>").flexImages({rowHeight: <?php echo str_replace('px','',$this->height); ?>});
	
	scriptJquery(document).on('click','.tab_layout_sesvideo_chanel_photos',function(){
		scriptJquery("#tabbed-widget_<?php echo $randonNumber; ?>").flexImages({rowHeight: <?php echo str_replace('px','',$this->height); ?>});
	});
 }
 
// globally define available tab array
<?php if($this->load_content == 'auto_load'){ ?>
		scriptJquery(document).ready(function() {
		 scriptJquery(window).scroll( function() {
			  var heightOfContentDiv_<?php echo $randonNumber; ?> = scriptJquery('#scrollHeightDivSes_<?php echo $randonNumber; ?>').offset().top;
        var fromtop_<?php echo $randonNumber; ?> = scriptJquery(this).scrollTop();
        if(fromtop_<?php echo $randonNumber; ?> > heightOfContentDiv_<?php echo $randonNumber; ?> - 100 && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block' && scriptJquery('.tab_layout_sesvideo_chanel_photos').hasClass('active')){
						document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
				}
     });
	});
<?php } ?>
</script>
<?php } ?>
<script type="text/javascript">
	function paggingNumber<?php echo $randonNumber; ?>(pageNum){
		 scriptJquery ('.overlay_<?php echo $randonNumber ?>').css('display','block');
			(scriptJquery.ajax({
        dataType: 'html',
				method: 'post',
				'url': en4.core.baseUrl + 'widget/index/mod/sesvideo/name/chanel-photos/',
				'data': {
					format: 'html',
					page: pageNum,    
					params :'<?php echo json_encode($this->params); ?>', 
					is_ajax : 1,
					identity : '<?php echo $randonNumber; ?>',
					identityObject : '<?php echo $this->identityObject; ?>',
				},
				success: function(responseHTML) {
					scriptJquery ('.overlay_<?php echo $randonNumber ?>').css('display','none');
					document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML =  responseHTML;
					if("<?php echo $this->view_type ; ?>" == "masonry"){
							scriptJquery("#tabbed-widget_<?php echo $randonNumber; ?>").flexImages({rowHeight: <?php echo str_replace('px','',$this->height); ?>});
					}
				}
			}));
			return false;
	}
	viewMoreHide_<?php echo $randonNumber; ?>();
  function viewMoreHide_<?php echo $randonNumber; ?>() {
    if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
  }
  function viewMore_<?php echo $randonNumber; ?> (){
    document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = 'none';
    document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = '';    
    (scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + 'widget/index/mod/sesvideo/name/chanel-photos/',
      'data': {
        format: 'html',
        page: <?php echo $this->page + 1; ?>,    
				params :'<?php echo json_encode($this->params); ?>', 
				is_ajax : 1,
				identity : '<?php echo $randonNumber; ?>',
				identityObject : '<?php echo $this->identityObject; ?>',
      },
      success: function(responseHTML) {
        scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
				document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
				if("<?php echo $this->view_type ; ?>" == "masonry"){
							scriptJquery("#tabbed-widget_<?php echo $randonNumber; ?>").flexImages({rowHeight: <?php echo str_replace('px','',$this->height); ?>});
				}
      }
    }));
    return false;
  }
</script>
<?php if(!$this->is_ajax){ ?>
<style type="text/css">
.sesalbum_list_grid.sesalbum_profile_list_width_<?php echo $randonNumber; ?> {
	margin:0 3px 6px;
	width: <?php echo is_numeric($this->width) ? $this->width.'px' : $this->width ?> !important;
}
.sesalbum_profile_list_height_<?php echo $randonNumber; ?> {
	height:<?php echo is_numeric($this->height) ? $this->height.'px' : $this->height ?>;
}
</style>
<script type="application/javascript">
// PHOTO LIKE ON PHOT0 LISTINGS
scriptJquery(document).on('click','.sesalbum_chanelphotolike',function(){
		var data = scriptJquery(this).attr('data-src');
		var objectDocument = this;
		 (scriptJquery.ajax({
      dataType: 'html',
			url : en4.core.baseUrl + 'sesvideo/chanel/like/photo_id/'+data,
			data : {
				format : 'json',
				type : 'photo',
				id : data,
			},
		 success: function(responseHTML) {
			 var data = responseHTML;
			 if(data.status == 'false'){
					 if(data.error == 'Login')
							alert(en4.core.language.translate('Please login'));
					 else
							alert(en4.core.language.translate('Invalid argument supplied.'));
			 }else{
					 if(data.condition == 'increment'){
              scriptJquery(objectDocument).addClass('button_active');
							scriptJquery(objectDocument).find('span').html(data.like_count);
							showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate("Photo like successfully")+'</span>', 'sesbasic_liked_notification');
					 }else{
              scriptJquery(objectDocument).removeClass('button_active');
							scriptJquery(objectDocument).find('span').html(data.like_count);
							showTooltip(10,10,'<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate("Photo removed from like successfully")+'</span>');
					 }
					 
				}
			var ObjectIncrem = scriptJquery(objectDocument).parent().parent().find('.sesalbum_list_grid_info').find('.sesalbum_list_grid_stats');
			var ObjectLength = scriptJquery(ObjectIncrem).children();
			if(ObjectLength.length >0){
					for(i=0;i<ObjectLength.length;i++){
						if(ObjectLength[i].hasClass('sesalbum_list_grid_likes')){
							var title = scriptJquery(ObjectLength[i]).attr('title').replace(/[^0-9]/g, '');	
							scriptJquery(ObjectLength[i]).attr('title',scriptJquery(ObjectLength[i]).attr('title').replace(title,data.like_count));
							var innerContent = scriptJquery(ObjectLength[i]).html().replace(/[^0-9]/g, '');
							scriptJquery(ObjectLength[i]).html(scriptJquery(ObjectLength[i]).html().replace(innerContent,data.like_count));
						}	
					}
			}
		}
		}));
		return false;
});
</script>
<?php } ?>
