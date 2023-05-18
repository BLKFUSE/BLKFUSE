<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php if(!$this->is_search_ajax){ ?>
<div class="sesbasic_dashboard_content_header sesbasic_clearfix">
  <h3><?php echo $this->translate("Manage Orders"); ?></h3>
  <br />
  <p><?php echo $this->translate('Below, you can manage the orders for your packages. You can use this page to monitor these orders. Entering criteria into the filter fields will help you find specific order.'); ?></p>
  <br />
</div>
<div class="epaidcontent_dashboard_search">
  <?php echo $this->searchForm->render($this); ?>
</div>
<br />
<?php } ?>
<div id="epaidcontent_manage_order_content">
<div class="sesbasic_dashboard_search_result">
<b><?php echo $this->paginator->getTotalItemCount().$this->translate(' order(s) found.'); ?></b>
</div>
<br />
<?php if($this->paginator->getTotalItemCount() > 0): ?>
<?php $defaultCurrency = Engine_Api::_()->epaidcontent()->defaultCurrency(); ?>
<div class="sesbasic_dashboard_table sesbasic_bxs">
  <form id='multidelete_form' method="post">
    <table class="epaidcontent_manage_table">
      <thead>
        <tr>
          <th><?php echo $this->translate("Order ID"); ?></th>
          <th><?php echo $this->translate("Buyer") ?></th>
          <th><?php echo $this->translate("Email") ?></th>
          <th><?php echo $this->translate("Order Total") ?></th>
          <th><?php echo $this->translate("Commission") ?></th>
          <th><?php echo $this->translate("Status") ?></th>
          <th><?php echo $this->translate("Gateway") ?></th>
          <th><?php echo $this->translate("Order Date") ?></th>
          <th><?php echo $this->translate("Options") ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($this->paginator as $item): ?>
        <tr>
        	<?php $package = Engine_Api::_()->getItem('epaidcontent_package', $item->package_id); ?>
          <td data-label=" <?php echo $this->translate("Order ID") ?> ">
          	<a class="sessmoothbox" href="<?php echo $this->url(array('package_id' => $package->package_id,'action'=>'view','order_id'=>$item->order_id), 'epaidcontent_order', true).'?order=view'; ?>"><?php echo '#'.$item->order_id; ?></a>
          </td>
          <td data-label=" <?php echo $this->translate("Buyer") ?> " class='epaidcontent_manage_table_bold'>
              <?php $user = Engine_Api::_()->getItem('user',$item->owner_id); ?>
              <a href="<?php echo $user->getHref(); ?>"><?php echo $user->getTitle(); ?></a>
          </td>
          <td  data-label=" <?php echo $this->translate("Email") ?> " title="<?php echo $user->email; ?>"><?php echo $user->email ? $this->string()->truncate($user->email, 7) : '-'; ?></td>
          <td data-label=" <?php echo $this->translate("Order Total") ?> " class='epaidcontent_manage_table_price'><?php echo Engine_Api::_()->epaidcontent()->getCurrencyPrice(round($item->total_amount,2),$defaultCurrency); ?></td>
          <td data-label=" <?php echo $this->translate("Commission") ?> "><?php echo Engine_Api::_()->epaidcontent()->getCurrencyPrice($item->commission_amount,$defaultCurrency); ?></td>
          <td data-label=" <?php echo $this->translate("Status") ?> "><?php echo $item->state; ?></td> 
          <td data-label=" <?php echo $this->translate("Gateway") ?> "><?php echo $item->gateway_type; ?></td> 
          <td data-label=" <?php echo $this->translate("Order Date") ?> " title="<?php echo Engine_Api::_()->epaidcontent()->dateFormat($item->creation_date); ?>"><?php echo $this->string()->truncate(Engine_Api::_()->epaidcontent()->dateFormat($item->creation_date), 10); ?></td> 
          <td data-label=" <?php echo $this->translate("Options") ?> " class="epaidcontent_manage_table_options">
            <a href="<?php echo $this->url(array('package_id' => $package->package_id,'action'=>'view','order_id'=>$item->order_id), 'epaidcontent_order', true).'?order=view'; ?>" class="sessmoothbox"><i class=" fa fa-eye"></i> <?php echo $this->translate("") ?></a>
          	<a href="<?php echo $this->url(array('action' => 'view', 'order_id' => $item->order_id, 'package_id' => $package->package_id,'format'=>'smoothbox'), 'epaidcontent_order', true); ?>" target="_blank"><i class=" fa fa-print"></i> <?php echo $this->translate("") ?></a>            
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
   </form>
</div>
<?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "epaidcontent"),array('identityWidget'=>'manage_order')); ?>
<?php else: ?>
<div class="tip">
  <span>
    <?php echo $this->translate("No order has been placed yet.") ?>
  </span>
</div>
<?php endif; ?>
</div>
<script type="application/javascript">
var requestPagging;
function paggingNumbermanage_order(pageNum){
	 scriptJquery('.sesbasic_loading_cont_overlay').css('display','block');
	 var searchFormData = scriptJquery('#epaidcontent_search_ticket_search').serialize();
		requestPagging= (scriptJquery.ajax({
      dataType: 'html',
			method: 'post',
			'url': en4.core.baseUrl + "widget/index/mod/epaidcontent/name/manage-orders",
			'data': {
				format: 'html',
				searchParams :searchFormData, 
				is_search_ajax:true,
				is_ajax : 1,
				page:pageNum,
				package_owner_id:<?php echo $this->user_id; ?>,
			},
			success: function(responseHTML) {
				scriptJquery('.sesbasic_loading_cont_overlay').css('display','none');
				scriptJquery('#epaidcontent_manage_order_content').html(responseHTML);
			}
		}));
		return false;
}
</script>
<?php if($this->is_search_ajax) die; ?>
<script type="application/javascript">
function executeAfterLoad(){
	if(!scriptJquery('#date-date_to').length )
		return;
	var FromEndDateOrder;
	var selectedDateOrder =  new Date(scriptJquery('#date-date_to').val());
	scriptJquery('#date-date_to').datepicker({
			format: 'yyyy-m-d',
			weekStart: 1,
			autoclose: true,
			endDate: FromEndDateOrder, 
	}).on('changeDate', function(ev){
		selectedDateOrder = ev.date;	
		scriptJquery('#date-date_from').datepicker('setStartDate', selectedDateOrder);
	});
	scriptJquery('#date-date_from').datepicker({
			format: 'yyyy-m-d',
			weekStart: 1,
			autoclose: true,
			startDate: selectedDateOrder,
	}).on('changeDate', function(ev){
		FromEndDateOrder	= ev.date;	
		 scriptJquery('#date-date_to').datepicker('setEndDate', FromEndDateOrder);
	});	
}
scriptJquery(document).on('click','.epaidcontent_search_ticket_search',function(e){
	e.prpageDefault();
	sendParamInSearch = scriptJquery(this).attr('data-rel');
	scriptJquery('#epaidcontent_search_ticket_search').trigger('click');
});
scriptJquery('#loadingimgepaidcontent-wrapper').hide();
</script>
