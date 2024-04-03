<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: success.tpl  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontestjoinfees/externals/styles/styles.css'); ?>

<div class="layout_middle">
	<div class="generic_layout_container layout_core_content">
  	<div class="sescontest_fees_process_complete_page sesbasic_bxs">
    	<div class="sescontest_fees_process_complete">
      	<?php if(empty($this->error)){ ?>
        	<i><img src="application/modules/Sescontest/externals/images/success.png" alt="" /></i>
          <span class="_text"><?php echo $this->translate("Your order has been successfully completed."); ?></span>
        <?php }else{ ?>
        	<i><img src="application/modules/Sescontestjoinfees/externals/images/fail.png" alt="" /></i>
          <span class="_text _error"><?php echo $this->error; ?></span>
        <?php } ?>
      </div>
      <div class="sescontest_fees_process_complete_btn sesbasic_bxs sesbasic_clearfix">
        <a href="<?php echo $this->url(array('action'=>'view'), 'sescontestjoinfees_user_order', true); ?>" class="sesbasic_link_btn floatL"><?php echo $this->translate("Go To My Order"); ?></a>
        <a href="<?php echo $this->url(array('action' => 'create', 'contest_id' => $this->contest->contest_id),'sescontest_join_contest','true');?>" class="sesbasic_link_btn floatL"><?php echo $this->translate("Submit Entry"); ?></a>
      </div>
		</div>
  </div>
	</div>
