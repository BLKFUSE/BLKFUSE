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

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sestutorial/externals/styles/styles.css'); ?>
<div class="sestutorial_category_view sestutorial_clearfix sestutorial_bxs">
  <ul class="row justify-content-center">
    <?php foreach($this->resultcategories as $resultcategorie): ?>
    <li class="col-lg-<?php echo $this->gridblock; ?> col-md-4 col-sm-6 col-12">
      <div class="category_view_section sestutorial_animation">
        <?php if(@engine_in_array('socialshare', $this->showinformation)): ?>
          <div class="tutorial_social_btns">
            <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $resultcategorie->getHref()); ?>
            <?php  echo $this->partial('_socialShareIcons.tpl', 'sesbasic', array('resource' => $resultcategorie, 'socialshare_enable_plusicon' => $this->widgetParams['socialshare_enable_plusicon'], 'socialshare_icon_limit' => $this->widgetParams['socialshare_icon_limit'])); ?>
          </div>
        <?php endif; ?>
        <a href="<?php echo $resultcategorie->getHref(); ?>" class="sestutorial_linkinherit" style="height:<?php echo $this->mainblockheight ?>px;">
          <p class="category_icon"><img style="width:<?php echo $this->categoryiconwidth ?>px;height:<?php echo $this->categoryiconheight ?>px;" src="<?php echo $resultcategorie->getPhotoUrl(); ?>" /></p>
          <?php if(@engine_in_array('title', $this->showinformation)): ?>
            <p class="category_title"><?php echo $this->translate($resultcategorie->category_name); ?></p>
          <?php endif; ?>
        </a>
      </div>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
