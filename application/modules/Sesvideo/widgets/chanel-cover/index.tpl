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

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/channel_cover.css'); ?>
<!--<?php if($this->tab == 'inside'){ ?>
<style>
.layout_core_container_tabs .tabs_alt{ display:none};
</style>
<?php } ?>-->
<?php
if(isset($this->can_edit)){
  //First, include the Webcam.js JavaScript Library 
  $base_url = $this->layout()->staticBaseUrl;
  $this->headScript()->appendFile($base_url . 'application/modules/Sesbasic/externals/scripts/webcam.js'); 
}
?>
<script type="application/javascript">
<?php if(engine_in_array('rating',$this->option) && (($this->getAllowRating == 0 && $this->allowShowRating == 1 && $this->total_rating_average != 0 ) || ($this->getAllowRating == 1) )){ ?>
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
    var resource_id = <?php echo $this->subject->chanel_id;?>;
    var total_votes = <?php echo $this->rating_count;?>;
    var viewer = <?php echo $this->viewer->getIdentity();?>;
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
						 document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('rating on own chanel is not allowed');?>";
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
        document.getElementById('rating_text').innerHTML = " <?php echo $this->translate(array('%s rating', '%s ratings', $this->rating_count),$this->locale()->toNumber($this->rating_count)) ?>";      }
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
        dataType: 'html',
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
					//showTooltip(10,10,'<i class="fa fa-star"></i><span>'+("Chanel Rated successfully")+'</span>', 'sesbasic_rated_notification');
					total_votes = responseJSON[0].total;
					var rating_sum = responseJSON[0].rating_sum;
					var totalTxt = responseJSON[0].totalTxt;
          pre_rate = rating_sum / total_votes;
          set_rating();
          document.getElementById('rating_text').innerHTML = responseJSON[0].total+' '+totalTxt;
          new_text = responseJSON[0].total+' '+totalTxt;
					showTooltip(10,10,'<i class="fa fa-star"></i><span>'+("Channel Rated successfully")+'</span>', 'sesbasic_rated_notification');
        }
      }));
    }
    set_rating();
  });
<?php } ?>
</script>
<div class="sesvideo_channel_cover_wrapper sesbasic_bxs" style="height:<?php echo $this->height; ?>px">
<div class="sesvideo_channel_cover_container sesbasic_bxs <?php echo $this->tab == 'inside' ? 'sesvideo_channel_cover_tabs_wrap' : '' ?> " style="height:<?php echo $this->height; ?>px">
  <!--Cover Photo-->
   <?php if(isset($this->subject->cover_id) && $this->subject->cover_id != 0 && $this->subject->cover_id != ''){ 
  			 $chanelArtCover =	Engine_Api::_()->storage()->get($this->subject->cover_id, '')->getPhotoUrl(); 
   }else
   		$chanelArtCover =''; 
	?>
  <div id="sesvideo_cover_default" class="sesvideo_cover_thumbs" style="display:<?php echo $chanelArtCover == '' ? 'block' : 'none'; ?>;">
  <ul>
  <?php
     $chanelImage = Engine_Api::_()->sesvideo()->getChanelPhotos($this->subject->getIdentity());
     $countTotal = engine_count($chanelImage);
  	 foreach( $chanelImage as $photo ){
     		 $imageURL = $photo->getPhotoUrl('thumb.normalmain');
          if(strpos($imageURL,'http') === false){
          	$http_s = (!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://";
          	$imageURL = $http_s.$_SERVER['HTTP_HOST'].$imageURL;
           }
           $widthPer = $countTotal == 3 ? "33.33" : ($countTotal == 2 ? "50" : '100') ; ?> 
         		<li style="height:<?php echo $this->height; ?>px;width:<?php echo $widthPer; ?>%">
                <span style="background-image:url(<?php echo $imageURL; ?>);"></span> 
           	</li>
 		<?php } ?>
 </ul>
 </div>
  <span class="sesvideo_channel_cover_image" id="sesvideo_cover_id" style="background-image:url(<?php echo $chanelArtCover; ?>); "></span>
  <span class="sesvideo_channel_cover_fade"></span>
  <!--Upload/Change Cover Options-->
 <?php if($this->can_edit){ ?>
  <div class="sesvideo_channel_cover_change_cover" id="sesvideo_cover_change">
  	 <a href="javascript:;" id="cover_change_btn">
     	<i class="fa fa-camera" id="cover_change_btn_i"></i>
      <span id="change_profile_txt"><?php echo $this->translate("Update Profile Picture"); ?></span>
    </a>
    <div class="sesvideo_channel_cover_change_cover_options sesvideo_option_box"> 
    	<i class="sesvideo_channel_cover_change_cover_options_arrow"></i>
      <input type="file" id="uploadFileSesvideo" name="art_cover" onchange="readImageUrl(this);" style="display:none">
      <a id="uploadWebCamPhoto" href="javascript:;"><i class="fa fa-camera"></i><?php echo $this->translate("Take Cover Photo"); ?></a>
      <a id="uploadCoverPhoto" href="javascript:;"><i class="fa fa-plus"></i><?php echo (isset($this->subject->art_cover) && $this->subject->cover_id != 0 && $this->subject->cover_id != '') ? $this->translate('Change Cover Photo') : $this->translate('Add Cover Photo');; ?></a>
      <a href="javascript:;" id="removeCover" style="display:<?php echo (isset($this->subject->cover_id) && $this->subject->cover_id != 0 && $this->subject->cover_id != '') ? 'block' : 'none' ; ?>;" data-src="<?php echo $this->subject->cover_id; ?>"><i class="fa fa-trash"></i><?php echo $this->translate('Remove Cover Photo'); ?></a>
    </div>
  </div>
 <?php } ?>
  <div class="sesvideo_channel_cover_inner">
    <div class="sesvideo_channel_cover_cont sesbasic_clearfix">
      <div class="sesvideo_channel_cover_cont_inner">
        <!--Main Photo-->
        <div class="sesvideo_channel_cover_main_photo">
        	 <?php 
           if($this->photo == 'oPhoto'){
        			$user = Engine_Api::_()->getItem('user',$this->subject->owner_id);
        			echo $this->itemPhoto($user, 'thumb.profile');
            }else{?>
            		<img src="<?php echo $this->subject->getPhotoUrl(); ?>" alt="" class="thumb_profile item_photo_user ">
         <?php  	}
 				 ?>
        </div>        
        <div class="sesvideo_channel_cover_info">
          <h2 class="sesvideo_channel_cover_title">
          	<?php echo $this->subject->getTitle(); ?>
            <?php if(engine_in_array('verified',$this->option) && $this->subject->is_verified){ ?>
          		<i class="sesvideo_verified fa fa-check-square" title="<?php echo $this->translate('Verified') ;?>"></i>
            <?php } ?>
          </h2>
          <div class="sesvideo_channel_cover_date clear sesbasic_clearfix"> 
          	<?php echo  $this->translate('by').' '.$this->subject->getOwner()->__toString(); ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $this->translate('Added %1$s', $this->timestamp($this->subject->creation_date)); ?>
          </div>
          <?php if($this->allowRateOwn && engine_in_array('rating',$this->option) && (($this->getAllowRating == 0 && $this->allowShowRating == 1 && $this->total_rating_average != 0 ) || ($this->getAllowRating == 1) )){ ?>
              <div id="video_rating" class="sesbasic_rating_star sesvideo_channel_cover_rating" onmouseout="rating_out();">
                <span id="rate_1" class="fa fa-star" <?php  if ($this->viewer_id && $this->ratedAgain && $this->allowMine &&  $this->allowRating):?>onclick="rate(1);"<?php  endif; ?> onmouseover="rating_over(1);"></span>
                <span id="rate_2" class="fa fa-star" <?php if ( $this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating):?>onclick="rate(2);"<?php endif; ?> onmouseover="rating_over(2);"></span>
                <span id="rate_3" class="fa fa-star" <?php if ( $this->viewer_id && $this->ratedAgain  && $this->allowMine && $this->allowRating):?>onclick="rate(3);"<?php endif; ?> onmouseover="rating_over(3);"></span>
                <span id="rate_4" class="fa fa-star" <?php if ($this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating):?>onclick="rate(4);"<?php endif; ?> onmouseover="rating_over(4);"></span>
                <span id="rate_5" class="fa fa-star" <?php if ($this->viewer_id && $this->ratedAgain  && $this->allowMine && $this->allowRating):?>onclick="rate(5);"<?php endif; ?> onmouseover="rating_over(5);"></span>
                <span id="rating_text" class="sesbasic_rating_text"><?php echo $this->translate('click to rate');?></span>
              </div>
            <?php } ?>
          <!--Channel Statics-->
           <?php if(engine_in_array('stats',$this->option)){ ?>
            <div class="sesvideo_channel_cover_stats sesbasic_clearfix">
              <div title="<?php echo $this->translate(array('%s video', '%s videos', $this->video_count), $this->locale()->toNumber($this->video_count))?>"> 
                <span class="sesvideo_channel_cover_stat_count"><?php echo $this->video_count; ?></span>
                <span class="sesvideo_channel_cover_stat_txt"> <?php echo str_replace(',','',preg_replace('/[0-9]+/', '', $this->translate(array('%s Video', '%s Videos', $this->video_count), $this->locale()->toNumber($this->video_count)))); ?></span>
              </div>
            <?php if(Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('sesalbum'))){ ?>
              <div title="<?php echo $this->translate(array('%s photo', '%s photos', $this->photo_count), $this->locale()->toNumber($this->photo_count))?>"> 
                <span class="sesvideo_channel_cover_stat_count"><?php echo $this->photo_count; ?></span>
                <span class="sesvideo_channel_cover_stat_txt"> <?php echo str_replace(',','',preg_replace('/[0-9]+/', '', $this->translate(array('%s Photo', '%s Photos', $this->photo_count), $this->locale()->toNumber($this->photo_count)))); ?></span>
              </div>
            <?php } ?>
              <div title="<?php echo $this->translate(array('%s view', '%s views', $this->subject->view_count), $this->locale()->toNumber($this->subject->view_count))?>">
                <span class="sesvideo_channel_cover_stat_count"><?php echo $this->subject->view_count ?></span>
                <span class="sesvideo_channel_cover_stat_txt"> <?php echo str_replace(',','',preg_replace('/[0-9]+/','',$this->translate(array('%s View', '%s Views', $this->subject->view_count), $this->locale()->toNumber($this->subject->view_count)))); ?></span>
              </div>
              <div title="<?php echo $this->translate(array('%s like', '%s likes', $this->subject->like_count), $this->locale()->toNumber($this->subject->like_count))?>">
                <span class="sesvideo_channel_cover_stat_count"><?php echo $this->subject->like_count; ?></span>
                <span class="sesvideo_channel_cover_stat_txt"> <?php echo str_replace(',','',preg_replace('/[0-9]+/', '', $this->translate(array('%s Like', '%s Likes', $this->subject->like_count), $this->locale()->toNumber($this->subject->like_count)))); ?></span>
              </div>
              <div title="<?php echo $this->translate(array('%s comment', '%s comments',$this->subject->comment_count), $this->locale()->toNumber($this->subject->comment_count))?>">
                <span class="sesvideo_channel_cover_stat_count"><?php echo $this->subject->comment_count; ?></span>
                <span class="sesvideo_channel_cover_stat_txt"> <?php echo str_replace(',','',preg_replace('/[0-9]+/', '',  $this->translate(array('%s Comment', '%s Comments',$this->subject->comment_count), $this->locale()->toNumber($this->subject->comment_count)))); ?></span> 
              </div>
              <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavc', 1)) { ?>
              <div title="<?php echo $this->translate(array('%s favourite', '%s favourites', $this->subject->favourite_count), $this->locale()->toNumber($this->subject->favourite_count))?>">
                <span class="sesvideo_channel_cover_stat_count"><?php echo $this->subject->favourite_count; ?></span>
                <span class="sesvideo_channel_cover_stat_txt"> <?php echo str_replace(',','',preg_replace('/[0-9]+/', '', $this->translate(array('%s Favourite', '%s Favourites', $this->subject->favourite_count), $this->locale()->toNumber($this->subject->favourite_count)))); ?></span>
              </div>
              <?php } ?>
              <div title="<?php echo $this->translate(array('%s follower', '%s followers', $this->subject->follow_count), $this->locale()->toNumber($this->subject->follow_count))?>">
                <span class="sesvideo_channel_cover_stat_count"><?php echo $this->subject->follow_count; ?></span>
                <span class="sesvideo_channel_cover_stat_txt"> <?php echo str_replace(',','',preg_replace('/[0-9]+/', '', $this->translate(array('%s Follower', '%s Followers', $this->subject->follow_count), $this->locale()->toNumber($this->subject->follow_count)))); ?></span>
              </div>
            </div>
           <?php } ?>
        </div>

       <div class="sesvideo_channel_cover_footer">
       		<div class="sesvideo_channel_cover_footer_inner">
        <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0){ ?>
          <!--Channel Buttons-->
          <div class="sesvideo_channel_cover_buttons sesbasic_clearfix">
            <?php if(engine_in_array('addVideo',$this->option)){ ?>
           <?php if($this->can_edit){ ?>
            <a href="<?php echo $this->url(array('module' => 'sesvideo', 'action' => 'create', 'chanel_id' => $this->subject->chanel_id), 'sesvideo_general', true); ?>" class="sesbasic_icon_btn" title="<?php echo $this->translate('Add video'); ?>" class="sesbasic_icon_btn sesbasic_icon_edit_btn" ><i class="fas fa-file-video"></i></a>
           <?php } ?>
          <?php } ?> 
           <?php if(engine_in_array('follow',$this->option)){ ?>
           <?php  $followbutton =  Engine_Api::_()->getDbtable('chanelfollows', 'sesvideo')->checkFollow(Engine_Api::_()->user()->getViewer()->getIdentity(),$this->subject->chanel_id); ?>
            <a href="javascript:;" title="<?php echo $this->translate('Follow'); ?>" data-url="<?php echo $this->subject->chanel_id ; ?>" class="sesbasic_icon_btn sesvideo_chanel_follow sesbasic_icon_btn_count sesbasic_icon_follow_btn <?php echo ($followbutton)  ? 'button_active' : '' ?>"> <i class="fa fa-check"></i><span><?php echo $this->subject->follow_count; ?></span></a>
          
           <?php } ?>
            <?php
                $canComment =  $this->subject->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');
                if(engine_in_array('like',$this->option) && Engine_Api::_()->user()->getViewer()->getIdentity() != 0 && $canComment){
            ?>
                <!--Like Button-->
                <?php $LikeStatus = Engine_Api::_()->sesvideo()->getLikeStatusVideo($this->subject->chanel_id,$this->subject->getType()); ?>
              <a href="javascript:;" title="<?php echo $this->translate('Like'); ?>" data-url="<?php echo $this->subject->chanel_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesvideo_like_sesvideo_chanel <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $this->subject->like_count; ?></span></a>
              <?php } ?>              
             <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavc', 1) && engine_in_array('favourite',$this->option) &&  isset($this->subject->favourite_count) && Engine_Api::_()->user()->getViewer()->getIdentity() != '0'){ ?>
              <?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesvideo')->isFavourite(array('resource_type'=>'sesvideo_chanel','resource_id'=>$this->subject->chanel_id)); ?>
              <a href="javascript:;" title="<?php echo $this->translate('Favourite'); ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesvideo_favourite_sesvideo_chanel <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $this->subject->chanel_id ; ?>"><i class="fa fa-heart"></i><span><?php echo $this->subject->favourite_count; ?></span></a>
            <?php } ?>
          <?php if(engine_in_array('report',$this->option) && ($this->viewer_id != $this->subject->owner_id)){ ?>
            <a title="<?php echo $this->translate('Report'); ?>" href="<?php echo $this->url(array("module"=> "core", "controller" => "report", "action" => "create", "route" => "default", "subject" => $this->subject->getGuid()),'default',true); ?>" onclick='opensmoothboxurl(this.href);return false;' class="sesbasic_icon_btn sesbasic_icon_share_btn" ><i class="fa fa-flag"></i></a>
          <?php } ?>
          <?php if(engine_in_array('share',$this->option)){ ?>
            <a title="<?php echo $this->translate('Share'); ?>" href="<?php echo $this->url(array('module'=> 'sesvideo', 'controller' => 'index','action' => 'share','route' => 'default','type' => 'sesvideo_chanel','id' => $this->subject->getIdentity(),'format' => 'smoothbox'),'default',true); ?>" class="sesbasic_icon_btn sesbasic_icon_share_btn" onclick='opensmoothboxurl(this.href);return false;' ><i class="fas fa-share-alt"></i></a>
            <?php } ?>
          <?php if(engine_in_array('edit',$this->option)){ ?>
           <?php if($this->can_edit){ ?>
            <a href="<?php echo $this->url(array('module' => 'sesvideo', 'action' => 'edit', 'chanel_id' => $this->subject->chanel_id), 'sesvideo_chanel', true); ?>" class="sesbasic_icon_btn" title="<?php echo $this->translate('Edit Channel'); ?>" class="sesbasic_icon_btn sesbasic_icon_edit_btn" ><i class="fas fa-pencil-alt"></i></a>
           <?php } ?>
          <?php } ?>  
         <?php if(engine_in_array('delete',$this->option)){ ?>
           <?php if($this->can_delete){ ?>
            <a title="<?php echo $this->translate('Delete Channel'); ?>" href="<?php echo $this->url(array('module' => 'sesvideo', 'action' => 'delete', 'chanel_id' => $this->subject->chanel_id), 'sesvideo_chanel', true); ?>" class="sesbasic_icon_btn sesbasic_icon_delete_btn" onclick='opensmoothboxurl(this.href);return false;'><i class="fa fa-trash"></i></a>
           <?php } ?>
          <?php } ?>
          </div>
        <?php  } ?>       
         <?php if($this->tab == 'inside'){ ?>
          <div class="sesvideo_chanel_tabs sesvideo_channel_cover_tabs"></div>
         <?php } ?>
         </div>
       </div>
      </div>
    </div>
  </div>
  <div id="sesvideo_cover_photo_loading" class="sesbasic_loading_cont_overlay" style="display:none;"></div>
</div>
</div>
<?php if($this->is_fullwidth){ ?>
<script type="application/javascript">
scriptJquery(document).ready(function(){
	var htmlElement = scriptJquery("#body");
  htmlElement.addClass('sesvideo_channel_cover_full');
	scriptJquery('#global_content').css('padding-top',0);
	scriptJquery('#global_wrapper').css('padding-top',0);	
});
</script>
<?php } ?>

<?php if($this->tab == 'inside'){ ?>
<style type="text/css">
@media only screen and (min-width:767px){
.layout_core_container_tabs .tabs_alt{ display:none;}
}
</style>
<script type="application/javascript">
if (matchMedia('only screen and (min-width: 767px)').matches) {
scriptJquery(document).ready(function(){
var tabs = scriptJquery('.layout_core_container_tabs').find('.tabs_alt').get(0).outerHTML;
scriptJquery('.layout_core_container_tabs').find('.tabs_alt').remove();
scriptJquery('.sesvideo_chanel_tabs').html(tabs);
});
scriptJquery(document).on('click','ul#main_tabs li > a',function(){
	if(scriptJquery(this).parent().hasClass('more_tab'))
	  return;
	var index = scriptJquery(this).parent().index() + 1;
	var divLength = scriptJquery('.layout_core_container_tabs > div');
	for(i=0;i<divLength.length;i++){
		scriptJquery(divLength[i]).hide();
	}
	scriptJquery('.layout_core_container_tabs').children().eq(index).show();
});
scriptJquery(document).on('click','.tab_pulldown_contents ul li',function(){
 var totalLi = scriptJquery('ul#main_tabs > li').length;
 var index = scriptJquery(this).index();
 var divLength = scriptJquery('.layout_core_container_tabs > div');
	for(i=0;i<divLength.length;i++){
		scriptJquery(divLength[i]).hide();
	}
 scriptJquery('.layout_core_container_tabs').children().eq(index+totalLi).show();
});
}
</script>
<?php } ?>
<?php if(isset($this->can_edit)){ ?>
<script type="application/javascript">
scriptJquery('<div class="sesvideo_photo_update_popup sesbasic_bxs" id="sesvideo_popup_cam_upload" style="display:none"><div class="sesvideo_photo_update_popup_overlay"></div><div class="sesvideo_photo_update_popup_container sesvideo_photo_update_webcam_container"><div class="sesvideo_photo_update_popup_header sesbm"><?php echo $this->translate("Click to Take Photo") ?><a class="fa fa-times" href="javascript:;" onclick="hideProfilePhotoUpload()" title="<?php echo $this->translate("Close") ?>"></a></div><div class="sesvideo_photo_update_popup_webcam_options"><div id="sesvideo_camera" style="background-color:#ccc;"></div><div class="centerT sesvideo_photo_update_popup_btns">   <button onclick="take_snapshot()" style="margin-right:3px;" ><?php echo $this->translate("Take Photo") ?></button><button onclick="hideProfilePhotoUpload()" ><?php echo $this->translate("Cancel") ?></button></div></div></div></div><div class="sesvideo_photo_update_popup sesbasic_bxs" id="sesvideo_popup_existing_upload" style="display:none"><div class="sesvideo_photo_update_popup_overlay"></div><div class="sesvideo_photo_update_popup_container" id="sesvideo_popup_container_existing"><div class="sesvideo_photo_update_popup_header sesbm"><?php echo $this->translate("Select a photo") ?><a class="fa fa-times" href="javascript:;" onclick="hideProfilePhotoUpload()" title="<?php echo $this->translate("Close") ?>"></a></div><div class="sesvideo_photo_update_popup_content"><div id="sesvideo_chanel_existing_data"></div><div id="sesvideo_profile_existing_img" style="display:none;text-align:center;"><img src="application/modules/Sesbasic/externals/images/loading.gif" alt="<?php echo $this->translate("Loading"); ?>" style="margin-top:10px;"  /></div></div></div></div>').appendTo('body');
scriptJquery(document).on('click','#uploadCoverPhoto',function(){
		document.getElementById('uploadFileSesvideo').click();
});
function readImageUrl(input){
	var url = input.files[0].name;
	var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
	if((ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG' || ext == 'gif' || ext == 'GIF' || ext == "webp")){
		var formData = new FormData();
		formData.append('webcam', input.files[0]);
		formData.append('chanel_id', '<?php echo $this->subject->chanel_id; ?>');
		scriptJquery('#sesvideo_cover_photo_loading').show();
 scriptJquery.ajax({
		xhr:  function() {
		var xhrobj = scriptJquery.ajaxSettings.xhr();
		if (xhrobj.upload) {
				xhrobj.upload.addEventListener('progress', function(event) {
						var percent = 0;
						var position = event.loaded || event.position;
						var total = event.total;
						if (event.lengthComputable) {
								percent = Math.ceil(position / total * 100);
						}
						//Set progress
				}, false);
		}
		return xhrobj;
		},
    url:  en4.core.baseUrl+'sesvideo/chanel/edit-coverphoto/',
    type: "POST",
    contentType:false,
    processData: false,
		cache: false,
		data: formData,
		success: function(response){
			text = JSON.parse(response);
			if(text.status == 'true'){
				if(text.src != '')
				scriptJquery('#sesvideo_cover_id').css('background-image', 'url(' + text.src + ')');
				scriptJquery('#sesvideo_cover_default').hide();
				scriptJquery('#uploadCoverPhoto').html('<i class="fa fa-plus"></i>'+en4.core.language.translate('Change Cover Photo'));
				scriptJquery('#removeCover').css('display','block');
			}
			scriptJquery('#sesvideo_cover_photo_loading').hide();
		}
    });
	}
}
scriptJquery(document).on('click','#uploadWebCamPhoto',function(){
	scriptJquery('#sesvideo_popup_cam_upload').show();
	<!-- Configure a few settings and attach camera -->
	Webcam.set({
		width: 320,
		height: 240,
		image_format:'jpeg',
		jpeg_quality: 90
	});
	Webcam.attach('#sesvideo_camera');
});
<!-- Code to handle taking the snapshot and displaying it locally -->
function take_snapshot() {
	// take snapshot and get image data
	Webcam.snap(function(data_uri) {
		Webcam.reset();
		scriptJquery('#sesvideo_popup_cam_upload').hide();
		scriptJquery('#sesvideo_cover_photo_loading').show();
		// upload results
		 Webcam.upload( data_uri, en4.core.baseUrl+'sesvideo/chanel/edit-coverphoto/chanel_id/<?php echo $this->subject->chanel_id; ?>' , function(code, text) {
			 	text = JSON.parse(text);
				if(text.status == 'true'){
					if(text.src != ''){
						scriptJquery('#sesvideo_cover_id').css('background-image', 'url(' + text.src + ')');
						scriptJquery('#sesvideo_cover_default').hide();
						scriptJquery('#uploadCoverPhoto').html('<i class="fa fa-plus"></i>'+en4.core.language.translate('Change Cover Photo'));
						scriptJquery('#removeCover').css('display','block');
					}
				}
				scriptJquery('#sesvideo_cover_photo_loading').hide();
			} );
	});
}
scriptJquery('#removeCover').click(function(){
		scriptJquery(this).css('display','none');
		scriptJquery('#sesvideo_cover_id').css('background-image', 'url()');
		scriptJquery('#sesvideo_cover_default').show();
		var chanel_id = '<?php echo $this->subject->chanel_id; ?>';
		uploadURL = en4.core.baseUrl+'sesvideo/chanel/remove-cover/chanel_id/'+chanel_id;
		var jqXHR=scriptJquery.ajax({
			url: uploadURL,
			type: "POST",
			contentType:false,
			processData: false,
			cache: false,
			success: function(response){
				scriptJquery('#uploadCoverPhoto').html('<i class="fa fa-plus"></i>'+en4.core.language.translate('Add Cover Photo'));
				//silence
			 }
			}); 
});
function hideProfilePhotoUpload(){
	if(typeof Webcam != 'undefined')
	 Webcam.reset();
	canPaginatePageNumber = 1;
	scriptJquery('#sesvideo_popup_cam_upload').hide();
	scriptJquery('#sesvideo_popup_existing_upload').hide();
	if(typeof Webcam != 'undefined'){
		scriptJquery('.slimScrollDiv').remove();
		scriptJquery('.sesvideo_photo_update_popup_content').html('<div id="sesvideo_chanel_existing_data"></div><div id="sesvideo_profile_existing_img" style="display:none;text-align:center;"><img src="application/modules/Sesbasic/externals/images/loading.gif" alt="Loading" style="margin-top:10px;"  /></div>');
	}
}
scriptJquery(document).click(function(event){
	if(event.target.id == 'change_profile_txt' || event.target.id == 'cover_change_btn_i' || event.target.id == 'cover_change_btn'){
		if(scriptJquery('#sesvideo_cover_change').hasClass('active'))
			scriptJquery('#sesvideo_cover_change').removeClass('active')
		else
			scriptJquery('#sesvideo_cover_change').addClass('active')
	}else{
			scriptJquery('#sesvideo_cover_change').removeClass('active')
	}
});
</script>
<?php } ?>
