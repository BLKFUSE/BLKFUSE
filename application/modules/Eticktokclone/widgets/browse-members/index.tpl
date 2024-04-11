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
 
$randonNumber = "eticktokclone_cnt_member_".time(); ?>
<?php if(!empty($this->followVideos)){ ?>
  <?php echo $this->content()->renderWidget('eticktokclone.video-feed',array("followUser"=>true)) ?>
  <?php //return; ?>
<?php } ?>
<?php
 if(!$this->isAjax){
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eticktokclone/externals/styles/styles.css'); ?>

<div class="eticktokclone_members_listing">
 <?php }
 if($this->paginator->getTotalItemCount() > 0){
 ?>
 <?php foreach($this->paginator as $item){ ?>
  <div class="eticktokclone_members_listing_item">
    <article>
      <div class="_thumb">
        <a href="<?php echo $item->getHref(); ?>"><?php echo $this->itemPhoto($item, 'thumb.profile'); ?></a>
      </div>
      <div class="_info">
        <p class="_name"><a href="<?php echo $item->getHref(); ?>"><?php echo $item->getTitle(); ?></a></p>
        <p class="_stats sesbasic_text_light"><?php echo (int)Engine_Api::_()->eticktokclone()->getFollowCount($item->getIdentity()); ?> Followers</p>
        <p class="_btn">
          <?php if($this->viewer()->getIdentity() && $item->getIdentity() != $this->viewer()->getIdentity()){ ?>
          <div class="_btn">
            <?php $FollowUser = Engine_Api::_()->eticktokclone()->getFollowStatus($item->getIdentity());
            ?>
            <a href="javascript:void(0);" data-url="<?php echo $item->getIdentity(); ?>" onClick="eticktokclone_follow_button(this)" style="display:<?php echo !$FollowUser ? "" : "none" ?>;" class="eticktokclone_follow_button follow"><?php echo $this->translate("Follow"); ?></a>
            <a href="javascript:void(0);" data-url="<?php echo $item->getIdentity(); ?>" onClick="eticktokclone_follow_button(this)" style="display:<?php echo $FollowUser ? "" : "none" ?>;" class="eticktokclone_follow_button unfollow active" data-bs-toggle="eticktokclone_tooltip" data-bs-title="<?php echo $this->translate("Un-Follow"); ?>"><?php echo $this->translate("Following"); ?></a>
          </p>
        <?php }else if(!$this->viewer()->getIdentity()){ ?>
          <a href="<?php echo $this->url(array(), 'default', true) ?>login"  class="eticktokclone_follow_button"><?php echo $this->translate("Follow"); ?></a>

        <?php } ?>
      </div>
    </article>  
  </div>
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
      'url': en4.core.baseUrl + "widget/index/mod/eticktokclone/name/browse-members",
      'data': {
        format: 'html',
        page: page<?php echo $randonNumber; ?>,
				is_ajax : 1,
        type:"<?php echo $this->type; ?>",
        subject:"<?php echo $this->subject ? $this->subject->getGuid() : ""; ?>"
      },
      success: function(responseHTML) {
        if(document.getElementById('loading_image_<?php echo $randonNumber; ?>'))
        document.getElementById('loading_image_<?php echo $randonNumber; ?>').remove();
        if(document.getElementById('view_more_<?php echo $randonNumber; ?>'))
        document.getElementById('view_more_<?php echo $randonNumber; ?>').remove();
        scriptJquery('.eticktokclone_members_listing').append(responseHTML);
				
        if(document.getElementById('loading_image_<?php echo $randonNumber; ?>'))
        document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
        if(document.getElementById('view_more_<?php echo $randonNumber; ?>'))
        document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() + 1 ? 'none' : '' )) ?>";
      }
    });
  }

  </script>

  <?php }else{ ?>
    <div class="tip">
      <span><?php echo $this->translate("No member found.") ?></span>
    </div>
  <?php } ?>
  
<?php if(!$this->isAjax){ ?>
  <script type="text/javascript">
    scriptJquery( window ).load(function() {
		 scriptJquery(window).scroll( function() {
			  var heightOfContentDiv_<?php echo $randonNumber; ?> = scriptJquery('.eticktokclone_members_listing').offset().top;
        var fromtop_<?php echo $randonNumber; ?> = scriptJquery(this).scrollTop();
        if(fromtop_<?php echo $randonNumber; ?> > heightOfContentDiv_<?php echo $randonNumber; ?> - 100 && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block' ){
						document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
				}
     });
	});
  </script>
</div>

<?php }else{ ?>

  <?php } ?>
