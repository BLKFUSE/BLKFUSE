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
  return confirm("<?php echo $this->translate("Are you sure you want to delete the selected banner sizes?") ?>");
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
<h3><?php echo "Manage Banner Sizes"; ?></h3>
<p>
	<?php echo $this->translate("This page lists all the Banner Sizes created by you.") ?>	
</p>
<br class="clear" />
<div class="sesbasic_search_reasult">
	<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescomadbanr', 'controller' => 'manage', 'action' => 'create'), $this->translate("Add New Banner Size"), array('class'=>'smoothbox sesbasic_icon_add buttonlink')) ?>
</div>
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
  <div class="sesbasic_search_reasult">
    <?php echo $this->translate(array('%s dashboard found.', '%s dashboards found', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
  </div>
  <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
    <table class='admin_table'>
      <thead>
        <tr>
          <th class='admin_table_short'><input onclick='selectAll();' type='checkbox' class='checkbox' /></th>
          <th class='admin_table_short'>ID</th>
          <th><?php echo $this->translate("Title") ?></th>
          <th><?php echo $this->translate("Width") ?></th>
          <th><?php echo $this->translate("Height") ?></th>
          <th class="admin_table_centered"><?php echo $this->translate("Status");?></th>
          <th><?php echo $this->translate("Options") ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($this->paginator as $item): ?>
          <tr>
            <td><input <?php if($item->banner_id == 1) { ?> disabled <?php } ?> type='checkbox' class='checkbox' name='delete_<?php echo $item->banner_id;?>' value='<?php echo $item->banner_id ?>' /></td>
            <td><?php echo $item->banner_id ?></td>
            <td><?php echo $item->banner_name; ?></td>
            <td><?php echo $item->width; ?></td>
            <td><?php echo $item->height; ?></td>
            <td class="admin_table_centered">
            <?php if($item->banner_id != 1) { ?>
              <?php echo ( $item->enabled ? $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescomadbanr', 'controller' => 'manage', 'action' => 'enabled', 'id' => $item->banner_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sescomadbanr/externals/images/check.png', '', array('title' => $this->translate('Disable'))), array()) : $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescomadbanr', 'controller' => 'manage', 'action' => 'enabled', 'id' => $item->banner_id), $this->htmlImage('application/modules/Sescomadbanr/externals/images/error.png', '', array('title' => $this->translate('Enable')))) ) ?></td>
            <?php } else { ?>
              <?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sescomadbanr/externals/images/check.png', '', array('title' => $this->translate('Disable'))); ?>
            <?php } ?>
            <td>
              <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescomadbanr', 'controller' => 'manage', 'action' => 'create', 'id' => $item->banner_id), $this->translate("Edit"), array('class' => 'smoothbox')) ?>
              <?php if($item->banner_id != 1) { ?>
              |
              <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescomadbanr', 'controller' => 'manage', 'action' => 'delete', 'id' => $item->banner_id), $this->translate("Delete"),array('class' => 'smoothbox')) ?>
              <?php } ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <br />
    <div class='buttons'>
      <button type='submit'><?php echo $this->translate("Delete Selected") ?></button>
    </div>
  </form>
  <br />
  <div>
    <?php echo $this->paginationControl($this->paginator); ?>
  </div>
<?php else: ?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are no banners created by you yet.") ?>
    </span>
  </div>
<?php endif; ?>
