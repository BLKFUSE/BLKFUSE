<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Activity
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>
<?php echo $this->partial('_admin_breadcrumb.tpl', 'core', array('parentMenu' => "core_admin_main_manage", 'childMenuItemName' => 'core_admin_main_settings_activity')); ?>

<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render(); ?>
  </div>
<?php endif; ?>
<?php
  $this->form->setTitle('Activity Feed Settings');
  $this->form->setDescription($this->translate('ACTIVITY_FORM_ADMIN_SETTINGS_GENERAL_DESCRIPTION',
      $this->url(array('module' => 'activity','controller' => 'settings', 'action' => 'types'), 'admin_default')));
  $this->form->getDecorator('Description')->setOption('escape', false);
?>
<div class='settings'>
<?php echo $this->form->render($this); ?>
</div>
