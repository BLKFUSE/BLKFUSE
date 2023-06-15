<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: existing-blogs.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php foreach( $this->paginator as $blog ){ ?>
<div class="sescontest_exiting_blogs_item sesbasic_clearfix">
  <div id="sescontest_photo_content_<?php echo $blog->blog_id;?>">
    <div id="sescontest_thumb_<?php echo $blog->blog_id; ?>" class="sesbasic_clearfix">
      <div style="display: none;"> <?php echo $blog->body; ?> </div>
      <a href="javascript:void(0);" title="<?php echo $blog->title;?>" id="sescontest_profile_upload_existing_photos_<?php echo $blog->blog_id; ?>" data-src="<?php echo $blog->blog_id; ?>" class="sesbasic_clearfix">
      <span class="sescontest_blog_thumb bg_item_photo" style="background-image:url(<?php echo $blog->getPhotoUrl('thumb.normal'); ?>);"></span>
      <div class="sescontest_blog_info">
      	<p class="sescontest_blog_title"><?php echo $blog->title;?></p>
      </div>
      </a>
  	</div>
  </div>
  <?php if(0){ ?>
  	<div class="album_more_photos floatR clear"> <a href="javascript:;" id="sescontest_existing_album_see_more_<?php echo $blog->blog_id; ?>" data-src="1"> <?php echo $this->translate("See More"); ?> &raquo; </a> </div>
  <?php } ?>
  <div class="clear" style="text-align:center;display:none;" id="sescontest_existing_album_see_more_loading_<?php echo $blog->blog_id; ?>"> <img src="application/modules/Core/externals/images/loading.gif" alt="Loading"  /> </div>
</div>
<?php } ?>
<?php  if($this->paginator->getTotalItemCount() == 0){  ?>
<div class="tip"> <span> <?php echo $this->translate("There are currently no blogs");?> <?php echo $this->translate('%1$sClick here%2$s to create one!', 
      '<a href="'.$this->url(array('action' => 'create','controller'=>'index'),'sesalbum_general',true).'">', '</a>'); 
      ?> </span> 
	</div>
<?php } ?>
<script type="application/javascript">
canPaginateExistingPhotos = "<?php echo ($this->paginator->count() == 0 ? '0' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? '0' : '1' ))  ?>";
canPaginatePageNumber = "<?php echo $this->page + 1; ?>";
</script>
<?php die; ?>
