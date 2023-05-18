<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: success.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eusertip/externals/styles/style.css'); ?>

<div class="layout_middle">
	<div class="generic_layout_container layout_core_content">
  	<div class="eusertip_fees_process_complete_page sesbasic_bxs">
    	<div class="eusertip_fees_process_complete">
      	<?php if(empty($this->error)){ ?>
        	<i><img src="application/modules/Eusertip/externals/images/success.png" alt="" /></i>
          <span class="_text"><?php echo $this->translate("Your order has been successfully completed."); ?></span>
        <?php }else{ ?>
        	<i><img src="application/modules/Eusertip/externals/images/fail.png" alt="" /></i>
          <span class="_text _error"><?php echo $this->error; ?></span>
        <?php } ?>
      </div>
      <div class="eusertip_fees_process_complete_btn sesbasic_bxs sesbasic_clearfix">
        <a href="<?php echo $this->url(array('action'=>'my-orders'), 'eusertip_general', true); ?>" class="sesbasic_link_btn floatL"><?php echo $this->translate("Go To My Order"); ?></a>
      </div>
		</div>
  </div>
</div>
