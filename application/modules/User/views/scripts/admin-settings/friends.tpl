<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: friends.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     Sami
 */
?>
<?php echo $this->partial('_admin_breadcrumb.tpl', 'core', array('parentMenu' => "core_admin_main_settings", 'childMenuItemName' => 'core_admin_main_settings_friends')); ?>

<?php
  echo $this->navigation()
    ->menu()
    ->setContainer($this->navigation)
    ->setUlClass('admin_friends_tabs')
    ->render()
?>

<div class='settings'>
  <?php echo $this->form->render($this) ?>
</div>
