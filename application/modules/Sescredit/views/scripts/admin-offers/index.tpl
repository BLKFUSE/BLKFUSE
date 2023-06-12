<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescredit
 * @package    Sescredit
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-01-18 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

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
</script>

<?php $this->headTranslate(array('SesSun','SesMon','SesTue','SesWed','SesThu','SesFri','SesSat',"SesJan", "SesFeb", "SesMar", "SesApr", "SesMay", "SesJun", "SesJul", "SesAug", "SesSep", "SesOct", "SesNov", "SesDec"));?>
<?php include APPLICATION_PATH .  '/application/modules/Sescredit/views/scripts/dismiss_message.tpl';?>
<h3><?php echo $this->translate("Credit Sale Offers") ?></h3>
<p><?php echo $this->translate('This page lists all the offers you have created to sell credit points on your website. You can create new offer by using the "Create New Offer" link below.'); ?></p>
<br />

<?php  ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescredit/externals/scripts/moment.min.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescredit/externals/scripts/daterangepicker.min.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescredit/externals/styles/daterangepicker.css'); ?>


<h3><?php echo $this->translate("") ?></h3>
<p><?php echo $this->translate(''); ?></p>
<br />
<div style="overflow: hidden;">
  <?php echo $this->formFilter->render($this) ?>
</div>
<br>
<div class='sesbasic_search_reasult clear'>
  <div>
    <?php $count = $this->paginator->getTotalItemCount() ?>
    <?php echo $this->translate(array("%s offer found.", "%s offers found.", $count),
        $this->locale()->toNumber($count)) ?>
  </div>
  <div>
    <?php echo $this->paginationControl($this->paginator, null, null, array(
      'pageAsQuery' => true,
      'query' => $this->formValues,
    )); ?>
  </div>
</div>
<br />
<div>
  <?php echo $this->htmlLink(array('module' => 'sescredit','controller' => 'offers','action' => 'create'), $this->translate("Create New Offer"),array('class' => 'buttonlink sesbasic_icon_add')) ?>
</div>
<br />
<?php $price = Engine_Api::_()->sescredit()->getCurrencySymbol();?>
<?php if(engine_count($this->paginator) > 0):?>
<div class="admin_table_form">
  <form>
    <table class='admin_table'>
      <thead>
        <tr>
          <th style='width: 1%;'><?php echo $this->translate("ID") ?></th>
          <th><?php echo $this->translate("Point Value ($price)") ?></th>
          <th><?php echo $this->translate("Point") ?></th>
          <th><?php echo $this->translate("Number of Offer") ?></th>
          <th><?php echo $this->translate("User Avail Limit") ?></th>
          <th><?php echo $this->translate("Start Time") ?></th>
          <th><?php echo $this->translate("End Time") ?></th>
          <th class="admin_table_centered"><?php echo $this->translate("Status") ?></th>
          <th style='width: 1%;' class='admin_table_options'><?php echo $this->translate("Options") ?></th>
        </tr>
      </thead>
      <tbody id='menu_list'>
        <?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
          <?php foreach( $this->paginator as $item ): ?>
            <tr>
              <td><?php echo $item->offer_id ?></td>
              <td><?php echo $item->point_value; ?></td>
              <td><?php echo $item->point; ?></td>
              <?php if($item->limit_offer):?>
                <td><?php echo $item->limit_offer; ?></td>
              <?php else:?>
                <td><?php echo "No Limit"; ?></td>
              <?php endif;?>
              <?php if($item->user_avail):?>
                <td><?php echo $item->user_avail; ?></td>
              <?php else:?>
                <td><?php echo "Unlimited"; ?></td>
              <?php endif;?>
              <?php if($item->starttime != '0000-00-00 00:00:00'):?>
                <td><?php echo $item->starttime; ?></td>
                <td><?php echo $item->endtime; ?></td>
              <?php else:?>
                <td><?php echo "No Start Date"; ?></td>
                <td><?php echo "No End Date"; ?></td>
              <?php endif;?>
              <td class="admin_table_centered">
                <?php if($item->enable == 1):?>
                  <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescredit', 'controller' => 'admin-offers', 'action' => 'enable', 'id' => $item->offer_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Disabled')))) ?>
                <?php else: ?>
                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescredit', 'controller' => 'admin-offers', 'action' => 'enable', 'id' => $item->offer_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Enable')))) ?>
                <?php endif; ?>
              </td>
              <td class='admin_table_options'>
                <a class='' href='<?php echo $this->url(array('action' => 'edit', 'id' => $item->offer_id));?>'><?php echo $this->translate("Edit") ?></a>
                |
                <a class='smoothbox' href='<?php echo $this->url(array('action' => 'delete', 'id' => $item->offer_id));?>'><?php echo $this->translate("Delete") ?></a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
    <br />
  </form>
</div>
<?php else:?>
<div class="tip">
  <span>
    <?php echo "There are no offers in your search criteria.";?>
  </span>
</div>
<?php endif;?>

<script type='text/javascript'>

scriptJquery(function() {
  scriptJquery('input[name="show_date_field"]').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});

</script>
<style>
  .datepicker .footer button.apply:before{content:"Select";}
  .datepicker .footer button.cancel:before{content:"Cancel";}
</style>
