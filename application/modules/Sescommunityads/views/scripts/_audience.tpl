<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _audience.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<div style="float:left;width:200px;">
  <ul>
    <?php if($this->package->targetting){ ?>
    <?php 
      $counter = 0;
      foreach($this->profileTypes as $profileType){ ?>
    <li class="<?php echo $counter == 0 ? 'sescustom_active' : ''; ?>"> <a href="javascript:;" rel="<?php echo $profileType->getIdentity(); ?>" class="sescustom_field_a"><?php echo $profileType->label; ?></a> </li>
    <?php 
      $counter++;
      } ?>
    <?php } ?>
    <?php if($this->package->networking && engine_count($this->networks)){ ?>
    <li class="<?php echo !$this->package->targetting ? 'sescustom_active' : ''; ?>"> <a href="javascript:;" rel="sescommunity_network_targetting" class="sescustom_field_a"><?php echo $this->translate('Network Targeting'); ?></a> </li>
    <?php } ?>
  </ul>
</div>
<?php //display all the profile fields here; ?>
<div style="float:left; widows:600px;">
  <?php if($this->package->targetting){ ?>
  <?php echo $this->formField->render($this); ?>
  <?php } ?>
  <?php if($this->package->networking && engine_count($this->networks)){ ?>
  <div class="sescommunity_network_targetting">
    <select multiple name="network[]">
      <?php foreach($this->networks as $network){   ?>
      <option value="<?php echo $network->getIdentity(); ?>"><?php echo $network->getTitle(); ?></option>
      <?php } ?>
    </select>
  </div>
  <?php } ?>
</div>
<div class="sesbasic_clearfix"></div>
<div class="sescommmunity_popup_footer sesbasic_bg sesbasic_clearfix">
  <div class="_left"> <a href="javascript:;" class="sesbasic_button" onclick="sescomm_back_btn(3);"><?php echo $this->translate('Back'); ?></a> </div>
  <div class="_left"> <a href="javascript:;" class="sesbasic_button" onclick="sescomm_back_btn(5);"><?php echo $this->translate('Next'); ?></a> </div>
  <div class="_right"></div>
</div>
