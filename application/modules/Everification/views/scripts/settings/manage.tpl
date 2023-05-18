<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Everification
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manage.tpl 2019-06-05 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Everification/externals/styles/styles.css'); ?>
<div class="everification_settings _bxs">
	<div class="everification_settings_header">
  	<div class="_title"><?php echo $this->translate("Manage Document for Verified Badge"); ?></div>
    <div class="_des"><?php echo $this->translate("Here, you can manage your document submitted for verified badge."); ?></div>
  </div>
  <?php if($this->user_id == $this->viewer_id && count($this->documents) == 0) { ?>
  	<div class="everification_upload_btn">
    	<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'everification', 'controller' => 'index', 'action' => 'upload-document', 'format' => 'smoothbox'), $this->translate('Upload New Document'), array('class' => 'smoothbox buttonlink  everification_icon_add')); ?>
  	</div>
  <?php } ?>
  <?php if($this->paginator->getTotalItemCount() > 0) { ?>
    <div class="everification_manage_documents">
    	<table>
    		<thead class="_header">
        	<tr>
            <th class="_status"><?php echo $this->translate("Status"); ?></th>
            <th class="_preview"><?php echo $this->translate("Preview"); ?></th>
            <th class="_option"><?php echo $this->translate("Options"); ?></th>
          </tr>
      	</thead>
      	<tbody>
       <?php $counter = 1; ?>
        <?php foreach($this->paginator as $result) { ?>
          <tr>
            <td class="_status" data-label='<?php echo $this->translate("Status"); ?>'>
              <?php if($result->verified == '0') { ?>
                <span class="status_pending"><?php echo $this->translate("Verification Pending")?></span>
              <?php } else if($result->verified == '1') { ?>
                <span class="status_verified"><?php echo $this->translate("Verified")?></span>
              <?php } else if($result->verified == '2') { ?>
                <span class="status_rejected"><?php echo $this->translate("Rejected")?></span>
              <?php } ?>
            </td>
            <?php if($result->file_id) { ?>
            <td class="_preview" data-label='<?php echo $this->translate("Preview"); ?>'>
              <?php $storage = Engine_Api::_()->getItem('storage_file', $result->file_id); ?>
              <?php if($storage) { ?>
              <a target="_blank" href="<?php echo $storage->map(); ?>" class="buttonlink everification_icon_view"><?php echo $this->translate("Preview") ?></a>
              <?php } ?>
            </td>
            <?php } ?>
            <?php if($result->user_id == $this->viewer_id) { ?>
              <td class="_option"  data-label='<?php echo $this->translate("Options"); ?>'>
                <?php if($result->verified == '0' && empty($result->submintoadmin)) { ?>
                  <span><?php echo $this->htmlLink(array('route' => 'default', 'module' => 'everification', 'controller' => 'settings', 'action' => 'delete', 'document_id' => $result->document_id, 'format' => 'smoothbox'), $this->translate('Delete'), array('class' => 'buttonlink smoothbox everification_icon_delete')); ?></span>
                <?php } else if($result->verified == '2' && !empty($result->submintoadmin)) { ?>
                  <span><?php echo $this->htmlLink(array('route' => 'default', 'module' => 'everification', 'controller' => 'settings', 'action' => 'delete', 'document_id' => $result->document_id, 'format' => 'smoothbox'), $this->translate('Delete'), array('class' => 'buttonlink smoothbox everification_icon_delete')); ?></span>
                <?php } else { echo "---"; } ?>
              </td>
            <?php } ?>
          </tr>
        <?php $counter++; } ?>
      </tbody>
      </table>
    </div>
  <?php } else { ?>
    <div class="tip">
      <span>
        <?php echo $this->translate("There are no document uploaded by you yet.") ?>
      </span>
    </div>
  <?php } ?>
</div>  
