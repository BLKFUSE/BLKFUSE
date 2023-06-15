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

<div class="sescontest_view_sidebar_labels sesbasic_bxs sesbasic_clearfix">
  <?php if(engine_in_array('verified',$this->option) && isset($this->subject->verified) && $this->subject->verified){ ?>
    <span class="label_verified" title="<?php echo $this->translate('Verified') ;?>"><i class="fa fa-check"></i></span>
  <?php } ?>
  <?php if(engine_in_array('featured',$this->option) && $this->subject->featured){ ?>
    <span class="sescontest_label_featured" title="<?php echo $this->translate('Featured') ;?>"><i class="fa fa-star"></i></span>
  <?php } ?>
  <?php if(engine_in_array('sponsored',$this->option) && $this->subject->sponsored){ ?>
    <span class="sescontest_label_sponsored" title="<?php echo $this->translate('Sponsored') ;?>"><i class="fa fa-star"></i></span>
  <?php } ?>
  <?php if(engine_in_array('hot',$this->option) && $this->subject->hot){ ?>
    <span class="sescontest_label_hot" title="<?php echo $this->translate('Hot') ;?>"><i class="fa fa-star"></i></span>
  <?php } ?>
</div>
