<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php include APPLICATION_PATH .  '/application/modules/Sescommunityads/views/scripts/dismiss_message.tpl';?>
<script type="text/javascript">
  en4.core.runonce.add(function() {
    scriptJquery('th.admin_table_short input[type=checkbox]').on('click', function(event) {
      var el = scriptJquery(event.target);
      scriptJquery('input[type=checkbox]').prop('checked', el.prop('checked'));
    });
  });
  var multiDelete = function() {
    return confirm("<?php echo $this->translate('Are you sure you want to delete selected Ads?');?>");    
  }
</script>
<h3>
  <?php echo $this->translate("Abuse Reports") ?>
</h3>
<p>
  <?php echo $this->translate("This page lists all of the reports your users have sent in regarding inappropriate advertisements.") ?>
</p>
<?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
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
<?php endif; ?>
<div class='admin_results'>
  <div>
    <?php $count = $this->paginator->getTotalItemCount() ?>
    <?php echo $this->translate(array("%s report found", "%s reports found", $count), $count) ?>
  </div>
  <div>
    <?php echo $this->paginationControl($this->paginator, null, null, array(
      'query' => $this->filterValues,
      'pageAsQuery' => true,
    )); ?>
  </div>
</div>
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
<form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
<div class="admin_table_form">
  <table class='admin_table'>
    <thead>
      <tr>
        <th style="width: 1%;" class="admin_table_short"><input type='checkbox' class='checkbox'></th>
        <th style="width: 1%;">
          <a href="javascript:void(0);" onclick="javascript:changeOrder('report_id', 'ASC');">
            <?php echo $this->translate("ID") ?>
          </a>
        </th>
        <th style="width: 1%;">
          <a href="javascript:void(0);" onclick="javascript:changeOrder('category', 'ASC');">
            <?php echo $this->translate("Reasons") ?>
          </a>
        </th>
        <th>
          <a href="javascript:void(0);" onclick="javascript:changeOrder('description', 'ASC');">
            <?php echo $this->translate("Description") ?>
          </a>
        </th>
        <th>
          <a href="javascript:;" onclick="javascript:changeOrder('ip', 'ASC');">
            <?php echo $this->translate("IP Address") ?>
          </a>
        </th>
        <th style="width: 1%;">
          <?php echo $this->translate("Reporter") ?>
        </th>
        <th style="width: 1%;">
          <a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'ASC');">
            <?php echo $this->translate("Date") ?>
          </a>
        </th>
        <th style="width: 1%;">
          <a href="javascript:void(0);" onclick="javascript:changeOrder('subject_type', 'ASC');">
            <?php echo $this->translate("Ad") ?>
          </a>
        </th>
        
        <th style="width: 1%;">
          <?php echo $this->translate("Options") ?>
        </th>
      </tr>
    </thead>
    <tbody>
      <?php foreach( $this->paginator as $item ): ?>
      <tr>
        <td><input type='checkbox' name="delete_<?php echo $item->getIdentity(); ?>" class='checkbox' value="<?php echo $item->report_id?>"></td>
        <td><?php echo $item->report_id ?></td>
        <td class="nowrap"><?php echo $item->value ?></td>
        <td style="white-space: normal;"><?php echo $item->description ? $this->escape($item->description) : '-' ?></td>
        <td><?php echo $item->ip; ?></td>
        <td class="nowrap"><?php echo $item->user_id ? $this->htmlLink($this->item('user', $item->user_id)->getHref(), $this->item('user', $item->user_id)->getTitle(), array('target' => '_blank')) : 'Anonymous User'; ?></td>
        <td class="nowrap"><?php echo $item->creation_date ?></td>
        <td class="nowrap"><a href="<?php echo $this->url(array('action'=>'view','ad_id'=>$item->item_id),'sescommunityads_general',false) ?>" target="_blank">View Ad</a></td>
        <td class="admin_table_options">
          <?php echo $this->htmlLink(array('action' => 'action', 'id' => $item->getIdentity(), 'reset' => false, 'format' => 'smoothbox'), $this->translate("take action"), array('class' => 'smoothbox')) ?>
          <span class="sep">|</span>
          <?php echo $this->htmlLink(array('action' => 'delete', 'id' => $item->getIdentity(), 'reset' => false), $this->translate("dismiss"),array('class'=>'smoothbox')) ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <div class='buttons'>
    <button type='submit'><?php echo $this->translate("Delete Selected") ?></button>
  </div>
</div>
</form>
<?php else:?>
  <div class="tip">
    <span><?php echo $this->translate("There are currently no outstanding abuse reports.") ?></span>
  </div>
<?php endif; ?>
</div>
