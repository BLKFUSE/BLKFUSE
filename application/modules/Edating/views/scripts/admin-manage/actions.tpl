<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: actions.tpl 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
?>

<script type="text/javascript">
  function multiDelete()
  {
    return confirm("<?php echo $this->translate('Are you sure you want to delete the selected photos ?');?>");
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

<?php include APPLICATION_PATH .  '/application/modules/Edating/views/scripts/dismiss_message.tpl';?>
<h3>Manage Actions</h3>
<p>This page lists all of the actions your users have peroformed. You can use this page to monitor these actions. Entering criteria into the filter fields will help you find specific action.</p>
<?php
$settings = Engine_Api::_()->getApi('settings', 'core');?>
<br />
<div class='admin_search sesbasic_search_form'>
  <?php echo $this->formFilter->render($this) ?>
</div>
<br />
<?php $counter = $this->paginator->getTotalItemCount(); ?> 
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
  <div class="sesbasic_search_reasult">
    <?php echo $this->translate(array('%s action found.', '%s actions found.', $counter), $this->locale()->toNumber($counter)) ?>
  </div>
  <form id="multidelete_form" action="<?php echo $this->url();?>" onSubmit="return multiDelete()" method="POST">
    <table class='admin_table edating_table'>
      <thead>
        <tr>
          <!--<th class='admin_table_short'><input onclick="selectAll()" type='checkbox' class='checkbox' /></th>-->
          <th class='admin_table_short'>ID</th>
          <th><?php echo $this->translate('Who') ?></th>
          <th><?php echo $this->translate('What') ?></th>
          <th><?php echo $this->translate('When') ?></th>
          <th><?php echo $this->translate('Whom') ?></th>
          <!--<th><?php //echo $this->translate('Options') ?></th>-->
        </tr>
      </thead>
      <tbody>
        <?php foreach ($this->paginator as $item): ?>
        <?php $owner = Engine_Api::_()->getItem('user', $item->owner_id); ?>
        <?php $user = Engine_Api::_()->getItem('user', $item->user_id); ?>
        <tr>
          <!--<td><input type='checkbox' class='checkbox' name='delete_<?php //echo $item->action_id;?>' value="<?php //echo $item->action_id ?>"/></td>-->
          <td>
            <?php echo $item->getIdentity() ?>
          </td>
          <td class="edating_table_user">
            <?php echo $this->itemPhoto($owner, 'thumb.icon'); ?>
            <a href="<?php echo $owner->getHref(); ?>"><?php echo $owner->getTitle(); ?></a>
          </td>
          <td><?php echo $item->action; ?></td>
          <td><?php echo $this->locale()->toDateTime($item->time_stamp) ?></td>
          <td class="edating_table_user">
            <?php echo $this->itemPhoto($user, 'thumb.icon'); ?>
            <?php echo $this->htmlLink($user->getHref(), $user->getOwner()); ?>
          </td>
          <!--<td>
            <?php //echo $this->htmlLink(array('route' => 'default', 'module' => 'edating', 'controller' => 'admin-manage', 'action' => 'delete-photo', 'id' => $item->action_id), $this->translate("Delete"), array('class' => 'smoothbox')); ?>
          </td>-->
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <br/>
<!--    <div class='buttons'>
      <button type='submit'> <?php //echo $this->translate('Delete Selected') ?> </button>
    </div>-->
  </form>
  <br />
  <div class="clear"> <?php echo $this->paginationControl($this->paginator); ?> </div>
<?php else: ?>
  <div class="tip"> <span> <?php echo $this->translate("There are no photos .") ?> </span> </div>
<?php endif; ?>
