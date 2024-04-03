<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>

<?php 
  $isValid = false;
  if(isset($this->subject->resource_type) && $this->subject->resource_type){
    $item = Engine_Api::_()->getItem($this->subject->resource_type,$this->subject->resource_id);
    if($item)
      $isValid = true;
  }
  $dontShow = true;
  if($isValid && !Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.other.modulecontests', 0))
    $dontShow = false;
   
?>

<div class="sesbasic_breadcrumb">
    <?php if($dontShow): ?>
    <a href="<?php echo $this->url(array('action' => 'home'), 'sescontest_general'); ?>"><?php echo $this->translate("Contests Home"); ?></a>&nbsp;&raquo;
    <a href="<?php echo $this->url(array(), 'sescontest_general'); ?>"><?php echo $this->translate("Browse Contests"); ?></a>&nbsp;&raquo;
    <?php endif; ?>
   <?php if($this->subject->getType() == 'contest' && $isValid):?>
    <?php if($item): ?>
        <a href="<?php echo $item->getHref(); ?>"><?php echo $item->getTitle(); ?></a>&nbsp;&raquo;
    <?php endif; ?>
   <?php endif; ?>
  <?php if($this->subject->getType() == 'participant'):?>
    <?php $contest =  Engine_Api::_()->getItem('contest', $this->subject->contest_id);?>
    <a href="<?php echo $contest->getHref(); ?>"><?php echo $contest->getTitle(); ?></a>&nbsp;&raquo;
  <?php endif; ?>
  <?php echo !$this->subject->getTitle() ? $this->translate("Untitled"): $this->subject->getTitle(); ?>
</div>
