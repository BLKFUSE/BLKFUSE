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
<?php if(Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($this->photo)) { ?>
	<div id="album_content" class="paid_content">
		<?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $this->photo)); ?>
		<div class="epaidcontent_lock_img"><img src="application/modules/Epaidcontent/externals/images/paidcontent.png" alt="" /></div>
	</div>
<?php } else { ?>
<?php 
  $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl') .'application/modules/Sesbasic/externals/scripts/tagger.js');
  $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl') .'application/modules/Sesalbum/externals/scripts/core.js');
  $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl') . 'application/modules/Sesbasic/externals/scripts/flexcroll.js');
  $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesalbum/externals/styles/styles.css');
?>
<?php
if(!$this->is_ajax && isset($this->docActive)) {

	$imageURL = $this->photo->getPhotoUrl();
	
	if(strpos($this->photo->getPhotoUrl(),'http') === false)
    $imageURL = (!empty($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"] == 'on')) ? "https://". $_SERVER['HTTP_HOST'].$this->photo->getPhotoUrl() : "http://". $_SERVER['HTTP_HOST'].$this->photo->getPhotoUrl();
}
?>
<?php
  $this->headTranslate(array(
    'Save', 'Cancel', 'delete',
  ));
?>
<?php if(!$this->is_ajax){ ?>
 <div id="photo_content"  class="<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesalbum',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($this->album)) { ?> paid_content <?php } ?>" <?php if(!empty($this->locked)) { ?> style="display:none" <?php } ?>>
 
 <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesalbum',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($this->album)) { ?>
      <?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $this->album)); ?>
    <?php } ?>

 <?php } ?>
<?php $baseUrl = $this->layout()->staticBaseUrl; ?>
<?php if(($this->getAllowRating == 0 && $this->allowShowRating == 1 && $this->total_rating_average != 0 ) || ($this->getAllowRating == 1) ){ ?>
<script type="text/javascript">
  en4.core.runonce.add(function() {
    var pre_rate = "<?php echo $this->total_rating_average == '' ? '0' : $this->total_rating_average  ;?>";
		<?php if($this->viewer_id == 0){ ?>
			rated = 0;
		<?php }else if($this->allowShowRating == 1 && $this->allowRating == 0){?>
		var rated = 3;
		<?php }else if($this->allowRateAgain == 0 && $this->rated){ ?>
		var rated = 1;
		<?php }else if($this->canRate == 0 && $this->viewer_id != 0){?>
		var rated = 4;
		<?php }else if(!$this->allowMine){?>
		var rated = 2;
		<?php }else{ ?>
    var rated = '90';
		<?php } ?>
    var resource_id = <?php echo $this->photo->photo_id;?>;
    var total_votes = <?php echo $this->rating_count;?>;
    var viewer = <?php echo $this->viewer_id;?>;
    new_text = '';

    var rating_over = window.rating_over = function(rating) {
      if( rated == 1 ) {
        document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('you have already rated');?>";
				return;
        //set_rating();
      }
			<?php if(!$this->canRate){ ?>
				else if(rated == 4){
						 document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('rating is not allowed for your member level');?>";
						 return;
				}
			<?php } ?>
			<?php if(!$this->allowMine){ ?>
				else if(rated == 2){
						 document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('rating on own photo not allowed');?>";
						 return;
				}
			<?php } ?>
			<?php if($this->allowShowRating == 1){ ?>
				else if(rated == 3){
						 document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('rating is disabled');?>";
						 return;
				}
			<?php } ?>
			else if( viewer == 0 ) {
        document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('please login to rate');?>";
				return;
      } else {
        document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('click to rate');?>";
        for(var x=1; x<=5; x++) {
          if(x <= rating) {
            scriptJquery('#rate_'+x).addClass('fas fa-star rating_star_big_generic rating_star_big').removeClass('rating_star_big_disabled');
          } else {
            scriptJquery('#rate_'+x).addClass('fas fa-star rating_star_big_generic rating_star_big_disabled');
          }
        }
      }
    }
    
    var rating_out = window.rating_out = function() {
      if (new_text != ''){
        document.getElementById('rating_text').innerHTML = new_text;
      }
      else{
        document.getElementById('rating_text').innerHTML = " <?php echo $this->translate(array('%s rating', '%s ratings', $this->rating_count),$this->locale()->toNumber($this->rating_count)) ?>";        
      }
      if (pre_rate != 0){
        set_rating();
      }
      else {
        for(var x=1; x<=5; x++) {
          scriptJquery('#rate_'+x).addClass('fas fa-star rating_star_big_generic rating_star_big_disabled');
        }
      }
    }

    var set_rating = window.set_rating = function() {
      var rating = pre_rate;
      if (new_text != ''){
        document.getElementById('rating_text').innerHTML = new_text;
      }
      else{
        document.getElementById('rating_text').innerHTML = "<?php echo $this->translate(array('%s rating', '%s ratings', $this->rating_count),$this->locale()->toNumber($this->rating_count)) ?>";
      }
      for(var x=1; x<=parseInt(rating); x++) {
        scriptJquery('#rate_'+x).addClass('fas fa-star rating_star_big_generic rating_star_big').removeClass('rating_star_big_disabled');;
      }

      for(var x=parseInt(rating)+1; x<=5; x++) {
        scriptJquery('#rate_'+x).addClass('fas fa-star rating_star_big_generic rating_star_big_disabled');
      }

      var remainder = Math.round(rating)-rating;
      if (remainder <= 0.5 && remainder !=0){
        var last = parseInt(rating)+1;
        scriptJquery('#rate_'+last).addClass('fas fa-star rating_star_big_generic rating_star_big_half');
      }
    }

    var rate = window.rate = function(rating) {
      document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('Thanks for rating!');?>";
			<?php if($this->allowRateAgain == 0 && !$this->rated){ ?>
						 for(var x=1; x<=5; x++) {
								$('rate_'+x).set('onclick', '');
							}
					<?php } ?>
     
      (scriptJquery.ajax({
        dataType: 'json',
        'format': 'json',
        'url' : '<?php echo $this->url(array('module' => 'sesalbum', 'controller' => 'index', 'action' => 'rate'), 'default', true) ?>',
        'data' : {
          'format' : 'json',
          'rating' : rating,
          'resource_id': resource_id,
					'resource_type':'<?php echo $this->rating_type; ?>'
        },
        success : function(responseJSON, responseText)
        {
					<?php if($this->allowRateAgain == 0 && !$this->rated){ ?>
							rated = 1;
					<?php } ?>
					showTooltip(10,10,'<i class="fa fa-star"></i><span>'+("Photo Rated successfully")+'</span>', 'sesbasic_rated_notification');
					var total = responseJSON[0].total;
					var totalTxt = responseJSON[0].totalTxt;
					var rating_sum = responseJSON[0].rating_sum;
          pre_rate = rating_sum / total;
          set_rating();
          document.getElementById('rating_text').innerHTML = total+' '+totalTxt;
          new_text = total+' '+totalTxt;
        }
      }));
    }
    set_rating();
  });
					
</script>
<?php } ?>
<script type="text/javascript">
var maxHeight = <?php echo $this->maxHeight; ?>;
function doResizeForButton(){
  if(scriptJquery(".sesalbum_photo_view_option_btn").length == 0) return;
	<?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0){ ?>	
	var topPositionOfParentDiv =  scriptJquery(".sesalbum_photo_view_option_btn").offset().top + 35;
	topPositionOfParentDiv = topPositionOfParentDiv+'px';
	var leftPositionOfParentDiv =  scriptJquery(".sesalbum_photo_view_option_btn").offset().left - 115;
	leftPositionOfParentDiv = leftPositionOfParentDiv+'px';
	scriptJquery('.sesalbum_option_box').css('top',topPositionOfParentDiv);
	scriptJquery('.sesalbum_option_box').css('left',leftPositionOfParentDiv);
	<?php } ?>
	 var width = scriptJquery('.sesalbum_view_photo_container').width();
  scriptJquery('#media_photo').css('max-width',width+'px');
	scriptJquery('#media_photo').css('max-height',maxHeight+'px');
}

<?php //if(Engine_Api::_()->user()->getViewer()->getIdentity() != ''){ ?>
scriptJquery( window ).load(function() {
	doResizeForButton();
});
  en4.core.runonce.add(function() {
    var descEls = scriptJquery('.sesalbum_view_photo_des');
    if( descEls.length > 0 ) {
      descEls[0].enableLinks();
    }
    var taggerInstance = window.taggerInstance = new Tagger('#media_photo_next', {
      'title' : '<?php echo $this->string()->escapeJavascript($this->translate('Add Tag'));?>',
      'description' : '<?php echo $this->string()->escapeJavascript($this->translate('Type a tag or select a name from the list.'));?>',
      'createRequestOptions' : {
        'url' : '<?php echo $this->url(array('module' => 'sesalbum', 'controller' => 'photo', 'action' => 'add'), 'default', true) ?>',
        'data' : {
          'subject' : '<?php echo $this->subject()->getGuid() ?>'
        }
      },
      'deleteRequestOptions' : {
        'url' : '<?php echo $this->url(array('module' => 'core', 'controller' => 'tag', 'action' => 'remove'), 'default', true) ?>',
        'data' : {
          'subject' : '<?php echo $this->subject()->getGuid() ?>'
        }
      },
      'cropOptions' : {
        'container' : scriptJquery('#media_photo_next')
      },
      'tagListElement' : '#media_tags',
      'existingTags' : <?php echo Zend_Json::encode($this->tags) ?>,
      'suggestProto' : 'request.json',
      'suggestParam' : "<?php echo $this->url(array('module' => 'user', 'controller' => 'friends', 'action' => 'suggest', 'includeSelf' => true), 'default', true) ?>",
      'guid' : <?php echo ( $this->viewer->getIdentity() ? "'".$this->viewer->getGuid()."'" : 'false' ) ?>,
      'enableCreate' : <?php echo ( $this->canTag ? 'true' : 'false') ?>,
      'enableDelete' : <?php echo ( $this->canUntagGlobal ? 'true' : 'false') ?>
    });
    // Remove the href attrib while tagging
//     var nextHref = $('media_photo_next').get('href');
//     taggerInstance.addEvents({
//       'onBegin' : function() {
// 				scriptJquery('.sesalbum_photo_view_btns').hide();
//         $('media_photo_next').erase('href');
//       },
//       'onEnd' : function() {
// 				scriptJquery('.sesalbum_photo_view_btns').show();
//         $('media_photo_next').set('href', nextHref);
//       }
//     });
    var keyupEvent = function(e) {
      if( e.target.get('tag') == 'html' ||
          e.target.get('tag') == 'body' ) {
        if( e.key == 'right' ) {
          scriptJquery('#photo_next').trigger('click', e);
          //window.location.href = "<?php echo ( $this->nextPhoto ? $this->nextPhoto->getHref() : 'window.location.href' ) ?>";
        } else if( e.key == 'left' ) {
          scriptJquery('#photo_prev').trigger('click', e);
          //window.location.href = "<?php echo ( $this->previousPhoto ? $this->previousPhoto->getHref() : 'window.location.href' ) ?>";
        }
      }
    }
    
    scriptJquery(document).on('keyup', keyupEvent);
    // Add shutdown handler
    en4.core.shutdown.add(function() {
      scriptJquery(document).off('keyup', keyupEvent);
    });
  });
</script>
<div class='sesalbum_view_photo_main sesbasic_bxs sesbasic_clearfix'>
  <section>
    <div class='sesalbum_view_photo_container sesbasic_clearfix'>
      <?php if( $this->album->count() > 1 ): ?>
        <div class="sesalbum_view_photo_nav_btns">
          <?php
          $photoPreviousData = Engine_Api::_()->sesalbum()->getPreviousPhoto($this->album->album_id ,$this->photo->order ) ?  Engine_Api::_()->sesalbum()->getPreviousPhoto($this->album->album_id,$this->photo->order) : null;
          echo $this->htmlLink((isset($photoPreviousData->album_id) ?  Engine_Api::_()->sesalbum()->getHrefPhoto($photoPreviousData->photo_id,$photoPreviousData->album_id) : null ), '<i class="fa fa-angle-left"></i>', array('id' => 'photo_prev','data-url'=>$photoPreviousData->photo_id, 'class' => 'sesalbum_view_photo_nav_prev_btn'));
          $photoNextData = Engine_Api::_()->sesalbum()->getNextPhoto($this->album->album_id  ,$this->photo->order ) ?  Engine_Api::_()->sesalbum()->getNextPhoto($this->album->album_id  ,$this->photo->order ) : null;
           ?>
          <?php echo $this->htmlLink(( isset($photoNextData->album_id) ?  Engine_Api::_()->sesalbum()->getHrefPhoto($photoNextData->photo_id,$photoNextData->album_id) : null ), '<i class="fa fa-angle-right"></i>', array('id' => 'photo_next','data-url'=>$photoNextData->photo_id, 'class' => 'sesalbum_view_photo_nav_nxt_btn')) ?>
        </div>
      <?php endif ?>
      <div class='sesalbum_view_photo' id='media_photo_div'>
        <?php 
          $imageViewerURL = Engine_Api::_()->sesalbum()->getImageViewerHref($this->photo);
          if($imageViewerURL != ''){
        ?>
          <a href="<?php echo Engine_Api::_()->sesalbum()->getHrefPhoto($this->photo->photo_id,$this->photo->album_id); ?>" title="Open image in image viewer" onclick="getRequestedAlbumPhotoForImageViewer('<?php echo $this->photo->getPhotoUrl(); ?>','<?php echo $imageViewerURL; ?>');return false;" class="sesalbum_view_photo_expend"><i class="fa fa-expand"></i></a>
        <?php } ?>
        <div id="media_photo_next">
          <a id="photo_main_next" href="javascript:;">
          <?php echo $this->htmlImage($this->photo->getPhotoUrl('','','string'), $this->photo->getTitle(), array(
            'id' => 'media_photo',
            'onload'=>'doResizeForButton()'
          )); ?>
          </a>
        </div>
      </div>
    </div>
    <?php if( $this->canEdit ): ?>
      <div class="sesalbum_view_photo_rotate_btns">          
        <a class="sesalbum_icon_photos_rotate_ccw" id="ses-rotate-90" href="javascript:void(0)" onclick="sesRotate('<?php echo $this->photo->getIdentity() ?>','90')">&nbsp;</a>
        <a class="sesalbum_icon_photos_rotate_cw" id="ses-rotate-270" href="javascript:void(0)" onclick="sesRotate('<?php echo $this->photo->getIdentity() ?>','270')">&nbsp;</a>
        <a class="sesalbum_icon_photos_flip_horizontal" id="ses-rotate-horizontal"  href="javascript:void(0)" onclick="sesRotate('<?php echo $this->photo->getIdentity() ?>','horizontal')">&nbsp;</a>
        <a class="sesalbum_icon_photos_flip_vertical" id="ses-rotate-vertical"  href="javascript:void(0)" onclick="sesRotate('<?php echo $this->photo->getIdentity() ?>','vertical')">&nbsp;</a>          
      </div>
    <?php endif ?>
    <?php
    if($this->canCommentMemberLevelPermission == 0){
      $canComment = false;
    }else{
      $canComment = true;
    } ?>
    <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != '') { 
    $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $this->photo->getHref());
    ?>
      <div class="sesbasic_clearfix sesalbum_photo_view_btns">
        <?php echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->photo)); ?>
        <a title="<?php echo $this->translate('Share'); ?>" href="<?php echo $this->url(array("action" => "share", "type" => "album_photo", "photo_id" => $this->photo->getIdentity(),"format" => "smoothbox"), 'sesalbum_general', true); ?>" class="sesalbum_photo_view_share_button smoothbox"><i class="fas fa-share-alt"></i></a>
        <?php if(Engine_Api::_()->user()->getViewer()->getIdentity()) { ?>
          <a  title="<?php echo $this->translate('Message'); ?>" href="<?php echo $this->url(array('module'=> 'sesalbum', 'controller' => 'index', 'action' => 'message','photo_id' => $this->photo->getIdentity(), 'format' => 'smoothbox'),'sesalbum_extended',true); ?>" class="sesalbum_photo_view_msg_button smoothbox"><i class="fa fa-envelope"></i></a>
        <?php } ?>
        <?php if(isset($this->canDownload) && $this->canDownload){ ?>
          <a title="<?php echo $this->translate('Download'); ?>" href="<?php echo $this->url(array('module' => 'sesalbum', 'action' => 'download', 'photo_id' => $this->photo->photo_id,'type'=>'photo'), 'sesalbum_general', true); ?>" class="sesalbum_photo_view_download_button"><i class="fa fa-download"></i></a>
        <?php } ?>
       <?php if($this->canComment){ ?>
       <?php $LikeStatus = Engine_Api::_()->sesalbum()->getLikeStatusPhoto($this->photo->photo_id); ?>
        <a href="javascript:void(0);" id="sesLikeUnlikeButtonSesalbum" data-id = "<?php echo $this->photo->photo_id; ?>" class="sesalbum_view_like_button <?php echo $LikeStatus === true ? 'button_active' : '' ;  ?>"><i class="fa fa-thumbs-up"></i></a>
        <?php }
      $canFavourite =  Engine_Api::_()->authorization()->isAllowed('album',Engine_Api::_()->user()->getViewer(), 'favourite_photo');
      ?>
        <?php if($canFavourite && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.allow.favouritephoto', 1)) { ?>
      <?php $albumFavStatus = Engine_Api::_()->getDbtable('favourites', 'sesalbum')->isFavourite(array('resource_type'=>'album_photo','resource_id'=>$this->photo->photo_id)); ?>
        <a href="javascript:;" data-src='<?php echo $this->photo->photo_id; ?>' class="sesalbum_view_fav_button sesalbum_photoFav <?php echo ($albumFavStatus)>0 ? 'button_active' : '' ; ?>"><i class="fa fa-heart"></i></a>
        <?php } ?>
      <?php if($canComment){ ?>
        <a title="<?php echo $this->translate('Comment'); ?>" href="javascript:void(0);" id="sescomment_button" class="sesalbum_view_comment_button"><i class="fa fa-comment"></i></a>
      <?php } ?>
        <?php if($this->canTag){ ?>
          <a title="<?php echo $this->translate('Tag'); ?>" href="javascript:void(0);" onclick="taggerInstance.begin();" class="sesalbum_view_tag_button"><i class="fa fa-tag"></i></a>
        <?php } ?>
        <span class="sesalbum_photo_view_option_btn">
          <a title="<?php echo $this->translate('Options'); ?>" href="javascript:;" id="parent_container_option"><i id="fa-ellipsis-v" class="fa fa-ellipsis-v"></i></a>
        </span>  
      </div>
    <?php } ?>
  </section>  
  <div class="sesalbum_view_photo_count">
    <?php echo $this->translate('Photo %1$s of %2$s',
        $this->locale()->toNumber($this->photo->getPhotoIndex() + 1),
        $this->locale()->toNumber($this->album->count())) ?>
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
              <a href="javascript:;" onclick="getLikeData('<?php echo $this->photo_id; ?>')" class="sesalbum_user_listing_more">
                <?php echo '+';echo $this->paginator_like->getTotalItemCount() - $this->data_show_like ; ?>
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>
    <?php } ?>
    <?php if(isset($this->paginator_favourite) && isset($this->status_favourite) && $this->paginator_favourite->getTotalItemCount() >0){ ?>
      <!--People  Like photo code -->
      <div class="layout_sesalbum_people_favourite_photo">
        <h3><?php echo $this->translate("People Who Added This As Favourite");?></h3>
        <ul id="like-status-<?php echo $this->identity; ?>" class="sesalbum_user_listing sesbasic_clearfix clear">
          <?php foreach( $this->paginator_favourite as $item ): ?>
            <li>
              <?php $user = Engine_Api::_()->getItem('user',$item->user_id); ?>
              <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon'),array('title'=>$user->getTitle())); ?>
            </li>
          <?php endforeach; ?>
          <?php if($this->paginator_favourite->getTotalItemCount() > $this->data_show_favourite){ ?>
            <li>
              <a href="javascript:;" onclick="getFavouriteData('<?php echo $this->photo_id; ?>')" class="sesalbum_user_listing_more">
                <?php echo '+';echo $this->paginator_favourite->getTotalItemCount() - $this->data_show_favourite ; ?>
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>
    <?php } ?>
    <?php if(isset($this->paginator_tagged) && isset($this->status_tagged) && $this->paginator_tagged->getTotalItemCount()>0){ ?>
      <!-- People tagged in photo code-->
      <div class="layout_sesalbum_people_tagged_photo">
        <h3><?php echo $this->translate("People Who Are Tagged In This Photo"); ?></h3>
        <ul class="sesalbum_user_listing sesbasic_clearfix clear">
          <?php foreach( $this->paginator_tagged as $item ): ?>
            <li>
              <?php $user = Engine_Api::_()->getItem('user',$item->tag_id); ?>
              <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon'),array('title'=>$user->getTitle())); ?>
            </li>
          <?php endforeach; ?>
          <?php if($this->paginator_tagged->getTotalItemCount() > $this->data_show_tagged){ ?>
          <li>
            <a href="javascript:;" onclick="getTagData('<?php echo $this->photo_id; ?>')" class="sesalbum_user_listing_more">
             <?php echo '+';echo $this->paginator_tagged->getTotalItemCount() - $this->data_show_tagged ; ?>
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
          <?php $albumOwnerDetails = Engine_Api::_()->user()->getUser($this->album->owner_id); ?>
          <?php echo $this->htmlLink($albumOwnerDetails->getHref(), $this->itemPhoto($albumOwnerDetails, 'thumb.icon')); ?>  
        </div>
        <div class="sesalbum_view_photo_owner_details">
          <span class="sesalbum_view_photo_owner_name sesbasic_text_light">
            by <?php echo $this->htmlLink($albumOwnerDetails->getHref(), $albumOwnerDetails->getTitle()); ?>
          </span>
          <span class="sesbasic_text_light sesalbum_view_photo_date">
            <?php echo $this->translate('in %1$s', $this->htmlLink( Engine_Api::_()->sesalbum()->getHref($this->album->getIdentity()), $this->album->getTitle())); ?>
            on <?php echo date('F j',strtotime($this->photo->creation_date)); ?>
          </span>
        </div>
    	</div>
      <div class="sesalbum_view_photo_photo_stats">
        <?php if(($this->getAllowRating == 0 && $this->allowShowRating == 1 && $this->total_rating_average != 0 ) || ($this->getAllowRating == 1) ){ ?>
          <div id="album_rating" class="sesbasic_rating_star sesalbum_view_photo_rating" onmouseout="rating_out();">
            <span id="rate_1" class="rating_star_big_generic fas fa-star" <?php  if ($this->viewer_id && $this->ratedAgain && $this->allowMine &&  $this->allowRating):?>onclick="rate(1);"<?php  endif; ?> onmouseover="rating_over(1);"></span>
            <span id="rate_2" class="rating_star_big_generic fas fa-star" <?php if ( $this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating):?>onclick="rate(2);"<?php endif; ?> onmouseover="rating_over(2);"></span>
            <span id="rate_3" class="rating_star_big_generic fas fa-star" <?php if ( $this->viewer_id && $this->ratedAgain  && $this->allowMine && $this->allowRating):?>onclick="rate(3);"<?php endif; ?> onmouseover="rating_over(3);"></span>
            <span id="rate_4" class="rating_star_big_generic fas fa-star" <?php if ($this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating):?>onclick="rate(4);"<?php endif; ?> onmouseover="rating_over(4);"></span>
            <span id="rate_5" class="rating_star_big_generic fas fa-star" <?php if ($this->viewer_id && $this->ratedAgain  && $this->allowMine && $this->allowRating):?>onclick="rate(5);"<?php endif; ?> onmouseover="rating_over(5);"></span>
            <span id="rating_text" class="sesbasic_rating_text"><?php echo $this->translate('click to rate');?></span>
          </div>
        <?php } ?>  
        <div class="sesalbum_list_stats sesbasic_clearfix sesbasic_text_light clear">
          <span title="<?php echo $this->translate(array('%s Like', '%s Likes', $this->photo->like_count), $this->locale()->toNumber($this->photo->like_count))?>"><i class="sesbasic_icon_like_o" ></i><?php echo $this->translate(array('%s Like', '%s Likes', $this->photo->like_count), $this->locale()->toNumber($this->photo->like_count))?></span>
          <span title="<?php echo $this->translate(array('%s Comment', '%s Comments', $this->photo->comment_count), $this->locale()->toNumber($this->photo->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $this->translate(array('%s Comment', '%s Comments', $this->photo->comment_count), $this->locale()->toNumber($this->photo->comment_count))?></span>
          <span title="<?php echo $this->translate(array('%s View', '%s Views', $this->photo->view_count), $this->locale()->toNumber($this->photo->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $this->translate(array('%s View', '%s Views', $this->photo->view_count), $this->locale()->toNumber($this->photo->view_count))?></span>
          <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.allowfavourite', 1)) { ?>
            <span title="<?php echo $this->translate(array('%s Favourite', '%s Favourites', $this->photo->favourite_count), $this->locale()->toNumber($this->photo->favourite_count))?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $this->translate(array('%s Favourite', '%s Favourites', $this->photo->favourite_count), $this->locale()->toNumber($this->photo->favourite_count))?></span>
          <?php } ?>
          <span title="<?php echo $this->translate(array('%s Download', '%s Downloads', $this->photo->download_count), $this->locale()->toNumber($this->photo->download_count))?>"><i class="fa fa-download" ></i><?php echo $this->translate(array('%s Download', '%s Downloads', $this->photo->download_count), $this->locale()->toNumber($this->photo->download_count))?></span>
        </div>
      </div>
    </div>
    <div class="sesalbum_view_photo_info_left">
      <?php if( $this->photo->getDescription() ): ?>
        <div class="sesalbum_view_photo_des">
          <b>Description</b>
          <?php echo nl2br($this->photo->getDescription()) ?>
        </div>
      <?php endif; ?>
      <div class="sesalbum_view_photo_tags" id="media_tags" style="display: none;">
        <b><?php echo $this->translate('Tagged') ?></b>
      </div>
      <?php if((!is_null($this->photo->location) && $this->photo->location != '') && (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum_enable_location', 1))){ ?>
        <div class="sesalbum_view_photo_location"><i class="fas fa-map-marker-alt sesbasic_text_light"></i>
        <?php echo $this->htmlLink(Array("module"=> "sesalbum", "controller" => "photo", "action" => "location", "route" => "sesalbum_extended", "type" => "location","album_id" =>$this->photo->album_id, "photo_id" => $this->photo->getIdentity()), $this->photo->location, array("class" => "smoothboxOpen")); ?>
        </div>
      <?php } ?>
    </div>
    <!-- comment code-->
    <div class="sesalbum_photo_view_bottom_comments layout_core_comments">
      <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')){ ?>
      <?php echo $this->action("list", "comment", "sesadvancedcomment", array("type" => "album_photo", "id" => $this->photo->getIdentity(),'is_ajax_load'=>true)); 
        }else{
          echo $this->action("list", "comment", "core", array("type" => "album_photo", "id" => $this->photo->getIdentity())); 
        }
      ?> 
    </div>
  </div>
</div>
<script type="text/javascript">
var optionDataForButton;

<?php
  $report = true;
 if($this->photo->getOwner()->getIdentity() == $this->viewer()->getIdentity())
{
$report = false;
}
?>

optionDataForButton = '<div class="sesalbum_option_box"><?php if ($this->viewer()->getIdentity()):?><?php if( $this->canEdit ): ?><?php if((Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum_enable_location', 1))){ echo $this->htmlLink(Array("module"=> "sesalbum", "controller" => "photo", "action" => "location", "route" => "sesalbum_extended", "type" => "album_photo","album_id" =>$this->photo->album_id, "photo_id" => $this->photo->getIdentity()), $this->translate("Edit Location"), array("class" => "smoothboxOpen sesalbum_icon_map")); } ?><?php echo $this->htmlLink(Array("module"=> "sesalbum", "controller" => "photo", "action" => "edit", "route" => "sesalbum_extended","album_id" =>$this->photo->album_id, "photo_id" => $this->photo->getIdentity()), $this->translate("Edit"), array("class" => "smoothboxOpen sesbasic_icon_edit")) ?><?php endif; ?><?php if( $this->canDelete ): ?><?php echo $this->htmlLink(Array("module"=> "sesalbum", "controller" => "photo", "action" => "delete", "route" => "sesalbum_extended","album_id" =>$this->photo->album_id, "photo_id" => $this->photo->getIdentity()), $this->translate("Delete"), array("class" => "smoothboxOpen sesbasic_icon_delete")) ?><?php endif; ?><?php if( !$this->message_view ):?>  <?php echo $this->htmlLink($this->url(array("action" => "share", "type" => "album_photo", "photo_id" => $this->photo->getIdentity(),"format" => "smoothbox"), "sesalbum_general"	, true), $this->translate("Share"), array("class" => "smoothboxOpen sesbasic_icon_share")); ?><?php if($report){echo $this->htmlLink(Array("module"=> "core", "controller" => "report", "action" => "create", "route" => "default", "subject" => $this->photo->getGuid()), $this->translate("Report"), array("class" => "smoothboxOpen sesbasic_icon_report")); }?><?php echo $this->htmlLink(array("route" => "user_extended", "controller" => "edit", "action" => "external-photo", "photo" => $this->photo->getGuid()), $this->translate("Make Profile Photo"), array("class" => "smoothboxOpen sesalbum_icon_photo")) ?><?php endif;?><?php endif ?><?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 && (Engine_Api::_()->user()->getViewer()->level_id == 1 || Engine_Api::_()->user()->getViewer()->level_id == 2)): ?><?php echo $this->htmlLink(Array("module"=> "sesalbum", "controller" => "index", "action" => "featured", "route" => "sesalbum_extended", "photo_id" => $this->photo->getIdentity(),"type" =>"photos"),  $this->translate((($this->photo->is_featured == 1) ? "Unmark as Featured" : "Mark Featured")), array("class" => "sesalbum_admin_featured sesalbum_icon_photo")) ?><?php echo $this->htmlLink(Array("module"=> "sesalbum", "controller" => "index", "action" => "sponsored", "route" => "sesalbum_extended", "photo_id" => $this->photo->getIdentity(),"type" =>"photos"),  $this->translate((($this->photo->is_sponsored == 1) ? "Unmark as Sponsored" : "Mark Sponsored")), array("class" => "sesalbum_admin_sponsored sesalbum_icon_photo")) ?><?php if(strtotime($this->photo->endtime) < strtotime(date("Y-m-d")) && $this->photo->offtheday == 1){$itemofftheday=0;}else{$itemofftheday = $this->photo->offtheday;}echo $this->htmlLink(Array("module"=> "sesalbum", "controller" => "index", "action" => "offtheday", "route" => "sesalbum_extended","id" => $this->photo->photo_id, "type" => "album_photo", "param" => (($itemofftheday == 1) ? 0 : 1)),  $this->translate((($itemofftheday == 1) ? "Edit of the Day" : "Make Photo of The Day")), array("class" => "smoothboxOpen sesalbum_icon_photo")); ?><?php endif; ?></div>';
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
<?php //} ?>
<?php if(!$this->is_ajax){ ?>
		function changeResizeSesalbum(){
			scriptJquery('#locked_content').remove();
			scriptJquery('#photo_content').show();
			doResizeForButton();	
		}
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
      'url':en4.core.baseUrl + 'widget/index/mod/sesalbum/name/photo-view-page/',
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
					scriptJquery('.layout_sesalbum_photo_view_page').html(responseHTML);
					changeResizeSesalbum();
					var width = scriptJquery('.sesalbum_view_photo_container').width();
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
      'url':en4.core.baseUrl + 'sesalbum/index/corresponding-image/album_id/<?php echo $this->album->album_id; ?>',
      'data': {
        format: 'html',
				is_ajax : 1,
      },
      success: function(responseHTML) {
				if(responseHTML){
					scriptJquery('#sesalbum_corresponding_photo').html(responseHTML);
					scriptJquery('#sesalbum_corresponding_photo > a').each(function(index){
						scriptJquery(this).removeClass('slideuptovisible');
						if(scriptJquery(this).attr('data-url') == "<?php echo $this->photo->photo_id; ?>"){
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
		url = en4.core.baseUrl+'albums/index/tag-photo/photo_id/'+value;
		openURLinSmoothBox(url);	
		return;
	}
}
<?php if($this->viewer->getIdentity() !=0){ ?>
	scriptJquery(document).on('keyup', function (e) {
		if(scriptJquery('#'+e.target.id).prop('tagName') == 'INPUT' || scriptJquery('#'+e.target.id).prop('tagName') == 'TEXTAREA')
				return true;
		if(scriptJquery('#ses_media_lightbox_container').css('display') == 'none'){
			// like code
			if (e.keyCode === 76) {
				if(scriptJquery('#sesLikeUnlikeButtonSesalbum').length > 0)
				 scriptJquery('#sesLikeUnlikeButtonSesalbum').trigger('click');
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
		url = en4.core.baseUrl+'albums/index/like-photo/photo_id/'+value;
		openURLinSmoothBox(url);	
		return;
	}
}
</script>
<?php if(!$this->is_ajax ){ ?>
</div>
<div id="locked_content" style="display:none" class="sesbasic_locked_msg sesbasic_clearfix sesbasic_bxs">
	<div class="sesbasic_locked_msg_img"><i class="fa fa-lock"></i></div>
  <div class="sesbasic_locked_msg_cont">
    <h1><?php echo $this->translate('Locked Photo'); ?></h1>
    <p><?php echo $this->translate('Seems you enter wrong password'); ?> <a href="javascript:;" onClick="window.location.reload();"><?php echo $this->translate('click here'); ?></a> <?php echo $this->translate('to enter password again.'); ?></p>
	</div>    
</div>
<?php if($this->locked){ ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customAlert/sweetalert.css'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customAlert/sweetalert.js'); ?>
<script type="application/javascript">
 function promptPasswordCheck(){
	 scriptJquery('#photo_content').hide();
	 scriptJquery('#locked_content').show();
	//var password = prompt("Enter the password ?");
	swal({   
			title: "",   
			text: "<?php echo $this->translate('Enter Password %s', $this->album->getTitle()); ?>",   
			type: "input",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			animation: "slide-from-top",   
			inputPlaceholder: "<?php echo $this->translate('Enter Password'); ?>"
		}, function(inputValue){   
			if (inputValue === false) {
				scriptJquery('#photo_content').remove();
				scriptJquery('#locked_content').show();
			 return false;
			}
			if (inputValue === "") {    
			 swal.showInputError("<?php echo $this->translate('You need to write something!');  ?>");     
			 return false   
		}
			if(inputValue.toLowerCase() == '<?php echo strtolower($this->password); ?>'){
					scriptJquery('#locked_content').remove();
					scriptJquery('#photo_content').show();
					setCookieSesalbum('<?php echo $this->album->album_id; ?>');
					doResizeForButton();
					swal.close();
			}else{
			 	swal("Wrong Password", "You wrote: " + inputValue, "error");
				scriptJquery('#photo_content').remove();
				scriptJquery('#locked_content').show();
			}
			doResizeForButton();
	});
 }
 promptPasswordCheck();
</script>
<?php }else{ ?>
<script type="application/javascript">
 scriptJquery(document).ready(function(){
	scriptJquery('#locked_content').remove();
	scriptJquery('#photo_content').show();
	doResizeForButton();
});
</script>
<?php } ?>
<?php } ?>
<?php } ?>
