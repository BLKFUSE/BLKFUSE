<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: orders.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php include APPLICATION_PATH .  '/application/modules/Eusertip/views/scripts/dismiss_message.tpl';?>
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
    return confirm("<?php echo $this->translate('Are you sure you want to delete the selected entries?');?>");
  }

function selectAll()
{
  var i;
  var multidelete_form = document.getElementById('multidelete_form');
  var inputs = multidelete_form.elements;
  for (i = 1; i < inputs.length - 1; i++) {
    inputs[i].checked = inputs[0].checked;
  }
}

</script>
<div class='sesbasic-form sesbasic-categories-form'>
  <div>
    <?php if(is_countable($this->subNavigation) && engine_count($this->subNavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subNavigation)->render();?>
      </div>
    <?php endif; ?>
    <div class="sesbasic-form-cont">
    <?php if(is_countable($this->subsubNavigation) && engine_count($this->subsubNavigation) ): ?>
      <div class='tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subsubNavigation)->render();?>
      </div>
    <?php endif; ?>
    <h3><?php echo $this->translate("Manage Orders") ?></h3>
    <p><?php echo $this->translate('This page lists all of the orders paid on your website for tips. You can use this page to monitor these orders. Entering criteria into the filter fields will help you find specific entry order. Leaving the filter fields blank will show all the orders on your social network.'); ?></p>
    <br />
    <div class='admin_search sesbasic_search_form'>
      <?php echo $this->formFilter->render($this) ?>
    </div>
    <br />
    <?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
      <div class="sesbasic_search_reasult">
        <?php echo $this->translate(array('%s order found.', '%s orders found.', $this->paginator->getTotalItemCount()), $this->paginator->getTotalItemCount()) ?>
      </div>
      <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
        <div class="clear" style="overflow: auto;">  
        <table class='admin_table'>
          <thead>
            <tr>
              <th class='admin_table_short'><a href="javascript:void(0);" onclick="javascript:changeOrder('order_id', 'DESC');"><?php echo $this->translate("ID") ?></a></th>
              <th><?php echo $this->translate("Tip Title") ?></th>          
              <th><?php echo $this->translate("Owner Name") ?></th>
              <th class="admin_table_centered"><?php echo $this->translate("Gateway"); ?></th>
              <th class="admin_table_centered"><?php echo $this->translate("Currency") ?></th>
              <th class="admin_table_centered"><?php echo $this->translate("Total Amount"); ?></th>   
              <th class="admin_table_centered"><?php echo $this->translate("Date of Purchase  "); ?></th>   
              <th><?php echo $this->translate("Options") ?></th>
            </tr>
          </thead>
          <?php $defaultCurrency = Engine_Api::_()->eusertip()->defaultCurrency(); ?>
          <tbody>
            <?php foreach ($this->paginator as $item): ?>
            <tr>
              <td><?php echo $item->order_id ?></td>
              <?php $tip = Engine_Api::_()->getItem('eusertip_tip',$item->tip_id); ?>
              <td><a href="<?php echo $tip->getHref(); ?>" target="_blank"><?php echo $item->title; ?></a></td>
              <td><?php echo $item->getOwner(); ?></td>
              <td class="admin_table_centered"><?php echo $item->gateway_type; ?></td>
              <td class="admin_table_centered"><?php echo $item->currency_symbol ? $item->currency_symbol : '-'; ?></td>
              <td class="admin_table_centered"><?php echo Engine_Api::_()->eusertip()->getCurrencyPrice(round($item->total_amount,2),$defaultCurrency); ?></td>
              <td class="admin_table_centered">
                <?php echo date('Y-m-d',strtotime($item->creation_date)); ?>
              </td>
              <td>
                <?php echo $this->htmlLink($this->url(array('tip_id' => $tip->tip_id,'action'=>'view','order_id'=>$item->order_id), 'eusertip_order', true).'?order=view', $this->translate("View Order"), array('title' => $this->translate("View Order"), 'class' => 'smoothbox')); ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        </div>
      </form>
      <br/>
      <div>
        <?php echo $this->paginationControl($this->paginator); ?>
      </div>
    <?php else:?>
      <div class="tip">
        <span>
          <?php echo $this->translate('No one has purchased paid content tip on your website yet.') ?>
        </span>
      </div>
    <?php endif; ?>
    </div>
  </div>
</div>
