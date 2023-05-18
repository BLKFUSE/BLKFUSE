<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesuserimport
 * @package    Sesuserimport
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-11-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php include APPLICATION_PATH .  '/application/modules/Sesuserimport/views/scripts/dismiss_message.tpl';?>
<h3><?php echo $this->translate("Import Members Using CSV File") ?></h3><br />
<p><?php echo $this->translate('This page enables you to import Members on your website from CSV file. Please download the template file using the "Download Template File" button below. To start importing Members, click on the "Import Members" button.<br /><br />Notes: See the points below and make sure the csv is created follows each one:<br />
<br />1. Do not add any new column in the downloaded template file.<br />2. The data in the file should be pipe ("|") separated and in same ordering as that of the template file.<br />3. We recommend you to import 100 Members from the csv file at a time.<br />4. File must be in .csv format Only.<br />'); ?></p>
<br />
<div class="sesuserimport_import_buttons">
<a href="<?php echo $this->url(array('action' => 'download')) ?>" class="sesuserimport_download"><?php echo $this->translate('Download Template File')?></a>
<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesuserimport', 'controller' => 'manage-imports', 'action' => 'import'), $this->translate('Import Members'), array('class' => 'smoothbox sesuserimport_import')) ?>
</div>
