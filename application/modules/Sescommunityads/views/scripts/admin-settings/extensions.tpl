<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: statistics.tpl  2018-04-23 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php include APPLICATION_PATH .  '/application/modules/Sescommunityads/views/scripts/dismiss_message.tpl';?>
<?php $moduleApi = Engine_Api::_()->getDbTable('modules', 'core'); ?>
<div class='settings'>
  <form class="global_form">
    <div>
      <h3><?php echo $this->translate("Community Advertisement Extensions") ?> </h3>
      <p class="description">
        <?php echo $this->translate("Below are community advertisements extensions."); ?>
      </p>
        <table class='admin_table' style="width: 100%;">
          <thead>
            <tr>
               <th>Extension</th>
               <th align="center">Enabled</th>
            </tr>
          </thead>
          <tbody>
              <tr>
                <td class="extname">
                  <a href="admin/sescomadbanr/package/settings">Community Advertisements Banner Extension</a>
                </td>
                <td class="text-center">
                  <?php if($moduleApi->isModuleEnabled('sescomadbanr')): ?>
                    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescommunityads', 'controller' => 'admin-settings', 'action' => 'moduleenable', 'modulename' => 'sescomadbanr', 'enabled' => 0), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Disable')))) ?>
                  <?php else: ?>
                    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescommunityads', 'controller' => 'admin-settings', 'action' => 'moduleenable', 'modulename' => 'sescomadbanr', 'enabled' => 1), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Enable')))) ?>
                  <?php endif; ?> 
                </td>
              </tr>
          </tbody>
        </table>
    </div>
  </form>
</div>
<style type="text/css">
.extname a{font-weight:bold;}
</style>
