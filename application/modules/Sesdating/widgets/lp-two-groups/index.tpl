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

<div class="sesdating_lp_two_groups">
  <div class="sesdating_lp_groups_inner">
    <?php if($this->heading) { ?>
    <h3><?php echo $this->heading; ?></h3>
    <?php } ?>
    <div class="sesdating_lp_groups">
      <div class="row">
        <?php foreach($this->results as $result) { ?>
        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
          <div class="group_item">
            <div class="left_col">
              <div class="circular_item sesbasic_bg sesbasic_text_light"> <small><?php echo $this->translate("Members") ?></small><span class="count"><?php echo $this->translate(array('%s', '%s', $result->membership()->getMemberCount()),$this->locale()->toNumber($result->membership()->getMemberCount())) ?></span> </div>
              <div class="group_img"> <?php echo $this->itemPhoto($result, 'thumb.profile'); ?> </div>
            </div>
            <div class="right_col"> <span class="name"><a href="<?php echo $result->getHref(); ?>"><?php echo $result->getTitle(); ?></a></span> <span class="desc"><?php echo $result->description; ?></span> <span class="view"><a href="<?php echo $result->getHref(); ?>"><?php echo $this->translate("View Group"); ?> <i class="fa fa-caret-right"></i></a></span> </div>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
