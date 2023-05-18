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
 ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesalbum/externals/styles/styles.css'); ?>
<?php
if(!$this->is_ajax){
	$imageURL = $this->photo->getPhotoUrl();
	if(strpos($this->photo->getPhotoUrl(),'http') === false)
          	$imageURL = (!empty($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"] == 'on')) ? "https://" : "http://". $_SERVER['HTTP_HOST'].$this->photo->getPhotoUrl();
  $this->doctype('XHTML1_RDFA');
  $this->headMeta()->setProperty('og:title', $this->photo->getTitle());
  $this->headMeta()->setProperty('og:description', $this->photo->getDescription());
  $this->headMeta()->setProperty('og:image',$imageURL);
  $this->headMeta()->setProperty('twitter:title', $this->photo->getTitle());
  $this->headMeta()->setProperty('twitter:description', $this->photo->getDescription());
}
?>
<?php
  $this->headTranslate(array(
    'Save', 'Cancel', 'delete',
  ));
?>
<?php $baseUrl = $this->layout()->staticBaseUrl; ?>
<div class='sesalbum_view_photo sesbasic_bxs sesbasic_clearfix'>
  <div class='sesalbum_view_photo_container_wrapper sesbasic_clearfix'>
      <div class="sesalbum_view_photo_nav_btns">
        <?php
        $photoPreviousData = $this->previousPhoto;
        echo $this->htmlLink($photoPreviousData->getHref(), '<i class="fa fa-angle-left"></i>', array('id' => 'photo_prev','data-url'=>$photoPreviousData->chanelphoto_id, 'class' => 'sesalbum_view_photo_nav_prev_btn'));
        $photoNextData = $this->nextPhoto;
         ?>
        <?php echo $this->htmlLink($photoNextData->getHref(), '<i class="fa fa-angle-right"></i>', array('id' => 'photo_next','data-url'=>$photoNextData->chanelphoto_id, 'class' => 'sesalbum_view_photo_nav_nxt_btn')) ?>
      </div>
    <div class='sesalbum_view_photo_container' id='media_photo_div'>
      <?php  
         $imageViewerURL = Engine_Api::_()->sesvideo()->getImageViewerHref($this->photo);
         if($imageViewerURL != ''){  ?>
        <a href="<?php echo $this->photo->getHref(); ?>" title="Open image in image viewer" onclick="getRequestedAlbumPhotoForImageViewer('<?php echo $this->photo->getPhotoUrl(); ?>','<?php echo $imageViewerURL; ?>');return false;" class="sesalbum_view_photo_expend"><i class="fa fa-expand"></i></a>
      <?php } ?>
      <div id="media_photo_next">
      	<a id="photo_main_next" href="javascript:;">
        <?php echo $this->htmlImage($this->photo->getPhotoUrl(), $this->photo->getTitle(), array(
          'id' => 'media_photo',
          'onload'=>'doResizeForButton()'
        )); ?>
        </a>
      </div>
    </div>
    <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != ''){ 
    	$urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $this->photo->getHref());
    ?>
      <div class="sesbasic_clearfix sesalbum_photo_view_btns">
        
        <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->photo, 'param' => 'photoviewpage')); ?>

        
        <a title="<?php echo $this->translate('Share'); ?>" href="<?php echo $this->url(array("action" => "share", "type" => "sesvideo_chanelphoto", "photo_id" => $this->photo->getIdentity(),"format" => "smoothbox"), 'sesalbum_general', true); ?>" class="sesalbum_photo_view_share_button smoothbox"><i class="fa fa-share"></i></a>
        <a title="<?php echo $this->translate('Download'); ?>" href="<?php echo $this->url(array('module' => 'sesvideo', 'action' => 'download', 'photo_id' => $this->photo->chanelphoto_id,'file_id'=>$this->photo->file_id), 'sesvideo_chanel', true); ?>" class="sesalbum_photo_view_download_button"><i class="fa fa-download"></i></a>
       <?php if($this->canComment){ ?>
     	 <?php $LikeStatus = Engine_Api::_()->sesalbum()->getLikeStatusPhoto($this->photo->chanelphoto_id,'chanelphoto'); ?>
        <a href="javascript:void(0);" id="sesLikeUnlikeButton" class="sesalbum_view_like_button <?php echo $LikeStatus === true ? 'button_active' : '' ;  ?>"><i class="fa fa-thumbs-up"></i></a>
        <?php } ?>
      <?php if($this->canComment){ ?>
        <a title="<?php echo $this->translate('Comment'); ?>" href="javascript:void(0);" id="sescomment_button" class="sesalbum_view_comment_button"><i class="fa fa-comment"></i></a>
      <?php } ?>
        <span class="sesalbum_photo_view_option_btn">
          <a title="<?php echo $this->translate('Options'); ?>" href="javascript:;" id="parent_container_option"><i id="fa-ellipsis-v" class="fa fa-ellipsis-v"></i></a>
        </span>  
      </div>
    <?php } ?>
  </div>
  <div class="sesalbum_view_photo_count">
		<?php echo $this->translate('Photo %1$s of %2$s', $this->locale()->toNumber($this->photo->getPhotoIndex() + 1), $this->chanel->engine_count()) ?>
  </div>
	<div class="sesalbum_photo_view_bottom_right">
    <?php if(isset($this->status_slideshowPhoto)){ ?>
      <!-- Corresponding photo as per album id -->
      <div class="layout_sesalbum_photo_strip">
        <div class="sesalbum_photos_strip_slider sesbasic_clearfix clear">
          <a id="prevSlide" class="sesalbum_photos_strip_slider_btn btn-prev"><i class="fa fa-angle-left"></i></a>
          <div class="sesalbum_photos_strip_slider_content">
            <div id="sesalbum_corresponding_photo" style="width:257px;">
            <?php if(!$this->is_ajax){ ?>
              <img id="sesalbum_corresponding_photo_image" src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sesbasic/externals/images/loading.gif" alt="" style="margin-top:23px;" />
             <?php } ?>
            </div>
          </div>
          <a id="nextSlide" class="sesalbum_photos_strip_slider_btn btn-nxt"><i class="fa fa-angle-right"></i></a>
        </div>
      </div>
    <?php } ?>
    <?php if(isset($this->paginator_like) && isset($this->status_like) && $this->paginator_like->getTotalItemCount() >0){ ?>
      <!--People  Like photo code -->
      <div class="layout_sesalbum_people_like_photo">
        <h3><?php echo $this->translate("People Who Like This");?></h3>
        <ul id="like-status-<?php echo $this->identity; ?>" class="sesalbum_user_listing sesbasic_clearfix clear">
          <?php foreach( $this->paginator_like as $item ): ?>
            <li>
              <?php $user = Engine_Api::_()->getItem('user',$item->poster_id); ?>
              <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon'),array('title'=>$user->getTitle())); ?>
            </li>
          <?php endforeach; ?>
          <?php if($this->paginator_like->getTotalItemCount() > $this->data_show_like){ ?>
            <li>
              <a href="javascript:;" onclick="getLikeData('<?php echo $this->chanelphoto_id; ?>')" class="sesalbum_user_listing_more">
                <?php echo '+';echo $this->paginator_like->getTotalItemCount() - $this->data_show_like ; ?>
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>
    <?php } ?>
  </div>
	<div class="sesalbum_photo_view_bottom_middle sesbasic_clearfix">
    <?php if( $this->photo->getTitle() ): ?>
      <div class="sesalbum_view_photo_title">
        <?php echo $this->photo->getTitle(); ?>
      </div>
    <?php endif; ?>
    <div class="sesalbum_view_photo_middle_box clear sesbasic_clearfix">
      <div class="sesalbum_view_photo_owner_info sesbasic_clearfix">
        <div class="sesalbum_view_photo_owner_photo">
          <?php $albumOwnerDetails = Engine_Api::_()->user()->getUser($this->chanel->owner_id); ?>
          <?php echo $this->htmlLink($albumOwnerDetails->getHref(), $this->itemPhoto($albumOwnerDetails, 'thumb.icon')); ?>  
        </div>
        <div class="sesalbum_view_photo_owner_details">
          <span class="sesalbum_view_photo_owner_name sesbasic_text_light">
            <?php echo $this->translate("by"); ?><?php echo $this->htmlLink($albumOwnerDetails->getHref(), $albumOwnerDetails->getTitle()); ?>
          </span>
          <span class="sesbasic_text_light sesalbum_view_photo_date">
            <?php echo $this->translate('in %1$s', $this->htmlLink( $this->chanel->getHref(), $this->chanel->getTitle())); ?>
            on <?php echo date('F j',strtotime($this->photo->creation_date)); ?>
          </span>
        </div>
    	</div>
    </div>
    <div class="sesalbum_view_photo_info_left">
      <?php if( $this->photo->getDescription() ): ?>
        <div class="sesalbum_view_photo_des">
          <b><?php echo $this->translate("Description"); ?></b>
          <?php echo nl2br($this->photo->getDescription()) ?>
        </div>
      <?php endif; ?>
      <?php if(!is_null($this->photo->location) && $this->photo->location != '' && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo_enable_location', 1)){ ?>
        <div class="sesalbum_view_photo_location"><i class="fas fa-map-marker-alt sesbasic_text_light"></i>
        <?php echo $this->htmlLink(Array("module"=> "sesalbum", "controller" => "photo", "action" => "location", "route" => "sesalbum_extended", "type" => "location","chanel_id" =>$this->photo->chanel_id, "photo_id" => $this->photo->getIdentity()), $this->photo->location, array("class" => "smoothboxOpen")); ?>
        </div>
      <?php } ?>
    </div>
    <!-- comment code-->
    <div class="sesalbum_photo_view_bottom_comments layout_core_comments">
      <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')){ ?>
      <?php echo $this->action("list", "comment", "sesadvancedcomment", array("type" => "chanelphoto", "id" => $this->photo->getIdentity(),'is_ajax_load'=>true));
        }else{echo $this->action("list", "comment", "core", array("type" => "chanelphoto", "id" => $this->photo->getIdentity())); } ?>
    </div>
  </div>
</div>
<script type="text/javascript">
var maxHeight = <?php echo $this->maxHeight; ?>;
function doResizeForButton(){
  if(scriptJquery(".sesalbum_photo_view_option_btn").length == 0) return;
	<?php if(Engine_Api::_()->user()->getViewer()->getIdentity() == '0'){ ?>
			return false;
	<?php } ?>
	var topPositionOfParentDiv =  scriptJquery(".sesalbum_photo_view_option_btn").offset().top + 35;
	topPositionOfParentDiv = topPositionOfParentDiv+'px';
	var leftPositionOfParentDiv =  scriptJquery(".sesalbum_photo_view_option_btn").offset().left - 115;
	leftPositionOfParentDiv = leftPositionOfParentDiv+'px';
	scriptJquery('.sesalbum_option_box').css('top',topPositionOfParentDiv);
	scriptJquery('.sesalbum_option_box').css('left',leftPositionOfParentDiv);
}
 var width = scriptJquery('.sesalbum_view_photo_container_wrapper').width();
  scriptJquery('#media_photo').css('max-width',width+'px');
	scriptJquery('#media_photo').css('max-height',maxHeight+'px');
<?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != ''){ ?>
scriptJquery( window ).load(function() {
	doResizeForButton();
});
var optionDataForButton;
optionDataForButton = '<div class="sesalbum_option_box"><?php if ($this->viewer()->getIdentity()):?><?php if( $this->canEdit ): ?><?php echo $this->htmlLink(Array("module"=> "sesvideo", "controller" => "chanel", "action" => "location", "route" => "sesvideo_chanel","chanel_id" =>$this->photo->chanel_id, "photo_id" => $this->photo->getIdentity(),"type"=>"photo"), $this->translate("Edit Location"), array("class" => "smoothboxOpen sesvideo_icon_map")); ?><?php echo $this->htmlLink(Array("module"=> "sesvideo", "controller" => "chanel", "action" => "edit-photo", "route" => "sesvideo_chanel","chanel_id" =>$this->photo->chanel_id, "photo_id" => $this->photo->getIdentity()), $this->translate("Edit"), array("class" => "smoothboxOpen sesbasic_icon_edit")) ?><?php endif; ?><?php if( $this->canDelete ): ?><?php echo $this->htmlLink(Array("module"=> "sesvideo", "controller" => "chanel", "action" => "delete-photo", "route" => "sesvideo_chanel","chanel_id" =>$this->photo->chanel_id, "photo_id" => $this->photo->getIdentity()), $this->translate("Delete"), array("class" => "smoothboxOpen sesbasic_icon_delete")) ?><?php endif; ?><?php if( !$this->message_view ):?>  <?php echo $this->htmlLink($this->url(array("action" => "share", "type" => "chanelphoto", "photo_id" => $this->photo->getIdentity(),"format" => "smoothbox"), "sesalbum_general"	, true), $this->translate("Share"), array("class" => "smoothboxOpen sesbasic_icon_share")); ?><?php echo $this->htmlLink(Array("module"=> "core", "controller" => "report", "action" => "create", "route" => "default", "subject" => $this->photo->getGuid()), $this->translate("Report"), array("class" => "smoothboxOpen sesbasic_icon_report")); ?><?php endif; ?><?php endif; ?></div>';
scriptJquery(optionDataForButton).appendTo('body');
<?php if(!$this->is_ajax){ ?>
	scriptJquery(document).click(function(event){
		if(event.target.id == 'parent_container_option' || event.target.id == 'fa-ellipsis-v'){
			if(scriptJquery('#parent_container_option').hasClass('active')){
				scriptJquery('#parent_container_option').removeClass('active');
				scriptJquery('.sesalbum_option_box').hide();	
			}else{
				scriptJquery('#parent_container_option').addClass('active');
				scriptJquery('.sesalbum_option_box').show();	
		  }
		}else{
			scriptJquery('#parent_container_option').removeClass('active');
			scriptJquery('.sesalbum_option_box').hide();	
		}
	});
	// on window resize work
	scriptJquery(window).resize(function(){
			doResizeForButton();
	});
<?php } ?>
  //Set Width On Image
<?php } ?>
<?php if(!$this->is_ajax){ ?>
	/*change next previous button click event*/
		scriptJquery(document).on('click','#photo_prev',function(){
			changeNextPrevious(this);	
			return false;
		});
		scriptJquery(document).on('click','#photo_next',function(){
			changeNextPrevious(this);	
			return false;
		});
	 function changeNextPrevious(thisObject){
			history.pushState(null, null, scriptJquery(thisObject).attr('href'));
			var height = scriptJquery('#media_photo_div').height();
			var width = scriptJquery('#media_photo_div').width();
			scriptJquery('#media_photo_div').html('<div class="clear sesbasic_loading_container"></div>');
			scriptJquery('.sesbasic_loading_container').css('height',height) ;
			scriptJquery('.sesbasic_loading_container').css('width',width) ;
			var correspondingImageData = scriptJquery('#sesalbum_corresponding_photo').html();
			var photo_id = scriptJquery(thisObject).attr('data-url');
			(scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url':en4.core.baseUrl + 'widget/index/mod/sesvideo/name/chanel-photo-view-page/',
      'data': {
        format: 'html',
				 photo_id : photo_id,
				params :'<?php echo json_encode($this->params); ?>', 
				is_ajax : 1,
				maxHeight:maxHeight
      },
      success: function(responseHTML) {
				if(scriptJquery('.sesalbum_option_box').length >0)
					scriptJquery('.sesalbum_option_box').remove();
				<?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != ''){ ?>
					scriptJquery(optionDataForButton).appendTo('body');
				<?php } ?>
					scriptJquery('.layout_sesvideo_chanel_photo_view_page').html(responseHTML);
					var width = scriptJquery('.sesalbum_view_photo_container_wrapper').width();
					scriptJquery('#media_photo').css('max-width',width+'px');
					scriptJquery('#media_photo').css('max-height',maxHeight+'px');
					scriptJquery('#sesalbum_corresponding_photo').html(correspondingImageData);
					scriptJquery('#sesalbum_corresponding_photo > a').each(function(index){
					scriptJquery(this).removeClass('slideuptovisible');
					if(scriptJquery(this).attr('data-url') == photo_id)
						scriptJquery(this).addClass('active');
					else
						scriptJquery(this).removeClass('active');
						countSlide++;
					});
					scriptJquery('#sesalbum_corresponding_photo > a').eq(3).addClass('slideuptovisible');
					scriptJquery('#sesalbum_corresponding_photo').css('width',(countSlide*64)+'px');
      }
    }));
    return false;
	}
<?php } ?>
 <?php if(isset($this->status_slideshowPhoto) && !$this->is_ajax){ ?>
scriptJquery(document).on('click','.sesalbum_corresponding_image_album',function(e){
		e.preventDefault();
		if(!scriptJquery(this).hasClass('active'))
			changeNextPrevious(this);
});
var countSlide = 0;
function getCorrespondingImg(){
	(scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url':en4.core.baseUrl + 'sesvideo/index/corresponding-image/chanel_id/<?php echo $this->chanel->chanel_id; ?>',
      'data': {
        format: 'html',
				is_ajax : 1,
      },
      success: function(responseHTML) {
				if(responseHTML){
					scriptJquery('#sesalbum_corresponding_photo').html(responseHTML);
					scriptJquery('#sesalbum_corresponding_photo > a').each(function(index){
						scriptJquery(this).removeClass('slideuptovisible');
						if(scriptJquery(this).attr('data-url') == "<?php echo $this->photo->chanelphoto_id; ?>"){
							scriptJquery(this).addClass('active');	
						}
						countSlide++;	
					});
					scriptJquery('#sesalbum_corresponding_photo > a').eq(3).addClass('slideuptovisible');
					scriptJquery('#sesalbum_corresponding_photo').css('width',(countSlide*64)+'px');
				}
      }
    }));	
}
<?php } ?>
<?php if(!$this->is_ajax && isset($this->status_slideshowPhoto)){ ?>
scriptJquery(document).on('mouseover','#prevSlide',function(e){
	var indexCurrent = 	scriptJquery('#sesalbum_corresponding_photo > a.slideuptovisible').index();
	if(indexCurrent<4 || indexCurrent == '-1')
		scriptJquery('#prevSlide').css('cursor','not-allowed');
	else
		scriptJquery('#prevSlide').css('cursor','pointer');
});
scriptJquery(document).on('mouseover','#nextSlide',function(e){
	var indexCurrent = 	scriptJquery('#sesalbum_corresponding_photo > a.slideuptovisible').index();
	if(indexCurrent == (countSlide-1) || indexCurrent == '-1')
		scriptJquery('#nextSlide').css('cursor','not-allowed');
	else
		scriptJquery('#nextSlide').css('cursor','pointer');
});
scriptJquery(document).on('click','#nextSlide',function(){
	var indexCurrent = 	scriptJquery('#sesalbum_corresponding_photo > a.slideuptovisible').index();
	if((countSlide-1) == indexCurrent || indexCurrent == '-1'){
		// last slide is visible
	}else{
		var slideLeft = (countSlide-1) - indexCurrent;
		var leftAttr = scriptJquery('#sesalbum_corresponding_photo').css('left').replace('px','');
		leftAttr = leftAttr.replace('-','');		
		if(slideLeft>3){
			leftAttr = parseInt(leftAttr,10);
			scriptJquery('#sesalbum_corresponding_photo').css('left','-'+(leftAttr+(64*4))+'px');
			scriptJquery('#sesalbum_corresponding_photo > a').eq(indexCurrent).removeClass('slideuptovisible');
			scriptJquery('#sesalbum_corresponding_photo > a').eq((indexCurrent+4)).addClass('slideuptovisible');
		}else{
			leftAttr = parseInt(64*slideLeft,10)+parseInt(leftAttr,10);
			scriptJquery('#sesalbum_corresponding_photo').css('left','-'+leftAttr+'px');
			scriptJquery('#sesalbum_corresponding_photo > a').eq(indexCurrent).removeClass('slideuptovisible');
			scriptJquery('#sesalbum_corresponding_photo > a').eq((indexCurrent+slideLeft)).addClass('slideuptovisible');
		}
	}
});
scriptJquery(document).on('click','#prevSlide',function(){
	var indexCurrent = 	scriptJquery('#sesalbum_corresponding_photo > a.slideuptovisible').index();
	var leftAttr = scriptJquery('#sesalbum_corresponding_photo').css('left').replace('px','');
	leftAttr = leftAttr.replace('-','');
	leftAttr = parseInt(leftAttr,10);
 if(leftAttr == 0 || countSlide < 4 || indexCurrent == '-1'){
	 //first slide
 }else{
	var type = indexCurrent - 3;
	if(typeof type == 'number' && type > 3 ){
		scriptJquery('#sesalbum_corresponding_photo').css('left','-'+(leftAttr-(64*4))+'px');
		scriptJquery('#sesalbum_corresponding_photo > a').eq(indexCurrent).removeClass('slideuptovisible');
		scriptJquery('#sesalbum_corresponding_photo > a').eq((indexCurrent-4)).addClass('slideuptovisible');
	}else{
		var slideLeft = (countSlide-1)-((countSlide-1) - indexCurrent)
		leftAttr = parseInt(leftAttr,10) -  parseInt(64*type,10);
		if(countSlide-1 > 3 || countSlide-1 == 3 || indexCurrent-type < 4)
			var selectedindex = 3;
		else
			var selectedindex = indexCurrent-type;
		scriptJquery('#sesalbum_corresponding_photo').css('left',leftAttr+'px');
		scriptJquery('#sesalbum_corresponding_photo > a').eq(indexCurrent).removeClass('slideuptovisible');
		scriptJquery('#sesalbum_corresponding_photo > a').eq(selectedindex).addClass('slideuptovisible');
	}
 }
	return false;
});
<?php } ?>
 <?php if(isset($this->status_slideshowPhoto) && !$this->is_ajax){ ?>
scriptJquery(document).ready(function(){
	getCorrespondingImg();	
});
<?php } ?>
function getTagData(value){
	if(value){
		url = en4.core.staticBaseUrl+'albums/index/tag-photo/photo_id/'+value;
		openURLinSmoothBox(url);	
		return;
	}
}
<?php if($this->viewer->getIdentity() !=0){ ?>
	scriptJquery(document).on('keyup', function (e) {
		if(scriptJquery('#'+e.target.id).prop('tagName') == 'INPUT' || scriptJquery('#'+e.target.id).prop('tagName') == 'TEXTAREA')
				return true;
		if(scriptJquery('#ses_media_lightbox_container_video').css('display') == 'none'){
			// like code
			if (e.keyCode === 76) {
				if(scriptJquery('#sesLikeUnlikeButton').length > 0)
				 scriptJquery('#sesLikeUnlikeButton').trigger('click');
			}
			// favourite code
			if (e.keyCode === 70) {
				if(scriptJquery('.sesalbum_photoFav').length > 0)
					scriptJquery('.sesalbum_photoFav').trigger('click');
			}
			// open photo in lightbox code
			if(e.keyCode === 77){
				if(scriptJquery('.sesalbum_view_photo_expend').length>0)	
					scriptJquery('.sesalbum_view_photo_expend').trigger('click');
			}
		}
	});
<?php } ?>
function getLikeData(value){
	if(value){
		url = en4.core.staticBaseUrl+'albums/index/like-photo/photo_id/'+value;
		openURLinSmoothBox(url);	
		return;
	}
}
</script>
