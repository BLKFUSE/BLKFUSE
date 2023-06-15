<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesthought/externals/scripts/core.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesthought/externals/styles/styles.css'); ?>
<?php $allParams = $this->allParams; ?>
<?php if($allParams['title']) { ?>
<div class="sesthought"><?php echo $allParams['title']; ?></div>
<?php } ?>
<div class="row sesbasic_clearfix clear sesbasic_bxs">
  <?php foreach( $this->paginator as $item ): ?>
  <div class="col-lg-<?php echo $this->gridblock; ?> col-md-3 col-sm-6 col-6">
    <div class="sesthought_cat_iconlist">
      <a href="<?php echo $this->url(array('action' => 'index'), 'sesthought_general').'?category_id='.$item->category_id; ?>">
        <span class="sesthought_cat_iconlist_icon">
        <?php if($item->cat_icon != '' && !is_null($item->cat_icon) && intval($item->cat_icon)){ ?>
        <?php $cat_icon = Engine_Api::_()->storage()->get($item->cat_icon); ?>
        <?php if($cat_icon) { ?>
        <img src="<?php echo  Engine_Api::_()->storage()->get($item->cat_icon)->getPhotoUrl(); ?>" style="height:<?php echo is_numeric($allParams['heighticon']) ? $allParams['heighticon'].'px' : $allParams['heighticon'] ?>; width:<?php echo is_numeric($allParams['widthicon']) ? $allParams['widthicon'].'px' : $allParams['widthicon'] ?>;" />
        <?php } else { ?>
        <?php } ?>
        <?php } else { ?>
        <?php } ?>
        </span>
        <?php if(engine_in_array('title', $allParams['showStats'])){ ?>
        <span class="sesthought_cat_iconlist_title"><?php echo $this->translate($item->category_name); ?></span>
        <?php } ?>
        <?php if(engine_in_array('countThoughts', $allParams['showStats'])){ ?>
        <span class="sesthought_cat_iconlist_count sesbasic_text_light"><?php echo $this->translate(array('%s thought', '%s thoughts', $item->total_thought_categories), $this->locale()->toNumber($item->total_thought_categories))?></span>
        <?php } ?>
      </a>
    </div>
  </div>
  <?php endforeach; ?>
</div>
