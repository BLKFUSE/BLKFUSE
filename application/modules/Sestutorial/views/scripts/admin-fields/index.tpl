<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>

<?php include APPLICATION_PATH .  '/application/modules/Sestutorial/views/scripts/dismiss_message.tpl';?>
<?php 
// from fields module
echo $this->render('_jsAdmin.tpl'); ?>


<div class='clear sestutorial-form'>
  <div>
    <?php if(is_countable($this->subNavigation) && engine_count($this->subNavigation) ): ?>
      <div class='sestutorial-admin-sub-tabs'>
        <?php
        echo $this->navigation()->menu()->setContainer($this->subNavigation)->render();
        ?>
      </div>
    <?php endif; ?>
    <div class="clear sestutorial-form-cont">
      <h3><?php echo $this->translate('Tutorials Plugin Custom Fields'); ?></h3>
      <p>
        <?php echo $this->translate('You might want your users to provide some more information about their Tutorials. Here, you can create some custom fields of your choice and requirement.<br /><br />To reorder the custom fields, click on their names and drag them up or down. If you want to show different sets of fields to different types of categories, you can create multiple "Profile Types". While adding / editing a category, from the "Map Profile Type” field, you can map and associate Profile Types with Categories.'); ?>
      </p>
      <br />
      <div class="admin_fields_type">
        <h3><?php echo $this->translate("Editing Profile Type:") ?></h3>
        <?php echo $this->formSelect('profileType', $this->topLevelOption->option_id, array(), $this->topLevelOptions) ?>
      </div>
      <br />
      <div class="admin_fields_options">
        <a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_addquestion"><?php echo $this->translate('Add Question'); ?></a>
        <a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_renametype"><?php echo $this->translate('Rename Profile Type'); ?></a>
        <?php if (engine_count($this->topLevelOptions) > 1): ?>
          <a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_deletetype"><?php echo $this->translate('Delete Profile Type'); ?></a>
        <?php endif; ?>
        <a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_addtype"><?php echo $this->translate('Create New Profile Type'); ?></a>
        <a href="javascript:void(0);" onclick="void(0);" class="buttonlink admin_fields_options_saveorder" style="display:none;"><?php echo $this->translate('Save Order'); ?></a>
      </div>
      <br />
      <ul class="admin_fields clear">
        <?php foreach ($this->secondLevelMaps as $map): ?>
          <?php echo $this->adminFieldMeta($map) ?>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</div>
