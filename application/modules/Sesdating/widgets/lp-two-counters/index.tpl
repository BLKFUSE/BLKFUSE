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
<?php $allParams = $this->allParams; ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesdating/externals/styles/lp-two.css'); ?>

<div class="sesdating_lp_two_counters">
<div class="sesdating_counters_inner">
  <div class="counters_top">
    <?php if($allParams['heading']) { ?>
    <h3><?php echo $allParams['heading']; ?></h3>
    <?php } ?>
    <?php if($allParams['description']) { ?>
    <p class="sesbasic_text_light"><?php echo $allParams['description']; ?></p>
    <?php } ?>
    <?php if($allParams['button1text']) { ?>
    <a href="<?php echo $allParams['button1link']; ?>"><?php echo $allParams['button1text']; ?></a>
    <?php } ?>
    <?php if($allParams['heading']) { ?>
    <a href="<?php echo $allParams['button2link']; ?>"><?php echo $allParams['button2text']; ?></a>
    <?php } ?>
  </div>
  <div class="counters_bottom">
    <div class="row">
      <?php if($allParams['counter1value']) { ?>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="counter_item"> <span class="icon"><img src="application/modules/Sesdating/externals/images/members.png" /></span> <span class="counter"><?php echo $allParams['counter1value']; ?></span> <span class="name sesbasic_text_light"><?php echo $this->translate("Members in Total"); ?></span> </div>
      </div>
      <?php } ?>
      <?php if($allParams['counter2value']) { ?>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="counter_item"> <span class="icon"><img src="application/modules/Sesdating/externals/images/online.png" /></span> <span class="counter"><?php echo $allParams['counter2value']; ?></span> <span class="name sesbasic_text_light"><?php echo $this->translate("Members Online"); ?></span> </div>
      </div>
      <?php } ?>
      <?php if($allParams['counter3value']) { ?>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="counter_item"> <span class="icon"><img src="application/modules/Sesdating/externals/images/woman.png" /></span> <span class="counter"><?php echo $allParams['counter3value']; ?></span> <span class="name sesbasic_text_light"><?php echo $this->translate("Women online"); ?></span> </div>
      </div>
      <?php } ?>
      <?php if($allParams['counter4value']) { ?>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="counter_item"> <span class="icon"><img src="application/modules/Sesdating/externals/images/man.png" /></span> <span class="counter"><?php echo $allParams['counter4value']; ?></span> <span class="name sesbasic_text_light"><?php echo $this->translate("Men online"); ?></span> </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
</div>
<?php  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesdating/externals/scripts/jquery.min.js'); ?>
<script type="text/javascript">
    counteratoz(document).ready(function($) {
        $('.counter').counterUp({
            delay: 10,
            time: 1000
        });
    });
</script>
<?php  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesdating/externals/scripts/waypoints.min.js'); ?>
<?php  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesdating/externals/scripts/jquery.counterup.min.js'); ?>
