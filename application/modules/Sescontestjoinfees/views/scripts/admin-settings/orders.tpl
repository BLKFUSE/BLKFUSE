<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: orders.tpl  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php $baseURL = $this->layout()->staticBaseUrl; ?>
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

<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/dismiss_message.tpl';?>
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
<p><?php echo $this->translate('This page lists all of the orders of fees paid on your website for joining contests. You can use this page to monitor these orders. Entering criteria into the filter fields will help you find specific entry order. Leaving the filter fields blank will show all the orders on your social network.'); ?></p>
<br />
    <div class='admin_search sesbasic_search_form'>
      <?php echo $this->formFilter->render($this) ?>
    </div>
    <br />

    <?php $counter = $this->paginator->getTotalItemCount(); ?> 
    <?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
      <div class="sesbasic_search_reasult">
        <?php echo $this->translate(array('%s order found.', '%s orders found.', $counter), $this->locale()->toNumber($counter)) ?>
      </div>
      <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
        <div class="clear" style="overflow: auto;">  
        <table class='admin_table'>
          <thead>
            <tr>
              <th class='admin_table_short'><a href="javascript:void(0);" onclick="javascript:changeOrder('order_id', 'DESC');"><?php echo $this->translate("ID") ?></a></th>
              <th><?php echo $this->translate("Contest Title") ?></th>          
              <th><?php echo $this->translate("Entry Owner") ?></th>
              <th><?php echo $this->translate("Entry Title") ?></th>
              <th class="admin_table_centered"><?php echo $this->translate("Gateway"); ?></th>
              <th class="admin_table_centered"><?php echo $this->translate("Currency") ?></th>
              <th class="admin_table_centered"><?php echo $this->translate("Total Amount"); ?></th>   
              <th class="admin_table_centered"><?php echo $this->translate("Date of Purchase  "); ?></th>   
              <th><?php echo $this->translate("Options") ?></th>
            </tr>
          </thead>
          <?php $defaultCurrency = Engine_Api::_()->sescontestjoinfees()->defaultCurrency(); ?>
          <tbody>
            <?php foreach ($this->paginator as $item): ?>
            <tr>
              <td><?php echo $item->order_id ?></td>
              <?php $event = Engine_Api::_()->getItem('contest',$item->contest_id); ?>
              <td><a href="<?php echo $event->getHref(); ?>" target="_blank"><?php echo $item->title; ?></a></td>
              <td><?php echo $item->getOwner(); ?></td>
              <?php $entry = Engine_Api::_()->getItem('participant',$item->entry_id); ?>
              <?php if($entry){ ?>
              <td><a href="<?php echo $entry->getHref(); ?>" target="_blank"><?php echo $entry->title; ?></a></td>
              <?php }else{ ?>
               <td> -</td>
              <?php } ?>
              <td class="admin_table_centered"><?php echo $item->gateway_type; ?></td>
              <td class="admin_table_centered"><?php echo $item->currency_symbol ? $item->currency_symbol : '-'; ?></td>
              <td class="admin_table_centered"><?php echo Engine_Api::_()->sescontestjoinfees()->getCurrencyPrice(round($item->total_amount,2),$defaultCurrency); ?></td>
              <td class="admin_table_centered">
                <?php echo date('Y-m-d',strtotime($item->creation_date)); ?>
              </td>
              <td>
                <?php echo $this->htmlLink($this->url(array('contest_id' => $event->custom_url,'action'=>'view','order_id'=>$item->order_id), 'sescontestjoinfees_order', true).'?order=view', $this->translate("View Order"), array('title' => $this->translate("View Order"), 'class' => 'smoothbox')); ?>
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
          <?php echo $this->translate('No one has joined a paid contest on your website yet.') ?>
        </span>
      </div>
    <?php endif; ?>
    </div>
  </div>
</div>
