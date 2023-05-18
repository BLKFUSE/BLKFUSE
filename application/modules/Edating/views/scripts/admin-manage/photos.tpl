<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: photos.tpl 2022-06-09 00:00:00 socialnetworking.solutions $
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

<h2><?php echo $this->translate('SNS - Dating Plugin') ?></h2>

<?php if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesbasic'))
  {
    include APPLICATION_PATH .  '/application/modules/Sesbasic/views/scripts/_mapKeyTip.tpl'; 
  } else { ?>
     <div class="tip"><span><?php echo $this->translate("This plugin requires \"<a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>SocialNetworking.Solutions (SNS) Basic Required Plugin </a>\" to be installed and enabled on your website for Location and various other featrures to work. Please get the plugin from <a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>here</a> to install and enable on your site."); ?></span></div>
  <?php } ?>
<?php if(is_countable($this->navigation) && engine_count($this->navigation) ): ?>
<div class='tabs'>
  <?php
      // Render the menu
      //->setUlClass()
      echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
</div>
<?php endif; ?>
<h3>Manage Photos</h3>
<p>This page lists all of the photos your users have created. You can use this page to monitor these photos and delete offensive material if necessary. Entering criteria into the filter fields will help you find specific photo. Leaving the filter fields blank will show all the photos on your social network.</p>
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
    <?php echo $this->translate(array('%s photo found.', '%s photos found.', $counter), $this->locale()->toNumber($counter)) ?>
  </div>
  <form id="multidelete_form" action="<?php echo $this->url();?>" onSubmit="return multiDelete()" method="POST">
    <table class='admin_table edating_table edating_table_manage_photo'>
      <thead>
        <tr>
          <th class='admin_table_short'><input onclick="selectAll()" type='checkbox' class='checkbox' /></th>
          <th class='admin_table_short'>ID</th>
          <th><?php echo $this->translate('Image') ?></th>
          <th><?php echo $this->translate('Owner') ?></th>
          <th><?php echo $this->translate('Options') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($this->paginator as $item): ?>
        <tr>
          <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->photo_id;?>' value="<?php echo $item->photo_id ?>"/></td>
          <td><?php echo $item->getIdentity() ?></td>
          <td><img src="<?php echo $item->getPhotoUrl('thumb.normal'); ?>" style="height:75px; width:75px;"/></td>
          <td><?php echo $this->htmlLink($item->getHref(), $item->getOwner()); ?></td>
          <td>
            <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'edating', 'controller' => 'admin-manage', 'action' => 'delete-photo', 'id' => $item->photo_id), $this->translate("Delete"), array('class' => 'smoothbox')); ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <br/>
    <div class='buttons'>
      <button type='submit'> <?php echo $this->translate('Delete Selected') ?> </button>
    </div>
  </form>
  <br />
  <div class="clear"> <?php echo $this->paginationControl($this->paginator); ?> </div>
<?php else: ?>
  <div class="tip"> <span> <?php echo $this->translate("There are no photos .") ?> </span> </div>
<?php endif; ?>
