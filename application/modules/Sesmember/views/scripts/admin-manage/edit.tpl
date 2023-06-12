<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: edit.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesmember/views/scripts/dismiss_message.tpl';?>

<div>
  <?php echo $this->htmlLink(array('route' => 'admin_default','module' => 'sesmember','controller' => 'manage', 'action' => 'manage-page'), $this->translate("Back to Manage Member Home Pages"),array('class' => 'buttonlink sesbasic_icon_back')) ?>
</div>
<br />

<div class='clear sesbasic_admin_form sesmember_custompage_form'>
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
