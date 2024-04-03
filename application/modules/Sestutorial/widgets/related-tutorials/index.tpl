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

<div class="sestutorial_sidebar_list sestutorial_clearfix sestutorial_bxs">
  <?php foreach($this->tutorials as $tutorial): ?>
    <div class="sestutorial_sidebar_list_item">
      <div class="sestutorial_sidebar_list_title">
        <a href="<?php echo $tutorial->getHref(); ?>" title="<?php echo $tutorial->title; ?>"><i class="far fa-file-alt"></i> <span><?php echo $this->string()->truncate($this->string()->stripTags($tutorial->title), $this->tutorialtitlelimit); ?></span></a>
      </div>
      <?php if($this->showinformation) { ?>
      <?php if(engine_in_array('viewcount', $this->showinformation) || engine_in_array('commentcount', $this->showinformation) || engine_in_array('likecount', $this->showinformation) || engine_in_array('ratingcount', $this->showinformation)): ?>
        <div class="sestutorial_sidebar_list_stats">
          <?php if(is_array($this->showinformation) && engine_in_array('viewcount', $this->showinformation)): ?>
            <p title="<?php echo $this->translate(array('%s view', '%s views', $tutorial->view_count), $this->locale()->toNumber($tutorial->view_count)); ?>"><i class="fa fa-eye"></i> <?php echo $tutorial->view_count; ?></p>
          <?php endif; ?>
          <?php if(is_array($this->showinformation) && engine_in_array('commentcount', $this->showinformation)): ?>
            <p title="<?php echo $this->translate(array('%s comment', '%s comments', $tutorial->comment_count), $this->locale()->toNumber($tutorial->comment_count)); ?>"><i class="far fa-comment"></i> <?php echo $tutorial->comment_count; ?></p>
          <?php endif; ?>
          <?php if(engine_in_array('likecount', $this->showinformation)): ?>
            <p title="<?php echo $this->translate(array('%s like', '%s likes', $tutorial->like_count), $this->locale()->toNumber($tutorial->like_count)); ?>"><i class="far fa-thumbs-up"></i> <?php echo $tutorial->like_count; ?></p>
          <?php endif; ?>
          <?php if(is_array($this->showinformation) && engine_in_array('ratingcount', $this->showinformation)): ?>
            <p title="<?php echo $this->translate(array('%s rating', '%s ratings', $tutorial->rating), $this->locale()->toNumber($tutorial->rating)); ?>"><i class="far fa-star"></i> <?php echo $tutorial->rating; ?></p>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <?php } ?>
      <?php if($this->showinformation && engine_in_array('description', $this->showinformation)): ?>
        <div class="sestutorial_sidebar_list_discrtiption">
          <p><?php echo $this->string()->truncate($this->string()->stripTags($tutorial->description), $this->tutorialdescriptionlimit); ?> </p>
        </div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>
