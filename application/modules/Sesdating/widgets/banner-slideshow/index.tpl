<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating	
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesdating/externals/styles/styles.css'); ?>
<?php $identity = $this->identity; ?>
<style>
#slideshow_container	{ width:512px; height:384px; position:relative; }
#slideshow_container img { display:block; position:absolute; top:0; left:0; z-index:1; }
</style>
<script>
scriptJquery(document).ready(function() {
	var showDuration = 3000;
	var images = scriptJquery('#slideshow_container_<?php echo $identity; ?>').find('img');
	var currentIndex = 0;
	var interval;
	images.each(function(img,i){ 
		if(i > 0) {
      img.set('opacity',0);
		}
	});

  var show = function() {
    var elem = scriptJquery('#slideshow_container_<?php echo $identity ?>').find('img');
    elem.eq(currentIndex).animate({ opacity: 0 });
    currentIndex = currentIndex < elem.length - 1 ? currentIndex+1 : 0;
    scriptJquery(elem).eq(currentIndex).animate({ opacity: 1 })
  }
	scriptJquery( window ).load(function() {
    interval = setInterval(show, 10000);
	});
});
</script>
<link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:900" rel="stylesheet">
<div id="slideshow_container_<?php echo $identity; ?>"  class="dating_banner_container_wrapper sesbasic_bxs<?php if($this->full_width){ ?> isfull<?php } ?> " style="height:<?php echo $this->height.'px'; ?>;">
  <div class="dating_banner_container">
		<div class="dating_banner_img_container" style="height:<?php echo $this->height.'px'; ?>;">
			<?php foreach($this->paginator as $itemdata): ?>
				<?php $item = Engine_Api::_()->getItem('sesdating_slide',$itemdata->slide_id); ?>
				<img src="<?php echo $item->getFilePath('file_id'); ?>" />
			<?php endforeach; ?>
		</div>
		<div class="dating_banner_content" style="height:<?php echo $this->height.'px'; ?>;">
     <?php 
     $counter = 1;
     foreach($this->paginator as $item): ?>
			<div class="dating_banner_content_inner" <?php if($counter > 1){ ?> style="display:none;" <?php } ?>>
				<?php if($item->title != '' || $item->description  != '') { ?>	
					<?php if($item->title != ''){ ?>
						<h2 class="dating_banner_title" style="color:#<?php echo $item->title_button_color; ?>"><?php echo $item->title; ?></h2>
					<?php } ?>
				<?php } ?>
				<?php if($item->description  != ''){ ?>
					<p class="dating_banner_des" style="color:#<?php echo $item->description_button_color; ?>"><?php echo $item->description ; ?></p>
				<?php } ?>
				<?php if($item->extra_button){ ?>
        	<div class="dating_banner_btns">
						<a href="<?php echo $item->extra_button_link != '' ? $item->extra_button_link : 'javascript:void(0)'; ?>" class="dating_banner_btn"><?php echo $this->translate($item->extra_button_text); ?></a>
          </div>
				<?php } ?> 
			</div>
      <?php $counter++;
        endforeach; ?>
		</div>
	</div>
</div>
<?php if($this->full_width){ ?>
<script type="application/javascript">
scriptJquery(document).ready(function(){
	scriptJquery('#global_content').css('padding-top',0);
});
</script>

<?php } ?>
