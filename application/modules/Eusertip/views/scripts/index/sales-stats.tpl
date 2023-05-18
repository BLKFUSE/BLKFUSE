<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: sales-stats.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eusertip/externals/styles/style.css'); ?>

<div class="eusertip_dashboard_content sesbm sesbasic_clearfix">
  <?php $defaultCurrency = Engine_Api::_()->eusertip()->defaultCurrency(); ?>
  <div class="eusertip_dashboard_content_header sesbasic_clearfix">
    <h3><?php echo $this->translate("Sales Stats"); ?></h3>
  </div>
  <div class="eusertip_db_sale_stats_container sesbasic_bxs sesbasic_clearfix">
  	<div class="eusertip_db_sale_stats">
    	<section>
        <span><?php echo $this->translate("Today"); ?></span>
        <span><?php echo Engine_Api::_()->eusertip()->getCurrencyPrice($this->todaySale,$defaultCurrency); ?></span>
    	</section>
    </div>
  	<div class="eusertip_db_sale_stats">
			<section>
        <span><?php echo $this->translate("This Week"); ?></span>
        <span><?php echo Engine_Api::_()->eusertip()->getCurrencyPrice($this->weekSale,$defaultCurrency); ?></span>
    	</section>
    </div>
  	<div class="eusertip_db_sale_stats">
			<section>
        <span><?php echo $this->translate("This Month"); ?></span>
        <span><?php echo Engine_Api::_()->eusertip()->getCurrencyPrice($this->monthSale,$defaultCurrency); ?></span>
    	</section>
    </div>
  </div>
  
  <div class="eusertip_db_dashboard_ticket_statics sesbasic_bxs sesbasic_clearfix">
   <div class="eusertip_dashboard_content_header sesbasic_clearfix">
      <h3><?php echo $this->translate("Statistics"); ?></h3>
    </div>
    <div class="eusertip_db_sale_stats_container sesbasic_bxs sesbasic_clearfix">
      <div class="eusertip_db_sale_stats">
      	<section>
      		<span><?php echo $this->translate("Total Order"); ?></span>
          <span><?php echo $this->tipStatsSale['totalOrder'] ?></span>
      	</section>
      </div>
      <div class="eusertip_db_sale_stats">
      	<section>
          <span><?php echo $this->translate("Total Commission Amount"); ?></span>
          <span><?php echo Engine_Api::_()->eusertip()->getCurrencyPrice($this->tipStatsSale['commission_amount'],$defaultCurrency) ?> </span>
				</section>
      </div>
    </div>
  </div>
</div>
