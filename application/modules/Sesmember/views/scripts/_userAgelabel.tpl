<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _userAgelabel.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $memberAge = '';?>
<?php $member = $this->member;?>
<?php if(isset($this->ageActive)): $age = 0; ?>  
  <?php  $getFieldsObjectsByAlias = Engine_Api::_()->fields()->getFieldsObjectsByAlias($member); 
  if (!empty($getFieldsObjectsByAlias['birthdate'])) {
    $optionId = $getFieldsObjectsByAlias['birthdate']->getValue($member);
    if ($optionId && @$optionId->value) {
      $age = floor((time() - strtotime($optionId->value)) / 31556926);
    }
  } ?>
   <?php if($age && $optionId->value): ?>
    <?php echo "<div class='sesmember_list_stats sesmember_age'><span><span class='_label'>AGE</span><span class='sesbasic_text_light'>".$age."</span></span></div>"; ?>
  <?php endif; ?>
<?php endif; ?>
