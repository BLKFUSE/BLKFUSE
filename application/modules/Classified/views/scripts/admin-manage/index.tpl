<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Classified
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     Jung
 */
?>
<?php include APPLICATION_PATH .  '/application/modules/Classified/views/scripts/_adminHeader.tpl';?>
<script type="text/javascript">

function multiDelete()
{
  return confirm("<?php echo $this->translate('Are you sure you want to delete the selected classified listings?');?>");
}

function selectAll(obj)
{
  scriptJquery('.checkbox').each(function(){
    scriptJquery(this).prop("checked",scriptJquery(obj).prop("checked"))
  });
}
</script>

<p>
  <?php echo $this->translate("CLASSIFIEDS_VIEWS_SCRIPTS_ADMINMANAGE_INDEX_DESCRIPTION") ?>
</p>
<?php
$settings = Engine_Api::_()->getApi('settings', 'core');
if( $settings->getSetting('user.support.links', 0) == 1 ) {
	echo '     More info: <a href="https://community.socialengine.com/classifieds/597/49/classifieds" target="_blank">See KB article</a>.';
} 
?>		
<br />
<br />
<?php if( engine_count($this->paginator) ): ?>
<form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
<table class='admin_table admin_responsive_table'>
  <thead>
    <tr>
      <th class='admin_table_short'><input onclick='selectAll(this);' type='checkbox' class='checkbox' /></th>
      <th class='admin_table_short'>ID</th>
      <th><?php echo $this->translate("Title") ?></th>
      <th><?php echo $this->translate("Owner") ?></th>
      <th><?php echo $this->translate("Views") ?></th>
      <th><?php echo $this->translate("Date") ?></th>
      <th><?php echo $this->translate("Approve") ?></th>
      <th><?php echo $this->translate("Options") ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($this->paginator as $item): ?>
      <tr>
        <td ><input type='checkbox' class='checkbox' name='delete_<?php echo $item->classified_id;?>' value="<?php echo $item->classified_id; ?>" /></td>
        <td data-label="ID"><?php echo $item->classified_id ?></td>
        <td data-label="<?php echo $this->translate("Title") ?>"><?php echo $item->getTitle() ?></td>
        <td data-label="<?php echo $this->translate("Owner") ?>" class="admin_table_name"><span class="_name"><?php echo $this->user($item->owner_id)->getTitle() ?></span></td>
        <td data-label="<?php echo $this->translate("Views") ?>"><?php echo $this->locale()->toNumber($item->view_count) ?></td>
        <td data-label="<?php echo $this->translate("Date") ?>"><?php echo $this->locale()->toDateTime($item->creation_date) ?></td>
        
        
        <td data-label="<?php echo $this->translate("Approve") ?>">
          <?php if(!$item->resubmit) { ?>
            <?php echo "---"; ?>
          <?php } else { ?>
            <?php if($item->approved == 1): ?>
              <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'core', 'controller' => 'admin-approve-content', 'action' => 'approved', 'resource_id' => $item->getIdentity(), 'resource_type' => $item->getType()), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Core/externals/images/admin/check.png', '', array('title'=> $this->translate('Unapprove'))), array('class' => "smoothbox")) ?>
            <?php else: ?>
              <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'core', 'controller' => 'admin-approve-content', 'action' => 'approved', 'resource_id' => $item->getIdentity(), 'resource_type' => $item->getType()), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Core/externals/images/admin/uncheck.png', '', array('title'=> $this->translate('Approve'))), array('class' => "smoothbox")) ?>
            <?php endif; ?>
					<?php } ?>
				</td>
				
        <td  class="admin_table_options">
          <?php if(!$item->resubmit && empty($item->approved)) { ?>
            <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'core', 'controller' => 'admin-approve-content', 'action' => 'approved', 'resource_id' => $item->getIdentity(), 'resource_type' => $item->getType()), $this->translate("Approve"),array('class' => 'smoothbox')) ?>
            |
            <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'core', 'controller' => 'admin-approve-content', 'action' => 'reject', 'resource_id' => $item->getIdentity(), 'resource_type' => $item->getType()), $this->translate("Reject"),array('class' => 'smoothbox')) ?>
            |
          <?php } ?>
          
          <a href="<?php echo $this->url(array('user_id' => $item->owner_id, 'classified_id' => $item->classified_id), 'classified_entry_view') ?>">
            <?php echo $this->translate("view") ?>
          </a>
          |
          <?php echo $this->htmlLink(
            array('route' => 'default', 'module' => 'classified', 'controller' => 'admin-manage', 'action' => 'delete', 'id' => $item->classified_id),
            $this->translate("delete"),
            array('class' => 'smoothbox')) ?>
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

<br/>
<div>
  <?php echo $this->paginationControl($this->paginator); ?>
</div>

<?php else:?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are no classified listings by your members yet.") ?>
    </span>
  </div>
<?php endif; ?>
