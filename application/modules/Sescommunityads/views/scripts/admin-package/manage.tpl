<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manage.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php include APPLICATION_PATH .  '/application/modules/Sescommunityads/views/scripts/dismiss_message.tpl';?>
<h3>Manage Ads Packages</h3>
<p>From this section, you can create New Packages either Default or Paid which you can choose at the time of Ad creation. You can also search for the specific Package by entering the criteria into the fields.<br />
You can also disable the packages or can mark them as default according to your requirement from here. If you want to edit any package then you can do that also.</p>
<br />
<?php if( !empty($this->error) ): ?>
  <ul class="form-errors">
    <li>
      <?php echo $this->error ?>
    </li>
  </ul>

  <br />
<?php /*return; */ endif; ?>


<div>
  <?php echo $this->htmlLink(array('action' => 'create', 'reset' => false), $this->translate('Add Package'), array(
    'class' => 'buttonlink sesbasic_icon_add',
  )) ?>
</div>

<br />


<?php //if( $this->paginator->getTotalItemCount() > 0 ): ?>
  <script type="text/javascript">
    var currentOrder = '<?php echo $this->filterValues['order'] ?>';
    var currentOrderDirection = '<?php echo $this->filterValues['direction'] ?>';
    var changeOrder = function(order, default_direction){
      // Just change direction
      if( order == currentOrder ) {
        $('direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
      } else {
        document.getElementById('order').value = order;
        $('direction').value = default_direction;
      }
      scriptJquery('#filter_form').trigger('submit');
    }
  </script>

  <div class='admin_search'>
    <?php echo $this->formFilter->render($this) ?>
  </div>
  
  <br />
<?php //endif; ?>



<div class='admin_results'>
  <div>
    <?php $count = $this->paginator->getTotalItemCount() ?>
    <?php echo $this->translate(array("%s package found", "%s packages found", $count), $count) ?>
  </div>
  <div>
    <?php echo $this->paginationControl($this->paginator, null, null, array(
      'query' => $this->filterValues,
      'pageAsQuery' => true,
    )); ?>
  </div>
</div>

<br />


<?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
<div style="overflow:auto">
  <table class='admin_table'>
    <thead>
      <tr>
        <?php $class = ( $this->order == 'package_id' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->direction) : '' ) ?>
        <th style='width: 1%;' class="<?php echo $class ?>">
          <a href="javascript:void(0);" onclick="javascript:changeOrder('package_id', 'DESC');">
            <?php echo $this->translate("ID") ?>
          </a>
        </th>
        <?php $class = ( $this->order == 'title' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->direction) : '' ) ?>
        <th class="<?php echo $class ?>">
          <a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');">
            <?php echo $this->translate("Title") ?>
          </a>
        </th>
        
        <th style='width: 1%;' class="">
          <a href="javascript:void(0);">
            <?php echo $this->translate("Ads") ?>
          </a>
        </th>
        
        <th style='width: 1%;' class="">
          <a href="javascript:void(0);">
            <?php echo $this->translate("Payment Type") ?>
          </a>
        </th>
        
        <?php $class = ( $this->order == 'price' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->direction) : '' ) ?>
        <th style='width: 1%;' class="<?php echo $class ?>">
          <a href="javascript:void(0);" onclick="javascript:changeOrder('price', 'DESC');">
            <?php echo $this->translate("Price") ?>
          </a>
        </th>
        
        <th style='width: 1%;' class="">
          <a href="javascript:void(0);">
            <?php echo $this->translate("Ad Type") ?>
          </a>
        </th>
        <th style='width: 1%;'>
          <a href="javascript:void(0);">
            <?php echo $this->translate("Periods") ?>
          </a>
        </th>
        
        <th style='width: 1%;'>
          <?php echo $this->translate("Billing") ?>
        </th>
        <?php $class = ( $this->order == 'enabled' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->direction) : '' ) ?>
        <th style='width: 1%;' class='admin_table_centered <?php echo $class ?>'>
          <a href="javascript:void(0);" onclick="javascript:changeOrder('enabled', 'DESC');">
            <?php echo $this->translate("Enabled?") ?>
          </a>
        </th>
       
        <?php $class = ( $this->order == 'default' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->direction) : '' ) ?>
        <th style='width: 1%;' class='admin_table_centered <?php echo $class ?>'>
          <a href="javascript:void(0);" onclick="javascript:changeOrder('default', 'DESC');">
            <?php echo $this->translate("Default?") ?>
          </a>
        </th>
        <th style='width: 1%;' class='admin_table_centered'>
          <?php echo $this->translate("Active Ads") ?>
        </th>
        <th style='width: 1%;' class='admin_table_options'>
          <?php echo $this->translate("Options") ?>
        </th>
      </tr>
    </thead>
    <tbody>
      <?php foreach( $this->paginator as $item ): ?>
        <tr>
          <td><?php echo $item->package_id ?></td>
          <td class='admin_table_bold'>
            <?php echo $item->title ?>
          </td>
          <td class='admin_table_bold'>
            <?php echo $item->item_count; ?>
          </td>
          <td class=''>
            <?php echo $item->package_type == "recurring" ? 'Recurring' : "Non-Recurring" ?>
          </td>
          <td>
          <?php 
              $currency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');
              $view = Zend_Registry::get('Zend_View');
              echo $priceStr = $item->price == 0 ? 'FREE' : $view->locale()->toCurrency($item->price, $currency)
          ?>
          </td>
          <td class=''>
            <?php echo $item->click_type == "perclick" ? "Pay for clicks" : ($item->click_type == "perview" ? "Pay for Views" : ($item->click_type == "perday" ? "Pay for Days" : "")); ?>
          </td>
          <td class=''>
            <?php echo $item->click_limit == "-1" ? "Unlimited" : $item->click_limit; ?>
          </td>
          <td class="nowrap">
            <?php echo $item->getPackageDescription() ?>
          </td>
          
          <td class="admin_table_centered">
          <?php if($item->default != 1){ ?>
           <?php echo $item->enabled == 1 ?   $this->htmlLink(
                array('route' => 'default', 'module' => 'sescommunityads', 'controller' => 'admin-package', 'action' => 'enabled', 'id' => $item->getIdentity()),$this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Disabled the package')))) : $this->htmlLink(
                array('route' => 'default', 'module' => 'sescommunityads', 'controller' => 'admin-package', 'action' => 'enabled', 'id' => $item->getIdentity()),$this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Enabled the package')))) ; ?>
         <?php }else{ ?>
          -
         <?php  } ?>
         </td>
          <td class="admin_table_centered">
          <?php if($item->default != 1){ ?>
          <?php echo $item->default == 1 ?   $this->htmlLink(
                array('route' => 'default', 'module' => 'sescommunityads', 'controller' => 'admin-package', 'action' => 'default', 'id' => $item->getIdentity()),$this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Disabled the package')))) : $this->htmlLink(
                array('route' => 'default', 'module' => 'sescommunityads', 'controller' => 'admin-package', 'action' => 'default', 'id' => $item->getIdentity()),$this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Enabled the package'))),array('class'=>($item->price > 0) ? 'disabled': "")) ; ?>
               <?php }else{ ?>
          -
         <?php  } ?> 
          </td>
         
          <td class='admin_table_centered'>
            <?php echo $this->locale()->toNumber(@$this->adsCounts[$item->package_id], array('default_locale' => true)) ?>
          </td>
          <td class='admin_table_options'>
            <a href='<?php echo $this->url(array('action' => 'create', 'package_id' => $item->package_id)) ?>'>
              <?php echo $this->translate("edit") ?>
            </a>
            
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>


<script type="application/javascript">
scriptJquery('.disabled').click(function(e){
  e.preventDefault();
    alert('Only a free plan may be the default plan.');
})
</script>
