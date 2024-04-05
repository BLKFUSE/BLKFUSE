<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 $randonNumber = $this->randonNumber ? $this->randonNumber : "eticktokclone_cnt_".time().bin2hex(random_bytes(16));
 if(!$this->isAjax){
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eticktokclone/externals/styles/styles.css'); ?>

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
<div class="eticktokclone_videos_feed_container" id="eticktokclone_videos_feed_container_<?php echo $randonNumber ?>">
<?php } 
  if($this->paginator->getTotalItemCount() > 0){

?>
  <?php foreach( $this->paginator as $item ){
    
    $owner = Engine_Api::_()->getItem("eticktokclone_user",$item->owner_id);
    
    ?>
    <section class="eticktokclone_videos_feed_item">
      <div class="eticktokclone_videos_feed_item_header">
        <div class="_thumb">
          <?php echo $this->htmlLink($owner->getHref(), $this->itemBackgroundPhoto($owner->getOwner(), 'thumb.icon', $owner->getOwner()->getTitle()), array('title'=>$item->getOwner()->getTitle())) ?>
        </div>
        <div class="_content">
          <div class="_info">
            <p class="_title">
              <?php echo $this->htmlLink($owner->getOwner()->getHref(), $owner->getOwner()->getTitle()) ?>
            </p>
            <p class="_des"> <?php echo $item->getTitle(); ?></p>
            <div class="_des sesbasic_text_light"> <?php echo $item->getDescription(); ?></div>
            <?php if($item->songtitle){ ?>
            <p class="_music">
              <i class="fas fa-music"></i>
              <span><?php echo $item->songtitle; ?></span>
            </p>
            <?php } ?>
            <?php 
            $videoTags = $item->tags()->getTagMaps();
            if (engine_count($videoTags )):?>
              <p>
                <?php foreach ($videoTags as $tag):
                      if(empty($tag->getTag()->text))
                        continue;
                ?> 
                  <a class="tagged-video" href='<?php echo $this->url(array("tag"=>$tag->getIdentity()),'eticktokclone_tagged',true) ?>'>#<?php echo $tag->getTag()->text?></a>&nbsp;
                <?php endforeach; ?>
              </p>
            <?php endif; ?>
          </div>
          <?php if($this->viewer()->getIdentity() && $item->getOwner()->getIdentity() != $this->viewer()->getIdentity()){ ?>
          <div class="_btn">
            <?php $FollowUser = Engine_Api::_()->eticktokclone()->getFollowStatus($item->getOwner()->getIdentity());
            ?>
            <a href="javascript:void(0);" data-url="<?php echo $item->getOwner()->getIdentity(); ?>" onClick="eticktokclone_follow_button(this)" style="display:<?php echo !$FollowUser ? "block" : "none" ?>;" class="eticktokclone_follow_button follow"><?php echo $this->translate("Follow"); ?></a>
            <a href="javascript:void(0);" data-url="<?php echo $item->getOwner()->getIdentity(); ?>" onClick="eticktokclone_follow_button(this)" style="display:<?php echo $FollowUser ? "block" : "none" ?>;" class="eticktokclone_follow_button unfollow active" data-bs-toggle="eticktokclone_tooltip" data-bs-title="<?php echo $this->translate("Un-Follow"); ?>"><?php echo $this->translate("Following"); ?></a>
          </div>
          <?php }else if(!$this->viewer()->getIdentity()){ ?>
            <div class="_btn">
          <a href="<?php echo $this->url(array(), 'default', true) ?>login"  class="eticktokclone_follow_button follow"><?php echo $this->translate("Follow"); ?></a>
            </div>
        <?php } ?>
        </div>
      </div>
      <div class="eticktokclone_videos_feed_item_body">
        <div class="eticktokclone_videos_feed_item_player">
          <div class="_video">
            <?php $videoUrlID = Engine_Api::_()->getItem("storage_file",$item->file_id); 
              $videoURL = "";
              if($videoUrlID){
                $videoURL = $videoUrlID->map();
              }
            ?>
            <video controls id="video-feed-id-<?php echo $item->getIdentity(); ?>">
              <source src="<?php echo $videoURL; ?>" type="video/mp4">
              Your browser does not support the video tag.
            </video> 
           
          </div>
        </div>
        <div class="eticktokclone_videos_feed_item_options">
          <div class="_like">
            <?php  $LikeStatus = Engine_Api::_()->sesvideo()->getLikeStatusVideo($item->getIdentity(),$item->getType()); ?>
            <a href="javascript:void(0)" onClick="eticktokcloneincrementlike(this)"  data-url="<?php echo $item->video_id ; ?>" class="<?php echo $this->viewer()->getIdentity() ? "sesvideo_like_sesvideo_video" : ""; ?><?php echo ($LikeStatus) ? ' button_active' : '' ; ?>"><i class="fas fa-heart"></i></a>
            <span><?php echo $item->like_count;?></span>
          </div>
          <div class="_comment">
            <a href="<?php echo $item->getHref(); ?>" data-id="<?php echo $item->getIdentity(); ?>" class="openVideoInLightbox" data-image="<?php echo $item->getPhotoUrl(); ?>"><i class="fas fa-comment"></i></a>
            <span><?php echo $item->comment_count;?></span>
          </div>
          <div class="_share dropup">
            <a href="javascript:void(0)" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-share"></i></a>
            <div class="dropdown-menu">
              <div class="_sharepopup">
                <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php } ?>
  
  <div class="sesbasic_load_btn" style="display: none;" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" >
      <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" id="feed_viewmore_link_<?php echo $randonNumber; ?>"><i class="fa fa-sync"></i><span><?php echo $this->translate('View More');?></span></a>
    </div>  
    <div class="sesbasic_load_btn sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
  <?php }else{ ?>
    <div class="tip">
      <span><?php echo $this->translate("No video created yet.") ?></span>
    </div>
  <?php } ?>
  <script type="text/javascript">
 var page<?php echo $randonNumber; ?> = '<?php echo $this->page + 1; ?>';


en4.core.runonce.add(function () {
	viewMoreHide_<?php echo $randonNumber; ?>();
});
function viewMoreHide_<?php echo $randonNumber; ?>() {
    if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
  }
  function viewMore_<?php echo $randonNumber; ?> (){

    if(document.getElementById('loading_image_<?php echo $randonNumber; ?>'))
        document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'block';
        if(document.getElementById('view_more_<?php echo $randonNumber; ?>'))
        document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "none";
    requestViewMore_<?php echo $randonNumber; ?> = scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + "widget/index/mod/eticktokclone/name/<?php echo $this->widgetName; ?>",
      'data': {
        format: 'html',
        randonNumber:"<?php echo $randonNumber; ?>",
        page: page<?php echo $randonNumber; ?>,
				is_ajax : 1,
				limit_data: '<?php echo $this->limit_data; ?>',
      },
      success: function(responseHTML) {
        if(document.getElementById('loading_image_<?php echo $randonNumber; ?>'))
        document.getElementById('loading_image_<?php echo $randonNumber; ?>').remove();
        if(document.getElementById('view_more_<?php echo $randonNumber; ?>'))
        document.getElementById('view_more_<?php echo $randonNumber; ?>').remove();
        scriptJquery('#eticktokclone_videos_feed_container_<?php echo $randonNumber ?>').find("script").remove();
        scriptJquery('#eticktokclone_videos_feed_container_<?php echo $randonNumber ?>').append(responseHTML);
				
        if(document.getElementById('loading_image_<?php echo $randonNumber; ?>'))
        document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
        if(document.getElementById('view_more_<?php echo $randonNumber; ?>'))
        document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() + 1 ? 'none' : '' )) ?>";
      }
    });
  }

  </script>

  <?php if(!$this->isAjax){ ?>
    <!-- load more button -->

</div>

<script type="text/javascript">
  scriptJquery(document).on("click",'.openVideoInLightbox',function(e) {
    let id = scriptJquery(this).attr("data-id");
    scriptJquery(`#video-feed-id-${id}`)[0].pause();
  })
  function eticktokclone_follow_button(obj){
    if(!en4.user.viewer.id){
      window.location.href = en4.core.baseUrl+"login";
      return;
    }
    if(scriptJquery(obj).hasClass('follow')){
      scriptJquery(obj).parent().find(".unfollow").css('display','');
      scriptJquery(obj).hide();
    }else{
      scriptJquery(obj).parent().find(".follow").css('display','');
      scriptJquery(obj).hide();
    }
    scriptJquery.post(en4.core.baseUrl +"eticktokclone/index/follow",{id:scriptJquery(obj).data("url")},function(){})

  }

scriptJquery( window ).load(function() {
		 scriptJquery(window).scroll( function() {
			  var heightOfContentDiv_<?php echo $randonNumber; ?> = scriptJquery('#eticktokclone_videos_feed_container_<?php echo $randonNumber ?>').offset().top;
        var fromtop_<?php echo $randonNumber; ?> = scriptJquery(this).scrollTop();
        if(fromtop_<?php echo $randonNumber; ?> > heightOfContentDiv_<?php echo $randonNumber; ?> - 100 && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block' ){
						document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
				}
     });
	});
  function eticktokcloneincrementlike(obj){

    if(!en4.user.viewer.id){
      window.location.href = en4.core.baseUrl+"login";
      return;
    }
    let objD = scriptJquery(obj);
    let currentValue = parseInt(objD.parent().find("span").html());
    if(objD.hasClass("button_active")){
      // reduce
      objD.parent().find("span").html(currentValue -1);
    }else{
      // increment
      objD.parent().find("span").html(currentValue +1);
    }
  }
</script>
<?php }else { ?> 

<?php die; ?>  
<?php } ?>

<script type="text/javascript">
// Tooltip
scriptJquery(document).ready(function(){
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="eticktokclone_tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  });
});
</script>
