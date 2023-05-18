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
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesdating/externals/styles/lp-two.css'); ?>

<div class="sesdating_lp_two_stories">
  <div class="sesdating_lp_stories_inner">
    <h3><?php echo $this->translate($this->heading); ?></h3>
    <div class="sesdating_lp_stories">
      <div class="row justify-content-center">
        <?php foreach($this->results as $result) { ?>
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
          <div class="story_item">
            <div class="story_img"> <?php echo $this->itemPhoto($result, 'thumb.profile'); ?> </div>
            <div class="story_view"> <a href="<?php echo $result->getHref(); ?>"><i class="fa fa-play"></i></a> </div>
            <div class="story_desc">
              <h4><?php echo $result->getTitle(); ?></h4>
              <a href="<?php echo $result->getHref(); ?>"><i class="fa fa-chevron-right"></i><?php echo $this->translate("View More"); ?></a> </div>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
