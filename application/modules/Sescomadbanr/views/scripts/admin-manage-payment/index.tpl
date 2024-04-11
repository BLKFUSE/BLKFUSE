<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescomadbanr
 * @package    Sescomadbanr
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-03-08 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php include APPLICATION_PATH .  '/application/modules/Sescommunityads/views/scripts/dismiss_message.tpl';?>
<?php if(is_countable($this->navigation) && engine_count($this->navigation) ): ?>
  <div class='sesbasic-admin-sub-tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->subNavigation)->render(); ?>
  </div>
<?php endif; ?>
<script type="text/javascript">
function multiDelete()
{
  return confirm("<?php echo $this->translate("Are you sure you want to delete the selected entries?") ?>");
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
<h3><?php echo "Manage User Payments"; ?></h3>
<p>
	<?php echo $this->translate("This page lists all the user payments information that you sent using direct link. After receiving payment by member you can click on edit link and mark as complete.") ?>	
</p>
<div class="sesbasic_search_reasult">
	<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescomadbanr', 'controller' => 'manage-payment', 'action' => 'create'), $this->translate("Add New"), array('class'=>'smoothbox sesbasic_icon_add buttonlink')) ?>
</div>
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
  <div class="sesbasic_search_reasult">
    <?php echo $this->translate(array('%s entry found.', '%s entries found', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
  </div>
  <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
  <table class='admin_table'>
    <thead>
      <tr>
        <th class='admin_table_short'><input onclick='selectAll();' type='checkbox' class='checkbox' /></th>
        <th class='admin_table_short'>ID</th>
        <th><?php echo $this->translate("User Name") ?></th>
        <th><?php echo $this->translate("Email") ?></th>
        <th><?php echo $this->translate("Transaction Id") ?></th>
        <th><?php echo $this->translate("Ad Id") ?></th>
        <th><?php echo $this->translate("Status");?></th>
        <th><?php echo $this->translate("Options") ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($this->paginator as $item): ?>
        <tr>
          <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->userpayment_id;?>' value='<?php echo $item->userpayment_id ?>' /></td>
          <td><?php echo $item->userpayment_id ?></td>
          <td><?php echo $item->member_name; ?></td>
          <td><?php echo $item->email; ?></td>
          <td><?php echo $item->transaction_id; ?></td>
          <td><?php echo $item->sescommunityad_id; ?></td>
          <td>
          <?php if($item->status == 1) { ?>
          <?php echo "In Progress"; ?>
          <?php } else { ?>
          <?php echo "Complete"; ?>
          <?php } ?>
          </td>
          <td>
            <?php if($item->sescommunityad_id) { ?>
							<?php $ads = Engine_Api::_()->getItem('sescommunityads', $item->sescommunityad_id); ?>
							<?php if($ads) { ?>
								<?php echo $this->htmlLink($this->url(array('action' => 'view', 'sescommunityad_id' => $item->sescommunityad_id),'sescommunityads_general',false), $this->translate("View"), array('target' => '_blank')); ?> | 
							<?php } ?>
            <?php } ?>
            <?php if($item->status == 1) { ?>
							<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescomadbanr', 'controller' => 'manage-payment', 'action' => 'send-payment-reminder', 'id' => $item->userpayment_id), $this->translate("Send Payment Reminder")) ?> | 
            <?php } ?>
            <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescomadbanr', 'controller' => 'manage-payment', 'action' => 'create', 'id' => $item->userpayment_id), $this->translate("Edit"), array('class' => 'smoothbox')) ?> | 
            
            <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescomadbanr', 'controller' => 'manage-payment', 'action' => 'delete', 'id' => $item->userpayment_id), $this->translate("Delete"),array('class' => 'smoothbox')) ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <div class='buttons'>
    <button type='submit'><?php echo $this->translate("Delete Selected") ?></button>
  </div>
  </form>
  <div>
    <?php echo $this->paginationControl($this->paginator); ?>
  </div>
<?php else: ?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are no entries yet.") ?>
    </span>
  </div>
<?php endif; ?>
