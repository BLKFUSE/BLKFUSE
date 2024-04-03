<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php if(!$this->is_search_ajax){ ?>
<div class="sesbasic_dashboard_content_header sesbasic_clearfix">
  <h3><?php echo $this->translate("Manage Entry Orders"); ?></h3>
  <p><?php echo $this->translate('Below, you can manage the entry orders for this contest. You can use this page to monitor these orders. Entering criteria into the filter fields will help you find specific order.'); ?></p>
</div>
<div class="sesbasic_browse_search sesbasic_browse_search_horizontal sesbasic_dashboard_search_form">
  <?php echo $this->searchForm->render($this); ?>
</div>
<?php } ?>
<div id="sescontest_manage_order_content">
<div class="sesbasic_dashboard_search_result">
	<?php echo $this->paginator->getTotalItemCount().$this->translate(' order(s) found.'); ?>
</div>
<?php if($this->paginator->getTotalItemCount() > 0): ?>
<?php $defaultCurrency = Engine_Api::_()->sescontestjoinfees()->defaultCurrency(); ?>
<div class="sesbasic_dashboard_table sesbasic_bxs">
  <form id='multidelete_form' method="post">
    <table>
      <thead>
        <tr>
          <th class="centerT"><?php echo $this->translate("Order ID"); ?></th>
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
        	<?php $contest = Engine_Api::_()->getItem("contest", $item->contest_id); ?>
          <td class="centerT">
          	<a class="openSmoothbox" href="<?php echo $this->url(array('contest_id' => $contest->custom_url,'action'=>'view','order_id'=>$item->order_id), 'sescontestjoinfees_order', true).'?order=view'; ?>"><?php echo '#'.$item->order_id; ?></a>
          </td>
          <td>
              <?php $user = Engine_Api::_()->getItem('user',$item->owner_id); ?>
              <a href="<?php echo $user->getHref(); ?>"><?php echo $user->getTitle(); ?></a>
          </td>
          <td title="<?php echo $user->email; ?>"><?php echo $user->email ? $this->string()->truncate($user->email, 7) : '-'; ?></td>
          <td><?php echo Engine_Api::_()->sescontestjoinfees()->getCurrencyPrice(round($item->total_amount,2),$defaultCurrency); ?></td>
          <td><?php echo Engine_Api::_()->sescontestjoinfees()->getCurrencyPrice($item->commission_amount,$defaultCurrency); ?></td>
          <td><?php echo $item->state; ?></td> 
          <td><?php echo $item->gateway_type; ?></td> 
          <td title="<?php echo Engine_Api::_()->sescontestjoinfees()->dateFormat($item->creation_date); ?>"><?php echo $this->string()->truncate(Engine_Api::_()->sescontestjoinfees()->dateFormat($item->creation_date), 10); ?></td> 
          <td class="table_options">
            <a href="<?php echo $this->url(array('contest_id' => $contest->custom_url,'action'=>'view','order_id'=>$item->order_id), 'sescontestjoinfees_order', true).'?order=view'; ?>" class="openSmoothbox"><i class=" fa fa-eye"></i> <?php echo $this->translate("View Order") ?></a>
          	<a href="<?php echo $this->url(array('action' => 'view', 'order_id' => $item->order_id, 'contest_id' => $contest->custom_url,'format'=>'smoothbox'), 'sescontestjoinfees_order', true); ?>" target="_blank"><i class=" fa fa-print"></i> <?php echo $this->translate("Print Invoice") ?></a>            
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
   </form>
</div>
<?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sescontest"),array('identityWidget'=>'manage_order')); ?>
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
	 var searchFormData = scriptJquery('#sescontest_search_ticket_search').serialize();
		requestPagging= (scriptJquery.ajax({
			method: 'post',
			'url': en4.core.baseUrl + "widget/index/mod/sescontest/name/manage-orders",
			'data': {
				format: 'html',
				searchParams :searchFormData, 
				is_search_ajax:true,
				is_ajax : 1,
				page:pageNum,
				contest_id:<?php echo $this->contest_id; ?>,
			},
			success: function(responseHTML) {
				scriptJquery('.sesbasic_loading_cont_overlay').css('display','none');
				scriptJquery('#sescontest_manage_order_content').html(responseHTML);
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
scriptJquery(document).on('click','.sescontest_search_ticket_search',function(e){
	e.prcontestDefault();
	sendParamInSearch = scriptJquery(this).attr('data-rel');
	scriptJquery('#sescontest_search_ticket_search').trigger('click');
});
scriptJquery('#loadingimgsescontest-wrapper').hide();
</script>
