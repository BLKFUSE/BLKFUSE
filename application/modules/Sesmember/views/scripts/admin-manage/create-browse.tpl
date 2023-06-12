<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: create-browse.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesmember/views/scripts/dismiss_message.tpl';?>

<div>
  <?php echo $this->htmlLink(array('action' => 'manage-browsepage', 'reset' => false), $this->translate("Back to Browse Pages for Profile Types"),array('class' => 'buttonlink sesbasic_icon_back')) ?>
</div>
<br />

<div class='clear sesbasic_admin_form sesmember_custompage_form'>
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
