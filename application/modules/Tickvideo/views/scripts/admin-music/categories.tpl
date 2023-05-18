<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: categories.tpl 2020-11-03 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Tickvideo/views/scripts/dismiss_message.tpl';?>


<script type="text/javascript">
    function multiDelete()
    {
        return confirm("<?php echo $this->translate("Are you sure you want to delete the selected category ?") ?>");
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
<h3><?php echo $this->translate("Manage Music Categories") ?></h3>
<p>
    Here, you can add Categories for Music you want to use while the creation of Video by using the "Create New Category" button below. You can add as many music as you want in the Music Categories and also can edit and delete them as per your choice.
</p>
<br class="clear" />
<div class="sesbasic_search_reasult">

    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'tickvideo', 'controller' => 'music', 'action' => 'create-category'), $this->translate("Create New Category"), array('class'=>'smoothbox sesbasic_icon_add buttonlink')) ?>
</div>
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
<div class="sesbasic_search_reasult">
    <?php echo $this->translate(array('%s category  found.', '%s categories  found', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
</div>
<form id="multidelete_form" action="<?php echo $this->url();?>" onSubmit="return multiDelete()" method="POST">
    <table class='admin_table'>
        <thead>
            <tr>
                <th class='admin_table_short'><input onclick="selectAll()" type='checkbox' class='checkbox' /></th>
                <th class='admin_table_short'>Id</th>
                <th><?php echo $this->translate('Title') ?></th>
                <th class="text-center"><?php echo $this->translate('Number of Music') ?></th>
                <th class="text-center"><?php echo $this->translate('Status') ?></th>
                <th><?php echo $this->translate('Options') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->paginator as $item): ?>
            <tr>
                <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->category_id;?>' value="<?php echo $item->category_id ?>"/></td>
                <td><?php echo $item->category_id; ?></td>
                <td><?php echo $item->category_name ?></td>
                <td class="text-center"><?php echo $item->item_count; ?></td>
			   <td style="width:10%;" class="admin_table_centered">
                  <?php echo ( $item->status ? $this->htmlLink(array('route' => 'admin_default', 'module' => 'tickvideo', 'controller' => 'music', 'action' => 'enabled', 'category_id' => $item->category_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title' => $this->translate('Enabled'))), array()) : $this->htmlLink(array('route' => 'admin_default', 'module' => 'tickvideo', 'controller' => 'music', 'action' => 'enabled', 'category_id' => $item->category_id), $this->htmlImage('application/modules/Sesbasic/externals/images/icons/error.png', '', array('title' => $this->translate('Disabled')))) ) ?>
                </td>
                <td>
                    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'tickvideo', 'controller' => 'music', 'action' => 'create-category','id' => $item->category_id), $this->translate("Edit"),array('class' => 'smoothbox')) ?>
                    |
                    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'tickvideo', 'controller' => 'music', 'action' => 'manage','id' => $item->category_id), $this->translate("Manage Music")) ?>
                    |
                    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'tickvideo', 'controller' => 'music', 'action' => 'delete-category','id' => $item->category_id), $this->translate("Delete"),array('class' => 'smoothbox')) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div>
        <?php echo $this->paginationControl($this->paginator,null,null); ?>
    </div>

    <br />
    <br />
    <div class='buttons'>
        <button type='submit'><?php echo $this->translate("Delete Selected") ?></button>
    </div>
    <br />
</form>
<?php else: ?>
<div class="tip">
    <span>
        <?php echo $this->translate("There are no category created yet.") ?>
    </span>
</div>
<?php endif; ?>


<style>
.text-center {
   text-align: center;
}
</style>
