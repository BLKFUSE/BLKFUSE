<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _hoddenData.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php if($ad->user_id == $this->viewer()->getIdentity()){
        return;
}
  //}else if(!$this->viewer()->getIdentity()) return; ?>
<div class="sescomm_report_success" style="display:none;">
  <span class="success_img"><img src="application/modules/Sescommunityads/externals/images/success.png" alt="" /></span>
  <span class="success_message"><?php echo $this->translate('Thanks for your feedback. Your report has been submitted.'); ?></span>
</div>
<div class="sescmads_hidden_ad" style="display:none;">
  <div class="_ad_hidden_head sesbasic_clearfix">
    <span class="floatL"><?php echo $this->translate('Ad hidden') ; ?></span>
    <span class="floatR"><a href="javascript:;" class="sescomm_undo_ad"><?php echo $this->translate('Undo') ; ?></a></span>
  </div>  
  <p><?php echo $this->translate('You Won\'t See this ad and ads like this.') ; ?></p>
  <p><?php echo $this->translate('Why did you hide it?') ; ?></p>
  <div class="_ad_hidden_options">
    <?php $dataIdentity = $ad->getIdentity(); ?>
    <span class="_ad_option">
       <input type="radio" id="ad-option-spam<?php echo $dataIdentity; ?>" name="ad-option-spam" value="Offensive" />
       <label for="ad-option-spam<?php echo $dataIdentity; ?>"><?php echo $this->translate('Offensive') ; ?></label>
    </span>
    <span class="_ad_option">
       <input type="radio" id="ad-option-misleasing<?php echo $dataIdentity; ?>" name="ad-option-spam" value="Misleading" />
       <label for="ad-option-spam<?php echo $dataIdentity; ?>"><?php echo $this->translate('Misleading') ; ?></label>
    </span>
    <span class="_ad_option">
       <input type="radio" id="ad-option-inappropriate<?php echo $dataIdentity; ?>" name="ad-option-spam" value="Inappropriate" />
       <label for="ad-option-spam<?php echo $dataIdentity; ?>"><?php echo $this->translate('Inappropriate') ; ?></label>
    </span>
    <span class="_ad_option">
       <input type="radio" id="ad-option-licensed<?php echo $dataIdentity; ?>" name="ad-option-spam" value="Licensed Material" />
       <label for="ad-option-spam<?php echo $dataIdentity; ?>"><?php echo $this->translate('Licensed Material') ; ?></label>
    </span>
    <span class="_ad_option">
       <input type="radio" id="ad-option-other<?php echo $dataIdentity; ?>" name="ad-option-spam" value="Other" />
       <label for="ad-option-other<?php echo $dataIdentity; ?>"><?php echo $this->translate('Other') ; ?></label>
    </span>
    <span class="_ad_option sescomm_other" style="display:none;">
       <textarea type="text" name="other-text" value="" placeholder="" onClick="if(this.value == '<?php echo $this->translate('Specify your reason here..'); ?>'){ this.value = ''; }" onBlur="if(this.value == ''){this.value = '<?php echo $this->translate('Specify your reason here..'); ?>';}" ><?php echo $this->translate('Specify your reason here..'); ?></textarea><br />
       <button type="button" class="sescomm_report_other_smt" value="<?php echo $this->translate('Report'); ?>"><?php echo $this->translate('Report'); ?></button>
    </span>
  </div>
</div>