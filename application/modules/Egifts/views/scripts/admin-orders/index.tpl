<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2020-06-13 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/datepicker/bootstrap-datepicker.css'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/datepicker/jquery.timepicker.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/datepicker/bootstrap-datepicker.js'); ?>
<style>
	#date-date_to{display:block !important;}
	#date-date_from{display:block !important;}
</style>
<script type="text/javascript">
  var currentOrder = '<?php echo $this->order ?>';
  var currentOrderDirection = '<?php echo $this->order_direction ?>';
  var changeOrder = function(order, default_direction){
    // Just change direction
    if( order == currentOrder ) {
      document.getElementById('order_direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
    } else {
      document.getElementById('order').value = order;
      document.getElementById('order_direction').value = default_direction;
    }
    scriptJquery('#filter_form').trigger('submit');
  }

  function multiDelete() {
    return confirm("<?php echo $this->translate('Are you sure you want to delete the selected tickets?');?>");
  }

	function selectAll() {
		var i;
		var multidelete_form = document.getElementById('multidelete_form');
		var inputs = multidelete_form.elements;
		for (i = 1; i < inputs.length - 1; i++) {
			inputs[i].checked = inputs[0].checked;
		}
	}

</script>
<?php include APPLICATION_PATH .  '/application/modules/Egifts/views/scripts/dismiss_message.tpl';?>
<div>
<h3><?php echo $this->translate("Manage Orders") ?></h3>
<p><?php echo $this->translate('This page lists all of the orders which are purchased from your website. You can use this page to monitor these orders. Entering criteria into the filter fields will help you find specific ticket order. Leaving the filter fields blank will show all the orders on your social network.'); ?></p>
<br />
    <?php $defaultCurrency = Engine_Api::_()->egifts()->defaultCurrency(); ?>
    <div class='admin_search egifts_search_form'>
      <?php echo $this->formFilter->render($this) ?>
    </div>
    <br />
    <?php $counter = $this->paginator->getTotalItemCount(); ?>
    <?php if( engine_count($this->paginator)): ?>
      <div class="sesbasic_search_reasult">
        <?php echo $this->translate(array('%s order found.', '%s orders found.', $counter), $this->locale()->toNumber($counter)) ?>
      </div>
        <div class="clear" style="overflow: auto;">
         <form id='multidelete_form' method="post">
        <table class='admin_table'>
          <thead>
            <tr>
              <th class='admin_table_short'><a href="javascript:void(0);" onclick="javascript:changeOrder('giftpurchase_id', 'DESC');"><?php echo $this->translate("ID") ?></a></th>
              <th><?php echo $this->translate("Owner Name") ?></th>
              <th class="admin_table_centered"><?php echo $this->translate("Status") ?></th>
              <th class="admin_table_centered"><?php echo $this->translate("Total Amount"); ?></th>
              <th><?php echo $this->translate("Options") ?></th>
            </tr>
          </thead>
        
          <tbody>
            <?php foreach ($this->paginator as $item): ?>
            <tr>
            <td><?php echo $item->giftpurchase_id; ?></td>
            <td>
              <?php
                echo Engine_Api::_()->getItem('user',$item->owner_id);
              ?>
            </td>
            <td class="admin_table_centered">
              <?php echo $item->state ? $item->state : 'processing'; ?>
            </td>
            <td class="admin_table_centered">
              <?php echo Engine_Api::_()->egifts()->getCurrencyPrice(round($item->total_amount,2),$defaultCurrency); ?>
            </td>
            <td>
                <?php echo $this->htmlLink($this->url(array('action'=>'print', "module" => 'egifts',"controller" => 'orders','giftpurchase_id'=>$item->giftpurchase_id), 'admin_default', true).'?order=view', $this->translate("View Order"), array('title' => $this->translate("View Order"), 'class' => 'smoothbox')); ?>
                |
                <?php echo $this->htmlLink($this->url(array('action'=>'view', "module" => 'egifts',"controller" => 'orders','giftpurchase_id'=>$item->giftpurchase_id), 'admin_default', true), $this->translate("Print Invoice"), array('title' => $this->translate("Print Invoice"), 'target' => '_blank')); ?>
            </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table><br/>
   </form>
</div>
  <br/>
  <div>
    <?php echo $this->paginationControl($this->paginator); ?>
  </div>
<?php else:?>
  <div class="tip">
    <span>
      <?php echo $this->translate("Nobody has placed an order yet on your website.") ?>
    </span>
  </div>
<?php endif; ?>
</div>
</div>
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
executeAfterLoad();

  function changeOrderStatus(giftpurchase_id,object) {
      scriptJquery('img_'+giftpurchase_id).show();
      var formData = new FormData(scriptJquery(object).closest('form')[0]);
      var form = scriptJquery(object);
      scriptJquery.ajax({
          type:'POST',
          dataType:'html',
          url: 'admin/egifts/orders/change-order-status/giftpurchase_id/'+giftpurchase_id,
          data:formData,
          cache:false,
          contentType: false,
          processData: false,
          success:function(response){
              scriptJquery('img_'+giftpurchase_id).hide();
              var data = scriptJquery.parseJSON(response);
              if(data.status == 1){
                  scriptJquery(object).closest('td').html(data.message);
              }else{
                  alert('Something went wrong, please try again later');
              }
          },
          error: function(data){
              //silence
              scriptJquery('img_'+giftpurchase_id).hide();
              alert('Something went wrong, please try again later');
          }
      });
  }
  scriptJquery(document).on('click','.egifts_change_type',function () {
    if(scriptJquery(this).hasClass('active')){
        scriptJquery(this).removeClass('active');
        scriptJquery(this).parent().parent().find('form').hide();
        return;
    }else{
        scriptJquery(this).addClass('active');
        scriptJquery(this).parent().parent().find('form').show();
        return;
    }
  });
</script>
