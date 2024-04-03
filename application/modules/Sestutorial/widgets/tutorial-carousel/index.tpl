<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>

<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sestutorial/externals/scripts/core.js'); ?>

<?php $randonNumber = $this->identity; ?>
<?php $baseUrl = $this->layout()->staticBaseUrl; ?>
<script type="text/javascript" src="<?php echo $baseUrl; ?>externals/ses-scripts/PeriodicalExecuter.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>externals/ses-scripts/Carousel.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>externals/ses-scripts/Carousel.Extra.js"></script>

<style>
  #tutorialcategories<?php echo $randonNumber; ?> {
    position: relative;
    height:<?php echo $this->height + 2 ?>px;
    overflow: hidden;
  }
</style>
<div class="sestutorial_bxs slide sestutorial_category_carousel_wrapper clearfix <?php if($this->viewType == 'horizontal'): ?> sestutorial_category_carousel_h_wrapper <?php else: ?> sestutorial_category_carousel_v_wrapper <?php endif; ?>">
  <div id="tutorialcategories<?php echo $randonNumber; ?>" class="sestutorial_category_carousel">
    <?php foreach( $this->resultcategories as $item ):  ?>
      <div class="sestutorial_user_<?php echo $item->category_id ?>_<?php echo $randonNumber; ?> sestutorial_home_category_section sestutorial_clearfix" value="<?php echo $item->getIdentity();?>" style="width:<?php echo $this->width ?>px;">
        <?php if(@engine_in_array('socialshare', $this->showdetails)): ?>
          <div class="tutorial_social_btns">
            <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
            <?php  echo $this->partial('_socialShareIcons.tpl', 'sesbasic', array('resource' => $item, 'socialshare_enable_plusicon' => $this->widgetParams['socialshare_enable_plusicon'], 'socialshare_icon_limit' => $this->widgetParams['socialshare_icon_limit'])); ?>
          </div>
        <?php endif; ?>
      	<a href="<?php echo $item->getHref(); ?>" style="height:<?php echo $this->height ?>px;">
          <div class="sestutorial_home_category_section_img" style="height:<?php echo $this->heightphoto ?>px;width:100px;">
            <img src="<?php echo $item->getPhotoUrl(); ?>" />
          </div>
          <?php if(engine_in_array('categorytitle', $this->showdetails)): ?>
            <div class="sestutorial_home_category_section_title">
              <?php echo $item->title; ?>
            </div>
          <?php endif; ?>
          <?php if(engine_in_array('description', $this->showdetails)): ?>
            <div class="sestutorial_home_category_section_des">
              <?php echo $item->description; ?>
            </div>
          <?php endif; ?>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
  <?php if($this->viewType == 'horizontal'): ?>
    <div class="tabs_<?php echo $randonNumber; ?> sestutorial_category_carousel_nav">
      <a class="sestutorial_category_carousel_nav_pre sestutorial_animation" href="#page-p"><i class="fa fa-angle-left"></i></a>
      <a class="sestutorial_category_carousel_nav_nxt sestutorial_animation" href="#page-p"><i class="fa fa-angle-right"></i></a>
    </div>  
  <?php else: ?>
    <div class="tabs_<?php echo $randonNumber; ?> sestutorial_category_carousel_nav">
      <a class="sestutorial_category_carousel_nav_pre" href="#page-p"><i class="fa fa-angle-up"></i></a>
      <a class="sestutorial_category_carousel_nav_nxt" href="#page-p"><i class="fa fa-angle-down"></i></a>
    </div>  
  <?php endif; ?>

</div>
<script type="text/javascript">
scriptJquery(document).ready(function() {
  var duration = 150,
  div = document.getElement('div.tabs_<?php echo $randonNumber; ?>');
  links = div.getElements('a'),
  carousel = new Carousel.Extra({
    activeClass: 'selected',
    container: 'tutorialcategories<?php echo $randonNumber; ?>',
    circular: true,
    current: 1,
    previous: links.shift(),
    next: links.pop(),
    tabs: links,
    mode: '<?php echo $this->viewType; ?>',
    fx: {
      duration: duration
    }
  })
});
</script>
