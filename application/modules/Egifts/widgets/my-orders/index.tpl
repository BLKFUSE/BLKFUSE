<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Sespagejoin
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php if(!$this->is_search_ajax){ ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Egifts/externals/styles/styles.css'); ?>
<div class="sesbasic_dashboard_content_header sesbasic_clearfix">
  <h3><?php echo $this->translate("My Gift Orders"); ?></h3>
  <p><?php echo $this->translate('Below, you can manage the gift orders. You can use this page to monitor these orders. Entering criteria into the filter fields will help you find specific order.'); ?></p>
  <br />
</div>
<div class="egifts_dashboard_search">
  <?php echo $this->searchForm->render($this); ?>
</div>
<?php } ?>
<div id="egifts_manage_order_content">

<?php if($this->paginator->getTotalItemCount() > 0): ?>
	<div class="egifts_dashboard_search_result">
		<?php echo $this->paginator->getTotalItemCount(). $this->translate(' order(s) found.'); ?>
	</div>
	<?php $defaultCurrency = Engine_Api::_()->sesbasic()->defaultCurrency(); ?>
	<div class="egifts_manage_table">
		<form id='multidelete_form' method="post">
			<table class="egifts_manage_table">
				<thead>
					<tr>
						<th><?php echo $this->translate("Order ID"); ?></th>
						<th><?php echo $this->translate("Gift Name"); ?></th>
						<th><?php echo $this->translate("Order Total") ?></th>
						<th><?php echo $this->translate("Status") ?></th>
						<th><?php echo $this->translate("Order Date") ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->paginator as $item): ?>
					<tr>
						<?php $gift = Engine_Api::_()->getItem('egifts_gift', $item->gift_id); ?>
						<td data-label="<?php echo $this->translate("Order ID") ?>"><?php echo $item->giftpurchase_id; ?></td>
						<td data-label="<?php echo $this->translate("Gift Name") ?>" class='egifts_manage_table_price'><a href="<?php echo $gift->getHref(); ?>"><?php echo $gift->getTitle(); ?></a></td>
						<td data-label=" <?php echo $this->translate("Order Total") ?>" class='egifts_manage_table_price'><?php echo Engine_Api::_()->sesbasic()->getCurrencyPrice(round($item->total_amount,2),$defaultCurrency); ?></td>
						<td data-label=" <?php echo $this->translate("Status") ?>"><?php echo $item->state; ?></td>
						<td data-label=" <?php echo $this->translate("Order Date") ?>"><?php echo Engine_Api::_()->sesbasic()->dateFormat($item->transcation_date); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</form>
	</div>
	<?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "egifts"),array('identityWidget'=>'manage_order')); ?>
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
	 var searchFormData = scriptJquery('#manage_order_search_form').serialize();
		requestPagging= (scriptJquery.ajax({
      dataType: 'html',
			method: 'post',
			'url': en4.core.baseUrl + "widget/index/mod/egifts/name/my-orders",
			'data': {
				format: 'html',
				searchParams :searchFormData, 
				is_search_ajax:true,
				is_ajax : 1,
				page:pageNum,
				joinfees_owner_id:<?php echo $this->user_id; ?>,
			},
			success: function(responseHTML) {
				scriptJquery('.sesbasic_loading_cont_overlay').css('display','none');
				scriptJquery('#egifts_manage_order_content').html(responseHTML);
				scriptJquery('#loadingimgegifts-wrapper').hide();
			}
		}));
		return false;
}
</script>
<?php if($this->is_search_ajax) die; ?>
<script type="application/javascript">
	function executeAfterLoad() {
		if(!scriptJquery('#date-date_to').length )
			return;
		var FromEndDateOrder;
		var selectedDateOrder =  new Date(scriptJquery('#date-date_to').val());
		scriptJquery('#date-date_to').datepicker({
				weekStart: 1,
				autoclose: true,
				endDate: FromEndDateOrder, 
		}).on('changeDate', function(ev){
			selectedDateOrder = ev.date;	
			scriptJquery('#date-date_from').datepicker('setStartDate', selectedDateOrder);
		});
		scriptJquery('#date-date_from').datepicker({
				weekStart: 1,
				autoclose: true,
				startDate: selectedDateOrder,
		}).on('changeDate', function(ev){
			FromEndDateOrder	= ev.date;	
			scriptJquery('#date-date_to').datepicker('setEndDate', FromEndDateOrder);
		});	
	}
	scriptJquery(document).on('submit','#manage_order_search_form',function(e) {
		scriptJquery('#loadingimgegifts-wrapper').show();
		e.preventDefault();
		sendParamInSearch = scriptJquery(this).attr('data-rel');
		paggingNumbermanage_order(1);
	});
	executeAfterLoad();
	scriptJquery('#loadingimgegifts-wrapper').hide();
</script>
