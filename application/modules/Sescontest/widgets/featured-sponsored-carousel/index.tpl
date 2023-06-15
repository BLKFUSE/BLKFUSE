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

<?php  $baseURL = Zend_Registry::get('StaticBaseUrl');?>
<?php 
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js');
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/slick/slick.js');
?>
<?php 
  $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css');
?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>

<div class="sescontest_contest_carousel_wrapper sesbasic_clearfix sesbasic_bxs <?php if($this->params['viewType'] == 'horizontal'): ?>sescontest_carousel_h_wrapper<?php else: ?>sescontest_carousel_v_wrapper <?php endif; ?>" style="min-height:<?php echo $this->params['imageheight'] ?>px;">
  <div id="contestslide_<?php echo $this->identity; ?>" class="sescontest_slick_slider sesbasic_clearfix sescontest_contest_carousel_<?php echo $this->identity; ?> slider">
    <?php foreach( $this->paginator as $contest): ?>
    	<div class="sescontest_advgrid_item sesbasic_clearfix" style="width:<?php echo is_numeric($this->params['width']) ? $this->params['width'].'px':$this->params['width'];?>;">
        <article>
          <div class="sescontest_advgrid_item_header">
          	<?php if(isset($this->titleActive) ){ ?>
              <?php if(strlen($contest->getTitle()) > $this->params['title_truncation']){ 
                $title = mb_substr($contest->getTitle(),0,($this->params['title_truncation'])).'...';
                echo $this->htmlLink($contest->getHref(),$title, array() ) ?>
                <?php if(isset($this->verifiedLabelActive) && $contest->verified):?>
                  <i class="sescontest_label_verified fa fa-check-circle" title='<?php echo $this->translate("Verified");?>'></i>
                <?php endif;?>
              <?php }else{ ?>
                <?php echo $this->htmlLink($contest->getHref(),$contest->getTitle(), array() ) ?>
                <?php if(isset($this->verifiedLabelActive) && $contest->verified):?>
                  <i class="sescontest_label_verified fa fa-check-circle" title='<?php echo $this->translate("Verified");?>'></i>
                <?php endif;?>
              <?php } ?>
          	<?php } ?>
          </div>
          <div class="sescontest_advgrid_item_thumb sescontest_list_thumb" style="height:<?php echo $this->params['imageheight'] ?>px;">
            <a href="<?php echo $contest->getHref(); ?>" class="sescontest_advgrid_img">
              <span class="sesbasic_animation" style="background-image:url(<?php echo $contest->getPhotoUrl('thumb.profile'); ?>);"></span>
            </a>
            <?php $owner = $contest->getOwner(); ?>
            <div class="sescontest_advgrid_item_owner">
              <?php if(isset($this->byActive)){ ?>
                <span class="sescontest_advgrid_item_owner_img">
                  <?php echo $this->htmlLink($owner->getHref(), $this->itemPhoto($owner, 'thumb.normal', $owner->getTitle()), array('title'=>$owner->getTitle())) ?>
                </span>
              <?php } ?>
              
              <?php if(isset($this->socialSharingActive) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive) || isset($this->followButtonActive)) {
            $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $contest->getHref()); ?>
                <span class="useroption">
                  <a href="javascript:void(0);" class="fa fa-angle-down"></a>
                  <div class="sescontest_advgrid_item_btns">
                    <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataSharing.tpl';?>
                  </div>
                </span>
              <?php } ?>
              <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_mediaType.tpl';?>
              <span class="itemcont">
                <?php if(isset($this->byActive)){ ?>
                  <span class="ownername">
                    <?php echo $this->translate('by');?>&nbsp;<?php echo $this->htmlLink($owner->getHref(),$owner->getTitle() ) ?>
                  </span>
                <?php } ?>
                <span><?php echo $this->translate('in');?>&nbsp;
                  <?php if(isset($this->categoryActive)){ ?>
                    <?php if($contest->category_id != '' && intval($contest->category_id) && !is_null($contest->category_id)){ 
                    $categoryItem = Engine_Api::_()->getItem('sescontest_category', $contest->category_id);?>
                    <a href="<?php echo $categoryItem->getHref(); ?>"><?php echo $categoryItem->category_name; ?></a>
                    <?php } ?>
                  <?php } ?>
                </span>
              </span>  
            </div>
            <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->hotLabelActive) || isset($this->verifiedLabelActive)){ ?>
              <div class="sescontest_list_labels sesbasic_animation">
                <?php if(isset($this->featuredLabelActive) && $contest->featured){ ?>
                  <span class="sescontest_label_featured" title="<?php echo $this->translate('Featured');?>"><i class="fa fa-star"></i></span>
                <?php } ?>
                <?php if(isset($this->sponsoredLabelActive) && $contest->sponsored){ ?>
                  <span class="sescontest_label_sponsored" title="<?php echo $this->translate('Sponsored');?>"><i class="fa fa-star"></i></span>
                <?php } ?>
                <?php if(isset($this->hotLabelActive) && $contest->hot){ ?>
                  <span class="sescontest_label_hot" title="<?php echo $this->translate('Hot');?>"><i class="fa fa-star"></i></span>
                <?php } ?>
              </div>
            <?php } ?>
            <a href="<?php echo $contest->getHref(); ?>" class="_viewbtn sesbasic_link_btn sesbasic_animation"><?php echo $this->translate("View Contest");?></a>
          </div>
          <div class="sescontest_advgrid_item_footer">
            <?php include APPLICATION_PATH . '/application/modules/Sescontest/views/scripts/_dataBar.tpl'; ?>
          </div>
        </article>
      </div>
    <?php endforeach; ?>
  </div>
	<div class="sesbasic_loading_cont_overlay" style="display:block;"></div>
</div>
<style type="text/css">
.sescontest_contest_carousel_<?php echo $this->identity; ?>{display:none;}
.sescontest_contest_carousel_<?php echo $this->identity; ?>.slick-initialized{display:block;}
.sescontest_contest_carousel_<?php echo $this->identity; ?>.slick-initialized + .sesbasic_loading_cont_overlay{display:none !important;}
</style>
<script type="text/javascript">
	<?php if($this->params['viewType'] == 'horizontal'): ?>
	sesBasicAutoScroll(document).on('ready', function() {
		sesBasicAutoScroll(".sescontest_contest_carousel_<?php echo $this->identity; ?>").slick({
			infinite: true,
			dots: false,
			slidesToShow: 1,
			variableWidth: true,
			slidesToScroll: 1,
			dots:false,
			centerMode: true,
	})});
<?php else: ?>
	sesBasicAutoScroll(document).on('ready', function() {
		sesBasicAutoScroll(".sescontest_contest_carousel_<?php echo $this->identity; ?>").slick({
			infinite: true,
			dots: false,
			slidesToShow: 1,
			slidesToScroll: 1,
			dots:false,
	})});
<?php endif; ?>
</script>
