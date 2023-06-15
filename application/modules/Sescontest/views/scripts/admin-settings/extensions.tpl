<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: statistics.tpl  2018-04-23 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/dismiss_message.tpl';?>
<?php $moduleApi = Engine_Api::_()->getDbTable('modules', 'core'); ?>
<div class='settings'>
  <form class="global_form">
    <div>
      <h3><?php echo $this->translate("Contest Extensions") ?> </h3>
      <p class="description">
        <?php echo $this->translate("Below are contest extensions."); ?>
      </p>
        <table class='admin_table' style="width: 100%;">
         <thead>
            <tr>
               <th>Extension</th>
               <th class="admin_table_centered">Enabled</th>
            </tr>
          </thead>
          <tbody>
              <tr>
                <td class="extname">
                  <a href="admin/sescontestjoinfees/settings/extension">Contests Joining Fees & Payments System Plugin</a>
                </td>
                <td class="text-center">
                  <?php if($moduleApi->isModuleEnabled('sescontestjoinfees')): ?>
                    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-settings', 'action' => 'moduleenable', 'modulename' => 'sescontestjoinfees', 'enabled' => 0), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Disable')))) ?>
                  <?php else: ?>
                    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-settings', 'action' => 'moduleenable', 'modulename' => 'sescontestjoinfees', 'enabled' => 1), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Enable')))) ?>
                  <?php endif; ?> 
                </td>
              </tr>
              <tr>
                <td class="extname">
                  <a href="admin/sescontestjurymember/settings">Voting by Jury Members Plugin</a>
                </td>
                <td class="text-center">
                  <?php if($moduleApi->isModuleEnabled('sescontestjurymember')): ?>
                    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-settings', 'action' => 'moduleenable', 'modulename' => 'sescontestjurymember', 'enabled' => 0), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Disable')))) ?>
                  <?php else: ?>
                    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-settings', 'action' => 'moduleenable', 'modulename' => 'sescontestjurymember', 'enabled' => 1), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Enable')))) ?>
                  <?php endif; ?> 
                </td>
              </tr>
              <tr>
                <td class="extname">
                  <a href="admin/sescontestpackage/package/settings">Packages for Allowing Contest Creation Plugin</a>
                </td>
                <td class="text-center">
                  <?php if($moduleApi->isModuleEnabled('sescontestpackage')): ?>
                    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-settings', 'action' => 'moduleenable', 'modulename' => 'sescontestpackage', 'enabled' => 0), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Disable')))) ?>
                  <?php else: ?>
                    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-settings', 'action' => 'moduleenable', 'modulename' => 'sescontestpackage', 'enabled' => 1), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Enable')))) ?>
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
