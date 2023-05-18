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
<?php $params = $this->params; ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesdating/externals/styles/styles.css'); ?>
<div class="sesdating_side_options">
  <?php if($params['text1'] && $params['url1']) { ?>
   <a href="<?php echo $params['url1']; ?>" class="side_item">
     <i class="far fa-user-circle"></i>
    <div class="side_item_hover"><?php echo $params['text1']; ?></div>
   </a>
   <?php } ?>
   <?php if($params['text2'] && $params['url2']) { ?>
    <a href="<?php echo $params['url2']; ?>" class="side_item">
     <i class="fa fa-trophy"></i>
     <div class="side_item_hover"><?php echo $params['text2']; ?></div>
   </a>
   <?php } ?>
   <?php if($params['text3'] && $params['url3']) { ?>
    <a href="<?php echo $params['url3']; ?>" class="side_item">
     <i class="fab fa-dropbox"></i>
     <div class="side_item_hover"><?php echo $params['text3']; ?></div>
   </a>
   <?php } ?>
   <?php if($params['text4'] && $params['url4']) { ?>
    <a href="<?php echo $params['url4']; ?>" class="side_item">
     <i class="fa fa-tags"></i>
     <div class="side_item_hover"><?php echo $params['text4']; ?></div>
   </a>
   <?php } ?>
   <?php if($params['text5'] && $params['url5']) { ?>
    <a href="<?php echo $params['url5']; ?>" class="side_item">
     <i class="fa fa-phone"></i>
     <div class="side_item_hover"><?php echo $params['text5']; ?></div>
   </a>
   <?php } ?>
</div>
