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
<?php 
   $viewer = Engine_Api::_()->user()->getViewer();
    if ($viewer->getIdentity() == 0)
      $level = Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
    else
      $level = $viewer;
    $type = Engine_Api::_()->authorization()->getPermission($level, 'sesbasic_video', 'videoviewer');
    if ($type == 1) {
      $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
                      . 'application/modules/Sesbasic/externals/scripts/SesLightbox/photoswipe.min.js')
              ->appendFile(Zend_Registry::get('StaticBaseUrl')
                      . 'application/modules/Sesbasic/externals/scripts/SesLightbox/photoswipe-ui-default.min.js')
              ->appendFile(Zend_Registry::get('StaticBaseUrl')
                      . 'application/modules/Sesbasic/externals/scripts/videolightbox/sesvideoimagevieweradvance.js');
      $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
              . 'application/modules/Sesbasic/externals/styles/photoswipe.css');
    } else {
      $loadImageViewerFile = Zend_Registry::get('StaticBaseUrl') . 'application/modules/Sesbasic/externals/scripts/videolightbox/sesvideoimageviewerbasic.js';
      $this->headScript()->appendFile($loadImageViewerFile);
      $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
              . 'application/modules/Sesbasic/externals/styles/medialightbox.css');
    }
?>
<?php $flowplayer = Engine_Api::_()->sesbasic()->checkPluginVersion('core', '4.8.10') ? 'externals/flowplayer/flowplayer-3.2.18.swf' : 'externals/flowplayer/flowplayer-3.1.5.swf'; ?>
<?php $addThisCode = Engine_Api::_()->getApi('settings', 'core')->getSetting('ses.addthis',0); ?>
<?php if(is_array($this->allowAdvShareOptions) && $addThisCode && engine_in_array('addThis',$this->allowAdvShareOptions)){ ?>
<?php $this->headScript()->appendFile("//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $addThisCode; ?>"); ?>
<?php } ?>
<?php
if(isset($this->docActive)){
	$imageURL = $this->video->getPhotoUrl();
	if(strpos($this->video->getPhotoUrl(),'http') === false)
          	$imageURL = (!empty($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"] == 'on')) ? "https://" : "http://". $_SERVER['HTTP_HOST'].$this->video->getPhotoUrl();
  $this->doctype('XHTML1_RDFA');
  $this->headMeta()->setProperty('og:title', strip_tags($this->video->getTitle()));
  $this->headMeta()->setProperty('og:description', strip_tags($this->video->getDescription()));
  $this->headMeta()->setProperty('og:image',$imageURL);
  $imageHeightWidthData = @getimagesize($imageURL);
  $width = isset($imageHeightWidthData[0]) ? $imageHeightWidthData[0] : '300';
  $height = isset($imageHeightWidthData[1]) ? $imageHeightWidthData[1] : '200'; 
   $this->headMeta()->setProperty('og:image:width',$width);
    $this->headMeta()->setProperty('og:image:height',$height);
  $this->headMeta()->setProperty('twitter:title', strip_tags($this->video->getTitle()));
  $this->headMeta()->setProperty('twitter:description', strip_tags($this->video->getDescription()));
}
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css'); ?>

<?php if(Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($this->video)) { ?>
<div id="video_content" style="display:none" class="<?php if((isset($this->my_videos) && $this->my_videos) || 
(isset($this->my_channel) && $this->my_channel)){ ?>isoptions<?php } ?> paid_content">
  <?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $this->video)); ?>
  <div class="epaidcontent_lock_img"><img src="application/modules/Epaidcontent/externals/images/paidcontent.png" alt="" /></div>
</div>
<?php } else { ?>

<div id="video_content" style="display:none" class="<?php if((isset($this->my_videos) && $this->my_videos) || 
(isset($this->my_channel) && $this->my_channel)){ ?>isoptions<?php } ?> <?php 
if(Engine_Api::_()->getApi('core', 
'sesbasic')->isModuleEnable(array('epaidcontent')) && 
Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) 
&& Engine_Api::_()->epaidcontent()->isViewerPlanActive($this->video)) { ?> 
paid_content <?php } ?>">

              
<?php if( !$this->video || $this->video->status != 1 ):
  echo $this->translate('The video you are looking for does not exist or has not been processed yet.');
  return; // Do no render the rest of the script in this mode
endif; ?>
<?php if ( $this->video->type == 3 && $this->video_extension == 'mp4' )
    $this->headScript()
         ->appendFile($this->layout()->staticBaseUrl . 'externals/html5media/html5media.min.js');
?>

<script type="application/javascript">
var tagAction = window.tagAction = function(tag,name){
  var url = "<?php echo $this->url(array('module' => 'sesvideo','action'=>'browse'), 'sesvideo_general', true) ?>?tag_id="+tag+'&tag_name='+name;
  window.location.href = url;
}
</script>
<?php if(($this->getAllowRating == 0 && $this->allowShowRating == 1 && $this->total_rating_average != 0 ) || ($this->getAllowRating == 1) && engine_in_array('rateCount',$this->allowOptions) ){ ?>
<script type="text/javascript">
  en4.core.runonce.add(function() {
    var pre_rate = "<?php echo $this->total_rating_average == '' ? 0 : $this->total_rating_average  ;?>";
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
    var resource_id = <?php echo $this->video->video_id;?>;
    var total_votes = <?php echo $this->rating_count;?>;
    var viewer = <?php echo $this->viewer_id;?>;
    new_text = '';

    var rating_over = window.rating_over = function(rating) {
      if( rated == 1 ) {
        document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('you already rated');?>";
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
						 document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('rating on own video not allowed');?>";
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
          scriptJquery('#rate_'+x).removeAttr("class");
          if(x <= rating) {
            scriptJquery('#rate_'+x).addClass( 'fas fa-star rating_star_big_generic rating_star_big');
          } else {
            scriptJquery('#rate_'+x).addClass( 'fas fa-star rating_star_big_generic rating_star_big_disabled');
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
          scriptJquery('#rate_'+x).removeAttr("class");
          scriptJquery('#rate_'+x).addClass( 'fas fa-star rating_star_big_generic rating_star_big_disabled');
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
        scriptJquery('#rate_'+x).removeAttr("class");
        scriptJquery('#rate_'+x).addClass( 'fas fa-star rating_star_big_generic rating_star_big');
      }

      for(var x=parseInt(rating)+1; x<=5; x++) {
        scriptJquery('#rate_'+x).removeAttr("class");
        scriptJquery('#rate_'+x).addClass( 'fas fa-star rating_star_big_generic rating_star_big_disabled');
      }

      var remainder = Math.round(rating)-rating;
      if (remainder <= 0.5 && remainder !=0){
        var last = parseInt(rating)+1;
        scriptJquery('#rate_'+x).removeAttr("class");
        scriptJquery('#rate_'+last).addClass( 'fas fa-star rating_star_big_generic rating_star_big_half');
      }
    }

    var rate = window.rate = function(rating) {
      document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('Thanks for rating!');?>";
			<?php if($this->allowRateAgain == 0 && !$this->rated){ ?>
						 for(var x=1; x<=5; x++) {
								scriptJquery('#rate_'+x).attr('onclick', '');
							}
					<?php } ?>
     
      (scriptJquery.ajax({
        dataType: 'json',
        'format': 'json',
        'url' : '<?php echo $this->url(array('module' => 'sesvideo', 'controller' => 'index', 'action' => 'rate'), 'default', true) ?>',
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
					total_votes = responseJSON[0].total;
					var rating_sum = responseJSON[0].rating_sum;
          pre_rate = rating_sum / total_votes;
          set_rating();
					if(responseJSON[0].total == 1)
						var textRating = en4.core.language.translate('rating');
					else
						var textRating = en4.core.language.translate('ratings');
          document.getElementById('rating_text').innerHTML = responseJSON[0].total+" "+textRating;
          new_text = responseJSON[0].total+" "+textRating;
					showTooltip(10,10,'<i class="fa fa-star"></i><span>'+("Video Rated successfully")+'</span>', 'sesbasic_rated_notification');
        }
      }));
    }
    set_rating();
  });
</script>
<?php } ?>
<div class="sesvideo_video_view_container clear sesbasic_clearfix sesbasic_bxs">

  <?php if( $this->video->type == 3 ): ?>
    <div id="video_embed" class="sesvideo_view_embed clear sesbasic_clearfix ">

      <?php if ($this->video_extension !== 'flv'): ?>
        <?php if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('videovod')): ?>
          <iframe src="<?php echo  $this->baseUrl();  ?>/videovod/Iframe/index?video_id=<?php echo $this->v_id; ?>" width="480" allowfullscreen></iframe>
        <?php else: ?>
          <video id="video" controls preload="auto" width="480" height="386">
            <source type='video/mp4' src="<?php echo $this->video_location ?>">
          </video>
        <?php endif ?>
      <?php endif ?>
    </div>
  <?php else: ?>
    <div class="sesvideo_view_embed clear sesbasic_clearfix">
      <?php echo $this->videoEmbedded; ?>
    </div>
  <?php endif; ?>
  <div class="sesvideo_view_links">
    <?php $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideosell') && $this->video->owner_id == $viewer_id): ?>
      <a href="" class="fas fa-video"><?php echo $this->translate("View Sample Video"); ?></a>
		<?php endif; ?>
    <?php if(engine_in_array('openVideoLightbox',$this->allowOptions) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbasic.enable.lightbox', 1)){ ?>
      <a href="javascript:;" id="openVideoInLightbox" class="fa fa-expand sesvideo_view_openlightbox_link"><?php echo $this->translate("Open in Lightbox")?></a>
    <?php } ?>
	</div>
  
  <h2 class="sesvideo_view_title sesbasic_clearfix clear">
    <?php echo $this->video->getTitle() ?>
  </h2>
  <div class="sesvideo_view_author">
    <div class="sesvideo_view_author_photo">  
      <?php echo $this->htmlLink($this->video->getParent(), $this->itemPhoto($this->video->getParent(), 'thumb.icon')); ?>
    </div>
    <div class="sesvideo_view_author_info">
      <div class="sesvideo_view_author_name sesbasic_text_light">
        <?php echo $this->translate('By') ?>
        <?php echo $this->htmlLink($this->video->getParent(), $this->video->getParent()->getTitle()) ?>
      </div>
      <div class="sesvideo_view_date sesbasic_text_light">
        <?php echo $this->translate('Posted') ?>
        <?php echo $this->timestamp($this->video->creation_date) ?>
      </div>
    </div>
  </div>
  <div class="sesvideo_view_statics">
    <?php if(($this->getAllowRating == 0 && $this->allowShowRating == 1 && $this->total_rating_average != 0 ) || ($this->getAllowRating == 1) && engine_in_array('rateCount',$this->allowOptions) ){ ?>
      <div id="album_rating" class="sesvideo_rating_star sesvideo_view_rating" onMouseOut="rating_out();">
        <span id="rate_1" class="rating_star_big_generic fas fa-star" <?php  if ($this->viewer_id && $this->ratedAgain && $this->allowMine &&  $this->allowRating):?>onclick="rate(1);"<?php  endif; ?> onMouseOver="rating_over(1);"></span>
        <span id="rate_2" class="rating_star_big_generic fas fa-star" <?php if ( $this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating):?>onclick="rate(2);"<?php endif; ?> onMouseOver="rating_over(2);"></span>
        <span id="rate_3" class="rating_star_big_generic fas fa-star" <?php if ( $this->viewer_id && $this->ratedAgain  && $this->allowMine && $this->allowRating):?>onclick="rate(3);"<?php endif; ?> onMouseOver="rating_over(3);"></span>
        <span id="rate_4" class="rating_star_big_generic fas fa-star" <?php if ($this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating):?>onclick="rate(4);"<?php endif; ?> onMouseOver="rating_over(4);"></span>
        <span id="rate_5" class="rating_star_big_generic fas fa-star" <?php if ($this->viewer_id && $this->ratedAgain  && $this->allowMine && $this->allowRating):?>onclick="rate(5);"<?php endif; ?> onMouseOver="rating_over(5);"></span>
        <span id="rating_text" class="sesbasic_rating_text"><?php echo $this->translate('click to rate');?></span>
      </div>
    <?php } ?>
    <div class="sesvideo_view_stats sesvideo_list_stats sesbasic_text_light">
    <?php if(engine_in_array('likeCount',$this->allowOptions)){ ?>
      <span><i class="sesbasic_icon_like_o"></i><?php echo $this->translate(array('%s like', '%s likes', $this->video->like_count), $this->locale()->toNumber($this->video->like_count)); ?></span>
      <?php } ?>
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavv', 1) && engine_in_array('favouriteCount',$this->allowOptions)){ ?>
      <span><i class="sesbasic_icon_favourite_o"></i><?php echo $this->translate(array('%s favourite', '%s favourites', $this->video->favourite_count), $this->locale()->toNumber($this->video->favourite_count)); ?></span>
      <?php } ?>
      <?php if(engine_in_array('commentCount',$this->allowOptions)){ ?>
    <span><i class="sesbasic_icon_comment_o"></i><?php echo $this->translate(array('%s comment', '%s comments', $this->video->comment_count), $this->locale()->toNumber($this->video->comment_count))?></span>
    <?php } ?>
    <?php if(engine_in_array('viewCount',$this->allowOptions)){ ?>
    <span><i class="sesbasic_icon_view"></i><?php echo $this->translate(array('%s view', '%s views', $this->video->view_count), $this->locale()->toNumber($this->video->view_count)) ?></span>
    <?php } ?>
    </div>
  </div>
  <div class="sesvideo_view_meta sesbasic_text_light clear sesbasic_clearfix">
    <?php if( $this->category ): ?>
      <span><i class="far fa-folder-open" title="<?php echo $this->translate('Category'); ?>"></i> 
      	<?php if($this->video->category_id){ ?>
      	<?php $category = Engine_Api::_()->getItem('sesvideo_category',$this->video->category_id); ?>
       <?php if($category){ ?>
          <a href="<?php echo $category->getHref(); ?>"><?php echo $category->category_name; ?></a>
          	<?php $subcategory = Engine_Api::_()->getItem('sesvideo_category',$this->video->subcat_id); ?>
             <?php if($subcategory){ ?>
                &nbsp;&raquo;&nbsp;<a href="<?php echo $subcategory->getHref(); ?>"><?php echo $subcategory->category_name; ?></a>
            <?php } ?>
            <?php $subsubcategory = Engine_Api::_()->getItem('sesvideo_category',$this->video->subsubcat_id); ?>
             <?php if($subsubcategory){ ?>
                &nbsp;&raquo;&nbsp;<a href="<?php echo $subsubcategory->getHref(); ?>"><?php echo $subsubcategory->category_name; ?></a>
            <?php } ?>
      	<?php }          
      	} ?>
      </span>
    <?php endif; ?>
    
    <?php if (engine_count($this->videoTags )):?>
      <span>
        <i class="fa fa-tag"></i> 
        <?php foreach ($this->videoTags as $tag):
        			if(empty($tag->getTag()->text))
              	continue;
         ?>
          <a href='javascript:;' onclick='javascript:tagAction(<?php echo $tag->getTag()->tag_id; ?>,"<?php echo $tag->getTag()->text; ?>");'>#<?php echo $tag->getTag()->text?></a>&nbsp;
        <?php endforeach; ?>
      </span>
    <?php endif; ?>
   	<?php if(!is_null($this->video->location) && $this->video->location != '' && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo_enable_location', 1)){ ?>
      <span>
        <i class="fas fa-map-marker-alt"></i> 
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
          <a href="javascript:;" onclick="openURLinSmoothBox('<?php echo $this->url(array("module"=> "sesvideo", "controller" => "index", "action" => "location",  "video_id" => $this->video->getIdentity(),'type'=>'video_location'),'default',true); ?>');return false;"><?php echo $this->video->location; ?></a>
        <?php } else { ?>
          <?php echo $this->video->location;?>
        <?php } ?>
      </span>
    <?php } ?>
  </div>
  <div class="sesvideo_view_desc clear">
    <?php echo nl2br($this->video->description);?>
  </div>
  <div class="sesvideo_view_custom_fields">
	<?php
    //custom field data
    echo $this->sesbasicFieldValueLoop($this->video);
	?>
  </div>

  <div class="sesbasic_clearfix sesvideo_view_btm">
    <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideosell')) { ?>
    <?php //SEll Work ?>
    <div class="sesvideo_sellbutton_wrapper">
      <?php if($this->video->price > 0) { ?>
        <?php $currency = Engine_Api::_()->sesbasic()->getCurrencyPrice($this->video->price, 'USD');  ?>
        <span class="sesvideo_view_price"><?php echo $currency; ?></span>
    	<?php } ?>
      <span>
        <?php 
          
          $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
          
          $order_id = Engine_Api::_()->getDbTable('orders', 'sesvideosell')->getOrderId(array('video_id' => $this->video->getIdentity(), 'user_id' => $viewer_id));
      
          if($viewer_id && $this->video->price > 0 && $this->video->owner_id != $viewer_id && empty($order_id)) {
            echo $this->htmlLink(array('route' => 'default', 'module' => 'sesvideosell', 'controller' => 'order', 'action' => 'process', 'video_id' => $this->video->video_id, 'gateway_id' => 1), $this->translate("Buy This Video"), array('class' => 'sesvideo_sellbutton sesbasic_animation'));
          } elseif($viewer_id && $this->video->price > 0 && $this->video->owner_id != $viewer_id && !empty($order_id)) {
            echo $this->htmlLink(array('route' => 'sesvideosell_specific', 'action' => 'download', 'video_id' => $this->video->getIdentity()), $this->translate("Download This Video"), array('class' => 'sesvideo_sellbutton sesbasic_animation'));
          }      
        ?>
      </span>
    </div>
    <?php //SELL WORK ?>
    <?php } ?>
  
    <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0){ ?>
 		 <div class='sesvideo_view_options'>
<?php  if($this->video->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment')){ ?>
    <!--Like Button-->
    <?php $LikeStatus = Engine_Api::_()->sesvideo()->getLikeStatusVideo($this->video->getidentity(),$this->video->getType()); ?>
    	<a href="javascript:;" title="<?php echo $this->translate('Like'); ?>" data-url="<?php echo $this->video->getIdentity() ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesvideo_like_sesvideo_video <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $this->video->like_count; ?></span></a>
    <?php } ?>
    <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavv', 1) && engine_in_array('favouriteButton',$this->allowOptions) && Engine_Api::_()->user()->getViewer()->getIdentity()){ ?>
    	<?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesvideo')->isFavourite(array('resource_type'=>'sesvideo_video','resource_id'=>$this->video->getIdentity())); ?>
      	<a href="javascript:;" title="<?php echo $this->translate('Favourite'); ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesvideo_favourite_sesvideo_video <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $this->video->getIdentity(); ; ?>"><i class="fa fa-heart"></i><span><?php echo $this->video->favourite_count; ?></span></a>
    <?php } ?>
    <?php if(engine_in_array('shareAdvance',$this->allowOptions)){ ?>
      <a href="javascript:;" title="<?php echo $this->translate('Share'); ?>" class="sesbasic_icon_btn initialism sesbasic_popup_slide_open btn btn-success">
      	<i class="fa fa-share"></i>
      </a>
		<?php } ?>
    <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('video.enable.watchlater', 1) && Engine_Api::_()->user()->getViewer()->getIdentity() && engine_in_array('watchLater',$this->allowOptions)){
    $item = Engine_Api::_()->getDbTable('videos', 'sesvideo')->getWatchLaterStatus($this->video->video_id);
    ?>
      <a href="javascript:;" class="sesbasic_icon_btn sesvideo_watch_later <?php echo engine_count($item)  ? 'selectedWatchlater' : '' ?>" title = "<?php echo engine_count($item)  ? $this->translate('Remove from Watch Later') : $this->translate('Add to Watch Later') ?>" data-url="<?php echo $this->video->video_id ; ?>">
      	<i class="far fa-clock"></i>
      </a>
    <?php } ?>
    <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() && engine_in_array('addToPlaylist',$this->allowOptions)){ ?>
             <?php if(Engine_Api::_()->authorization()->getPermission(Engine_Api::_()->user()->getViewer()->level_id, 'video', 'addplayl_video')) { ?>
      <a href="javascript:;" onclick="opensmoothboxurl('<?php echo $this->url(array('action' => 'add','module'=>'sesvideo','controller'=>'playlist','video_id'=>$this->video->video_id),'default',true); ?>')" class="sesbasic_icon_btn sesvideo_add_playlist"  title="<?php echo  $this->translate('Add To Playlist'); ?>" data-url="<?php echo $this->video->video_id ; ?>"><i class="fa fa-plus"></i></a>
             <?php } ?>
    <?php } ?>
    <?php if( Engine_Api::_()->user()->getViewer()->getIdentity() ): ?>
			<?php if(engine_in_array('shareSimple',$this->allowOptions)){ ?>
      	<a href="<?php echo $this->url(array('module'=> 'sesvideo', 'controller' => 'index','action' => 'share','route' => 'default','type' => 'video','id' => $this->video->getIdentity(),'format' => 'smoothbox'),'default',true); ?>" class="sesbasic_icon_btn initialism btn btn-success smoothbox"  title="<?php echo  $this->translate('Share'); ?>"><i class="fa fa-share"></i></a>
      <?php  } ?>
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('video.enable.report',1) && engine_in_array('reportVideo',$this->allowOptions) && (Engine_Api::_()->user()->getViewer()->getIdentity() != $this->video->owner_id)){ ?>
    		<a href="<?php echo $this->url(array('module'=> 'core','controller' => 'report','action' => 'create','route' => 'default','subject' => $this->video->getGuid(),'format' => 'smoothbox'),'default',true); ?>" class="sesbasic_icon_btn sesbasic_icon_report_btn smoothbox"  title="<?php echo  $this->translate('Report'); ?>" data-url="<?php echo $this->video->video_id ; ?>"><i class="fa fa-flag"></i></a>
      <?php } ?>
    <?php endif ?>
     
    <?php if( $this->can_embed && engine_in_array('embedVideo',$this->allowOptions)): ?>
    	<a href="<?php echo $this->url(array('module'=> 'sesvideo','controller' => 'video','action' => 'embed','id' => $this->video->getIdentity(),'format' => 'smoothbox'),'default',true); ?>" class="sesbasic_icon_btn sesbasic_icon_embed_btn smoothbox"  title="<?php echo  $this->translate('Embed'); ?>"><i class="fa fa-code"></i></a>
    <?php endif;?>
     
    <?php if( $this->can_edit && engine_in_array('editVideo',$this->allowOptions)): ?>
    	<a href="<?php echo $this->url(array('controller' => 'index','action' => 'edit','video_id' => $this->video->video_id),'sesvideo_general',true) ?>" class="sesbasic_icon_btn sesbasic_icon_edit_btn"  title="<?php echo  $this->translate('Edit'); ?>" data-url="<?php echo $this->video->video_id ; ?>"><i class="fas fa-edit"></i></a>
    <?php endif;?>
    
    <?php if( $this->can_delete && $this->video->status != 2 && engine_in_array('deleteVideo',$this->allowOptions)): ?>
    	<a href="<?php echo $this->url(array('controller' => 'index', 'action' => 'delete', 'video_id' => $this->video->video_id),'sesvideo_general',true); ?>" class="sesbasic_icon_btn sesbasic_icon_delete_btn smoothbox"  title="<?php echo  $this->translate('Delete'); ?>"><i class="fa fa-trash"></i></a>
    <?php endif;?>
  </div>  
 		<?php } ?>
  </div>
</div>
<div id="sesvideo_image_video_url" data-src="<?php echo $this->video->getPhotoUrl(); ?>" style="display:none"></div>
<!-- Slide in -->
<div id="sesbasic_popup_slide" class="well">
  <div class="sesbasic_popup sesbasic_bxs">
    <div class="sesbasic_popup_title">
       <?php echo $this->translate("Share This Video"); ?>
      <span class="sesbasic_popup_slide_close sesbasic_text_light">
        <i class="fa fa-times"></i>
      </span>
    </div>
    <div class="sesbasic_popup_content">
      <div class="sesbasic_share_popup_content_row clear sesbasic_clearfix">
      	<div class="sesbasic_share_popup_buttons clear">
          <?php if(is_array($this->allowAdvShareOptions) && engine_in_array('privateMessage',$this->allowAdvShareOptions)){ ?>
            <a href="javascript:void(0)" class="sesbasic_button" onClick="opensmoothboxurl('<?php echo $this->url(array('module'=> 'sesbasic', 'controller' => 'index', 'action' => 'message','item_id' => $this->video->getIdentity(), 'type'=>'sesvideo_video'),'default',true); ?>')"> <?php echo $this->translate("Private Message"); ?></a>
            <?php } ?>
             <?php if(is_array($this->allowAdvShareOptions) && engine_in_array('siteShare',$this->allowAdvShareOptions)){ ?>
            <a href="javascript:void(0)" class="sesbasic_button" onClick="opensmoothboxurl('<?php echo $this->url(array('module'=> 'sesvideo', 'controller' =>'index','action' => 'share','type' => 'video','id' => $this->video->getIdentity(),'format' => 'smoothbox'),'default',true); ?>')"> <?php echo $this->translate("Share on Site"); ?></a>
            <?php } ?>
             <?php if(is_array($this->allowAdvShareOptions) && engine_in_array('quickShare',$this->allowAdvShareOptions)){ ?>
            <a href="javascript:void(0)" class="sesbasic_button" onClick="sessendQuickShare('<?php echo $this->url(array('module'=> 'sesvideo', 'controller' =>'index','action' => 'share','type' => 'video','id' => $this->video->getIdentity()),'default',true); ?>');return false;"> <?php echo $this->translate("Quick Share on Site"); ?></a>
          <?php } ?>
      	</div>
      </div>
      <?php if(is_array($this->allowAdvShareOptions) && $addThisCode && engine_in_array('addThis',$this->allowAdvShareOptions)){ ?>
      	<div class="sesbasic_share_popup_content_row clear sesbasic_clearfix">
          <div class="sesbasic_share_popup_content_field clear">
            <!-- Go to www.addthis.com/dashboard to customize your tools -->
            <div class="addthis_sharing_toolbox"></div>
          </div>
       	</div>
     <?php  } ?>
		 <!-- <?php if( $this->can_embed && Engine_Api::_()->getApi('settings', 'core')->getSetting('video.embeds', 1) && engine_in_array('embed',$this->allowAdvShareOptions)): ?>   
      <div class="sesbasic_share_popup_content_row clear sesbasic_clearfix">
        <div class="sesbasic_share_popup_content_label">
          <?php echo $this->translate("Embed"); ?>
        </div>
        <div class="sesbasic_share_popup_content_field clear">
          <textarea id="embed_testare_select_ses" style="height:67px"><?php echo $this->video->getEmbedCode(); ?></textarea>
        </div>
      </div>
    	<?php endif ?> -->
      <div class="sesbasic_share_popup_content_row">
      	<div class="sesbasic_share_itme_preview sesbasic_clearfix">
        	<div class="sesbasic_share_itme_preview_img">
          	<img src="<?php echo $this->video->getPhotoUrl();?>" />
          </div>
          <div class="sesbasic_share_itme_preview_info">
          	<div class="sesbasic_share_itme_preview_title">
            	<a href="<?php echo $this->video->getHref();?>"><?php echo $this->video->title;?></a>
            </div>
            <div class="sesbasic_share_itme_preview_des">
             <?php if(strlen($this->video->description) > 200){ 
                  $description = mb_substr($this->video->description,0,200).'...';
                  echo nl2br(strip_tags($description));
                 }else{ ?>
              <?php  echo nl2br(strip_tags($this->video->description));?>
              <?php } ?>
            </div>	
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- comment / like and artist code -->
<div class="sesvideo_view_bottom sesbasic_clearfix">
	<div class="sesvideo_view_bottom_right sesbasic_bxs">
    <?php if(engine_in_array('peopleLike',$this->allowOptions)){  ?>
      <div class="layout_sesvideo_people_like_video">
        <?php echo $this->content()->renderWidget('sesvideo.people-like-item',array('limit_data'=>$this->limitLike,'removeDecorator'=>'yes')); ?>
      </div>
    <?php } ?>
    <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavv', 1) && engine_in_array('favourite',$this->allowOptions) && $this->viewer()->getIdentity() != 0){  ?>
      <div class="layout_sesvideo_people_favourite_video">
        <?php echo $this->content()->renderWidget('sesvideo.people-favourite-item',array('limit_data'=>$this->limitFavourite,'removeDecorator'=>'yes')); ?>
      </div>
    <?php } ?>
    <?php $artists = json_decode($this->video->artists,true); ?>
    <?php if(!empty($artists) && engine_in_array('artist',$this->allowOptions) && engine_count($artists) && $artists != ''){  ?>
      <div class="layout_sesvideo_video_artist">
        <h3><?php echo $this->translate('Artist In This Video'); ?></h3>
        <ul class="sesbasic_user_grid_list sesbasic_clearfix">
          <?php foreach( $artists as $item ): ?>
            <li>
              <?php $artistItem = Engine_Api::_()->getItem('sesvideo_artist',$item) ?>
              <?php if(!$artistItem) continue; ?>
               <?php echo $this->htmlLink($artistItem->getHref(), $this->itemPhoto($artistItem, 'thumb.icon'),array('title'=>$artistItem->getTitle())); ?>
            </li>
          <?php endforeach; ?>
        </ul>
    	</div>
  	<?php } ?>
  </div>
  <div class="sesvideo_view_bottom_left layout_core_comments">
    <?php if(engine_in_array('comment',$this->allowOptions)){ ?>
    	<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')){ ?>
      <?php echo $this->action("list", "comment", "sesadvancedcomment", array("type" => $this->video->getType(), "id" => $this->video->getIdentity(),'is_ajax_load'=>true)); 
        }else{ echo $this->action("list", "comment", "core", array("type" => $this->video->getType(), "id" => $this->video->getIdentity())); } ?>
    <?php } ?>
  </div>


</div>


<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery-1.8.2.min.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery.popupoverlay.js'); ?>
<script type="text/javascript">
jquery1_8_2SesObject(document).ready(function () {
		jquery1_8_2SesObject('#embed_testare_select_ses').toggle(function() {
		jquery1_8_2SesObject(this).select();
	}, function() {
	});
    jquery1_8_2SesObject('#sesbasic_popup_slide').popup({
			focusdelay: 400,
			outline: true,
			vertical: 'top'
    });
});
</script>

</div>
<?php } ?>

<div id="locked_content" style="display:none" class="sesbasic_locked_msg sesbasic_clearfix sesbasic_bxs">
  <div class="sesbasic_locked_msg_img"><i class="fa fa-lock"></i></div>
    <div class="sesbasic_locked_msg_cont">
  	<h1><?php echo $this->translate('Locked Video'); ?></h1>
    <p>
    	<?php echo $this->translate('Seems you enter wrong password'); ?>
      <a href="javascript:;" onClick="window.location.reload();"><?php echo $this->translate('click here'); ?></a>
    	<?php echo $this->translate('to enter password again.'); ?>
  	</p>
  </div>
</div>
<?php if($this->locked){ ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customAlert/sweetalert.css'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customAlert/sweetalert.js'); ?>
<script type="application/javascript">
 function promptPasswordCheck(){
	 scriptJquery('#video_content').hide();
	 scriptJquery('#locked_content').show();
	//var password = prompt("Enter the password ?");
	swal({   
			title: "",   
			text: "<?php echo $this->translate('Enter Password For:'); ?> <?php echo $this->video->getTitle(); ?>",   
			type: "input",   
			showCancelButton: true,   
			closeOnConfirm: false,   
			animation: "slide-from-top",   
			inputPlaceholder: "<?php echo $this->translate('Enter Password'); ?>"
		}, function(inputValue){   
			if (inputValue === false) {
				scriptJquery('#video_content').remove();
				scriptJquery('#locked_content').show();
				scriptJquery('.layout_core_comments').remove();
			 return false;
			}
			if (inputValue === "") {    
			 swal.showInputError("<?php echo $this->translate('You need to write something!');  ?>");     
			 return false   
		}
			if(inputValue.toLowerCase() == '<?php echo strtolower($this->password); ?>'){
					scriptJquery('#locked_content').remove();
					scriptJquery('#video_content').show();
					setCookieSesvideo('<?php echo $this->video->video_id; ?>');
					if(scriptJquery('.sesvideo_view_embed').find('iframe')){
						var changeiframe = true;
					}else{
							
					}
					scriptJquery('.layout_core_comments').show();
					swal.close();
					scriptJquery('.layout_sesvideo_video_view_page').show();
			}else{
			 	swal("Wrong Password", "You wrote: " + inputValue, "error");
				scriptJquery('#video_content').remove();
				scriptJquery('#locked_content').show();
				
				scriptJquery('.layout_core_comments').remove();
			}
			if(typeof changeiframe != 'undefined'){
				scriptJquery('.sesvideo_view_embed').find('iframe').attr('src',scriptJquery('.sesvideo_view_embed').find('iframe').attr('src'));
				var aspect = 16 / 9;
				var el = document.id("videoFrame<?php echo $this->video->getIdentity(); ?>");
				if(typeof el == "undefined" || !el)
					return;
				var parent = el.getParent();
				var parentSize = parent.getSize();
				el.set("width", parentSize.x);
				el.set("height", parentSize.x / aspect);	
			}
	});
 }
 promptPasswordCheck();
</script>
<?php }else{ ?>
<script type="application/javascript">
 scriptJquery(document).ready(function(){
		scriptJquery('#locked_content').remove();
		scriptJquery('#video_content').show();
		scriptJquery('.layout_core_comments').show();
		if(scriptJquery('.sesvideo_view_embed').find('iframe')){
			scriptJquery('.sesvideo_view_embed').find('iframe').attr('src',scriptJquery('.sesvideo_view_embed').find('iframe').attr('src'));
			var aspect = 16 / 9;
			var el = document.getElementById("videoFrame");
			if(typeof el == "undefined" || !el)
				return;
			var parent = el.getParent();
			var parentSize = parent.getSize();
			el.set("width", parentSize.x);
			el.set("height", parentSize.x / aspect);	
		}
			
	 });
</script>
<?php } ?>
