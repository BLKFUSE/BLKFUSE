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
$randonNumber = "eticktokclone_like_video_cnt_";
?>
<?php if(empty($this->isAjax)){ ?>
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eticktokclone/externals/styles/styles.css'); ?>
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
<div class="eticktokclone_profile_videos">
  <div class="eticktokclone_profile_videos_listing eticktokclone_profile_videos_like_listing">
<?php } ?>
<?php
if($this->paginator->getTotalItemCount() > 0){ ?>
  <?php foreach( $this->paginator as $item ){ ?>
    <div class="eticktokclone_profile_videos_item">
      <article>
        <a href="<?php echo $item->getHref(); ?>" class="openVideoInLightbox" data-image="<?php echo $item->getPhotoUrl(); ?>">
          <span class="_thumb" style="background-image:url(<?php echo $item->getPhotoUrl(); ?>);"></span>
          <div class="_cont">
            <p><i class="eticktokclone_icon_like"></i><span><?php echo $item->like_count; ?></span></p>
          </div>
        </a>
      </article>
    </div>
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
<?php if(empty($this->isAjax)){ ?>
  </div>
</div>
<script>
  scriptJquery( window ).load(function() {
		 scriptJquery(window).scroll( function() {
			  var heightOfContentDiv_<?php echo $randonNumber; ?> = scriptJquery('.eticktokclone_profile_videos_like_listing').offset().top;
        var fromtop_<?php echo $randonNumber; ?> = scriptJquery(this).scrollTop();
        if(fromtop_<?php echo $randonNumber; ?> > heightOfContentDiv_<?php echo $randonNumber; ?> - 100 && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block' ){
						document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
				}
     });
	});
</script>
<?php } ?>
<script type="text/javascript">
 var page<?php echo $randonNumber; ?> = '<?php echo $this->page + 1; ?>';
scriptJquery(document).ready(function() {
  viewMoreHide_<?php echo $randonNumber; ?>();
})
viewMoreHide_<?php echo $randonNumber; ?>();
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
      'url': en4.core.baseUrl + "widget/index/mod/eticktokclone/name/profile-videos",
      'data': {
        format: 'html',
        subject:"<?php echo $this->subject->getGuid(); ?>",
        page: page<?php echo $randonNumber; ?>,
				is_ajax : 1
      },
      success: function(responseHTML) {
        
        if(document.getElementById('loading_image_<?php echo $randonNumber; ?>'))
        document.getElementById('loading_image_<?php echo $randonNumber; ?>').remove();
        if(document.getElementById('view_more_<?php echo $randonNumber; ?>'))
        document.getElementById('view_more_<?php echo $randonNumber; ?>').remove();

        scriptJquery('.eticktokclone_profile_videos_like_listing').append(responseHTML);
				
        if(document.getElementById('loading_image_<?php echo $randonNumber; ?>'))
        document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
        if(document.getElementById('view_more_<?php echo $randonNumber; ?>'))
        document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() + 1 ? 'none' : '' )) ?>";
      }
    });
  }

  </script>
  <?php if(!empty($this->isAjax)){ die; } ?>

  