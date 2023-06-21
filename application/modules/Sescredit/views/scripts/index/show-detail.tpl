<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescredit
 * @package    Sescredit
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: show-detail.tpl  2019-01-18 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl') . 'application/modules/Sescredit/externals/scripts/core.js'); ?>

<div class="sescredit_transactions_details_popup sesbasic_bxs">
	<ul>
		<li>
			<span><?php echo $this->translate("Credit Value:");?></span>
			<span><?php echo $this->creditDetail->credit;?></span>
		</li>
		<li>
			<span><?php echo $this->translate("Date:");?></span>
			<span><?php echo $this->creditDetail->creation_date;?></span>
		</li>
		<li>
			<span><?php echo $this->translate("Credit Type:");?></span>
			<span>
				<?php if($this->creditDetail->type == 'sescredit_affiliate'):?>
					<?php echo $this->translate("These points are received for inviter referral.");?>
				<?php elseif($this->creditDetail->type == 'transfer_to_friend'):?>
					<?php echo $this->translate("Points transferred to friends.");?>
				<?php elseif($this->creditDetail->type == 'receive_from_friend'):?>
					<?php echo $this->translate("Points received from your friend");?>
				<?php elseif($this->creditDetail->type):?>
          <?php $type = 'ADMIN_ACTIVITY_TYPE_'.strtoupper($this->creditDetail->type); ?>
          <?php echo str_replace(array('(subject)','(object)',' .'),array('','','.'),$this->translate($type));?>
				<?php endif;?>
			</span>	
		</li>
	</ul>
</div>
