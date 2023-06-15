<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>
<div class="sescontest_category_carousel_wrapper sescontest_carousel_h_wrapper ">
  <div class="slide sesbasic_clearfix sesbasic_bxs sescontest_slick_slider_container <?php echo $this->params['isfullwidth'] ? 'isfull_width' : '' ; ?>" style="height:<?php echo $this->params['height'] ?>px;display:none;">
    <div class="contestslide_<?php echo $this->identity; ?> sescontest_category_carousel sescontest_slick_slider">
      <?php foreach( $this->paginator as $item): ?>
      <div class="sescontest_category_carousel_item sesbasic_clearfix sescontest_grid_btns_wrap" style="height:<?php echo $this->params['height'] ?>px;width:<?php echo $this->params['width'] ?>px;">
        <div class="sescontest_category_carousel_item_thumb" style="height:<?php echo $this->params['height'] ?>px;width:<?php echo $this->params['width'] ?>px;">       
          <?php $href = $item->getHref(); $imageURL = $item->getPhotoUrl();?>
          <a href="<?php echo $href; ?>" class="sescontest_list_thumb_img">
            <span style="background-image:url(<?php echo $imageURL; ?>);"></span>
          </a>
          <?php if(isset($this->socialshareActive)) {
          $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
            <div class="sescontest_grid_btns"> 
              <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sessocialshare')){ ?>
                <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->params['socialshare_enable_plusicon'], 'socialshare_icon_limit' => $this->params['socialshare_icon_limit'])); ?>
              <?php } ?>
            </div>
           <?php } ?>
          </div>
          <div class="sescontest_category_carousel_item_info sesbasic_clearfix centerT">
            <?php if(isset($this->titleActive) ){ ?>
              <span class="sescontest_category_carousel_item_info_title">
                <?php if(strlen($item->getTitle()) > $this->params['title_truncation']){ 
                  $title = mb_substr($item->getTitle(),0,($this->params['title_truncation'] - 3 )).'...';
                  echo $this->htmlLink($item->getHref(),$this->translate($title)) ?>
                <?php }else{ ?>
                  <?php 
                  echo $this->htmlLink($item->getHref(),$this->translate($item->getTitle()) )?>
                <?php } ?>
              </span>
            <?php } ?>
             <?php if(isset($this->descriptionActive) ){ ?>
             <span class="sescontest_category_carousel_item_info_des">
                <?php if(strlen($item->description) > $this->params['description_truncation']){ 
                        $description = mb_substr($item->description,0,($this->params['description_truncation'] - 3)).'...';
                        echo $description; ?>
                <?php }else{ ?>
                  <?php echo $item->description ?>
                <?php } ?>
              </span>
            <?php } ?>
            <?php if(isset($this->countContestsActive) ){ ?>
              <span class="sescontest_category_carousel_item_info_stat">
                <?php echo $this->translate(array('%s Contest', '%s Contests',$item->total_contest_categories), $this->locale()->toNumber($item->total_contest_categories)); ?>
              </span>
            <?php } ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php 
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js');
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/slick/slick.js');
?>
<script type="text/javascript">
  scriptJquery(document).ready(function() {
		<?php if($this->params['isfullwidth']){ ?>
			var htmlElement = scriptJquery("body");
			htmlElement.addClass('sescontest_category_carousel');
		<?php } ?>
		<?php if($this->params['autoplay']){ ?> var playAu = true; <?php }else{ ?> var playAu  = false; <?php } ?>
		sesBasicAutoScroll('.contestslide_<?php echo $this->identity; ?>').slick({
			dots: false,
			infinite: true,
			speed: <?php echo $this->params['speed'] ?>,
			slidesToShow: 1,
			centerMode: true,
			variableWidth: true,
			autoplay:playAu,
		});
  });
	sesBasicAutoScroll('.contestslide_<?php echo $this->identity; ?>').on('init', function(contest, slick, currentSlide, nextSlide){
		sesBasicAutoScroll('.contestslide_<?php echo $this->identity; ?>').parent().show();
		console.log('done');
	});
</script>
